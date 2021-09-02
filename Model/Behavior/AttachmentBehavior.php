<?php
/**
 * AttachmentBehavior
 *
 * HACK: phpmd の ExcessiveClassComplexity ruleに引っかかる直前の状態なので、凝集度をみて分割したほうがよさそう
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

App::uses('ImageFileUtility', 'Files.Utility');

/**
 * Class AttachmentBehavior
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class AttachmentBehavior extends ModelBehavior {

/**
 * @var array モデル毎のAttachmentビヘイビア設定
 */
	protected $_settings = array();

/**
 * @var UploadFile $UploadFile UploadFile
 */
	public $UploadFile = null;

/**
 * @var UploadFilesContent 関連テーブルのモデル
 */
	public $UploadFilesContent = null;

/**
 * @var array アップロードされたファイル情報
 */
	protected $_uploadedFiles = array();

/**
 * SetUp Attachment behavior
 *
 * @param Model $model instance of model
 * @param array $config array of configuration settings.
 * @throws CakeException 先にOriginalKeyが登録されてないと例外
 * @return void
 */
	public function setup(Model $model, $config = array()) {
		//ビヘイビアの優先順位
		$this->settings['priority'] = 8;

		// 先にOriginalKeyをロードしてもらう
		if (! $model->Behaviors->loaded('NetCommons.OriginalKey')) {
			$error = '"NetCommons.OriginalKeyBehavior" not loaded in ' . $model->alias . '. ';
			$error .= 'Load "NetCommons.OriginalKeyBehavior" before loading "AttachmentBehavior"';
			throw new CakeException($error);
		}

		$this->UploadFile = ClassRegistry::init('Files.UploadFile');

		foreach ($config as $field => $options) {
			$this->uploadSettings($model, $field, $options);
		}

		$this->UploadFilesContent = ClassRegistry::init('Files.UploadFilesContent');

		$model->Behaviors->load('Files.UploadFileValidate');
		$model->Behaviors->load('Files.UploadValidatorWrap');
		$model->Behaviors->load('Files.DownloadCountUp');
	}

/**
 * After find callback. Can be used to modify any results returned by find.
 *
 * @param Model $model Model using this behavior
 * @param mixed $results The results of the find operation
 * @param bool $primary Whether this model is being queried directly (vs. being queried as an association)
 * @return mixed An array value will replace the value of $results - any other value will be ignored.
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
	public function afterFind(Model $model, $results, $primary = false) {
		// Recursiveと連動 @see https://github.com/NetCommons3/NetCommons3/issues/68
		if ($model->recursive < 0) {
			return $results;
		}

		$contentIdsWithIndex = array_filter(array_column(array_column($results, $model->alias), 'id'));

		if (count($contentIdsWithIndex) > 0) {
			$conditions = [
				'UploadFilesContent.plugin_key' => Inflector::underscore($model->plugin),
				'UploadFilesContent.content_id' => array_values($contentIdsWithIndex),
			];
			$uploadFiles = $this->UploadFilesContent->find('all', ['conditions' => $conditions]);
			foreach ($uploadFiles as $uploadFile) {
				$contentId = $uploadFile['UploadFilesContent']['content_id'];
				// $resultsに同じcontent_idのレコードが複数あることもありえるのでその全てのレコードに関連づける
				$keys = array_keys($contentIdsWithIndex, $contentId);
				$fieldName = $uploadFile['UploadFile']['field_name'];
				foreach ($keys as $key) {
					$results[$key]['UploadFile'][$fieldName] = $uploadFile['UploadFile'];
				}
			}
		}

		return $results;
	}

/**
 * before validate PHPのアップロードエラーがあったらvalidationErrorをセットする
 *
 * @param Model $model モデル
 * @param array $options オプション
 * @return void
 */
	public function beforeValidate(Model $model, $options = array()) {
		if (isset($this->_settings[$model->alias]['fileFields'])) {
			foreach (array_keys($this->_settings[$model->alias]['fileFields']) as $fieldName) {
				if (isset($model->data[$model->alias][$fieldName])) {
					$fileData = $model->data[$model->alias][$fieldName];
					// php upload errorだったらvalidationerrorにする
					if (isset($fileData['error']) &&
						$fileData['error'] !== UPLOAD_ERR_OK &&
						$fileData['error'] !== UPLOAD_ERR_NO_FILE) {
						$model->validationErrors[$fieldName][] =
							__d('files', 'Failed uploading file.');
					}
				}
			}
		}
	}

/**
 * beforeSave
 * 元モデルのデータに返す値をセットする
 *
 * @param Model $model Model
 * @param array $options Options
 * @return mixed
 */
	public function beforeSave(Model $model, $options = array()) {
		$this->beforeSaveByAttachment($model, $options);
		return parent::beforeSave($model);
	}

/**
 * beforeSave
 * 元モデルのデータに返す値をセットする
 *
 * @param Model $model Model
 * @param array $options Options
 * @return void
 */
	public function beforeSaveByAttachment(Model $model, $options = array()) {
		foreach ($this->_settings[$model->alias]['fileFields'] as $fieldName => $fieldOptions) {
			if (isset($model->data[$model->alias][$fieldName])) {
				$fileData = $model->data[$model->alias][$fieldName];
				// $fileData['error'] があったら処理中止。バリデーションエラーにする。
				if (!empty($fileData['name'])) {
					// 元データにファイル名フィールドが定義されてたら埋める
					$fileNameFieldName = Hash::get($fieldOptions, 'fileNameFieldName');
					if ($fileNameFieldName) {
						$model->data[$model->alias][$fileNameFieldName] =
							$fileData['name'];
					}
					// サイズフィールドをうめる
					$sizeFieldName = Hash::get($fieldOptions, 'sizeFieldName');
					if ($sizeFieldName) {
						$model->data[$model->alias][$sizeFieldName] =
							$fileData['size'];
					}
				}
			}
		}
	}

/**
 * afterSave
 *
 * @param Model $model モデル
 * @param bool $created 新規作成
 * @param array $options オプション
 * @throws Exception
 * @return void
 */
	public function afterSave(Model $model, $created, $options = array()) {
		$this->afterSaveByAttachment($model, $created, $options);
	}

/**
 * afterSave
 *
 * @param Model $model モデル
 * @param bool $created 新規作成
 * @param array $options オプション
 * @throws InternalErrorException
 * @return void
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 */
	public function afterSaveByAttachment(Model $model, $created, $options = array()) {
		$this->_uploadedFiles = array();
		foreach ($this->_settings[$model->alias]['fileFields'] as $fieldName => $fieldOptions) {
			if (isset($model->data[$model->alias][$fieldName])) {
				$fileData = $model->data[$model->alias][$fieldName];

				if (!empty($fileData['name'])) {
					$uploadFile = $this->UploadFile->create();
					$pathInfo = pathinfo($fileData['name']);
					$uploadFile['UploadFile']['plugin_key'] = Inflector::underscore($model->plugin);
					$keyField = Hash::get($fieldOptions, 'contentKeyFieldName', 'key');
					$uploadFile['UploadFile']['content_key'] = $model->data[$model->alias][$keyField];
					$uploadFile['UploadFile']['field_name'] = $fieldName;
					$uploadFile['UploadFile']['original_name'] = $fileData['name'];
					$uploadFile['UploadFile']['extension'] = $pathInfo['extension'];
					$uploadFile['UploadFile']['real_file_name'] = $fileData;

					//MIMEタイプがimageではじまってなかったらサムネイルはつくらない
					$fieldOptions['thumbnails'] = ImageFileUtility::isImageByFilePath($fileData['tmp_name']);
					// フィールド毎にオプションを設定しなおしてsave実行
					$this->UploadFile->setOptions($fieldOptions);
					// ε(　　　　 v ﾟωﾟ)　＜ 例外処理
					$saveResult = $this->UploadFile->save($uploadFile);
					if ($saveResult) {
						$this->_uploadedFiles[$fieldName] = $saveResult;
					} else {
						$errorMessage = 'UploadFile::save() Failed. fieldName=' . $fieldName;
						$errorMessage .= "\n";
						$errorMessage .= "uploadFile:\n";
						$errorMessage .= var_export($uploadFile, true);
						$errorMessage .= "\n";
						$errorMessage .= "validationErrors:\n";
						$errorMessage .= var_export($this->UploadFile->validationErrors, true);
						$this->log($errorMessage, LOG_DEBUG);
						throw new InternalErrorException($errorMessage);
					}
				}
			}
		}

		// アップロードがなかったら以前のデータを挿入する
		// formからhiddenで UploadFile.field_name.id 形式でデータが渡ってくる
		// $data['UploadFile']にはモデルデータ編集時に添付されてるファイルについてのデータが入っている
		if (isset($model->data['UploadFile'])) {
			$uploadFiles = $model->data['UploadFile'];
		} else {
			$uploadFiles = [];
		}
		foreach ($uploadFiles as $uploadFile) {
			// 同じfield_nameでアップロードされてるなら以前のファイルへの関連レコードを
			// 新規に追加する必要は無い（過去の関連レコードはそのまま）
			if (isset($this->_uploadedFiles[$uploadFile['field_name']])) {
				// 新たにアップロードされてる
				// 履歴のないモデル（is_latest, is_activeカラムがない）だったら、以前のファイルを削除する
				// 履歴のないモデルか？
				if (!$model->hasField('is_latest')) {
					// 履歴をもたないモデルなら以前のファイルを削除
					$this->UploadFile->removeFile($model->id, $uploadFile['id']);
				}
			} else {
				// 同じfield_nameでアップロードされてなければ以前のファイルへの関連レコードを入れる
				$removePath = $model->alias . '.' . $uploadFile['field_name'] . '.remove';
				if (Hash::get($model->data, $removePath, false)) {
					// ファイル削除にチェックが入ってるのでリンクしない
					// 今のコンテンツIDで関連テーブルのレコードがあったら、ユーザモデルのように履歴のないモデルなので
					// そのときは関連テーブルを消す必要があるのでremoveFileは呼んでおく。
					$this->UploadFile->removeFile($model->id, $uploadFile['id']);
				} else {
					// 履歴なし&アップロードされなかった　なら　関連レコードの追加はしない（idに変更なければ追加する必要がない）
					if ($model->hasField('is_latest')) {
						$uploadFileId = $uploadFile['id'];
						$this->_saveUploadFilesContent($model, $uploadFileId);
					}
				}
			}
		}

		// 関連テーブルの挿入
		foreach ($this->_uploadedFiles as $uploadedFile) {
			$uploadFileId = $uploadedFile['UploadFile']['id'];
			$this->_saveUploadFilesContent($model, $uploadFileId);
		}
	}

/**
 * afterSaveでUploadFileテーブルに登録した結果を返す。
 *
 * @param Model $model 元モデル
 * @return array
 */
	public function getUploadedFiles(Model $model) {
		return $this->_uploadedFiles;
	}

/**
 * After delete
 *
 * @param Model $model 元モデル
 * @return void
 */
	public function afterDelete(Model $model) {
		// afterDeleteだと$model->idで消したデータのIDがとれるだけ
		$contentId = $model->id;
		$this->UploadFile->deleteLink($model->plugin, $contentId);
	}

/**
 * content_keyからUploadFile, UploadFilesContent, および実ファイルの削除をおこなう。
 *
 * ファイルを添付する元コンテンツの削除時に呼び出してください。
 *
 * @param Model $model Model
 * @param array $contentKeys content_keyリスト
 * @return bool
 */
	public function deleteUploadFileByContentKeys(Model $model, array $contentKeys) {
		// ファイルフィールドが未定義ならなにもしない
		if (!isset($this->_settings[$model->alias]['fileFields'])) {
			return true;
		}

		$fields = array_keys($this->_settings[$model->alias]['fileFields']);
		$pluginKey = Inflector::underscore($model->plugin);

		$uploadFiles = $this->UploadFile->find(
			'all',
			[
				'conditions' => [
					'UploadFile.plugin_key' => $pluginKey,
					'UploadFile.content_key' => $contentKeys,
					'UploadFile.field_name' => $fields
				],
				'fields' => ['UploadFile.id'],
			]
		);
		$fileIds = [];
		foreach ($uploadFiles as $uploadFile) {
			$fileId = $uploadFile['UploadFile']['id'];
			if (!$this->UploadFile->deleteUploadFile($fileId)) {
				return false;
			}
			$fileIds[] = $fileId;

		}
		// 関連テーブルのデータも削除
		$result = $this->UploadFilesContent->deleteAll(
			[
				'UploadFilesContent.upload_file_id' => $fileIds,
			],
			false,
			false
		);

		return (bool)$result;
	}

/**
 * アップロードフィールドの設定
 *
 * @param Model $model モデル
 * @param string $field フィールド名
 * @param array $options オプション
 * @return void
 */
	public function uploadSettings(Model $model, $field, $options = array()) {
		if (is_int($field)) {
			$field = $options;
			$options = array();
		}
		$this->_settings[$model->alias]['fileFields'][$field] = $options;
		//
		$model->validate[$field]['size'] =
			[
				'rule' => ['validateRoomFileSizeLimit']
			];

		// 元モデルに拡張子バリデータをセットする
		$uploadAllowExtension = $this->UploadFile->getAllowExtension();
		$model->validate[$field]['extension'] = [
			// システム設定の値をとってくる。trimすること
			'rule' => ['isValidExtension', $uploadAllowExtension, false],
			'message' => __d('files', 'It is upload disabled file format')
		];
		$model->validate[$field]['size'] = [
			'rule' => 'validateRoomFileSizeLimit',
		];

		// removeをファイルアップロードと同時指定しないようにするバリデータ
		$model->validate[$field]['remove'] = [
			'rule' => ['validateRemoveWithoutUploading'],
			'message' => __d('files', 'Cannot attach and delete a file at the same time.')
		];
	}

/**
 * removeUploadSettings
 *
 * @param Model $model Model 元モデル
 * @param string $field フィールド名
 * @return void
 */
	public function removeUploadSettings(Model $model, $field) {
		// バリデーション削除
		unset($model->validate[$field]['extension']);
		unset($model->validate[$field]['size']);
		// _settings削除
		unset($this->_settings[$model->alias]['fileFields'][$field]);
	}

/**
 * コンテンツに、物理ファイルを添付する処理
 *
 * @param Model $model 元モデル
 * @param array $data コンテンツデータ
 * @param string $fieldName 添付するフィールド名
 * @param File|string $file 添付するファイルのFileインスタンスかファイルパス
 * @param string $keyFieldName コンテンツキーのフィールド名 省略可能 デフォルト key
 * @return void
 */
	public function attachFile(Model $model, $data, $fieldName, $file, $keyFieldName = 'key') {
		if (!is_a($file, 'File')) {
			// $fileがpathのとき
			$file = new File($file);
		}

		$pluginKey = Inflector::underscore($model->plugin);
		$contentKey = $data[$model->alias][$keyFieldName];
		$contentId = $data[$model->alias]['id'];

		$this->UploadFile->attach($pluginKey, $contentKey, $contentId, $fieldName, $file);
	}

/**
 * Attachmentビヘイビアで添付されたファイルのパスを返す
 *
 * @param Model $model Model
 * @param array $uploadFileData UploadFile Data Attachmentビヘイビアで取得される形式
 * @param string $fieldName フィールド名
 * @return string ファイルパス
 */
	public function getRealFilePath(Model $model, $uploadFileData, $fieldName) {
		$data = array();
		$data['UploadFile'] = $uploadFileData['UploadFile'][$fieldName];
		return $this->UploadFile->getRealFilePath($data);
	}

/**
 * コンテンツとアップロードファイルの関連テーブルを保存
 *
 * HACK: UploadFilesContentモデルに移動させたほうがよいと思われる。
 *
 * @param Model $model モデル
 * @param int $uploadFileId アップロードファイルID
 * @return array
 * @throws InternalErrorException
 */
	protected function _saveUploadFilesContent(Model $model, $uploadFileId) {
		if ($this->UploadFile->exists($uploadFileId) === false) {
			// 関連づけようとしているUploadFileレコードが存在しなかったら例外なげる
			//（履歴なしタイプの同時編集で発生しうる）
			throw new InternalErrorException('UploadFile Record Not Found');
		}

		$contentId = $model->data[$model->alias]['id'];
		$contentIsActive = Hash::get($model->data[$model->alias], 'is_active', null);
		$contentIsLatest = Hash::get($model->data[$model->alias], 'is_latest', null);
		$data = [
			'content_id' => $contentId,
			'content_is_active' => $contentIsActive,
			'content_is_latest' => $contentIsLatest,
			'upload_file_id' => $uploadFileId,
			'plugin_key' => Inflector::underscore($model->plugin),
		];
		$data = $this->UploadFilesContent->create($data);
		// ε(　　　　 v ﾟωﾟ)　＜ 例外処理
		$this->UploadFilesContent->save($data);
		return array($contentId, $data);
	}

}
