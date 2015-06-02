<?php
/**
 * FilesController Test Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('FilesController', 'Files.Controller');
App::uses('FilesControllerTestCase', 'Files.Test/Case/Controller');
App::uses('RolesControllerTest', 'Roles.Test/Case/Controller');
App::uses('Folder', 'Utility');

/**
 * FilesController Test Case
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Files\Test\Case\Controller
 */
class FilesControllerTest extends FilesControllerTestCase {

/**
 * Expect view action
 *
 * @return void
 */
	public function testView() {
		$folder = new Folder();
		$folder->create(TMP . 'tests' . DS . 'file' . DS . '1');
		$file = new File(
			APP . 'Plugin' . DS . 'Files' . DS . 'Test' . DS . 'Fixture' . DS . 'logo.gif'
		);
		$file->copy(TMP . 'tests' . DS . 'file' . DS . '1' . DS . 'logo_hash.gif');
		$file->copy(TMP . 'tests' . DS . 'file' . DS . '1' . DS . 'logo_hash_big.gif');
		$file->copy(TMP . 'tests' . DS . 'file' . DS . '1' . DS . 'logo_hash_medium.gif');
		$file->copy(TMP . 'tests' . DS . 'file' . DS . '1' . DS . 'logo_hash_small.gif');
		$file->copy(TMP . 'tests' . DS . 'file' . DS . '1' . DS . 'logo_hash_thumbnail.gif');
		$file->close();

		ob_start();
		$this->testAction(
			'/files/files/view/file1.gif',
			array(
				'method' => 'get',
			)
		);

		$this->assertEquals(200, $this->controller->response->statusCode());
		$this->assertEquals('image/gif', $this->controller->response->type());

		//アップロードテストのためのディレクトリ削除
		$folder = new Folder();
		$folder->delete(TMP . 'tests' . DS . 'file');

		unset($folder);
	}

/**
 * Expect download action
 *
 * @return void
 */
	public function testDownload() {
		$folder = new Folder();
		$folder->create(TMP . 'tests' . DS . 'file' . DS . '1');
		$file = new File(
			APP . 'Plugin' . DS . 'Files' . DS . 'Test' . DS . 'Fixture' . DS . 'logo.gif'
		);
		$file->copy(TMP . 'tests' . DS . 'file' . DS . '1' . DS . 'logo_hash.gif');
		$file->copy(TMP . 'tests' . DS . 'file' . DS . '1' . DS . 'logo_hash_big.gif');
		$file->copy(TMP . 'tests' . DS . 'file' . DS . '1' . DS . 'logo_hash_medium.gif');
		$file->copy(TMP . 'tests' . DS . 'file' . DS . '1' . DS . 'logo_hash_small.gif');
		$file->copy(TMP . 'tests' . DS . 'file' . DS . '1' . DS . 'logo_hash_thumbnail.gif');
		$file->close();

		ob_start();
		$this->testAction(
			'/files/files/download/file1.gif',
			array(
				'method' => 'get',
			)
		);

		$this->assertEquals(200, $this->controller->response->statusCode());
		$this->assertEquals('image/gif', $this->controller->response->type());

		//アップロードテストのためのディレクトリ削除
		$folder = new Folder();
		$folder->delete(TMP . 'tests' . DS . 'file');

		unset($folder);
	}

/**
 * view action 例外テスト
 *
 * @return void
 * @throws Exception
 */
	public function testViewException() {
		$this->setExpectedException('NotFoundException');

		$controller = $this->generate('Files.Files', array(
			'models' => array(
				'Files.FileModel' => array('find')
			)
		));
		$controller->FileModel->expects($this->once())
			->method('find')
			->will($this->returnValue(false));

		$folder = new Folder();
		$folder->create(TMP . 'tests' . DS . 'file' . DS . '1');
		$file = new File(
			APP . 'Plugin' . DS . 'Files' . DS . 'Test' . DS . 'Fixture' . DS . 'logo.gif'
		);
		$file->copy(TMP . 'tests' . DS . 'file' . DS . '1' . DS . 'logo_hash.gif');
		$file->copy(TMP . 'tests' . DS . 'file' . DS . '1' . DS . 'logo_hash_big.gif');
		$file->copy(TMP . 'tests' . DS . 'file' . DS . '1' . DS . 'logo_hash_medium.gif');
		$file->copy(TMP . 'tests' . DS . 'file' . DS . '1' . DS . 'logo_hash_small.gif');
		$file->copy(TMP . 'tests' . DS . 'file' . DS . '1' . DS . 'logo_hash_thumbnail.gif');
		$file->close();

		try {
			ob_start();
			$this->testAction(
				'/files/files/view/file1.gif',
				array(
					'method' => 'get',
				)
			);
		} catch (Exception $e) {
			//アップロードテストのためのディレクトリ削除
			$folder = new Folder();
			$folder->delete(TMP . 'tests' . DS . 'file');

			unset($folder);

			throw $e;
		}
	}

/**
 * download action 例外テスト
 *
 * @return void
 * @throws Exception
 */
	public function testDownloadException() {
		$this->setExpectedException('NotFoundException');

		$controller = $this->generate('Files.Files', array(
			'models' => array(
				'Files.FileModel' => array('find')
			)
		));
		$controller->FileModel->expects($this->once())
			->method('find')
			->will($this->returnValue(false));

		$folder = new Folder();
		$folder->create(TMP . 'tests' . DS . 'file' . DS . '1');
		$file = new File(
			APP . 'Plugin' . DS . 'Files' . DS . 'Test' . DS . 'Fixture' . DS . 'logo.gif'
		);
		$file->copy(TMP . 'tests' . DS . 'file' . DS . '1' . DS . 'logo_hash.gif');
		$file->copy(TMP . 'tests' . DS . 'file' . DS . '1' . DS . 'logo_hash_big.gif');
		$file->copy(TMP . 'tests' . DS . 'file' . DS . '1' . DS . 'logo_hash_medium.gif');
		$file->copy(TMP . 'tests' . DS . 'file' . DS . '1' . DS . 'logo_hash_small.gif');
		$file->copy(TMP . 'tests' . DS . 'file' . DS . '1' . DS . 'logo_hash_thumbnail.gif');
		$file->close();

		try {
			ob_start();
			$this->testAction(
				'/files/files/download/file1.gif',
				array(
					'method' => 'get',
				)
			);
		} catch (Exception $e) {
			//アップロードテストのためのディレクトリ削除
			$folder = new Folder();
			$folder->delete(TMP . 'tests' . DS . 'file');

			unset($folder);

			throw $e;
		}
	}
}
