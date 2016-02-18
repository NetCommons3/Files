<?php
/**
 * FileUploadComponent::upload()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * FileUploadComponent::upload()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Files\Test\Case\Controller\Component\FileUploadComponent
 */
class FileUploadComponentUploadTest extends NetCommonsControllerTestCase {

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
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		//ログアウト
		TestAuthGeneral::logout($this);

		parent::tearDown();
	}

/**
 * upload()のテスト
 *
 * @return void
 */
	public function testUpload() {
		//テストコントローラ生成
		$this->generateNc('TestFiles.TestFileUploadComponent');

		//ログイン
		TestAuthGeneral::login($this);

		//テスト実行
		$this->_testNcAction('/test_files/test_file_upload_component/index', array(
			'method' => 'get'
		));

		//チェック
		$pattern = '/' . preg_quote('Controller/Component/FileUploadComponent', '/') . '/';
		$this->assertRegExp($pattern, $this->view);

		//TODO:必要に応じてassert追加する
		debug($this->view);

	}

}
