<?php
/**
 * Created by PhpStorm.
 * User: ryuji
 * Date: 2015/11/19
 * Time: 14:37
 */

App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');
App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');
App::uses('TemporaryUploadFile', 'Files.Utility');

/**
 * Summary for AttachmentBehavior Test Case
 *
 * @property TestCreateProfile $SiteSetting テスト用モデル
 */
class TemporaryUploadFileTest extends NetCommonsCakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = [];

	public function setUp() {
		copy(APP . 'Plugin/Files/Test/Fixture/logo.gif', TMP . 'test.gif');

	}

	public function tearDown() {
		unlink(TMP . 'test.gif');
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
			'tmp_name' => TMP . 'test.gif',
			'error' => 0,
			'size' => 442850,
		];

		// アップロードしたファイルではないので例外が発生する
		$this->setExpectedException('InternalErrorException');
		$uploadFile = new TemporaryUploadFile($data);
	}

	public function testNewSuccess() {
		$data = [
			'name' => 'test.gif',
			'type' => "image/gif",
			'tmp_name' => TMP . 'test.gif',
			'error' => 0,
			'size' => 442850,
		];

		//$uploadFileMock = $this->getMockBuilder('TemporaryUploadFile')
		//	->setConstructorArgs($data)
		//	->setMethods('_moveFile')
		//	->getMock();
		//$uploadFileMock = $this->getMock('TemporaryUploadFile', ['_moveFile'], [$data]);
		//$uploadFileMock->expects($this->once())
		//	->method('_moveFile');
			//->will($this->returnCallback('testMove'));
			//->will($this->returnValue(true));

		$uploadFile = new TemporaryUploadFileTesting($data);
		$this->assertFileNotExists(TMP . 'test.gif');
		$this->assertFileExists($uploadFile->path);
	}

}
class TemporaryUploadFileTesting extends TemporaryUploadFile {
	protected function _moveFile($path, $destPath) {
		return rename($path, $destPath);
	}
}