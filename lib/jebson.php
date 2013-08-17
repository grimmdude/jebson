<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once 'lib/yaml.php';
class Jebson {
  // Default page data
  public static $pageData = array(
				  'title' =>'Grimmdude - Jammin till the jammin&#039;s through',
				  'description'=>'',
				  'keywords'=>''
				  );
  
  public static $contentDirectory = 'content/';
  public static $templateDirectory = 'templates/';
  public static $templateLoadOrder = array('header','body','footer');
  public static $yaml;
  public static $request;
  
  public static function buildPage() {
   
	self::$request = self::parseRequest();
	self::$yaml = self::getYaml();
	
	// Grab data from Yaml
	foreach (self::$yaml as $key => $value) {
	  self::$pageData[$key] = $value;
	}
	
	ob_start();
	foreach (self::$templateLoadOrder as $template) {
	  include self::$templateDirectory.$template.'.php';
	}
	ob_end_flush();
  }

  public static function parseRequest() {
    return explode('/', $_SERVER['REQUEST_URI']);
  }
  
  public static function getYaml() {
      if (file_exists(self::$contentDirectory.self::$request[1].'.html')) {
	$content = file_get_contents(self::$contentDirectory.self::$request[1].'.html');
	$yaml = Yaml::parse(Yaml::get($content));
	return $yaml;
      }
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