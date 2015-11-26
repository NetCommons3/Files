<?php
/**
 * AttachmentBehavior
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

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
		// 先にOriginalKeyをロードしてもらう
		if (! $model->Behaviors->loaded('NetCommons.OriginalKey')) {
			$error = '"NetCommons.OriginalKeyBehavior" not loaded in ' . $model->alias . '. ';
			$error .= 'Load "NetCommons.OriginalKeyBehavior" before loading "AttachmentBehavior"';
			throw new CakeException($error);
		};

		foreach ($config as $filed => $options) {
			$this->uploadSettings($model, $filed, $options);
		}

		$this->UploadFile = ClassRegistry::init('Files.UploadFile');
		$this->UploadFilesContent = ClassRegistry::init('Files.UploadFilesContent');
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
		foreach ($results as $key => $content) {
			if (isset($content[$model->alias]['id'])) {
				$contentId = $content[$model->alias]['id'];
				$conditions = [
						'UploadFilesContent.plugin_key' => Inflector::underscore($model->plugin),
						'UploadFilesContent.content_id' => $contentId,
				];
				$uploadFiles = $this->UploadFilesContent->find('all', ['conditions' => $conditions]);
				foreach ($uploadFiles as $uploadFile) {
					$results[$key]['UploadFile'][$uploadFile['UploadFile']['field_name']] = $uploadFile['UploadFile'];
				}
			}
		}
		return $results;
	}

/**
 * Before save method. Called before all saves
 *
 * Handles setup of file uploads
 *
 * @param Model $model Model instance
 * @param array $options Options passed from Model::save().
 * @return bool
 */
	public function beforeSave(Model $model, $options = array()) {
		foreach ($this->_settings[$model->alias]['fileFields'] as $fieldName => $filedOptions) {

			if (isset($model->data[$model->alias][$fieldName])) {
				$fileData = $model->data[$model->alias][$fieldName];
				if ($fileData['name']) {
					$uploadFile = $this->UploadFile->create();
					$pathInfo = pathinfo($fileData['name']);
					$uploadFile['UploadFile']['plugin_key'] = Inflector::underscore($model->plugin);
					$keyField = Hash::get($filedOptions, 'contentKeyFieldName', 'key');
					$uploadFile['UploadFile']['content_key'] = $model->data[$model->alias][$keyField];
					$uploadFile['UploadFile']['field_name'] = $fieldName;
					$uploadFile['UploadFile']['original_name'] = $fileData['name'];
					$uploadFile['UploadFile']['extension'] = $pathInfo['extension'];
					$uploadFile['UploadFile']['real_file_name'] = $fileData;

					// フィールド毎にオプションを設定しなおしてsave実行
					$this->UploadFile->setOptions($filedOptions);
					// ε(　　　　 v ﾟωﾟ)　＜ 例外処理
					$this->_uploadedFiles[$fieldName] = $this->UploadFile->save($uploadFile);
				}
			}
		}

		return true;
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
		// アップロードがなかったら以前のデータを挿入する
		// formからhiddenで UploadFile.0.id 形式でデータが渡ってくる
		if (isset($model->data['UploadFile'])) {
			foreach ($model->data['UploadFile'] as $uploadFile) {
				// 同じfield_nameでアップロードされてるなら以前のファイルへの関連レコードは不要
				if (isset($this->_uploadedFiles[$uploadFile['field_name']])) {
					// 新たにアップロードされてる
				} else {
					// 同じfield_nameでアップロードされてなければ以前のファイルへの関連レコードを入れる
					//if (Hash::get($model->data[$model->alias][$uploadFile['field_name']], 'remove', false)) {
					if (Hash::get($model->data, $model->alias . '.' . $uploadFile['field_name'] . '.remove', false)) {
						// ファイル削除なのでリンクしない
						// 今のコンテンツIDで関連テーブルのレコードがあったら、ユーザモデルのように履歴のないモデルなのでそのときは関連テーブルを消す
						$this->UploadFile->removeFile($model->id, $uploadFile['id']);
					} else {
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
 * アップロードフィールドの設定
 *
 * @param Model $model モデル
 * @param string $filed フィールド名
 * @param array $options オプション
 * @return void
 */
	public function uploadSettings(Model $model, $filed, $options = array()) {
		if (is_int($filed)) {
			$filed = $options;
			$options = array();
		}
		$this->_settings[$model->alias]['fileFields'][$filed] = $options;
	}

/**
 * コンテンツとアップロードファイルの関連テーブルを保存
 *
 * @param Model $model モデル
 * @param int $uploadFileId アップロードファイルID
 * @return array
 */
	protected function _saveUploadFilesContent(Model $model, $uploadFileId) {
		$contentId = $model->data[$model->alias]['id'];
		$data = [
				'content_id' => $contentId,
				'upload_file_id' => $uploadFileId,
				'plugin_key' => Inflector::underscore($model->plugin),
		];
		$data = $this->UploadFilesContent->create($data);
		// ε(　　　　 v ﾟωﾟ)　＜ 例外処理
		$this->UploadFilesContent->save($data);
		return array($contentId, $data);
	}


	/**
	 * コンテンツに、物理ファイルを添付する処理
	 *
	 * @param Model $model
	 * @param $data
	 * @param $fieldName
	 * @param File $file
	 */
	public function attachFile(Model $model, $data, $fieldName, $file, $keyFiledName = 'key') {
		if(!is_a($file, 'File')){
			// $fileがpathのとき
			$file = new File($file);
		}
		// UploadFileへ登録
		$uploadFile = $this->UploadFile->registByFile($file,
				Inflector::underscore($model->plugin),
				$data[$model->alias][$keyFiledName],
				$fieldName
				);
		// 関連テーブル登録
		$this->UploadFile->makeLink($model->plugin, $data[$model->alias]['id'], $uploadFile['UploadFile']['id']);

	}

	// ===== 以下 UploadBehavior のバリデータをラップ ====

/**
 * Check that the file does not exceed the max
 * file size specified by PHP
 *
 * @param Model $model Model instance
 * @param mixed $check Value to check
 * @return bool Success
 */
	public function isUnderPhpSizeLimit(Model $model, $check) {
		return $this->UploadFile->isUnderPhpSizeLimit($check);
	}

/**
 * Check that the file does not exceed the max
 * file size specified in the HTML Form
 *
 * @param Model $model Model instance
 * @param mixed $check Value to check
 * @return bool Success
 */
	public function isUnderFormSizeLimit(Model $model, $check) {
		return $this->UploadFile->isUnderFormSizeLimit($check);
	}

/**
 * Check that the file was completely uploaded
 *
 * @param Model $model Model instance
 * @param mixed $check Value to check
 * @return bool Success
 */
	public function isCompletedUpload(Model $model, $check) {
		return $this->UploadFile->isCompletedUpload($check);
	}

/**
 * Check that a file was uploaded
 *
 * @param Model $model Model instance
 * @param mixed $check Value to check
 * @return bool Success
 */
	public function isFileUpload(Model $model, $check) {
		return $this->UploadFile->isFileUpload($check);
	}

/**
 * Check that either a file was uploaded,
 * or the existing value in the database is not blank.
 *
 * @param Model $model Model instance
 * @param mixed $check Value to check
 * @return bool Success
 */
	public function isFileUploadOrHasExistingValue(Model $model, $check) {
		return $this->UploadFile->isFileUploadOrHasExistingValue($check);
	}

/**
 * Check that the PHP temporary directory is missing
 *
 * @param Model $model Model instance
 * @param mixed $check Value to check
 * @param bool $requireUpload Whether or not to require a file upload
 * @return bool Success
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
	public function tempDirExists(Model $model, $check, $requireUpload = true) {
		return $this->UploadFile->tempDirExists($check, $requireUpload);
	}

/**
 * Check that the file was successfully written to the server
 *
 * @param Model $model Model instance
 * @param mixed $check Value to check
 * @param bool $requireUpload Whether or not to require a file upload
 * @return bool Success
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
	public function isSuccessfulWrite(Model $model, $check, $requireUpload = true) {
		return $this->UploadFile->isSuccessfulWrite($check, $requireUpload);
	}

/**
 * Check that a PHP extension did not cause an error
 *
 * @param Model $model Model instance
 * @param mixed $check Value to check
 * @param bool $requireUpload Whether or not to require a file upload
 * @return bool Success
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
	public function noPhpExtensionErrors(Model $model, $check, $requireUpload = true) {
		return $this->UploadFile->noPhpExtensionErrors($check, $requireUpload);
	}

/**
 * Check that the file is of a valid mimetype
 *
 * @param Model $model Model instance
 * @param mixed $check Value to check
 * @param array $mimetypes file mimetypes to allow
 * @param bool $requireUpload Whether or not to require a file upload
 * @return bool Success
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
	public function isValidMimeType(Model $model, $check, $mimetypes = array(), $requireUpload = true) {
		return $this->UploadFile->isValidMimeType($check, $mimetypes, $requireUpload);
	}

/**
 * Check that the upload directory is writable
 *
 * @param Model $model Model instance
 * @param mixed $check Value to check
 * @param bool $requireUpload Whether or not to require a file upload
 * @return bool Success
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
	public function isWritable(Model $model, $check, $requireUpload = true) {
		return $this->UploadFile->isWritable($check, $requireUpload);
	}

/**
 * Check that the upload directory exists
 *
 * @param Model $model Model instance
 * @param mixed $check Value to check
 * @param bool $requireUpload Whether or not to require a file upload
 * @return bool Success
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
	public function isValidDir(Model $model, $check, $requireUpload = true) {
		return $this->UploadFile->isValidDir($check, $requireUpload);
	}

/**
 * Check that the file is below the maximum file upload size
 *
 * @param Model $model Model instance
 * @param mixed $check Value to check
 * @param int $size Maximum file size
 * @param bool $requireUpload Whether or not to require a file upload
 * @return bool Success
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
	public function isBelowMaxSize(Model $model, $check, $size = null, $requireUpload = true) {
		return $this->UploadFile->isValidDir($check, $size, $requireUpload);
	}

/**
 * Check that the file is above the minimum file upload size
 *
 * @param Model $model Model instance
 * @param mixed $check Value to check
 * @param int $size Minimum file size
 * @param bool $requireUpload Whether or not to require a file upload
 * @return bool Success
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
	public function isAboveMinSize(Model $model, $check, $size = null, $requireUpload = true) {
		return $this->UploadFile->isAboveMinSize($check, $size, $requireUpload);
	}

/**
 * Check that the file has a valid extension
 *
 * @param Model $model Model instance
 * @param mixed $check Value to check
 * @param array $extensions file extenstions to allow
 * @param bool $requireUpload Whether or not to require a file upload
 * @return bool Success
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
	public function isValidExtension(Model $model, $check, $extensions = array(), $requireUpload = true) {
		return $this->UploadFile->isValidExtension($check, $extensions, $requireUpload);
	}

/**
 * Check that the file is above the minimum height requirement
 *
 * @param Model $model Model instance
 * @param mixed $check Value to check
 * @param int $height Height of Image
 * @param bool $requireUpload Whether or not to require a file upload
 * @return bool Success
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
	public function isAboveMinHeight(Model $model, $check, $height = null, $requireUpload = true) {
		return $this->UploadFile->isAboveMinHeight($check, $height, $requireUpload);
	}

/**
 * Check that the file is below the maximum height requirement
 *
 * @param Model $model Model instance
 * @param mixed $check Value to check
 * @param int $height Height of Image
 * @param bool $requireUpload Whether or not to require a file upload
 * @return bool Success
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
	public function isBelowMaxHeight(Model $model, $check, $height = null, $requireUpload = true) {
		return $this->UploadFile->isBelowMaxHeight($check, $height, $requireUpload);
	}

/**
 * Check that the file is above the minimum width requirement
 *
 * @param Model $model Model instance
 * @param mixed $check Value to check
 * @param int $width Width of Image
 * @param bool $requireUpload Whether or not to require a file upload
 * @return bool Success
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
	public function isAboveMinWidth(Model $model, $check, $width = null, $requireUpload = true) {
		return $this->UploadFile->isAboveMinWidth($check, $width, $requireUpload);
	}

/**
 * Check that the file is below the maximum width requirement
 *
 * @param Model $model Model instance
 * @param mixed $check Value to check
 * @param int $width Width of Image
 * @param bool $requireUpload Whether or not to require a file upload
 * @return bool Success
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
	public function isBelowMaxWidth(Model $model, $check, $width = null, $requireUpload = true) {
		return $this->UploadFile->isBelowMaxWidth($check, $width, $requireUpload);
	}

}
