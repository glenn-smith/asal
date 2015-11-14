<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_ASAL = "localhost";
$database_ASAL = "myDatabase";
$username_ASAL = "myDatabaseUser";
$password_ASAL = "myDatabaseUserPassword";
$ASAL = mysql_pconnect($hostname_ASAL, $username_ASAL, $password_ASAL) or trigger_error(mysql_error(),E_USER_ERROR); 
?>
