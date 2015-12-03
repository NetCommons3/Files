<?php
/**
 * Created by PhpStorm.
 * User: ryuji
 * Date: 2015/12/03
 * Time: 14:34
 */

App::uses('TemporaryFile', 'Files.Utility');

class CsvFileWriter extends TemporaryFile {

	protected $_fields = null;

	/**
	 * @var SplFileObject
	 */
	protected $_splFileObject;

	protected $_options = array();

	/**
	 * CsvFileWriter constructor.
	 *
	 * @param null $folderPath
	 * @param array $options header => falseでヘッダ行無し。デフォルトtrue. header_name => ['title' => 'タイトル'] //連想配列でCSVデータを追加するときのキーのヘッダ名
	 */
	public function __construct($folderPath = null, $options = array()) {
		parent::__construct($folderPath);
		$this->_splFileObject = new SplFileObject($this->path, 'w');
		$this->_options = $options;
	}

	public function put($data) {
		if($this->_fields === null){
			$this->_setupFields($data);
		}
		//$flat = Hash::flatten($data);
		$line = array();
		foreach($this->_fields as $field){
			$line[] = Hash::get($data, $field, '');
		}
		mb_convert_variables('SJIS-win', 'UTF-8', $line); // ε(　　　　 v ﾟωﾟ)　＜ SJIS-win決め打ちなのをなんとかしたいか
		$this->_splFileObject->fputcsv($line);
	}

	public function _setupFields($data) {
		$flat = Hash::flatten($data);
		$this->_fields = array_keys($flat);

		// ヘッダ無しオプションへの対応
		if(Hash::get($this->_options, 'header', true)){
			if(Hash::get($this->_options, 'header_name', false)){
				// ヘッダ名定義あり
				$headerName = Hash::get($this->_options, 'header_name');
				mb_convert_variables('SJIS-win', 'UTF-8', $headerName);
				$header = array();
				foreach($this->_fields as $field){
					$header[] = isset($headerName[$field]) ? $headerName[$field] : $field;
					//$header = hash::get($headerName, $field, $field);
				}
				$this->_splFileObject->fputcsv($header);

			}else{
				$this->_splFileObject->fputcsv($this->_fields);
			}
		}
	}
}