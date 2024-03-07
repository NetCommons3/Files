<?php
/**
 * TemporaryFile
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

App::uses('File', 'Utility');
App::uses('TemporaryFolder', 'Files.Utility');
App::uses('Security', 'Utility');

/**
 * Class TemporaryFile
 */
class TemporaryFile extends File {

/**
 * @var array このクラスで作成されたテンポラリファイルのリスト
 */
	private static $__filePaths = [];

/**
 * @var boola register_shutdown_functionに登録済みか
 */
	private static $__isRegisteredShutdownFunction = false;

/**
 * @var TemporaryFolder テンポラリファイルを配置するテンポラリフォルダ
 */
	protected $_tmpFolder;

/**
 * TemporaryFile constructor.
 *
 * @param string $folderPath テンポラリフォルダを作成するフォルダパス。指定されなければテンポラリフォルダを作成する
 */
	public function __construct($folderPath = null) {
		if ($folderPath === null) {
			$this->_tmpFolder = new TemporaryFolder();
			$folderPath = $this->_tmpFolder->path;
		}
		$fileName = Security::hash(mt_rand() . microtime(), 'md5');

		$path = $folderPath . DS . $fileName;

		self::$__filePaths[] = $path;
		if (!self::$__isRegisteredShutdownFunction) {
			register_shutdown_function([TemporaryFile::CLASS, 'deleteAll']);
			self::$__isRegisteredShutdownFunction = true;
		}
		parent::__construct($path, true);
	}

/**
 * 削除
 * 
 * @return void
 */
	public function delete() {
		$key = array_search($this->path, self::$__filePaths);
		if ($key !== false) {
			unset(self::$__filePaths[$key]);
			if ($this->_tmpFolder instanceof TemporaryFolder) {
				$this->_tmpFolder->delete();
			}
		}
		parent::delete();
	}

/**
 * 全テンポラリファイル削除
 *
 * @return void
 */
	public static function deleteAll() {
		foreach (self::$__filePaths as $path) {
			$file = new File($path);
			$file->delete();
		}
		self::$__filePaths = [];
	}
}
