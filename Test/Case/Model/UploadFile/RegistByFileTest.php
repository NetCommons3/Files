<?php
/**
 * UploadFile::registByFile()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');
App::uses('UploadFileFixture', 'Files.Test/Fixture');

/**
 * UploadFile::registByFile()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Files\Test\Case\Model\UploadFile
 */
class UploadFileRegistByFileTest extends NetCommonsModelTestCase {

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
	protected $_methodName = 'registByFile';

/**
 * testRegistByFile method
 *
 * @return void
 */
	public function testRegistByFile() {
		copy(dirname(dirname(dirname(__DIR__))) . DS . 'Fixture' . DS . 'logo.gif', TMP . 'logo.gif');
		$file = new File(TMP . 'logo.gif');
		$pluginKey = 'files';
		$contentKey = 'content_key_1';
		$fieldName = 'image';
		$data = $this->UploadFile->registByFile($file, $pluginKey, $contentKey, $fieldName);

		$this->assertTrue($data['UploadFile']['id'] > 0);

		$this->UploadFile->delete($this->UploadFile->id);
	}

/**
 * testRegistByFileFailed method
 *
 * @return void
 */
	public function testRegistByFileFailed() {
		copy(dirname(dirname(dirname(__DIR__))) . DS . 'Fixture' . DS . 'logo.gif', TMP . 'logo.gif');
		$file = new File(TMP . 'logo.gif');
		$pluginKey = 'files';
		$contentKey = 'content_key_1';
		$fieldName = 'image';

		$this->setExpectedException('InternalErrorException');
		$this->_mockForReturnFalse('UploadFile', 'Files.UploadFile', 'save');

		$this->UploadFile->registByFile($file, $pluginKey, $contentKey, $fieldName);
	}

}
