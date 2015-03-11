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
class FileUploadComponent extends Component {

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
}
