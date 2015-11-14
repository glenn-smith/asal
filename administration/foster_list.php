<?php require_once('../Connections/ASAL.php'); ?>
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

$MM_restrictGoTo = "log_in_fails.php";
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

mysql_select_db($database_ASAL, $ASAL);
$query_rs_foster = "SELECT foster.foster_id, foster.`first`, foster.`last`, animals.animal_type, animals.name, animals.animal_id, animals.intake_date, animals.image_path, animals.status, animals.breed, animals.weight, animals.color, animals.sex, animals.birth_date FROM foster, animals WHERE animals.foster_id=foster.foster_id AND animals.status='Available' ORDER BY animals.name";
$rs_foster = mysql_query($query_rs_foster, $ASAL) or die(mysql_error());
$row_rs_foster = mysql_fetch_assoc($rs_foster);
$totalRows_rs_foster = mysql_num_rows($rs_foster);

$currentPage = $_SERVER["PHP_SELF"];

$queryString_rs_foster = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rs_foster") == false && 
        stristr($param, "totalRows_rs_foster") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rs_foster = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rs_foster = sprintf("&totalRows_rs_foster=%d%s", $totalRows_rs_foster, $queryString_rs_foster);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/asal_admin.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>

<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>ASAL Fostered Animals</title>
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
      <a href="dispositions_2009.php">Dispositions (2009)</a><br />
      <a href="adopters.php">Adopter List</a><br />
     <a href="fosters.php">Foster List</a><br />
    <a href="foster_list.php">Animals Fostered List</a><br />
 	  <a href="success_stories.php">Success List </a><br /> 
      <a href="log_in.php">Log Off </a><br />
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
    <h1 align="left">ASAL Administration - Fostered Animals List</h1>
    <p align="left">
<table border="1" align="center">
      <tr>
        <td><div align="center">Name</div></td>
        <td><div align="center">Breed</div></td>
        <td><div align="center">Weight</div></td>
        <td><div align="center">Sex</div></td>
        <td><div align="center">Birth</div></td>
        <td><div align="center">Color</div></td>
        <td><div align="center">Foster</div></td>
      </tr>
      <?php do { ?>
      <tr>
        <td><a href="animal_change.php?animal_id=<?php echo $row_rs_foster['animal_id']; ?>"><?php echo $row_rs_foster['name']; ?></a></td>
        <td><?php echo $row_rs_foster['breed']; ?></td>
        <td><a href="foster_edit.php?foster_id=<?php echo $row_rs_foster['foster_id']; ?>"><?php echo $row_rs_foster['weight']; ?></a></td>
        <td><?php echo $row_rs_foster['sex']; ?></td>
        <td><?php echo $row_rs_foster['birth_date']; ?></td>
        <td><?php echo $row_rs_foster['color']; ?></td>
        <td><a href="foster_edit.php?foster_id=<?php echo $row_rs_foster['foster_id']; ?>"><?php echo $row_rs_foster['last']; ?></a></td>
        </tr>
      <?php } while ($row_rs_foster = mysql_fetch_assoc($rs_foster)); ?>
    </table>
    <div align="center"><br />
Total Foster Animals - <?php echo $totalRows_rs_foster ?> <br>
    </div>
    <p align="left">&nbsp;</p>
  <!-- InstanceEndEditable --></div>
</div>
<div id="footer">&copy;2009 Afghan Stray Animal League and JLS Consulting, Inc </div>
</body><!-- InstanceEnd -->