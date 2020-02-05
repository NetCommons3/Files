<?php
/**
 * AttachmentBehavior::uploadSettings()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');

/**
 * AttachmentBehavior::removeUploadSettings()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Files\Test\Case\Model\Behavior\AttachmentBehavior
 */
class AttachmentBehaviorRemoveUploadSettingsTest extends NetCommonsModelTestCase {

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
	}

/**
 * removeUploadSettings()のテスト
 *
 * @return void
 */
	public function testRemoveUploadSettings() {
		$field = 'photo';
		$options = [ // 連想配列オプション
			'foo' => 'bar'
		];
		$this->TestModel->uploadSettings($field, $options);

		$attachment = ClassRegistry::getObject('AttachmentBehavior');
		//チェック
		$settings = new ReflectionProperty($attachment, '_settings');
		$settings->setAccessible(true);
		$uploadSettings = [
			'TestAttachmentBehaviorModel' => [
				'fileFields' => [
					'photo' => $options,
				]
			],
			'OtherModel' => [
				'fileFields' => [
					'pdf' => [],
					'photo' => [],
				]
			]
		];
		$settings->setValue($attachment, $uploadSettings);

		$this->TestModel->removeUploadSettings($field);

		$expected = [
			'TestAttachmentBehaviorModel' => [
				'fileFields' => [
					// photoの設定削除
				]
			],
			'OtherModel' => [
				'fileFields' => [
					'pdf' => [],
					// 別のモデルには影響なし
					'photo' => [],
				]
			]
		];

		$this->assertSame($expected, $settings->getValue($attachment));
	}

}
