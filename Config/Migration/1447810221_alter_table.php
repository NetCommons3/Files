<?php
/**
 * alter table
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

/**
 * Class AlterTable
 */
class AlterTable extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'alter_table';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'alter_field' => array(
				'files' => array(
					'role_type' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'c', 'charset' => 'utf8'),
				),
				'upload_files' => array(
					'real_file_name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'file name | ファイル名 |  | ', 'charset' => 'utf8'),
				),
			),
			'create_field' => array(
				'upload_files_contents' => array(
					'plugin_key' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'id'),
				),
			),
			'drop_field' => array(
				'upload_files_contents' => array('model'),
			),
		),
		'down' => array(
			'alter_field' => array(
				'files' => array(
					'role_type' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'comment' => 'c', 'charset' => 'utf8'),
				),
				'upload_files' => array(
					'real_file_name' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'comment' => 'file name | ファイル名 |  | ', 'charset' => 'utf8'),
				),
			),
			'drop_field' => array(
				'upload_files_contents' => array('plugin_key'),
			),
			'create_field' => array(
				'upload_files_contents' => array(
					'model' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
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
