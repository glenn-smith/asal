<?php require_once('../Connections/JLS_conn_local.php'); ?>
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

$colname_rs_animals = "1";
if (isset($_GET['animal_id'])) {
  $colname_rs_animals = (get_magic_quotes_gpc()) ? $_GET['animal_id'] : addslashes($_GET['animal_id']);
}
mysql_select_db($database_JLS_conn, $JLS_conn);
$query_rs_animals = sprintf("SELECT animals.animal_id, animals.name, animals. adopter_id, animals.intake_date, animals.adopt_date, animals.animal_type, animals.breed, animals.color, animals.sex, animals.birth_date, animals.death_date, animals.medical_record, animals.image_path, animals.location, animals.status, adopters.adopter_id, adopters.`first`, adopters.animal_nu_name, adopters.`last`, adopters.address1, adopters.address2, adopters.city, adopters.`state`, adopters.zip, adopters.email, adopters.adoption_date, adopters.adoption_fee, animals.`description`, animals.altered FROM animals, adopters WHERE animals.animal_id=%s AND adopters.adopter_id=animals.adopter_id", GetSQLValueString($colname_rs_animals, "int"));
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
      <h1 align="left">ASAL Administration<br />
      Animal/Adopter Detail</h1>
    <table width="100%" border="1" bordercolor="#000000">
      <tr>
        <th scope="col">Animal</th>
        <th scope="col">Adopter</th>
      </tr>
      <tr>
        <td>Animal ID: <?php echo $row_rs_animals['animal_id']; ?></td>
        <td>Adopter ID: <?php echo $row_rs_animals['adopter_id']; ?></td>
      </tr>
      <tr>
        <td>Status:<?php echo $row_rs_animals['status']; ?></td>
        <td>First:<?php echo $row_rs_animals['first']; ?></td>
      </tr>
      <tr>
        <td>Name:<?php echo $row_rs_animals['name']; ?></td>
        <td>Last:<?php echo $row_rs_animals['last']; ?></td>
      </tr>
      <tr>
        <td>Breed:<?php echo $row_rs_animals['breed']; ?></td>
        <td>New Name: <?php echo $row_rs_animals['animal_nu_name']; ?></td>
      </tr>
      <tr>
        <td>Color:<?php echo $row_rs_animals['color']; ?></td>
        <td>Addr_1:<?php echo $row_rs_animals['address1']; ?></td>
      </tr>
      <tr>
        <td>Sex:<?php echo $row_rs_animals['sex']; ?></td>
        <td>Addr_2<?php echo $row_rs_animals['address2']; ?></td>
      </tr>
      <tr>
        <td>Born:<?php echo $row_rs_animals['birth_date']; ?></td>
        <td>City: <?php echo $row_rs_animals['city']; ?></td>
      </tr>
      <tr>
        <td>Died:<?php echo $row_rs_animals['death_date']; ?></td>
        <td>State:<?php echo $row_rs_animals['state']; ?></td>
      </tr>
      <tr>
        <td>Medical:<?php echo $row_rs_animals['medical_record']; ?></td>
        <td>Zip:<?php echo $row_rs_animals['zip']; ?></td>
      </tr>
      <tr>
        <td>Fee Paid: <?php echo $row_rs_animals['adoption_fee']; ?></td>
        <td>Email:<?php echo $row_rs_animals['email']; ?></td>
      </tr>
      <tr>
        <td><p align="center"><img src="<?php echo $row_rs_animals['image_path']; ?>" /></p>        </td>
        <td>Adopted_Date:<?php echo $row_rs_animals['adoption_date']; ?></td>
      </tr>
      <tr>
        <td colspan="2">Description:<?php echo $row_rs_animals['description']; ?></td>
      </tr>
    </table>
    <p align="left"><br />
    <br />
    <br />
    </p>
    <!-- InstanceEndEditable --></div>
</div>
<div id="footer">&copy;2009 Afghan Stray Animal League and JLS Consulting, Inc </div>
</body><!-- InstanceEnd -->