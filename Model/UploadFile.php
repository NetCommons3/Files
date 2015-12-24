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

/**
 * Summary for File Model
 */

class UploadFile extends FilesAppModel {

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
							]
					),
			],
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
 * @return void
 */
	public function removeFile($contentId, $fileId) {
		$UploadFilesContents = ClassRegistry::init('Files.UploadFilesContents');
		$link = $UploadFilesContents->findByContentIdAndUploadFileId($contentId, $fileId);
		if ($link) {
			// 関連レコードみつかったら削除する
			$UploadFilesContents->delete($link['UploadFilesContents']['id'], false);
			// ファイルIDの関連テーブルが他に見つからなかったらファイルも削除する
			$count = $UploadFilesContents->find('count', ['conditions' => ['upload_file_id' => $fileId]]);
			if ($count == 0) {
				// 他に関連レコード無ければファイル削除
				$this->delete($fileId, false);
			}
		}
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
		// imagickクラスがなかったらサムネイル生成はGDを利用
		if (class_exists('imagick') === false) {
			$this->uploadSettings('real_file_name', 'thumbnailMethod', 'php');
		}

		$roomId = Current::read('Room.id');
		$path = WWW_ROOT . 'files' . DS . 'upload_file' . DS . 'real_file_name' . DS . $roomId . DS;

		// ID以外のpathを保存 WWW_ROOTも除外する
		$path = substr($path, strlen(WWW_ROOT));
		$this->data['UploadFile']['path'] = $path;

		$this->uploadSettings('real_file_name', 'path', $path);
		$this->uploadSettings('real_file_name', 'thumbnailPath', $path);

		// トータルダウンロード数設定
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
			$this->data['UploadFile']['total_download_count'] = ($result['UploadFile']['total'] !== null) ? $result['UploadFile']['total'] : 0;
		}
		unset($this->virtualFields['total']);
		return true;
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
			'conditions' => [
				'UploadFilesContent.plugin_key' => $pluginKey,
				'UploadFilesContent.content_id' => $contentId,
				'UploadFile.field_name' => $fieldName
			]
		];

		$UploadFilesContent = ClassRegistry::init('Files.UploadFilesContent');
		$file = $UploadFilesContent->find('first', $options);
		return $file;
	}

/**
 * ダウンロードカウントアップ
 *
 * @param array $data UploadFileデータ
 * @throws Exception
 * @return void
 */
	public function countUp($data) {
		$data[$this->alias]['download_count'] = $data[$this->alias]['download_count'] + 1;
		$data[$this->alias]['total_download_count'] = $data[$this->alias]['total_download_count'] + 1;
		// plugin_key, content_key, field_nameが同じだったら
		$this->create();
		$this->begin();
		$this->save($data, ['callbacks' => false]);
		$this->commit();
	}

/**
 * FileインスタンスのファイルをUplodFileに登録する
 *
 * @param File $file 登録するファイルのFileインスタンス
 * @param string $pluginKey プラグインキー
 * @param string $contentKey コンテンツキー
 * @param string $fieldName フィールド名
 * @return array
 * @throws Exception
 */
	public function registByFile(File $file, $pluginKey, $contentKey, $fieldName) {
		// データの登録
		$data = $this->create();
		// $dataにアサイン
		$data['UploadFile']['plugin_key'] = $pluginKey;
		$data['UploadFile']['content_key'] = $contentKey;
		$data['UploadFile']['field_name'] = $fieldName;
		$data['UploadFile']['original_name'] = $file->name;
		$data['UploadFile']['extension'] = pathinfo($file->name, PATHINFO_EXTENSION);
		$data['UploadFile']['real_file_name'] = [
			'name' => $file->name,
			'type' => $file->mime(),
			'tmp_name' => $file->path,
			'error' => 0,
			'size' => $file->size(),
		];
		$data = $this->save($data); // あれ？普通にsaveするとUploadビヘイビアが動く？

		return $data;
	}

/**
 * 指定されたパスのファイルをUploadFileに登録する
 *
 * このメソッドではコンテンツとの関連テーブルに関連レコードは挿入されないことに注意
 * コンテンツと関連づけたい場合はAttachmentBehavior::attachFile()推奨
 *
 * @param string $filePath 登録するファイルのファイルパス
 * @param string $pluginKey プラグインキー
 * @param string $contentKey コンテンツキー
 * @param string $fieldName フィールド名
 * @return array 登録されたUploadFileレコード
 */
	public function registByFilePath($filePath, $pluginKey, $contentKey, $fieldName) {
		$file = new File($filePath);
		return $this->registByFile($file, $pluginKey, $contentKey, $fieldName);
	}

/**
 * 関連テーブルにコンテンツとUploadFileとの関連データを作成
 *
 * @param string $pluginKey プラグインキー
 * @param int $contentId コンテンツID
 * @param int $uploadFileId アップロードファイルID
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
		// ε(　　　　 v ﾟωﾟ)　＜ 例外処理
		$UploadFilesContent->save($data);
	}

/**
 * 関連テーブルデータの削除
 *
 * @param string $pluginKey プラグインキー
 * @param int $contentId コンテンツID
 * @param string $fieldName フィールド名
 * @return void
 */
	public function deleteLink($pluginKey, $contentId, $fieldName) {
		$conditions = [
			'UploadFilesContent.plugin_key' => $pluginKey,
			'UploadFilesContent.content_id' => $contentId,
			'UploadFile.field_name' => $fieldName,
		];
		$result = $this->UploadFilesContent->find('all', ['conditions' => $conditions]);
		foreach ($result as $link) {
			$this->_deleteNoRelationUploadFile($link);
		}
	}

/**
 * 関連テーブルデータがひとつもないUploadFileレコードを削除する
 *
 * @param array $link UploadFilesContent関連レコードのデータ
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
			$this->delete($link['UploadFile']['id']);
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
}


