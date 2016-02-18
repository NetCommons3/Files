<?php
/**
 * ZipDownloader::addFile()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');

/**
 * ZipDownloader::addFile()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Files\Test\Case\Utility\ZipDownloader
 */
class UtilityZipDownloaderAddFileTest extends NetCommonsCakeTestCase {

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'files';

/**
 * addFile()のテスト
 *
 * @return void
 */
	public function testAddFile() {
		//データ生成
		$filePath = null;
		$localName = null;

		//テスト実施
		//$result = $this->addFile($filePath, $localName);

		//チェック
		//TODO:assertを書く
		//debug($result);
	}

}
