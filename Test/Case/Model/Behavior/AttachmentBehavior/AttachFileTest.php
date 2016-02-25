<?php
/**
 * AttachmentBehavior::attachFile()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');

/**
 * AttachmentBehavior::attachFile()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Files\Test\Case\Model\Behavior\AttachmentBehavior
 */
class AttachmentBehaviorAttachFileTest extends NetCommonsModelTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array();

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'files';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		//テストプラグインのロード
		NetCommonsCakeTestCase::loadTestPlugin($this, 'Files', 'TestFiles');
		$this->TestModel = ClassRegistry::init('TestFiles.TestAttachmentBehaviorModel');
	}

/**
 * attachFile()テストのDataProvider
 *
 * ### 戻り値
 *  - data コンテンツデータ
 *  - fieldName 添付するフィールド名
 *  - file 添付するファイルのFileインスタンスかファイルパス
 *  - keyFieldName コンテンツキーのフィールド名 省略可能 デフォルト key
 *
 * @return array データ
 */
	public function dataProvider() {
		//TODO:テストパタンを書く
		$result[0] = array();
		$result[0]['data'] = null;
		$result[0]['fieldName'] = null;
		$result[0]['file'] = null;
		$result[0]['keyFieldName'] = 'key';

		return $result;
	}

/**
 * attachFile()のテスト
 *
 * @param array $data コンテンツデータ
 * @param string $fieldName 添付するフィールド名
 * @param File|string $file 添付するファイルのFileインスタンスかファイルパス
 * @param string $keyFieldName コンテンツキーのフィールド名 省略可能 デフォルト key
 * @dataProvider dataProvider
 * @return void
 */
	public function testAttachFile($data, $fieldName, $file, $keyFieldName) {
		//テスト実施
		$result = $this->TestModel->attachFile($data, $fieldName, $file, $keyFieldName);

		//チェック
		//TODO:Assertを書く
		debug($result);
	}

}
