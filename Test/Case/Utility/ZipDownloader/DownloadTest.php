<?php
/**
 * ZipDownloader::download()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');
App::uses('CakeResponse', 'Network');

/**
 * ZipDownloader::download()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Files\Test\Case\Utility\ZipDownloader
 */
class UtilityZipDownloaderDownloadTest extends NetCommonsCakeTestCase {

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'files';

/**
 * download()のテスト
 *
 * @return void
 */
	public function testDownload() {
		//データ生成
		$zip = new ZipDownloader();
		$zip->addFromString('foo.txt', 'foo');
		//$folderPath = TMP . 'log';
		//$zip->addFolder($folderPath);
		$zip->setPassword('password');
		$response = $zip->download('zipfile.zip');

		//テスト実施
		$this->assertInstanceOf('CakeResponse', $response);
		$this->assertEquals('application/zip', $response->type());
	}

/**
 * ZIPダウンロードの利用例
 *
 * @return CakeResponse
 */
	public function useZipDownloadExample() {
		$zip = new ZipDownloader();
		$zip->addFromString('foo.txt', 'foo');
		$folderPath = TMP . 'log';
		$zip->addFolder($folderPath);
		$zip->setPassword('password');
		return $zip->download('file.zip');
	}

}
