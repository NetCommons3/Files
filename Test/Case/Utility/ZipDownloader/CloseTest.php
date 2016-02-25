<?php
/**
 * ZipDownloader::close()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');

/**
 * ZipDownloader::close()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Files\Test\Case\Utility\ZipDownloader
 */
class UtilityZipDownloaderCloseTest extends NetCommonsCakeTestCase {

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'files';

/**
 * close()のテスト
 *
 * @return void
 */
	public function testClose() {
		$zip = new ZipDownloader();
		$zip->addFromString('foo.txt', 'foo');
		$openProperty = new ReflectionProperty($zip, '_open');
		$openProperty->setAccessible(true);
		$open = $openProperty->getValue($zip);
		// open
		$this->assertTrue($open);
		$zip->close();
		//close
		$open = $openProperty->getValue($zip);
		$this->assertFalse($open);
	}

/**
 * zip コマンド失敗のテスト
 *
 * @return void
 */
	public function testCloseFailed() {
		$zip = new ZipDownloader();
		$zip->addFromString('foo.txt', 'foo');
		$zip->setPassword('password');
		$zipCommandProperty = new ReflectionProperty($zip, '_zipCommand');
		$zipCommandProperty->setAccessible(true);
		$zipCommandProperty->setValue($zip, 'detaramena_command');

		$false = $zip->close();
		$this->assertFalse($false);
	}

}
