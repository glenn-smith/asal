<?php require_once('../Connections/JLS_conn.php'); ?>
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

mysql_select_db($database_JLS_conn, $JLS_conn);
$query_rs_intakes = "SELECT * FROM animals WHERE animals.intake_date>'2007-12-31' ORDER BY animals.animal_type, animals.source, animals.name";
$rs_intakes = mysql_query($query_rs_intakes, $JLS_conn) or die(mysql_error());
$row_rs_intakes = mysql_fetch_assoc($rs_intakes);
$totalRows_rs_intakes = mysql_num_rows($rs_intakes);

mysql_select_db($database_JLS_conn, $JLS_conn);
$query_rs_adoptions = "SELECT animals.animal_id, animals.name, animals.animal_type, animals.location, adopters.adopter_id, adopters.`last`, adopters.`first`, adopters.adoption_date, animals.adopter_id FROM animals, adopters WHERE adopters.adoption_date>'2007-12-31' AND animals.adopter_id=adopters.adopter_id ORDER BY animals.animal_type, animals.location, animals.name";
$rs_adoptions = mysql_query($query_rs_adoptions, $JLS_conn) or die(mysql_error());
$row_rs_adoptions = mysql_fetch_assoc($rs_adoptions);
$totalRows_rs_adoptions = mysql_num_rows($rs_adoptions);

mysql_select_db($database_JLS_conn, $JLS_conn);
$query_rs_euthanized = "SELECT * FROM animals WHERE status = 'Euthanized' AND death_date>2008-01-01 ORDER BY animals.animal_type, animals.name";
$rs_euthanized = mysql_query($query_rs_euthanized, $JLS_conn) or die(mysql_error());
$row_rs_euthanized = mysql_fetch_assoc($rs_euthanized);
$totalRows_rs_euthanized = mysql_num_rows($rs_euthanized);

mysql_select_db($database_JLS_conn, $JLS_conn);
$query_rs_died = "SELECT * FROM animals WHERE animals.death_date>'2007-12-31' AND animals.status='Died' ORDER BY animals.animal_type, animals.name";
$rs_died = mysql_query($query_rs_died, $JLS_conn) or die(mysql_error());
$row_rs_died = mysql_fetch_assoc($rs_died);
$totalRows_rs_died = mysql_num_rows($rs_died);
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
    <h1 align="left">HSFC Administration - Disposition (2008) </h1>
    <h2 align="center">Intakes (2008) <br />
    Total=<?php echo $totalRows_rs_intakes ?></h2>
    <table width="540" border="5" align="center">
      <tr>
        <th scope="col"><div align="center">From</div></th>
        <th scope="col"><div align="center">Name</div></th>
        <th scope="col"><div align="center">Date</div></th>
        <th scope="col">Type</th>
        <th scope="col"><div align="center">Location</div></th>
      </tr>
      <?php do { ?>
        <tr>
          <td><div align="center"><?php echo $row_rs_intakes['source']; ?></div></td>
          <td><div align="center"><a href="animal_change.php?animal_id=<?php echo $row_rs_intakes['animal_id']; ?>"><?php echo $row_rs_intakes['name']; ?></a></div></td>
          <td><div align="center"><?php echo $row_rs_intakes['intake_date']; ?></div></td>
          <td><?php echo $row_rs_intakes['animal_type']; ?></td>
          <td><div align="center"><?php echo $row_rs_intakes['location']; ?></div></td>
        </tr>
        <?php } while ($row_rs_intakes = mysql_fetch_assoc($rs_intakes)); ?>
    </table>
    <h2 align="center">Adoptions<br />
Total = <?php echo $totalRows_rs_adoptions ?></h2>
    <table width="520" border="5" align="center">
      <tr>
        <th valign="middle" scope="col"><p><a href="adopter_change.php?adopter_id=<?php echo $row_rs_adoptions['adopter_id']; ?>"></a> Type</p></th>
        <th scope="col">Name </th>
        <th scope="col">Location</th>
        <th scope="col">ID</th>
        <th scope="col">Date</th>
        <th scope="col"><a href="animal_change.php?animal_id=<?php echo $row_rs_adoptions['animal_id']; ?>"></a>First</th>
        <th scope="col">Last</th>
        <th scope="col">ID</th>
      </tr>
      <?php do { ?>
      <tr>
        <td><?php echo $row_rs_adoptions['animal_type']; ?></td>
        <td><a href="adopter_change.php?adopter_id=<?php echo $row_rs_adoptions['adopter_id']; ?>"><?php echo $row_rs_adoptions['name']; ?></a></td>
        <td><?php echo $row_rs_adoptions['location']; ?></td>
        <td><?php echo $row_rs_adoptions['animal_id']; ?></td>
        <td><?php echo $row_rs_adoptions['adoption_date']; ?></td>
        <td><a href="adopter_change.php?adopter_id=<?php echo $row_rs_adoptions['adopter_id']; ?>"><?php echo $row_rs_adoptions['first']; ?></a></td>
        <td><?php echo $row_rs_adoptions['last']; ?></td>
        <td><?php echo $row_rs_adoptions['adopter_id']; ?></td>
      </tr>
      <?php } while ($row_rs_adoptions = mysql_fetch_assoc($rs_adoptions)); ?>
    </table>
    <h2 align="center">Euthanized (2008)<br />
Total=<?php echo $totalRows_rs_euthanized ?></h2>
    <table width="540" border="5" align="center">
      <tr>
        <th scope="col"><div align="center">Name</div></th>
        <th scope="col"><div align="center">Date</div></th>
        <th scope="col">Type</th>
        <th scope="col">Location</th>
      </tr>
      <?php do { ?>
      <tr>
        <td><div align="center"><a href="animal_change.php?animal_id=<?php echo $row_rs_euthanized['animal_id']; ?>"><?php echo $row_rs_euthanized['name']; ?></a></div></td>
        <td><div align="center"><?php echo $row_rs_euthanized['death_date']; ?></div></td>
        <td><?php echo $row_rs_euthanized['animal_type']; ?></td>
        <td><div align="center"><?php echo $row_rs_euthanized['location']; ?></div></td>
      </tr>
      <?php } while ($row_rs_euthanized = mysql_fetch_assoc($rs_euthanized)); ?>
    </table>
    <h2 align="center">Died (2008)<br />
      Total=<?php echo $totalRows_rs_died ?></h2>
    <table width="540" border="5" align="center">
      <tr>
        <th scope="col"><div align="center">Name</div></th>
        <th scope="col"><div align="center">Date</div></th>
        <th scope="col">Type</th>
        <th scope="col">Location</th>
      </tr>
      <?php do { ?>
      <tr>
        <td><div align="center"><a href="animal_change.php?animal_id=<?php echo $row_rs_euthanized['animal_id']; ?>"><?php echo $row_rs_died['name']; ?></a></div></td>
        <td><div align="center"><?php echo $row_rs_died['death_date']; ?></div></td>
        <td><?php echo $row_rs_died['animal_type']; ?></td>
        <td><div align="center"><?php echo $row_rs_died['location']; ?></div></td>
      </tr>
      <?php } while ($row_rs_euthanized = mysql_fetch_assoc($rs_euthanized)); ?>
    </table>
    <p align="left"></p>
    <p>&nbsp;</p>
    <p align="left">&nbsp;</p>
  <!-- InstanceEndEditable --></div>
</div>
<div id="footer">&copy;2009 Afghan Stray Animal League and JLS Consulting, Inc </div>
</body><!-- InstanceEnd -->