<?php
/**
 * UploadFile::getFile()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsGetTest', 'NetCommons.TestSuite');

/**
 * UploadFile::getFile()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Files\Test\Case\Model\UploadFile
 */
class UploadFileGetFileTest extends NetCommonsGetTest {

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
	protected $_methodName = 'getFile';

/**
 * getFile()のテスト
 *
 * @return void
 */
	public function testGetFile() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//データ生成
		$pluginKey = 'site_manager';
		$contentId = 2;
		$fieldName = 'photo';

		//テスト実施
		$result = $this->$model->$methodName($pluginKey, $contentId, $fieldName);

		// UploadFile.id =1 のデータが取れる
		$this->assertEquals(1, $result['UploadFile']['id']);
	}

}
