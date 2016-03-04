<?php
/**
 * Created by PhpStorm.
 * User: ryuji
 * Date: 2015/11/19
 * Time: 14:37
 */

App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');
App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');
App::uses('TemporaryFile', 'Files.Utility');

/**
 * Summary for AttachmentBehavior Test Case
 *
 * @property TestCreateProfile $SiteSetting テスト用モデル
 */
class TemporaryFileTest extends NetCommonsCakeTestCase {

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
		$temporaryFile = new TemporaryFile();
		$this->assertTrue(file_exists($temporaryFile->path));
		$path = $temporaryFile->path;
		$this->assertEquals(TMP, substr($path, 0, strlen(TMP)));
	}

/**
 * test delete
 *
 * @return void
 */
	public function testDelete() {
		$temporaryFile = new TemporaryFile();
		$path = $temporaryFile->path;
		$temporaryFile->delete();
		$this->assertFalse(file_exists($path));
	}
}
