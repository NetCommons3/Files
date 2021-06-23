<?php
/**
 * ExistsRealFileByModelRecordTest.php
 *
 * @author Japan Science and Technology Agency
 * @author National Institute of Informatics
 * @link http://researchmap.jp researchmap Project
 * @link http://www.netcommons.org NetCommons Project
 * @license http://researchmap.jp/public/terms-of-service/ researchmap license
 * @copyright Copyright 2017, researchmap Project
 */
\App::uses('ControllerTestCase', 'TestSuite');
\App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');
\App::uses('DownloadComponent', 'Files.Controller/Component');
\App::uses('ComponentCollection', 'Controller');

/**
 * Class DownloadComponentExistsRealFileByModelRecordTest
 */
final class DownloadComponentExistsRealFileByModelRecordTest extends \NetCommonsControllerTestCase {

/**
 * setUp
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		// uploadBasePathをテスト用に変更する。
		$uploadFile = ClassRegistry::init('Files.UploadFile');
		$uploadFile->uploadBasePath = \CakePlugin::path('Files') . 'Test' . DS . 'Fixture' . DS;
	}

/**
 * testExists
 *
 * @return void
 */
	public function testExists() {
		$download = new DownloadComponent(new ComponentCollection());

		$data = [
			'UploadFile' => [
				'file' => [
					'id' => '1',
					'path' => 'files/upload_file/real_file_name/1/',
					'real_file_name' => 'foobarhash.jpg'
				]
			]
		];
		$result = $download->existsRealFileByModelRecord($data, 'file', 'small');

		$this->assertTrue($result);
	}

/**
 * testNotExists
 *
 * @return void
 */
	public function testNotExists() {
		$download = new DownloadComponent(new ComponentCollection());

		$data = [
			'UploadFile' => [
				'file' => [
					'id' => '1',
					'path' => 'files/upload_file/real_file_name/1/',
					'real_file_name' => 'foobarhash.jpg'
				]
			]
		];
		$result = $download->existsRealFileByModelRecord($data, 'file', 'not_found_size');

		$this->assertFalse($result);
	}

}