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
 * Before the controller action
 *
 * @param Controller $controller Controller with components
 * @param string $field Request parameter name.
 * @return void
 */
	public function upload(Controller $controller, $modelName, $field) {
		if (! isset($controller->data[$modelName][$field]) ||
				$controller->data[$modelName][$field]['name'] === '') {
			return array();
		}

		$slug = Security::hash(
			$controller->data[$modelName][$field]['name'] . mt_rand() . microtime(), 'md5'
		);

		$data['File'] = Hash::merge(array(
				//'name' => $controller->data['File'][$field]['name'],
				'slug' => $slug,
				'extension' => pathinfo($controller->data[$modelName][$field]['name'], PATHINFO_EXTENSION),
				'original_name' => $slug,
				//'size' => $controller->data['File'][$field]['size'],
				'mimetype' => $controller->data[$modelName][$field]['type'],
			),
			$controller->data[$modelName][$field]
		);
		if (preg_match('/^image/', $data['File']['type']) === 1 ||
				preg_match('/^video/', $data['File']['type']) === 1) {
			$data['File']['alt'] = $data['File']['name'];
		}

		//CakeLog::debug('FileUploadComponent::upload() $data=' . print_r($data, true));

		return $data;
	}
}
