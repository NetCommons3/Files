<?php
/**
 * CsvFileWriter::download()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');
App::uses('CsvFileReader', 'Files.Utility');
App::uses('CsvFileWriter', 'Files.Utility');
App::uses('CakeResponse', 'Network');
/**
 * CsvFileWriter::download()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Files\Test\Case\Utility\CsvFileWriter
 */
class UtilityCsvFileWriterDownloadTest extends NetCommonsCakeTestCase {

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'files';

/**
 * download()のテスト
 *
 * @return void
 */
	public function testDownload() {
		//データ生成
		$csvFilePath = dirname(dirname(dirname(__DIR__))) . '/Fixture/sample_csv_excel2010.csv';
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

		//テスト実施
		$response = $writer->download('test.csv');
		$this->assertInstanceOf('CakeResponse', $response);
		$this->assertEquals('text/csv', $response->type());
		//debug($response->header());
	}

}
