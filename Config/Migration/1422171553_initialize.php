<?php
/**
 * Files Migration
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

/**
 * Files Migration
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Files\Config\Migration
 */
class Initialize extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'initialize';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'files' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary', 'comment' => 'ID |  |  | '),
					'name' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'comment' => 'file name | ファイル名 |  | ', 'charset' => 'utf8', 'after' => 'id'),
					'original_name' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'comment' => 'original file name | オリジナルファイル名 |  | ', 'charset' => 'utf8', 'after' => 'name'),
					'slug' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'comment' => 'slug | 固定リンク(デフォルト:アップロードID + 拡張子) |  | ', 'charset' => 'utf8', 'after' => 'name'),
					'extension' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'comment' => 'extension | 拡張子 |  | ', 'charset' => 'utf8', 'after' => 'path'),
					'path' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'comment' => 'path | パス |  | ', 'charset' => 'utf8', 'after' => 'slug'),
					'mimetype' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'comment' => 'mimetype | MIMEタイプ |  | ', 'charset' => 'utf8', 'after' => 'extension'),
					'size' => array('type' => 'integer', 'null' => false, 'default' => '0', 'comment' => 'file size | ファイルサイズ |  | ', 'after' => 'mimetype'),
					'alt' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'alt | 代替テキスト |  | ', 'charset' => 'utf8', 'after' => 'size'),
					'description' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'description | 説明 |  | ', 'charset' => 'utf8', 'after' => 'alt'),
					'role_type' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'role type | ロールタイプ | | e.g.) room_file_role, user_file_role, registoration_file_role', 'charset' => 'utf8', 'after' => 'description'),
					//'number_of_downloads' => array('type' => 'integer', 'null' => false, 'default' => '0', 'comment' => 'number of downloads | ダウンロード数 |  | ', 'after' => 'type'),
					'download_password' => array('type' => 'string', 'null' => true, 'collate' => 'utf8_general_ci', 'comment' => 'download password | ダウンロードパスワード |  | ', 'charset' => 'utf8', 'after' => 'number_of_downloads'),
					'status' => array('type' => 'integer', 'null' => true, 'default' => '0', 'comment' => 'status | ステータス | 0:未確定, 1:公開中, 2:利用中止 | ', 'after' => 'download_password'),
					'created_user' => array('type' => 'integer', 'null' => true, 'default' => '0', 'comment' => 'created user | 作成者 | users.id | '),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'created datetime | 作成日時 |  | '),
					'modified_user' => array('type' => 'integer', 'null' => true, 'default' => '0', 'comment' => 'modified user | 更新者 | users.id | '),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'modified datetime | 更新日時 |  | '),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'files_plugins' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary', 'comment' => 'ID |  |  | '),
					'file_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'comment' => 'file id | ファイルID | files.id | '),
					'plugin_key' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'plugin key | プラグインキー | plugins.key | ', 'charset' => 'utf8'),
					'created_user' => array('type' => 'integer', 'null' => true, 'default' => '0', 'comment' => 'created user | 作成者 | users.id | '),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'created datetime | 作成日時 |  | '),
					'modified_user' => array('type' => 'integer', 'null' => true, 'default' => '0', 'comment' => 'modified user | 更新者 | users.id | '),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'modified datetime | 更新日時 |  | '),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'files_rooms' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary', 'comment' => 'ID |  |  | '),
					'file_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'comment' => 'file id | ファイルID | files.id | '),
					'room_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'comment' => 'room id | ルームID | rooms.id | '),
					'created_user' => array('type' => 'integer', 'null' => true, 'default' => '0', 'comment' => 'created user | 作成者 | users.id | '),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'created datetime | 作成日時 |  | '),
					'modified_user' => array('type' => 'integer', 'null' => true, 'default' => '0', 'comment' => 'modified user | 更新者 | users.id | '),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'modified datetime | 更新日時 |  | '),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'files_users' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary', 'comment' => 'ID |  |  | '),
					'file_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'comment' => 'file id | ファイルID | files.id | '),
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'comment' => 'user id | ユーザID | users.id | '),
					'created_user' => array('type' => 'integer', 'null' => true, 'default' => '0', 'comment' => 'created user | 作成者 | users.id | '),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'created datetime | 作成日時 |  | '),
					'modified_user' => array('type' => 'integer', 'null' => true, 'default' => '0', 'comment' => 'modified user | 更新者 | users.id | '),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'modified datetime | 更新日時 |  | '),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'files', 'file_role_permissions', 'files_contents', 'files_rooms', 'files_users'
			),
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		return true;
	}
}
