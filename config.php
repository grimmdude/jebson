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

	public static $contentDirectory = 'content/';
	public static $viewsDirectory = 'views/';
	public static $viewLoadOrder = array('header','body','footer');
	public static $homepage = 'home.html';
	public static $blogURI = 'blog';
	public static $postsPerPage = 5;
	public static $cache = false;
	public static $debug = true;
	public static $speedMyShitUp = true; //jk
}