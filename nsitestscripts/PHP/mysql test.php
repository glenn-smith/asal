<html>
<head>
<meta http-equiv="Content-Language" content="en-us">
</head>
<center>
<?php

    $IPAddress = $_REQUEST['IPAddress']; 
    $Username = $_REQUEST['Username'];
    $Password = $_REQUEST['Password'];
    	if ($IPAddress == "") {
			$IPAddress = "Type IP Here";
			$Username = "";
			$Password = "";
	} 
	else {
		connection($IPAddress, $Username, $Password);
	}
		


?>

<form method="POST" action="">
	
	<p>MySQL IP: <input type="text" value="<?echo $IPAddress;?>" name = "IPAddress" size="20"><br>
	MySQL Username: <input type="text" value="<?echo $Username;?>" name = "Username" size="20"><br>
	MySQL Password: <input type="password" value="<?echo $Password;?>" name = "Password" size="20"></p>
	<p><input type="submit" value="Submit" value="B1"><input type="reset" value="Reset" name="B2"></p>
</form>



<?
function connection($IPAddress, $Username, $Password){
if (mysql_connect ($IPAddress,$Username,$Password)){

echo "<font color=green size = 4><b>Connected Fine</b> Scoll down to try another</font> <br>";

}else{

echo "<b>Error while connecting</b>";

}
}
			$IPAddress = "Type IP Here";
			$Username = "";
			$Password = "";

?>
</center>
</html>