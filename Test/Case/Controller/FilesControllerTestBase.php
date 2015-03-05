<?php
/**
 * FilesController Test Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsFrameComponent', 'NetCommons.Controller/Component');
App::uses('NetCommonsBlockComponent', 'NetCommons.Controller/Component');
App::uses('NetCommonsRoomRoleComponent', 'NetCommons.Controller/Component');
App::uses('YAControllerTestCase', 'NetCommons.TestSuite');

/**
 * FilesController Test Case
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Files\Test\Case\Controller
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class FilesControllerTestBase extends YAControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'site_setting',
		'plugin.files.file',
		'plugin.files.files_plugin',
		'plugin.files.files_room',
		'plugin.files.files_user',
		'plugin.files.plugin',
		'plugin.files.user',
		'plugin.files.user_attributes_user',
		'plugin.m17n.language',
		'plugin.m17n.languages_page',
		'plugin.roles.default_role_permission',
		'plugin.rooms.roles_rooms_user',
		'plugin.rooms.roles_room',
		'plugin.rooms.room',
		'plugin.rooms.room_role_permission',
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		Configure::write('Config.language', 'ja');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		Configure::write('Config.language', null);
		CakeSession::write('Auth.User', null);
		parent::tearDown();
	}

/**
 * _generateController method
 *
 * @param string $controllerName controller name
 * @param array $addMocks generate options
 * @return void
 */
	protected function _generateController($controllerName, $addMocks = array()) {
		$mocks = array(
			'components' => array(
				'Auth' => array('user'),
				'Session',
				'Security',
			)
		);
		$params = array_merge_recursive($mocks, $addMocks);

		$this->generate($controllerName, $params);
	}

/**
 * _logout method
 *
 * @return void
 */
	protected function _logout() {
		//ログアウト処理
		$this->testAction('/auth/logout', array(
			'data' => array(
			),
		));
		$this->assertNull(CakeSession::read('Auth.User'), '_logout()');
	}

/**
 * _setComponentError method
 *
 * @param string $componentName component name
 * @param string $methodName method name
 * @return void
 */
	protected function _setComponentError($componentName, $methodName) {
		$this->controller->$componentName
			->staticExpects($this->any())
			->method($methodName)
			->will($this->returnValue(false));

		$this->assertTrue(
				method_exists($this->controller->$componentName, $methodName),
				get_class($this->controller->$componentName) . '::' . $methodName
			);
	}

/**
 * _setModelError method
 *
 * @param string $modelName model name
 * @param string $methodName method name
 * @return void
 */
	protected function _setModelError($modelName, $methodName) {
		$this->controller->$modelName = $this->getMockForModel($modelName, array($methodName));
		$this->controller->$modelName->expects($this->any())
			->method($methodName)
			->will($this->returnValue(false));

		$this->assertTrue(
				method_exists($this->controller->$modelName, $methodName),
				get_class($this->controller->$modelName) . '::' . $methodName
			);
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
