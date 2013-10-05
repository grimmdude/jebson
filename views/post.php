<article>
	<?php if (!empty(self::$request)): ?>
		<h1><?php echo self::$pageData['title']; ?></h1>
	<?php endif ?>
	<?php echo self::$content; ?>
</article>