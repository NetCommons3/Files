<?php
/**
 * File Model
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('FilesAppModel', 'Files.Model');
App::uses('Folder', 'Utility');

/**
 * Summary for File Model
 */
class FileModel extends FilesAppModel {

/**
 * Custom database table name
 *
 * @var string
 */
	public $useTable = 'files';

/**
 * Alias name for model.
 *
 * @var string
 */
	public $alias = 'File';

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'FilesPlugin' => array(
			'className' => 'Files.FilesPlugin',
		),
		'FilesRoom' => array(
			'className' => 'Files.FilesRoom',
		),
		'FilesUser' => array(
			'className' => 'Files.FilesUser',
		)
	);

/**
 * use behavior
 *
 * @var array
 * @link http://book.cakephp.org/2.0/en/models/behaviors.html#using-behaviors
 */
	public $actsAs = array(
		'Files.YAUpload'
	);

/**
 * Called during validation operations, before validation. Please note that custom
 * validation rules can be defined in $validate.
 *
 * @param array $options Options passed from Model::save().
 * @return bool True if validate operation should continue, false to abort
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#beforevalidate
 * @see Model::save()
 */
	public function beforeValidate($options = array()) {
		$this->validate = Hash::merge($this->validate, array(
			'name' => array(
				'notEmpty' => array(
					'rule' => array('notEmpty'),
					'message' => __d('net_commons', 'Invalid request.'),
					'allowEmpty' => false,
					'required' => true,
				),
			),
			'slug' => array(
				'notEmpty' => array(
					'rule' => array('notEmpty'),
					'message' => __d('net_commons', 'Invalid request.'),
					'allowEmpty' => false,
					'required' => true,
				),
			),
			'path' => array(
				'notEmpty' => array(
					'rule' => array('notEmpty'),
					'message' => __d('net_commons', 'Invalid request.'),
					'allowEmpty' => false,
					//'required' => true,
					'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'extension' => array(
				'notEmpty' => array(
					'rule' => array('notEmpty'),
					'message' => __d('net_commons', 'Invalid request.'),
					'allowEmpty' => false,
					'required' => true,
				),
			),
			'mimetype' => array(
				'notEmpty' => array(
					'rule' => array('notEmpty'),
					'message' => __d('net_commons', 'Invalid request.'),
					'allowEmpty' => false,
					//'required' => true,
				),
			),
			'size' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					'message' => __d('net_commons', 'Invalid request.'),
					'allowEmpty' => false,
					//'required' => true,
				),
			),
			'role_type' => array(
				'notEmpty' => array(
					'rule' => array('notEmpty'),
					'message' => __d('net_commons', 'Invalid request.'),
					'allowEmpty' => false,
					'required' => true,
				),
			),
			'number_of_downloads' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					'message' => __d('net_commons', 'Invalid request.'),
				),
			),
			'status' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					'message' => __d('net_commons', 'Invalid request.'),
				),
			),
		));

		return parent::beforeValidate($options);
	}

/**
 * save file
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function saveFile($data) {
		$this->loadModels([
			'FileModel' => 'Files.FileModel',
			'FilesPlugin' => 'Files.FilesPlugin',
			'FilesRoom' => 'Files.FilesRoom',
			'FilesUser' => 'Files.FilesUser',
		]);

		//トランザクションBegin
		$dataSource = $this->getDataSource();
		$dataSource->begin();

		try {
			//validationを実行
			if (! $this->validateFile($data)) {
				return false;
			}
			if (! $this->validateFileAssociated($data)) {
				return false;
			}

			if (! $file = $this->save(null, false)) {
				// @codeCoverageIgnoreStart
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
				// @codeCoverageIgnoreEnd
			}

			$this->saveFileAssociated($file);

			//トランザクションCommit
			$dataSource->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$dataSource->rollback();
			//エラー出力
			CakeLog::error($ex);
			throw $ex;
		}

		return $file;
	}

/**
 * validate edumap
 *
 * @param array $data received post data
 * @return bool True on success, false on error
 */
	public function validateFile($data) {
		$this->set($data);
		$this->validates();
		return $this->validationErrors ? false : true;
	}

/**
 * validate edumap
 *
 * @param array $data received post data
 * @return bool True on success, false on error
 */
	public function validateFileAssociated($data) {
		foreach (['FilesPlugin', 'FilesRoom', 'FilesUser'] as $model) {
			if (! isset($data[$model])) {
				continue;
			}
			$this->$model->set($data);
			$this->$model->validates();
			if ($this->$model->validationErrors) {
				$this->validationErrors = Hash::merge($this->validationErrors, $this->$model->validationErrors);
			}
		}
		return $this->validationErrors ? false : true;
	}

/**
 * save edumap associeation
 *
 * @param array $data received post data
 * @return bool True on success, false on error
 * @throws InternalErrorException
 */
	public function saveFileAssociated($data) {
		foreach (['FilesPlugin', 'FilesRoom', 'FilesUser'] as $model) {
			if (! isset($data[$model])) {
				continue;
			}
			$this->$model->data[$model]['file_id'] = $data[$this->alias]['id'];
			if (! $this->$model->save(null, false)) {
				// @codeCoverageIgnoreStart
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
				// @codeCoverageIgnoreEnd
			}
		}
		return true;
	}

/**
 * delete file
 * Please do the transaction and validation in the caller.
 *
 * @param array $fileIds received delete data
 * @return mixed True on success, false on failure
 * @throws InternalErrorException
 */
	public function deleteFiles($fileIds) {
		$this->loadModels([
			'FileModel' => 'Files.FileModel',
			'FilesPlugin' => 'Files.FilesPlugin',
			'FilesRoom' => 'Files.FilesRoom',
			'FilesUser' => 'Files.FilesUser',
		]);

		//削除ファイルのデータ取得
		$files = $this->find('all', array(
			'recursive' => -1,
			'conditions' => array('id' => $fileIds),
		));

		//データ削除
		if (! $this->deleteAll([$this->alias . '.id' => $fileIds], true, false)) {
			// @codeCoverageIgnoreStart
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			// @codeCoverageIgnoreEnd
		}

		//関連データ削除
		$this->deleteFileAssociated($fileIds);

		//物理ファイルの削除
		$folder = new Folder();
		foreach ($files as $file) {
			$folder->delete($file[$this->alias]['path']);
		}

		return true;
	}

/**
 * deleteValidates method
 *
 * @param array $fileIds delete file id(s)
 * @return mixed array on success, false on error
 */
	public function validateDeletedFiles($fileIds) {
		//削除ファイルのデータ取得
		if (! $files = $this->find('all', [
			'recursive' => -1,
			'conditions' => ['id' => $fileIds],
		])) {
			return false;
		}

		//削除チェック(後で追加)
		//foreach ($files as $file) {
			//権限チェック
			//CakeLog::debug(print_r($file, true));
		//}

		return $files;
	}

/**
 * delete edumap associeation
 *
 * @param array $fileIds delete file id(s)
 * @return bool True on success, false on error
 * @throws InternalErrorException
 */
	public function deleteFileAssociated($fileIds) {
		//削除処理
		foreach (['FilesPlugin', 'FilesRoom', 'FilesUser'] as $model) {
			if (! $this->$model->deleteAll([$this->$model->alias . '.file_id' => $fileIds], true, false)) {
				// @codeCoverageIgnoreStart
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
				// @codeCoverageIgnoreEnd
			}
		}
		return true;
	}

}
