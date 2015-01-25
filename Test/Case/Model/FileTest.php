<?php
/**
 * File Test Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('File', 'Files.Model');

/**
 * File Test Case
 */
class FileTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->File = ClassRegistry::init('Files.File');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->File);
		parent::tearDown();
	}

}
