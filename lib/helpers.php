<?php
/**
* 
*/
class Helpers
{
	/**
	* Helper function to load js
	* @param string $assetPath
	*/
	public static function loadJS($assetPath) {
		return '<script type="text/javascript" src="'.$assetPath.'"></script>'."\n";
	}

	/**
	* Helper function to load css
	* @param string $assetPath
	*/
	public static function loadCSS($assetPath) {
		return '<link type="text/css" media="all" rel="stylesheet" href="'.$assetPath.'">'."\n";
	}
}

