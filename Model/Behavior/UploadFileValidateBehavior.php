<?php
/**
 * Created by PhpStorm.
 * User: ryuji
 * Date: 2016/05/20
 * Time: 11:59
 */

class UploadFileValidateBehavior extends ModelBehavior {

/**
 * NetCommons3のセキュリティ設定で許可されている拡張子かチェックする
 *
 * @param Model $model Model
 * @param string $extension 拡張子
 * @return bool
 */
	public function isAllowUploadFileExtension(Model $model, $extension) {
		$allowExtension = $this->getAllowExtension($model);
		return in_array($extension, $allowExtension);
	}

/**
 * NetCommons3のセキュリティ設定で許可されている拡張子のリストを返す
 *
 * @param Model $model Model
 * @return array
 */
	public function getAllowExtension(Model $model) {
		$uploadAllowExtension = explode(',', SiteSettingUtil::read('Upload.allow_extension'));
		$uploadAllowExtension = array_map('trim', $uploadAllowExtension);
		return $uploadAllowExtension;
	}

}