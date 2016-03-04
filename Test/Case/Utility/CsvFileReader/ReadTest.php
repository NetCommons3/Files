<?php
/**
 * CsvFileReader::valid()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');
App::uses('CsvFileReader', 'Files.Utility');

/**
 * CsvFileReader::valid()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Files\Test\Case\Utility\CsvFileReader
 */
class UtilityCsvFileReaderReadTest extends NetCommonsCakeTestCase {

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'files';

/**
 * test read
 *
 * @return void
 */
	public function testRead() {
		$csvFilePath = dirname(dirname(dirname(__DIR__))) . '/Fixture/sample_csv_excel2010.csv';
		$csvReader = new CsvFileReader($csvFilePath);

		$result = iterator_to_array($csvReader);
		$this->assertEquals([1, 2, 3, 4, 5, 6], $result[0]);
		$this->assertEquals([
			'カンマの入った文字列,この手前にカンマ',
			'ダブルクォート"の入った文字列',
			'途中に改行
が入ってる文字列',
			'途中に￥が入ってる文字列\この手前にあり',
			'',
			''
		], $result[1]);
	}

/**
 * test read Fileインスタンスを渡したとき
 *
 * @return void
 */
	public function testReadFromFileObject() {
		$csvFilePath = dirname(dirname(dirname(__DIR__))) . '/Fixture/sample_csv_excel2010.csv';
		$csvFile = new File($csvFilePath);
		$csvReader = new CsvFileReader($csvFile);

		$result = iterator_to_array($csvReader);

		$this->assertEquals([1, 2, 3, 4, 5, 6], $result[0]);
		$this->assertEquals([
			'カンマの入った文字列,この手前にカンマ',
			'ダブルクォート"の入った文字列',
			'途中に改行
が入ってる文字列',
			'途中に￥が入ってる文字列\この手前にあり',
			'',
			''
		], $result[1]);
	}
}
