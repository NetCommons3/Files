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
class TemporaryFolder extends Folder {

/**
 * @var string[] このクラスで作成されたテンポラリフォルダリスト
 */
	private static $__folderPaths = [];

/**
 * @var bool register_shutdown_functionに登録済みか
 */
	private static $__isRegisteredShutdownFunction = false;

/**
 * TemporaryFolder constructor.
 */
	public function __construct() {
		$path = TMP;
		$path .= Security::hash(mt_rand() . microtime(), 'md5');
		//$mode = '0775'; // ε(　　　　 v ﾟωﾟ)　＜パーミッションいくつが適切だ？
		$mode = false; // とりあえずデフォルトのまま
		self::$__folderPaths[] = $path;
		if (!self::$__isRegisteredShutdownFunction) {
			register_shutdown_function([TemporaryFolder::CLASS, 'deleteAll']);
			self::$__isRegisteredShutdownFunction = true;
		}
		parent::__construct($path, true, $mode);
	}

/**
 * 削除
 *
 * @param string|null $path 削除対象パス
 * @return bool
 */
	public function delete($path = null) {
		$path = $path ?? $this->path;
		if ($path) {
			$key = array_search($path, self::$__folderPaths);
			if ($key !== false) {
				unset(self::$__folderPaths[$key]);
			}
		}
		return parent::delete($path);
	}

/**
 * 全テンポラリフォルダの削除
 *
 * @return void
 */
	public static function deleteAll() {
		$folder = new Folder();
		foreach (self::$__folderPaths as $path) {
			$folder->delete($path);
		}
		self::$__folderPaths = [];
	}
}
