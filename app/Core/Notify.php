<?php

require_once('Mail.php');

class Notify{
	// construct
	public function __construct($subject = null , $msg = null){
		
		$mail = new Mail;
		$values = array(
					"to"		=>	"elmeftouhi@gmail.com",
					"message" 	=>	($msg == null)? "null": $msg,
					"subject" 	=>	($subject == null)? "null": $subject
					);
		
		$mail->send($values);
	}
	
}