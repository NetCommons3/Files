<?php
/**
 * UploadFile::removeFile()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');
App::uses('UploadFileFixture', 'Files.Test/Fixture');

/**
 * UploadFile::removeFile()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Files\Test\Case\Model\UploadFile
 */
class UploadFileRemoveFileTest extends NetCommonsModelTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.files.upload_file',
		'plugin.files.upload_files_content',
	);

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'files';

/**
 * Model name
 *
 * @var string
 */
	protected $_modelName = 'UploadFile';

/**
 * Method name
 *
 * @var string
 */
	protected $_methodName = 'removeFile';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->UploadFilesContent = ClassRegistry::init('Files.UploadFilesContent');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->UploadFileContent);
		parent::tearDown();
	}

/**
 * testRemoveFile method
 *
 * @return void
 */
	public function testRemoveFile() {
		$contentId = 2;
		$fileId = 1;
		// uploadビヘイビアが動作して実態ファイル削除を実行する…がここではUploadビヘイビアを外してDBレベルのテスト
		$this->UploadFile->Behaviors->unload('Upload');
		$this->UploadFile->removeFile($contentId, $fileId);

		$conditions = [
			'upload_file_id' => $fileId,
			'content_id' => $contentId,
		];
		// 関連テーブルが削除される
		$count = $this->UploadFilesContent->find('count', ['conditions' => $conditions]);
		$this->assertEquals(0, $count);

		// 他に関連がないのでファイルレコードも削除
		$count = $this->UploadFile->find('count', ['conditions' => ['id' => 1]]);
		$this->assertEquals(0, $count);
	}

/**
 * 関連レコードがない contents->deleteがこーるされない
 *
 * @return void
 */
	public function testRemoveFileNoLink() {
		$contentId = 2;
		$fileId = 2;
		// uploadビヘイビアが動作して実態ファイル削除を実行する…がここではUploadビヘイビアを外してDBレベルのテスト
		$this->UploadFile->Behaviors->unload('Upload');

		// 関連レコードがない contents->deleteがこーるされない
		$mock = $this->getMockForModel('Files.UploadFilesContent', ['delete']);
		$mock->expects($this->never())
			->method('delete');

		$this->UploadFile->removeFile($contentId, $fileId);
	}

/**
 * 他に関連レコードがあるのときはリンクだけ削除してファイルを残す $this->delteがコールされない
 *
 * @return void
 */
	public function testRemoveFileNoMoreLink() {
		// 2レコードのコンテンツデータからリンクされている1ファイルのデータ
		$contentId = 3;
		$fileId = 3;
		// uploadビヘイビアが動作して実態ファイル削除を実行する…がここではUploadビヘイビアを外してDBレベルのテスト
		$this->UploadFile->Behaviors->unload('Upload');

		// 関連レコードがない contents->deleteがこーるされない
		$mock = $this->getMockForModel('Files.UploadFile', ['delete']);
		$mock->expects($this->never())
			->method('delete');

		$this->UploadFile->removeFile($contentId, $fileId);

		$conditions = [
			'upload_file_id' => $fileId,
			'content_id' => $contentId,
		];
		// 関連テーブルが削除される
		$count = $this->UploadFilesContent->find('count', ['conditions' => $conditions]);
		$this->assertEquals(0, $count);

		// 他に関連があるのでファイルは残す
		$count = $this->UploadFile->find('count', ['conditions' => ['id' => $fileId]]);
		$this->assertEquals(1, $count);
	}

/**
 * UploadFilesContent->delete fail
 *
 * @return void
 */
	public function testRemoveFileLinkDeleteFail() {
		$contentId = 2;
		$fileId = 1;
		// uploadビヘイビアが動作して実態ファイル削除を実行する…がここではUploadビヘイビアを外してDBレベルのテスト
		$this->UploadFile->Behaviors->unload('Upload');

		// 関連レコードがない contents->deleteがこーるされない
		$this->_mockForReturnFalse('UploadFilesContent', 'Files.UploadFilesContent', 'delete');
		$this->setExpectedException('InternalErrorException');

		$this->UploadFile->removeFile($contentId, $fileId);
	}

/**
 * $this->delete fail
 *
 * @return void
 */
	public function testRemoveFileDeleteFail() {
		$contentId = 2;
		$fileId = 1;
		// uploadビヘイビアが動作して実態ファイル削除を実行する…がここではUploadビヘイビアを外してDBレベルのテスト
		$this->UploadFile->Behaviors->unload('Upload');

		// 関連レコードがない contents->deleteがこーるされない
		$this->_mockForReturnFalse('UploadFile', 'Files.UploadFile', 'delete');
		$this->setExpectedException('InternalErrorException');

		$this->UploadFile->removeFile($contentId, $fileId);
	}


}
