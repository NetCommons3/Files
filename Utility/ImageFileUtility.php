<?php
/**
 * ImageFileUtility.php
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

/**
 * Class ImageFileUtility
 */
class ImageFileUtility {

/**
 * MimeTypeをみてimageか判定する
 *
 * @param string $file ファイルパス
 * @return bool
 */
	public static function isImageByFilePath($file) {
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mimeType = finfo_file($finfo, $file);
		finfo_close($finfo);
		$result = (substr($mimeType, 0, 5) === 'image');
		return $result;
	}

}
