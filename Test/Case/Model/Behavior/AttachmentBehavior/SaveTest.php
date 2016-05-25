<?php
/**
 * AttachmentBehavior::save()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');
App::uses('TestAttachmentBehaviorSaveModelFixture', 'Files.Test/Fixture');

/**
 * AttachmentBehavior::save()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Files\Test\Case\Model\Behavior\AttachmentBehavior
 */
class AttachmentBehaviorSaveTest extends NetCommonsModelTestCase {

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

		$newData = $this->TestModel->create($data);
		$savedData = $this->TestModel->save($newData);
		//CakeLog::debug(var_export($savedData, true));

		// UploadFileと TestModelレコードの関連テーブルができてること。

		$UploadFilesContent = ClassRegistry::init('Files.UploadFilesContent');
		$link = $UploadFilesContent->findByContentId($savedData['TestAttachmentBehaviorSaveModel']['id']);
		//CakeLog::debug(var_export($link, true));
		$this->assertInternalType('array', $link);
		$this->assertNotEmpty($link);
	}

/**
 * test コンテンツ編集時にファイル添付しなかったケースのテスト
 *
 * @throws Exception
 * @return void
 */
	public function testEditContentWithNoFile() {
		// contentId = 2のコンテンツと fileId =4 のファイルがつながっている
		// contentId =2のコンテンツを更新する。NC3としては複製された新レコード contentId=3がセーブされる
		// その時ファイルが添付されてなければ、元々contentId=1についていたファイル（fileId=1）が引き続き添付される
		$baseContentId = 2;
		$fileId = 4;

		$UploadFilesContent = ClassRegistry::init('Files.UploadFilesContent');

		$link = $UploadFilesContent->findByPluginKeyAndContentIdAndUploadFileId('test_files', $baseContentId, $fileId);
		$this->assertNotEmpty($link);

		$data = $this->TestModel->findById(2);
		unset($data['TestAttachmentBehaviorSaveModel']['id']);
		$data['TestAttachmentBehaviorSaveModel']['photo'] = [
			'name' => '',
			'type' => '',
			'tmp_name' => '',
			'error' => UPLOAD_ERR_NO_FILE,
			'size' => '',
		];
		$data['TestAttachmentBehaviorSaveModel']['pdf'] = [
			'name' => '',
			'type' => '',
			'tmp_name' => '',
			'error' => UPLOAD_ERR_NO_FILE,
			'size' => '',
		];
		$data['UploadFile']['photo']['id'] = $fileId;

		$savedData = $this->TestModel->save($data); // 同じキーで新規レコード登録（NC3での編集時の保存処理）

		$link = $UploadFilesContent->findByPluginKeyAndContentIdAndUploadFileId('test_files', $savedData['TestAttachmentBehaviorSaveModel']['id'], $fileId);
		$this->assertNotEmpty($link);
	}

/**
 * 履歴のないモデルでコンテンツを編集したときは過去に関連づいていてたファイルを削除する(removeFile()をコールする）
 *
 * @return void
 */
	public function testNoIsLatestModel() {
		// モデルのスキーマからis_latestを削除する
		$property = new ReflectionProperty($this->TestModel, '_schema');
		$property->setAccessible(true);
		$val = $property->getValue($this->TestModel);
		unset($val['is_latest']);
		$property->setValue($this->TestModel, $val);
		//チェック
		// 新規アップロードされててis_latestがない（履歴なしのモデル）ときは、以前登録されてたファイルを削除(removeFile())する

		$fileId = 4;
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
		$data['TestAttachmentBehaviorSaveModel'] = $data;
		$data['UploadFile']['photo']['id'] = $fileId; // 添付されてたファイルのID
		$data['UploadFile']['photo']['field_name'] = 'photo'; // 添付ファイルの仮想フィールド名

		// removeFileが呼ばれるかテスト
		$uploadFileMock = $this->getMockForModel('Files.UploadFile', ['removeFile']);
		$uploadFileMock->expects($this->once())
			->method('removeFile');
		$newData = $this->TestModel->create($data);
		$this->TestModel->save($newData);
	}

/**
 * コンテンツ編集時にファイル削除チェックをいれたケースのテスト
 *
 * @return void
 */
	public function testEditContentAndRemoveFile() {
		// contentId = 2のコンテンツと fileId =4 のファイルがつながっている
		// contentId =2のコンテンツを更新する。NC3としては複製された新レコード contentId=3がセーブされる
		// その時ファイルが添付されてなければ、元々contentId=1についていたファイル（fileId=1）が引き続き添付される
		$baseContentId = 2;
		$fileId = 4;

		$UploadFilesContent = ClassRegistry::init('Files.UploadFilesContent');

		$link = $UploadFilesContent->findByPluginKeyAndContentIdAndUploadFileId('test_files', $baseContentId, $fileId);
		$this->assertNotEmpty($link);

		$data = $this->TestModel->findById(2);
		unset($data['TestAttachmentBehaviorSaveModel']['id']);
		$data['TestAttachmentBehaviorSaveModel']['photo'] = [
			'name' => '',
			'type' => '',
			'tmp_name' => '',
			'error' => UPLOAD_ERR_NO_FILE,
			'size' => '',
		];
		$data['TestAttachmentBehaviorSaveModel']['pdf'] = [
			'name' => '',
			'type' => '',
			'tmp_name' => '',
			'error' => UPLOAD_ERR_NO_FILE,
			'size' => '',
		];
		$data['UploadFile']['photo']['id'] = $fileId;
		// NetCommonsFormでremoveチェックいれたときのデータ
		$data['TestAttachmentBehaviorSaveModel']['photo']['remove'] = 1;
		$savedData = $this->TestModel->save($data); // 同じキーで新規レコード登録（NC3での編集時の保存処理）

		$link = $UploadFilesContent->findByPluginKeyAndContentIdAndUploadFileId('test_files', $savedData['TestAttachmentBehaviorSaveModel']['id'], $fileId);
		$this->assertEmpty($link); // リンクが存在しないこと
	}
}
