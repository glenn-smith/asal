<?php 
/*************************************\
To optimize the tables within a database:

* Upload this file to the blog's folder and visit it.
* Enter in the DB information and click [Optimize Tables]

\*************************************/
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>MySQL Table Optimizer</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<h3>MySQL Table Optimizer</h3>

<?
$host=$_REQUEST['host'];
$username=$_REQUEST['username'];
$password=$_REQUEST['password'];
$db_name=$_REQUEST['database'];

if ($host == ""){	//Enter in DB information
	echo "<form method='get' action=''>" .
		"SQL Server: <input name='host'><br>" .
		"Username: <input name='username'><br>" .
		"Password: <input name='password' type = 'password' ><br>" .
		"DB Name: <input name='database'><br>" .
		"<input value='Optimize Tables' type='submit'>" .
		"</form>";
} else {	//Optimize IT
	// Connecting, selecting database
	$sqlconn = mysql_connect($host, $username, $password) or die('Could not connect: ' . mysql_error());
	mysql_select_db($db_name) or die ("Unable to select database: ". mysql_error());
	
	$result = mysql_list_tables($db_name);
	
	while ($row = mysql_fetch_row($result)) {
		$table = $row[0];
		$query = "OPTIMIZE TABLE `" . $table . "`";
		$repaired = mysql_query($query) or die("<b>Error: ". mysql_error() . "</b>");
		echo $table . " optimized.<br>";
	}
	
	// Closing connection
	mysql_close($sqlconn);
	
	echo "<br><b>All tables optimized!</b><br>";
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
