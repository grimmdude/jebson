<?php
/**
 * Jebson
 * A pseudo static CMS
 * @author Garrett Grimm
 * @date September, 2013
 *
 */

require_once 'lib/yaml.php';
class Jebson {
	// Setup
	public static $contentDirectory = 'content/';
	public static $templateDirectory = 'templates/';
	public static $templateLoadOrder = array('header','body','footer');
	public static $blogURI = 'blog';
	public static $postsPerPage = 5;
	public static $cache = false;
	public static $debug = true;
	
	// Instance data
	public static $request;
	public static $startTime;
	public static $loadTime;
	public static $yaml;
	public static $content;
	public static $excerpt;
	public static $title;
	public static $date;
	public static $slug;
	public static $pageNumber;
	
	// Default page data
	public static $pageData = array(
					'title'		=>'Grimmdude - Jammin till the jammin&#039;s through',
					'description'	=>'',
					'keywords'	=>''
			  		);

	/**
	 * Main function that puts everything together.  The only method that's called from index.php
	 * @return void
	 */
	public static function init() {
		if (self::$debug) {
			error_reporting(E_ALL);
			ini_set('display_errors', '1');
		}
		
		self::$startTime = microtime(true);
		ob_start();
		self::getParsedRequest();
		self::getContent(implode('-', self::$request).'.html');
		self::$loadTime = microtime(true) - self::$startTime;
		self::buildPage();
		
		// If cache is enabled and this page hasn't been cached then save the completed page in the cache folder
		if (self::$cache && !file_exists('cache/'.str_replace('/', '-', $_SERVER['REQUEST_URI']).'.html')) {
			if (!file_put_contents('cache/'.str_replace('/', '-', $_SERVER['REQUEST_URI']).'.html', ob_get_contents())) {
				// Exception here if can't write to cache dir
			}
		}
		ob_flush();
	}

	/**
	 * Parses request URI into an array and stores as self::$request
	 * @return void
	 */
	public static function getParsedRequest() {
		self::$request = array_values(array_filter(explode('/', $_SERVER['REQUEST_URI'])));
	}

	/**
	 * Gets content from given filename and saves it to static properties
	 * @return void
	 *
	 */
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
		}
	}
	
	/**
	 * Pulls Yaml from self::$content and saves it to static properties
	 * @return void
	 *
	 */
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
	
	/**
	 * Includes each element of the page as defined by self::$templateLoadOrder
	 * @return void
	 */
	public static function buildPage() {
		foreach (self::$templateLoadOrder as $template) {
			include self::$templateDirectory.$template.'.php';
		}
	}
	
	/**
	 * Outputs output to page (not from cache)
	 * @return void
	 *
	 */
	public static function renderContent() {		
		if (self::$request[0] == self::$blogURI) {
			self::$pageNumber = isset(self::$request[1]) && is_numeric(self::$request[1]) ? self::$request[1] : 1;
			
			// List posts with excerpts here
			$posts = self::getPosts(self::$pageNumber);
			if (count($posts)) {
				foreach (self::getPosts(self::$pageNumber) as $post) {
					self::getContent($post);
					include self::$templateDirectory.'excerpt.php';
				}
			}
			else {
				self::error(404);
			}
		}
		elseif (isset(self::$content)) {
			include self::$templateDirectory.'post.php';
		}
		else {
			self::error(404);
		}
	}
	
	/**
	 * Gets requested post filenames
	 * @param int $page Page number.  Defaults to 1
	 * @param string $order 'desc' or 'asc'. Defaults to 'desc'
	 * @return array
	 */
	public static function getPosts($page = 1, $order = 'desc') {
		if ($handle = opendir(self::$contentDirectory)) {
			$allPosts = array();
			// First create a list of available posts
 			while (false !== ($entry = readdir($handle))) {
				if (substr($entry, 0, 1) != '.' && is_numeric(substr(str_replace('-','',$entry), 0, 7))) {
					$allPosts[] = $entry;
				}
			}
			closedir($handle);
			if ($order == 'desc') {
				rsort($allPosts);
			}
			else {
				asort($allPosts);
			}
			
			// Now pull out the posts we want to return
			$postCount = 0;
			$returnPosts = array();
			foreach ($allPosts as $post) {
				$postCount++;
				if (is_numeric($page)) {
					if ($page == 1) {
						$start = $page;
						$stop = $page + self::$postsPerPage - 1;
					}
					else {
						$start = $page * self::$postsPerPage - 1;
						$stop = $page * self::$postsPerPage + self::$postsPerPage - 2;
					}
					
					if (in_array($postCount, range($start, $stop))) {
						$returnPosts[] = $post;
					}
				}
				elseif (!is_numeric($page)) {
					$returnPosts[] = $post;	
				}
			}
			
			return $returnPosts;
		}
		else {
			// Exception here.  Can't open self::$contentDirectory
		}
	}
	
	/**
	 * Parses a filename into an array of parts
	 * @param string $filename Name of file to parse
	 * @return array
	 */
	public static function parseFilename($filename) {
		$return['date'] = substr($filename, 0, 10);
		$return['raw_title'] = str_replace('.html', '', substr($filename, 11));
		$return['slug'] = '/'.str_replace('-', '/', $return['date']).'/'.$return['raw_title'];
		return $return;
	}

	/**
	 * Sends the appropriate error header to the browser and outputs a message
	 * @param int $error Error code
	 * @return void
	 */
	public static function error($error) {
		switch ($error) {
			case 404:
				header('HTTP/1.0 404 Not Found');
				echo '<p>Sorry, this page does not exist, 404.</p>';
				break;
			default:
				echo 'Error';
				break;
		}
	}
}