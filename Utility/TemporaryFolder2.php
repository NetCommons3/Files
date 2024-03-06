<?php
/**
 * Created by PhpStorm.
 * User: ryuji
 * Date: 2015/11/19
 * Time: 14:15
 */

App::uses('Folder', 'Utility');
App::uses('Security', 'Utility');

/**
 * Class TemporaryFolder
 *
 * インタンス破棄で自動的にフォルダを削除するクラス
 *
 * 使い方
 * ```
 * $tempFolder = new TemporaryFolder();
 * $tempFolder->path パス取得
 * // あれこれ
 * $tempFolder->delete(); // 不要になったらdelete
 * ```
 */
class TemporaryFolder2 extends Folder {

	private static $folderPaths = [];
	private static $isRegisteredShutdownFunction = false;

/**
 * TemporaryFolder constructor.
 */
	public function __construct() {
		$path = TMP;
		$path .= Security::hash(mt_rand() . microtime(), 'md5');
		//$mode = '0775'; // ε(　　　　 v ﾟωﾟ)　＜パーミッションいくつが適切だ？
		$mode = false; // とりあえずデフォルトのまま
		self::$folderPaths[] = $path;
		if (!self::$isRegisteredShutdownFunction) {
			register_shutdown_function([TemporaryFolder2::class, 'deleteAll']);
			self::$isRegisteredShutdownFunction = true;
		}
		parent::__construct($path, true, $mode);
	}

	public function delete($path = null) {
		$path = $path ?? $this->path;
		if ($path) {
			$key = array_search($path, self::$folderPaths);
			if ($key !== false) {
				unset(self::$folderPaths[$key]);
			}
		}
		parent::delete($path);
	}

	public static function deleteAll() {
		$folder = new Folder();
		foreach (self::$folderPaths as $path) {
			$folder->delete($path);
		}
		self::$folderPaths = [];
	}
}