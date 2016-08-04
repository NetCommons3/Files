<?php
/**
 * add content_is_active and content_is_latest
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

/**
 * Class AddContentIsActiveAndLatest
 */
class AddContentIsActiveAndLatest extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_content_is_active_and_latest';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'upload_files_contents' => array(
					'content_is_active' => array('type' => 'boolean', 'null' => true, 'default' => null, 'after' => 'upload_file_id'),
					'content_is_latest' => array('type' => 'boolean', 'null' => true, 'default' => null, 'after' => 'content_is_active'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'upload_files_contents' => array('content_is_active', 'content_is_latest'),
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
