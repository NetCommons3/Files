<?php
/**
 * ZipDownloader::addFromString()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');

/**
 * ZipDownloader::addFromString()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Files\Test\Case\Utility\ZipDownloader
 */
class UtilityZipDownloaderAddFromStringTest extends NetCommonsCakeTestCase {

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'files';

/**
 * addFromString()のテスト
 *
 * @return void
 */
	public function testAddFromString() {
		$zip = new ZipDownloader();
		//$tmpFolder = new TemporaryFolder();
		//$zip->open($tmpFolder->path . DS . 'test.zip', true);

		$zip->addFromString('foo.txt', 'foo');

		$zip->close();

		$unzip = new UnZip($zip->path);
		//$unzip->open($tmpFolder->path . DS . 'test.zip');
		//$unzipFolder = new TemporaryFolder();
		//$unzip->extractTo($unzipFolder->path);
		$unzip->extract();

		$contents = file($unzip->path . DS . 'foo.txt');
		$this->assertEquals('foo', $contents[0]);
	}

}
