<?php
/**
 * AttachmentBehavior::isAboveMinHeight()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');

/**
 * AttachmentBehavior::isAboveMinHeight()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Files\Test\Case\Model\Behavior\AttachmentBehavior
 */
class AttachmentBehaviorIsAboveMinHeightTest extends NetCommonsModelTestCase {

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
 * isAboveMinHeight()テストのDataProvider
 *
 * ### 戻り値
 *  - check Value to check
 *  - height Height of Image
 *  - requireUpload Whether or not to require a file upload
 *
 * @return array データ
 */
	public function dataProvider() {
		//TODO:テストパタンを書く
		$result[0] = array();
		$result[0]['check'] = null;
		$result[0]['height'] = null;
		$result[0]['requireUpload'] = true;

		return $result;
	}

/**
 * isAboveMinHeight()のテスト
 *
 * @param mixed $check Value to check
 * @param int $height Height of Image
 * @param bool $requireUpload Whether or not to require a file upload
 * @dataProvider dataProvider
 * @return void
 */
	public function testIsAboveMinHeight($check, $height, $requireUpload) {
		//テスト実施
		$result = $this->TestModel->isAboveMinHeight($check, $height, $requireUpload);

		//チェック
		//TODO:Assertを書く
		debug($result);
	}

}
