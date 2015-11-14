<?php require_once('../Connections/JLS_conn.php'); ?>
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
$query_rs_dog_dieds_adopted = "SELECT animals.animal_type, animals.adopt_date FROM animals WHERE animal_type = 'dog' AND animals.adopt_date>20071231 AND animals.adopt_date<20100101";
$rs_dog_dieds_adopted = mysql_query($query_rs_dog_dieds_adopted, $JLS_conn) or die(mysql_error());
$row_rs_dog_dieds_adopted = mysql_fetch_assoc($rs_dog_dieds_adopted);
$totalRows_rs_dog_dieds_adopted = mysql_num_rows($rs_dog_dieds_adopted);
$query_rs_dog_dieds_adopted = "SELECT animals.animal_type, animals.adopt_date FROM animals WHERE animal_type = 'dog' AND animals.adopt_date>20071231 AND animals.adopt_date<20100101";
$rs_dog_dieds_adopted = mysql_query($query_rs_dog_dieds_adopted, $JLS_conn) or die(mysql_error());
$row_rs_dog_dieds_adopted = mysql_fetch_assoc($rs_dog_dieds_adopted);
$totalRows_rs_dog_dieds_adopted = mysql_num_rows($rs_dog_dieds_adopted);

mysql_select_db($database_JLS_conn, $JLS_conn);
$query_rs_foster_cats_adopt = "SELECT animals.animal_type, animals.adopt_date, animals.location, animals.name, animals.status FROM animals WHERE animals.animal_type='cat' AND animals.adopt_date>20071231 AND animals.adopt_date<20100101 AND animals.location='HSFC Foster' AND status='Adopted'";
$rs_foster_cats_adopt = mysql_query($query_rs_foster_cats_adopt, $JLS_conn) or die(mysql_error());
$row_rs_foster_cats_adopt = mysql_fetch_assoc($rs_foster_cats_adopt);
$totalRows_rs_foster_cats_adopt = mysql_num_rows($rs_foster_cats_adopt);

mysql_select_db($database_JLS_conn, $JLS_conn);
$query_rs_cats_fairfax = "SELECT animals.animal_type, animals.adopt_date, animals.location FROM animals WHERE animals.animal_type='cat' AND animals.adopt_date>20071231 AND animals.adopt_date<20100101 AND animals.location='HSFC Fairfax'";
$rs_cats_fairfax = mysql_query($query_rs_cats_fairfax, $JLS_conn) or die(mysql_error());
$row_rs_cats_fairfax = mysql_fetch_assoc($rs_cats_fairfax);
$totalRows_rs_cats_fairfax = mysql_num_rows($rs_cats_fairfax);

mysql_select_db($database_JLS_conn, $JLS_conn);
$query_rs_horses = "SELECT animals.animal_type, animals.adopt_date FROM animals WHERE animals.animal_type='horse' AND animals.adopt_date>20071231 AND animals.adopt_date<20100101";
$rs_horses = mysql_query($query_rs_horses, $JLS_conn) or die(mysql_error());
$row_rs_horses = mysql_fetch_assoc($rs_horses);
$totalRows_rs_horses = mysql_num_rows($rs_horses);

mysql_select_db($database_JLS_conn, $JLS_conn);
$query_rs_rabbits = "SELECT animals.animal_type, animals.adopt_date FROM animals WHERE animals.animal_type='rabbit' AND animals.adopt_date>20071231 AND animals.adopt_date<20100101";
$rs_rabbits = mysql_query($query_rs_rabbits, $JLS_conn) or die(mysql_error());
$row_rs_rabbits = mysql_fetch_assoc($rs_rabbits);
$totalRows_rs_rabbits = mysql_num_rows($rs_rabbits);

mysql_select_db($database_JLS_conn, $JLS_conn);
$query_rs_guinea_pig = "SELECT animals.animal_type, animals.adopt_date FROM animals WHERE animals.animal_type='guinea pig' AND animals.adopt_date>20071231 AND animals.adopt_date<20100101";
$rs_guinea_pig = mysql_query($query_rs_guinea_pig, $JLS_conn) or die(mysql_error());
$row_rs_guinea_pig = mysql_fetch_assoc($rs_guinea_pig);
$totalRows_rs_guinea_pig = mysql_num_rows($rs_guinea_pig);

mysql_select_db($database_JLS_conn, $JLS_conn);
$query_rs_birds = "SELECT animals.animal_type, animals.adopt_date FROM animals WHERE animals.animal_type='bird' AND animals.adopt_date>20071231 AND animals.adopt_date<20100101";
$rs_birds = mysql_query($query_rs_birds, $JLS_conn) or die(mysql_error());
$row_rs_birds = mysql_fetch_assoc($rs_birds);
$totalRows_rs_birds = mysql_num_rows($rs_birds);

mysql_select_db($database_JLS_conn, $JLS_conn);
$query_rs_dog_intake = "SELECT * FROM animals WHERE animals.animal_type='dog' AND animals.intake_date>'2007-12-31' AND animals.intake_date<'2008-12-31' ";
$rs_dog_intake = mysql_query($query_rs_dog_intake, $JLS_conn) or die(mysql_error());
$row_rs_dog_intake = mysql_fetch_assoc($rs_dog_intake);
$totalRows_rs_dog_intake = mysql_num_rows($rs_dog_intake);

mysql_select_db($database_JLS_conn, $JLS_conn);
$query_rs_foster_intake = "SELECT * FROM animals WHERE animals.animal_type='cat' AND animals.intake_date>'2007-12-31' AND animals.intake_date<'2008-12-31' AND animals.location='HSFC Foster'";
$rs_foster_intake = mysql_query($query_rs_foster_intake, $JLS_conn) or die(mysql_error());
$row_rs_foster_intake = mysql_fetch_assoc($rs_foster_intake);
$totalRows_rs_foster_intake = mysql_num_rows($rs_foster_intake);

mysql_select_db($database_JLS_conn, $JLS_conn);
$query_rs_cats_bldg_intake = "SELECT * FROM animals WHERE animals.animal_type='cat' AND animals.intake_date>'2007-12-31' AND animals.intake_date<'2008-12-31' AND animals.location='HSFC Fairfax '";
$rs_cats_bldg_intake = mysql_query($query_rs_cats_bldg_intake, $JLS_conn) or die(mysql_error());
$row_rs_cats_bldg_intake = mysql_fetch_assoc($rs_cats_bldg_intake);
$totalRows_rs_cats_bldg_intake = mysql_num_rows($rs_cats_bldg_intake);

mysql_select_db($database_JLS_conn, $JLS_conn);
$query_rs_horse_intake = "SELECT animals.animal_type, animals.adopt_date FROM animals WHERE animals.animal_type='horse' AND animals.intake_date>20071231 AND animals.intake_date<20100101";
$rs_horse_intake = mysql_query($query_rs_horse_intake, $JLS_conn) or die(mysql_error());
$row_rs_horse_intake = mysql_fetch_assoc($rs_horse_intake);
$totalRows_rs_horse_intake = mysql_num_rows($rs_horse_intake);

mysql_select_db($database_JLS_conn, $JLS_conn);
$query_rs_rabbit_intake = "SELECT animals.animal_type, animals.adopt_date FROM animals WHERE animals.animal_type='rabbit' AND animals.intake_date>20071231 AND animals.intake_date<20100101";
$rs_rabbit_intake = mysql_query($query_rs_rabbit_intake, $JLS_conn) or die(mysql_error());
$row_rs_rabbit_intake = mysql_fetch_assoc($rs_rabbit_intake);
$totalRows_rs_rabbit_intake = mysql_num_rows($rs_rabbit_intake);

mysql_select_db($database_JLS_conn, $JLS_conn);
$query_rs_guinea_intake = "SELECT animals.animal_type, animals.adopt_date FROM animals WHERE animals.animal_type='guinea pig' AND animals.intake_date>20071231 AND animals.intake_date<20100101";
$rs_guinea_intake = mysql_query($query_rs_guinea_intake, $JLS_conn) or die(mysql_error());
$row_rs_guinea_intake = mysql_fetch_assoc($rs_guinea_intake);
$totalRows_rs_guinea_intake = mysql_num_rows($rs_guinea_intake);

mysql_select_db($database_JLS_conn, $JLS_conn);
$query_rs_birds_intake = "SELECT animals.animal_type, animals.adopt_date FROM animals WHERE animals.animal_type='bird' AND animals.intake_date>20071231 AND animals.intake_date<20100101";
$rs_birds_intake = mysql_query($query_rs_birds_intake, $JLS_conn) or die(mysql_error());
$row_rs_birds_intake = mysql_fetch_assoc($rs_birds_intake);
$totalRows_rs_birds_intake = mysql_num_rows($rs_birds_intake);

mysql_select_db($database_JLS_conn, $JLS_conn);
$query_rs_dog_euth = "SELECT * FROM animals WHERE animals.animal_type='dog' AND status = 'Euthanized' AND death_date>'2007-12-31' AND death_date<'2009-01-01'";
$rs_dog_euth = mysql_query($query_rs_dog_euth, $JLS_conn) or die(mysql_error());
$row_rs_dog_euth = mysql_fetch_assoc($rs_dog_euth);
$totalRows_rs_dog_euth = mysql_num_rows($rs_dog_euth);

mysql_select_db($database_JLS_conn, $JLS_conn);
$query_rs_foster_euth = "SELECT * FROM animals WHERE animals.animal_type='cat' AND status = 'Euthanized' AND death_date>'2008-12-31' AND death_date<'2009-01-01' AND location='HSFC Foster'";
$rs_foster_euth = mysql_query($query_rs_foster_euth, $JLS_conn) or die(mysql_error());
$row_rs_foster_euth = mysql_fetch_assoc($rs_foster_euth);
$totalRows_rs_foster_euth = mysql_num_rows($rs_foster_euth);

mysql_select_db($database_JLS_conn, $JLS_conn);
$query_rs_bldg_cats_euth = "SELECT * FROM animals WHERE animals.animal_type='cat' AND status = 'Euthanized' AND death_date>'2007-12-31' AND death_date<'2009-01-01' AND location='HSFC Fairfax'";
$rs_bldg_cats_euth = mysql_query($query_rs_bldg_cats_euth, $JLS_conn) or die(mysql_error());
$row_rs_bldg_cats_euth = mysql_fetch_assoc($rs_bldg_cats_euth);
$totalRows_rs_bldg_cats_euth = mysql_num_rows($rs_bldg_cats_euth);

mysql_select_db($database_JLS_conn, $JLS_conn);
$query_rs_horse_euth = "SELECT * FROM animals WHERE animals.animal_type='horse' AND status = 'Euthanized' AND death_date>'2007-12-31' AND death_date<'2009-01-01'";
$rs_horse_euth = mysql_query($query_rs_horse_euth, $JLS_conn) or die(mysql_error());
$row_rs_horse_euth = mysql_fetch_assoc($rs_horse_euth);
$totalRows_rs_horse_euth = mysql_num_rows($rs_horse_euth);

mysql_select_db($database_JLS_conn, $JLS_conn);
$query_rs_rabbit_euth = "SELECT * FROM animals WHERE animals.animal_type='rabbit' AND status = 'Euthanized' AND death_date>'2007-12-31' AND death_date<'2009-01-01'";
$rs_rabbit_euth = mysql_query($query_rs_rabbit_euth, $JLS_conn) or die(mysql_error());
$row_rs_rabbit_euth = mysql_fetch_assoc($rs_rabbit_euth);
$totalRows_rs_rabbit_euth = mysql_num_rows($rs_rabbit_euth);

mysql_select_db($database_JLS_conn, $JLS_conn);
$query_rs_guinea_euth = "SELECT * FROM animals WHERE animals.animal_type='guinea pig' AND status = 'Euthanized' AND death_date>'2007-01-01' AND death_date<'2009-01-01'";
$rs_guinea_euth = mysql_query($query_rs_guinea_euth, $JLS_conn) or die(mysql_error());
$row_rs_guinea_euth = mysql_fetch_assoc($rs_guinea_euth);
$totalRows_rs_guinea_euth = mysql_num_rows($rs_guinea_euth);

mysql_select_db($database_JLS_conn, $JLS_conn);
$query_rs_bird_euth = "SELECT * FROM animals WHERE animals.animal_type='bird' AND status = 'Euthanized' AND death_date>'2007-12-31' AND death_date<'2009-01-01' ";
$rs_bird_euth = mysql_query($query_rs_bird_euth, $JLS_conn) or die(mysql_error());
$row_rs_bird_euth = mysql_fetch_assoc($rs_bird_euth);
$totalRows_rs_bird_euth = mysql_num_rows($rs_bird_euth);

mysql_select_db($database_JLS_conn, $JLS_conn);
$query_rs_dog_died = "SELECT * FROM animals WHERE animals.animal_type ='dog' AND animals.death_date>'2007-12-31' AND animals.death_date<'2009-01-01' AND animals.status='Died' ";
$rs_dog_died = mysql_query($query_rs_dog_died, $JLS_conn) or die(mysql_error());
$row_rs_dog_died = mysql_fetch_assoc($rs_dog_died);
$totalRows_rs_dog_died = mysql_num_rows($rs_dog_died);

mysql_select_db($database_JLS_conn, $JLS_conn);
$query_rs_foster_died = "SELECT * FROM animals WHERE animals.animal_type ='cat' AND animals.death_date>'2007-12-31' AND animals.death_date>'2009-01-01' AND animals.status='Died' AND animals.location='HSFC Foster'";
$rs_foster_died = mysql_query($query_rs_foster_died, $JLS_conn) or die(mysql_error());
$row_rs_foster_died = mysql_fetch_assoc($rs_foster_died);
$totalRows_rs_foster_died = mysql_num_rows($rs_foster_died);

mysql_select_db($database_JLS_conn, $JLS_conn);
$query_rs_cats_bldg_died = "SELECT * FROM animals WHERE animals.animal_type ='cat' AND animals.death_date>'2007-12-31' AND animals.death_date<'2009-01-01' AND animals.status='Died' AND animals.location='HSFC Fairfax'";
$rs_cats_bldg_died = mysql_query($query_rs_cats_bldg_died, $JLS_conn) or die(mysql_error());
$row_rs_cats_bldg_died = mysql_fetch_assoc($rs_cats_bldg_died);
$totalRows_rs_cats_bldg_died = mysql_num_rows($rs_cats_bldg_died);

mysql_select_db($database_JLS_conn, $JLS_conn);
$query_rs_horse_died = "SELECT * FROM animals WHERE animals.animal_type ='horse' AND animals.death_date>'2007-12-31' AND animals.death_date<'2009-01-01' AND animals.status='Died' ";
$rs_horse_died = mysql_query($query_rs_horse_died, $JLS_conn) or die(mysql_error());
$row_rs_horse_died = mysql_fetch_assoc($rs_horse_died);
$totalRows_rs_horse_died = mysql_num_rows($rs_horse_died);

mysql_select_db($database_JLS_conn, $JLS_conn);
$query_rs_rabbit_died = "SELECT * FROM animals WHERE animals.animal_type ='rabbit' AND animals.death_date>'2007-12-31' AND animals.death_date<'2009-01-01' AND animals.status='Died' ";
$rs_rabbit_died = mysql_query($query_rs_rabbit_died, $JLS_conn) or die(mysql_error());
$row_rs_rabbit_died = mysql_fetch_assoc($rs_rabbit_died);
$totalRows_rs_rabbit_died = mysql_num_rows($rs_rabbit_died);

mysql_select_db($database_JLS_conn, $JLS_conn);
$query_rs_guinea_died = "SELECT * FROM animals WHERE animals.animal_type ='guinea pig' AND animals.death_date>'2007-12-31' AND animals.death_date>'2008-01-01' AND animals.status='Died' ";
$rs_guinea_died = mysql_query($query_rs_guinea_died, $JLS_conn) or die(mysql_error());
$row_rs_guinea_died = mysql_fetch_assoc($rs_guinea_died);
$totalRows_rs_guinea_died = mysql_num_rows($rs_guinea_died);

mysql_select_db($database_JLS_conn, $JLS_conn);
$query_rs_bird_died = "SELECT * FROM animals WHERE animals.animal_type ='bird' AND animals.death_date>'2007-12-31' AND animals.death_date>'2008-01-01' AND animals.status='Died' ";
$rs_bird_died = mysql_query($query_rs_bird_died, $JLS_conn) or die(mysql_error());
$row_rs_bird_died = mysql_fetch_assoc($rs_bird_died);
$totalRows_rs_bird_died = mysql_num_rows($rs_bird_died);
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
    <p><strong><a href="../index_1.php">Web Site </a></strong><a href="../index_1.php">- Emerging</a><br />
      <a href="../cats_foster_1.php"><br />
      </a><a href="../dogs_1.php">Dogs (Live)</a><br />
    </p>
    <p><br />
    </p>
  </div>
  <div id="content_nav"><!-- InstanceBeginEditable name="content" -->
    <h1 align="left">HSFC Administration - Summary - 2008 </h1>
    <table width="200" border="1" align="center">
      <tr align="center">
        <td><strong>Type</strong></td>
        <td align="center"><strong>Intakes</strong></td>
        <td align="center"><strong>Adopts</strong></td>
        <td align="center"><strong>Euth</strong></td>
        <td align="center"><strong>Died</strong></td>
      </tr>
      <tr>
        <td>Dogs</td>
        <td align="center"><?php echo $totalRows_rs_dog_intake ?></td>
        <td align="center"><?php echo $totalRows_rs_dog_dieds_adopted ?></td>
        <td align="center"><?php echo $totalRows_rs_dog_euth ?></td>
        <td align="center"><?php echo $totalRows_rs_dog_died ?></td>
      </tr>
      <tr>
        <td>Foster Cats</td>
        <td align="center"><?php echo $totalRows_rs_foster_intake ?></td>
        <td align="center"><?php echo $totalRows_rs_foster_cats_adopt ?></td>
        <td align="center"><?php echo $totalRows_rs_foster_euth ?></td>
        <td align="center"><?php echo $totalRows_rs_foster_died ?></td>
      </tr>
      <tr>
        <td>HSFC Cats</td>
        <td align="center"><?php echo $totalRows_rs_cats_bldg_intake ?></td>
        <td align="center"><?php echo $totalRows_rs_cats_fairfax ?></td>
        <td align="center"><?php echo $totalRows_rs_bldg_cats_euth ?></td>
        <td align="center"><?php echo $totalRows_rs_cats_bldg_died ?></td>
      </tr>
      <tr>
        <td>Horses</td>
        <td align="center"><?php echo $totalRows_rs_horse_intake ?></td>
        <td align="center"><?php echo $totalRows_rs_horses ?></td>
        <td align="center"><?php echo $totalRows_rs_horse_euth ?></td>
        <td align="center"><?php echo $totalRows_rs_horse_died ?></td>
      </tr>
      <tr>
        <td>Rabbits</td>
        <td align="center"><?php echo $totalRows_rs_rabbit_intake ?></td>
        <td align="center"><?php echo $totalRows_rs_rabbits ?></td>
        <td align="center"><?php echo $totalRows_rs_rabbit_euth ?></td>
        <td align="center"><?php echo $totalRows_rs_rabbit_died ?></td>
      </tr>
      <tr>
        <td>Guinea Pigs</td>
        <td align="center"><?php echo $totalRows_rs_guinea_intake ?></td>
        <td align="center"><?php echo $totalRows_rs_guinea_pig ?></td>
        <td align="center"><?php echo $totalRows_rs_guinea_euth ?></td>
        <td align="center"><?php echo $totalRows_rs_guinea_died ?></td>
      </tr>
      <tr>
        <td>Birds</td>
        <td align="center"><?php echo $totalRows_rs_birds_intake ?></td>
        <td align="center"><?php echo $totalRows_rs_birds ?></td>
        <td align="center"><?php echo $totalRows_rs_bird_euth ?></td>
        <td align="center"><?php echo $totalRows_rs_bird_died ?></td>
      </tr>
      <tr>
        <td>Total</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
      </tr>
    </table>
    <p align="left">&nbsp;</p>
    <!-- InstanceEndEditable --></div>
</div>
<div id="footer">&copy;2009 Afghan Stray Animal League and JLS Consulting, Inc </div>
</body><!-- InstanceEnd -->