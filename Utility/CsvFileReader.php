<?php
/**
 * Created by PhpStorm.
 * User: ryuji
 * Date: 2015/11/26
 * Time: 14:37
 */

// ε(　　　　 v ﾟωﾟ)　＜ 改行コードCR（Excel for Mac）への対応
// ε(　　　　 v ﾟωﾟ)　＜最後に空行までよみこまれちゃうことをへの対処

App::uses('NetCommonsFile', 'Files.Utility');

class CsvFileReader extends SplFileObject{

	protected $_tmpFile;

	public function __construct($filePath) {
		if (is_a($filePath, 'File')){
			$filePath = $filePath->path;
		}
		$tmp = NetCommonsFile::convertSjisWin2Utf8($filePath);
		$path = $tmp->path;
		parent::__construct($path);
		$this->setFlags(SplFileObject::READ_CSV);
	}

	/**
	 * valid SplFileObjectでCSVフィルを読ませると最終行の空配列で返るのでそれの抑止
	 *
	 * @return bool
	 */
	public function valid() {
		$var = parent::current();
		if($var === array(null)){
			return false;
		}
		return true;
	}
}

