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

	public function testUnZipFailed(){
		$zip = new UnZip(dirname(dirname(__DIR__)) . DS . 'Fixture/test_with_password.zip');
		$zip->setPassword('no match password');
		$resultFalse = $zip->extract();
		$this->assertFalse($resultFalse);

	}

///**
// * test create zip no password
// *
// * @return void
// */
//	public function testCreateZipNoPassword() {
//		$zip = new NetCommonsZip();
//		$tmpFolder = new TemporaryFolder();
//		//$tmpFolder = new Folder(TMP . 'test', true);
//		$zipPath = $tmpFolder->path . DS . 'test.zip';
//
//		$zip->open($zipPath, ZipArchive::CREATE);
//		$addFile = dirname(dirname(__DIR__)) . DS . 'Fixture' . DS . 'logo.gif';
//
//		$zip->addFile($addFile);
//		$zip->close();
//
//		$this->assertFileExists($zipPath);
//
//		$unzip = new ZipArchive();
//		$unzip->open($zipPath);
//		$unzipFolder = new TemporaryFolder();
//		$unzip->extractTo($unzipFolder->path);
//
//		$this->assertFileExists($unzipFolder->path . DS . 'logo.gif');
//		$fileSize = filesize($unzipFolder->path . DS . 'logo.gif');
//		$this->assertTrue($fileSize > 0);
//	}
//
///**
// * test creat zip with password
// *
// * @return void
// */
//	public function testCreateZipWithPassword() {
//		$zip = new NetCommonsZip();
//		$tmpFolder = new TemporaryFolder();
//		//$tmpFolder = new Folder(TMP . 'test', true);
//		$zipPath = $tmpFolder->path . DS . 'test.zip';
//
//		$zip->open($zipPath, ZipArchive::CREATE);
//		$addFile = dirname(dirname(__DIR__)) . DS . 'Fixture' . DS . 'logo.gif';
//
//		$zip->addFile($addFile);
//
//		$zip->setPassword('test');
//
//		$zip->close();
//
//		$this->assertFileExists($zipPath);
//
//		$unzip = new NetCommonsZip();
//		$unzip->open($zipPath);
//		$unzipFolder = new TemporaryFolder();
//		$unzip->setPassword('test');
//		$unzip->extractTo($unzipFolder->path);
//
//		$this->assertFileExists($unzipFolder->path . DS . 'logo.gif');
//		$fileSize = filesize($unzipFolder->path . DS . 'logo.gif');
//		$this->assertTrue($fileSize > 0);
//		//sleep(60);
//	}
//
///**
// * test add folder
// *
// * @return void
// */
//	public function testAddFolder() {
//		$zip = new NetCommonsZip();
//		$tmpFolder = new TemporaryFolder();
//		//$tmpFolder = new Folder(TMP . 'test', true);
//		$zipPath = $tmpFolder->path . DS . 'test.zip';
//
//		$zip->open($zipPath, ZipArchive::CREATE);
//
//		$addFolder = dirname(dirname(__DIR__)) . DS . 'Fixture';
//
//		$zip->addFolder($addFolder);
//		$zip->close();
//		$this->assertFileExists($zipPath);
//	}
//
///**
// * test add from string
// *
// * @return void
// */
//	public function testAddFromString() {
//		$zip = new NetCommonsZip();
//		$tmpFolder = new TemporaryFolder();
//		$zip->open($tmpFolder->path . DS . 'test.zip', true);
//
//		$zip->addFromString('foo.txt', 'foo');
//
//		$zip->close();
//
//		$unzip = new NetCommonsZip();
//		$unzip->open($tmpFolder->path . DS . 'test.zip');
//		$unzipFolder = new TemporaryFolder();
//		$unzip->extractTo($unzipFolder->path);
//
//		$contents = file($unzipFolder->path . DS . 'foo.txt');
//		$this->assertEquals('foo', $contents[0]);
//	}
//
//	public function XtestRisou() {
//		$zip = new ZipDownloader();
//		// アーカイブ先を指定する必要はない
//		$zip->addFromString('foo.txt', 'foo');
//		$folderPath = TMP . 'log';
//		$zip->addFolder($folderPath);
//		$zip->setPassword('password');
//		return $zip->download('file.zip');
//
//	}
}