<?php
/**
 * TemporaryUploadFile
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

App::uses('File', 'Utility');
App::uses('TemporaryFolder', 'Files.Utility');

/**
 * Class TemporaryUploadFile
 *
 * 作業用テンポラリフォルダをつくって自動的にその中にアップロードファイルを配置するクラス
 * プロセス終了時にテンポラリフォルダごと自動的に削除される。
 *
 * @see FileUploadComponent FileUploadComponent::getTemporaryUploadFile()で利用される
 *
 * # 利用例
 * $file = new TemporaryUploadFile($_FILE['import_csv']);
 * echo $file->path; // ファイルのフルパス
 * echo $file->error; // アップロード時のエラー情報
 */
class TemporaryUploadFile extends File {

/**
 * @var int アップロード時のエラー情報
 */
	public $error;

/**
 * @var TemporaryFolder ファイルの配置されるテンポラリフォルダのインスタンス
 */
	public $temporaryFolder;

/**
 * TemporaryUploadFile constructor.
 *
 * アップロードファイルは、自動的に作成されたテンポラリフォルダに配置される。
 * インスタンス破棄時にテンポラリフォルダ毎ファイルも削除される
 * ファイル名は自動的にハッシュしたものに書き換わる。
 *
 * @param array $file アップロードファイルの配列
 */
	public function __construct($file) {
		$this->temporaryFolder = new TemporaryFolder();
		$path = $file['tmp_name'];
		$destFileName = Security::hash(mt_rand() . microtime(), 'md5') . '.' . pathinfo(
				$file['name'],
				PATHINFO_EXTENSION
			);

		move_uploaded_file($path, $this->temporaryFolder->path . '/' . $destFileName);
		$this->error = $file['error'];
		parent::__construct($this->temporaryFolder->path . '/' . $destFileName);
	}
}