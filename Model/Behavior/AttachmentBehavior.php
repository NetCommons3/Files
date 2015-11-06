<?php
/**
 * AttachmentBehavior
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */
// TODO UploadBehaviorを継承した方が使いやすいのか要検討
/**
 * Class AttachmentBehavior
 */
class AttachmentBehavior extends ModelBehavior {
//class AttachmentBehavior extends UploadBehavior {


	protected $_settings = array();

/**
 * @var UploadFile $UploadFile UploadFile
 */
	public $UploadFile = null;

	public $UploadFilesContent = null;

	protected $_uploadedFiles = array();

/**
 * SetUp Upload behavior
 *
 * @param object $model instance of model
 * @param array $config array of configuration settings.
 * @throws CakeException 先にOriginalKeyが登録されてないと例外
 * @return void
 */
	public function setup(Model $model, $config = array()) {
		// 先にOriginalKeyをロードしてもらう
		if (! $model->Behaviors->loaded('NetCommons.OriginalKey')) {
			$error = '"NetCommons.OriginalKeyBehavior" not loaded in ' . $model->alias . '. ';
			$error .= 'Load "NetCommons.OriginalKeyBehavior" before loading "AttachmentBehavior"';
			throw new CakeException($error);
		};

		$this->_settings[$model->alias]['fileFields'] = $config;
		$this->UploadFile = ClassRegistry::init('Files.UploadFile');
		$this->UploadFilesContent = ClassRegistry::init('Files.UploadFilesContent');
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
		foreach ($results as $key => $content) {
			if(isset($content[$model->alias]['id'])){
				$contentId = $content[$model->alias]['id'];
				$conditions = [
						'UploadFilesContent.plugin_key' => Inflector::underscore($model->plugin),
						'UploadFilesContent.content_id' => $contentId,
				];
				$uploadFiles = $this->UploadFilesContent->find('all', ['conditions' => $conditions]);
				foreach ($uploadFiles as $uploadFile) {
					$results[$key]['UploadFile'][] = $uploadFile['UploadFile'];
				}
			}
		}
		return $results;
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
		foreach ($this->_settings[$model->alias]['fileFields'] as $fieldName) {

			$fileData = $model->data[$model->alias][$fieldName];
			if ($fileData['name']) {
				$uploadFile = $this->UploadFile->create();
				$pathInfo = pathinfo($fileData['name']);
				$uploadFile['UploadFile']['plugin_key'] = Inflector::underscore($model->plugin);
				$uploadFile['UploadFile']['content_key'] = $model->data[$model->alias]['key'];
				$uploadFile['UploadFile']['field_name'] = $fieldName;
				$uploadFile['UploadFile']['original_name'] = $fileData['name'];
				$uploadFile['UploadFile']['extension'] = $pathInfo['extension'];
				$uploadFile['UploadFile']['real_file_name'] = $fileData;

				// TODO 例外処理

				$this->_uploadedFiles[$fieldName] = $this->UploadFile->save($uploadFile);
			}
		}

		return true;
	}

/**
 * afterSave
 *
 * @param Model $model
 * @param bool $created
 * @param array $options
 * @throws Exception
 */
	public function afterSave(Model $model, $created, $options = array())
	{
		// アップロードがなかったら以前のデータを挿入する
		// formからhiddenで UploadFile.0.id 形式でデータが渡ってくる
		if (isset($model->data['UploadFile'])) {
			foreach($model->data['UploadFile'] as $uploadFile){
				// 同じfield_nameでアップロードされてるなら以前のファイルへの関連レコードは不要
				if(isset($this->_uploadedFiles[$uploadFile['field_name']])){
					// 新たにアップロードされてる
				} else {
					// 同じfield_nameでアップロードされてなければ以前のファイルへの関連レコードを入れる
					$uploadFileId = $uploadFile['id'];
					$this->_saveUploadFilesContent($model, $uploadFileId);
				}
			}
		}
		// 関連テーブルの挿入
		foreach ($this->_uploadedFiles as $uploadedFile) {
			$uploadFileId = $uploadedFile['UploadFile']['id'];
			$this->_saveUploadFilesContent($model, $uploadFileId);
		}
	}

	/**
	 * @param Model $model
	 * @param $uploadFileId
	 * @return array
	 */
	protected function _saveUploadFilesContent(Model $model, $uploadFileId) {
		$contentId = $model->data[$model->alias]['id'];
		$data = [
				'content_id' => $contentId,
				'upload_file_id' => $uploadFileId,
				'plugin_key' => Inflector::underscore($model->plugin),
		];
		$data = $this->UploadFilesContent->create($data);
		CakeLog::debug(var_export($data, true));
		// TODO 例外処理
		$this->UploadFilesContent->save($data);
		return array($contentId, $data);
	}
}
