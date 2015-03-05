<?php
/**
 * Controller for FileUpload component test
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
 * FileUpload Component test case
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\NetCommons\Test\Case\Controller
 */
class FileUploadComponentTestBase extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		//'plugin.frames.frame',
		//'plugin.frames.language',
		//'plugin.frames.plugin',
		//'plugin.boxes.box',
		//'plugin.blocks.block',
		//'plugin.users.user',
		//'plugin.rooms.room',
	);

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
 * testIndex method
 *
 * @return void
 */
	public function testIndex() {
		$this->assertTrue(true);
	}

}
