<?php
/**
 * YAUploadBehavior test case
 *
 * @copyright Copyright 2014, NetCommons Project
 * @author Kohei Teraguchi <kteraguchi@commonsnet.org>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

App::uses('FileModel', 'Files.Model');

/**
 * YAUploadBehavior Test
 */
class YAUploadBehaviorModel extends FileModel {

/**
 * Table name
 * @var string
 */
	public $useTable = 'files';

/**
 * List of behaviors
 * @var array
 */
	//public $actsAs = array(
	//	'Files.YAUpload' => array(
	//		'userModel' => 'YAUploadBehaviorModel',
	//	),
	//);

/**
 * List of hasOne associations
 * @var array
 */
	public $hasOne = array();

/**
 * List of hasMany associations
 * @var array
 */
	public $hasMany = array();

/**
 * List of belongsTo associations
 * @var array
 */
	public $belongsTo = array();

/**
 * List of hasAndBelongsToMany associations
 * @var array
 */
	public $hasAndBelongsToMany = array();

/**
 * List of validation rules
 * @var array
 */
	public $validate = array();
}

/**
 * Base class of YAUploadBehavior test case
 */
class YAUploadBehaviorTestBase extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.files.file',
		'plugin.files.files_plugin',
		'plugin.files.files_room',
		'plugin.files.files_user',
		'plugin.files.plugin',
		'plugin.files.user',
		'plugin.files.user_attributes_user',
		'plugin.m17n.language',
		'plugin.rooms.room',
	);

/**
 * setUp
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		/* ClassRegistry::flush(); */
		$this->model = ClassRegistry::init('YAUploadBehaviorModel');
		/* $this->model = ClassRegistry::init('Users.User', true); */
		/* if ($this->model->useDbConfig !== 'test') { */
		/* 	$this->model->setDataSource('master'); */
		/* } */
		/* $this->loadModels(['model' => 'Users.User']); */
		/* $this->model->setDataSource('test'); */
		$this->model->Behaviors->load('Files.YAUpload');
	}

/**
 * tearDown
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();
		//Configure::delete('Trackable.Auth');
		//CakeSession::delete('Auth.User');
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