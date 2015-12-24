<?php
/**
 * DownloadComponent
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

App::uses('Component', 'Controller');

/**
 * Class DownloadComponent
 */
class DownloadComponent extends Component {

/**
 * @var Controller コントローラ
 */
	protected $_controller = null;

/**
 * Called before the Controller::beforeFilter().
 *
 * @param Controller $controller Instantiating controller
 * @return void
 */
	public function initialize(Controller $controller) {
		$this->_controller = $controller;
	}

/**
 * ダウンロード実行
 *
 * @param int $contentId コンテンツID
 * @param array $options オプション field : ダウンロードのフィールド名, size: nullならオリジナル thumb, small, medium, big
 * @return CakeResponse|null
 * @throws ForbiddenException
 */
	public function doDownload($contentId, $options = array()) {
		$fieldName = $this->_controller->params['pass'][2];
		$size = Hash::get($this->_controller->params['pass'], 3, null);

		$fieldName = Hash::get($options, 'field', $fieldName);
		$size = Hash::get($options, 'size', $size);

		// ファイル情報取得 plugin_keyとコンテンツID、フィールドの情報が必要
		$UploadFile = ClassRegistry::init('Files.UploadFile');
		$pluginKey = Inflector::underscore($this->_controller->plugin);
		$file = $UploadFile->getFile($pluginKey, $contentId, $fieldName);
		// ルームチェック
		if ($file['UploadFile']['room_id']) {
			$roomId = Current::read('Room.id');
			if ($file['UploadFile']['room_id'] != $roomId) {
				throw new ForbiddenException();
			}
		}
		if ($file['UploadFile']['block_key']) {
			// block_keyによるガード
			$Block = ClassRegistry::init('Blocks.Block');
			$uploadFileBlock = $Block->findByKeyAndLanguageId($file['UploadFile']['block_key'], Current::read('Language.id'));
			if ($Block->isVisible($uploadFileBlock) === false) {
				throw new ForbiddenException();
			}
		}

		// size対応
		$filename = $file['UploadFile']['real_file_name'];
		if ($size !== null) {
			$filename = $size . '_' . $filename;
		}

		$filePath = $this->_getFilePath($file, $filename);

		$this->_controller->response->file($filePath, array('name' => $file['UploadFile']['original_name']));

		// Download カウントアップ
		$UploadFile->countUp($file);

		return $this->_controller->response;
	}

	/**
	 * @param $file
	 * @param $filename
	 * @return string
	 */
	protected function _getFilePath($file, $filename) {
		$filePath = WWW_ROOT . $file['UploadFile']['path'] . $file['UploadFile']['id'] . DS . $filename;
		return $filePath;
	}
}
