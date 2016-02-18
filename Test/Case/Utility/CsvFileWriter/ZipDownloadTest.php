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
		$zipFilename = null;
		$csvFilename = null;
		$password = null;

		//テスト実施
		//$result = $this->zipDownload($zipFilename, $csvFilename, $password);

		//チェック
		//TODO:assertを書く
		//debug($result);
	}

}
