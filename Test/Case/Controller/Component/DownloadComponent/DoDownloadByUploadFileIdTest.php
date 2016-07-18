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
			//null,
			//null,
			'photo', //params['pass'][0]
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

/**
 * ダウンロードコンポーネントでblock_keyガード条件をblock_keyかコンテンツkeyのあるときとしたら、アバター表示時にコンテンツキー有り&block_key無しのため例外発生するようになった。
 * それを修正できたことを確かめるテスト
 *
 * @return void
 */
	public function testFixAvatarDownloadProblem() {
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
		$this->controller->params['pass'] = [
			//null,
			//null,
			'avatar', //params['pass'][0]
		];

		$fileId = 6; // avatarファイル

		$path = WWW_ROOT . 'files/upload_file/real_file_name//6/hash_name.jpg';

		$responseMock = $this->getMock('CakeResponse', ['file']);
		$responseMock->expects($this->once())
			->method('file')
			->with($this->equalTo($path));
		$this->controller->response = $responseMock;

		// コンテンツキーが入ってるとブロックガードで例外が発生してた。-> DownloadComponent修正で例外発生しなくなったのを確認
		// @codingStandardsIgnoreStart NOTICE無視して例外発生するのを確認したかったので@でエラー抑止してます。
		@$this->controller->Download->doDownloadByUploadFileId($fileId);
		// @codingStandardsIgnoreEnd
	}
}
