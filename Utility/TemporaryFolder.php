<?php
/**
 * Created by PhpStorm.
 * User: ryuji
 * Date: 2015/11/19
 * Time: 14:15
 */

App::uses('Folder', 'Utility');

/**
 * Class TemporaryFolder
 *
 * 使い方
 * ```
 * $tempFolder = new TemporaryFolder();
 * $tempFolder->path パス取得
 * // あれこれ
 * $tempFolder->delete(); // 不要になったらdelete
 * ```
 */
class TemporaryFolder extends Folder {

/**
 * TemporaryFolder constructor.
 */
	public function __construct() {
		$path = TMP;
		$path .= Security::hash(mt_rand() . microtime(), 'md5');

		$mode = '0775'; // ε(　　　　 v ﾟωﾟ)　＜パーミッションいくつが適切だ？

		parent::__construct($path, true, $mode);
	}

/**
 * デストラクタ
 */
	public function __destruct() {
		$this->delete();
	}
}