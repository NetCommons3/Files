<?php
/**
 * AttachmentBehavior::setup()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');
App::uses('TestAttachmentBehaviorSaveModelFixture', 'Files.Test/Fixture');

/**
 * AttachmentBehavior::setup()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Files\Test\Case\Model\Behavior\AttachmentBehavior
 */
class AttachmentBehaviorSetupTest extends NetCommonsModelTestCase {

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
	}

/**
 * OriginalKeyビヘイビアがロードされてないと例外
 *
 * @return void
 */
	public function testSetupNotLoadOriginalKeyBehavior() {
		$this->setExpectedException('CakeException');
		// OriginalKeyBehaviorをロードしてないモデルだと例外発生
		$this->TestModel = ClassRegistry::init('TestFiles.TestAttachmentBehaviorSetupFailModel');
	}

/**
 * AttachmentBehavior::_settingsに設定値が入る
 *
 * @return void
 */
	public function testSetup() {
		$this->TestModel = ClassRegistry::init('TestFiles.TestAttachmentBehaviorSetupWithConfigModel');
		// _settingsにActAsで設定した設定値が入る
		$attachment = ClassRegistry::getObject('AttachmentBehavior');
		$property = new ReflectionProperty($attachment, '_settings');
		$property->setAccessible(true);
		$value = $property->getValue($attachment);

		$expects = [
			'TestAttachmentBehaviorSetupWithConfigModel' => [
				'fileFields' => [
					'photo' => [
						'thumbnailSizes' => [
							'small' => '200ml',
						]
					]
				]
			]
		];

		$this->assertEquals($expects, $value);
	}
}
