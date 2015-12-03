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
}