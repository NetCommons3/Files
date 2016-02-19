<?php
/**
 * ZipDownloaderTest
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');
App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');
App::uses('TemporaryFolder', 'Files.Utility');
App::uses('ZipDownloader', 'Files.Utility');
App::uses('UnZip', 'Files.Utility');
/**
 * Summary for CsvFileWriter Test Case
 *
 * test.zip
 * -test/
 *  - bar
 *  - foo
 *  - hoge/
 *   - hogehoge in "hogehoge file"
 */
class ZipDownloaderTest extends CakeTestSuite {

/**
 * All test suite
 *
 * @return CakeTestSuite
 */
	public static function suite() {
		$name = __CLASS__;
		$path = __DIR__ . DS . substr($name, 0, -4); // 末尾のTest"を除外
		$suite = new CakeTestSuite(sprintf('All %s tests', $name));
		$suite->addTestDirectoryRecursive($path
		);
		return $suite;
	}
}
