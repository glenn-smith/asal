<?php require_once('Connections/ASAL.php'); ?>
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
$query_rs_dogs = "SELECT * FROM animals WHERE status = 'available' AND animal_type='dog' ORDER BY animals.name ASC";
$rs_dogs = mysql_query($query_rs_dogs, $ASAL) or die(mysql_error());
$row_rs_dogs = mysql_fetch_assoc($rs_dogs);
$totalRows_rs_dogs = mysql_num_rows($rs_dogs);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/asal.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>ASAL Available Dogs</title>
<!-- InstanceEndEditable -->
<style type="text/css" media="all">
<!--
/* This rule resets a core set of elements so that they will appear consistent across browsers. Without this rule, content styled with an h1 tag, for example, would appear in different places in Firefox and Internet Explorer because each browser has a different top margin default value. By resetting these values to 0, the elements will initially be rendered in an identical fashion and their properties can be easily defined by the designer in any subsequent rule. */
html, body, div, span, applet, object, iframe, h1, h2, h3, h4, h5, h6 {
  margin: 0;
  padding: 0;
  border: 0;
  outline: 0;
  font-size: 100%;
}
/* The body is the outermost layout component and contains the visible page content. Setting properties for the body element will help to create consistent styling of the page content and more manageable CSS. Besides using the body element to set global properties, it is common to use the body element to set the background color of the page and create a centered container for the page content to display. */
body {
  background-color: #46383a;
  color: #2e2d2c;
  font-family: Arial, Helvetica, sans-serif;
  font-size: 11px;
  line-height: 14px;
  margin: 0 0 0 0; /* Sets the margin properties for an element using shorthand notation (top, right, bottom, left) */
  padding: 0 0 0 0; /* Sets the padding properties for an element using shorthand notation (top, right, bottom, left) */
  text-align: center; /* Centers the page content container in IE 5 browsers. */
}
/* Commonly used to style page titles. */
h1 {
  color: #b54f37;
  font-size: 14px;
  font-weight: bold;
  line-height: 14px;
}
/* Commonly used to style section titles. */
h2 {
  color: #b54f37;
  font-size: 12px;
  font-weight: bold;
  line-height: 14px;
}
.photo_right {
	float: right;
	margin-right: 5px;
	margin-bottom: 5px;
	margin-left: 5px;
	margin-top: 5px;
	border: medium solid #000000;
}
/* Sets the style for unvisited links. */
a,  a:link {
  color: #da5242;
  font-weight: bold;
  text-decoration: none;
}
/* Sets the style for visited links. */
a:visited {
  color: #904841;
  font-weight: bold;
  text-decoration: none;
}
/* Sets the style for links on mouseover. */
a:hover {
  color: #bf1c1c;
  text-decoration: underline;
}
/* Sets the style for a link that has focus. */
a:focus {
  color: #bf1c1c;
}
/* Sets the style for a link that is being activated/clicked. */
a:active {
  color: #702922;
}
/* This is a container for the page content. It is common to use the container to constrain the width of the page content and allow for browser chrome to avoid the need for horizontal scrolling. For fixed layouts you may specify a container width and use auto for the left and right margin to center the container on the page. IE 5 browser require the use of text-align: center defined by the body element to center the container. For liquid layouts you may simply set the left and right margins to center the container on the page. */
#outerWrapper {
	background-color: #fff;
	margin: 0 auto 0 auto; /* Sets the margin properties for an element using shorthand notation (top, right, bottom, left) */
	max-width: 765px;
	text-align: left; /* Redefines the text alignment defined by the body element. */
	width: 70em;
}
#outerWrapper #header {
	background-color: #cda98b;
	border-bottom: solid 1px #8b675a; /* Sets the bottom border properties for an element using shorthand notation */
	font-size: 18px;
	font-weight: bold;
	line-height: 15px;
	padding: 5px; /* Sets the padding properties for an element using shorthand notation (top, right, bottom, left) */
}
#outerWrapper #contentWrapper {
  overflow: hidden;
}
#outerWrapper #contentWrapper #leftColumn1 {
  background-color: #f9f1e1;
  border-right: solid 1px #cda98b; /* Sets the right border properties for an element using shorthand notation */
  float: left;
  padding: 10px 10px 10px 10px; /* Sets the padding properties for an element using shorthand notation (top, right, bottom, left) */
  width: 14em;
}
/* Contains the main page content. When using a mutliple column layout the margins will be set to account for the floated columns' width, margins, and padding. */
#outerWrapper #contentWrapper #content {
  margin: 0 0 0 16em; /* Sets the margin properties for an element using shorthand notation (top, right, bottom, left) */
  padding: 10px 10px 10px 10px; /* Sets the padding properties for an element using shorthand notation (top, right, bottom, left) */
}
#outerWrapper #footer {
  background-color: #f9f1e1;
  border-top: solid 1px #cda98b; /* Sets the top border properties for an element using shorthand notation */
  padding: 10px 10px 10px 10px; /* Sets the padding properties for an element using shorthand notation (top, right, bottom, left) */
}
-->
</style>
<style type="text/css" media="print">
<!--
/* It is common to set printer friendly styles such as a white background with black text. */
body {
  background-color: #fff;
  background-image: none;
  border-color: #000; /* Sets the border color properties for an element using shorthand notation */
  color: #000;
}
-->
</style>
<!--[if IE 5]>
<style type="text/css"> 
/* IE 5 does not use the standard box model, so the column widths are overidden to render the page correctly. */
#outerWrapper #contentWrapper #leftColumn1 {
  width: 16em;
}
</style>
<![endif]-->
<!--[if IE]>
<style type="text/css"> 
/* The proprietary zoom property gives IE the hasLayout property which addresses several bugs. */
#outerWrapper #contentWrapper, #outerWrapper #contentWrapper #content {
  zoom: 1;
}
</style>
<![endif]-->
<!-- InstanceBeginEditable name="head" -->
<script type="text/javascript" src="CSSMenuWriter/cssmw5/menu.js"></script>
<style type="text/css" media="all">
<!--
@import url("CSSMenuWriter/cssmw5/menu.css");
-->
</style>
<!--[if lte IE 6]>
<style type="text/css" media="all">
@import url("../CSSMenuWriter/cssmw5/menu_ie.css");
</style>
<![endif]-->

<title>2 Column Elastic, Left Sidebar, Header and Footer</title>
<!-- InstanceEndEditable -->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-38559341-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>
<meta name="google-site-verification" content="GpXk9rzRDLY5IO73tpm_wkyM6UlxTWUxemYKKwQPzhY" />
<body>

<div id="outerWrapper">
  <div id="header"><img src="images/ASAL_home_banner.gif" width="755" height="145" alt="Banner" /></div>

  <div id="contentWrapper">
    <div id="leftColumn1">
      <h2>&nbsp;
        <ul class="level-0" id="cssmw5">
          <li><span><a href="index.php">Home</a></span></li>
          <li><span><a href="mission_1.html">Mission</a></span></li>
          <li><span><a href="donate_1.html">Donate</a></span></li>
          <li><span><a href="dogs_1.php">Available Dogs</a></span></li>          
          <li><span><a href="cats_1.php">Available Cats</a></span></li>          
          <li><span><a href="adopt_1.php">How to Adopt</a></span></li>          
          <li><span><a href="success_1.php">Success Stories</a></span></li>
          <li><span><a href="fam_photos_1.php">Family Photos</a></span></li>
        </ul>
        <script type="text/javascript">if(window.attachEvent) { window.attachEvent("onload", function() { cssmw5.intializeMenu('cssmw5'); }); } else if(window.addEventListener) { window.addEventListener("load", function() { cssmw5.intializeMenu('cssmw5'); }, true); }</script>
      </h2>
    </div>

    <div id="content"><!-- InstanceBeginEditable name="EditRegion3" -->
      <h1 align="center">Dogs Available
      </h1>
      <h1>&nbsp;</h1>
      <hr />
      <div align="center">
        <?php do { ?>
          <table width="500" border="1">
            <tr>
              <td><img src="<?php echo $row_rs_dogs['image_path']; ?>" alt="dogs pic" width="240" /></td>
              <td><p align="center"><?php echo $row_rs_dogs['name']; ?> - <?php echo $row_rs_dogs['birth_date']; ?><br />
                <?php echo $row_rs_dogs['breed']; ?>-<?php echo $row_rs_dogs['sex']; ?> <br />
                <?php echo $row_rs_dogs['weight']; ?> lbs </p>
                <p><?php echo $row_rs_dogs['description']; ?></p>
              <p><?php echo $row_rs_dogs['adoptability']; ?></p></td>
            </tr>
          </table>
          <?php } while ($row_rs_dogs = mysql_fetch_assoc($rs_dogs)); ?>
      </div>
      <p>&nbsp;</p>
    <!-- InstanceEndEditable --></div>
  </div>
  <div id="footer">
    <p align="center">&copy;2009-2013 Afghan Stray Animal League<br />
      Website by JLS Consulting, Inc.
    </p>
  </div>
</div>

</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($rs_dogs);
?>
