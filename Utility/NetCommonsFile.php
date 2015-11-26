<?php
/**
 * Created by PhpStorm.
 * User: ryuji
 * Date: 2015/11/26
 * Time: 16:44
 */
App::uses('TemporaryFile', 'Files.Utility');
class NetCommonsFile {
	public static function convertSjisWin2Utf8($filePath) {
		$tmp = new TemporaryFile();
		$fp = fopen($filePath, 'r');
		while(($line = fgets($fp)) !== false){
			$line = mb_convert_encoding($line, 'UTF-8', 'sjis-win');
			$tmp->append($line);
		}
		return $tmp;
	}

	public static function getTemporaryFileConvertSjisWin2Utf8($filePath) {
		
	}
}