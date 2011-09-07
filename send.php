<?php
require('../../../wp-load.php');
$msg = 'error';
$mailto = null;
$mailto = get_bloginfo('admin_email');
if ($mailto)
{
	$name = stripcslashes($_POST['name']);
	$email = stripcslashes($_POST['email']);
	$subject = stripcslashes($_POST['subject']);	
	$message = nl2br(stripcslashes($_POST['message']));


	$body = "

		Message:
		".$message." 
		\n
		Name: ".$name." 
		E-mail: ".$email." 
		\n
		Sending IP: ".$_SERVER['REMOTE_ADDR']." 
		Sending Script: ".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].";

	";

	$subject = 'Feedback: '.$subject;

	if (mail($mailto , $subject, $body))
	{
		$msg = 'success';
	}
}

print($msg);

if (isset($_GET['debug']))
{
	echo nl2br($body) ;
}
?>