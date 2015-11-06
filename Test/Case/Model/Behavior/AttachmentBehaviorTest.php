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

	public $fixtures = [
		//'plugin.files.file',
		'plugin.net_commons.site_setting',
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
		$this->SiteSetting = ClassRegistry::init('NetCommons.SiteSetting');
		//$this->TestCreateProfile->Behaviors->load('NetCommons.OriginalKey', ['photo']);
		$this->SiteSetting->Behaviors->load('Files.Attachment', ['photo']);

		copy(APP . 'Plugin/Files/Test/Fixture/logo.gif', TMP . '/test.gif');

		$this->_setupUploadBehaviorMock();
	}

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

	public function testUpload() {
		$data = [
				'key' => 1,
				'photo' => [
						'name' => 'photofile.gif',
						'type' => "image/gif",
						'tmp_name' => TMP . '/test.gif',
						'error' => 0,
						'size' => 442850,
				]
		];
		// TODO Uploadプラグインをアンロードしてテスト でないと本番アップ先にファイルいれちゃうので

		$newData = $this->SiteSetting->create($data);
		$savedData = $this->SiteSetting->save($newData);
		CakeLog::debug(var_export($savedData, true));

		// UploadFileと SiteSettingレコードの関連テーブルができてること。

		$UploadFilesContent = ClassRegistry::init('Files.UploadFilesContent');
		$link = $UploadFilesContent->findByContentId($savedData['SiteSetting']['id']);
		CakeLog::debug(var_export($link, true));
		$this->assertInternalType('array', $link);
		$this->assertNotEmpty($link);
	}

	public function testEditContentWithNoFile() {
		// contentId = 2のコンテンツと fileId =1 のファイルがつながっている
		// contentId =2のコンテンツを更新する。NC3としては複製された新切れコード contentId=3がセーブされる
		// その時ファイルが添付されてなければ、元々contentId=1についていたファイル（fileId=1）が引き続き添付される
		$UploadFilesContent = ClassRegistry::init('Files.UploadFilesContent');

		$link = $UploadFilesContent->findByPluginKeyAndContentIdAndUploadFileId('net_commons', 2, 1);
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

		$this->SiteSetting->save($data); // 同じキーで新規レコード登録（NC3での編集時の保存処理）

		$link = $UploadFilesContent->findByPluginKeyAndContentIdAndUploadFileId('net_commons', 3, 1);
		$this->assertNotEmpty($link);


	}

	public function xtestEditContentRemoveFile() {
		// contentId = 2のコンテンツと fileId =1 のファイルがつながっている
		// contentId =2のコンテンツを更新する。NC3としては複製された新切れコード contentId=3がセーブされる
		// その時、ファイル削除のチェックが入っていたら、添付されていたファイルは無くなる（関連が切れる）
		// 関連が切れるだけで元ファイルは履歴のために引き続き残る。

	}
}
