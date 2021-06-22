<?php
/**
 * AttachmentBehavior::find()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');
App::uses('TestAttachmentBehaviorFindModelFixture', 'Files.Test/Fixture');

/**
 * AttachmentBehavior::find()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Files\Test\Case\Model\Behavior\AttachmentBehavior
 */
class AttachmentBehaviorFindTest extends NetCommonsModelTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.files.test_attachment_behavior_find_model',
	);

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'files';

/**
 * @var TestAttachmentBehaviorFindModel テスト用モデル
 */
	private $__testModel;

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		//テストプラグインのロード
		NetCommonsCakeTestCase::loadTestPlugin($this, 'Files', 'TestFiles');
		/** @var TestAttachmentBehaviorFindModel TestModel */
		$this->__testModel = ClassRegistry::init('TestFiles.TestAttachmentBehaviorFindModel');
	}

/**
 * find()のテスト
 *
 * @return void
 */
	public function testFind() {
		// afterFindで添付されてるファイル情報をくっつける
		$this->__testModel->recursive = 0;
		$content = $this->__testModel->findById(2);
		$this->assertEquals(4, $content['UploadFile']['photo']['id']);

		// recursive マイナスだと添付ファイル情報をつけない
		$this->__testModel->recursive = -1;
		$content = $this->__testModel->findById(2);
		$this->assertFalse(isset($content['UploadFile']));
	}

/**
 * test検索結果が複数
 *
 * @return void
 */
	public function testSomeResults() {
		$this->__testModel->recursive = 0;
		$contents = $this->__testModel->find('all', ['sort' => 'id ASC']);

		// id:1 は添付ファイル無し
		self::assertArrayNotHasKey('UploadFile', $contents[0]);
		// id:2 は添付ファイルid:4が添付されている
		self::assertSame('4', $contents[1]['UploadFile']['photo']['id']);
		// id:5 は添付ファイルid:5が添付されている
		self::assertSame('5', $contents[2]['UploadFile']['photo']['id']);
	}

/**
 * 同じIDが複数回ある検索結果
 *
 * @return void
 */
	public function testAfterFindWithDuplicateContentId() {
		$this->__testModel->recursive = 0;
		$results = [
			[
				'TestAttachmentBehaviorFindModel' => [
					'id' => '2',
				]
			],
			[
				'TestAttachmentBehaviorFindModel' => [
					'id' => '5'
				]
			],
			// LEFT JOINなどで同じIDが2回取得されることを想定したデータ
			[
				'TestAttachmentBehaviorFindModel' => [
					'id' => '2'
				]
			]
		];
		/** @var AttachmentBehavior $attachmentBehavior */
		$attachmentBehavior = ClassRegistry::getObject('AttachmentBehavior');
		$contents = $attachmentBehavior->afterFind($this->__testModel, $results);

		// id:2 は添付ファイルid:4が添付されている
		self::assertSame('4', $contents[0]['UploadFile']['photo']['id']);
		// id:5 は添付ファイルid:5が添付されている
		self::assertSame('5', $contents[1]['UploadFile']['photo']['id']);
		// id:2 は添付ファイルid:4が添付されている
		self::assertSame('4', $contents[2]['UploadFile']['photo']['id']);
	}

}
