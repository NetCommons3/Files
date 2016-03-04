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
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		//テストプラグインのロード
		NetCommonsCakeTestCase::loadTestPlugin($this, 'Files', 'TestFiles');
		$this->TestModel = ClassRegistry::init('TestFiles.TestAttachmentBehaviorFindModel');
	}

/**
 * find()のテスト
 *
 * @return void
 */
	public function testFind() {
		////テストデータ
		//$type = 'first';
		//$query = array();
		//
		////テスト実施
		//$result = $this->TestModel->find($type, $query);

		// afterFindで添付されてるファイル情報をくっつける
		$this->TestModel->recursive = 0;
		$content = $this->TestModel->findById(2);
		$this->assertEquals(4, $content['UploadFile']['photo']['id']);

		// recursive マイナスだと添付ファイル情報をつけない
		$this->TestModel->recursive = -1;
		$content = $this->TestModel->findById(2);
		$this->assertFalse(isset($content['UploadFile']));
	}

}
