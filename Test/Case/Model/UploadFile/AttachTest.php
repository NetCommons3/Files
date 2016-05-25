<?php
/**
 * UploadFile::attach()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');
App::uses('UploadFileFixture', 'Files.Test/Fixture');

/**
 * UploadFile::attach()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Files\Test\Case\Model\UploadFile
 */
class UploadFileAttachTest extends NetCommonsModelTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.files.upload_file',
		'plugin.files.upload_files_content',
		'plugin.site_manager.site_setting',
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
	protected $_methodName = 'attach';

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
 * testAttach method
 *
 * @return void
 */
	public function testAttach() {
		copy(dirname(dirname(dirname(__DIR__))) . DS . 'Fixture' . DS . 'logo.gif', TMP . 'logo.gif');

		$pluginKey = 'site_manager';
		$contentKey = 'content_key_1';
		$contentId = 3;
		$fieldName = 'image';
		$file = new File(TMP . 'logo.gif');

		$this->UploadFile->attach($pluginKey, $contentKey, $contentId, $fieldName, $file);

		// UploadFileレコードが登録される
		$conditions = [
			'plugin_key' => $pluginKey,
			'content_key' => $contentKey,
			'field_name' => $fieldName,
		];
		$file = $this->UploadFile->find('first', ['conditions' => $conditions]);
		$this->assertTrue($file['UploadFile']['id'] > 0);

		// 関連レコードが登録される。
		$conditions = [
			'UploadFilesContent.upload_file_id' => $file['UploadFile']['id'],
			'UploadFilesContent.content_id' => $contentId,
			'UploadFilesContent.plugin_key' => $pluginKey
		];
		$link = $this->UploadFilesContent->find('first', ['conditions' => $conditions]);
		$this->assertTrue($link['UploadFilesContent']['id'] > 0);
	}
}
