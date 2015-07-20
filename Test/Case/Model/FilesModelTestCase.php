<?php
/**
 * File Test Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('FileModel', 'Files.Model');
App::uses('FileFixture', 'Files.Test/FileFixture');

/**
 * FileModel Test Case
 */
class FilesModelTestCase extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.files.file',
		'plugin.files.files_plugin',
		'plugin.files.files_room',
		'plugin.files.files_user',
		'plugin.m17n.language',
		'plugin.plugin_manager.plugin',
		'plugin.rooms.room',
		'plugin.users.user',
		'plugin.users.user_attributes_user',
	);

/**
 * Create temporary file for upload test.
 *
 * @param string $fileName Temporary file name
 * @return void
 */
	public function createTmpFile($fileName) {
		//アップロードテストのためのテンポラリファイル生成
		$folder = new Folder();
		$folder->create(TMP . 'tests' . DS . 'files' . DS . 'tmp');

		$file = new File(
			APP . 'Plugin' . DS . 'Files' . DS . 'Test' . DS . 'Fixture' . DS . $fileName
		);
		$file->copy(TMP . 'tests' . DS . 'files' . DS . 'tmp' . DS . $fileName);
		$file->close();

		unset($folder, $file);
	}

/**
 * Delete directory for upload test.
 *
 * @return void
 */
	public function tearDownFiles() {
		//アップロードテストのためのディレクトリ削除
		$folder = new Folder();
		$folder->delete(TMP . 'tests' . DS . 'files');

		unset($folder);
	}

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->FileModel = ClassRegistry::init('Files.FileModel');
		$this->FilesPlugin = ClassRegistry::init('Files.FilesPlugin');
		$this->FilesRoom = ClassRegistry::init('Files.FilesRoom');
		$this->FilesUser = ClassRegistry::init('Files.FilesUser');

		$this->FileModel->Behaviors->attach('YAUpload');
		$this->FileModel->Behaviors->YAUpload->setup($this->FileModel, array('upload' => array()));
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->FileModel);
		unset($this->FilesPlugin);
		unset($this->FilesRoom);
		unset($this->FilesUser);

		$this->tearDownFiles();
		parent::tearDown();
	}

/**
 * _assertArray method
 *
 * @param string $key target key
 * @param mixed $value array or string, number
 * @param array $result result data
 * @return void
 */
	protected function _assertArray($key, $value, $result) {
		if ($key !== null) {
			$this->assertArrayHasKey($key, $result);
			$target = $result[$key];
		} else {
			$target = $result;
		}
		if (is_array($value)) {
			foreach ($value as $nextKey => $nextValue) {
				$this->_assertArray($nextKey, $nextValue, $target);
			}
		} elseif (isset($value)) {
			$this->assertEquals($value, $target, 'key=' . print_r($key, true) . '|value=' . print_r($value, true) . '|result=' . print_r($result, true));
		}
	}

/**
 * Create uploaded file for upload test.
 *
 * @return void
 */
	//public function setUpFiles($data) {
	//	//アップロードテストのためのアップロードファイル生成
	//	$folder = new Folder();
	//
	//	foreach ($this->records as $i => $recode) {
	//		$fileFixture = APP . 'Plugin' . DS . 'Files' . DS . 'Test' . DS . 'Fixture' . DS . $this->records[$i]['name'];
	//		if (! file_exists($fileFixture)) {
	//			continue;
	//		}
	//		$folder->create($this->records[$i]['path']);
	//		$file = new File($fileFixture);
	//		$file->copy($this->records[$i]['path'] . $this->records[$i]['original_name'] . '.' . $this->records[$i]['extension']);
	//
	//		if (preg_match('/^image/', $this->records[$i]['mimetype']) === 1) {
	//			foreach (['_big', '_medium', '_small', '_thumbnail'] as $size) {
	//				$file->copy(
	//					$this->records[$i]['path'] . $this->records[$i]['original_name'] . $size . '.' . $this->records[$i]['extension']
	//				);
	//			}
	//		}
	//		$file->close();
	//		unset($file);
	//	}
	//
	//	unset($folder);
	//}

/**
 * Create temporary file for upload test.
 *
 * @return void
 */
	//public static function createTmpFile($fileName) {
	//	//アップロードテストのためのテンポラリファイル生成
	//	$folder = new Folder();
	//	$folder->create(TMP . 'tests' . DS . Inflector::variable($this->name) . DS . 'tmp');
	//
	//	$file = new File(
	//		APP . 'Plugin' . DS . 'Files' . DS . 'Test' . DS . 'Fixture' . DS . $fileName
	//	);
	//	$file->copy(TMP . 'tests' . DS . Inflector::variable($this->name) . DS . 'tmp' . DS . $fileName);
	//	$file->close();
	//
	//	unset($folder, $file);
	//}

/**
 * Delete directory for upload test.
 *
 * @return void
 */
	//public static function tearDownFiles() {
	//	//アップロードテストのためのディレクトリ削除
	//	$folder = new Folder();
	//	$folder->delete(TMP . 'tests' . DS . Inflector::variable($this->name));
	//
	//	unset($folder);
	//}

/**
 * testIndex method
 *
 * @return void
 */
	public function testIndex() {
		$this->assertTrue(true);
	}

}
