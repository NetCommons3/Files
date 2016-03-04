<?php
/**
 * NetCommonsFile::basename()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');
App::uses('NetCommonsFile', 'Files.Utility');

/**
 * NetCommonsFile::basename()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
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
 * test basename
 *
 * @return void
 */
	public function testBasename() {
		$path = TMP . '日本語ファイル名.gif';
		$basename = NetCommonsFile::basename($path);
		$this->assertEquals($basename, '日本語ファイル名.gif');
	}

/**
 * test basenamae フォルダパス無しでの動作
 *
 * @return void
 */
	public function testBasenameForOnlyFile() {
		$path = '日本語ファイル名.gif';
		$basename = NetCommonsFile::basename($path);
		$this->assertEquals($basename, '日本語ファイル名.gif');
	}

}
