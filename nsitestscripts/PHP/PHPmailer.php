<html>
<head>
  <title>PHP Form Mail Test Script</title>
</head>
<body>
<h3>PHP Form Mail Test Script</h3>
<?PHP
	$email = $_REQUEST['email'];
	$body = $_REQUEST['body'];
	$subject = $_REQUEST['subject'];
	$from = $_REQUEST['from'];
	$sendusing = $_REQUEST['sendusing'];
	$usehtml = $_REQUEST['usehtml'];

	if ($sendusing == "mail") {
		$header = "From: " . $from . "\n";
		if ($usehtml == "yes") $header .= "Content-Type:text/html\n"; else $header .= "Content-Type:text/plain\n";
		if (mail($email, $subject, $body, $header))  echo "SUCCESS... Email sent using Mail() function";else  echo "FAILED TO SEND!";
		echo "<br>" . date('l dS \of F Y h:i:s A') . "<br><br>";
	} else if ($sendusing == "pear") {
		if (mail_this($email, $subject, $body, $from))  echo "SUCCESS... Email sent to using PEAR module";else  echo "FAILED TO SEND!";
		echo "<br>" . date('l dS \of F Y h:i:s A') . "<br><br>";
	} else {
		$email = "Type Email Address";
		$body = "FormMail Test";
		$subject = "Test Email";
		$from = "custserv@networksolutions.com";
		$sendusing = "mail";
	}
?>

<table><form method="post" action="" name="sendmailtest">
	<tr><td>TO:</td><td><input value="<?echo $email;?>" name="email" size="35"></td></tr>
	<tr><td>FROM:</td><td><input value="<?echo $from;?>" name="from" size="35"></td></tr>
    <tr><td>Subject:</td><td><input value="<?echo $subject;?>" name="subject" size="35"></td></tr>
	<tr><td colspan=2>Body:<br><textarea  cols="45" rows="3" name="body"><?echo $body;?></textarea></tr>
	<tr><td>Send Using:</td>
	<td><input name='sendusing' value='mail' type='radio'<?if ($sendusing == "mail") echo " checked='checked'";?>><b>Mail() Function</b><br>
	<input name='sendusing' value='pear' type='radio'<?if ($sendusing == "pear") echo " checked='checked'";?>><b>PEAR Module</b></td></tr>
	<tr><td>Use HTML <input name="usehtml" value="yes" type="checkbox"<?if ($usehtml == "yes") echo " checked='checked'";?>></td>
	<td align=right><input name="submit" value="Send Email" type="submit"></td></tr>
</form></table>

<?
function mail_this($email, $subject, $body, $from) {
	include('Mail.php');

	$headers['From']    = $from;
	$headers['To']      = $email;
	$headers['Subject'] = $subject;
	if ($usehtml == "yes") $headers['Content-type'] = "text/html"; else $headers['Content-type'] = "text/plain";

	$params["host"] = "localhost";
	$params["port"] = "25";
	$params["auth"] = false;
	$params["username"] = "username";
	$params["password"] = "password";

	// Create the mail object using the Mail::factory method
	$mail_object =& Mail::factory('smtp', $params);

	$mail_object->send($email, $headers, $body);

	if (PEAR::isError($mail_object)) {
  		return false;
 	} else {
  		return true;
 	}
}
?>
</body>
<!--
#######################################################
##                                                   ##
## NOTE:  This is a Network Solutions Test Script.   ##
## Any modifications to this script are not the      ##
## responsibility of Network Solutions as this script## 
## was generated only to ensure that your service is ##
## functioning properly.                             ##
##                                                   ##
#######################################################
-->
</html> 
