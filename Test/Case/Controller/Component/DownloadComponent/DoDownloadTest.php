<?php
/**
 * DownloadComponent::doDownload()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * DownloadComponent::doDownload()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Files\Test\Case\Controller\Component\DownloadComponent
 */
class DownloadComponentDoDownloadTest extends NetCommonsControllerTestCase {

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
 * doDownload()のテスト
 *
 * @return void
 */
	public function testDoDownload() {
		//テストコントローラ生成
		$this->generateNc('TestFiles.TestDownloadComponent');

		//ログイン
		TestAuthGeneral::login($this);

		//テスト実行
		$this->_testNcAction('/test_files/test_download_component/index', array(
			'method' => 'get'
		));

		//チェック
		$pattern = '/' . preg_quote('Controller/Component/DownloadComponent', '/') . '/';
		$this->assertRegExp($pattern, $this->view);

		//TODO:必要に応じてassert追加する
		debug($this->view);
	}

}
