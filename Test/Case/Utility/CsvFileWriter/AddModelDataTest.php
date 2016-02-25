<?php
/**
 * CsvFileWriter::addModelData()のテスト
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
 * CsvFileWriter::addModelData()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Files\Test\Case\Utility\CsvFileWriter
 */
class UtilityCsvFileWriterAddModelDataTest extends NetCommonsCakeTestCase {

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'files';

/**
 * addModelData()のテスト
 *
 * @return void
 */
	public function testAddModelData() {
		$header = [
			'BlogEntry.id' => 'データID',
			'BlogEntry.title' => 'タイトル',
			'BlogEntry.body1' => '本文1',
			'BlogEntry.publish_start' => '公開日時'
		];
		$blogEntries = [
			0 => [
				'BlogEntry' => [
					'id' => 1,
					'title' => '記事タイトル1',
					'body1' => '記事本文1',
					'publish_start' => '2015-12-31 00:00:00'
				],
			],
			1 => [
				'BlogEntry' => [
					'id' => 2,
					'title' => '記事タイトル2',
					'body1' => '記事本文2',
					'publish_start' => '2015-12-31 01:00:00'
				],
			]
		];
		$csvWriter = new CsvFileWriter(['header' => $header]);
		foreach($blogEntries as $data){
			$csvWriter->addModelData($data);
		}
		$csvWriter->close();

		//チェック
		$csvReader = new CsvFileReader($csvWriter->path);

		foreach ($csvReader as $index => $resultLine) {
			if ($index === 0) {
				// header
				$this->assertEquals(array_values($header), $resultLine);
			}
			if ($index > 0) {
				$this->assertEquals(array_values(Hash::flatten($blogEntries[$index - 1])), $resultLine);
			}
		}
	}

/**
 * addModelData()のテスト headerで指定されたカラムだけCSV出力されるかのテスト
 *
 * @return void
 */
	public function testAddModelDataFilterHeader() {
		$header = [
			'BlogEntry.id' => 'データID',
			'BlogEntry.title' => 'タイトル',
			//'BlogEntry.body1' => '本文1',
			//'BlogEntry.publish_start' => '公開日時'
		];
		$blogEntries = [
			0 => [
				'BlogEntry' => [
					'id' => 1,
					'title' => '記事タイトル1',
					'body1' => '記事本文1',
					'publish_start' => '2015-12-31 00:00:00'
				],
			],
			1 => [
				'BlogEntry' => [
					'id' => 2,
					'title' => '記事タイトル2',
					'body1' => '記事本文2',
					'publish_start' => '2015-12-31 01:00:00'
				],
			]
		];
		$csvWriter = new CsvFileWriter(['header' => $header]);
		foreach($blogEntries as $data){
			$csvWriter->addModelData($data);
		}
		$csvWriter->close();

		//チェック
		$csvReader = new CsvFileReader($csvWriter->path);

		foreach ($csvReader as $index => $resultLine) {
			if ($index === 0) {
				// header
				$this->assertEquals(array_values($header), $resultLine);
			}
			// id, titleだけがCSVに出力される
			if ($index > 0) {
				$this->assertEquals($blogEntries[$index - 1]['BlogEntry']['id'], $resultLine[0]);
				$this->assertEquals($blogEntries[$index - 1]['BlogEntry']['title'], $resultLine[1]);
			}
		}
	}

/**
 * addModelData()のテスト header指定無しのケース
 *
 * @return void
 */
	public function testAddModelDataNoFilter() {
		//$header = [
		//	'BlogEntry.id' => 'データID',
		//	'BlogEntry.title' => 'タイトル',
		//	'BlogEntry.body1' => '本文1',
		//	'BlogEntry.publish_start' => '公開日時'
		//];
		$blogEntries = [
			0 => [
				'BlogEntry' => [
					'id' => 1,
					'title' => '記事タイトル1',
					'body1' => '記事本文1',
					'publish_start' => '2015-12-31 00:00:00'
				],
			],
			1 => [
				'BlogEntry' => [
					'id' => 2,
					'title' => '記事タイトル2',
					'body1' => '記事本文2',
					'publish_start' => '2015-12-31 01:00:00'
				],
			]
		];
		$csvWriter = new CsvFileWriter();
		foreach($blogEntries as $data){
			$csvWriter->addModelData($data);
		}
		$csvWriter->close();

		//チェック
		$csvReader = new CsvFileReader($csvWriter->path);

		foreach ($csvReader as $index => $resultLine) {
			//if ($index === 0) {
			//	// header
			//	$this->assertEquals(array_values($header), $resultLine);
			//}
			//if ($index > 0) {
				$this->assertEquals(array_values(Hash::flatten($blogEntries[$index ])), $resultLine);
			//}
		}
	}
}
