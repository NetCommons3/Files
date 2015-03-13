<?php
/**
 * Files Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('FilesAppController', 'Files.Controller');

/**
 * Files Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Files\Controller
 */
class FilesController extends FilesAppController {

/**
 * use model
 *
 * @var array
 */
	public $uses = array(
		'Files.FileModel'
	);

/**
 * use component
 *
 * @var array
 */
	//public $components = array();

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('download');
	}

/**
 * download action
 *
 * @param string $fileName File name
 * @return void
 * @throws NotFoundException
 */
	public function download($fileName = null) {
		$this->autoRender = false;

		$fileInfo = explode('_', pathinfo($fileName, PATHINFO_FILENAME));

		if (! $file = $this->FileModel->find('first', [
			'recursive' => -1,
			'conditions' => ['slug' => $fileInfo[0]],
		])) {
			// @codeCoverageIgnoreStart
			throw new NotFoundException(__d('files', 'Not Found file.'));
			// @codeCoverageIgnoreEnd
		}

		//権限チェック

		$filePath = $file[$this->FileModel->alias]['path'] .
					$file[$this->FileModel->alias]['original_name'] .
					(isset($fileInfo[1]) ? '_' . $fileInfo[1] : '') .
					'.' . $file[$this->FileModel->alias]['extension'];

		if (file_exists($filePath)) {
			$this->response->file($filePath, array('name' => $file[$this->FileModel->alias]['name']));

			// 単にダウンロードさせる場合はこれを使う
			//$this->response->download($file[$this->FileModel->alias]['name']);

			//$this->response->body($file[$this->FileModel->alias]['name']);
		}
	}

}

