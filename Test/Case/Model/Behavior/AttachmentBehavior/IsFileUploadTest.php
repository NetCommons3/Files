<?php
/**
 * AttachmentBehavior::isFileUpload()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');

/**
 * AttachmentBehavior::isFileUpload()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Files\Test\Case\Model\Behavior\AttachmentBehavior
 */
class AttachmentBehaviorIsFileUploadTest extends NetCommonsModelTestCase {

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
 * isFileUpload()テストのDataProvider
 *
 * ### 戻り値
 *  - check Value to check
 *
 * @return array データ
 */
	public function dataProvider() {
		//TODO:テストパタンを書く
		$result[0] = array();
		$result[0]['check'] = null;

		return $result;
	}

/**
 * isFileUpload()のテスト
 *
 * @param mixed $check Value to check
 * @dataProvider dataProvider
 * @return void
 */
	public function testIsFileUpload($check) {
		//テスト実施
		$result = $this->TestModel->isFileUpload($check);

		//チェック
		//TODO:Assertを書く
		debug($result);
	}

}
