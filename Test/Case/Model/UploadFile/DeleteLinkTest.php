<?php
/**
 * UploadFile::deleteLink()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');
App::uses('UploadFileFixture', 'Files.Test/Fixture');
App::uses('TemporaryFolder', 'Files.Utility');
App::uses('TemporaryFile', 'Files.Utility');
/**
 * UploadFile::deleteLink()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Files\Test\Case\Model\UploadFile
 */
class UploadFileDeleteLinkTest extends NetCommonsModelTestCase {

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
	protected $_methodName = 'deleteLink';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		//$this->UploadFile = ClassRegistry::init('Files.UploadFile');
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
 * testDeleteLink method
 *
 * @return void
 */
	public function testDeleteLink() {
		$pluginKey = 'site_manager';
		$contentId = 2;
		$fieldName = 'photo';

		// Uploadビヘイビアが実ファイルを削除しにくるので事前に削除されるファイルを用意しておく
		$tmpFolder = new TemporaryFolder();
		$this->UploadFile->uploadBasePath = $tmpFolder->path . '/';
		$tmpFile = new TemporaryFile($tmpFolder->path . '/files/upload_file/real_file_name/1/1/');
		rename($tmpFile->path, dirname($tmpFile->path) . '/foobarhash.jpg');
		$tmpFile->name = 'foobarhash.jpg';
		$tmpFile->path = dirname($tmpFile->path) . '/foobarhash.jpg';

		$this->UploadFile->deleteLink($pluginKey, $contentId, $fieldName);

		// fixtureで挿入された関連レコードが削除されてること
		$count = $this->UploadFilesContent->find('count', ['conditions' => ['UploadFilesContent.id' => 1]]);
		$this->assertEquals(0, $count);

		// fixtureで挿入されたUploadFileレコードも削除されること（他に関連がないので）
		$count = $this->UploadFile->find('count', ['conditions' => ['UploadFile.id' => 1]]);
		$this->assertEquals(0, $count);
	}

/**
 * deleteLink delete()失敗で例外発生
 *
 * @return void
 */
	public function testDeleteLinkFailed() {
		$pluginKey = 'site_manager';
		$contentId = 2;
		$fieldName = 'photo';
		// Uploadビヘイビアを無効にしておく
		$this->UploadFile->Behaviors->unload('Upload');
		$this->_mockForReturnFalse('UploadFile', 'Files.UploadFile', 'delete');

		$this->setExpectedException('InternalErrorException');

		$this->UploadFile->deleteLink($pluginKey, $contentId, $fieldName);
	}

/**
 * deleteLink コンテンツとのリンクがないケース
 *
 * @return void
 */
	public function testDeleteLinkByNoLink() {
		$pluginKey = 'site_manager';
		$contentId = 3;
		$fieldName = 'photo';
		// Uploadビヘイビアを無効にしておく
		$this->UploadFile->Behaviors->unload('Upload');
		$this->UploadFile->deleteLink($pluginKey, $contentId, $fieldName);

		// 削除されるリンクがないので、レコード数に変化無し
		$count = $this->UploadFilesContent->find('count', ['conditions' => ['UploadFilesContent.id' => 1]]);
		$this->assertEquals(1, $count);

		// 削除されるリンクがないので、レコード数に変化無し
		$count = $this->UploadFile->find('count', ['conditions' => ['UploadFile.id' => 1]]);
		$this->assertEquals(1, $count);
	}
}
