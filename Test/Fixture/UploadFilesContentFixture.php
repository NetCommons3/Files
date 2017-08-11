<?php
/**
 * UploadFilesContentFixture
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

/**
 * Summary for UploadFilesContentFixture
 */
class UploadFilesContentFixture extends CakeTestFixture {

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'plugin_key' => 'site_manager',
			'content_id' => 2,
			'upload_file_id' => 1,
			'created_user' => 1,
			'created' => '2015-10-29 08:50:56',
			'modified_user' => 1,
			'modified' => '2015-10-29 08:50:56'
		),
		array(
			'id' => 2,
			'plugin_key' => 'site_manager',
			'content_id' => 3,
			'upload_file_id' => 3,
			'created_user' => 1,
			'created' => '2015-10-29 08:50:56',
			'modified_user' => 1,
			'modified' => '2015-10-29 08:50:56'
		),
		array(
			'id' => 3,
			'plugin_key' => 'site_manager',
			'content_id' => 4,
			'upload_file_id' => 3,
			'created_user' => 1,
			'created' => '2015-10-29 08:50:56',
			'modified_user' => 1,
			'modified' => '2015-10-29 08:50:56'
		),
		array(
			'id' => 4,
			'plugin_key' => 'test_files',
			'content_id' => 2,
			'upload_file_id' => 4,
			'created_user' => 1,
			'created' => '2015-10-29 08:50:56',
			'modified_user' => 1,
			'modified' => '2015-10-29 08:50:56'
		),
		array(
			'id' => 5,
			'plugin_key' => 'test_files',
			'content_id' => 5,
			'upload_file_id' => 5,
			'created_user' => 1,
			'created' => '2015-10-29 08:50:56',
			'modified_user' => 1,
			'modified' => '2015-10-29 08:50:56'
		),
		array( // avatar
			'id' => 6,
			'plugin_key' => 'users',
			'content_id' => 1,
			'upload_file_id' => 6,
			'created_user' => 1,
			'created' => '2015-10-29 08:50:56',
			'modified_user' => 1,
			'modified' => '2015-10-29 08:50:56'
		),
		array( // video
			'id' => 7,
			'plugin_key' => 'videos',
			'content_id' => 2,
			'upload_file_id' => 11,
			'created_user' => 1,
			'created' => '2015-10-29 08:50:56',
			'modified_user' => 1,
			'modified' => '2015-10-29 08:50:56'
		),
	);

/**
 * Initialize the fixture.
 *
 * @return void
 */
	public function init() {
		require_once App::pluginPath('Files') . 'Config' . DS . 'Schema' . DS . 'schema.php';
		$this->fields = (new FilesSchema())->tables['upload_files_contents'];
		parent::init();
	}

}
