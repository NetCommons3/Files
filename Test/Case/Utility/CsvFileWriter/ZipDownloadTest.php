<?php
/**
 * CsvFileWriter::zipDownload()のテスト
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
 * CsvFileWriter::zipDownload()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Files\Test\Case\Utility\CsvFileWriter
 */
class UtilityCsvFileWriterZipDownloadTest extends NetCommonsCakeTestCase {

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'files';

/**
 * zipDownload()のテスト
 *
 * @return void
 */
	public function testZipDownload() {
		//データ生成
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

		$zipFilename = 'test.zip';
		$csvFilename = 'test.csv';
		$password = 'password';
		//テスト実施
		$response = $writer->zipDownload($zipFilename, $csvFilename, $password);
		$this->assertInstanceOf('CakeResponse', $response);
		$this->assertEquals('application/zip', $response->type());
		//debug($response->header());

	}

/**
 * 日本語ファイルリネームテスト
 *
 * ZIPダウンロードするときに日本語ファイル名をつけることがあるが、日本語ファイル名にちゃんとリネームできるかのテスト（ファイルシステムによってはNGになるかも）
 *
 * @return void
 */
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
		$file = dirname(dirname(dirname(__DIR__))) . DS . 'Fixture' . DS . 'logo.gif';
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
