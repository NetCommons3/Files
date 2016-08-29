<?php
/**
 * CsvFileWriter
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

App::uses('TemporaryFile', 'Files.Utility');
App::uses('ZipDownloader', 'Files.Utility');

/**
 * Class CsvFileWriter
 *
 * ε(　　　　 v ﾟωﾟ)　＜例外処理が…
 */
class CsvFileWriter extends TemporaryFile {

/**
 * CsvFileWriter constructor.
 *
 * @param array $options folder => CSVファイルを生成するフォルダ header => array(key => ヘッダ名) ヘッダ&カラム名
 */
	public function __construct($options = array()) {
		$folderPath = Hash::get($options, 'folder', null);
		parent::__construct($folderPath);
		$this->_options = $options;
		if (Hash::get($this->_options, 'header', false)) {
			// headerオプションが指定されてたらヘッダ出力
			$this->add($this->_options['header']);
		}
	}

/**
 * CSVファイルに追加する行データ
 *
 * @param array $line 配列
 * @return void
 */
	public function add(array $line) {
		$fp = fopen('php://temp', 'w+');
		fputcsv($fp, $line);
		rewind($fp);
		$csvLine = '';
		while (feof($fp) === false) {
			$csvLine .= fgets($fp);
		}
		fclose($fp);
		// ε(　　　　 v ﾟωﾟ)　＜ SJIS-win決め打ちなのをなんとかしたいか
		$convertLine = mb_convert_encoding($csvLine, 'SJIS-win', 'UTF-8');
		$this->append($convertLine);
	}

/**
 * CSVファイルに追加する連想配列データ
 *
 * コンストラクタに$options['header']でカラム名が定義されてればそのカラムだけをCSVに追加する
 *
 * @param array $data モデルの連想配列データ
 * @return void
 */
	public function addModelData(array $data) {
		$header = Hash::get($this->_options, 'header', false);
		if ($header) {
			// ヘッダ指定されてれば指定されたカラムだけ出力
			$fields = array_keys($header);
			$line = array();
			foreach ($fields as $field) {
				$line[] = Hash::get($data, $field, '');
			}
			$this->add($line);
		} else {
			// ヘッダ未指定なら全カラム出力
			$line = Hash::flatten($data);
			$this->add($line);
		}
	}

/**
 * ダウンロード
 *
 * @param string $filename ダウンロード時のファイル名
 * @return CakeResponse
 */
	public function download($filename) {
		$response = new CakeResponse();
		$response->type('text/csv');
		$response->file($this->path, ['name' => $filename, 'download' => 'true']);
		return $response;
	}

/**
 * zip download
 *
 * @param string $zipFilename Zipファイル名
 * @param string $csvFilename ZipされるCsvファイル名
 * @param string|null $password Zipにつけるパスワード
 * @return CakeResponse
 */
	public function zipDownload($zipFilename, $csvFilename, $password = null) {
		// csvファイルを$csvFilenameへリネーム
		$this->_rename($csvFilename);
		// zipFile作成
		$zip = new ZipDownloader();
		$zip->addFile($this->path);
		// zipのダウンロードを実行
		if (strlen($password)) {
			$zip->setPassword($password);
		}
		$zip->close();

		return $zip->download($zipFilename);
	}

/**
 * リネーム
 *
 * @param string $toFilename 変更後のファイル名
 * @return void
 */
	protected function _rename($toFilename) {
		// ε(　　　　 v ﾟωﾟ)　＜ $toFilenameがフルパスかファイル名のみかで処理分ける
		$prohibited = array('\\', '/', ':', '*', '?', '"', '<', '>', '|');
		$toFilename = str_replace($prohibited, '_', $toFilename);
		rename($this->path, dirname($this->path) . DS . $toFilename);
		$this->path = dirname($this->path) . DS . $toFilename;
	}
}
