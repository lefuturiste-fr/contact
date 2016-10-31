<?php
/*
*	Send a simple email
*	Warning : This class does not allows you to send stable mails. Tis emails are sometimes often interpreted as spam.
*
*/
class mail {

	public $sender;
	public $nameSender;
	public $recipient;
	public $object;
	public $htmlMessage;

	public function sendMail() {

		$header = 'To:'.$this->recipient."\n";	 
		$header .= 'From: '.$this->nameSender.' <'.$this->sender.'>'."\n";
		$header .= 'Reply-to: '.$this->nameSender.' <'.$this->sender.'>'."\n";
		$header .= 'MIME-Version: 1.0'."\n";
		$header .= 'Content-type: text/html; charset=iso-8859-1' . "\n";

		$message = '
		<html>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title>'.$this->object.'</title>
		</head>
		<body>
			'.$this->htmlMessage.'
		</body>
		</html>
		';

		mail($this->recipient, $this->object, $message, $header);
	}
}