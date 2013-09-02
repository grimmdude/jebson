<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once 'lib/yaml.php';
class Jebson {
	// Setup
	public static $contentDirectory = 'content/';
	public static $templateDirectory = 'templates/';
	public static $templateLoadOrder = array('header','body','footer');
	public static $blogURI = 'blog';
	public static $postsPerPage = 5;
	public static $cache = true;
	
	// Instance data
	public static $request;
	public static $start_time;
	public static $load_time;
	public static $yaml;
	public static $content;
	public static $excerpt;
	public static $title;
	public static $date;
	public static $slug;
	public static $pageNumber;
	
	// Default page data
	public static $pageData = array(
						'title'			=>'Grimmdude - Jammin till the jammin&#039;s through',
						'description'	=>'',
						'keywords'		=>''
			  		);

	public static function init() {
		self::$start_time = microtime(true);
		ob_start();
		self::getParsedRequest();
		self::getContent(implode('-', self::$request).'.html');
		self::buildPage();
		
		// If cache is enabled then save the completed page in the cache folder
		if (self::$cache) {
			file_put_contents('cache/'.str_replace('/', '-', $_SERVER['REQUEST_URI']).'.html', ob_get_contents());
		}
		ob_flush();
	}

	public static function getParsedRequest() {
		self::$request = array_values(array_filter(explode('/', $_SERVER['REQUEST_URI'])));
	}

	public static function getContent($filename = false) {
		$postPath = self::$contentDirectory.$filename;
		
		// First check to see if cache is enabled and we have a cached page for this request
		if (self::$cache && file_exists('cache/'.str_replace('/', '-', $_SERVER['REQUEST_URI']).'.html')) {
			echo 'cached...';
			readfile('cache/'.str_replace('/', '-', $_SERVER['REQUEST_URI']).'.html');
			die;
		}
		elseif (file_exists($postPath)) {
			self::$content = file_get_contents($postPath);
			self::getYaml();
			
			$parsedFilename = self::parseFilename($filename);
			//self::$title = $parsedFilename['title'];
			self::$date = $parsedFilename['date'];
			self::$slug = $parsedFilename['slug'];
			
			return true;
		}
		return false;
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
		self::$load_time = microtime(true) - self::$start_time;
		foreach (self::$templateLoadOrder as $template) {
			include self::$templateDirectory.$template.'.php';
		}
	}
	
	public static function renderContent() {		
		if (self::$request[0] == self::$blogURI) {
			self::$pageNumber = isset(self::$request[1]) && is_numeric(self::$request[1]) ? self::$request[1] : null;
			
			// List posts with excerpts here
			foreach (self::getPosts(self::$pageNumber) as $post)
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
	
	public static function getPosts($page = null) {
		if ($handle = opendir(self::$contentDirectory)) {
			$postCount = 0;
 			while (false !== ($entry = readdir($handle))) {
				if (substr($entry, 0, 1) != '.' && is_numeric(substr(str_replace('-','',$entry), 0, 7))) {
					$postCount++;
					if (is_numeric($page)) {
						switch ($page) {
							case 1:
								$start = $page;
								$stop = $page + self::$postsPerPage - 1;
								break;
							default:
								$start = $page * self::$postsPerPage - 1;
								$stop = $page * self::$postsPerPage + self::$postsPerPage - 2;
								break;
						}
						if (in_array($postCount, range($start, $stop))) {
							$posts[] = $entry;
						}
					}
					elseif (!is_numeric($page)) {
						$posts[] = $entry;	
					}
				}
			}
			closedir($handle);
			rsort($posts);
			return $posts;
		}
	}
	
	public static function parseFilename($filename) {
		$return['date'] = substr($filename, 0, 10);
		$return['raw_title'] = str_replace('.html', '', substr($filename, 11));
		//$return['title'] = str_replace('-', ' ', $return['raw_title']);
		$return['slug'] = '/'.str_replace('-', '/', $return['date']).'/'.$return['raw_title'];
		return $return;
	}

	public static function error($error) {
		switch ($error) {
			case 404:
				header('HTTP/1.0 404 Not Found');
				echo '<p>Sorry, this page does not exist.</p>';
				break;
			
			default:
				echo 'Error';
				break;
		}
	}
}