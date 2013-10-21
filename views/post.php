<article>
	<?php if (!empty(self::$request)): // Don't show title on homepage ?>
		<h1><?php echo self::$pageData['title']; ?></h1>
	<?php endif ?>
	<?php if (self::$date): ?>
		<p><?php echo date('m/d/Y', strtotime(self::$date)); ?></p>
	<?php endif ?>
	<?php echo self::$content; ?>
</article>