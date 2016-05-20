<?php
/**
 * UploadFileValidateBehavior
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

/**
 * Class UploadFileValidateBehavior
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