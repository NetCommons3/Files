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
		if (! $this->runtime) {
			return;
		}

		$db = $model->getDataSource();

		$fields = array_keys($this->runtime[$model->alias]);
		foreach ($fields as $field) {
			if (isset($model->data[$field][$model->FileModel->alias]['path']) && $model->data[$field][$model->FileModel->alias]['path'] !== '') {
				$path = $model->data[$field][$model->FileModel->alias]['path'] . $model->id . '{DS}';
				$model->FileModel->updateAll(
					array(
						'path' => $db->value($path, 'string')
					),
					array(
						$model->FileModel->primaryKey => (int)$model->data[$field][$model->FileModel->alias]['id']
					)
				);
			}
		}
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
		$fields = array_keys($this->settings[$model->alias]);
		foreach ($fields as $field) {
			if (isset($model->data[$field]['File']['path']) && $model->data[$field]['File']['path'] !== '') {
				$newPath = $this->__realPath($model->data[$field]['File']['path']);
				$this->uploadSettings($model, $field, 'path', $newPath);
				$this->uploadSettings($model, $field, 'thumbnailPath', $newPath);
			}
		}
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
		foreach ($results as $key => &$row) {
			if (! isset($row['File']['path'])) {
				continue;
			}

			//物理パスの設定
			$results[$key]['File']['path'] = $this->__realPath($row['File']['path']);

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
