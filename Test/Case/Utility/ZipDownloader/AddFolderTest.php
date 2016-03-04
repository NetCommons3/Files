<?php
/**
 * ZipDownloader::addFolder()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');

/**
 * ZipDownloader::addFolder()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Files\Test\Case\Utility\ZipDownloader
 */
class UtilityZipDownloaderAddFolderTest extends NetCommonsCakeTestCase {

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'files';

/**
 * addFolder()のテスト
 *
 * @return void
 */
	public function testAddFolder() {
		$zip = new ZipDownloader();
		$addFolder = dirname(dirname(dirname(__DIR__))) . DS . 'Fixture';

		$zip->addFolder($addFolder);

		$zip->close();
		$this->assertFileExists($zip->path);

		$unzip = new UnZip($zip->path);
		$unzip->extract();
		$this->assertFileExists($unzip->path . DS . 'Fixture' . DS . 'logo.gif');
	}

/**
 * addFolder() 例外発生のテスト
 *
 * @return void
 */
	public function testAddFolderFailed() {
		$zip = new ZipDownloader();

		$this->setExpectedException('InternalErrorException');
		// warning抑止して例外を発生させる
		// @codingStandardsIgnoreStart
		@$zip->addFolder('/detarameFolder');
		// @codingStandardsIgnoreEnd
	}
}
