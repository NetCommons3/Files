<?php
/**
 * UploadFilesContent Model
 *
 * @property Content $Content
 * @property UploadFile $UploadFile
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

App::uses('FilesAppModel', 'Files.Model');

/**
 * Summary for UploadFilesContent Model
 */
class UploadFilesContent extends FilesAppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'model' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'content_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'upload_file_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed
/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'UploadFile' => array(
			'className' => 'Files.UploadFile',
			'foreignKey' => 'upload_file_id',
			'conditions' => '',
			'fields' => [
				'id', 'plugin_key', 'content_key', 'field_name', 'original_name', 'path',
				'real_file_name', 'extension', 'mimetype', 'size', 'download_count',
				'total_download_count', 'room_id', 'block_key'
			],
			'type' => 'inner',
			'order' => ''
		),
	);

}
