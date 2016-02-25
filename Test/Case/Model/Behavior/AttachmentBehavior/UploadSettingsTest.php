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
 * uploadSettings()テストのDataProvider
 *
 * ### 戻り値
 *  - filed フィールド名
 *  - options オプション
 *
 * @return array データ
 */
	public function dataProvider() {
		//TODO:テストパタンを書く
		$result[0] = array();
		$result[0]['filed'] = null;
		$result[0]['options'] = array();

		return $result;
	}

/**
 * uploadSettings()のテスト
 *
 * @param string $filed フィールド名
 * @param array $options オプション
 * @dataProvider dataProvider
 * @return void
 */
	public function testUploadSettings($filed, $options) {
		//テスト実施
		$result = $this->TestModel->uploadSettings($filed, $options);

		//チェック
		//TODO:Assertを書く
		debug($result);
	}

}
