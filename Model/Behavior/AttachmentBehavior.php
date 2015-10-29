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


	protected $_settings = array();

/**
 * @var UploadFile $UploadFile UploadFile
 */
	public $UploadFile = null;

/**
 * SetUp Upload behavior
 *
 * @param object $model instance of model
 * @param array $config array of configuration settings.
 * @return void
 */
	public function setup(Model $model, $config = array()) {
		$this->_settings[$model->alias]['fileFields'] = $config;

		$this->UploadFile = ClassRegistry::init('Files.UploadFile');
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
		foreach($this->_settings[$model->alias]['fileFields'] as $fieldName){
			$fileData = $model->data[$model->alias][$fieldName] ;
			$uploadFile['UploadFile']['original_name'] = $fileData; // TODO 元ファイル名を保存するフィールドを指定する
			$this->UploadFile->save($uploadFile);
		}
		return true;
	}

/**
 * afterSave
 *
 * @param boolean $created 新規のときtrue
 */
	public function afterSave(Model $mode, $created, $options = array())
	{
		// TODO content_keyを UploadFileModelへ書き込む beforeではビヘイビア実行順によってはkeyが取得できない可能性があるのでafterで処理
		// TODO 関連テーブルのinsert


	}

}
