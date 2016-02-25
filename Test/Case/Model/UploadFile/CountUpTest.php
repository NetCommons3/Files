<?php
/**
 * UploadFile::countUp()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsSaveTest', 'NetCommons.TestSuite');
App::uses('UploadFileFixture', 'Files.Test/Fixture');

/**
 * UploadFile::countUp()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Files\Test\Case\Model\UploadFile
 */
class UploadFileCountUpTest extends NetCommonsSaveTest {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.files.upload_file',
		'plugin.files.upload_files_content',
	);

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'files';

/**
 * Model name
 *
 * @var string
 */
	protected $_modelName = 'UploadFile';

/**
 * Method name
 *
 * @var string
 */
	protected $_methodName = 'countUp';

/**
 * testCountUp method
 *
 * @return void
 */
	public function testCountUp() {
		$file = $this->UploadFile->findById(1);
		$beforeCount = $file['UploadFile']['download_count'];
		$this->UploadFile->countUp($file);
		$afterFile = $this->UploadFile->findById(1);
		$this->assertEquals($beforeCount + 1, $afterFile['UploadFile']['download_count']);
	}

/**
 * Save用DataProvider
 *
 * ### 戻り値
 *  - data 登録データ
 *
 * @return array テストデータ
 */
	public function dataProviderSave() {
		$data['UploadFile'] = (new UploadFileFixture())->records[1];
		$data['UploadFile']['status'] = '1';

		//TODO:テストパタンを書く
		$results = array();
		// * 編集の登録処理
		$results[0] = array($data);
		// * 新規の登録処理
		$results[1] = array($data);
		$results[1] = Hash::insert($results[1], '0.UploadFile.id', null);
		$results[1] = Hash::insert($results[1], '0.UploadFile.key', null);
		$results[1] = Hash::remove($results[1], '0.UploadFile.created_user');

		return $results;
	}

/**
 * SaveのExceptionError用DataProvider
 *
 * ### 戻り値
 *  - data 登録データ
 *  - mockModel Mockのモデル
 *  - mockMethod Mockのメソッド
 *
 * @return array テストデータ
 */
	public function dataProviderSaveOnExceptionError() {
		$data['UploadFile'] = (new UploadFileFixture())->records[0];

		//TODO:テストパタンを書く
		return array(
			array($data, 'Files.UploadFile', 'save'),
		);
	}

/**
 * SaveのValidationError用DataProvider
 *
 * ### 戻り値
 *  - data 登録データ
 *  - mockModel Mockのモデル
 *  - mockMethod Mockのメソッド(省略可：デフォルト validates)
 *
 * @return array テストデータ
 */
	public function dataProviderSaveOnValidationError() {
		$data['UploadFile'] = (new UploadFileFixture())->records[0];

		//TODO:テストパタンを書く
		return array(
			array($data, 'Files.UploadFile'),
		);
	}

}
