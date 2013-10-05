<?php
/**
* Config
*/
class Config
{
	public static $contentDirectory = 'content/';
	public static $viewsDirectory = 'views/';
	public static $viewLoadOrder = array('header','body','footer');
	public static $homepage = 'sample-page.html';
	public static $blogURI = 'blog';
	public static $postsPerPage = 5;
	public static $cache = false;
	public static $debug = true;
}
