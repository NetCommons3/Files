<?php
/**
 * UploadFileDesableThumbnailBehavior
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

/**
 * Class UploadFileDesableThumbnailBehavior
 */
class UploadFileDesableThumbnailBehavior extends ModelBehavior {

/**
 * beforeSave is called before a model is saved. Returning false from a beforeSave callback
 * will abort the save operation.
 *
 * @param Model $model Model using this behavior
 * @param array $options Options passed from Model::save().
 * @return mixed False if the operation should abort. Any other result will continue.
 * @see Model::save()
 */
	public function beforeSave(Model $model, $options = array()) {
		// 画像以外だったらサムネイルを生成させない
		$makeThumbnails = $this->_isImageFile(
			$model->data['UploadFile']['real_file_name']['tmp_name']
		);
		$model->uploadSettings('real_file_name', 'thumbnails', $makeThumbnails);

		return true;
	}

/**
 * MimeTypeをみてimageか判定する
 *
 * @param string $file ファイルへのパス
 * @return bool
 */
	protected function _isImageFile($file) {
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mimeType = finfo_file($finfo, $file);
		finfo_close($finfo);
		$result = (substr($mimeType, 0, 5) === 'image');
		return $result;
	}

}
