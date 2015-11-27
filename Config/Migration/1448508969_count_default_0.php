<?php
/**
 * CountDefaut0
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

/**
 * Class CountDefault0
 */
class CountDefault0 extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'count_default_0';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'alter_field' => array(
				'upload_files' => array(
					'download_count' => array('type' => 'integer', 'null' => true, 'default' => '0', 'unsigned' => false),
					'total_download_count' => array('type' => 'integer', 'null' => true, 'default' => '0', 'unsigned' => false),
				),
				'upload_files_contents' => array(
					'plugin_key' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
			),
		),
		'down' => array(
			'alter_field' => array(
				'upload_files' => array(
					'download_count' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
					'total_download_count' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
				),
				'upload_files_contents' => array(
					'plugin_key' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
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