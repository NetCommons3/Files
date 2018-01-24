<?php
/**
 * beforeSave()とafterSave()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');
App::uses('UploadFileFixture', 'Files.Test/Fixture');

/**
 * beforeSave()とafterSave()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Files\Test\Case\Model\UploadFile
 */
class UploadFileSaveTest extends NetCommonsModelTestCase {

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
	protected $_methodName = 'save';

/**
 * save()のテスト
 *
 * @return void
 */
	public function testSave() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//データ生成
		$data['UploadFile'] = (new UploadFileFixture())->records[0];
		$data['UploadFile'] = [
			'plugin_key' => 'site_manager',
			'content_key' => 'theme',
			'field_name' => 'photo',
			'original_name' => 'foo.jpg',
			//'extension' => 'jpg',
			//'mimetype' => 'image/jpg',
			//'size' => 1,
			//'download_count' => 1,
			//'total_download_count' => 1,
			//'room_id' => '2',
			//'block_key' => 'block_1',
			//'created_user' => 1,
			//'created' => '2015-11-06 02:20:55',
			//'modified_user' => 1,
			//'modified' => '2015-11-06 02:20:55'
		];
		// behaviorはずしておく
		$this->$model->Behaviors->unload('UploadFileDisableThumbnail');
		//テスト実施
		$result = $this->$model->$methodName($data);

		// pathがセットされるか？
		$this->assertStringStartsWith('files' . DS . 'upload_file' . DS . 'real_file_name' . DS, $result['UploadFile']['path']);

		// トータルダウンロードの値が更新されるか id=1 1カウント, id=3 1カウント total 2になるはず
		$this->assertEquals(2, $result['UploadFile']['total_download_count']);
	}
}
