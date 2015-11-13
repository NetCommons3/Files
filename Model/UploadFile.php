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
									'dir' => 'path',
									'type' => 'mimetype',
									'size' => 'size'
							]
					),
			],
	];

	public function setOptions($options) {
		$this->uploadSettings('real_file_name', $options);
	}

	public function removeFile($contentId, $fileId) {
		$UploadFilesContents = ClassRegistry::init('Files.UploadFilesContents');
		$link = $UploadFilesContents->findByContentIdAndUploadFileId($contentId, $fileId);
		if($link){
			// 関連レコードみつかったら削除する
			$UploadFilesContents->delete($link['UploadFilesContents']['id'], false);
			// ファイルIDの関連テーブルが他に見つからなかったらファイルも削除する
			$count = $UploadFilesContents->find('count', ['conditions' => ['upload_file_id' => $fileId]]);
			if($count == 0){
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
		return Security::hash($currentName) . '.' . pathinfo($currentName, PATHINFO_EXTENSION);
	}

/**
 * beforeSave
 *
 * ファイルの保存先を設定
 *
 * @param array $options オプション
 * @return void
 */
	public function beforeSave($options = array()) {
		$roomId = Current::read('Room.id');
		$path = WWW_ROOT . DS . 'files' . DS . 'upload_file' . DS . 'real_file_name' . DS . $roomId . DS;
		$this->uploadSettings('real_file_name', 'path', $path);
		$this->uploadSettings('real_file_name', 'thumbnailPath', $path);
	}


/**
 * After Save
 *
 * @param bool $created 新規のときtrue
 * @param array $options オプション
 * @return void
 */
	public function afterSave($created, $options= array()) {
		// TODO UploadビヘイビアのaftereSave後に処理が必要なら実装する
	}

}


