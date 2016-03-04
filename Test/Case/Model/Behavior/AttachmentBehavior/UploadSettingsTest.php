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
 * AttachmentBehavior::uploadSettings()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Files\Test\Case\Model\Behavior\AttachmentBehavior
 */
class AttachmentBehaviorUploadSettingsTest extends NetCommonsModelTestCase {

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
 * uploadSettings()のテスト
 *
 * @return void
 */
	public function testUploadSettings() {
		$field = 'photo';
		$options = [ // 連想配列オプション
			'foo' => 'bar'
		];
		//テスト実施
		$this->TestModel->uploadSettings($field, $options);

		$attachment = ClassRegistry::getObject('AttachmentBehavior');
		//チェック
		$property = new ReflectionProperty($attachment, '_settings');
		$property->setAccessible(true);
		$value = $property->getValue($attachment);

		$expects = [
			'TestAttachmentBehaviorModel' => [
				'fileFields' => [
					'photo' => $options,
				]
			]
		];
		$this->assertEquals($expects, $value);

		// setupから呼ばれたときにフィールド名しか定義されてなくて、オプション未設定のとき
		// $fieldが配列添え字になり、$optionsにフィールド名文字列がくることになる
		$field = 0;
		$options = 'photo';
		//テスト実施
		$this->TestModel->uploadSettings($field, $options);

		$attachment = ClassRegistry::getObject('AttachmentBehavior');
		//チェック
		$property = new ReflectionProperty($attachment, '_settings');
		$property->setAccessible(true);
		$value = $property->getValue($attachment);

		$expects = [
			'TestAttachmentBehaviorModel' => [
				'fileFields' => [
					'photo' => array(),
				]
			]
		];
		$this->assertEquals($expects, $value);
	}

}
