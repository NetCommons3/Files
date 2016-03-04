<?php
/**
 * UploadFileFixture
 *
* @author Noriko Arai <arai@nii.ac.jp>
* @author Your Name <yourname@domain.com>
* @link http://www.netcommons.org NetCommons Project
* @license http://www.netcommons.org/license.txt NetCommons License
* @copyright Copyright 2014, NetCommons Project
 */

/**
 * Summary for UploadFileFixture
 */
class UploadFileFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary', 'comment' => 'ID |  |  | '),
		'plugin_key' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'content_key' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'field_name' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'original_name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'original file name | オリジナルファイル名 |  | ', 'charset' => 'utf8'),
		'path' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'path | パス |  | ', 'charset' => 'utf8'),
		'real_file_name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'file name | ファイル名 |  | ', 'charset' => 'utf8'),
		'extension' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'extension | 拡張子 |  | ', 'charset' => 'utf8'),
		'mimetype' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'mimetype | MIMEタイプ |  | ', 'charset' => 'utf8'),
		'size' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'comment' => 'file size | ファイルサイズ |  | '),
		'download_count' => array('type' => 'integer', 'null' => true, 'default' => '0', 'unsigned' => false),
		'total_download_count' => array('type' => 'integer', 'null' => true, 'default' => '0', 'unsigned' => false),
		'room_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'block_key' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created_user' => array('type' => 'integer', 'null' => true, 'default' => '0', 'unsigned' => false, 'comment' => 'created user | 作成者 | users.id | '),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'created datetime | 作成日時 |  | '),
		'modified_user' => array('type' => 'integer', 'null' => true, 'default' => '0', 'unsigned' => false, 'comment' => 'modified user | 更新者 | users.id | '),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'modified datetime | 更新日時 |  | '),
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
			'content_key' => 'theme',
			'field_name' => 'photo',
			'original_name' => 'foo.jpg',
			'path' => 'files/upload_file/real_file_name/1/',
			'real_file_name' => 'foobarhash.jpg',
			'extension' => 'jpg',
			'mimetype' => 'image/jpg',
			'size' => 1,
			'download_count' => 1,
			'total_download_count' => 1,
			'room_id' => 1,
			'block_key' => 'block_1',
			'created_user' => 1,
			'created' => '2015-11-06 02:20:55',
			'modified_user' => 1,
			'modified' => '2015-11-06 02:20:55'
		),
		array(
			'id' => 2,
			'plugin_key' => 'Lorem ipsum dolor sit amet',
			'content_key' => 'Lorem ipsum dolor sit amet',
			'field_name' => 'Lorem ipsum dolor sit amet',
			'original_name' => 'Lorem ipsum dolor sit amet',
			'path' => 'Lorem ipsum dolor sit amet',
			'real_file_name' => 'Lorem ipsum dolor sit amet',
			'extension' => 'Lorem ipsum dolor sit amet',
			'mimetype' => 'Lorem ipsum dolor sit amet',
			'size' => 2,
			'download_count' => 2,
			'total_download_count' => 2,
			'room_id' => 2,
			'block_key' => 'Lorem ipsum dolor sit amet',
			'created_user' => 2,
			'created' => '2016-02-25 03:44:14',
			'modified_user' => 2,
			'modified' => '2016-02-25 03:44:14'
		),
		array(
			'id' => 3,
			'plugin_key' => 'site_manager',
			'content_key' => 'theme',
			'field_name' => 'photo',
			'original_name' => 'foo.jpg',
			'path' => 'files/upload_file/real_file_name/1/',
			'real_file_name' => 'foobarhash.jpg',
			'extension' => 'jpg',
			'mimetype' => 'image/jpg',
			'size' => 1,
			'download_count' => 1,
			'total_download_count' => 1,
			'room_id' => 1,
			'block_key' => 'block_1',
			'created_user' => 1,
			'created' => '2015-11-06 02:20:55',
			'modified_user' => 1,
			'modified' => '2015-11-06 02:20:55'
		),
		array(
			'id' => 4,
			'plugin_key' => 'test_files',
			'content_key' => 'publish_key',
			'field_name' => 'photo',
			'original_name' => 'photo.jpg',
			'path' => 'files/upload_file/real_file_name/1/',
			'real_file_name' => 'hash_filename.jpg',
			'extension' => 'jpg',
			'mimetype' => 'image/jpg',
			'size' => 4,
			'download_count' => 4,
			'total_download_count' => 4,
			'room_id' => 1,
			'block_key' => 'block_1',
			'created_user' => 4,
			'created' => '2016-02-25 03:44:14',
			'modified_user' => 4,
			'modified' => '2016-02-25 03:44:14'
		),
		array(
			'id' => 5,
			'plugin_key' => 'Lorem ipsum dolor sit amet',
			'content_key' => 'Lorem ipsum dolor sit amet',
			'field_name' => 'Lorem ipsum dolor sit amet',
			'original_name' => 'Lorem ipsum dolor sit amet',
			'path' => 'Lorem ipsum dolor sit amet',
			'real_file_name' => 'Lorem ipsum dolor sit amet',
			'extension' => 'Lorem ipsum dolor sit amet',
			'mimetype' => 'Lorem ipsum dolor sit amet',
			'size' => 5,
			'download_count' => 5,
			'total_download_count' => 5,
			'room_id' => 5,
			'block_key' => 'Lorem ipsum dolor sit amet',
			'created_user' => 5,
			'created' => '2016-02-25 03:44:14',
			'modified_user' => 5,
			'modified' => '2016-02-25 03:44:14'
		),
		array(
			'id' => 6,
			'plugin_key' => 'Lorem ipsum dolor sit amet',
			'content_key' => 'Lorem ipsum dolor sit amet',
			'field_name' => 'Lorem ipsum dolor sit amet',
			'original_name' => 'Lorem ipsum dolor sit amet',
			'path' => 'Lorem ipsum dolor sit amet',
			'real_file_name' => 'Lorem ipsum dolor sit amet',
			'extension' => 'Lorem ipsum dolor sit amet',
			'mimetype' => 'Lorem ipsum dolor sit amet',
			'size' => 6,
			'download_count' => 6,
			'total_download_count' => 6,
			'room_id' => 6,
			'block_key' => 'Lorem ipsum dolor sit amet',
			'created_user' => 6,
			'created' => '2016-02-25 03:44:14',
			'modified_user' => 6,
			'modified' => '2016-02-25 03:44:14'
		),
		array(
			'id' => 7,
			'plugin_key' => 'Lorem ipsum dolor sit amet',
			'content_key' => 'Lorem ipsum dolor sit amet',
			'field_name' => 'Lorem ipsum dolor sit amet',
			'original_name' => 'Lorem ipsum dolor sit amet',
			'path' => 'Lorem ipsum dolor sit amet',
			'real_file_name' => 'Lorem ipsum dolor sit amet',
			'extension' => 'Lorem ipsum dolor sit amet',
			'mimetype' => 'Lorem ipsum dolor sit amet',
			'size' => 7,
			'download_count' => 7,
			'total_download_count' => 7,
			'room_id' => 7,
			'block_key' => 'Lorem ipsum dolor sit amet',
			'created_user' => 7,
			'created' => '2016-02-25 03:44:14',
			'modified_user' => 7,
			'modified' => '2016-02-25 03:44:14'
		),
		array(
			'id' => 8,
			'plugin_key' => 'Lorem ipsum dolor sit amet',
			'content_key' => 'Lorem ipsum dolor sit amet',
			'field_name' => 'Lorem ipsum dolor sit amet',
			'original_name' => 'Lorem ipsum dolor sit amet',
			'path' => 'Lorem ipsum dolor sit amet',
			'real_file_name' => 'Lorem ipsum dolor sit amet',
			'extension' => 'Lorem ipsum dolor sit amet',
			'mimetype' => 'Lorem ipsum dolor sit amet',
			'size' => 8,
			'download_count' => 8,
			'total_download_count' => 8,
			'room_id' => 8,
			'block_key' => 'Lorem ipsum dolor sit amet',
			'created_user' => 8,
			'created' => '2016-02-25 03:44:14',
			'modified_user' => 8,
			'modified' => '2016-02-25 03:44:14'
		),
		array(
			'id' => 9,
			'plugin_key' => 'Lorem ipsum dolor sit amet',
			'content_key' => 'Lorem ipsum dolor sit amet',
			'field_name' => 'Lorem ipsum dolor sit amet',
			'original_name' => 'Lorem ipsum dolor sit amet',
			'path' => 'Lorem ipsum dolor sit amet',
			'real_file_name' => 'Lorem ipsum dolor sit amet',
			'extension' => 'Lorem ipsum dolor sit amet',
			'mimetype' => 'Lorem ipsum dolor sit amet',
			'size' => 9,
			'download_count' => 9,
			'total_download_count' => 9,
			'room_id' => 9,
			'block_key' => 'Lorem ipsum dolor sit amet',
			'created_user' => 9,
			'created' => '2016-02-25 03:44:14',
			'modified_user' => 9,
			'modified' => '2016-02-25 03:44:14'
		),
		array(
			'id' => 10,
			'plugin_key' => 'Lorem ipsum dolor sit amet',
			'content_key' => 'Lorem ipsum dolor sit amet',
			'field_name' => 'Lorem ipsum dolor sit amet',
			'original_name' => 'Lorem ipsum dolor sit amet',
			'path' => 'Lorem ipsum dolor sit amet',
			'real_file_name' => 'Lorem ipsum dolor sit amet',
			'extension' => 'Lorem ipsum dolor sit amet',
			'mimetype' => 'Lorem ipsum dolor sit amet',
			'size' => 10,
			'download_count' => 10,
			'total_download_count' => 10,
			'room_id' => 10,
			'block_key' => 'Lorem ipsum dolor sit amet',
			'created_user' => 10,
			'created' => '2016-02-25 03:44:14',
			'modified_user' => 10,
			'modified' => '2016-02-25 03:44:14'
		),
	);

}
