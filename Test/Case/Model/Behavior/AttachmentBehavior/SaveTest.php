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
	}

/**
 * save()のテスト
 *
 * @return void
 */
	public function testSave() {
		//テストデータ
		$data = array(
			'TestAttachmentBehaviorSaveModel' => (new TestAttachmentBehaviorSaveModelFixture())->records[0],
		);

		//テスト実施
		$result = $this->TestModel->save($data);

		//チェック
		//TODO:Assertを書く
		debug($result);
	}

}
