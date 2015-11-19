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

	public function testCreate() {
		$tempFolder = new TemporaryFolder();
		debug($tempFolder->path);
		$this->assertTrue(file_exists($tempFolder->path));
		$path = $tempFolder->path;
		$this->assertEquals(TMP, substr($path, 0, strlen(TMP)));
	}

	public function testDelete() {
		$tempFolder = new TemporaryFolder();
		$path = $tempFolder->path;
		$tempFolder->delete();
		$this->assertFalse(file_exists($path));

		$tempFolder2 = new TemporaryFolder();
		$path2 = $tempFolder2->path;
		unset($tempFolder2);
		$this->assertFalse(file_exists($path2));
	}
}