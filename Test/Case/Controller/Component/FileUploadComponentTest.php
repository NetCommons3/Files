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
 * FileUpload Component Test case
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Files\Test\Case\Controller
 */
class FileUploadComponentTest extends CakeTestSuite {

/**
 * All test suite
 *
 * @return CakeTestSuite
 */
	public static function suite() {
		$name = __CLASS__;
		$path = __DIR__ . DS . substr($name, 0, -4); // 末尾のTest"を除外
		$suite = new CakeTestSuite(sprintf('All %s tests', $name));
		$suite->addTestDirectoryRecursive($path
		);
		return $suite;
	}
}
