<?php
/**
 * Upload behavior
 *
 * Enables users to easily add file uploading and necessary validation rules
 *
 * PHP versions 4 and 5
 *
 * Copyright 2010, Jose Diaz-Gonzalez
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2010, Jose Diaz-Gonzalez
 * @link          http://github.com/josegonzalez/cakephp-upload
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('UploadBehavior', 'Upload.Model/Behavior');
App::uses('Folder', 'Utility');

/**
 * Summary for Files Upload Behavior
 */
class YAUploadBehavior extends UploadBehavior {

/**
 * File Base URL
 *
 * @var string
 */
	const FILE_BASE_URL = '/files/files/download/';

/**
 * upload dir
 *
 * @var string
 */
	const UPLOAD_DIR = 'uploads';

/**
 * rootDir variables
 *
 * @var array
 */
	public $rootDir;

/**
 * fileBaseUrl variable
 *
 * @var array
 */
	public $fileBaseUrl = self::FILE_BASE_URL;

/**
 * upload dir variable
 *
 * @var array
 */
	public $uploadDir = self::UPLOAD_DIR;

/**
 * thumbnailSizes
 *
 * @var array
 */
	public $thumbnailSizes = array(
		'big' => '800ml',
		'medium' => '450ml',
		'small' => '250ml',
		'thumbnail' => '100ml',
	);

/**
 * Override Upload befavior's default for NetCommons
 *
 * @var array
 */
	private $__default = array(
		'maxSize' => 200097152,
		'fields' => [
			'type' => 'mimetype',
			'dir' => 'path',
		],
		'thumbnailPrefixStyle' => false,
		'nameCallback' => 'nameCallback',
	);

/**
 * SetUp Upload behavior
 *
 * @param object $model instance of model
 * @param array $config array of configuration settings.
 * @return void
 */
	public function setup(Model $model, $config = array()) {
		CakeLog::debug('YAUploadBefavior::setup()');

		if (isset($config['fileBaseUrl'])) {
			$this->fileBaseUrl = $config['fileBaseUrl'];
			unset($config['fileBaseUrl']);
		}
		if (isset($config['uploadDir'])) {
			$this->uploadDir = $config['uploadDir'];
			unset($config['uploadDir']);
		}
		if (isset($config['thumbnailSizes'])) {
			$this->thumbnailSizes = $config['thumbnailSizes'];
			unset($config['thumbnailSizes']);
		}
		if (isset($config['rootDir'])) {
			$this->rootDir = $config['rootDir'];
			unset($config['rootDir']);
		} else {
			$this->rootDir = ROOT . DS . APP_DIR . DS . $this->uploadDir . DS;
		}

		$fields = array_keys($config);
		foreach ($fields as $field) {
			if (! isset($config[$field]['rootDir'])) {
				$config[$field]['rootDir'] = $this->rootDir;
			}
			if (! isset($config[$field]['thumbnailSizes'])) {
				$config[$field]['thumbnailSizes'] = $this->thumbnailSizes;
			}

			$config[$field] = Hash::merge($this->__default, $config[$field]);
		}

		//CakeLog::debug('YAUploadBefavior::setup() $config=' . print_r($config, true));

		parent::setup($model, $config);
	}

/**
 * nameCallback method
 *
 * @param Model $model Model instance
 * @param string $field Name of field being modified
 * @param string $currentName current filename
 * @param array $data Array of data being manipulated in the current request
 * @param array $options Array of options for the current rename
 * @return string file name
 */
	public function nameCallback(Model $model, $field, $currentName, $data, $options) {
		CakeLog::debug('YAUploadBefavior::nameCallback()');
		//CakeLog::debug('YAUploadBefavior::nameCallback() $field=' . print_r($field, true));
		//CakeLog::debug('YAUploadBefavior::nameCallback() $currentName=' . print_r($currentName, true));
		//CakeLog::debug('YAUploadBefavior::nameCallback() $data=' . print_r($data, true));
		//CakeLog::debug('YAUploadBefavior::nameCallback() $options=' . print_r($options, true));

		return $data[$field]['File']['slug'] . '.' . pathinfo($currentName, PATHINFO_EXTENSION);
	}

/**
 * Updates a database record with the necessary extra data
 *
 * @param Model $model Model instance
 * @param array $data array containing data to be saved to the record
 * @return void
 */
	protected function _updateRecord(Model $model, $data) {
//		CakeLog::debug('YAUploadBefavior::_updateRecord()');
//		CakeLog::debug('YAUploadBefavior::_updateRecord() $data=' . print_r($data, true));
//		CakeLog::debug('YAUploadBefavior::_updateRecord() $model->data=' . print_r($model->data, true));
////		if ($model->FileModel) {
//			CakeLog::debug('YAUploadBefavior::_updateRecord() $model->FileModel->id=' . print_r($model->FileModel->id, true));
//		} else {
//			CakeLog::debug('YAUploadBefavior::_updateRecord() $model->FileModel=' . print_r('none', true));
//		}
//		CakeLog::debug('YAUploadBefavior::_updateRecord() $this->settings=' . print_r($this->settings, true));
//		CakeLog::debug('YAUploadBefavior::_updateRecord() $this->runtime=' . print_r($this->runtime, true));
//		CakeLog::debug('YAUploadBefavior::_updateRecord() $model->alias=' . print_r($model->alias, true));
//		CakeLog::debug('YAUploadBefavior::_updateRecord() id=' . print_r($model->id, true));

//		$db = $model->getDataSource();

//		$fields = array_keys($this->settings[$model->alias]);
//		foreach ($fields as $field) {
//			if (isset($model->data[$field]['File']['path']) && $model->data[$field]['File']['path'] !== '') {
//				$data = array(
//					'path' => $db->value($model->data[$field]['File']['path'] . $model->id . '{DS}', 'string')
//				);
//				$model->FileModel->updateAll($data, array(
//					$model->FileModel->alias . '.' . $model->FileModel->primaryKey => (int)$model->data[$model->alias][$field]
//				));
//			}
//		}
	}

/**
 * Before save method. Called before all saves
 *
 * Handles setup of file uploads
 *
 * @param Model $model Model instance
 * @param array $options Options passed from Model::save().
 * @return bool
 */
	public function beforeSave(Model $model, $options = array()) {
		CakeLog::debug('YAUploadBefavior::beforeSave()');
		//CakeLog::debug('YAUploadBefavior::beforeSave() $options=' . print_r($options, true));

		$fields = array_keys($this->settings[$model->alias]);
		foreach ($fields as $field) {
			CakeLog::debug('YAUploadBefavior::beforeSave() $field=' . print_r($field, true));
			if (isset($model->data[$field]['File']['path']) && $model->data[$field]['File']['path'] !== '') {
				$newPath = $this->__realPath($model->data[$field]['File']['path']);
				//CakeLog::debug('YAUploadBefavior::beforeSave() $newPath=' . print_r($newPath, true));

				$this->uploadSettings($model, $field, 'path', $newPath);
				$this->uploadSettings($model, $field, 'thumbnailPath', $newPath);
			}
		}

		//CakeLog::debug('YAUploadBefavior::beforeSave() $this->settings=' . print_r($this->settings, true));
		return parent::beforeSave($model, $options);
	}

/**
 * After find callback. Can be used to modify any results returned by find.
 *
 * @param Model $model Model using this behavior
 * @param mixed $results The results of the find operation
 * @param bool $primary Whether this model is being queried directly (vs. being queried as an association)
 * @return mixed An array value will replace the value of $results - any other value will be ignored.
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
	public function afterFind(Model $model, $results, $primary = false) {
		CakeLog::debug('YAUploadBefavior::afterFind()');

		foreach ($results as $key => &$row) {
			if (! isset($row['File']['path'])) {
				continue;
			}

			//物理パスの設定
			$results[$key]['File']['path'] = $this->__realPath($row['File']['path'] . $row[$model->alias]['id'] . '{DS}');

			//URLの設定
			$url = $this->fileBaseUrl . $results[$key]['File']['slug'];
			$results[$key]['File']['url'] =
					$url . '.' . $results[$key]['File']['extension'];

			$types = array_keys($this->thumbnailSizes);
			foreach ($types as $type) {
				$filePath = $results[$key]['File']['path'] .
							$results[$key]['File']['original_name'] . '_' . $type;

				if (file_exists($filePath . '.' . $results[$key]['File']['extension'])) {
					$results[$key]['File']['url_' . $type] =
							$url . '_' . $type . '.' . $results[$key]['File']['extension'];

				} elseif (file_exists($filePath . '.png')) {
					$results[$key]['File']['url_' . $type] =
							$url . '_' . $type . '.png';
				}
			}
		}

		//CakeLog::debug('YAUploadBefavior::afterFind() $results=' . print_r($results, true));
		return $results;
	}

/**
 * __realPath
 *
 * @param string $path path
 * @return string Real path
 */
	private function __realPath($path) {
		$replacements = array(
			'{ROOT}'	=> $this->rootDir,
			'{DS}'		=> DS,
		);

		$path = Folder::slashTerm(
			str_replace(
				array_keys($replacements),
				array_values($replacements),
				$path
			)
		);

		return $path;
	}

}
