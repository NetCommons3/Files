<?php
/**
 * DownloadComponent::doDownloadByUploadFileId()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * DownloadComponent::doDownloadByUploadFileId()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Files\Test\Case\Controller\Component\DownloadComponent
 */
class DownloadComponentDoDownloadByUploadFileIdTest extends NetCommonsControllerTestCase {

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
 * test doDownloadByUploadFileId
 *
 * @return void
 */
	public function testDoDownloadByUploadFileId() {
		//テストコントローラ生成
		$this->generateNc('TestFiles.TestDownloadComponent');
		// $this->controllerにテスト用コントローラが配置される

		//ログイン
		TestAuthGeneral::login($this);

		// componentのInitializeをコールしたいのでアクションコール
		$this->_testNcAction(
			'/test_files/test_download_component/index',
			array(
				'method' => 'get'
			)
		);

		$pass = [
			null,
			null,
			'photo', //params['pass'][2]
		];

		Current::$current['Room']['id'] = 1;
		Current::$current['Language']['id'] = 2;
		$this->controller->plugin = 'SiteManager';

		$this->controller->params['pass'] = $pass;

		$fileId = 1;
		// responseをモックにして渡される値をテスト
		$path = WWW_ROOT . 'files/upload_file/real_file_name/1/1/foobarhash.jpg';

		$responseMock = $this->getMock('CakeResponse', ['file']);
		$responseMock->expects($this->once())
			->method('file')
			->with($this->equalTo($path));
		$this->controller->response = $responseMock;

		// カウントアップが呼ばれるかテスト
		$UploadFileMock = $this->getMockForModel('Files.UploadFile', ['countUp']);
		$UploadFileMock->expects($this->once())
			->method('countUp')
			->will($this->returnValue(true));

		$this->controller->Download->doDownloadByUploadFileId($fileId);
	}
}
