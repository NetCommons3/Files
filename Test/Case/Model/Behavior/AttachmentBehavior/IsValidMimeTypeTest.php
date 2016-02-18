<?php
/**
 * AttachmentBehavior::isValidMimeType()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');

/**
 * AttachmentBehavior::isValidMimeType()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Files\Test\Case\Model\Behavior\AttachmentBehavior
 */
class AttachmentBehaviorIsValidMimeTypeTest extends NetCommonsModelTestCase {

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
 * isValidMimeType()テストのDataProvider
 *
 * ### 戻り値
 *  - check Value to check
 *  - mimetypes file mimetypes to allow
 *  - requireUpload Whether or not to require a file upload
 *
 * @return array データ
 */
	public function dataProvider() {
		//TODO:テストパタンを書く
		$result[0] = array();
		$result[0]['check'] = null;
		$result[0]['mimetypes'] = array();
		$result[0]['requireUpload'] = true;

		return $result;
	}

/**
 * isValidMimeType()のテスト
 *
 * @param mixed $check Value to check
 * @param array $mimetypes file mimetypes to allow
 * @param bool $requireUpload Whether or not to require a file upload
 * @dataProvider dataProvider
 * @return void
 */
	public function testIsValidMimeType($check, $mimetypes, $requireUpload) {
		//テスト実施
		$result = $this->TestModel->isValidMimeType($check, $mimetypes, $requireUpload);

		//チェック
		//TODO:Assertを書く
		debug($result);
	}

}
