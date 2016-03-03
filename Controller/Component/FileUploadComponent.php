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
App::uses('TemporaryUploadFile', 'Files.Utility');

/**
 * FileUpload Component
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Files\Controller\Component
 */
class FileUploadComponent extends Component {

/**
 * @var Controller 呼び出し元コントローラ
 */
	public $controller;

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
 * アップロードされたテンポラリファイルを得る。
 *
 * @param string $fieldName フォームのフィールド名
 * @return TemporaryUploadFile
 */
	public function getTemporaryUploadFile($fieldName) {
		$fileInfo = Hash::get($this->controller->request->data, $fieldName);
		return $this->_getTemporaryUploadFile($fileInfo);
	}

/**
 * TemporaryUploadFileインスタンス生成
 *
 * @param array $fileInfo $_FILES[xxx]相当の配列
 * @return TemporaryUploadFile
 *
 * @codeCoverageIgnore
 */
	protected function _getTemporaryUploadFile($fileInfo) {
		return new TemporaryUploadFile($fileInfo);
	}
}
