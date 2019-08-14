<?php
App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');
App::uses('TestAttachmentBehaviorSaveModelFixture', 'Files.Test/Fixture');

class AfterSaveByAttachmentTest extends NetCommonsModelTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.files.test_attachment_behavior_save_model',
		'plugin.site_manager.site_setting',
	);

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'files';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		//テストプラグインのロード
		NetCommonsCakeTestCase::loadTestPlugin($this, 'Files', 'TestFiles');
		$this->TestModel = ClassRegistry::init('TestFiles.TestAttachmentBehaviorSaveModel');

		copy(APP . 'Plugin/Files/Test/Fixture/logo.gif', TMP . '/test.gif');
	}

/**
 * UploadFile::saveが失敗したら例外とエラーログの記録
 *
 * @return void
 */
	public function testUploadFileSaveIsFailed() {
		$uploadFile = $this->getMockForModel('Files.UploadFile', ['save']);
		$uploadFile->expects($this->once())
			->method('save')
			->will($this->returnValue(false));

		$data = [
			'key' => 1,
			'photo' => [
				'name' => 'photofile.gif',
				'type' => "image/gif",
				'tmp_name' => TMP . '/test.gif',
				'error' => 0,
				'size' => 442850,
			],
		];

		$newData = $this->TestModel->create($data);
		try {
			$this->TestModel->save($newData);
			$this->fail('例外が発生してない');
		} catch (InternalErrorException $e) {
			$this->assertContains('UploadFile::save() Failed. fieldName=photo', $e->getMessage());
		}
	}
}