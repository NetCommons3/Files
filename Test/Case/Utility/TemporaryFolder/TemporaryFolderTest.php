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
App::uses('TemporaryFolder2', 'Files.Utility');

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
	public function testMemoryLeakForKeepFolders() {
		$max = 1000;
		App::uses('TemporaryFolder', 'Files.Utility');
		$before = $this->reportMemory('before');
		for ($i = 0; $i< $max; $i++) {
			$folders[] = new TemporaryFolder();
		}
		$this->reportMemory('after ', $before);
	}

	public function testMemoryLeakForKeepFoldersByTemporaryFolder2() {
		$max = 1000;
		App::uses('TemporaryFolder', 'Files.Utility');
		$before = $this->reportMemory('before');
		for ($i = 0; $i< $max; $i++) {
			$folders[] = new TemporaryFolder2();
		}
		$this->reportMemory('after ', $before);
	}

	public function testMemoryLeakWithDelete() {
		$max = 1000;
		App::uses('TemporaryFolder', 'Files.Utility');
		$before = $this->reportMemory('before');
		for ($i = 0; $i< $max; $i++) {
			$folder = new TemporaryFolder();
			$folders[] =$folder;
			$folder->delete();
		}
		$this->reportMemory('after ', $before);
	}

	public function testMemoryLeakWithDeleteByTemporaryFolder2() {
		$max = 1000;
		App::uses('TemporaryFolder', 'Files.Utility');
		$before = $this->reportMemory('before');
		for ($i = 0; $i< $max; $i++) {
			$folder = new TemporaryFolder2();
			$folders[] =$folder;
			$folder->delete();
		}
		$this->reportMemory('after ', $before);
	}
	public function testMemoryLeakWithDeleteAndOverwrite() {
		$max = 1000;
		App::uses('TemporaryFolder', 'Files.Utility');
		$before = $this->reportMemory('before');
		for ($i = 0; $i< $max; $i++) {
			$folder = new TemporaryFolder();
			$folder->delete();
		}
		$this->reportMemory('after ', $before);
	}

	public function testMemoryLeakWithDeleteAndOverwriteByTemporaryFolder2() {
		$max = 1000;
		App::uses('TemporaryFolder', 'Files.Utility');
		$before = $this->reportMemory('before');
		for ($i = 0; $i< $max; $i++) {
			$folder = new TemporaryFolder2();
			$folder->delete();
		}
		$this->reportMemory('after ', $before);
	}
	private function reportMemory(string $string, int $before = null) {
		$current = memory_get_usage(false);
		print($string . ':' . number_format($current));
		if ($before !== null) {
			print "\n";
			print($string . '(diff):' . number_format($current - $before));
		}
		print "\n";
		return $current;
	}
}
