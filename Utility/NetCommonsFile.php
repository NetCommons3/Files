<?php
/**
 * NetCommonsFile
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

App::uses('TemporaryFile', 'Files.Utility');

/**
 * Class NetCommonsFile
 */
class NetCommonsFile {

/**
 * 指定されたファイルの文字コードをSjis-winからUTF-8に変換したテンポラリファイルを作成して返す
 *
 * @param string $filePath ファイルパス
 * @return TemporaryFile 文字コードをUTF8に変換したテンポラリファイル
 */
	public static function getTemporaryFileConvertSjisWin2Utf8($filePath) {
		$tmp = new TemporaryFile();
		$fp = fopen($filePath, 'r');
		while (($line = fgets($fp)) !== false) {
			$line = mb_convert_encoding($line, 'UTF-8', 'sjis-win');
			$tmp->append($line);
		}
		return $tmp;
	}

	public static function basename($path) {
		$slashPath = str_replace(DS, '/', $path);
		$separatePath = explode('/', $slashPath);
		$basename = array_pop($separatePath);
		return $basename;
	}
}