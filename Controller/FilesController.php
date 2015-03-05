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
 * index action
 *
 * @return void
 */
	public function index() {
	}

/**
 * view action
 *
 * @return void
 */
	public function view() {
	}

/**
 * add action
 *
 * @return void
 */
	public function add() {
	}

/**
 * edit action
 *
 * @return void
 */
	public function edit() {
	}

/**
 * delete action
 *
 * @return void
 */
	public function delete() {
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

		list($slug, ) = explode('_', pathinfo($fileName, PATHINFO_BASENAME));

		if (! $file = $this->FileModel->find('first', [
			'recursive' => -1,
			'conditions' => ['slug' => $slug],
		])) {
			// @codeCoverageIgnoreStart
			throw new NotFoundException(__d('files', 'Not Found file.'));
			// @codeCoverageIgnoreEnd
		}

		//TODO: 権限チェック

		$filePath = $file[$this->FileModel->alias]['path'] . $fileName . '.' . $file[$this->FileModel->alias]['extension'];
		if (file_exists($filePath)) {
			$this->response->file($filePath);

			// 単にダウンロードさせる場合はこれを使う
			//$this->response->download($file[$this->FileModel->alias]['name']);

			$this->response->body($file[$this->FileModel->alias]['name']);
		}
	}

}

