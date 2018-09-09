<?php
/**
 * 速度改善のためのインデックス追加したやつの修正
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsMigration', 'NetCommons.Config/Migration');

/**
 * 速度改善のためのインデックス追加したやつの修正
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Files\Config\Migration
 */
class ModifiedIndex2 extends NetCommonsMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'modified_index_2';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'drop_field' => array(
				'upload_files' => array('indexes' => array('content_key')),
			),
			'create_field' => array(
				'upload_files' => array(
					'indexes' => array(
						'content_key' => array('column' => array('content_key', 'field_name', 'plugin_key', 'id'), 'unique' => 0),
					),
				),
			),
		),
		'down' => array(
			'create_field' => array(
				'upload_files' => array(
					'indexes' => array(
						'content_key' => array(),
					),
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
