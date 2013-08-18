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
		self::$request = array_values(array_filter(explode('/', $_SERVER['REQUEST_URI'])));
	}

	public static function getContent() {
		$postPath = self::$contentDirectory.implode('-', self::$request).'.html';
		
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
		
		if (empty(self::$request[0])) {
			// List posts with excertps here
			foreach (self::getAllPosts() as $post)
			{
				$filename = self::parseFilename($post);
				?>
				<a href="<?php echo $filename['slug']; ?>"><?php echo $filename['title']; ?></a>
				<?php
			}
		}
		elseif (isset(self::$content)) {
			echo '<h1>'.self::$pageData['title'].'</h1>';
			// Strip out that YAML
			echo str_replace(self::$yaml['raw'], '', self::$content);
		}
		else {
			self::error(404);
		}
	}
	
	public static function getAllPosts() {
		if ($handle = opendir(self::$contentDirectory)) {
		    while (false !== ($entry = readdir($handle))) {
		        if (substr($entry, 0, 1) != '.') {
		            $posts[] = $entry;
		        }
		    }
		    closedir($handle);
			return $posts;
		}
	}
	
	public static function parseFilename($filename) {
		$return['date'] = substr($filename, 0, 10);
		$return['raw_title'] = rtrim(substr($filename, 11), '.html');
		$return['title'] = str_replace('-', ' ', $return['raw_title']);
		$return['slug'] = '/'.str_replace('-', '/', $return['date']).'/'.$return['raw_title'];
		return $return;
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