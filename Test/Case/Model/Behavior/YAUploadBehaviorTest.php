<?php
/**
 * TrackableBehavior test case
 *
 * @copyright Copyright 2014, NetCommons Project
 * @author Kohei Teraguchi <kteraguchi@commonsnet.org>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

App::uses('YAUploadBehaviorModel', 'Files.Test/Case/Model/Behavior');
App::uses('UploadBehavior', 'Upload.Model/Behavior');

/**
 * YAUploadBehaviorTest
 */
class YAUploadBehaviorTest extends UploadBehavior {

/**
 * testIndex method
 *
 * @return void
 */
	public function testIndex() {
		$this->assertTrue(true);
	}

}
