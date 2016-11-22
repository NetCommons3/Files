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
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'plugin_key' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'content_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 45, 'unsigned' => false),
		'upload_file_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 45, 'unsigned' => false),
		'created_user' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => '作成者'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '作成日時'),
		'modified_user' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => '更新者'),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '更新日時'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

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

}
