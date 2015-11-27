<?php
/**
 * CsvFileReader
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

// ε(　　　　 v ﾟωﾟ)　＜ 改行コードCR（Excel for Mac）への対応
// ε(　　　　 v ﾟωﾟ)　＜最後に空行までよみこまれちゃうことをへの対処

App::uses('NetCommonsFile', 'Files.Utility');

/**
 * Class CsvFileReader
 */
class CsvFileReader extends SplFileObject {

/**
 * @var File 文字コード変換したテンポラリファイル
 */
	protected $_tmpFile;

/**
 * CsvFileReader constructor.
 *
 * @param string|File $filePath CSVファイルのFileインスタンスかファイルパス
 */
	public function __construct($filePath) {
		if (is_a($filePath, 'File')) {
			$filePath = $filePath->path;
		}
		$tmp = NetCommonsFile::getTemporaryFileConvertSjisWin2Utf8($filePath);
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
		if ($var === array(null)) {
			return false;
		}
		return true;
	}
}

