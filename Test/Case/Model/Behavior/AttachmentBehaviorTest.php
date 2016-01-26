<?php
/**
 * AttachmentBehavior Test Case
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

App::uses('ModelBehavior', 'Model');
App::uses('AttachmentBehavior', 'Files.Model/Behavior');
App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');
App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * Summary for AttachmentBehavior Test Case
 *
 * @property TestCreateProfile $SiteSetting テスト用モデル
 */
class AttachmentBehaviorTest extends NetCommonsCakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = [
		//'plugin.files.file',
		'plugin.site_manager.site_setting',
		'plugin.files.upload_file',
		'plugin.files.upload_files_content',
	];

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Attachment = new AttachmentBehavior();
		//NetCommonsControllerTestCase::loadTestPlugin($this, 'NetCommons', 'TestNetCommons');

		// ε(　　　　 v ﾟωﾟ)　＜テストのためにとりあえずSiteSetting使ってる。ちゃんとダミーのモデルにしたい
		$this->SiteSetting = ClassRegistry::init('SiteManager.SiteSetting');
		$this->SiteSetting->Behaviors->load('NetCommons.OriginalKey');
		$this->SiteSetting->Behaviors->load('Files.Attachment', ['photo', 'pdf']);

		copy(APP . 'Plugin/Files/Test/Fixture/logo.gif', TMP . '/test.gif');

		$this->_setupUploadBehaviorMock();
	}

/**
 * Uploadビヘイビアをモックに差し替え
 *
 * @return void
 */
	protected function _setupUploadBehaviorMock() {
		$uploadBehaviorMock = $this->getMock('UploadBehavior', ['handleUploadedFile', '_createThumbnails']);

		$uploadBehaviorMock->expects($this->any())
				->method('handleUploadedFile')
				->will($this->returnValue(true));
		ClassRegistry::removeObject('UploadBehavior');
		ClassRegistry::addObject('UploadBehavior', $uploadBehaviorMock);

		$UploadFile = ClassRegistry::init('Files.UploadFile');
		$UploadFile->Behaviors->unload('Upload.Upload');
		$UploadFile->Behaviors->load('Upload.Upload', $UploadFile->actsAs['Upload.Upload']);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Attachment);
		ClassRegistry::removeObject('UploadBehavior');

		parent::tearDown();
	}

/**
 * UploadFileに添付ファイルのレコードできてるか
 *
 * @throws Exception
 * @return void
 */
	public function testUpload() {
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
						'name' => '',
						'type' => "",
						'tmp_name' => '',
						'error' => UPLOAD_ERR_NO_FILE,
						'size' => 0,
				]

		];

		$newData = $this->SiteSetting->create($data);
		$savedData = $this->SiteSetting->save($newData);
		//CakeLog::debug(var_export($savedData, true));

		// UploadFileと SiteSettingレコードの関連テーブルができてること。

		$UploadFilesContent = ClassRegistry::init('Files.UploadFilesContent');
		$link = $UploadFilesContent->findByContentId($savedData['SiteSetting']['id']);
		//CakeLog::debug(var_export($link, true));
		$this->assertInternalType('array', $link);
		$this->assertNotEmpty($link);
	}

/**
 * test afterFind
 *
 * @return void
 */
	public function testAfterFind() {
		// afterFindで添付されてるファイル情報をくっつける
		$content = $this->SiteSetting->findById(2);
		$this->assertEquals(1, $content['UploadFile']['photo']['id']);
	}

/**
 * test コンテンツ編集時にファイル添付しなかったケースのテスト
 *
 * @throws Exception
 * @return void
 */
	public function testEditContentWithNoFile() {
		// contentId = 2のコンテンツと fileId =1 のファイルがつながっている
		// contentId =2のコンテンツを更新する。NC3としては複製された新切れコード contentId=3がセーブされる
		// その時ファイルが添付されてなければ、元々contentId=1についていたファイル（fileId=1）が引き続き添付される
		$UploadFilesContent = ClassRegistry::init('Files.UploadFilesContent');

		$link = $UploadFilesContent->findByPluginKeyAndContentIdAndUploadFileId('site_manager', 2, 1);
		$this->assertNotEmpty($link);

		$data = $this->SiteSetting->findById(2);
		unset($data['SiteSetting']['id']);
		$data['SiteSetting']['photo'] = [
			'name' => '',
			'type' => '',
			'tmp_name' => '',
			'error' => UPLOAD_ERR_NO_FILE,
			'size' => '',
		];
		$data['SiteSetting']['pdf'] = [
				'name' => '',
				'type' => '',
				'tmp_name' => '',
				'error' => UPLOAD_ERR_NO_FILE,
				'size' => '',
		];
		$data['UploadFile']['photo']['id'] = 1;

		$savedData = $this->SiteSetting->save($data); // 同じキーで新規レコード登録（NC3での編集時の保存処理）

		$link = $UploadFilesContent->findByPluginKeyAndContentIdAndUploadFileId('site_manager', $savedData['SiteSetting']['id'], 1);
		$this->assertNotEmpty($link);
	}

/**
 * 実装実験テスト(^^;
 *
 * @return void
 */
	public function testHashGet() {
		$data = [
			'BlogEntry' => [
				'photo' => [
					'name' => 'file.jpg'
				]
			]
		];
		$fileName = Hash::get($data, 'BlogEntry.photo.name');
		$this->assertEquals('file.jpg', $fileName);

		$data2 = [];
		$emptyString = Hash::get($data2, 'BlogEntry.photo.name', '');
		$this->assertEquals('', $emptyString);
	}

/**
 * Uploadプラグインのvalidatorをラップできてるかのテスト
 *
 * @throws Exception
 * @return void
 */
	public function testWrapValidator() {
		$this->SiteSetting->validate['pdf'] = [
				'rule' => array('isValidExtension', array('pdf'), false),
				'message' => 'pdf only'
		];
		$data = $this->SiteSetting->findById(2);
		unset($data['SiteSetting']['id']);
		$result = $this->SiteSetting->save($data);
		$this->assertInternalType('array', $result);

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

		$newData = $this->SiteSetting->create($data);
		$resultFalse = $this->SiteSetting->save($newData);

		$this->assertFalse($resultFalse);
		$this->assertNotEmpty($this->SiteSetting->validationErrors);
		debug($this->SiteSetting->validationErrors);
	}
}