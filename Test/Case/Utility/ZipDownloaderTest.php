<?php
/**
 * ZipDownloaderTest
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');
App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');
App::uses('TemporaryFolder', 'Files.Utility');
App::uses('ZipDownloader', 'Files.Utility');
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
class ZipDownloaderTest extends NetCommonsCakeTestCase {

	public $fixtures = [];


/**
 * test create zip no password
 * TODO ZipArchiver使う
 * @return void
 */
	public function testCreateZipNoPassword() {
		$zip = new ZipDownloader();
		//$tmpFolder = new TemporaryFolder();
		//$tmpFolder = new Folder(TMP . 'test', true);
		//$zipPath = $tmpFolder->path . DS . 'test.zip';

		//$zip->open($zipPath, ZipArchive::CREATE);
		$addFile = dirname(dirname(__DIR__)) . DS . 'Fixture' . DS . 'logo.gif';

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
 * test creat zip with password
 *
 * @return void
 */
	public function testCreateZipWithPassword() {
		$zip = new ZipDownloader();
		//$tmpFolder = new Folder(TMP . 'test', true);
		//$zipPath = $tmpFolder->path . DS . 'test.zip';

		//$zip->open($zipPath, ZipArchive::CREATE);
		$addFile = dirname(dirname(__DIR__)) . DS . 'Fixture' . DS . 'logo.gif';

		$zip->addFile($addFile);

		$zip->setPassword('test');

		$zip->close();

		$this->assertFileExists($zip->path);

		$unzip = new UnZip($zip->path);
		$unzip->setPassword('test');
		$unzip->extract();
		$this->assertFileExists($unzip->path . DS . 'logo.gif');
		$fileSize = filesize($unzip->path . DS . 'logo.gif');
		$this->assertTrue($fileSize > 0);
	}

/**
 * test add folder
 *
 * @return void
 */
	public function testAddFolder() {
		//$zip = new ZipArchive();
		//$zip->open(TMP  . 'test.zip', ZipArchive::CREATE);
		//$zip->addEmptyDir('hoge/hogehoge/hogehogehoge');
		//$zip->addFromString('foo/bar/hoge', 'test');
		//$zip->addFromString('test', 'test');
		//$zip->close();
		//exit();


		$zip = new ZipDownloader();
		$addFolder = dirname(dirname(__DIR__)) . DS . 'Fixture';

		$zip->addFolder($addFolder);

		$zip->close();
		$this->assertFileExists($zip->path);

		$unzip = new UnZip($zip->path);
		$unzip->extract();
		$this->assertFileExists($unzip->path . DS . 'Fixture' . DS . 'logo.gif');
	}

/**
 * test add from string
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

	public function XtestRisou() {
		$zip = new ZipDownloader();
		// アーカイブ先を指定する必要はない
		$zip->addFromString('foo.txt', 'foo');
		$folderPath = TMP . 'log';
		$zip->addFolder($folderPath);
		$zip->setPassword('password');
		return $zip->download('file.zip');

	}
}