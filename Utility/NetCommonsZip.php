<?php
/**
 * NetCommonsZip
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

/*
 * TODO
 * ✓ 仮にメソッド追加
 * ✓ ZipArchiveに名前と引数を合わせる
 *
 * - 生成したZIPを自動的に削除する
 * - CakePHPのFile, Folderを渡せるようにする（必要か？）
 *
  */

class NetCommonsZip {

	/**
	 * @var TemporaryFolder
	 */
	protected $_tmpFolder;
	protected $_mode = 'zip';
	protected $_password = null;

	protected $_zipPath;

	/**
	 * @param $filePath
	 * @param $flags ZipArchive::OVERWRITE or ZipArchive::CREATE or null nullはunzip用
	 */
	public function open($filePath, $create = false) {
		$this->_zipPath = $filePath;
		if($create){
			$this->_tmpFolder = new TemporaryFolder();
		}else{
			// TODO unzip
			$this->_mode = 'unzip';
		}
	}

	/**
	 * close
	 *
	 * zipの作成 unzip時はclose
	 * @return bool
	 */
	public function close(){
		//  zipコマンド発行
		if($this->_mode === 'zip'){
			// zipする
			$cmd = 'zip';
			// 作成対象を相対パスにしないとパス情報がはいっちゃう
			if($this->_password){
				// パスワードを使う
				$execCmd = sprintf('%s -r -e -P %s %s %s', $cmd, escapeshellarg($this->_password), escapeshellarg($this->_zipPath), '*');
			}else{
				// パスワード無しZIP
				$execCmd = sprintf('%s -r %s %s', $cmd, escapeshellarg($this->_zipPath), '*');
			}

			chdir($this->_tmpFolder->path);
			// コマンドを実行する
			exec(($execCmd));

		}else{
			// unzipなら常にtrue;
			return true;
		}
	}

	public function extractTo($path) {
		// TODO 例外処理
		if(version_compare(PHP_VERSION, '5.6.0', '<') && $this->_password){
			// コマンドで解凍
			$cmd = sprintf('unzip -P %s %s -d %s', ($this->_password), $this->_zipPath, $path);
			exec($cmd);
		}else{
			$zip = new ZipArchive();
			$zip->open($this->_zipPath);
			if($this->_password){
				$zip->setPassword($this->_password);
			}
			$zip->extractTo($path);
		}
	}

	public function addFile($filePath, $localname = null) {
		// ファイルをlocalnameにしてコピー
		if($localname === null){
			$localname = basename($filePath);
		}
		if (!copy($filePath, $this->_tmpFolder->path . DS . $localname)){
			// 失敗
			throw new InternalErrorException('NetCommonsZip File IO Error');
		}
	}

	public function addFromString($localname, $contents) {
		// TODO 例外処理
		$file = new File($this->_tmpFolder->path . DS . $localname, true);
		$file->write($contents);
		$file->close();
	}

	public function addFolder($folderPath) {
		$folder = new Folder($folderPath);
		if( !$folder->copy($this->_tmpFolder->path)){
			throw new InternalErrorException('NetCommonsZip File IO Error');
		}
	}

	public function setPassword($password) {
		$this->_password = $password;

	}
}