<?php
/**
 * NetCommonsFileTest
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');
App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');
App::uses('NetCommonsFile', 'Files.Utility');

class NetCommonsFileTest extends NetCommonsCakeTestCase {

/**
 * @var array fixture
 */
	public $fixtures = [];

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