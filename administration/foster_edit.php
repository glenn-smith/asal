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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE foster SET `first`=%s, `last`=%s, address1=%s, address2=%s, city=%s, `state`=%s, zip=%s, email=%s, home_phone=%s, work_phone=%s, cell_phone=%s, occupation=%s, employer=%s, employer_address=%s, animal_experience=%s, animal_type_1=%s, breed_1=%s, birth_1=%s, sex_1=%s, Obtained_1=%s, animal_type_2=%s, breed_2=%s, birth_2=%s, sex_2=%s, obtained_2=%s, animal_type_3=%s, breed_3=%s, birth_3=%s, sex_3=%s, obtained_3=%s, animal_type_4=%s, breed_4=%s, birth_4=%s, sex_4=%s, obtained_4=%s, current_vet=%s, vet_phone=%s, previous_animals_description=%s, previous_vet=%s, previous_vet_phone=%s WHERE foster_id=%s",
                       GetSQLValueString($_POST['first'], "text"),
                       GetSQLValueString($_POST['last'], "text"),
                       GetSQLValueString($_POST['address1'], "text"),
                       GetSQLValueString($_POST['address2'], "text"),
                       GetSQLValueString($_POST['city'], "text"),
                       GetSQLValueString($_POST['state'], "text"),
                       GetSQLValueString($_POST['zip'], "int"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['home_phone'], "text"),
                       GetSQLValueString($_POST['work_phone'], "text"),
                       GetSQLValueString($_POST['cell_phone'], "int"),
                       GetSQLValueString($_POST['occupation'], "text"),
                       GetSQLValueString($_POST['employer'], "text"),
                       GetSQLValueString($_POST['employer_address'], "text"),
                       GetSQLValueString($_POST['animal_experience'], "text"),
                       GetSQLValueString($_POST['animal_type_1'], "text"),
                       GetSQLValueString($_POST['breed_1'], "text"),
                       GetSQLValueString($_POST['birth_1'], "date"),
                       GetSQLValueString($_POST['sex_1'], "text"),
                       GetSQLValueString($_POST['Obtained_1'], "text"),
                       GetSQLValueString($_POST['animal_type_2'], "text"),
                       GetSQLValueString($_POST['breed_2'], "text"),
                       GetSQLValueString($_POST['birth_2'], "date"),
                       GetSQLValueString($_POST['sex_2'], "text"),
                       GetSQLValueString($_POST['obtained_2'], "text"),
                       GetSQLValueString($_POST['animal_type_3'], "text"),
                       GetSQLValueString($_POST['breed_3'], "text"),
                       GetSQLValueString($_POST['birth_3'], "date"),
                       GetSQLValueString($_POST['sex_3'], "text"),
                       GetSQLValueString($_POST['obtained_3'], "text"),
                       GetSQLValueString($_POST['animal_type_4'], "text"),
                       GetSQLValueString($_POST['breed_4'], "text"),
                       GetSQLValueString($_POST['birth_4'], "date"),
                       GetSQLValueString($_POST['sex_4'], "text"),
                       GetSQLValueString($_POST['obtained_4'], "text"),
                       GetSQLValueString($_POST['current_vet'], "text"),
                       GetSQLValueString($_POST['vet_phone'], "text"),
                       GetSQLValueString($_POST['previous_animals_description'], "text"),
                       GetSQLValueString($_POST['previous_vet'], "text"),
                       GetSQLValueString($_POST['previous_vet_phone'], "text"),
                       GetSQLValueString($_POST['foster_id'], "int"));

  mysql_select_db($database_ASAL, $ASAL);
  $Result1 = mysql_query($updateSQL, $ASAL) or die(mysql_error());

  $updateGoTo = "foster_list.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_rs_foster = "-1";
if (isset($_GET['foster_id'])) {
  $colname_rs_foster = (get_magic_quotes_gpc()) ? $_GET['foster_id'] : addslashes($_GET['foster_id']);
}
mysql_select_db($database_ASAL, $ASAL);
$query_rs_foster = sprintf("SELECT * FROM foster WHERE foster_id = %s", GetSQLValueString($colname_rs_foster, "int"));
$rs_foster = mysql_query($query_rs_foster, $ASAL) or die(mysql_error());
$row_rs_foster = mysql_fetch_assoc($rs_foster);
$totalRows_rs_foster = mysql_num_rows($rs_foster);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/asal_admin.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>

<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>HSFC Administration - Foster Edit</title>
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
    <h1 align="left">HSFC Administration <br />
      Foster Edit
    </h1>
    <p align="left">&nbsp;</p>
    
    <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
      <table align="center">
        <tr valign="baseline">
          <td nowrap align="right">Foster_id:</td>
          <td><?php echo $row_rs_foster['foster_id']; ?></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">First:</td>
          <td><input type="text" name="first" value="<?php echo $row_rs_foster['first']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Last:</td>
          <td><input type="text" name="last" value="<?php echo $row_rs_foster['last']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Address1:</td>
          <td><input type="text" name="address1" value="<?php echo $row_rs_foster['address1']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Address2:</td>
          <td><input type="text" name="address2" value="<?php echo $row_rs_foster['address2']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">City:</td>
          <td><input type="text" name="city" value="<?php echo $row_rs_foster['city']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">State:</td>
          <td><input type="text" name="state" value="<?php echo $row_rs_foster['state']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Zip:</td>
          <td><input type="text" name="zip" value="<?php echo $row_rs_foster['zip']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Email:</td>
          <td><input type="text" name="email" value="<?php echo $row_rs_foster['email']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Home_phone:</td>
          <td><input type="text" name="home_phone" value="<?php echo $row_rs_foster['home_phone']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Work_phone:</td>
          <td><input type="text" name="work_phone" value="<?php echo $row_rs_foster['work_phone']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Cell_phone:</td>
          <td><input type="text" name="cell_phone" value="<?php echo $row_rs_foster['cell_phone']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">&nbsp;</td>
          <td><input type="submit" value="Update record"></td>
        </tr>
      </table>
      <input type="hidden" name="MM_update" value="form1">
      <input type="hidden" name="foster_id" value="<?php echo $row_rs_foster['foster_id']; ?>">
    </form>
    <p>&nbsp;</p>
    <p align="left">&nbsp;</p>
  <!-- InstanceEndEditable --></div>
</div>
<div id="footer">&copy;2009 Afghan Stray Animal League and JLS Consulting, Inc </div>
</body><!-- InstanceEnd -->