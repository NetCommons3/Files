<?php
/**
 * ZipDownloader::addFromString()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');

/**
 * ZipDownloader::addFromString()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Files\Test\Case\Utility\ZipDownloader
 */
class UtilityZipDownloaderAddFromStringTest extends NetCommonsCakeTestCase {

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'files';

/**
 * addFromString()のテスト
 *
 * @return void
 */
	public function testAddFromString() {
		//データ生成
		$localname = null;
		$contents = null;

		//テスト実施
		//$result = $this->addFromString($localname, $contents);

		//チェック
		//TODO:assertを書く
		//debug($result);
	}

}
