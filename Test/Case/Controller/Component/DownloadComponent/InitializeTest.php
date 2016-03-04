<?php
/**
 * DownloadComponent::initialize()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * DownloadComponent::initialize()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Files\Test\Case\Controller\Component\DownloadComponent
 */
class DownloadComponentInitializeTest extends NetCommonsControllerTestCase {

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
		$this->generateNc('TestFiles.TestDownloadComponent');

		//ログイン
		TestAuthGeneral::login($this);

		//テスト実行
		$this->_testNcAction('/test_files/test_download_component/index', array(
			'method' => 'get'
		));

		//チェック
		//$pattern = '/' . preg_quote('Controller/Component/DownloadComponent', '/') . '/';
		//$this->assertRegExp($pattern, $this->view);
		$property = new ReflectionProperty($this->controller->Download, '_controller');
		$property->setAccessible(true);
		$this->assertInstanceOf('AppController', $property->getValue($this->controller->Download));
	}

}
