<?php
/**
 * UnZip::setPassword()のテスト
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
 * UnZip::setPassword()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Files\Test\Case\Utility\UnZip
 */
class UtilityUnZipSetPasswordTest extends NetCommonsCakeTestCase {

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'files';

/**
 * test unzip with password
 *
 * @return void
 */
	public function testUnzipWithPassword() {
		$zip = new UnZip(dirname(dirname(dirname(__DIR__))) . DS . 'Fixture/test_with_password.zip');
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

}
