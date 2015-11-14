<?php require_once('../Connections/JLS_conn.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "log_in.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

mysql_select_db($database_JLS_conn, $JLS_conn);
$query_rs_shots = "SELECT animal_id, name, intake_date, animal_type, FVRCP_due, rabies_due, status FROM animals WHERE animals.status='Available' AND animals.FVRCP_due>0 OR animals.status='Available' AND animals.rabies_due  >0 OR animals.status='Detention' AND animals.rabies_due >0 OR animals.status='Detention' AND animals.FVRCP_due>0 ORDER BY animals.animal_type,  animals.FVRCP_due";
$rs_shots = mysql_query($query_rs_shots, $JLS_conn) or die(mysql_error());
$row_rs_shots = mysql_fetch_assoc($rs_shots);
$totalRows_rs_shots = mysql_num_rows($rs_shots);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/admin.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>

<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>HSFC Administration</title>
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable -->
<link href="../CSS/2col_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="holder">
  <div id="header"><img src="../images/ASAL_home_banner.gif" alt="HSFC" width="755" height="145" border="0" /></div>
  <div id="nav"> 
    <p><a href="animal_inventory.php">ANIMAL INVENTORY </a></p>
    <p> <strong>FORMS</strong><br />
      <a href="animal_add.php">Add Animals</a><br />
      <a href="adopter_add.php">Add Adopters</a><br />
      <a href="foster_add.php">Add Foster</a><br />
      <a href="success_add.php">Add Success</a><br />
    </p>
    </p>
    <p>      <strong>REPORTS</strong><a href="dispositions_2008.php"><br /></a><br />  
      <a href="adopters.php">Adopter List </a><br />
 	  <a href="success_stories.php">Success List </a><br /> 
      <a href="../admin_login.php">Log Off </a><br />
      <br />
      <strong><a href="help.php">HELP</a></strong></p>
    <p><strong><a href="../index.php">Web Site </a></strong><a href="../index.php">- Emerging</a><br />
      <a href="../cats_foster_1.php"><br />
      </a><a href="../dogs_1.php">Dogs (Live)</a><br />
    </p>
    <p><br />
    </p>
  </div>
  <div id="content_nav"><!-- InstanceBeginEditable name="content" -->
    <h1 align="left">HSFC Administration - Shots Due/Overdue<br />
      Only Animals with FVRCP or Rabies Shots Due/Overdue are Shown<br />
      Entries can be Corrected by Clicking on the Animals Name
    </h1>
    <table width="520" border="5">
      <tr>
        <th scope="col"><div align="center">ID</div></th>
        <th scope="col"><div align="center">Name</div></th>
        <th scope="col"><div align="center">Type</div></th>
        <th scope="col"><div align="center">Intake</div></th>
        <th scope="col"><p align="center">FVRCP<br />
        Due</p>        </th>
        <th scope="col"><p align="center">&nbsp;</p>
          <p align="center">Days<br />
          Due</p>
        <p align="center">&nbsp;</p></th>
        <th scope="col"><div align="center">Rabies<br />
        Due</div></th>
        <th scope="col"><div align="center">Days<br />
        Due</div></th>
      </tr>
      <?php do { ?>
        <tr>
          <td><?php echo $row_rs_shots['animal_id']; ?></td>
          <td><a href="animal_change.php?animal_id=<?php echo $row_rs_shots['animal_id']; ?>"><?php echo $row_rs_shots['name']; ?></a></td>
          <td><?php echo $row_rs_shots['animal_type']; ?></td>
          <td><?php echo $row_rs_shots['intake_date']; ?></td>
          <td><?php echo $row_rs_shots['FVRCP_due']; ?></td>
          <td><div align="center"><var>
            <?php
$days = floor((strtotime($row_rs_shots['FVRCP_due']) - time())/86400);
print("$days \n");
?>
          </var></div></td>
          <td><?php echo $row_rs_shots['rabies_due']; ?></td>
          <td><div align="center"><var>
            <?php
$days = floor((strtotime($row_rs_shots['rabies_due']) - time())/86400);
print("$days \n");
?>
            </var></div></td>
        </tr>
        <?php } while ($row_rs_shots = mysql_fetch_assoc($rs_shots)); ?>
    </table>
    <p align="left">&nbsp;Total Animals Displayed = <?php echo $totalRows_rs_shots ?> </p>
    <p align="left">&nbsp;</p>
  <!-- InstanceEndEditable --></div>
</div>
<div id="footer">&copy;2009 Afghan Stray Animal League and JLS Consulting, Inc </div>
</body><!-- InstanceEnd -->