<?php
/**
 * beforeSetOptions()とafterSetOptions()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');
App::uses('UploadFileFixture', 'Files.Test/Fixture');

/**
 * beforeSetOptions()とafterSetOptions()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Files\Test\Case\Model\UploadFile
 */
class UploadFileSetOptionsTest extends NetCommonsModelTestCase {

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
	protected $_methodName = 'setOptions';

/**
 * setOptions()のテスト
 *
 * @return void
 */
	public function testSetOptions() {
		$this->UploadFile->Behaviors->unload('Upload');
		$options = [
			'foo' => 'bar'
		];
		$uploadBehaviorMock = $this->getMock('UploadBehavior', ['uploadSettings']);
		//
		$uploadBehaviorMock->expects($this->once()) //1回だけ呼ばれる
			->method('uploadSettings')
			->with(
				$this->isInstanceOf('Model'),
				$this->equalTo('real_file_name'),
				$this->equalTo($options));
		ClassRegistry::removeObject('UploadBehavior');
		ClassRegistry::addObject('UploadBehavior', $uploadBehaviorMock);
		//
		$this->UploadFile->Behaviors->load('Upload');
		$this->UploadFile->setOptions($options);
	}
}
