<?php
/**
 * NetCommonsFile::getTemporaryFileConvertSjisWin2Utf8()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');
App::uses('NetCommonsFile', 'Files.Utility');

/**
 * NetCommonsFile::getTemporaryFileConvertSjisWin2Utf8()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Files\Test\Case\Utility\NetCommonsFile
 */
class UtilityNetCommonsFileGetTemporaryFileConvertSjisWin2Utf8Test extends NetCommonsCakeTestCase {

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'files';

/**
 * getTemporaryFileConvertSjisWin2Utf8()のテスト
 *
 * @return void
 */
	public function testGetTemporaryFileConvertSjisWin2Utf8() {
		$filePath = dirname(dirname(dirname(__DIR__))) . '/Fixture/sample_csv_excel2010.csv';
		$file = NetCommonsFile::getTemporaryFileConvertSjisWin2Utf8($filePath);
		//debug($file);
		$fileContent = file_get_contents($file->path);
		$encoding = mb_detect_encoding($fileContent);
		$this->assertEquals('UTF-8', $encoding);
	}
}
