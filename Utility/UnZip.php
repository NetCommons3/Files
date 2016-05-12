<?php
/**
 * UnZip
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

App::uses('TemporaryFolder', 'Files.Utility');

/**
 * UnZip
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */
class UnZip {

/**
 * @var string|null password
 */
	protected $_password = null;

/**
 * @var string zip file path
 */
	protected $_zipPath;

/**
 * @var string 解凍先フォルダパス
 */
	public $path;

/**
 * UnZip constructor.
 *
 * @param string $zipFilePath 解凍するZIPファイルパス
 */
	public function __construct($zipFilePath) {
		$this->_zipPath = $zipFilePath;
	}

/**
 * set password
 *
 * @param string $password ZIPの解凍に使うパスワード
 *
 * @return void
 */
	public function setPassword($password) {
		$this->_password = $password;
	}

/**
 * 解凍実行
 *
 * @return false|TemporaryFolder
 */
	public function extract() {
		$tmpFolder = new TemporaryFolder();
		$result = $this->_extractTo($tmpFolder->path);
		if ($result) {
			$this->path = $tmpFolder->path;
			return $tmpFolder;
		} else {
			return false;
		}
	}

/**
 * 解凍
 *
 * @param string $path 解凍先
 * @codeCoverageIgnore
 * @return bool
 */
	protected function _extractTo($path) {
		if (version_compare(PHP_VERSION, '5.6.0', '<') && $this->_password) {
			// コマンドで解凍
			$cmd = sprintf('unzip -P %s %s -d %s', ($this->_password), $this->_zipPath, $path);
			exec($cmd, $output, $returnVar);
			if ($returnVar > 0) {
				// エラー
				CakeLog::warning('Unzip Error:output=' . json_encode($output) . ', return_var=' . $returnVar);
				return false;
			}
			return true;
		} else {
			$zip = new ZipArchive();
			$result = $zip->open($this->_zipPath);
			if ($result !== true) {
				return false;
			}
			if ($this->_password) {
				$zip->setPassword($this->_password);
			}
			return $zip->extractTo($path);
		}
	}
}