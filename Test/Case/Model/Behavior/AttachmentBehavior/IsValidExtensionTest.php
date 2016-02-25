<?php
/**
 * AttachmentBehavior::isValidExtension()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');

/**
 * AttachmentBehavior::isValidExtension()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Files\Test\Case\Model\Behavior\AttachmentBehavior
 */
class AttachmentBehaviorIsValidExtensionTest extends NetCommonsModelTestCase {

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
 * isValidExtension()テストのDataProvider
 *
 * ### 戻り値
 *  - check Value to check
 *  - extensions file extenstions to allow
 *  - requireUpload Whether or not to require a file upload
 *
 * @return array データ
 */
	public function dataProvider() {
		//TODO:テストパタンを書く
		$result[0] = array();
		$result[0]['check'] = null;
		$result[0]['extensions'] = array();
		$result[0]['requireUpload'] = true;

		return $result;
	}

/**
 * isValidExtension()のテスト
 *
 * @param mixed $check Value to check
 * @param array $extensions file extenstions to allow
 * @param bool $requireUpload Whether or not to require a file upload
 * @dataProvider dataProvider
 * @return void
 */
	public function testIsValidExtension($check, $extensions, $requireUpload) {
		//テスト実施
		$result = $this->TestModel->isValidExtension($check, $extensions, $requireUpload);

		//チェック
		//TODO:Assertを書く
		debug($result);
	}

}
