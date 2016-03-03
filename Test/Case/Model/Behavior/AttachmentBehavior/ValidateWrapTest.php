<?php
/**
 * AttachmentBehavior::isAboveMinHeight()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');

/**
 * AttachmentBehavior::isAboveMinHeight()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Files\Test\Case\Model\Behavior\AttachmentBehavior
 */
class AttachmentBehaviorValidateWrapTest extends NetCommonsModelTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array();

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
		$this->TestModel = ClassRegistry::init('TestFiles.TestAttachmentBehaviorModel');

		//$this->SiteSetting = ClassRegistry::init('SiteManager.SiteSetting');
		//$this->SiteSetting->Behaviors->load('NetCommons.OriginalKey');
		//$this->SiteSetting->Behaviors->load('Files.Attachment', ['photo', 'pdf']);
		//
		//copy(APP . 'Plugin/Files/Test/Fixture/logo.gif', TMP . '/test.gif');


		$this->_setupUploadBehaviorMock();
	}

/**
 * Uploadビヘイビアをモックに差し替え
 *
 * @return void
 */
	protected function _setupUploadBehaviorMock() {
		$this->uploadBehaviorMock = $this->getMock('UploadBehavior');

		ClassRegistry::removeObject('UploadBehavior');
		ClassRegistry::addObject('UploadBehavior', $this->uploadBehaviorMock);

		$UploadFile = ClassRegistry::init('Files.UploadFile');
		$UploadFile->Behaviors->unload('Upload.Upload');
		$UploadFile->Behaviors->load('Upload.Upload', $UploadFile->actsAs['Upload.Upload']);
	}

/**
 * isAboveMinHeight()テストのDataProvider
 *
 * ### 戻り値
 *  - check Value to check
 *  - height Height of Image
 *  - requireUpload Whether or not to require a file upload
 *
 * @return array データ
 */
	public function dataProvider() {
		$methods = [
			['isUnderPhpSizeLimit', 1],
			['isUnderFormSizeLimit', 1],
			['isCompletedUpload', 1],
			['isFileUpload', 1],
			['isFileUploadOrHasExistingValue', 1],
			['tempDirExists', 2],
			['isSuccessfulWrite', 2],
			['noPhpExtensionErrors', 2],
			['isValidMimeType', 3],
			['isWritable', 2],
			['isValidDir', 1],
			['isBelowMaxSize', 3],
			['isAboveMinSize', 3],
			['isValidExtension', 2],
			['isAboveMinHeight', 3],
			['isBelowMaxHeight', 3],
			['isAboveMinWidth', 3],
			['isBelowMaxWidth', 3],
		];
		return $methods;
	}

/**
 * バリデートメソッドコールでUploadビヘイビアのバリデートメソッドがコールされるかのテスト
 *
 * @param string $method バリデートメソッド名
 * @param int $paramNum メソッドのパラメータ数
 * @dataProvider dataProvider
 * @return void
 */
	public function testWrapValidateMethod($method, $paramNum) {
		//テスト実施
		$params = array_fill(0, $paramNum, 'value');
		$this->uploadBehaviorMock->expects($this->once())
			->method($method);
		call_user_func_array(array($this->TestModel, $method), $params);
	}

/**
 * Uploadプラグインのvalidatorをラップできてるかのテスト
 *
 * @throws Exception
 * @return void
 */
	public function testWrapValidator() {
		$this->TestModel->validate['pdf'] = [
			'rule' => array('isValidExtension', array('pdf'), false),
			'message' => 'isValidExtension'
		];

		$data = [
			'key' => 1,
			'photo' => [
				'name' => 'photofile.gif',
				'type' => "image/gif",
				'tmp_name' => TMP . '/test.gif',
				'error' => 0,
				'size' => 442850,
			],
			'pdf' => [
				'name' => 'photofile.gif',
				'type' => "image/gif",
				'tmp_name' => TMP . '/test.gif',
				'error' => 0,
				'size' => 442850,
			],
		];

		//$this->TestModel->create();
		$this->TestModel->set($data);
		$resultFalse = $this->TestModel->validates();

		$this->assertFalse($resultFalse);
		$this->assertNotEmpty($this->TestModel->validationErrors);
		$this->assertEquals('isValidExtension', $this->TestModel->validationErrors['pdf'][0]);
	}

}
