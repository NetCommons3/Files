<?php
/**
 * beforeNameCallback()とafterNameCallback()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');
App::uses('UploadFileFixture', 'Files.Test/Fixture');

/**
 * beforeNameCallback()とafterNameCallback()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Files\Test\Case\Model\UploadFile
 */
class UploadFileNameCallbackTest extends NetCommonsModelTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.files.upload_file',
		'plugin.files.upload_files_content',
	);

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'files';

/**
 * Model name
 *
 * @var string
 */
	protected $_modelName = 'UploadFile';

/**
 * Method name
 *
 * @var string
 */
	protected $_methodName = 'nameCallback';

/**
 * nameCallback()のテスト
 *
 * @return void
 */
	public function testNameCallback() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//データ生成
		$data['UploadFile'] = (new UploadFileFixture())->records[0];

		//テスト実施
		$result = $this->$model->$methodName($data);

		//チェック
		//TODO:Assertを書く
		debug($result);
	}

}
