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
class TemporaryFolderMemoryLeakTest extends NetCommonsCakeTestCase {

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
 * testMemoryLeakForKeepFolders
 * メモリ使用量確認用コード。テスト対象外
 *
 * @return void
 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
 */
	public function testMemoryLeakForKeepFolders() {
		$max = 1000;
		App::uses('TemporaryFolder', 'Files.Utility');
		$before = $this->__reportMemory('before');
		for ($i = 0; $i < $max; $i++) {
			$folders[] = new TemporaryFolder();
		}
		$this->__reportMemory('after ', $before);
	}

/**
 * testMemoryLeakWithDelete
 * メモリ使用量確認用コード。テスト対象外
 *
 * @return void
 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
 */
	public function testMemoryLeakWithDelete() {
		$max = 1000;
		App::uses('TemporaryFolder', 'Files.Utility');
		$before = $this->__reportMemory('before');
		for ($i = 0; $i < $max; $i++) {
			$folder = new TemporaryFolder();
			$folders[] = $folder;
			$folder->delete();
		}
		$this->__reportMemory('after ', $before);
	}

/**
 * testMemoryLeakWithDeleteAndOverwrite
 * メモリ使用量確認用コード。テスト対象外
 *
 * @return void
 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
 */
	public function testMemoryLeakWithDeleteAndOverwrite() {
		$max = 1000;
		App::uses('TemporaryFolder', 'Files.Utility');
		$before = $this->__reportMemory('before');
		for ($i = 0; $i < $max; $i++) {
			$folder = new TemporaryFolder();
			$folder->delete();
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
