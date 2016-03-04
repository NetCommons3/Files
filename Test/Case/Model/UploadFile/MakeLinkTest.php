<?php
/**
 * UploadFile::makeLink()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');
App::uses('UploadFileFixture', 'Files.Test/Fixture');

/**
 * UploadFile::makeLink()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Files\Test\Case\Model\UploadFile
 */
class UploadFileMakeLinkTest extends NetCommonsModelTestCase {

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
	protected $_methodName = 'makeLink';

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
 * testMakeLink method
 *
 * @return void
 */
	public function testMakeLink() {
		$pluginKey = 'files';
		$contentId = 1;
		$uploadFileId = 1;
		$this->UploadFile->makeLink($pluginKey, $contentId, $uploadFileId);

		// 関連レコードが挿入されてることを確認
		$conditions = [
			'UploadFilesContent.plugin_key' => $pluginKey,
			'UploadFilesContent.content_id' => $contentId,
			'UploadFilesContent.upload_file_id' => $uploadFileId
		];
		$link = $this->UploadFilesContent->find('first', ['conditions' => $conditions]);

		$this->assertTrue($link['UploadFilesContent']['id'] > 0);
	}

/**
 * testMakeLinkFailed method
 *
 * @return void
 */
	public function testMakeLinkFailed() {
		$pluginKey = 'files';
		$contentId = 1;
		$uploadFileId = 1;
		$this->setExpectedException('InternalErrorException');
		$this->_mockForReturnFalse('UploadFile', 'Files.UploadFilesContent', 'save');
		$this->UploadFile->makeLink($pluginKey, $contentId, $uploadFileId);
	}

}
