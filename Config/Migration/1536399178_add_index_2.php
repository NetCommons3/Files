<?php
/**
 * 速度改善のためのインデックス追加
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsMigration', 'NetCommons.Config/Migration');

/**
 * 速度改善のためのインデックス追加
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Files\Config\Migration
 */
class AddInde2 extends NetCommonsMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_index';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'alter_field' => array(
				'upload_files' => array(
					'content_key' => array('type' => 'string', 'null' => true, 'default' => null, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
			),
			'create_field' => array(
				'upload_files' => array(
					'indexes' => array(
						'content_key' => array('column' => array('content_key', 'id', 'field_name', 'extension'), 'unique' => 0),
					),
				),
			),
		),
		'down' => array(
			'alter_field' => array(
				'upload_files' => array(
					'content_key' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
			),
			'drop_field' => array(
				'upload_files' => array('indexes' => array('content_key')),
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
