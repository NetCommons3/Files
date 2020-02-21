<?php
/**
 * Attachment
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

App::uses('FilesAppModel', 'Files.Model');
App::uses('Folder', 'Utility');

//app/webroot/index.phpでセットするため、不要だが、念のため入れておく。
if (!defined('UPLOADS_ROOT')) {
	if (file_exists(dirname(WWW_ROOT) . DS . 'Uploads' . DS)) {
		define('UPLOADS_ROOT', dirname(WWW_ROOT) . DS . 'Uploads' . DS);
	} else {
		define('UPLOADS_ROOT', WWW_ROOT);
	}
}

/**
 * Summary for File Model
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class UploadFile extends FilesAppModel {

/**
 * @var string UploadFileでアップロードする基準パス
 */
	public $uploadBasePath = UPLOADS_ROOT;

/**
 * アップロードファイルを登録する際、
 * アップロードディレクトリのパスがWEB_ROOTパス配下になるのでUPLOADS_ROOTにchdirする必要があるため、
 * 処理が終わったら元に戻すように現在のパスを保持しておく用の変数
 *
 * @var string UploadFileでアップロードする基準パス
 */
	private $__currentDir = null;

/**
 * @var int recursiveはデフォルトアソシエーションなしに
 */
	public $recursive = -1;

/**
 * ビヘイビア
 *
 * @var array
 */
	public $actsAs = [
		'Files.UploadFileDisableThumbnail',
		'Upload.Upload' => [
			'real_file_name' => array(
					'thumbnailSizes' => array(
						// NC2 800 , 640, 480だった
							'big' => '800ml',
							'medium' => '400ml',
							'small' => '200ml',
							'thumb' => '80x80',
					),
					'nameCallback' => 'nameCallback',
					'fields' => [
							//'dir' => 'path',
							'type' => 'mimetype',
							'size' => 'size'
					],
					'deleteFolderOnDelete' => true,
			),
		],
		'Files.UploadFileValidate',
	];

/**
 * hasMany
 *
 * @var array
 */
	public $hasMany = [
			'UploadFilesContent' => array(
					'className' => 'Files.UploadFilesContent',
			),
	];

/**
 * beforeValidate
 *
 * @param array $options options
 * @return bool
 */
	public function beforeValidate($options = array()) {
		// 拡張子チェック
		$uploadAllowExtension = $this->getAllowExtension();
		$this->validate['real_file_name']['extension'] = [
			'rule' => ['isValidExtension', $uploadAllowExtension, false],
			'message' => __d('files', 'It is upload disabled file format')
		];
		$this->validate['real_file_name']['size'] = 'validateRoomFileSizeLimit';

		return parent::beforeValidate($options);
	}

/**
 * ビヘイビア設定
 *
 * @param array $options オプション
 * @return void
 */
	public function setOptions($options) {
		$this->uploadSettings('real_file_name', $options);
	}

/**
 * アップロードファイルとコンテンツとの関連づけ削除
 *
 * @param int $contentId コンテンツID
 * @param int $fileId アップロードファイルID
 * @throws InternalErrorException
 * @return void
 */
	public function removeFile($contentId, $fileId) {
		if (! $contentId || ! $fileId) {
			return;
		}
		$UploadFilesContent = ClassRegistry::init('Files.UploadFilesContent');
		$link = $UploadFilesContent->findByContentIdAndUploadFileId($contentId, $fileId);
		if ($link) {
			// 関連レコードみつかったら削除する
			if ($UploadFilesContent->delete($link['UploadFilesContent']['id'], false) === false) {
				throw new InternalErrorException('Failed UploadFile::removeFile()');
			}
			// ファイルIDの関連テーブルが他に見つからなかったらファイルも削除する
			$count = $UploadFilesContent->find('count', ['conditions' => ['upload_file_id' => $fileId]]);
			if ($count == 0) {
				// 他に関連レコード無ければファイル削除
				if ($this->deleteUploadFile($fileId) === false) {
					throw new InternalErrorException('Failed UploadFile::removeFile()');
				}
			}
		}
	}

/**
 * Delete uploadFile
 *
 * @param int $fileId UploadFile.id
 * @return bool
 */
	public function deleteUploadFile($fileId) {
		// Uploadビヘイビアにpathを渡す
		$uploadFile = $this->findById($fileId);
		if (! $uploadFile) {
			//データがない場合、既に削除済みとしてtrueを返す。
			return true;
		}

		if (file_exists($this->getRealFilePath($uploadFile))) {
			$path = $this->uploadBasePath . $uploadFile['UploadFile']['path'];
			$this->uploadSettings('real_file_name', 'path', $path);
			$this->uploadSettings('real_file_name', 'thumbnailPath', $path);
			$result = $this->delete($fileId, false);
		} else {
			$this->Behaviors->disable('Upload.Upload');
			$result = $this->delete($fileId, false);
			$this->Behaviors->enable('Upload.Upload');
		}

		return $result;
	}

/**
 * nameCallback method
 *
 * @param string $field Name of field being modified
 * @param string $currentName current filename
 * @param array $data Array of data being manipulated in the current request
 * @param array $options Array of options for the current rename
 * @return string file name
 */
	public function nameCallback($field, $currentName, $data, $options) {
		//return Security::hash(mt_rand() . microtime(), 'md5')
		return Security::hash($currentName, 'md5') . '.' . pathinfo($currentName, PATHINFO_EXTENSION);
	}

/**
 * beforeSave
 *
 * ファイルの保存先を設定
 * トータルダウンロード数を設定
 *
 * @param array $options オプション
 * @return void
 */
	public function beforeSave($options = array()) {
		$this->__currentDir = getcwd();
		chdir($this->uploadBasePath);

		// imagickクラスがなかったらサムネイル生成はGDを利用
		if (class_exists('imagick') === false) {
			// @codeCoverageIgnoreStart
			$this->uploadSettings('real_file_name', 'thumbnailMethod', '_resizePhp');
			// @codeCoverageIgnoreEnd
		}

		$path = $this->uploadBasePath . 'files' . DS . 'upload_file' . DS;
		if (!empty($this->data['UploadFile']['room_id'])) {
			$roomId = $this->data['UploadFile']['room_id'];
		} else {
			$roomId = Current::read('Room.id');
		}
		if ($roomId) {
			$path .= 'real_file_name' . DS . $roomId . DS;
		} else {
			$path .= $this->data['UploadFile']['field_name'] . DS;
		}

		// ID以外のpathを保存 WWW_ROOTも除外する
		$path = substr($path, strlen($this->uploadBasePath));
		$this->data['UploadFile']['path'] = $path;

		$this->uploadSettings('real_file_name', 'path', $path);
		$this->uploadSettings('real_file_name', 'thumbnailPath', $path);

		// トータルダウンロード数設定
		if ($this->data['UploadFile']['content_key']) {
			$this->virtualFields['total'] = 'sum(download_count)';
			$options = [
				'fields' => ['total'],
				'conditions' => [
					'plugin_key' => $this->data['UploadFile']['plugin_key'],
					'content_key' => $this->data['UploadFile']['content_key'],
					'field_name' => $this->data['UploadFile']['field_name'],
				]
			];
			if (Hash::get($this->data, 'UploadFile.id', false) === false) {
				// 新規の時だけトータルをセット
				$result = $this->find('first', $options);
				$total = ($result['UploadFile']['total'] !== null) ? $result['UploadFile']['total'] : 0;
				$this->data['UploadFile']['total_download_count'] = $total;
			}
			unset($this->virtualFields['total']);
		}

		return true;
	}

/**
 * save()実行後に呼ばれるメソッド
 *
 * @param bool $created 新しいレコードが作成された場合はTrue
 * @param array $options Model::save()のオプション
 * @return void
 * @link http://book.cakephp.org/2.0/ja/models/callback-methods.html#aftersave
 * @see Model::save()
 */
	public function afterSave($created, $options = array()) {
		chdir($this->__currentDir);
		parent::afterSave($created, $options);
	}

/**
 * ファイル情報取得
 *
 * @param string $pluginKey プラグインキー
 * @param int $contentId コンテンツID
 * @param string $fieldName フィールド名
 * @return array|false
 */
	public function getFile($pluginKey, $contentId, $fieldName) {
		$options = [
			'fields' => [
				'UploadFilesContent.id',
				'UploadFilesContent.plugin_key',
				'UploadFilesContent.content_id',
				'UploadFilesContent.upload_file_id',
				'UploadFilesContent.content_is_active',
				'UploadFilesContent.content_is_latest',
				'UploadFile.id',
				'UploadFile.plugin_key',
				'UploadFile.content_key',
				'UploadFile.field_name',
				'UploadFile.original_name',
				'UploadFile.path',
				'UploadFile.real_file_name',
				'UploadFile.extension',
				'UploadFile.mimetype',
				'UploadFile.size',
				'UploadFile.download_count',
				'UploadFile.total_download_count',
				'UploadFile.room_id',
				'UploadFile.block_key'
			],
			'conditions' => [
				'UploadFilesContent.plugin_key' => $pluginKey,
				'UploadFilesContent.content_id' => $contentId,
				'UploadFile.field_name' => $fieldName
			],
			'order' => ['UploadFile.id' => 'desc']
		];

		$UploadFilesContent = ClassRegistry::init('Files.UploadFilesContent');
		$file = $UploadFilesContent->find('first', $options);
		return $file;
	}

/**
 * ファイル情報取得
 *
 * アバターなどは、UploadFilesContentを見る必要がないので、ContentKeyでファイル情報を取得できるようにする
 *
 * @param string $pluginKey プラグインキー
 * @param string $contentKey コンテンツキー
 * @param string $fieldName フィールド名
 * @return array|false
 */
	public function getFileByContentKey($pluginKey, $contentKey, $fieldName) {
		$options = [
			'fields' => [
				'UploadFile.id',
				'UploadFile.plugin_key',
				'UploadFile.content_key',
				'UploadFile.field_name',
				'UploadFile.original_name',
				'UploadFile.path',
				'UploadFile.real_file_name',
				'UploadFile.extension',
				'UploadFile.mimetype',
				'UploadFile.size',
				'UploadFile.download_count',
				'UploadFile.total_download_count',
				'UploadFile.room_id',
				'UploadFile.block_key'
			],
			'conditions' => [
				'UploadFile.plugin_key' => $pluginKey,
				'UploadFile.content_key' => $contentKey,
				'UploadFile.field_name' => $fieldName
			],
			'order' => ['UploadFile.id' => 'desc']
		];

		$file = $this->find('first', $options);
		return $file;
	}

/**
 * ダウンロードカウントアップ
 *
 * @param array $data UploadFileデータ
 * @throws InternalErrorException
 * @return void
 */
	public function countUp($data) {
		$data[$this->alias]['download_count'] = $data[$this->alias]['download_count'] + 1;
		$data[$this->alias]['total_download_count'] = $data[$this->alias]['total_download_count'] + 1;
		// plugin_key, content_key, field_nameが同じだったら
		$this->create();
		$this->begin();
		$result = $this->save($data, ['callbacks' => false, 'validate' => false]);
		if ($result === false) {
			throw new InternalErrorException('Failed UploadFile::countUp()');
		}

		$isWysiwyg = 'wysiwyg' == $data[$this->alias]['plugin_key'];

		if ($isWysiwyg) // wysiwyg の時はCDN cache を invalidate しない
			$this->invalidateCDN = false;

		$this->commit();

		if ($isWysiwyg)
			$this->invalidateCDN = true;

		return $result;
	}

/**
 * FileインスタンスのファイルをUplodFileに登録する
 *
 * @param File|string $file 登録するファイルのFileインスタンス OR ファイルパス
 * @param string $pluginKey プラグインキー
 * @param string $contentKey コンテンツキー
 * @param string $fieldName フィールド名
 * @param array $data データ登録時に上書きしたいデータを渡す
 * @return array
 * @throws InternalErrorException
 */
	public function registByFile(File $file, $pluginKey, $contentKey, $fieldName, $data = array()) {
		if (is_string($file)) {
			$file = new File($file);
		}

		// データの登録
		$_tmpData = $this->create();
		// $dataにアサイン
		$_tmpData['UploadFile']['plugin_key'] = $pluginKey;
		$_tmpData['UploadFile']['content_key'] = $contentKey;
		$_tmpData['UploadFile']['field_name'] = $fieldName;
		$originalName = property_exists($file, 'originalName') ? $file->originalName : $file->name;
		$_tmpData['UploadFile']['original_name'] = $originalName;
		$_tmpData['UploadFile']['extension'] = pathinfo($file->name, PATHINFO_EXTENSION);
		$_tmpData['UploadFile']['real_file_name'] = [
			'name' => $file->name,
			'type' => $file->mime(),
			'tmp_name' => $file->path,
			'error' => 0,
			'size' => $file->size(),
		];
		$data = Hash::merge($_tmpData, $data);
		$data = $this->save($data);
		if ($data === false) {
			throw new InternalErrorException('Failed UploadFile::registByFile()');
		}

		return $data;
	}

/**
 * 関連テーブルにコンテンツとUploadFileとの関連データを作成
 *
 * @param string $pluginKey プラグインキー
 * @param int $contentId コンテンツID
 * @param int $uploadFileId アップロードファイルID
 * @throws InternalErrorException
 * @return void
 */
	public function makeLink($pluginKey, $contentId, $uploadFileId) {
		$data = [
				'content_id' => $contentId,
				'upload_file_id' => $uploadFileId,
				'plugin_key' => $pluginKey,
		];
		$UploadFilesContent = ClassRegistry::init('Files.UploadFilesContent');
		$data = $UploadFilesContent->create($data);
		if ($UploadFilesContent->save($data) === false) {
			throw new InternalErrorException('Failed UploadFile::makeLink()');
		}
	}

/**
 * 関連テーブルデータの削除
 *
 * @param string $pluginKey プラグインキー
 * @param int $contentId コンテンツID
 * @param string $fieldName フィールド名
 * @return void
 */
	public function deleteLink($pluginKey, $contentId, $fieldName = null) {
		if (! $pluginKey || ! $contentId) {
			return;
		}

		$conditions = [
			'UploadFilesContent.plugin_key' => $pluginKey,
			'UploadFilesContent.content_id' => $contentId,
		];
		if ($fieldName !== null) {
			$conditions['UploadFile.field_name'] = $fieldName;
		}
		$result = $this->UploadFilesContent->find('all', ['conditions' => $conditions]);
		foreach ($result as $link) {
			$this->_deleteNoRelationUploadFile($link);
		}
	}

/**
 * 関連テーブルデータがひとつもないUploadFileレコードを削除する
 *
 * @param array $link UploadFilesContent関連レコードのデータ
 * @throws InternalErrorException
 * @return void
 */
	protected function _deleteNoRelationUploadFile($link) {
		$this->UploadFilesContent->delete($link['UploadFilesContent']['id']);
		// このリンク以外にリンクがなければUploadFileレコードを削除する
		$conditions = [
				'UploadFilesContent.upload_file_id' => $link['UploadFile']['id'],

		];
		$count = $this->UploadFilesContent->find('count', ['conditions' => $conditions]);
		if ($count == 0) {
			if ($this->deleteUploadFile($link['UploadFile']['id']) === false) {
				throw new InternalErrorException('Failed UploadFile::_deleteNoRelationUploadFile');
			};
		}
	}

/**
 * 添付ファイルをDBに登録する
 *
 * @param string $pluginKey 登録するプラグイン名
 * @param string $contentKey コンテンツキー
 * @param int $contentId コンテンツID
 * @param string $fieldName フィールド名
 * @param File $file 登録するファイルのFileインスタンス
 *
 * @return void
 */
	public function attach($pluginKey, $contentKey, $contentId, $fieldName, $file) {
		// UploadFileへ登録
		$uploadFile = $this->registByFile(
				$file,
				$pluginKey,
				$contentKey,
				$fieldName
		);
		// 以前の添付ファイルとの関連を切る。
		$this->deleteLink($pluginKey, $contentId, $fieldName);
		// 関連テーブル登録
		$this->makeLink($pluginKey, $contentId, $uploadFile['UploadFile']['id']);
	}

/**
 * UploadFileに登録済みの実ファイルのパスを返す
 *
 * @param array $uploadFileData UploadFile
 * @return string 実ファイルパス
 */
	public function getRealFilePath($uploadFileData) {
		$filePath = $this->uploadBasePath .
			$uploadFileData['UploadFile']['path'] .
			$uploadFileData['UploadFile']['id'] .
			DS . $uploadFileData['UploadFile']['real_file_name'];
		return $filePath;
	}
}
