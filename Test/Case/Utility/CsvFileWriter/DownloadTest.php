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
		$filename = null;

		//テスト実施
		//$result = $this->download($filename);

		//チェック
		//TODO:assertを書く
		//debug($result);
	}

}
