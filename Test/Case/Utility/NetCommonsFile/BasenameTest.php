<?php
/**
 * NetCommonsFile::basename()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');

/**
 * NetCommonsFile::basename()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Files\Test\Case\Utility\NetCommonsFile
 */
class UtilityNetCommonsFileBasenameTest extends NetCommonsCakeTestCase {

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'files';

/**
 * basename()のテスト
 *
 * @return void
 */
	public function testBasename() {
		//データ生成
		$path = null;

		//テスト実施
		//$result = $this->basename($path);

		//チェック
		//TODO:assertを書く
		//debug($result);
	}

}
