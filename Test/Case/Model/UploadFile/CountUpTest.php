<?php
/**
 * UploadFile::countUp()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');
App::uses('UploadFileFixture', 'Files.Test/Fixture');

/**
 * UploadFile::countUp()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Files\Test\Case\Model\UploadFile
 */
class UploadFileCountUpTest extends NetCommonsModelTestCase {

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
	protected $_methodName = 'countUp';

/**
 * testCountUp method
 *
 * @return void
 */
	public function testCountUp() {
		$file = $this->UploadFile->findById(1);
		$beforeCount = $file['UploadFile']['download_count'];
		$this->UploadFile->countUp($file);
		$afterFile = $this->UploadFile->findById(1);
		$this->assertEquals($beforeCount + 1, $afterFile['UploadFile']['download_count']);
	}

/**
 * testCountUp method save 失敗テスト
 *
 * @return void
 */
	public function testCountUpFailed() {
		$file = $this->UploadFile->findById(1);
		$this->setExpectedException('InternalErrorException');
		$this->_mockForReturnFalse('UploadFile', 'Files.UploadFile', 'save');
		$this->UploadFile->countUp($file);
	}

}
