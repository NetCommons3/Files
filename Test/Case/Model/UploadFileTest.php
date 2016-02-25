<?php
/**
 * UploadFile Test Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
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
		copy(dirname(dirname(__DIR__)) . DS . 'Fixture' . DS . 'logo.gif', TMP . 'logo.gif');
		$file = new File(TMP . 'logo.gif');
		$pluginKey = 'files';
		$contentKey = 'content_key_1';
		$fieldName = 'image';
		$data = $this->UploadFile->registByFile($file, $pluginKey, $contentKey, $fieldName);

		$this->assertTrue($data['UploadFile']['id'] > 0);

		$this->UploadFile->delete($this->UploadFile->id);
	}

/**
 * testRegistByFilePath method
 *
 * @return void
 */
	public function testRegistByFilePath() {
		copy(dirname(dirname(__DIR__)) . DS . 'Fixture' . DS . 'logo.gif', TMP . 'logo.gif');
		$pluginKey = 'files';
		$contentKey = 'content_key_1';
		$fieldName = 'image';

		// registByFileのラップメソッドなので、registByFileがコールされてるかテストする
		$UploadFileMock = $this->getMockForModel('Files.UploadFile', ['registByFile']);
		$UploadFileMock->expects($this->once())
			->method('registByFile')
			->with(
				$this->isInstanceOf('File'),
				$this->equalTo($pluginKey),
				$this->equalTo($contentKey),
				$this->equalTo($fieldName)
			);

		$UploadFileMock->registByFilePath(TMP . 'logo.gif', $pluginKey, $contentKey, $fieldName);
	}

/**
 * testMakeLink method
 *
 * @return void
 */
	public function testMakeLink() {
		$pluginKey = 'files';
		$contentId = 1;
		$uploadFileId = 1;
		$this->UploadFile->makeLink($pluginKey, $contentId, $uploadFileId);

		// 関連レコードが挿入されてることを確認
		$conditions = [
			'UploadFilesContent.plugin_key' => $pluginKey,
			'UploadFilesContent.content_id' => $contentId,
			'UploadFilesContent.upload_file_id' => $uploadFileId
		];
		$link = $this->UploadFilesContent->find('first', ['conditions' => $conditions]);

		$this->assertTrue($link['UploadFilesContent']['id'] > 0);
	}

/**
 * testDeleteLink method
 *
 * @return void
 */
	public function testDeleteLink() {
		$pluginKey = 'site_manager';
		$contentId = 2;
		$fieldName = 'photo';
		// Uploadビヘイビアを無効にしておく
		$this->UploadFile->Behaviors->unload('Upload');
		$this->UploadFile->deleteLink($pluginKey, $contentId, $fieldName);

		// fixtureで挿入された関連レコードが削除されてること
		$count = $this->UploadFilesContent->find('count', ['conditions' => ['UploadFilesContent.id' => 1]]);
		$this->assertEquals(0, $count);

		// fixtureで挿入されたUploadFileレコードも削除されること（他に関連がないので）
		$count = $this->UploadFile->find('count', ['conditions' => ['UploadFile.id' => 1]]);
		$this->assertEquals(0, $count);
	}


}
