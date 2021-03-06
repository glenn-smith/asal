<?php require_once('../Connections/JLS_conn.php'); ?><?php
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

$maxRows_rs_adoptions_bytype = 10;
$pageNum_rs_adoptions_bytype = 0;
if (isset($_GET['pageNum_rs_adoptions_bytype'])) {
  $pageNum_rs_adoptions_bytype = $_GET['pageNum_rs_adoptions_bytype'];
}
$startRow_rs_adoptions_bytype = $pageNum_rs_adoptions_bytype * $maxRows_rs_adoptions_bytype;

mysql_select_db($database_JLS_conn, $JLS_conn);
$query_rs_adoptions_bytype = "SELECT animals.animal_type AS type, COUNT(*) FROM animals WHERE animals.adopt_date>'20071231' GROUP BY animals.animal_type ";
$query_limit_rs_adoptions_bytype = sprintf("%s LIMIT %d, %d", $query_rs_adoptions_bytype, $startRow_rs_adoptions_bytype, $maxRows_rs_adoptions_bytype);
$rs_adoptions_bytype = mysql_query($query_limit_rs_adoptions_bytype, $JLS_conn) or die(mysql_error());
$row_rs_adoptions_bytype = mysql_fetch_assoc($rs_adoptions_bytype);

if (isset($_GET['totalRows_rs_adoptions_bytype'])) {
  $totalRows_rs_adoptions_bytype = $_GET['totalRows_rs_adoptions_bytype'];
} else {
  $all_rs_adoptions_bytype = mysql_query($query_rs_adoptions_bytype);
  $totalRows_rs_adoptions_bytype = mysql_num_rows($all_rs_adoptions_bytype);
}
$totalPages_rs_adoptions_bytype = ceil($totalRows_rs_adoptions_bytype/$maxRows_rs_adoptions_bytype)-1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
    <h1 align="left">HSFC Administration-State Report</h1>
    <table width="520" border="5">
      <tr>
        <th scope="col">Type</th>
        <th scope="col">Count</th>
      </tr>
      <?php do { ?>
        <tr>
          <td><?php echo $row_rs_adoptions_bytype['type']; ?></td>
          <td><?php echo $row_rs_adoptions_bytype['COUNT(*)']; ?></td>
        </tr>
        <?php } while ($row_rs_adoptions_bytype = mysql_fetch_assoc($rs_adoptions_bytype)); ?>
    </table>
    <p align="left">&nbsp;</p>
    <p align="left"><br />
    </p>
    <p align="left">&nbsp;</p>
    <p align="left">&nbsp;</p>
  <!-- InstanceEndEditable --></div>
</div>
<div id="footer">&copy;2009 Afghan Stray Animal League and JLS Consulting, Inc </div>
</body><!-- InstanceEnd -->