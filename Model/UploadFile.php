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
			'original_name' => array(
				'thumbnailSizes' => array(
					'xvga' => '1024x768',
					'vga' => '640x480',
					'thumb' => '80x80',
				)
			)
		],
	];


/**
 * After Save
 *
 * @param boolean $created 新規のときtrue
 */
//	public function afterSave($created)
//	{
//		// TODO UploadビヘイビアのaftereSave後に処理が必要なら実装する
//
//	}
}

