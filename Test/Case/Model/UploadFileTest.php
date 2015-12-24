<?php
/**
 * UploadFile Test Case
 *
* @author Noriko Arai <arai@nii.ac.jp>
* @author Your Name <yourname@domain.com>
* @link http://www.netcommons.org NetCommons Project
* @license http://www.netcommons.org/license.txt NetCommons License
* @copyright Copyright 2014, NetCommons Project
 */

App::uses('UploadFile', 'Files.Model');
App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');
App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * Summary for UploadFile Test Case
 *
 * @property UploadFile $UploadFile
 * @property UploadFilesContent $UploadFilesContent
 */
class UploadFileTest extends NetCommonsCakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = [

	];

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->UploadFile = ClassRegistry::init('Files.UploadFile');
		$this->UploadFilesContent = ClassRegistry::init('Files.UploadFilesContent');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->UploadFile);

		parent::tearDown();
	}

/**
 * testSetOptions method
 *
 * @return void
 */
	public function testSetOptions() {
	}

/**
 * testRemoveFile method
 *
 * @return void
 */
	public function testRemoveFile() {
		$contentId = 2;
		$fileId = 1;
		// uploadビヘイビアが動作して実態ファイル削除を実行する…がここではUploadビヘイビアを外してDBレベルのテスト
		$this->UploadFile->Behaviors->unload('Upload');
		$this->UploadFile->removeFile($contentId, $fileId);

		$conditions = [
			'upload_file_id' => $fileId,
			'content_id' => $contentId,
		];
		// 関連テーブルが削除される
		$count = $this->UploadFilesContent->find('count', ['conditions' => $conditions]);
		$this->assertEquals(0, $count);


		// 他に関連がないのでファイルレコードも削除
		$count = $this->UploadFile->find('count', ['conditions' => ['id' => 1]]);
		$this->assertEquals(0, $count);
	}

/**
 * testNameCallback method
 *
 * @return void
 */
	public function testNameCallback() {
	}

/**
 * testGetFile method
 *
 * @return void
 */
	public function testGetFile() {
	}

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
 * testRegistByFile method
 *
 * @return void
 */
	public function testRegistByFile() {
	}

/**
 * testRegistByFilePath method
 *
 * @return void
 */
	public function testRegistByFilePath() {
	}

/**
 * testMakeLink method
 *
 * @return void
 */
	public function testMakeLink() {
	}

/**
 * testDeleteLink method
 *
 * @return void
 */
	public function testDeleteLink() {
	}

/**
 * testAttach method
 *
 * @return void
 */
	public function testAttach() {
	}

}
