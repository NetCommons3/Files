<?php
/**
 * ZipDownloader::addFile()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');

/**
 * ZipDownloader::addFile()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Files\Test\Case\Utility\ZipDownloader
 */
class UtilityZipDownloaderAddFileTest extends NetCommonsCakeTestCase {

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'files';

/**
 * test create zip no password
 *
 * @return void
 */
	public function testCreateZipNoPassword() {
		$zip = new ZipDownloader();
		$addFile = dirname(dirname(dirname(__DIR__))) . DS . 'Fixture' . DS . 'logo.gif';

		$zip->addFile($addFile);
		$zip->close();

		$this->assertFileExists($zip->path);

		$unzip = new ZipArchive();
		$unzip->open($zip->path);
		$unzipFolder = new TemporaryFolder();
		$unzip->extractTo($unzipFolder->path);

		$this->assertFileExists($unzipFolder->path . DS . 'logo.gif');
		$fileSize = filesize($unzipFolder->path . DS . 'logo.gif');
		$this->assertTrue($fileSize > 0);
	}

/**
 * addFile() で例外発生のテスト
 *
 * @return void
 */
	public function testAddFileFailed() {
		$zip = new ZipDownloader();
		$addFile = dirname(dirname(dirname(__DIR__))) . DS . 'Fixture' . DS . 'logo.gif';
		//$tmpFolder = new ReflectionProperty($zip, '_tmpFolder');
		//$tmpFolder->setAccessible(true);
		//$tmp = $tmpFolder->getValue($zip);
		//$tmp->path = '/detara';

		$this->setExpectedException('InternalErrorException');
		// warning抑止して例外を発生させる
		// @codingStandardsIgnoreStart
		@$zip->addFile($addFile, 'Failed/Filename');
		// @codingStandardsIgnoreEnd
	}

}
