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

				$this->_uploadedFiles[] = $this->UploadFile->save($uploadFile);
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
		// アップロードがなかったら？以前の
		// 関連テーブルの挿入
		foreach ($this->_uploadedFiles as $uploadedFile) {
			$contentId = $model->data[$model->alias]['id'];
			$uploadFileId = $uploadedFile['UploadFile']['id'];
			$data = [
				'content_id' => $contentId,
				'upload_file_id' => $uploadFileId,
				'plugin_key' => Inflector::underscore($model->plugin),
			];
			CakeLog::debug(var_export($data, true));
			$data = $this->UploadFilesContent->create($data);
			CakeLog::debug(var_export($data, true));
			// TODO 例外処理
			$this->UploadFilesContent->save($data);
		}
	}
}
