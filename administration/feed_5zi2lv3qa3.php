<?php ?><?php
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
?><?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

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
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
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

mysql_select_db("hsfc", mysql_pconnect("205.178.146.29","jls","JLS9079"));
$query_rs_feed_chart = "SELECT animal_id, name, intake_date, animal_type, breed, weight, color, sex, birth_date, image_path, FVRCP_due, rabies_due, other_medical_info, location, status, HSFC_Bldg_room, feeding_instructions FROM animals WHERE animals.location='HSFC Fairfax' AND animals.status='Available' ORDER BY animals.HSFC_Bldg_room, animals.name";
$rs_feed_chart = mysql_query($query_rs_feed_chart, mysql_pconnect("205.178.146.29","jls","JLS9079")) or die(mysql_error());
$row_rs_feed_chart = mysql_fetch_assoc($rs_feed_chart);
$totalRows_rs_feed_chart = mysql_num_rows($rs_feed_chart);
?><!--MMDW 5 --><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
    <h1 mmdw="24"  align="left">HSFC Administration - Feeding Chart </h1>
    <table mmdw="25"  width="520" border="5">
      <!--MMDW 6 --><?php do { ?><!--MMDW 7 -->
        <tr>
          <th mmdw="26"  scope="col"><a mmdw="27"  href="animal_change.php?animal_id=<?php echo $row_rs_feed_chart['animal_id']; ?>"><!--MMDW 8 --><?php echo $row_rs_feed_chart['name']; ?><!--MMDW 9 --></a></th>
          <th mmdw="28"  scope="col"><var>Age (Yrs):
            <!--MMDW 10 --><?php
$years = floor((time() - strtotime($row_rs_feed_chart['birth_date']))/86400/365);
print("$years \n");
?><!--MMDW 11 -->
          </var></th>
          <th mmdw="29"  scope="col"><var>Sex:<!--MMDW 12 --><?php echo $row_rs_feed_chart['sex']; ?><!--MMDW 13 --></var></th>
        </tr>
        <tr>
          <td>Room:<!--MMDW 14 --><?php echo $row_rs_feed_chart['HSFC_Bldg_room']; ?><!--MMDW 15 --> </td>
          <td>Intake Date =<!--MMDW 16 --><?php echo $row_rs_feed_chart['intake_date']; ?><!--MMDW 17 --></td>
          <td>Breed:<!--MMDW 18 --><?php echo $row_rs_feed_chart['breed']; ?><!--MMDW 19 --></td>
        </tr>
        <tr>
          <td>FVRCP due: <!--MMDW 20 --><?php echo $row_rs_feed_chart['FVRCP_due']; ?><!--MMDW 21 --></td>
          <td>Rabies due: <!--MMDW 22 --><?php echo $row_rs_feed_chart['rabies_due']; ?><!--MMDW 23 --></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td mmdw="30"  colspan="3">Other Medical: <!--MMDW 24 --><?php echo $row_rs_feed_chart['other_medical_info']; ?><!--MMDW 25 --></td>
        </tr>
        <tr>
          <td mmdw="31"  colspan="3">Menu: <!--MMDW 26 --><?php echo $row_rs_feed_chart['feeding_instructions']; ?><!--MMDW 27 --></td>
        </tr>
        <tr>
          <td mmdw="32"  colspan="3"><div mmdw="33"  align="center"><img mmdw="34"  src="<?php echo $row_rs_feed_chart['image_path']; ?>" /></div></td>
        </tr>
        <!--MMDW 28 --><?php } while ($row_rs_feed_chart = mysql_fetch_assoc($rs_feed_chart)); ?><!--MMDW 29 -->
    </table>
    <p mmdw="35"  align="left">&nbsp;</p>
    <p mmdw="36"  align="left">&nbsp;</p>
  <!-- InstanceEndEditable --></div>
</div>
<div id="footer">&copy;2009 Afghan Stray Animal League and JLS Consulting, Inc </div>
</body><!-- InstanceEnd -->