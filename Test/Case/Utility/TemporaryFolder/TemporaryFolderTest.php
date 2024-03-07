<?php
/**
 * Created by PhpStorm.
 * User: ryuji
 * Date: 2015/11/19
 * Time: 14:37
 */

App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');
App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');
App::uses('TemporaryFolder', 'Files.Utility');

/**
 * Summary for AttachmentBehavior Test Case
 *
 * @property TestCreateProfile $SiteSetting テスト用モデル
 */
class TemporaryFolderTest extends NetCommonsCakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = [];

/**
 * test create
 *
 * @return void
 */
	public function testCreate() {
		$tempFolder = new TemporaryFolder();
		$this->assertTrue(file_exists($tempFolder->path));
		$path = $tempFolder->path;
		$this->assertEquals(TMP, substr($path, 0, strlen(TMP)));
	}

/**
 * test delete
 *
 * @return void
 */
	public function testDelete() {
		$tempFolder = new TemporaryFolder();
		$path = $tempFolder->path;
		$tempFolder->delete();
		$this->assertFalse(file_exists($path));
	}

}
