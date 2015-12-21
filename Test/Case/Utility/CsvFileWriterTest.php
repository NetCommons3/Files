<?php
/**
 * CsvFileWriterTest
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');
App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');
App::uses('CsvFileReader', 'Files.Utility');
App::uses('CsvFileWriter', 'Files.Utility');

/**
 * Summary for CsvFileWriter Test Case
 *
 */
class CsvFileWriterTest extends NetCommonsCakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = [];

/**
 * test read
 *
 * @return void
 */
	public function testWrite() {
		$csvFilePath = dirname(dirname(__DIR__)) . '/Fixture/sample_csv_excel2010.csv';
		$csvReader = new CsvFileReader($csvFilePath);
		$lines = array();
		foreach ($csvReader as $line) {
			$lines[] = $line;
		}

		$writer = new CsvFileWriter();
		foreach ($lines as $line) {
			$writer->add($line);
		}
		$writer->close();

		$csvReader = new CsvFileReader($writer->path);
		foreach ($csvReader as $line) {
			debug($line);
		}
	}

	public function testRenameJapaneseFilename() {
		$tmpFile = new TemporaryFile();
		$result = rename($tmpFile->path, '日本語ファイル名.csv');

		$this->assertTrue($result);
	}

/**
 * CSVに日本語ファイル名を指定したときの問題
 *
 * @see https://github.com/NetCommons3/Files/issues/39
 * @return void
 */
	public function testAddFileJapaneseFilename() {
		$file = dirname(dirname(__DIR__)) . DS . 'Fixture' . DS . 'logo.gif';
		$addFile = TMP . '日本語ファイル名.gif';
		copy($file, $addFile);

		$zipDownloader = new ZipDownloader();
		$zipDownloader->addFile($addFile);

		$property = new ReflectionProperty($zipDownloader, '_tmpFolder');
		$property->setAccessible(true);
		$folderPath = $property->getValue($zipDownloader)->path;

		$this->assertFileExists($folderPath . DS . '日本語ファイル名.gif');
	}
}