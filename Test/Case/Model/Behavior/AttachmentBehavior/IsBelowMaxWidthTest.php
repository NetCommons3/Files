<?php
/**
 * AttachmentBehavior::isBelowMaxWidth()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');

/**
 * AttachmentBehavior::isBelowMaxWidth()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Files\Test\Case\Model\Behavior\AttachmentBehavior
 */
class AttachmentBehaviorIsBelowMaxWidthTest extends NetCommonsModelTestCase {

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
 * isBelowMaxWidth()テストのDataProvider
 *
 * ### 戻り値
 *  - check Value to check
 *  - width Width of Image
 *  - requireUpload Whether or not to require a file upload
 *
 * @return array データ
 */
	public function dataProvider() {
		//TODO:テストパタンを書く
		$result[0] = array();
		$result[0]['check'] = null;
		$result[0]['width'] = null;
		$result[0]['requireUpload'] = true;

		return $result;
	}

/**
 * isBelowMaxWidth()のテスト
 *
 * @param mixed $check Value to check
 * @param int $width Width of Image
 * @param bool $requireUpload Whether or not to require a file upload
 * @dataProvider dataProvider
 * @return void
 */
	public function testIsBelowMaxWidth($check, $width, $requireUpload) {
		//テスト実施
		$result = $this->TestModel->isBelowMaxWidth($check, $width, $requireUpload);

		//チェック
		//TODO:Assertを書く
		debug($result);
	}

}
