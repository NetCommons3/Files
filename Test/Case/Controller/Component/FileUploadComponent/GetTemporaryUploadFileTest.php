<?php
/**
 * FileUploadComponent::getTemporaryUploadFile()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');
App::uses('TemporaryUploadFileTesting', 'Files.Test/Testing');

/**
 * FileUploadComponent::getTemporaryUploadFile()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Files\Test\Case\Controller\Component\FileUploadComponent
 */
class FileUploadComponentGetTemporaryUploadFileTest extends NetCommonsControllerTestCase {

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
 * getTemporaryUploadFile()のテスト
 *
 * @return void
 */
	public function testGetTemporaryUploadFile() {
		//$Collection = new ComponentCollection();
		//$component = new FileUploadComponent($Collection);
		//$component->getTemporaryUploadFile();
		//

		//テストコントローラ生成
		$this->generateNc('TestFiles.TestFileUploadComponent');
		//ログイン
		TestAuthGeneral::login($this);

		$fileData = array(
			'name' => 'logo.doc',
			'type' => 'application/msword',
			'size' => 5873,
			'tmp_name' => 'foo_bar'
		);
		$this->controller->request->data = [
				'field' => $fileData
		];
		$Collection = new ComponentCollection();

		$mock = $this->getMock('FileUploadComponent', ['_getTemporaryUploadFile'], array($Collection));
		$mock->expects($this->once()) //1回だけ呼ばれる
			->method('_getTemporaryUploadFile')
			->with($this->equalTo($fileData));
		$mock->initialize($this->controller);
		$mock->getTemporaryUploadFile('field');
	}
}
