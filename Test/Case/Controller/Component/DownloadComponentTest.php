<?php
/**
 * Created by PhpStorm.
 * User: ryuji
 * Date: 2015/11/19
 * Time: 14:37
 */

App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');
App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');
App::uses('TestHelperController', 'NetCommons.Test/test_app/Controller');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('ComponentCollection', 'Controller');
App::uses('DownloadComponent', 'Files.Controller/Component');

/**
 * TemporaryUploadFileTest
 */
class DownloadComponentTest extends NetCommonsCakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = [];

/**
 * @var DownloadComponent ダウンロードコンポーネント
 */
	public $Download = null;

/**
 * setUp
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		Configure::write('Config.language', 'ja');

		//テストコントローラ読み込み
		$CakeRequest = new CakeRequest();
		$CakeResponse = new CakeResponse();
		$this->TestController = new TestHelperController($CakeRequest, $CakeResponse);
		//コンポーネント読み込み
		$Collection = new ComponentCollection();
		$this->Download = new DownloadComponent($Collection);
		$this->Download->initialize($this->TestController);
		$this->Download->viewSetting = false;
	}

/**
 * tearDown
 *
 * @return void
 */
	public function tearDown() {
		Configure::write('Config.language', null);
		Current::$current = null;
	}

/**
 * test doDownload
 *
 * @return void
 */
	public function testDoDownload() {
		$pass = [
			null,
			null,
			'photo', //params['pass'][2]
		];

		Current::$current['Room']['id'] = 1;
		Current::$current['Language']['id'] = 2;
		$this->TestController->plugin = 'SiteManager';

		$contentId = 2;

		$this->TestController->params['pass'] = $pass;

		// responseをモックにして渡される値をテスト
		$path = WWW_ROOT . 'files/upload_file/real_file_name/1/1/foobarhash.jpg';

		$responseMock = $this->getMock('CakeResponse', ['file']);
		$responseMock->expects($this->once())
			->method('file')
			->with($this->equalTo($path));
		$this->TestController->response = $responseMock;

		// カウントアップが呼ばれるかテスト
		$UploadFileMock = $this->getMockForModel('Files.UploadFile', ['countUp']);
		$UploadFileMock->expects($this->once())
			->method('countUp')
			->will($this->returnValue(true));

		$this->Download->doDownload($contentId);
	}

}
