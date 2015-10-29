<?php
/**
 * AttachmentBehavior Test Case
 *
* @author Jun Nishikawa <topaz2@m0n0m0n0.com>
* @link http://www.netcommons.org NetCommons Project
* @license http://www.netcommons.org/license.txt NetCommons License
 */

App::uses('ModelBehavior', 'Model');
App::uses('AttachmentBehavior', 'Files.Model/Behavior');

/**
 * Summary for AttachmentBehavior Test Case
 */
class AttachmentBehaviorTest extends CakeTestCase {

	public $fixtures = [
		'plugin.files.file',
	];
/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Attachment = new AttachmentBehavior();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Attachment);

		parent::tearDown();
	}

	public function testGetFileFieldsByModel() {
		/**
		 * @var FileModel $File
		 */
		$File = ClassRegistry::init('Files.File');
		debug($File->getColumnTypes());
		debug($File->schema());


	}
}
