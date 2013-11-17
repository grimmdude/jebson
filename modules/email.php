<?php

/**
* Email module
* @author Garrett Grimm
*/
class Email
{
	public static function sendEmail() {
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			mail("to_email", $_POST['subject'], $_POST['message'], "From: contact@grimmdude.com" );
			return true;
		}
	}
}