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
App::uses('TemporaryFolder2', 'Files.Utility');
App::uses('Security', 'Utility');

/**
 * Class TemporaryFile
 */
class TemporaryFile2 extends File {

	private static $filePaths = [];
	private static $isRegisteredShutdownFunction = false;

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
			$this->_tmpFolder = new TemporaryFolder2();
			$folderPath = $this->_tmpFolder->path;
		}
		$fileName = Security::hash(mt_rand() . microtime(), 'md5');

		$path = $folderPath . DS . $fileName;

		self::$filePaths[] = $path;
		if (!self::$isRegisteredShutdownFunction) {
			register_shutdown_function([TemporaryFile2::class, 'deleteAll']);
			self::$isRegisteredShutdownFunction = true;
		}
		parent::__construct($path, true);
	}
	public function delete() {
		$key = array_search($this->path, self::$filePaths);
		if ($key !== false) {
			unset(self::$filePaths[$key]);
			if ($this->_tmpFolder instanceof TemporaryFolder2) {
				$this->_tmpFolder->delete();
			}
		}
		parent::delete();
	}

	public static function deleteAll() {
		foreach (self::$filePaths as $path) {
			$file = new File($path);
			$file->delete();
		}
		self::$filePaths = [];
	}
}
