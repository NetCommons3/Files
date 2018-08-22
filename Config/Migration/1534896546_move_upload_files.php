<?php
/**
 * app/webroot/filesからapp/Uploads/filesに移動
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

App::uses('Folder', 'Utility');

/**
 * Class app/webroot/filesからapp/Uploads/filesに移動
 */
class MoveUploadFiles extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'move_upload_files';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(),
		'down' => array(),
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
		if (file_exists(WWW_ROOT . 'files' . DS . 'upload_file' . DS) &&
				file_exists(dirname(WWW_ROOT) . DS . 'Uploads' . DS)) {
			$folder = new Folder();
			$folder->move([
				'from' => WWW_ROOT . 'files' . DS . 'upload_file',
				'to' => dirname(WWW_ROOT) . DS . 'Uploads' . DS . 'files' . DS . 'upload_file' . DS,
			]);
		}
		return true;
	}
}
