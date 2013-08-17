<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
class Jebson {
  public static $contentDirectory = 'content/';
  public static $templateDirectory = 'templates/';
  public static $templateLoadOrder = array('header','body','footer');
  public static $request;
  
  public static function buildPage() {
    if (self::$request = self::parseRequest()) {     
	ob_start();
	foreach (self::$templateLoadOrder as $template) {
	  include self::$templateDirectory.$template.'.php';
	}
	ob_end_flush();
    }
  }

  public static function parseRequest() {
    return explode('/', $_SERVER['REQUEST_URI']);
  }
  
  public static function renderContent() {
    if (!empty(self::$request[1])) {
      if (file_exists(self::$contentDirectory.self::$request[1].'.html')) {
	include self::$contentDirectory.self::$request[1].'.html';
      }
      else {
	self::error('Post not found');
      }
    }
    else {
      // List posts with excertps here
      echo 'all posts';
    }
  }
  
  public static function error($error = 'Error') {
    echo $error;
    die;
  }
}