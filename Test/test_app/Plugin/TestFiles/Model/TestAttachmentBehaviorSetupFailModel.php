<?php
/**
 * AttachmentBehaviorテスト用Model
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppModel', 'Model');

/**
 * AttachmentBehaviorテスト用Model
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Files\Test\test_app\Plugin\TestFiles\Model
 */
class TestAttachmentBehaviorSetupFailModel extends AppModel {

/**
 * テーブル名
 *
 * @var mixed
 */
	public $useTable = false;

/**
 * 使用ビヘイビア
 *
 * @var array
 */
	public $actsAs = array(
		'Files.Attachment'
	);

}
