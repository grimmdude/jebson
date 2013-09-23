<!DOCTYPE html>
<html>
	<head>
		<title><?php echo self::$pageData['title']; ?></title>
		<meta name="description" content="<?php echo self::$pageData['description']; ?>" />
		<meta name="keywords" content="<?php echo self::$pageData['keywords']; ?>" />
		<link rel="canonical" href="http://<?php echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" />
		<link rel="stylesheet" href="/assets/css/bootstrap.css" type="text/css" media="all" />
		<link rel='stylesheet'  href='/assets/css/all.css' type='text/css' media='all' />
		<link rel="shortcut icon" href="/assets/img/favicon.ico" />
		<script src="/assets/js/jquery.js"></script>
		</head>
		<body>
			<div class="container">
				<header>
					<h1><a id="logo" href="/">Grimmdude</a></h1>
				</header>
				<nav>
					<ul>
						<li><a href="/web-development">Web Development</a></li>
						<li><a href="/music">Music</a></li>
						<li><a href="/blog">Blog</a></li>
						<li><a href="/contact">Contact</a></li>
					</ul>
				</nav>
				<div class="row">
					<div class="col-md-8">
