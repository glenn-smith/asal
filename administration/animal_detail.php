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

$colname_rs_animals = "-1";
if (isset($_GET['animal_id'])) {
  $colname_rs_animals = (get_magic_quotes_gpc()) ? $_GET['animal_id'] : addslashes($_GET['animal_id']);
}
mysql_select_db($database_JLS_conn, $JLS_conn);
$query_rs_animals = sprintf("SELECT * FROM animals WHERE animal_id = %s", GetSQLValueString($colname_rs_animals, "int"));
$rs_animals = mysql_query($query_rs_animals, $JLS_conn) or die(mysql_error());
$row_rs_animals = mysql_fetch_assoc($rs_animals);
$totalRows_rs_animals = mysql_num_rows($rs_animals);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/admin.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>

<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>animal detail</title>
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
    <h1 align="left">HSFC Administration - Animal Detail <br />
    <a href="animal_change.php?animal_id=<?php echo $row_rs_animals['animal_id']; ?>">Edit Animal Detail</a></h1>
    <table width="550" border="5">
      <caption align="top">
	  <tr>
        <th scope="col"><p align="left">Name:<?php echo $row_rs_animals['name']; ?></p></th>
        <th scope="col"><p align="left">Status:<?php echo $row_rs_animals['status']; ?></p></th>
      </tr>
      <tr>
        <td><p>Intake Date: <?php echo $row_rs_animals['intake_date']; ?></p></td>
        <td><p>Source:<?php echo $row_rs_animals['source']; ?></p></td>
      </tr>
      <tr>
        <td><p>Altered:<?php echo $row_rs_animals['altered']; ?></p></td>
        <td><p>Breed:<?php echo $row_rs_animals['breed']; ?></p></td>
      </tr>
      <tr>
        <td><p>Color:<?php echo $row_rs_animals['color']; ?></p></td>
        <td><p>Sex:<?php echo $row_rs_animals['sex']; ?></p></td>
      </tr>
      <tr>
        <td><p>Weight:<?php echo $row_rs_animals['weight']; ?></p></td>
        <td><p>Medical:<?php echo $row_rs_animals['medical_record']; ?></p></td>
      </tr>
      <tr>
        <td><p>Born:<?php echo $row_rs_animals['birth_date']; ?></p></td>
        <td><p>Died:<?php echo $row_rs_animals['death_date']; ?></p></td>
      </tr>
      <tr>
        <td><p>&nbsp;</p></td>
        <td><p>Image Path : <?php echo $row_rs_animals['image_path']; ?></p></td>
      </tr>
      <tr>
        <td><p>Location:<?php echo $row_rs_animals['location']; ?></p></td>
        <td><p>HSFC_FAX_Room: <?php echo $row_rs_animals['HSFC_Bldg_room']; ?></p></td>
      </tr>
      <tr>
        <td colspan="2"><p align="center"><img src="<?php echo $row_rs_animals['image_path']; ?>" /></p>        </td>
      </tr>
      <tr>
        <td colspan="2">Description:<?php echo $row_rs_animals['description']; ?></td>
      </tr>
      <tr>
        <td colspan="2">Feeding Instructions: <?php echo $row_rs_animals['feeding_instructions']; ?></td>
      </tr>
    </table>
    <p align="left"><a href="animal_change.php?animal_id=<?php echo $row_rs_animals['animal_id']; ?>"></a><br />
      <a href="animal_delete.php?animal_id=<?php echo $row_rs_animals['animal_id']; ?>">Delete Animal Record</a>  <br />
      <br />
      <br />
    </p>
    <!-- InstanceEndEditable --></div>
</div>
<div id="footer">&copy;2009 Afghan Stray Animal League and JLS Consulting, Inc </div>
</body><!-- InstanceEnd -->