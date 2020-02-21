<?php
/**
 * UploadFileValidateBehavior
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

App::uses('SiteSettingUtil', 'SiteManager.Utility');
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
		return in_array(strtolower($extension), $allowExtension, true);
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

/**
 * ルームのファイルサイズ合計を返す
 * 履歴データは含まない。
 *
 * @param Model $model Model
 * @param int $roomId ルームID
 * @return int 合計ファイルサイズ（Byte)
 */
	public function getTotalSizeByRoomId(Model $model, $roomId) {
		// 単純sumじゃだめ。重複は排除しないといけないのでSQL直書き
		$query = <<< EOF
		SELECT sum(size) AS total_size FROM
			(
				SELECT DISTINCT `UploadFile`.`id`, `UploadFile`.`size`
				FROM `%s` AS `UploadFilesContent`
				LEFT JOIN `%s` AS `UploadFile`
					ON (`UploadFilesContent`.`upload_file_id` = `UploadFile`.`id`)
				WHERE (
					(`UploadFilesContent`.`content_is_active` IN (1, NULL))
					OR
					(`UploadFilesContent`.`content_is_latest` IN (1, NULL))
					) AND `UploadFile`.`room_id` = ?
				GROUP BY `UploadFile`.`id`
			) AS UploadFileSize;
EOF;

		$query = sprintf($query,
			$model->tablePrefix . 'upload_files_contents',
			$model->tablePrefix . 'upload_files');
		$result = $model->query($query, [$roomId]);
		$total = $result[0][0]['total_size'];
		$total = (is_null($total)) ? 0 : $total;
		return $total;
	}

/**
 * NetCommons3のシステム管理→一般設定で許可されているルーム容量内かをチェックするバリデータ
 *
 * @param Model $model Model
 * @param array $check バリデートする値
 * @return bool|string 容量内: true, 容量オーバー: string エラーメッセージ
 */
	public function validateRoomFileSizeLimit(Model $model, $check) {
		$field = $this->_getField($check);

		$roomId = Current::read('Room.id');

		$maxRoomDiskSize = Current::read('Space.room_disk_size');
		if ($maxRoomDiskSize === null) {
			return true;
		}

		// sizeなければuploadされてないのでtrueでぬける
		if (!isset($check[$field]['size'])) {
			return true;
		}

		$size = $check[$field]['size'];

		$roomTotalSize = $this->getTotalSizeByRoomId($model, $roomId);
		if (($roomTotalSize + $size) < $maxRoomDiskSize) {
			return true;
		} else {
			$roomsLanguage = ClassRegistry::init('Room.RoomsLanguage');
			$data = $roomsLanguage->find(
				'first',
				[
					'conditions' => [
						'room_id' => $roomId,
						'language_id' => Current::read('Language.id'),
					]
				]
			);
			$roomName = $data['RoomsLanguage']['name'];
			// ファイルサイズをMBとかkb表示に
			$message = __d(
				'files',
				'Total file size uploaded to the %s, exceeded the limit. The limit is %s(%s left).',
				$roomName,
				CakeNumber::toReadableSize($maxRoomDiskSize),
				CakeNumber::toReadableSize($maxRoomDiskSize - $roomTotalSize)
			);
			return $message;
		}
	}

/**
 * Returns the field to check
 *
 * @param array $check array of validation data
 * @return string
 */
	protected function _getField($check) {
		$fieldKeys = array_keys($check);
		return array_pop($fieldKeys);
	}

}
