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
 * @return void
 */
	public function upload(Controller $controller) {
		if (! isset($controller->data['File'][FileModel::INPUT_NAME]) ||
				$controller->data['File'][FileModel::INPUT_NAME]['name'] === '') {
			return array();
		}

		$slug = Security::hash(
			$controller->data['File'][FileModel::INPUT_NAME]['name'] . mt_rand() . microtime(), 'md5'
		);

		$data = Hash::merge(array(
				'name' => $controller->data['File'][FileModel::INPUT_NAME]['name'],
				'slug' => $slug,
				'extension' => pathinfo($controller->data['File'][FileModel::INPUT_NAME]['name'], PATHINFO_EXTENSION),
				'original_name' => $slug,
				'size' => $controller->data['File'][FileModel::INPUT_NAME]['size'],
				'mimetype' => $controller->data['File'][FileModel::INPUT_NAME]['type'],
			),
			$controller->data['File']
		);
		if (preg_match('/^image/', $controller->data['File'][FileModel::INPUT_NAME]['type']) === 1 ||
				preg_match('/^video/', $controller->data['File'][FileModel::INPUT_NAME]['type']) === 1) {
			$data['alt'] = $data['name'];
		}

		return $data;
	}
}
