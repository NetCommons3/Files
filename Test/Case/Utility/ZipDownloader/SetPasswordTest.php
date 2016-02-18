<?php
/**
 * ZipDownloader::setPassword()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');

/**
 * ZipDownloader::setPassword()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Files\Test\Case\Utility\ZipDownloader
 */
class UtilityZipDownloaderSetPasswordTest extends NetCommonsCakeTestCase {

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'files';

/**
 * setPassword()のテスト
 *
 * @return void
 */
	public function testSetPassword() {
		//データ生成
		$password = null;

		//テスト実施
		//$result = $this->setPassword($password);

		//チェック
		//TODO:assertを書く
		//debug($result);
	}

}
