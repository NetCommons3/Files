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
App::uses('NetCommonsZip', 'Files.Utility');

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
class NetCommonsZipTest extends NetCommonsCakeTestCase {

	public $fixtures = [];

	public function testUnzip(){
		$zip = new NetCommonsZip();
		$zip->open(dirname(dirname(__DIR__)) . DS . 'Fixture/test.zip' );
		$tmpFolder = new TemporaryFolder();
		$zip->extractTo($tmpFolder->path);
		$this->assertFileExists($tmpFolder->path . DS . 'test');
		$this->assertFileExists($tmpFolder->path . DS . 'test' .DS .'bar');
		$this->assertFileExists($tmpFolder->path . DS . 'test' .DS . 'hoge');
		$this->assertFileExists($tmpFolder->path . DS . 'test' .DS . 'hoge' . DS . 'hogehoge');

		$contents = file($tmpFolder->path . DS . 'test' .DS . 'hoge' . DS . 'hogehoge');
		$this->assertEquals('hogehoge file', $contents[0]);

	}

	public function testCreateZipNoPassword() {
		$zip = new NetCommonsZip();
		$tmpFolder = new TemporaryFolder();
		//$tmpFolder = new Folder(TMP . 'test', true);
		$zipPath = $tmpFolder->path . DS . 'test.zip';

		$zip->open($zipPath, ZipArchive::CREATE);
		$addFile = dirname(dirname(__DIR__)) . DS . 'Fixture' . DS . 'logo.gif';

		$zip->addFile($addFile);
		$zip->close();

		$this->assertFileExists($zipPath);

		$unzip = new ZipArchive();
		$unzip->open($zipPath);
		$unzipFolder = new TemporaryFolder();
		$unzip->extractTo($unzipFolder->path);

		$this->assertFileExists($unzipFolder->path . DS . 'logo.gif');
		$fileSize = filesize($unzipFolder->path . DS . 'logo.gif');
		$this->assertTrue($fileSize > 0);

	}


	public function testCreateZipWithPassword() {
		$zip = new NetCommonsZip();
		$tmpFolder = new TemporaryFolder();
		//$tmpFolder = new Folder(TMP . 'test', true);
		$zipPath = $tmpFolder->path . DS . 'test.zip';

		$zip->open($zipPath, ZipArchive::CREATE);
		$addFile = dirname(dirname(__DIR__)) . DS . 'Fixture' . DS . 'logo.gif';

		$zip->addFile($addFile);

		$zip->setPassword('test');

		$zip->close();

		$this->assertFileExists($zipPath);

		$unzip = new NetCommonsZip();
		$unzip->open($zipPath);
		$unzipFolder = new TemporaryFolder();
		$unzip->setPassword('test');
		$unzip->extractTo($unzipFolder->path);

		$this->assertFileExists($unzipFolder->path . DS . 'logo.gif');
		$fileSize = filesize($unzipFolder->path . DS . 'logo.gif');
		$this->assertTrue($fileSize > 0);
		//sleep(60);
	}


	public function testAddFolder(){
		$zip = new NetCommonsZip();
		$tmpFolder = new TemporaryFolder();
		//$tmpFolder = new Folder(TMP . 'test', true);
		$zipPath = $tmpFolder->path . DS . 'test.zip';

		$zip->open($zipPath, ZipArchive::CREATE);

		$addFolder = dirname(dirname(__DIR__)) . DS . 'Fixture';

		$zip->addFolder($addFolder);
		$zip->close();
		$this->assertFileExists($zipPath);

	}

	public function testAddFromString() {
		$zip = new NetCommonsZip();
		$tmpFolder = new TemporaryFolder();
		$zip->open($tmpFolder->path . DS . 'test.zip', true);

		$zip->addFromString('foo.txt', 'foo');

		$zip->close();

		$unzip = new NetCommonsZip();
		$unzip->open($tmpFolder->path . DS . 'test.zip');
		$unzipFolder = new TemporaryFolder();
		$unzip->extractTo($unzipFolder->path);

		$contents = file($unzipFolder->path . DS . 'foo.txt');
		$this->assertEquals('foo', $contents[0]);

	}


}