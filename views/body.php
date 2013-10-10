<?php self::renderContent(); ?>
<?php if (is_numeric(self::$pageNumber)): ?>
	<p>Page <?php echo self::$pageNumber; ?></p>
	<p>Total Pages <?php echo self::$totalPages; ?></p>
<?php endif; ?>