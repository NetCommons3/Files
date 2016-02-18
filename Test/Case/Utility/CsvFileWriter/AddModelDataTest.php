<?php
/**
 * CsvFileWriter::addModelData()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');

/**
 * CsvFileWriter::addModelData()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
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
		//データ生成
		array =  $data;

		//テスト実施
		//$result = $this->addModelData(array);

		//チェック
		//TODO:assertを書く
		//debug($result);
	}

}
