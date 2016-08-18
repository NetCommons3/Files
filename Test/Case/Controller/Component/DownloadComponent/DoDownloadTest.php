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
App::uses('Role', 'Roles.Model');

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
 * test doDownload
 *
 * @return void
 */
	public function testDoDownload() {
		//テストコントローラ生成
		$this->generateNc('TestFiles.TestDownloadComponent');
		// $this->controllerにテスト用コントローラが配置される

		//ログイン
		TestAuthGeneral::login($this);

		// componentのInitializeをコールしたいのでアクションコール
		$this->_testNcAction('/test_files/test_download_component/index', array(
			'method' => 'get'
		));

		$pass = [
			//null,
			//null,
			'photo', //params['pass'][0]
		];

		Current::$current['Room']['id'] = 1;
		Current::$current['Language']['id'] = 2;
		$this->controller->plugin = 'SiteManager';

		$contentId = 2;

		$this->controller->params['pass'] = $pass;

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

		$this->controller->Download->doDownload($contentId);
	}

/**
 * サムネイルファイルのダウンロード
 *
 * @return void
 */
	public function testDoDownloadThumbnail() {
		//テストコントローラ生成
		$this->generateNc('TestFiles.TestDownloadComponent');
		// $this->controllerにテスト用コントローラが配置される

		//ログイン
		TestAuthGeneral::login($this);

		// componentのInitializeをコールしたいのでアクションコール
		$this->_testNcAction('/test_files/test_download_component/index', array(
			'method' => 'get'
		));

		$pass = [
			//null,
			//null,
			'photo', //params['pass'][0]
		];

		Current::$current['Room']['id'] = 1;
		Current::$current['Language']['id'] = 2;
		$this->controller->plugin = 'SiteManager';

		$contentId = 2;

		$this->controller->params['pass'] = $pass;

		// responseをモックにして渡される値をテスト
		$path = WWW_ROOT . 'files/upload_file/real_file_name/1/1/small_foobarhash.jpg';

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

		$this->controller->Download->doDownload($contentId, ['size' => 'small']);
	}

/**
 * 別ルームだったら例外発生
 *
 * @return void
 */
	public function testDifferentRoomId() {
		//テストコントローラ生成
		$this->generateNc('TestFiles.TestDownloadComponent');
		// $this->controllerにテスト用コントローラが配置される

		//ログイン
		TestAuthGeneral::login($this);

		// componentのInitializeをコールしたいのでアクションコール
		$this->_testNcAction('/test_files/test_download_component/index', array(
			'method' => 'get'
		));

		$pass = [
			//null,
			//null,
			'photo', //params['pass'][0]
		];

		Current::$current['Room']['id'] = 2; // 別ルーム
		Current::$current['Language']['id'] = 2;
		$this->controller->plugin = 'SiteManager';

		$contentId = 2;

		$this->controller->params['pass'] = $pass;

		$this->setExpectedException('ForbiddenException');
		$this->controller->Download->doDownload($contentId, ['size' => 'small']);
	}

/**
 * ブロック非表示で編集許可（block_editable）なしは例外発生
 *
 * @return void
 */
	public function testInvisibleBlock() {
		//テストコントローラ生成
		$this->generateNc('TestFiles.TestDownloadComponent');
		// $this->controllerにテスト用コントローラが配置される

		//ログイン
		TestAuthGeneral::login($this, Role::ROOM_ROLE_KEY_EDITOR);

		// componentのInitializeをコールしたいのでアクションコール
		$this->_testNcAction('/test_files/test_download_component/index', array(
			'method' => 'get'
		));

		$pass = [
			//null,
			//null,
			'photo', //params['pass'][0]
		];

		Current::$current['Room']['id'] = 1;
		Current::$current['Language']['id'] = 2;
		$this->controller->plugin = 'SiteManager';

		$contentId = 2;

		$this->controller->params['pass'] = $pass;

		// Block->isVisible == false
		$BlockMock = $this->getMockForModel('Block', ['isVisible']);
		$BlockMock->expects($this->once())
			->method('isVisible')
			->will($this->returnValue(false));

		$this->setExpectedException('ForbiddenException');
		$this->controller->Download->doDownload($contentId, ['size' => 'small']);
	}

/**
 * sizeに'..'が入ってたらディレクトリトラバーサルの可能性有りとしてBadRequest
 *
 * @return void
 */
	public function testBadRequestSize() {
		//テストコントローラ生成
		$this->generateNc('TestFiles.TestDownloadComponent');
		// $this->controllerにテスト用コントローラが配置される

		//ログイン
		TestAuthGeneral::login($this);

		// componentのInitializeをコールしたいのでアクションコール
		$this->_testNcAction('/test_files/test_download_component/index', array(
			'method' => 'get'
		));

		$pass = [
			//null,
			//null,
			'photo', //params['pass'][0]
		];

		Current::$current['Room']['id'] = 1;
		Current::$current['Language']['id'] = 2;
		$this->controller->plugin = 'SiteManager';

		$contentId = 2;

		$this->controller->params['pass'] = $pass;

		$this->setExpectedException('BadRequestException');

		$this->controller->Download->doDownload($contentId, ['size' => '../foo']);
	}

/**
 * test doDownload content_key, block_keyともにセットされてないuploadFileのダウンロード時はblock_keyでガードしない
 *
 * @return void
 */
	public function testDoDownloadNoContentKeyAndBlockKey() {
		//テストコントローラ生成
		$this->generateNc('TestFiles.TestDownloadComponent');
		// $this->controllerにテスト用コントローラが配置される

		//ログイン
		TestAuthGeneral::login($this);

		// componentのInitializeをコールしたいのでアクションコール
		$this->_testNcAction('/test_files/test_download_component/index', array(
			'method' => 'get'
		));

		$pass = [
			//null,
			//null,
			'photo', //params['pass'][0]
		];

		Current::$current['Room']['id'] = 1;
		Current::$current['Language']['id'] = 2;
		$this->controller->plugin = 'TestFiles';

		// content_keyもblock_keyもnullなデータ
		$contentId = 5;

		$this->controller->params['pass'] = $pass;

		// CakeResponseをモックにしとく
		$responseMock = $this->getMock('CakeResponse');
		$this->controller->response = $responseMock;

		// ブロックの表示状況チェックがされないこと
		$BlockMock = $this->getMockForModel('Block', ['isVisible']);
		$BlockMock->expects($this->never())
			->method('isVisible');

		$this->controller->Download->doDownload($contentId);
	}
}
