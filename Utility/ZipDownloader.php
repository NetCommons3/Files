<?php
/**
 * NetCommonsZip
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

App::uses('File', 'Utility');
App::uses('TemporaryFolder', 'Files.Utility');
App::uses('TemporaryFile', 'Files.Utility');
App::uses('NetCommonsFile', 'Files.Utility');

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
 * @var string Zipコマンド
 */
	protected $_zipCommand = 'zip';

/**
 * @var string クライアントOSの文字コード
 */
	protected $_clientOsEncoding;

/**
 * ZipDownloader constructor.
 */
	public function __construct() {
		$this->_tmpFolder = new TemporaryFolder();
		$this->_open = true;

		$userAgent = Hash::get($_SERVER, 'HTTP_USER_AGENT');
		if (stristr($userAgent, 'Mac')) {
			// Macの場合
			$this->_clientOsEncoding = 'UTF-8';
		} elseif (stristr($userAgent, 'Windows')) {
			// Windowsの場合
			$this->_clientOsEncoding = 'SJIS-win';
		} else {
			$this->_clientOsEncoding = Configure::read('App.encoding');
		}
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
		$zipSaveFolder = new TemporaryFolder();
		$this->path = $zipSaveFolder->path . DS . Security::hash(mt_rand() . microtime(), 'md5') . '.zip';

		if (strlen($this->_password)) {
			// パスワードを使う
			$cmd = $this->_zipCommand;
			$execCmd = sprintf(
				'%s -r -e -P %s %s %s',
				$cmd,
				escapeshellarg($this->_password),
				escapeshellarg($this->path),
				'*'
			);

			chdir($this->_tmpFolder->path);
			// コマンドを実行する
			//exec(($execCmd));
			exec($execCmd, $output, $returnVar);
			//CakeLog::debug($execCmd);
			if ($returnVar > 0) {
				CakeLog::debug(' Error:output=' . json_encode($output) . ', return_var=' . $returnVar);
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
					$zip->addEmptyDir($this->_convertFilename($relativePath));
				}
			}
			foreach ($files as $file) {
				$relativePath = str_replace($this->_tmpFolder->path . DS, '', $file);
				$zip->addFile($relativePath, $this->_convertFilename($relativePath));
			}
			$zip->close();
		}

		$this->_open = false;
	}

/**
 * ファイル追加
 *
 * @param string $filePath 追加するファイルのパス
 * @param string|null $localName  ZIPに追加するときのファイル名
 *
 * @return void
 * @throws InternalErrorException
 */
	public function addFile($filePath, $localName = null) {
		// ファイルをlocalNameにしてコピー
		if ($localName === null) {
			$localName = NetCommonsFile::basename($filePath);
		}
		$destPath = $this->_tmpFolder->path . DS . $localName;
		$result = copy($filePath, $this->_convertFilename($destPath));
		if (!$result) {
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
		if (!$folder->copy($this->_tmpFolder->path . DS . NetCommonsFile::basename($folder->path))) {
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

		// 全角文字が含まれたファイル名が指定されている場合
		// encodeしてあげる
		if (strlen($filename) != mb_strlen($filename)) {
			$filename = rawurlencode($filename);
		}
		$response = new CakeResponse();
		$response->type('application/zip');

		$content = 'attachment;';
		$content .= 'filename*=UTF-8\'\'' . $filename;
		$response->header('Content-Disposition', $content);

		$response->file($this->path);
		return $response;
	}

/**
 * OSのファイルシステムにあわせて文字コード変換を行う
 *
 * @param string $name ファイル名
 * @return string
 */
	protected function _convertFilename($name) {
		// Mac上でNC3をつかってるケースの対策
		// Macファイルシステムでは濁点文字が2つの文字になるNFDなのをNFCに変換する
		if (class_exists('Normalizer')) {
			if (Normalizer::isNormalized($name, Normalizer::FORM_D)) {
				$name = Normalizer::normalize($name, Normalizer::FORM_C);
			}
		}
		$name = mb_convert_encoding($name, $this->_clientOsEncoding, 'auto');
		return $name;
	}
}
