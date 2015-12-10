<?php
/**
 * CsvFileWriterTest
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');
App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');
App::uses('CsvFileReader', 'Files.Utility');
App::uses('CsvFileWriter', 'Files.Utility');

/**
 * Summary for CsvFileWriter Test Case
 *
 */
class ZipFileTest extends NetCommonsCakeTestCase {

	public $fixtures = [];

	public function testZip() {
		$zip = new ZipArchive();
		$zip->open(TMP . DS . 'tmp.zip', ZipArchive::CREATE);
		$zip->addFromString('foo', 'bar');
		sleep(100);
	}

}