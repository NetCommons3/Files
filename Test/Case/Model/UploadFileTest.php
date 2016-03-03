<?php
/**
 * UploadFile Test Case
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('UploadFile', 'Files.Model');
App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');
App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * Summary for UploadFile Test Case
 *
 * @property UploadFile $UploadFile
 * @property UploadFilesContent $UploadFilesContent
 */
class UploadFileTest extends NetCommonsCakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = [

	];

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->UploadFile = ClassRegistry::init('Files.UploadFile');
		$this->UploadFilesContent = ClassRegistry::init('Files.UploadFilesContent');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->UploadFile);
		parent::tearDown();
	}
}
