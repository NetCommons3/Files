<?php
/**
 * ZipDownloader::setPassword()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');

/**
 * ZipDownloader::setPassword()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Files\Test\Case\Utility\ZipDownloader
 */
class UtilityZipDownloaderSetPasswordTest extends NetCommonsCakeTestCase {

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'files';

/**
 * test creat zip with password
 *
 * @return void
 */
	public function testCreateZipWithPassword() {
		$zip = new ZipDownloader();
		//$tmpFolder = new Folder(TMP . 'test', true);
		//$zipPath = $tmpFolder->path . DS . 'test.zip';

		//$zip->open($zipPath, ZipArchive::CREATE);
		$addFile = dirname(dirname(dirname(__DIR__))) . DS . 'Fixture' . DS . 'logo.gif';

		$zip->addFile($addFile);

		$zip->setPassword('test');

		$zip->close();

		$this->assertFileExists($zip->path);

		$unzipFolder1 = new TemporaryFolder();
		$cmd = sprintf('unzip -P %s %s -d %s', 'test', $zip->path, $unzipFolder1->path);
		exec($cmd, $output, $returnVar);
		$this->assertEquals(0, $returnVar, $output);

		$this->assertFileExists($unzipFolder1->path . DS . 'logo.gif');
		$fileSize = filesize($unzipFolder1->path . DS . 'logo.gif');
		$this->assertTrue($fileSize > 0);
	}

/**
 * 空パスワードのテスト
 *
 * @return void
 */
	public function testCreateZipWithEmptyPassword() {
		$zip = new ZipDownloader();
		$addFile = dirname(dirname(dirname(__DIR__))) . DS . 'Fixture' . DS . 'logo.gif';

		$zip->addFile($addFile);

		$zip->setPassword('');

		$zip->close();

		$this->assertFileExists($zip->path);

		$unzipFolder1 = new TemporaryFolder();
		$cmd = sprintf('unzip %s -d %s', $zip->path, $unzipFolder1->path);
		exec($cmd, $output, $returnVar);
		$this->assertEquals(0, $returnVar, $output);

		$this->assertFileExists($unzipFolder1->path . DS . 'logo.gif');
		$fileSize = filesize($unzipFolder1->path . DS . 'logo.gif');
		$this->assertTrue($fileSize > 0);
	}
}
