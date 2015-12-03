<?php
/**
 * Created by PhpStorm.
 * User: ryuji
 * Date: 2015/12/03
 * Time: 14:34
 */

App::uses('TemporaryFile', 'Files.Utility');


class CsvFileWriter extends TemporaryFile{

	public function __construct($options = array()) {
		$folderPath = Hash::get($options, 'folder', null);
		parent::__construct($folderPath);
		$this->_splFileObject = new SplFileObject($this->path, 'w');
		$this->_options = $options;
		if(Hash::get($this->_options, 'header', false)){
			// TODO headerオプションが指定されてたらヘッダ出力
			$this->add($this->_options['header']);
		}
	}

	public function add(array $line) {
		mb_convert_variables('SJIS-win', 'UTF-8', $line); // ε(　　　　 v ﾟωﾟ)　＜ SJIS-win決め打ちなのをなんとかしたいか
		$this->_splFileObject->fputcsv($line);
	}

	public function addModelData(array $data) {
		$header = Hash::get($this->_options, 'header', false);
		if($header){
			// TODO ヘッダ指定されてれば指定されたカラムだけ出力
			$fields = array_keys($header);
			$line = array();
			foreach($fields as $field){
				$line[] = Hash::get($data, $field, '');
			}
			$this->add($line);
		}else{
			// TODO ヘッダ未指定なら全カラム出力
			$line = Hash::flatten($data);
			$this->add($line);
		}
	}


	public function download($filename) {
		$response = new CakeResponse();
		$response->type('text/csv');
		$response->file($this->path, ['name' => $filename, 'download' => 'true']);
		return $response;
	}
}
