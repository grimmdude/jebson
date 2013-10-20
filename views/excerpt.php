<article>
	<h2><a href="<?php echo self::$slug; ?>"><?php echo self::$pageData['title']; ?></a></h2>
	<p><?php echo date('m/d/Y', strtotime(self::$date)); ?></p>
	<?php echo self::$excerpt; ?>
	<p><a href="<?php echo self::$slug; ?>">Read More...</a></p>
</article>