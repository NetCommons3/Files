<?php
/**
 * Created by PhpStorm.
 * User: ryuji
 * Date: 2015/11/19
 * Time: 14:37
 */

App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');
App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');
App::uses('TemporaryFolder', 'Files.Utility');
App::uses('TemporaryUploadFile', 'Files.Utility');
App::uses('TemporaryUploadFileTesting', 'Files.Test/Testing');

/**
 * TemporaryUploadFileTest
 */
class TemporaryUploadFileTest extends NetCommonsCakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = [];

/**
 * @var TemporaryFolder テンポラリフォルダ
 */
	protected $_tmpFolder = null;

/**
 * setUp
 *
 * @return void
 */
	public function setUp() {
		$this->_tmpFolder = new TemporaryFolder();
		copy(APP . 'Plugin/Files/Test/Fixture/logo.gif', $this->_tmpFolder->path . DS . 'test.gif');
	}

/**
 * tearDown
 *
 * @return void
 */
	public function tearDown() {
		// 残ってたら削除する
		//@unlink(TMP . 'test.gif');
	}

/**
 * test create
 *
 * @return void
 */
	public function testFailMoveUploadedFile() {
		$data = [
			'name' => 'test.gif',
			'type' => "image/gif",
			'tmp_name' => $this->_tmpFolder->path . DS . 'test.gif',
			'error' => 0,
			'size' => 442850,
		];

		// アップロードしたファイルではないので例外が発生する
		$this->setExpectedException('InternalErrorException');
		new TemporaryUploadFile($data);
	}

/**
 * テスト用にmove_uploaded_fileをrenameに置き換えてのテスト
 *
 * @return void
 */
	public function testNewSuccess() {
		$data = [
			'name' => 'test.gif',
			'type' => "image/gif",
			'tmp_name' => $this->_tmpFolder->path . DS . 'test.gif',
			'error' => 0,
			'size' => 442850,
		];

		$uploadFile = new TemporaryUploadFileTesting($data);
		$this->assertFileNotExists($this->_tmpFolder->path . DS . 'test.gif');
		$this->assertFileExists($uploadFile->path);
	}
}
