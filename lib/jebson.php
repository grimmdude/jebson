<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once 'lib/yaml.php';
class Jebson {
	// Default page data
	public static $pageData = array(
			 						 'title'		=>'Grimmdude - Jammin till the jammin&#039;s through',
									  'description'	=>'',
									  'keywords'	=>''
			  						);

	public static $urlOffset = 1;
	public static $contentDirectory = 'content/';
	public static $templateDirectory = 'templates/';
	public static $templateLoadOrder = array('header','body','footer');
	public static $yaml;
	public static $request;
	public static $content;

	public static function init() {
		self::getParsedRequest();
		self::getContent();
		self::getYaml();
		self::buildPage();
	}

	public static function getParsedRequest() {
		self::$request = explode('/', $_SERVER['REQUEST_URI']);
	}

	public static function getContent() {
		$postPath = self::$contentDirectory.self::$request[1 + self::$urlOffset].'.html';
		
		if (file_exists($postPath)) {
			self::$content = file_get_contents($postPath);
		}
	}
	
	public static function getYaml() {
		if (isset(self::$content)) {
			self::$yaml['raw'] = Yaml::get(self::$content);
			self::$yaml['parsed'] = Yaml::parse(self::$yaml['raw']);
			
			// Store data from YAML for views to use
			foreach (self::$yaml['parsed'] as $key => $value) {
				self::$pageData[$key] = $value;
			}
		}
	}
	
	public static function buildPage() {
		ob_start();
		foreach (self::$templateLoadOrder as $template) {
			include self::$templateDirectory.$template.'.php';
		}
		ob_end_flush();	
	}
	
	public static function renderContent() {
		if (empty(self::$request[1 + self::$urlOffset])) {
			// List posts with excertps here
			echo 'all posts';
		}
		elseif (isset(self::$content)) {
			// Strip out that YAML
			echo str_replace(self::$yaml['raw'], '', self::$content);
		}
		else {
			self::error(404);
		}
	}

	public static function error($error) {
		switch ($error) {
			case 404:
				echo 'Sorry, this page does not exist.';
				break;
			
			default:
				echo 'Error';
				break;
		}
	}
}