<?php
/**
 * FileUploadComponent::initialize()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * FileUploadComponent::initialize()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Files\Test\Case\Controller\Component\FileUploadComponent
 */
class FileUploadComponentInitializeTest extends NetCommonsControllerTestCase {

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
 * initialize()のテスト
 *
 * @return void
 */
	public function testInitialize() {
		//テストコントローラ生成
		$this->generateNc('TestFiles.TestFileUploadComponent');

		//ログイン
		TestAuthGeneral::login($this);

		//テスト実行
		$this->_testNcAction('/test_files/test_file_upload_component/index', array(
			'method' => 'get'
		));

		// initializeでコントローラがわたってるはず
		$this->assertEquals($this->controller, $this->controller->FileUpload->controller);
	}
}
