<?php
/**
 * FileUpload Component
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('Component', 'Controller');
App::uses('FileModel', 'Files.Model');

/**
 * FileUpload Component
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Files\Controller\Component
 */
class DownloadComponent extends Component {

	/**
	 * Called before the Controller::beforeFilter().
	 *
	 * @param Controller $controller Instantiating controller
	 * @return void
	 */
	public function initialize(Controller $controller) {
		$this->controller = $controller;
	}


	public function doDownload($contentId, $fieldName, $size = null) {

		// TODO ファイル情報取得 plugin_keyとコンテンツID、フィールドの情報が必要
		$UploadFile = ClassRegistry::init('Files.UploadFile');
		$pluginKey = Inflector::underscore($this->controller->plugin);
		$file = $UploadFile->getFile($pluginKey, $contentId, $fieldName);
		// TODO ルームチェック

		$roomId = Current::read('Room.id');
		// TODO size対応
		// TODO path Upload時に確定させる

		//$filePath = WWW_ROOT . 'files/upload_file/real_file_name/' . $roomId . '/' . $file['UploadFile']['id'] . '/' . $file['UploadFile']['real_file_name'];
		$filePath = WWW_ROOT . $file['UploadFile']['path'] . $file['UploadFile']['id'] . '/' .  $file['UploadFile']['real_file_name'];

		$this->controller->response->file($filePath, array('name' => $file['UploadFile']['original_name']));
		return $this->controller->response;

	}
}
