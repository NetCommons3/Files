<?php
/**
 * TemporaryUploadFileTesting
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

App::uses('TemporaryUploadFile', 'Files.Utility');

/**
 * Class TemporaryUploadFileTesting
 */
class TemporaryUploadFileTesting extends TemporaryUploadFile {

/**
 * _move
 *
 * @param string $path 移動元パス
 * @param string $destPath 移動先パス
 * @return bool
 */
	protected function _moveFile($path, $destPath) {
		return rename($path, $destPath);
	}
}
