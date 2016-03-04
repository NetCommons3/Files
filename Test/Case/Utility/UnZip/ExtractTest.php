<?php
/**
 * UnZip::extract()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');
App::uses('TemporaryFolder', 'Files.Utility');
App::uses('TemporaryFile', 'Files.Utility');
App::uses('UnZip', 'Files.Utility');

/**
 * UnZip::extract()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Files\Test\Case\Utility\UnZip
 */
class UtilityUnZipExtractTest extends NetCommonsCakeTestCase {

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'files';

/**
 * test unzip
 *
 * @return void
 */
	public function testUnzip() {
		$zip = new UnZip(dirname(dirname(dirname(__DIR__))) . DS . 'Fixture/test.zip');
		$unzipedFolder = $zip->extract();
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
		$zip = new UnZip(dirname(dirname(dirname(__DIR__))) . DS . 'Fixture/test_with_password.zip');
		$zip->setPassword('no match password');
		$resultFalse = $zip->extract();
		$this->assertFalse($resultFalse);
	}
}
