<?php
/**
 * Jebson
 * A pseudo static CMS
 * @author Garrett Grimm
 * @date September, 2013
 *
 */

require_once dirname(__FILE__).'/yaml.php';
require_once dirname(__FILE__).'/../config.php';
require_once dirname(__FILE__).'/helpers.php';

// Load Config
Config::init();

class Jebson {
	// Instance data
	public static $request;
	public static $startTime;
	public static $loadTime;
	public static $responseCode = 200;
	public static $yaml;
	public static $content;
	public static $excerpt;
	public static $title;
	public static $date;
	public static $slug;
	public static $pageData;
	public static $modules = array();
	
	// Below only available on blog page
	public static $pageNumber;
	public static $totalPages;
	public static $totalPosts;
	
	/**
	 * Main function that puts everything together.  The only method that's called from index.php
	 * @return void
	 */
	public static function init() {
		if (Config::$debug) {
			error_reporting(E_ALL);
			ini_set('display_errors', '1');
		}
		else {
			error_reporting(0);
			@ini_set('display_errors', 0);
		}
		
		self::$startTime = microtime(true);
		self::$pageData = Config::$defaultPageData;
		self::loadModules();
		self::getParsedRequest();
		self::getContent();
		self::$loadTime = microtime(true) - self::$startTime;
		self::buildPage();
		
		// If cache is enabled and this page hasn't been cached then save the completed page in the cache folder
		if (Config::$cache && !in_array(ltrim($_SERVER['REQUEST_URI'], '/'), Config::$cacheExclude) && !file_exists('cache/'.str_replace('/', '-', $_SERVER['REQUEST_URI']).'.html')) {
			if (!file_put_contents('cache/'.str_replace('/', '-', $_SERVER['REQUEST_URI']).'.html', ob_get_contents())) {
				// Can't write to cache dir...
				throw new Exception('/cache is not writeable.');
			}
		}
	}

	/**
	 * Loads any modules for use in views
	 * @return void
	 */
	public static function loadModules() {
		if (is_dir('modules')) {
			if ($handle = opendir('modules')) {
				// Include each module class
	 			while (false !== ($module = readdir($handle))) {
					if (substr($module, 0, 1) != '.') {
						require_once 'modules/'.$module;
						self::$modules[] = $module;
					}
				}
				closedir($handle);
			}
			else {
				throw new Exception('/modules is not readable.');
			}
		}
	}
	
	/**
	 * Parses request URI into an array and stores as self::$request
	 * @return void
	 */
	public static function getParsedRequest() {
		// Strip off query string
		self::$request = array_values(array_filter(explode('/', strtok($_SERVER['REQUEST_URI'], '?'))));
	}

	/**
	 * Gets content from given filename and saves it to static properties
	 * @return void
	 */
	public static function getContent($filename = null) {
		// Get the filename (and path) by the request
		$filename = is_null($filename) ? self::getFilename() : $filename;
				
		// Check if this is the home page
		if (empty(self::$request) && Config::$blogURI != '') {
			$postPath = Config::$contentDirectory.Config::$homepage.'.php';
		}
		else {
			$postPath = Config::$contentDirectory.$filename;
		}

		// First check to see if cache is enabled and we have a cached page for this request
		if (Config::$cache && file_exists('cache/'.str_replace('/', '-', $_SERVER['REQUEST_URI']).'.php')) {
			echo 'cached...';
			readfile('cache/'.str_replace('/', '-', $_SERVER['REQUEST_URI']).'.php');
			exit;
		}
		elseif (file_exists($postPath)) {
			ob_start();
			include $postPath;
			self::$content = ob_get_contents();
			ob_end_clean();
			
			// Go through each $wildcard and replace any needed strings
			if (isset(Config::$wildcards)) {
				foreach (Config::$wildcards as $find => $replace) {
					self::$content = str_replace($find, $replace, self::$content);
				}
			}
			
			// Get first paragraph to use as an excerpt
			preg_match_all('/<p[^>]*>.*?<\/p>/s', self::$content, $paragraphs);
			self::$excerpt = $paragraphs[0][0];
			
			//self::$content = file_get_contents($postPath);
			//self::getYaml();
			
			$parsedFilename = self::parseFilename($filename);
			//self::$title = $parsedFilename['title'];
			self::$date = $parsedFilename['date'];
			self::$slug = $parsedFilename['slug'];
			return self::$content;
		}
		elseif (!self::isBlog()) {
			// Trigger 404
			self::$responseCode = 404;
		}
	}
	
	
	/**
	 * Pulls Yaml from self::$content and saves it to static properties
	 * @return void
	 * NO LONGER USED
	 */
	/*
	public static function getYaml() {
		self::$yaml['raw'] = Yaml::get(self::$content);
		self::$yaml['parsed'] = Yaml::parse(self::$yaml['raw']);
		
		// At this point we don't need the YAML in the content anymore so we can get rid of it
		self::$content = str_replace(self::$yaml['raw'], '', self::$content);
		list(self::$excerpt) = explode('{{readmore}}', self::$content);
		
		// Gotta get rid of the {{readmore}} tag now...there should be a better way to do this
		self::$content = str_replace('{{readmore}}', '', self::$content);
		
		// Go through each $wildcard and replace any needed strings
		if (isset(Config::$wildcards)) {
			foreach (Config::$wildcards as $find => $replace) {
				self::$content = str_replace($find, $replace, self::$content);
			}
		}
		
		// Store data from YAML for views to use
		foreach (self::$yaml['parsed'] as $key => $value) {
			self::$pageData[$key] = $value;
		}
	}
	*/
	
	/**
	 * Includes each element of the page as defined by Config::$viewLoadOrder
	 * @return void
	 */
	public static function buildPage() {
		if (self::$responseCode != 200) {
			self::error(self::$responseCode);
		}
		foreach (Config::$viewLoadOrder as $view) {
			include Config::$viewsDirectory.$view.'.php';
		}
	}
	
	/**
	 * Outputs output to page (not from cache)
	 * @return void
	 *
	 */
	public static function renderContent() {
		if (self::$responseCode != 200) {
			include Config::$viewsDirectory.'404.php';
		}
		elseif (self::isBlog()) {
			if (empty(Config::$blogURI)) { // Blog is set to show on homepage
				self::$pageNumber = isset(self::$request[0]) && is_numeric(self::$request[0]) ? self::$request[0] : 1;
			}
			else {
				self::$pageNumber = isset(self::$request[1]) && is_numeric(self::$request[1]) ? self::$request[1] : 1;
			}
			
			// List posts with excerpts here
			$posts = self::getPosts(self::$pageNumber);

			// Grab each post
			if (count($posts)) {				
				foreach ($posts as $post) {
					self::getContent($post);
					include Config::$viewsDirectory.'excerpt.php';
				}
			}
		}
		elseif (isset(self::$content)) {
			include Config::$viewsDirectory.'post.php';
		}	
	}
	
	/**
	 * Gets requested post filenames
	 * @param int $page Page number.  Defaults to 1
	 * @param string $order 'desc' or 'asc'. Defaults to 'desc'
	 * @return array
	 */
	public static function getPosts($page = false, $order = 'desc') {
		if ($handle = opendir(Config::$contentDirectory)) {
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
			
			// Calculate total number of post pages and set instance var
			self::$totalPosts = count($allPosts);
			self::$totalPages = ceil(self::$totalPosts / Config::$postsPerPage);
			
			// Now pull out the posts we want to return
			$postCount = 0;
			$returnPosts = array();
			foreach ($allPosts as $post) {
				$postCount++;
				if (is_numeric($page)) {
					if ($page == 1) {
						$start = $page;
						$stop = $page + Config::$postsPerPage - 1;
					}
					else {
						$start = $page * Config::$postsPerPage - (Config::$postsPerPage - 1);
						$stop = $page * Config::$postsPerPage;
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
	 * Gets filename from request.
	 * Note: If there are multiple URL segments this functions checks to see if the first segment is a number.  If it is
	 *       then it's assumed a blog post and that filename will be returned.  Otherwise it will look for the file in the 
	 *       request folder(s).
	 * @return string path of html file requested
	 */
	public static function getFilename() {
		if (isset(self::$request[0]) && !is_numeric(self::$request[0]) ) {
			return implode('/', self::$request).'.php';
		}
		else {
			return implode('-', self::$request).'.php';
		}
	}
	
	/**
	 * Parses a filename into an array of parts
	 * @param string $filename Name of file to parse
	 * @return array
	 */
	public static function parseFilename($filename) {
		$return['date'] = self::isPost($filename) ? substr($filename, 0, 10) : false;
		$return['raw_title'] = str_replace('.php', '', substr($filename, 11));
		$return['slug'] = '/'.str_replace('-', '/', $return['date']).'/'.$return['raw_title'];
		return $return;
	}
	
	/**
	* Determine if a file is a blog post.  (if it starts with the date format)
	* @param string $filename Name of the post file
	*/
	public static function isPost($filename) {
		return is_numeric(substr(str_replace('-','',$filename), 0, 7));
	}
	
	/**
	* Determine if the request is for the blog.
	*/
	public static function isBlog() {
		return 
				(empty(self::$request) && empty(Config::$blogURI)) ||
				(isset(self::$request[0]) && is_numeric(self::$request[0]) && empty(self::$request[1]) && empty(Config::$blogURI)) || 
				(!empty(self::$request) && self::$request[0] == Config::$blogURI);
	}

	/**
	 * Sends the appropriate error header to the browser and outputs a message
	 * @param int $error Error code
	 * @return void
	 */
	public static function error($error) {
		switch ($error) {
			case 404:
				header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
				//include Config::$viewsDirectory.'404.php';
				break;
			default:
				//echo 'Error';
				break;
		}
	}
}