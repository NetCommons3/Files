<?php
/**
 * NetCommonsZip
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */


App::uses('TemporaryFolder', 'Files.Utility');
App::uses('TemporaryFile', 'Files.Utility');

/**
 * Class NetCommonsZip
 * 基本的な利用シーンはZipしてダウンロードするテンポラリな使い方
 */
class ZipDownloader {

/**
 * @var TemporaryFolder 作業用フォルダ
 */
	protected $_tmpFolder;

/**
 * @var string|null password
 */
	protected $_password = null;

/**
 * @var string zip filepath
 */
	public $path;

/**
 * @var bool ファイルオープン常態
 */
	protected $_open = false;

/**
 * ZipDownloader constructor.
 */
	public function __construct() {
		$this->_tmpFolder = new TemporaryFolder();
		$this->_open = true;
		register_shutdown_function(array($this, 'delete'));
	}

/**
 * zipファイルの削除
 *
 * @return void
 */
	public function delete() {
		// zip本体の削除
		unlink($this->path);
	}

/**
 * close
 *
 * zipの作成
 *
 * @return bool
 */
	public function close() {
		// zip作成先
		//$zipSaveFolder = new TemporaryFolder();
		//$this->path = $zipSaveFolder->path . DS . 'download.zip';
		// TODO TemporaryFileでなく作業用フォルダとファイル名だけ欲しい。ファイルはここでは作りたくない
		$zipFile = new TemporaryFile();
		$this->path = $zipFile->path;

		if ($this->_password) {
			// パスワードを使う
			$cmd = 'zip';
			$execCmd = sprintf(
				'%s -r -e -P %s %s %s',
				$cmd,
				escapeshellarg($this->_password),
				escapeshellarg($this->path),
				'*'
			);
			chdir($this->_tmpFolder->path);
			// コマンドを実行する
			exec($execCmd, $output, $returnVar);
			if ($returnVar > 0) {
				CakeLog::warning(' Error:output=' . json_encode($output) . ', return_var=' . $returnVar);
				return false;
			}

		} else {
			// パスワード無しZIP
			// ZipArchiverを使う
			$zip = new ZipArchive();
			$zip->open($this->path, ZipArchive::CREATE);
			chdir($this->_tmpFolder->path);

			list($folders, $files) = $this->_tmpFolder->tree();
			foreach ($folders as $folder) {
				if ($folder !== $this->_tmpFolder->path) {
					$relativePath = str_replace($this->_tmpFolder->path . DS, '', $folder);
					$zip->addEmptyDir($relativePath);
				}
			}
			foreach ($files as $file) {
				$relativePath = str_replace($this->_tmpFolder->path . DS, '', $file);
				$zip->addFile($relativePath);
			}
			$zip->close();
			copy($this->path, TMP . time() . '.zip');
		}

		$this->_open = false;
	}

/**
 * ファイル追加
 *
 * @param string $filePath 追加するファイルのパス
 * @param string|null $localname  ZIPに追加するときのファイル名
 *
 * @return void
 * @throws InternalErrorException
 */
	public function addFile($filePath, $localname = null) {
		// ファイルをlocalnameにしてコピー
		if ($localname === null) {
			$localname = basename($filePath);
		}
		if (!copy($filePath, $this->_tmpFolder->path . DS . $localname)) {
			// 失敗
			throw new InternalErrorException('NetCommonsZip File IO Error');
		}
	}

/**
 * add from string
 *
 * @param string $localname zipファイルに追加するときのファイル名
 * @param string $contents 追加するファイルの中身
 *
 * @return void
 */
	public function addFromString($localname, $contents) {
		// ε(　　　　 v ﾟωﾟ)　＜ 例外処理
		$file = new File($this->_tmpFolder->path . DS . $localname, true);
		$file->write($contents);
		$file->close();
	}

/**
 * add folder
 *
 * @param string $folderPath zipに追加するフォルダのパス
 *
 * @return void
 * @throws InternalErrorException
 */
	public function addFolder($folderPath) {
		$folder = new Folder($folderPath);
		if (!$folder->copy($this->_tmpFolder->path . DS . basename($folder->path))) {
			throw new InternalErrorException('NetCommonsZip File IO Error');
		}
	}

/**
 * set password
 *
 * @param string $password ZIPの解凍・圧縮に使うパスワード
 *
 * @return void
 */
	public function setPassword($password) {
		$this->_password = $password;
	}

/**
 * Download
 *
 * @param string $filename download時のファイル名
 * @return CakeResponse ダウンロードレスポンス
 */
	public function download($filename) {
		// closeされてなかったらcloseする
		if ($this->_open) {
			$this->close();
		}
		$response = new CakeResponse();
		$response->type('application/zip');
		$response->file($this->path, ['name' => $filename, 'download' => 'true']);
		return $response;
	}
}
