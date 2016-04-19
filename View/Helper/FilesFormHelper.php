<?php
/**
 * FilesFormHelper
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppHelper', 'View/Helper');

/**
 * FilesFormHelper
 *
 * @package NetCommons\Files\View\Helper
 */
class FilesFormHelper extends AppHelper {

/**
 * Other helpers used by FormHelper
 *
 * @var array
 */
	public $helpers = array(
		'Form',
		'Html',
		'NetCommons.NetCommonsForm',
	);

/**
 * @var array アップロードされたファイルの元ファイル名
 */
	protected $_uploadFileNames = array();

/**
 * Filesプラグインのアップロードフォームの準備
 *
 * 現在添付されてるファイルのID、フィールド名をhidden で埋め込む
 *
 * @return string
 */
	public function setupFileUploadForm() {
		// setup的な処理と定型のhidden埋め込み
		$output = '';
		if (isset($this->request->data['UploadFile'])) {
			foreach (array_keys($this->request->data['UploadFile']) as $key) {
				$output .= $this->NetCommonsForm->input('UploadFile.' . $key . '.id', ['type' => 'hidden']);
				$output .= $this->NetCommonsForm->input('UploadFile.' . $key . '.field_name', ['type' => 'hidden']);
				$output .= $this->NetCommonsForm->input('UploadFile.' . $key . '.original_name', ['type' => 'hidden']);
			}

			// uploadされた元ファイル名のリスト
			$this->_uploadFileNames = Hash::combine(
					$this->request->data['UploadFile'],
					'{s}.field_name',
					'{s}.original_name'
			);
		}
		return $output;
	}

/**
 * Filesプラグイン用のfileフォーム。ファイル削除チェックボックスとファイル名表示付き
 *
 * @param string $fieldName フィールド名
 * @param array $options オプション
 *  filename => false でフィアル名非表示
 *  remove => falseで削除チェックボックス非表示。デフォルトはtrue
 * @return string inputタグ等
 */
	public function uploadFile($fieldName, $options = array()) {
		if (strpos($fieldName, '.')) {
			//モデル名あり ex BlogEntry.pdf
			$inputFieldName = $fieldName;
			$fieldName = substr($fieldName, strrpos($fieldName, '.') + 1); //BlogEntry.pdf -> pdf
		} else {
			// モデル名ついてない
			$modelName = $this->Form->defaultModel;
			$inputFieldName = $modelName . '.' . $fieldName;
		}
		$output = '<div class="form-group">';
		$defaultOptions = [
			'class' => '',
			'div' => false,
			'remove' => true,
			'filename' => true,
		];
		$options = Hash::merge($defaultOptions, $options, ['type' => 'file']);

		$remove = Hash::get($options, 'remove');
		Hash::remove($options, 'remove');
		$filename = Hash::get($options, 'filename');
		Hash::remove($options, 'filename');

		$help = $help = Hash::get($options, 'help', false);
		Hash::remove($options, 'help');

		$output .= $this->NetCommonsForm->input($inputFieldName, $options);

		// help-block
		if ($help) {
			$output .= $this->Html->tag('p', $help, ['class' => 'help-block']);
		}

		if (isset($this->_uploadFileNames[$fieldName])) {
			if ($filename) {
				$output .= $this->_uploadFileNames[$fieldName];
			}
			if ($remove) {
				$output .= $this->NetCommonsForm->checkbox(
						$inputFieldName . '.remove',
						['type' => 'checkbox', 'div' => false, 'error' => false]
				);
				$output .= $this->Form->label($inputFieldName . '.remove', __d('net_commons', 'Delete'));
			}
		}
		$output .= '</div>';

		return $output;
	}

}

