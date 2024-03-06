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
App::uses('TemporaryFile2', 'Files.Utility');

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
	public function testMemoryLeakForKeepFiles() {
		$max = 1000;
		App::uses('TemporaryFolder', 'Files.Utility');
		$before = $this->reportMemory('before');
		for ($i = 0; $i< $max; $i++) {
			$files[] = new TemporaryFile();
		}
		$this->reportMemory('after ', $before);
	}

	public function testMemoryLeakForKeepFilesByTemporaryFile2() {
		$max = 1000;
		App::uses('TemporaryFolder', 'Files.Utility');
		$before = $this->reportMemory('before');
		for ($i = 0; $i< $max; $i++) {
			$files[] = new TemporaryFile2();
		}
		$this->reportMemory('after ', $before);
	}

	public function testMemoryLeakWithDelete() {
		$max = 1000;
		App::uses('TemporaryFolder', 'Files.Utility');
		$before = $this->reportMemory('before');
		for ($i = 0; $i< $max; $i++) {
			$file = new TemporaryFile();
			$files[] =$file;
			$file->delete();
		}
		$this->reportMemory('after ', $before);
	}

	public function testMemoryLeakWithDeleteByTemporaryFile2() {
		$max = 1000;
		App::uses('TemporaryFolder', 'Files.Utility');
		$before = $this->reportMemory('before');
		for ($i = 0; $i< $max; $i++) {
			$file = new TemporaryFile2();
			$files[] =$file;
			$file->delete();
		}
		$this->reportMemory('after ', $before);
	}
	public function testMemoryLeakWithDeleteAndOverwrite() {
		$max = 1000;
		App::uses('TemporaryFolder', 'Files.Utility');
		$before = $this->reportMemory('before');
		for ($i = 0; $i< $max; $i++) {
			$folder = new TemporaryFile();
			$folder->delete();
		}
		$this->reportMemory('after ', $before);
	}

	public function testMemoryLeakWithDeleteAndOverwriteByTemporaryFile2() {
		$max = 1000;
		App::uses('TemporaryFolder', 'Files.Utility');
		$before = $this->reportMemory('before');
		for ($i = 0; $i< $max; $i++) {
			$folder = new TemporaryFile2();
			$folder->delete();
		}
		$this->reportMemory('after ', $before);
	}

	public function testMemoryLeakWithOverwrite() {
		$max = 1000;
		App::uses('TemporaryFolder', 'Files.Utility');
		$before = $this->reportMemory('before');
		for ($i = 0; $i< $max; $i++) {
			$file = new TemporaryFile();
			// ここでテンポラリファイルを使った処理を想定
		}
		$this->reportMemory('after ', $before);
	}

	public function testMemoryLeakWithOverwriteByTemporaryFile2() {
		$max = 1000;
		App::uses('TemporaryFolder', 'Files.Utility');
		$before = $this->reportMemory('before');
		for ($i = 0; $i< $max; $i++) {
			$file = new TemporaryFile2();
			// ここでテンポラリファイルを使った処理を想定
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
