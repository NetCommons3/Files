<?php
/**
 * Schema file
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * Schema file
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Files\Config\Schema
 */
class AppSchema extends CakeSchema {

/**
 * Database connection
 *
 * @var string
 */
	public $connection = 'master';

/**
 * before
 *
 * @param array $event savent
 * @return bool
 */
	public function before($event = array()) {
		return true;
	}

/**
 * after
 *
 * @param array $event event
 * @return void
 */
	public function after($event = array()) {
	}

/**
 * files table
 *
 * @var array
 */
	public $files = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => '', 'key' => 'primary', 'comment' => 'ID |  |  | '),
		'name' => array('type' => 'string', 'null' => false, 'default' => '', 'collate' => 'utf8_general_ci', 'comment' => 'file name | ファイル名 |  | ', 'charset' => 'utf8'),
		'original_name' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'comment' => 'original file name | オリジナルファイル名 |  | ', 'charset' => 'utf8'),
		'slug' => array('type' => 'string', 'null' => false, 'default' => '', 'collate' => 'utf8_general_ci', 'comment' => 'slug | 固定リンク(デフォルト:アップロードID + 拡張子) |  | ', 'charset' => 'utf8'),
		'extension' => array('type' => 'string', 'null' => false, 'default' => '', 'collate' => 'utf8_general_ci', 'comment' => 'extension | 拡張子 |  | ', 'charset' => 'utf8'),
		'path' => array('type' => 'string', 'null' => false, 'default' => '', 'collate' => 'utf8_general_ci', 'comment' => 'path | パス |  | ', 'charset' => 'utf8'),
		'mimetype' => array('type' => 'string', 'null' => false, 'default' => '', 'collate' => 'utf8_general_ci', 'comment' => 'mimetype | MIMEタイプ |  | ', 'charset' => 'utf8'),
		'size' => array('type' => 'integer', 'null' => false, 'default' => '0', 'comment' => 'file size | ファイルサイズ |  | '),
		'alt' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'alt | 代替テキスト |  | ', 'charset' => 'utf8'),
		'description' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'description | 説明 |  | ', 'charset' => 'utf8'),
		'role_type' => array('type' => 'string', 'null' => false, 'default' => '', 'collate' => 'utf8_general_ci', 'comment' => 'role type | ロールタイプ | | e.g.) room_file_role, user_file_role, registoration_file_role', 'charset' => 'utf8'),
		//'number_of_downloads' => array('type' => 'integer', 'null' => false, 'default' => '0', 'comment' => 'number of downloads | ダウンロード数 |  | '),
		'download_password' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'download password | ダウンロードパスワード |  | ', 'charset' => 'utf8'),
		'status' => array('type' => 'integer', 'null' => false, 'default' => '0', 'comment' => 'status | ステータス | 0:未確定, 1:公開中, 2:利用中止 | '),
		'created_user' => array('type' => 'integer', 'null' => true, 'default' => '0', 'comment' => 'created user | 作成者 | users.id | '),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'created datetime | 作成日時 |  | '),
		'modified_user' => array('type' => 'integer', 'null' => true, 'default' => '0', 'comment' => 'modified user | 更新者 | users.id | '),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'modified datetime | 更新日時 |  | '),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * files_plugins table
 *
 * @var array
 */
	public $files_plugins = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary', 'comment' => 'ID |  |  | '),
		'file_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'comment' => 'file id | ファイルID | files.id | '),
		'plugin_key' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'plugin key | プラグインキー | plugins.key | ', 'charset' => 'utf8'),
		'created_user' => array('type' => 'integer', 'null' => true, 'default' => '0', 'comment' => 'created user | 作成者 | users.id | '),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'created datetime | 作成日時 |  | '),
		'modified_user' => array('type' => 'integer', 'null' => true, 'default' => '0', 'comment' => 'modified user | 更新者 | users.id | '),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'modified datetime | 更新日時 |  | '),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * files_rooms table
 *
 * @var array
 */
	public $files_rooms = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary', 'comment' => 'ID |  |  | '),
		'file_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'comment' => 'file id | ファイルID | files.id | '),
		'room_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'comment' => 'room id | ルームID | rooms.id | '),
		'created_user' => array('type' => 'integer', 'null' => true, 'default' => '0', 'comment' => 'created user | 作成者 | users.id | '),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'created datetime | 作成日時 |  | '),
		'modified_user' => array('type' => 'integer', 'null' => true, 'default' => '0', 'comment' => 'modified user | 更新者 | users.id | '),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'modified datetime | 更新日時 |  | '),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * files_users table
 *
 * @var array
 */
	public $files_users = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary', 'comment' => 'ID |  |  | '),
		'file_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'comment' => 'file id | ファイルID | files.id | '),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'comment' => 'user id | ユーザID | users.id | '),
		'created_user' => array('type' => 'integer', 'null' => true, 'default' => '0', 'comment' => 'created user | 作成者 | users.id | '),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'created datetime | 作成日時 |  | '),
		'modified_user' => array('type' => 'integer', 'null' => true, 'default' => '0', 'comment' => 'modified user | 更新者 | users.id | '),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'modified datetime | 更新日時 |  | '),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

}
