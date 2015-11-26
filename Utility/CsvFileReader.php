<?php
/**
 * Created by PhpStorm.
 * User: ryuji
 * Date: 2015/11/26
 * Time: 14:37
 */

// ε(　　　　 v ﾟωﾟ)　＜ 改行コードCR（Excel for Mac）への対応
// ε(　　　　 v ﾟωﾟ)　＜最後に空行までよみこまれちゃうことをへの対処

class CsvFileReader extends SplFileObject{

	protected $_tmpFile;

	public function __construct($filePath) {
		if (is_a($filePath, 'File')){
			$filePath = $filePath->path;
		}
		// コンストラクタから別メソッド呼ぶと "The parent constructor was not called: the object is in an invalid state" になるのでここにベタ書き。
		// ε(　　　　 v ﾟωﾟ)　＜一度メモリに全データ読みこむのが気になる。 別解としてはcurrentで文字コード変換かな
		$data = file_get_contents($filePath);
		$data = mb_convert_encoding($data, 'UTF-8', 'sjis-win'); // ε(　　　　 v ﾟωﾟ)　＜コード固定でいいのか？
		$this->_tmpFile = tmpfile();
		$meta = stream_get_meta_data($this->_tmpFile);
		fwrite($this->_tmpFile, $data);
		rewind($this->_tmpFile);
		$path = $meta['uri'];

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

	public function __destruct() {
		fclose($this->_tmpFile);
	}

}