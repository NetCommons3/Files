<?php
/**
 * DownloadCountUpBehavior.php
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

/**
 * Class DownloadCountUpBehavior
 */
final class DownloadCountUpBehavior extends ModelBehavior {

/**
 * @var UploadFile UploadFile model
 */
	private $__uploadFile;

/**
 * setup
 *
 * @param Model $model model
 * @param array $config config
 * @return void
 */
	public function setup(Model $model, $config = array()) {
		$this->__uploadFile = ClassRegistry::init('Files.UploadFile');

		parent::setup($model, $config);
	}

/**
 * ダウンロードカウントアップ
 *
 * @param Model $model 元モデル
 * @param array $data UploadFile Model Data
 * @param string $fieldName アップロードファイルフィールド名
 * @return void
 */
	public function downloadCountUp(Model $model, $data, $fieldName) {
		$uploadFile = [
			'UploadFile' => $data['UploadFile'][$fieldName]
		];
		$this->__uploadFile->countUp($uploadFile);
	}
}