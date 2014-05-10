<!DOCTYPE html>
<html>
	<head>
		<title><?php echo self::$pageData['title']; ?></title>
		<meta name="description" content="<?php echo self::$pageData['description']; ?>" />
		<meta name="keywords" content="<?php echo self::$pageData['keywords']; ?>" />
		<meta name="robots" content="index, follow" />
		<meta name="viewport" content="width=device-width, initial-scale=.9" />
		<link rel="canonical" href="http://<?php echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" />
		<?php echo Helpers::loadCSS('/assets/css/bootstrap.css'); ?>
		<?php echo Helpers::loadCSS('/assets/css/all.css'); ?>
		<link rel="shortcut icon" href="/assets/img/favicon.ico" />
		</head>
		<body>
			<div class="container row col-md-12">
				<header>
					<h1><a id="logo" href="/">Jebson</a></h1>
				</header>
				<nav>
					<ul>
						<li><a href="/blog">Blog</a></li>
						<li><a href="/sample-page">Sample Page</a></li>
						<li><a href="/sub/sample-page">Sample Sub Page</a></li>
					</ul>
				</nav>
				<div class="row">
					<div class="col-md-8">