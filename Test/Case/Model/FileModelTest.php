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

App::uses('FilesModelTestCase', 'Files.Test/Case/Model');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

/**
 * FileModel Test Case
 */
class FileModelTest extends FilesModelTestCase {

/**
 * Expect FileModel->saveFile() on success
 *
 * @return void
 */
	public function testSaveFile() {
		//データ生成
		$data = array(
			'File' => array(
				'status' => 1,
				'role_type' => 'file_test',
				'path' => TMP . 'tests' . DS . 'files' . DS,
				'slug' => 'file_test_1',
				'extension' => 'gif',
				'original_name' => 'file_test_1',
				'mimetype' => 'image/gif',
				'name' => 'logo.gif',
				'alt' => 'logo.gif',
				'size' => 5873,
			),
			'FilesPlugin' => array(
				'plugin_key' => 'files'
			),
			'FilesRoom' => array(
				'room_id' => 1
			),
			'FilesUser' => array(
				'user_id' => 1
			),
		);

		$this->FileModel->saveFile($data);

		//期待値の生成
		$fileId = 2;

		$expected = $data;
		$expected = Hash::insert($expected, '{s}.file_id', $fileId);
		$expected['File']['id'] = $expected['File']['file_id'];
		unset($expected['File']['file_id']);

		$result = $this->FileModel->findById($fileId);
		$result['FilesPlugin'] = $result['FilesPlugin'][0];
		$result['FilesRoom'] = $result['FilesRoom'][0];
		$result['FilesUser'] = $result['FilesUser'][0];

		$this->_assertArray(null, $expected, $result);
	}

/**
 * Expect FileModel->saveFile() on success
 *
 * @return void
 */
	public function testDeleteFile() {
		$fileId = 1;

		//データ生成
		$this->FileModel->deleteFiles($fileId);

		//期待値の生成
		$result = $this->FileModel->findById($fileId);
		$this->assertEmpty($result, 'FileModel');

		$result = $this->FilesPlugin->findByFileId($fileId);
		$this->assertEmpty($result, 'FilesPlugin');

		$result = $this->FilesRoom->findByFileId($fileId);
		$this->assertEmpty($result, 'FilesRoom');

		$result = $this->FilesUser->findByFileId($fileId);
		$this->assertEmpty($result, 'FilesUser');
	}

}
