---
title: Contact Form
keywords: Jebson CMS, wordpress alternative, fast loading, easy setup
description: Jebson is a very lightweight databaseless CMS that is geared towards simplicity and swift load times
contact:true
---
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['h'])) {
	if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		if (!empty($_POST['subject']) && !empty($_POST['message'])) {
			mail("grimmdude@gmail.com", $_POST['subject'], $_POST['message'], "From: contact@grimmdude.com" );
		}
		else {
			$error = 'Please provide both a subject and a message.';
		}
	}
	else {
		$error = 'Please provide a valid email address for me to respond to.';
	}	
}
?>
<?php if (isset($error)): ?>
	<p><?php echo $error; ?></p>
<?php endif ?>
<form action="/contact" method="post">
	<p><input type="text/submit/hidden/button" name="email" id="email" placeholder="your email" value="<?php echo array_key_exists('email', $_POST) ? $_POST['email'] : ''; ?>" /></p>
	<p><input type="text/submit/hidden/button" name="subject" id="subject" placeholder="subject" /></p>
	<p><input type="text/submit/hidden/button" name="message" id="email" placeholder="message" /></p>
	<p><input type="submit" value="Continue &rarr;"></p>
	<label style="display:block;position:absolute;left:-9999px">
	  Please leave this checkbox blank
	  <input type="checkbox" name="h" value="1" />
	</label>
</form>