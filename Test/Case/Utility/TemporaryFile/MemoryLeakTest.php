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
 * メモリ使用量の測定用 
 *
 */
class TemporaryFileMemoryLeakTest extends NetCommonsCakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = [];

/**
 * setup
 */
	public function setUp() {
		$this->markTestSkipped();
	}

/**
 * testMemoryLeakForKeepFiles
 *
 * @return void
 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
 */
	public function testMemoryLeakForKeepFiles() {
		$max = 1000;
		App::uses('TemporaryFolder', 'Files.Utility');
		$before = $this->__reportMemory('before');
		for ($i = 0; $i < $max; $i++) {
			$files[] = new TemporaryFile();
		}
		$this->__reportMemory('after ', $before);
	}

/**
 * testMemoryLeakWithDelete
 *
 * @return void
 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
 */
	public function testMemoryLeakWithDelete() {
		$max = 1000;
		App::uses('TemporaryFolder', 'Files.Utility');
		$before = $this->__reportMemory('before');
		for ($i = 0; $i < $max; $i++) {
			$file = new TemporaryFile();
			$files[] = $file;
			$file->delete();
		}
		$this->__reportMemory('after ', $before);
	}

/**
 * testMemoryLeakWithDeleteAndOverwrite
 *
 * @return void
 */
	public function testMemoryLeakWithDeleteAndOverwrite() {
		$max = 1000;
		App::uses('TemporaryFolder', 'Files.Utility');
		$before = $this->__reportMemory('before');
		for ($i = 0; $i < $max; $i++) {
			$folder = new TemporaryFile();
			$folder->delete();
		}
		$this->__reportMemory('after ', $before);
	}

/**
 * testMemoryLeakWithOverwrite
 *
 * @return void
 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
 */
	public function testMemoryLeakWithOverwrite() {
		$max = 1000;
		App::uses('TemporaryFolder', 'Files.Utility');
		$before = $this->__reportMemory('before');
		for ($i = 0; $i < $max; $i++) {
			$file = new TemporaryFile();
			// ここでテンポラリファイルを使った処理を想定
		}
		$this->__reportMemory('after ', $before);
	}

/**
 * __reportMemory
 *
 * @param string $string
 * @param int|null $before
 * @return int
 */
	private function __reportMemory(string $string, int $before = null) {
		$current = memory_get_usage(false);
		print $string . ':' . number_format($current);
		if ($before !== null) {
			print "\n";
			print $string . '(diff):' . number_format($current - $before);
		}
		print "\n";
		return $current;
	}
}
