<?php
self::$pageData['title'] = 'Contact Form';
self::$pageData['keywords'] = 'Jebson CMS, wordpress alternative, fast loading, easy setup';
self::$pageData['description'] = 'Jebson is a very lightweight databaseless CMS that is geared towards simplicity and swift load times';
?>

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
<form action="/contact" method="post" class="form-horizontal" role="form">
	<div class="form-group">
		<label for="email" class="col-sm-2 control-label">Your Email:</label>
		<div class="col-sm-5">
			<input type="email" class="form-control" name="email" id="email" value="<?php echo array_key_exists('email', $_POST) ? $_POST['email'] : ''; ?>" />
		</div>
	</div>
	<div class="form-group">
		<label for="subject" class="col-sm-2 control-label">Subject:</label>
		<div class="col-sm-5">
			<input type="text" class="form-control" name="subject" id="subject" value="<?php echo array_key_exists('subject', $_POST) ? $_POST['subject'] : ''; ?>" />
		</div>
	</div>
	<div class="form-group">
		<label for="message" class="col-sm-2 control-label">Message:</label>
		<div class="col-sm-5">
			<textarea id="message" name="message" class="form-control" rows="3"><?php echo array_key_exists('message', $_POST) ? $_POST['message'] : ''; ?></textarea>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-5">
			<button type="submit" class="btn btn-default">Send</button>
		</div>
	</div>
	<label style="display:block;position:absolute;left:-9999px">
	  Please leave this checkbox blank
	  <input type="checkbox" name="h" value="1" />
	</label>
</form>