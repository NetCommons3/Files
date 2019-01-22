<?php
/**
 * DownloadComponent
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

App::uses('Component', 'Controller');

/**
 * Class DownloadComponent
 */
class DownloadComponent extends Component {

/**
 * @var Controller コントローラ
 */
	protected $_controller = null;

/**
 * Called before the Controller::beforeFilter().
 *
 * @param Controller $controller Instantiating controller
 * @return void
 */
	public function initialize(Controller $controller) {
		$this->_controller = $controller;
	}

/**
 * ダウンロード実行
 *
 * @param int $contentId コンテンツID
 * @param array $options オプション field : ダウンロードのフィールド名, size: nullならオリジナル thumb, small, medium, big
 * @return CakeResponse|null
 * @throws ForbiddenException
 */
	public function doDownload($contentId, $options = array()) {
		if (isset($options['field'])) {
			$fieldName = $options['field'];
			unset($options['field']);
		} else {
			if (isset($this->_controller->request->params['field_name'])) {
				$fieldName = $this->_controller->request->params['field_name'];
			} elseif (isset($this->_controller->params['pass'][0])) {
				$fieldName = $this->_controller->params['pass'][0];
			} else {
				$fieldName = null;
			}
		}

		if (isset($options['size'])) {
			$size = $options['size'];
			unset($options['size']);
		} else {
			if (isset($this->_controller->request->params['size'])) {
				$size = $this->_controller->request->params['size'];
			} elseif (isset($this->_controller->params['pass'][1])) {
				$size = $this->_controller->params['pass'][1];
			} else {
				$size = null;
			}
		}

		// ファイル情報取得 plugin_keyとコンテンツID、フィールドの情報が必要
		$UploadFile = ClassRegistry::init('Files.UploadFile');
		$pluginKey = Inflector::underscore($this->_controller->plugin);
		$file = $UploadFile->getFile($pluginKey, $contentId, $fieldName);

		return $this->_downloadUploadFile($file, $size, $options);
	}

/**
 * UploadFileのID指定でのダウンロード実行
 *
 * @param int $uploadFileId UploadFile ID
 * @param array $options オプション field : ダウンロードのフィールド名, size: nullならオリジナル thumb, small, medium, big
 * @return CakeResponse|null
 * @throws ForbiddenException
 */
	public function doDownloadByUploadFileId($uploadFileId, $options = array()) {
		if (isset($options['size'])) {
			$size = $options['size'];
			unset($options['size']);
		} else {
			if (isset($this->_controller->request->params['size'])) {
				$size = $this->_controller->request->params['size'];
			} elseif (isset($this->_controller->params['pass'][2])) {
				$size = $this->_controller->params['pass'][2];
			} else {
				$size = null;
			}
		}

		// ファイル情報取得 plugin_keyとコンテンツID、フィールドの情報が必要
		$UploadFile = ClassRegistry::init('Files.UploadFile');

		$file = $UploadFile->findById($uploadFileId);
		if (! $file) {
			//データがない＝リンク切れ。リンク切れの場合、ログアウトしないようにするため、メッセージを追加
			throw new ForbiddenException('Not found file');
		}
		return $this->_downloadUploadFile($file, $size, $options);
	}

/**
 * UploadFileのデータ指定でのダウンロード実行
 *
 * @param array $file UploadFile data
 * @param array $options オプション field : ダウンロードのフィールド名, size: nullならオリジナル thumb, small, medium, big
 * @return CakeResponse|null
 * @throws ForbiddenException
 */
	public function doDownloadByUploadFile($file, $options = []) {
		if (isset($options['size'])) {
			$size = $options['size'];
			unset($options['size']);
		} else {
			if (isset($this->_controller->request->params['size'])) {
				$size = $this->_controller->request->params['size'];
			} else {
				$size = null;
			}
		}

		return $this->_downloadUploadFile($file, $size, $options);
	}

/**
 * ダウンロード処理
 *
 * @param array $file UploadFile data
 * @param string $size サムネイル名
 * @param array $options オプション
 * @return CakeResponse|null
 * @throws ForbiddenException
 * @throws BadRequestException
 * @throws NotFoundException
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 */
	protected function _downloadUploadFile($file, $size, $options) {
		$UploadFile = ClassRegistry::init('Files.UploadFile');

		// ルームチェック
		if ($file['UploadFile']['room_id']) {
			$roomId = Current::read('Room.id');
			if ($file['UploadFile']['room_id'] != $roomId) {
				throw new ForbiddenException('Not found file');
			}
		}
		if ($file['UploadFile']['block_key']) {
			// block_keyによるガード
			$Block = ClassRegistry::init('Blocks.Block');
			$uploadFileBlock = $Block->findByKey(
				$file['UploadFile']['block_key']
			);
			// ブロック見えない & ブロック編集できないのは 403
			if (!$uploadFileBlock ||
				($Block->isVisible($uploadFileBlock) === false &&
					!Current::permission('block_editable'))) {
				throw new ForbiddenException('Not found file');
			}
		}

		// size対応
		$filename = $file['UploadFile']['real_file_name'];
		if ($size) {
			// $size = '../../'とかを排除するため！
			if (strpos($size, '..') !== false) {
				throw new BadRequestException();
			}
			$filename = $size . '_' . $filename;
		}

		$filePath = $UploadFile->uploadBasePath .
				$file['UploadFile']['path'] . $file['UploadFile']['id'] . DS . $filename;

		try {
			$downloadFileName = $file['UploadFile']['original_name'];
			if (! empty($options['name'])) {
				$downloadFileName = $options['name'];
			}
			$content = 'attachment;';
			$content .= 'filename*=UTF-8\'\'' . $downloadFileName;
			$this->_controller->response->header('Content-Disposition', $content);

			// name, downloadが入っているとCake側処理により文字化けが発生する
			unset($options['name']);
			unset($options['download']);

			$this->_controller->response->file(
				$filePath,
				$options
			);
		} catch (NotFoundException $ex) {
			//データがない＝リンク切れ。リンク切れの場合、ログアウトしないようにする
			CakeLog::error($ex);
			throw new NotFoundException('Not found file');
		} catch (Exception $ex) {
			CakeLog::error($ex);
			throw $ex;
		}

		// Download カウントアップ
		$UploadFile->countUp($file);

		return $this->_controller->response;
	}
}
