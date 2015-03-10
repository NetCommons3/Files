<?php
/**
 * FileUpload Component Test Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('Controller', 'Controller');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('ComponentCollection', 'Controller');
App::uses('Block', 'Blocks.Model');
App::uses('FileUploadComponent', 'Files.Controller/Component');

/**
 * Controller for FileUpload component test
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Files\Test\Case\Controller
 */
class FileUploadComponentController extends Controller {

}

/**
 * FileUpload Component Test case
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Files\Test\Case\Controller
 */
class FileUploadComponentTest extends CakeTestCase {

/**
 * FileUpload component
 *
 * @var Component FileUpload component
 */
	public $FileUpload = null;

/**
 * Controller for FileUploadComponent test
 *
 * @var Controller Controller for FileUploadComponent test
 */
	public $TestController = null;

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
		$this->TestController = new FileUploadComponentController($CakeRequest, $CakeResponse);
		//コンポーネント読み込み
		$Collection = new ComponentCollection();
		$this->FileUpload = new FileUploadComponent($Collection);
		$this->FileUpload->viewSetting = false;
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();

		unset($this->FileUpload);
		unset($this->TestController);

		Configure::write('Config.language', null);
	}

/**
 * Expect FileUpload->upload()
 *
 * @return void
 */
	public function testUpload() {
		$this->TestController->data = array(
			'Test' => array(
				'field' => array(
					'name' => 'logo.doc',
					'type' => 'application/msword',
					'size' => 5873,
				)
			),
			'field' => array(
				'File' => array(
					'role_type' => 'room_file_role',
					'path' => '{ROOT}test{DS}1{DS}',
				),
			)
		);

		$result = $this->FileUpload->upload($this->TestController, 'Test', 'field');

		$this->assertArrayHasKey('name', $result);
		$this->assertTextEquals($result['name'], $this->TestController->data['Test']['field']['name']);

		$this->assertArrayHasKey('mimetype', $result);
		$this->assertTextEquals($result['mimetype'], $this->TestController->data['Test']['field']['type']);

		$this->assertArrayHasKey('extension', $result);
		$this->assertTextEquals($result['extension'], 'doc');

		$this->assertArrayHasKey('slug', $result);
		$this->assertArrayHasKey('original_name', $result);
	}

/**
 * Expect FileUpload->upload()
 *
 * @return void
 */
	public function testUploadEmpty() {
		$this->TestController->data = array(
			'Test' => array(),
		);

		$result = $this->FileUpload->upload($this->TestController, 'Test', 'field');

		$this->assertCount(0, $result);
	}

/**
 * Expect FileUpload->upload()
 *
 * @return void
 */
	public function testUploadImage() {
		$this->TestController->data = array(
			'Test' => array(
				'field' => array(
					'name' => 'logo.gif',
					'type' => 'image/gif',
					'size' => 5873,
				)
			),
			'field' => array(
				'File' => array(
					'role_type' => 'room_file_role',
					'path' => '{ROOT}test{DS}1{DS}',
				),
			)
		);

		$result = $this->FileUpload->upload($this->TestController, 'Test', 'field');

		$this->assertArrayHasKey('name', $result);
		$this->assertTextEquals($result['name'], $this->TestController->data['Test']['field']['name']);

		$this->assertArrayHasKey('mimetype', $result);
		$this->assertTextEquals($result['mimetype'], $this->TestController->data['Test']['field']['type']);

		$this->assertArrayHasKey('extension', $result);
		$this->assertTextEquals($result['extension'], 'gif');

		$this->assertArrayHasKey('slug', $result);
		$this->assertArrayHasKey('original_name', $result);
	}

/**
 * Expect FileUpload->upload()
 *
 * @return void
 */
	public function testUploadVideo() {
		$this->TestController->data = array(
			'Test' => array(
				'field' => array(
					'name' => 'logo.avi',
					'type' => 'video/avi',
					'size' => 5873,
				)
			),
			'field' => array(
				'File' => array(
					'role_type' => 'room_file_role',
					'path' => '{ROOT}test{DS}1{DS}',
				),
			)
		);

		$result = $this->FileUpload->upload($this->TestController, 'Test', 'field');

		$this->assertArrayHasKey('name', $result);
		$this->assertTextEquals($result['name'], $this->TestController->data['Test']['field']['name']);

		$this->assertArrayHasKey('mimetype', $result);
		$this->assertTextEquals($result['mimetype'], $this->TestController->data['Test']['field']['type']);

		$this->assertArrayHasKey('extension', $result);
		$this->assertTextEquals($result['extension'], 'avi');

		$this->assertArrayHasKey('slug', $result);
		$this->assertArrayHasKey('original_name', $result);
	}

}
