<?php
/**
* Config
*/
class Config
{
	public static $defaultPageData = array(
										'title'			=> 'Jebson CMS',
										'description'	=> 'This is the default page data that will be if none is specified in the requested content file.',
										'keywords'		=> 'Jebson CMS, wordpress alternative, fast loading, easy setup'
									);

	public static $contentDirectory; // The directory that houses the content files.
	public static $viewsDirectory; // The directory that houses the view files.
	public static $viewLoadOrder    = array('header','body','footer'); // The order in which main 'structure' views will be loaded
	public static $homepage         = 'home'; // The content file that will serve as your homepage.
	public static $blogURI          = 'blog'; // The URI that will point to your blog (list of posts).  Leave blank for blog on homepage.
	public static $wildcards        = array(); // Array of wildcards that, when used in a post or page, are replaced by their corresponding value.
	public static $postsPerPage     = 5; // How many posts to show per blog page.
	public static $cache            = false; // Enable/disable cacheing.
	public static $cacheExclude     = array('contact'); // Array of posts/pages that are to be excluded from caching if they have dynamic content.
	public static $debug            = true; // Enable/disable debugging.  Basically toggles PHP error reporting.

	public static function init() {
		self::$contentDirectory = dirname(__FILE__).'/content/'; // The directory that houses the content files.
		self::$viewsDirectory   = dirname(__FILE__).'/views/'; // The directory that houses the view files.
	}
}