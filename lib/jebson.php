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

	// Directories
	public static $contentDirectory = 'content/';
	public static $templateDirectory = 'templates/';
	public static $templateLoadOrder = array('header','body','footer');
	
	// Instance data
	public static $request;
	public static $yaml;
	public static $content;
	public static $excerpt;
	public static $title;
	public static $date;
	public static $slug;

	public static function init() {
		$start_time = microtime();
		ob_start();
		self::getParsedRequest();
		self::getContent(implode('-', self::$request).'.html');
		self::buildPage();
		echo 'Page load time: '.(microtime() - $start_time);
		ob_flush();
	}

	public static function getParsedRequest() {
		self::$request = array_values(array_filter(explode('/', $_SERVER['REQUEST_URI'])));
	}

	public static function getContent($filename = false) {
		
		$postPath = self::$contentDirectory.$filename;
		
		if (file_exists($postPath)) {
			self::$content = file_get_contents($postPath);
			
			self::getYaml();
			
			$parsedFilename = self::parseFilename($filename);
			self::$title = $parsedFilename['title'];
			self::$date = $parsedFilename['date'];
			self::$slug = $parsedFilename['slug'];
		}
	}
	
	public static function getYaml() {
		self::$yaml['raw'] = Yaml::get(self::$content);
		self::$yaml['parsed'] = Yaml::parse(self::$yaml['raw']);
		
		// At this point we don't need the YAML in the content anymore so we can get rid of it
		self::$content = str_replace(self::$yaml['raw'], '', self::$content);
		list(self::$excerpt) = explode('{{readmore}}', self::$content);
		
		// Gotta get rid of the {{readmore}} tag now...there should be a better way to do this
		self::$content = str_replace('{{readmore}}', '', self::$content);
		
		// Store data from YAML for views to use
		foreach (self::$yaml['parsed'] as $key => $value) {
			self::$pageData[$key] = $value;
		}
	}
	
	public static function buildPage() {
		foreach (self::$templateLoadOrder as $template) {
			include self::$templateDirectory.$template.'.php';
		}
	}
	
	public static function renderContent() {		
		if (empty(self::$request[0])) {
			// List posts with excerpts here
			foreach (self::getPosts() as $post)
			{
				self::getContent($post);
				include self::$templateDirectory.'excerpt.php';
			}
		}
		elseif (isset(self::$content)) {
			include self::$templateDirectory.'post.php';
		}
		else {
			self::error(404);
		}
	}
	
	public static function getPosts($start = false, $end = false) {
		if ($handle = opendir(self::$contentDirectory)) {
		    while (false !== ($entry = readdir($handle))) {
		        if (substr($entry, 0, 1) != '.' && is_numeric(substr(str_replace('-','',$entry), 0, 7))) {
		            $posts[] = $entry;
		        }
		    }
		    closedir($handle);
			return $posts;
		}
	}
	
	public static function parseFilename($filename) {
		$return['date'] = substr($filename, 0, 10);
		$return['raw_title'] = str_replace('.html', '', substr($filename, 11));
		$return['title'] = str_replace('-', ' ', $return['raw_title']);
		$return['slug'] = '/'.str_replace('-', '/', $return['date']).'/'.$return['raw_title'];
		return $return;
	}

	public static function error($error) {
		switch ($error) {
			case 404:
				header('HTTP/1.0 404 Not Found');
				echo 'Sorry, this page does not exist.';
				break;
			
			default:
				echo 'Error';
				break;
		}
	}
}