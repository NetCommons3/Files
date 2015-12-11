<?php
/**
 * NetCommonsZipTest
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');
App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');
App::uses('TemporaryFolder', 'Files.Utility');
App::uses('UnZip', 'Files.Utility');
/**
 * Summary for CsvFileWriter Test Case
 *
 * test.zip
 * -test/
 *  - bar
 *  - foo
 *  - hoge/
 *   - hogehoge in "hogehoge file"
 */
class UnZipTest extends NetCommonsCakeTestCase {

/**
 * @var array fixture
 */
	public $fixtures = [];

/**
 * test unzip
 *
 * @return void
 */
	public function testUnzip() {
		$zip = new UnZip(dirname(dirname(__DIR__)) . DS . 'Fixture/test.zip');
		$unzipedFolder = $zip->extract();
		//$zip->open(dirname(dirname(__DIR__)) . DS . 'Fixture/test.zip');
		$this->assertFileExists($unzipedFolder->path . DS . 'test');
		$this->assertFileExists($unzipedFolder->path . DS . 'test' . DS . 'bar');
		$this->assertFileExists($unzipedFolder->path . DS . 'test' . DS . 'hoge');
		$this->assertFileExists($unzipedFolder->path . DS . 'test' . DS . 'hoge' . DS . 'hogehoge');

		$contents = file($unzipedFolder->path . DS . 'test' . DS . 'hoge' . DS . 'hogehoge');
		$this->assertEquals('hogehoge file', $contents[0]);
	}

/**
 * test unzip with password
 *
 * @return void
 */
	public function testUnzipWithPassword() {
		$zip = new UnZip(dirname(dirname(__DIR__)) . DS . 'Fixture/test_with_password.zip');
		$zip->setPassword('password');
		$unzipedFolder = $zip->extract();
		//$zip->open(dirname(dirname(__DIR__)) . DS . 'Fixture/test.zip');
		$this->assertFileExists($unzipedFolder->path . DS . 'test');
		$this->assertFileExists($unzipedFolder->path . DS . 'test' . DS . 'bar');
		$this->assertFileExists($unzipedFolder->path . DS . 'test' . DS . 'hoge');
		$this->assertFileExists($unzipedFolder->path . DS . 'test' . DS . 'hoge' . DS . 'hogehoge');

		$contents = file($unzipedFolder->path . DS . 'test' . DS . 'hoge' . DS . 'hogehoge');
		$this->assertEquals('hogehoge file', $contents[0]);
	}

/**
 * test unzip failed
 *
 * @return void
 */
	public function testUnZipFailed() {
		$zip = new UnZip(dirname(dirname(__DIR__)) . DS . 'Fixture/test_with_password.zip');
		$zip->setPassword('no match password');
		$resultFalse = $zip->extract();
		$this->assertFalse($resultFalse);
	}
}