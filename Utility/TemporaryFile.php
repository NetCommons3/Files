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

		register_shutdown_function(array($this, 'delete'));

		parent::__construct($folderPath . DS . $fileName, true);
	}
}
