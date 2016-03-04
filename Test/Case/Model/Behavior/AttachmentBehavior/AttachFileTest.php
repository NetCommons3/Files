<?php
/**
 * AttachmentBehavior::attachFile()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');

/**
 * AttachmentBehavior::attachFile()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Files\Test\Case\Model\Behavior\AttachmentBehavior
 */
class AttachmentBehaviorAttachFileTest extends NetCommonsModelTestCase {

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
		copy(APP . 'Plugin/Files/Test/Fixture/logo.gif', TMP . '/test.gif');
	}

/**
 * attachFile()のテスト
 *
 * @return void
 */
	public function testAttachFile() {
		// UploadFile::attach モック
		$data = [
			'TestAttachmentBehaviorModel' => [
				'id' => 10,
				'key' => 'content_key'

			]
		];
		$fieldName = 'photo';
		$file = new File(TMP . 'test.gif');
		$keyFieldName = 'key';

		//ClassRegistry::removeObject('UploadFile');
		$uploadFileMock = $this->getMockForModel('Files.UploadFile', ['attach']);
		// 一回呼ばれることを確認
		// UploadFile::attach($pluginKey, $contentKey, $contentId, $fieldName, $file);
		$uploadFileMock->expects($this->once())
			->method('attach')
			->with(
				$this->equalTo('test_files'),
				$this->equalTo('content_key'),
				$this->equalTo(10),
				$this->equalTo($fieldName),
				$this->equalTo($file)
			);
		ClassRegistry::removeObject('UploadFile');
		ClassRegistry::addObject('UploadFile', $uploadFileMock);

		// AttachmentBehaviorにくっついてるUploadFileモデルをモックに差し替え
		$attachmentBehavior = ClassRegistry::getObject('AttachmentBehavior');
		$attachmentBehavior->UploadFile = $uploadFileMock;

		//テスト実施
		$this->TestModel->attachFile($data, $fieldName, $file, $keyFieldName);
	}

/**
 * attachFile() path渡し
 *
 * @return void
 */
	public function testAttachFileWithFilePath() {
		// UploadFile::attach モック
		$data = [
			'TestAttachmentBehaviorModel' => [
				'id' => 10,
				'key' => 'content_key'

			]
		];
		$fieldName = 'photo';
		$filePath = TMP . 'test.gif';
		$keyFieldName = 'key';

		//ClassRegistry::removeObject('UploadFile');
		$uploadFileMock = $this->getMockForModel('Files.UploadFile', ['attach']);
		// 一回呼ばれることを確認
		// UploadFile::attach($pluginKey, $contentKey, $contentId, $fieldName, $file);
		$uploadFileMock->expects($this->once())
			->method('attach')
			->with(
				$this->equalTo('test_files'),
				$this->equalTo('content_key'),
				$this->equalTo(10),
				$this->equalTo($fieldName),
				$this->isInstanceOf('File')
			);
		ClassRegistry::removeObject('UploadFile');
		ClassRegistry::addObject('UploadFile', $uploadFileMock);

		// AttachmentBehaviorにくっついてるUploadFileモデルをモックに差し替え
		$attachmentBehavior = ClassRegistry::getObject('AttachmentBehavior');
		$attachmentBehavior->UploadFile = $uploadFileMock;

		//テスト実施
		$this->TestModel->attachFile($data, $fieldName, $filePath, $keyFieldName);
	}
}
