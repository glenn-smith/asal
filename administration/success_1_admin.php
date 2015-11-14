<?php require_once('../Connections/JLS_conn.php'); ?>
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
$query_rs_success = "SELECT * FROM success WHERE success.show_web='Yes' ORDER BY story_date DESC";
$rs_success = mysql_query($query_rs_success, $JLS_conn) or die(mysql_error());
$row_rs_success = mysql_fetch_assoc($rs_success);
$totalRows_rs_success = mysql_num_rows($rs_success);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/2col_bg_wht_rev.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>HSFC Content Template</title>
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable -->
<script type="text/JavaScript">
<!--

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}

function MM_nbGroup(event, grpName) { //v6.0
  var i,img,nbArr,args=MM_nbGroup.arguments;
  if (event == "init" && args.length > 2) {
    if ((img = MM_findObj(args[2])) != null && !img.MM_init) {
      img.MM_init = true; img.MM_up = args[3]; img.MM_dn = img.src;
      if ((nbArr = document[grpName]) == null) nbArr = document[grpName] = new Array();
      nbArr[nbArr.length] = img;
      for (i=4; i < args.length-1; i+=2) if ((img = MM_findObj(args[i])) != null) {
        if (!img.MM_up) img.MM_up = img.src;
        img.src = img.MM_dn = args[i+1];
        nbArr[nbArr.length] = img;
    } }
  } else if (event == "over") {
    document.MM_nbOver = nbArr = new Array();
    for (i=1; i < args.length-1; i+=3) if ((img = MM_findObj(args[i])) != null) {
      if (!img.MM_up) img.MM_up = img.src;
      img.src = (img.MM_dn && args[i+2]) ? args[i+2] : ((args[i+1])? args[i+1] : img.MM_up);
      nbArr[nbArr.length] = img;
    }
  } else if (event == "out" ) {
    for (i=0; i < document.MM_nbOver.length; i++) {
      img = document.MM_nbOver[i]; img.src = (img.MM_dn) ? img.MM_dn : img.MM_up; }
  } else if (event == "down") {
    nbArr = document[grpName];
    if (nbArr)
      for (i=0; i < nbArr.length; i++) { img=nbArr[i]; img.src = img.MM_up; img.MM_dn = 0; }
    document[grpName] = nbArr = new Array();
    for (i=2; i < args.length-1; i+=2) if ((img = MM_findObj(args[i])) != null) {
      if (!img.MM_up) img.MM_up = img.src;
      img.src = img.MM_dn = (args[i+1])? args[i+1] : img.MM_up;
      nbArr[nbArr.length] = img;
  } }
}
//-->
</script>
<link href="/CSS/2col_content_rev.css" rel="stylesheet" type="text/css" />
</head>

<body onload="MM_preloadImages('/HSFC_test/SiteImages/pawbutton-deadlink.jpg','/HSFC_test/SiteImages/pawbutton-deep-.5in.jpg')">
<div id="holder">
  <div id="header">
    <div align="center"><a href="/home_1.php"><img src="/HSFC_test/SiteImages/HSFCheaderwithcutout-orig.gif" alt="HSFC Header" border="0" usemap="#Image1Map" id="Image1" /></a>
<map name="Image1Map" id="Image1Map"><area shape="rect" coords="49,29,741,67" href="#" /></map></div>
  </div>
  <div id="nav">
    <p><a href="/aboutus_1.html" target="_top" onclick="MM_nbGroup('down','group1','pawbuttondeep5in','',1)" onmouseover="MM_nbGroup('over','pawbuttondeep5in','/HSFC_test/SiteImages/pawbutton-deadlink.jpg','',1)" onmouseout="MM_nbGroup('out')"><img src="/HSFC_test/SiteImages/pawbutton-deep-.5in.jpg" alt="About Us Button" name="pawbuttondeep5in" width="39" height="36" border="0" id="Image2" /></a>About Us<br />
    <a href="/pets_1.html" target="_self" onclick="MM_nbGroup('down','group1','pawbuttondeep5in3','',1)" onmouseover="MM_nbGroup('over','pawbuttondeep5in3','/HSFC_test/SiteImages/pawbutton-deadlink.jpg','',1)" onmouseout="MM_nbGroup('out')"><img src="/HSFC_test/SiteImages/pawbutton-deep-.5in.jpg" alt="button_adoptable_animals" name="pawbuttondeep5in3" width="39" height="36" border="0" /></a>Adoptable Animals<a href="/adopt_1.html" target="_self" onclick="MM_nbGroup('down','group1','pawbuttondeep5in2','',1)" onmouseover="MM_nbGroup('over','pawbuttondeep5in2','/HSFC_test/SiteImages/pawbutton-deadlink.jpg','',1)" onmouseout="MM_nbGroup('out')"><img src="/HSFC_test/SiteImages/pawbutton-deep-.5in.jpg" alt="Adopting a Pet" name="pawbuttondeep5in2" width="39" height="36" border="0" /></a>Adopting a Pet      <a href="../adopt.html" target="_self"></a><br />
    <a href="/forms_1.html" target="_self" onclick="MM_nbGroup('down','group1','pawbuttondeep5in9','',1)" onmouseover="MM_nbGroup('over','pawbuttondeep5in9','/HSFC_test/SiteImages/pawbutton-deadlink.jpg','',1)" onmouseout="MM_nbGroup('out')"><img src="/HSFC_test/SiteImages/pawbutton-deep-.5in.jpg" alt="Applications/Downloads" name="pawbuttondeep5in9" width="39" height="36" border="0" id="pawbuttondeep5in9" /></a>Applications/ Downloads<br />
    <a href="/contact_1.html" target="_self" onclick="MM_nbGroup('down','group1','pawbuttondeep5in11','',1)" onmouseover="MM_nbGroup('over','pawbuttondeep5in11','/HSFC_test/SiteImages/pawbutton-deadlink.jpg','',1)" onmouseout="MM_nbGroup('out')"><img src="/HSFC_test/SiteImages/pawbutton-deep-.5in.jpg" alt="Contact Us" name="pawbuttondeep5in11" width="39" height="36" border="0" id="pawbuttondeep5in11" /></a>Contact Us<br />
    <a href="/donate_1.html" target="_self" onclick="MM_nbGroup('down','group1','pawbuttondeep5in6','',1)" onmouseover="MM_nbGroup('over','pawbuttondeep5in6','/HSFC_test/SiteImages/pawbutton-deadlink.jpg','',1)" onmouseout="MM_nbGroup('out')"><img src="/HSFC_test/SiteImages/pawbutton-deep-.5in.jpg" alt="Volunteering Button" name="pawbuttondeep5in6" width="39" height="36" border="0" id="pawbuttondeep5in6" /></a>Donate<br />
    <a href="/home_1.php" target="_self" onclick="MM_nbGroup('down','group1','pawbuttondeep5in6','',1)" onmouseover="MM_nbGroup('over','pawbuttondeep5in6','/HSFC_test/SiteImages/pawbutton-deadlink.jpg','',1)" onmouseout="MM_nbGroup('out')"><img src="/HSFC_test/SiteImages/pawbutton-deep-.5in.jpg" alt="Volunteering Button" name="pawbuttondeep5in6" width="39" height="36" border="0" id="pawbuttondeep5in6" /></a><strong>HOME</strong>/<strong>NEWS</strong><br />
    <a href="/kids_1.html" target="_self" onclick="MM_nbGroup('down','group1','pawbuttondeep5in13','',1)" onmouseover="MM_nbGroup('over','pawbuttondeep5in13','/HSFC_test/SiteImages/pawbutton-deadlink.jpg','',1)" onmouseout="MM_nbGroup('out')"><img src="/HSFC_test/SiteImages/pawbutton-deep-.5in.jpg" alt="Kids Corner" name="pawbuttondeep5in13" width="39" height="36" border="0" id="pawbuttondeep5in13" /></a>Kids Corner<br />
    <a href="/spay_1.html" target="_self" onclick="MM_nbGroup('down','group1','pawbuttondeep5in7','',1)" onmouseover="MM_nbGroup('over','pawbuttondeep5in7','/HSFC_test/SiteImages/pawbutton-deadlink.jpg','',1)" onmouseout="MM_nbGroup('out')"><img src="/HSFC_test/SiteImages/pawbutton-deep-.5in.jpg" alt="Volunteering Button" name="pawbuttondeep5in7" width="39" height="36" border="0" id="pawbuttondeep5in7" /></a>Spay/Neuter Info<br />
    <a href="/administration/success_1_admin.php" target="_self" onclick="MM_nbGroup('down','group1','pawbuttondeep5in10','',1)" onmouseover="MM_nbGroup('over','pawbuttondeep5in10','/HSFC_test/SiteImages/pawbutton-deadlink.jpg','',1)" onmouseout="MM_nbGroup('out')"><img src="/HSFC_test/SiteImages/pawbutton-deep-.5in.jpg" alt="Success Stories" name="pawbuttondeep5in10" width="39" height="36" border="0" /></a>Success Stories!<br />
    <a href="/thrift_1.html" target="_self" onclick="MM_nbGroup('down','group1','pawbuttondeep5in8','',1)" onmouseover="MM_nbGroup('over','pawbuttondeep5in8','/HSFC_test/SiteImages/pawbutton-deadlink.jpg','',1)" onmouseout="MM_nbGroup('out')"><img src="/HSFC_test/SiteImages/pawbutton-deep-.5in.jpg" alt="Thrift Stores Button" name="pawbuttondeep5in8" width="39" height="36" border="0" /></a>Thrift Stores<br />
      <a href="/links_1.html" target="_self" onclick="MM_nbGroup('down','group1','pawbuttondeep5in12','',1)" onmouseover="MM_nbGroup('over','pawbuttondeep5in12','/HSFC_test/SiteImages/pawbutton-deadlink.jpg','',1)" onmouseout="MM_nbGroup('out')"><img src="/HSFC_test/SiteImages/pawbutton-deep-.5in.jpg" alt="Useful Links" name="pawbuttondeep5in12" width="39" height="36" border="0" id="pawbuttondeep5in12" /></a>Useful Links<br />
      <a href="/volunteer_1.html" target="_self" onclick="MM_nbGroup('down','group1','pawbuttondeep5in4','',1)" onmouseover="MM_nbGroup('over','pawbuttondeep5in4','/HSFC_test/SiteImages/pawbutton-deadlink.jpg','',1)" onmouseout="MM_nbGroup('out')"><img src="/HSFC_test/SiteImages/pawbutton-deep-.5in.jpg" alt="Volunteering Button" name="pawbuttondeep5in4" width="39" height="36" border="0" /></a> Volunteering<a href="/help_1.html" target="_self" onclick="MM_nbGroup('down','group1','pawbuttondeep5in5','',1)" onmouseover="MM_nbGroup('over','pawbuttondeep5in5','/HSFC_test/SiteImages/pawbutton-deadlink.jpg','',1)" onmouseout="MM_nbGroup('out')"><br />
    <img src="/HSFC_test/SiteImages/pawbutton-deep-.5in.jpg" alt="Ways You Can Help" name="pawbuttondeep5in5" width="39" height="36" border="0" /></a>Ways To Help/Donate<br />
    <a href="../ani-meals.html"><img src="../HSFC_test/SiteImages/pawbutton-deep-.5in.jpg" width="39" height="36" border="0" /></a>Ani-Meals Program   <br />
      <br />
      <br />
      <br />
    </p>
</div>
  <div id="content_nav"><!-- InstanceBeginEditable name="content" -->
    <div align="center">
      <p><strong>The Humane Society of Fairfax County Success Stories </strong></p>
      <table width="520" border="5">
        <?php do { ?>
          <tr>
            <th bgcolor="#8C3BE4" scope="col"><div align="center"></div></th>
          </tr>
          <tr>
            <th scope="col"><div align="center"><img src="<?php echo $row_rs_success['image_path']; ?>" /><img src="<?php echo $row_rs_success['image_2_path']; ?>" /><br />
                <br />
            </div></th>
          </tr>
          <tr>
            <td><?php echo $row_rs_success['animal_name']; ?>:<?php echo $row_rs_success['story']; ?></td>
          </tr>
          <?php } while ($row_rs_success = mysql_fetch_assoc($rs_success)); ?>
      </table>
      <p>&nbsp;      </p>
    </div>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <h1 align="left">&nbsp;</h1>
    <p align="left">&nbsp;</p>
  <!-- InstanceEndEditable --></div>
</div>
<div id="footer">
  <div align="center">
    <p><img src="/images/logo-colorsmaller1inchht.jpg" alt="logo" width="114" height="72" /><br />
        4057 
    Chain Bridge Road ~ Fairfax VA 22030 ~ 703.385.7387<br />
    <em>Humane 
    Society of Fairfax County, Inc., is a 501(c)3 nonprofit charitable organization<br />
    </em>&copy;2008 Humane Society of Fairfax County, Inc </p>
  </div>
</div>
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($rs_success);
?>
