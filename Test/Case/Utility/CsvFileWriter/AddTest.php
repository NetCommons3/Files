<?php
/**
 * CsvFileWriter::add()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');
App::uses('CsvFileReader', 'Files.Utility');
App::uses('CsvFileWriter', 'Files.Utility');

/**
 * CsvFileWriter::add()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Files\Test\Case\Utility\CsvFileWriter
 */
class UtilityCsvFileWriterAddTest extends NetCommonsCakeTestCase {

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'files';

/**
 * add()のテスト
 *
 * @return void
 */
	public function testAdd() {
		debug(mb_internal_encoding());
		mb_internal_encoding('UTF-8');
		$lines = array();
		$lines[] = array(0, 1, 2, 3, 4, 5);
		$lines[] = array(
			'カンマの入った文字列,この手前にカンマ',
			'ダブルクォート"の入った文字列',
			'途中に改行
が入ってる文字列',
			'途中に￥が入ってる文字列\この手前にあり',
			'Foo',
			'Bar'
		);
		$writer = new CsvFileWriter();
		foreach ($lines as $line) {
			$writer->add($line);
		}
		$writer->close();

		$csvReader = new CsvFileReader($writer->path);
		foreach ($csvReader as $index => $resultLine) {
			$this->assertEquals($lines[$index], $resultLine);
		}
	}
}
