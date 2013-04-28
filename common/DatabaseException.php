<?php

  class DatabaseException extends Exception {
	  private $errors;

	  public function __construct() {
		$this->errors = array();
	  }

	  public static function singleMessage($message) {
		$errors = new DatabaseException();
		$errors->addError('global', $message);
		return $errors;
	  }

	  public static function singleField($field, $message) {
		$errors = new DatabaseException();
		$errors->addError($field, $message);
		return $errors;
	  }

	  public function addError($field, $message) {
		$this->errors[$field] = $message;
          }	

	  public function hasErrors() {
		return count($this->errors) != 0;
	  }

	  public function getErrors() {
		return $this->errors;
	  }
  }
?>