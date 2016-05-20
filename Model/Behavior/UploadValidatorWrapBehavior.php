<?php
/**
 * UploadValidatorWrapBehavior
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

/**
 * Class UploadValidatorWrapBehavior
 *
 * UploadBehaviorのバリデータだけをwrapしたビヘイビア
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class UploadValidatorWrapBehavior extends ModelBehavior {

/**
 * SetUp Attachment behavior
 *
 * @param Model $model instance of model
 * @param array $config array of configuration settings.
 * @throws CakeException 先にOriginalKeyが登録されてないと例外
 * @return void
 */
	public function setup(Model $model, $config = array()) {
		$this->UploadFile = ClassRegistry::init('Files.UploadFile');
	}

/**
 * Check that the file does not exceed the max
 * file size specified by PHP
 *
 * @param Model $model Model instance
 * @param mixed $check Value to check
 * @return bool Success
 */
	public function isUnderPhpSizeLimit(Model $model, $check) {
		return $this->UploadFile->isUnderPhpSizeLimit($check);
	}

/**
 * Check that the file does not exceed the max
 * file size specified in the HTML Form
 *
 * @param Model $model Model instance
 * @param mixed $check Value to check
 * @return bool Success
 */
	public function isUnderFormSizeLimit(Model $model, $check) {
		return $this->UploadFile->isUnderFormSizeLimit($check);
	}

/**
 * Check that the file was completely uploaded
 *
 * @param Model $model Model instance
 * @param mixed $check Value to check
 * @return bool Success
 */
	public function isCompletedUpload(Model $model, $check) {
		return $this->UploadFile->isCompletedUpload($check);
	}

/**
 * Check that a file was uploaded
 *
 * @param Model $model Model instance
 * @param mixed $check Value to check
 * @return bool Success
 */
	public function isFileUpload(Model $model, $check) {
		return $this->UploadFile->isFileUpload($check);
	}

/**
 * Check that either a file was uploaded,
 * or the existing value in the database is not blank.
 *
 * @param Model $model Model instance
 * @param mixed $check Value to check
 * @return bool Success
 */
	public function isFileUploadOrHasExistingValue(Model $model, $check) {
		return $this->UploadFile->isFileUploadOrHasExistingValue($check);
	}

/**
 * Check that the PHP temporary directory is missing
 *
 * @param Model $model Model instance
 * @param mixed $check Value to check
 * @param bool $requireUpload Whether or not to require a file upload
 * @return bool Success
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
	public function tempDirExists(Model $model, $check, $requireUpload = true) {
		return $this->UploadFile->tempDirExists($check, $requireUpload);
	}

/**
 * Check that the file was successfully written to the server
 *
 * @param Model $model Model instance
 * @param mixed $check Value to check
 * @param bool $requireUpload Whether or not to require a file upload
 * @return bool Success
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
	public function isSuccessfulWrite(Model $model, $check, $requireUpload = true) {
		return $this->UploadFile->isSuccessfulWrite($check, $requireUpload);
	}

/**
 * Check that a PHP extension did not cause an error
 *
 * @param Model $model Model instance
 * @param mixed $check Value to check
 * @param bool $requireUpload Whether or not to require a file upload
 * @return bool Success
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
	public function noPhpExtensionErrors(Model $model, $check, $requireUpload = true) {
		return $this->UploadFile->noPhpExtensionErrors($check, $requireUpload);
	}

/**
 * Check that the file is of a valid mimetype
 *
 * @param Model $model Model instance
 * @param mixed $check Value to check
 * @param array $mimetypes file mimetypes to allow
 * @param bool $requireUpload Whether or not to require a file upload
 * @return bool Success
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
	public function isValidMimeType(Model $model, $check, $mimetypes = array(),
		$requireUpload = true) {
		return $this->UploadFile->isValidMimeType($check, $mimetypes, $requireUpload);
	}

/**
 * Check that the upload directory is writable
 *
 * @param Model $model Model instance
 * @param mixed $check Value to check
 * @param bool $requireUpload Whether or not to require a file upload
 * @return bool Success
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
	public function isWritable(Model $model, $check, $requireUpload = true) {
		return $this->UploadFile->isWritable($check, $requireUpload);
	}

/**
 * Check that the upload directory exists
 *
 * @param Model $model Model instance
 * @param mixed $check Value to check
 * @param bool $requireUpload Whether or not to require a file upload
 * @return bool Success
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
	public function isValidDir(Model $model, $check, $requireUpload = true) {
		return $this->UploadFile->isValidDir($check, $requireUpload);
	}

/**
 * Check that the file is below the maximum file upload size
 *
 * @param Model $model Model instance
 * @param mixed $check Value to check
 * @param int $size Maximum file size
 * @param bool $requireUpload Whether or not to require a file upload
 * @return bool Success
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
	public function isBelowMaxSize(Model $model, $check, $size = null, $requireUpload = true) {
		return $this->UploadFile->isBelowMaxSize($check, $size, $requireUpload);
	}

/**
 * Check that the file is above the minimum file upload size
 *
 * @param Model $model Model instance
 * @param mixed $check Value to check
 * @param int $size Minimum file size
 * @param bool $requireUpload Whether or not to require a file upload
 * @return bool Success
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
	public function isAboveMinSize(Model $model, $check, $size = null, $requireUpload = true) {
		return $this->UploadFile->isAboveMinSize($check, $size, $requireUpload);
	}

/**
 * Check that the file has a valid extension
 *
 * @param Model $model Model instance
 * @param mixed $check Value to check
 * @param array $extensions file extenstions to allow
 * @param bool $requireUpload Whether or not to require a file upload
 * @return bool Success
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
	public function isValidExtension(Model $model, $check, $extensions = array(),
		$requireUpload = true) {
		return $this->UploadFile->isValidExtension($check, $extensions, $requireUpload);
	}

/**
 * Check that the file is above the minimum height requirement
 *
 * @param Model $model Model instance
 * @param mixed $check Value to check
 * @param int $height Height of Image
 * @param bool $requireUpload Whether or not to require a file upload
 * @return bool Success
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
	public function isAboveMinHeight(Model $model, $check, $height = null, $requireUpload = true) {
		return $this->UploadFile->isAboveMinHeight($check, $height, $requireUpload);
	}

/**
 * Check that the file is below the maximum height requirement
 *
 * @param Model $model Model instance
 * @param mixed $check Value to check
 * @param int $height Height of Image
 * @param bool $requireUpload Whether or not to require a file upload
 * @return bool Success
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
	public function isBelowMaxHeight(Model $model, $check, $height = null, $requireUpload = true) {
		return $this->UploadFile->isBelowMaxHeight($check, $height, $requireUpload);
	}

/**
 * Check that the file is above the minimum width requirement
 *
 * @param Model $model Model instance
 * @param mixed $check Value to check
 * @param int $width Width of Image
 * @param bool $requireUpload Whether or not to require a file upload
 * @return bool Success
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
	public function isAboveMinWidth(Model $model, $check, $width = null, $requireUpload = true) {
		return $this->UploadFile->isAboveMinWidth($check, $width, $requireUpload);
	}

/**
 * Check that the file is below the maximum width requirement
 *
 * @param Model $model Model instance
 * @param mixed $check Value to check
 * @param int $width Width of Image
 * @param bool $requireUpload Whether or not to require a file upload
 * @return bool Success
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
	public function isBelowMaxWidth(Model $model, $check, $width = null, $requireUpload = true) {
		return $this->UploadFile->isBelowMaxWidth($check, $width, $requireUpload);
	}
}