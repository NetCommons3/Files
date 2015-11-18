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

/**
 * ダウンロード実行
 *
 * @param int $contentId コンテンツID
 * @param array $options オプション field : ダウンロードのフィールド名, size: nullならオリジナル thubm, small, medium, big
 * @return mixed
 */
	public function doDownload($contentId, $options = array()) {

		$fieldName = $this->controller->params['pass'][2];
		$size = Hash::get($this->controller->params['pass'], 3, null);

		$fieldName = Hash::get($options, 'field', $fieldName);
		$size = Hash::get($options, 'size', $size);


		// ファイル情報取得 plugin_keyとコンテンツID、フィールドの情報が必要
		$UploadFile = ClassRegistry::init('Files.UploadFile');
		$pluginKey = Inflector::underscore($this->controller->plugin);
		$file = $UploadFile->getFile($pluginKey, $contentId, $fieldName);
		// ルームチェック
		if($file['UploadFile']['room_id']){
			$roomId = Current::read('Room.id');
			if($file['UploadFile']['room_id'] != $roomId){
				throw new ForbiddenException();
			}
		}
		if($file['UploadFile']['block_key']){
			// block_keyによるガード
			$Block = ClassRegistry::init('Blocks.Block');
			$block = $Block->findByKeyAndLanguageId($file['UploadFile']['block_key'], Current::read('Language.id'));
			if($Block->isVisible($block) === false){
				throw new ForbiddenException();
			}
		}

		// size対応
		$filename = $file['UploadFile']['real_file_name'];
		if($size !== null){
			$filename = $size . '_' . $filename;
		}

		$filePath = WWW_ROOT . $file['UploadFile']['path'] . $file['UploadFile']['id'] . '/' . $filename ;

		$this->controller->response->file($filePath, array('name' => $file['UploadFile']['original_name']));
		return $this->controller->response;
	}
}
