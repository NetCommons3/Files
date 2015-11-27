<?php
/**
 * CsvFileReaderTest
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');
App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');
App::uses('CsvFileReader', 'Files.Utility');

/**
 * Summary for AttachmentBehavior Test Case
 *
 * @property TestCreateProfile $SiteSetting テスト用モデル
 */
class CsvFileReaderTest extends NetCommonsCakeTestCase {

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
	public function testRead() {
		$csvFilePath = dirname(dirname(__DIR__)) . '/Fixture/sample_csv_excel2010.csv';
		$csvReader = new CsvFileReader($csvFilePath);

		foreach ($csvReader as $line) {
			debug($line);
		}
	}
}