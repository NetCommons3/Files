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

	protected $_files = array();
/**
 * Called before the Controller::beforeFilter().
 *
 * @param Controller $controller Instantiating controller
 * @return void
 */
	public function initialize(Controller $controller) {
		$this->controller = $controller;
		//$this->_grab();
	}

/**
 * アップロードされたテンポラリファイルを得る。
 *
 * @param string $fieldName フォームのフィールド名
 * @return TemporaryUploadFile
 */
	public function getTemporaryUploadFile($fieldName) {
		$file = Hash::get($this->controller->request->data, $fieldName);
		$fileObject = new TemporaryUploadFile($file);
		return $fileObject;
	}

/**
 * Before the controller action
 *
 * @param string $model Model name.
 * @param string $field Request parameter name.
 * @return void
 */
	public function upload($model, $field) {
		if (! isset($this->controller->data[$model][$field]) ||
				$this->controller->data[$model][$field]['name'] === '') {
			return array();
		}

		$slug = Security::hash(
			$this->controller->data[$model][$field]['name'] . mt_rand() . microtime(), 'md5'
		);

		$data = Hash::merge(
			$this->controller->data[$field]['File'],
			array(
				'slug' => $slug,
				'extension' => pathinfo($this->controller->data[$model][$field]['name'], PATHINFO_EXTENSION),
				'original_name' => $slug,
				'mimetype' => $this->controller->data[$model][$field]['type'],
			),
			$this->controller->data[$model][$field]
		);
		if (preg_match('/^image/', $data['type']) === 1 ||
				preg_match('/^video/', $data['type']) === 1) {
			$data['alt'] = $data['name'];
		}

		return $data;
	}

//	protected function _grab(){
//		if(Hash::get($_FILES, 'data', false)){
//			// ファイルアップロードがある。
//			// 名前を取得する
//			// $this->Form->create(false, ['type' => 'file']);
//			// $this->Form->input('import_csv', ['type' => 'file']); したときに$_FILESの形式
//			//array(
//			//		'data' => array(
//			//				'name' => array(
//			//						'import_csv' => '',
//			//						'import_photo' => ''
//			//				),
//			//				'type' => array(
//			//						'import_csv' => '',
//			//						'import_photo' => ''
//			//				),
//			//				'tmp_name' => array(
//			//						'import_csv' => '',
//			//						'import_photo' => ''
//			//				),
//			//				'error' => array(
//			//						'import_csv' => (int) 4,
//			//						'import_photo' => (int) 4
//			//				),
//			//				'size' => array(
//			//						'import_csv' => (int) 0,
//			//						'import_photo' => (int) 0
//			//				)
//			//		)
//			//);// [data][name][import_csv]
//			// モデルがあると
//			// [data][name][ModelName][import_csv]
//			$fields = array_keys($_FILES['data']['name']);
//			foreach($fields as $field){
//				$tmp_name = $this->request->data[$field]['tmp_name'];
//				$this->_files[] = new TemporaryUploadFile($tmp_name);
//			}
//		}
//
//debug($_FILES);
//	}
}
