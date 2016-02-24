<?php
/**
 * FileUploadComponent::getTemporaryUploadFile()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');
App::uses('TemporaryUploadFileTesting', 'Files.Test/Testing');

/**
 * FileUploadComponent::getTemporaryUploadFile()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
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
		//テスト実行
		//$this->_testNcAction('/test_files/test_file_upload_component/index', array(
		//	'method' => 'post'
		//));

		//$file = $this->controller->FileUpload->getTemporaryUploadFile('field', 'TemporaryUploadFileTesting');

		////
		////$method = new ReflectionMethod($this->controller->FileUpload, '_getTemporaryUploadFile');
		////$method->setAccessible(true);
		////
		////$result = $method->invoke($this->controller->FileUpload, $data);
		////
		////$this->assertEqual($result, 'StringAbar');
		////
		////$method = new ReflectionMethod()
		$Collection = new ComponentCollection();

		$mock = $this->getMock('FileUploadComponent', ['_getTemporaryUploadFile'], array($Collection));
		$mock->expects($this->once()) //1回だけ呼ばれる
			->method('_getTemporaryUploadFile')
			->with($this->equalTo($fileData));
		$mock->initialize($this->controller);
	$mock->getTemporaryUploadFile('field');

			//
		////テスト実行
		//$this->_testNcAction('/test_files/test_file_upload_component/index', array(
		//	'method' => 'post'
		//));
		//
		//$file = $this->controller->FileUpload->getTemporaryUploadFile('field');
		//
		////$this->assertInstanceOf('TemporaryUploadFile', $file);


	}

}
