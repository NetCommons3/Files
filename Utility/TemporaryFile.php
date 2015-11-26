<?php
/**
 * Created by PhpStorm.
 * User: ryuji
 * Date: 2015/11/26
 * Time: 16:44
 */
App::uses('TemporaryFolder', 'Files.Utility');
class TemporaryFile extends File{
	protected $_tmpFolder;
	public function __construct($folderPath = null) {
		if($folderPath === null){
			$this->_tmpFolder = new TemporaryFolder();
			$folderPath = $this->_tmpFolder->path;
		}
		$fileName = Security::hash(mt_rand() . microtime(), 'md5');
		parent::__construct($folderPath . DS . $fileName, true);
	}
}
