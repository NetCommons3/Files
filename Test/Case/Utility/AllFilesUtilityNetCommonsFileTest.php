<?php
/**
 * All NetCommonsFile Test suite
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsTestSuite', 'NetCommons.TestSuite');

/**
 * All NetCommonsFile Test suite
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Files\Test\Case\NetCommonsFile
 */
class AllFilesUtilityNetCommonsFileTest extends NetCommonsTestSuite {

/**
 * All NetCommonsFile Test suite
 *
 * @return NetCommonsTestSuite
 * @codeCoverageIgnore
 */
	public static function suite() {
		$name = preg_replace('/^All([\w]+)Test$/', '$1', __CLASS__);
		$suite = new NetCommonsTestSuite(sprintf('All %s tests', $name));
		$suite->addTestDirectoryRecursive(__DIR__ . DS . 'NetCommonsFile');
		return $suite;
	}

}
