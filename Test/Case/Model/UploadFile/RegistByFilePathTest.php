<?php
/**
 * UploadFile::registByFilePath()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');
App::uses('UploadFileFixture', 'Files.Test/Fixture');

/**
 * UploadFile::registByFilePath()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Files\Test\Case\Model\UploadFile
 */
class UploadFileRegistByFilePathTest extends NetCommonsModelTestCase {

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
	protected $_methodName = 'registByFilePath';

/**
 * testRegistByFilePath method
 *
 * @return void
 */
	public function testRegistByFilePath() {
		copy(dirname(dirname(dirname(__DIR__))) . DS . 'Fixture' . DS . 'logo.gif', TMP . 'logo.gif');
		$pluginKey = 'files';
		$contentKey = 'content_key_1';
		$fieldName = 'image';

		// registByFileのラップメソッドなので、registByFileがコールされてるかテストする
		$UploadFileMock = $this->getMockForModel('Files.UploadFile', ['registByFile']);
		$UploadFileMock->expects($this->once())
			->method('registByFile')
			->with(
				$this->isInstanceOf('File'),
				$this->equalTo($pluginKey),
				$this->equalTo($contentKey),
				$this->equalTo($fieldName)
			);

		$UploadFileMock->registByFilePath(TMP . 'logo.gif', $pluginKey, $contentKey, $fieldName);
	}
}
