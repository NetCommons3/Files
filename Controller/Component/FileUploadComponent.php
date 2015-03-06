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
 * @param string $model Model name.
 * @param string $field Request parameter name.
 * @return void
 */
	public function upload(Controller $controller, $model, $field) {
		if (! isset($controller->data[$model][$field]) ||
				$controller->data[$model][$field]['name'] === '') {
			return array();
		}

		$slug = Security::hash(
			$controller->data[$model][$field]['name'] . mt_rand() . microtime(), 'md5'
		);

		$data = Hash::merge(
			$controller->data[$field]['File'],
			array(
				'slug' => $slug,
				'extension' => pathinfo($controller->data[$model][$field]['name'], PATHINFO_EXTENSION),
				'original_name' => $slug,
				'mimetype' => $controller->data[$model][$field]['type'],
			),
			$controller->data[$model][$field]
		);
		if (preg_match('/^image/', $data['type']) === 1 ||
				preg_match('/^video/', $data['type']) === 1) {
			$data['alt'] = $data['name'];
		}

		return $data;
	}
}
