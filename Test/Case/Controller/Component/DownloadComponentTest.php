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
 * @var DownloadComponent
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
	}

	public function testDoDownload() {
		$pass = [
			null,
			null,
			'photo', //params['pass'][2]
		];

		Current::$current['Room']['id'] = 1;
		Current::$current['Language']['id'] = 2;
		$this->TestContrller->plugin = 'NetCommons';

		$contentId = 2;

		$this->TestController->params['pass'] = $pass;

		// TODO responseをモックにして渡される値をテスト
		// TODO downloadカウントがカウントアップされるかをテスト
		$response = $this->Download->doDownload($contentId);
	}

}
