<?php
/**
 * Attachment
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

App::uses('FilesAppModel', 'Files.Model');
App::uses('Folder', 'Utility');

/**
 * Summary for File Model
 */

class UploadFile extends FilesAppModel {

	//var $useTable = 'attachments'; // TODO 実験用

	public $actsAs = [
		'Upload.Upload' => [
			'real_file_name' => array(
				'thumbnailSizes' => array(
					'xvga' => '1024x768',
					'vga' => '640x480',
					'thumb' => '80x80',
				),
				'nameCallback' => 'nameCallback',
			),


		],
	];

/**
 * nameCallback method
 *
 * @param string $field Name of field being modified
 * @param string $currentName current filename
 * @param array $data Array of data being manipulated in the current request
 * @param array $options Array of options for the current rename
 * @return string file name
 */
	public function nameCallback($field, $currentName, $data, $options) {
		return Security::hash($currentName);
	}


	/**
 * After Save
 *
 * @param boolean $created 新規のときtrue
 */
	public function afterSave($created, $options= array()) {
		// TODO UploadビヘイビアのaftereSave後に処理が必要なら実装する
		debug($created);
	}
}

