<?php require_once('../Connections/ASAL.php'); ?>
<?php require_once("../WA_ValidationToolkit/WAVT_Scripts_PHP.php"); ?>
<?php require_once("../WA_ValidationToolkit/WAVT_ValidatedForm_PHP.php"); ?>
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
if ($_SERVER["REQUEST_METHOD"] == "POST")  {
  $WAFV_Redirect = "";
  $_SESSION['WAVT_animalchange_Errors'] = "";
  if ($WAFV_Redirect == "")  {
    $WAFV_Redirect = $_SERVER["PHP_SELF"];
  }
  $WAFV_Errors = "";
  $WAFV_Errors .= WAValidateDT($row_rs_animals['spay_neuter'] . "",true,"\b([12]\d|3[0-1]|0[1-9])\.(1[0-2]|0[1-9])\.\d{4}\b","","",false,".*","","",false,1);

  if ($WAFV_Errors != "")  {
    PostResult($WAFV_Redirect,$WAFV_Errors,"animalchange");
  }
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
  $updateSQL = sprintf("UPDATE animals SET foster_id=%s, adopter_id=%s, case_no=%s, name=%s, source=%s, source_name=%s, intake_date=%s, adopt_date=%s, animal_type=%s, breed=%s, weight=%s, color=%s, sex=%s, birth_date=%s, death_date=%s, `description`=%s, image_path=%s, adoptability=%s, heartworm_test=%s, dhlpp_1=%s, dhlpp_2=%s, dhlpp_3=%s, fiv_felv_test=%s, FVRCP_due=%s, fvrcp_1=%s, fvrcp_2=%s, rabies_type_test=%s, rabies_due=%s, rabies_1year=%s, rabies_3year=%s, fecal_date_result_meds=%s, worming_1=%s, worming_2=%s, worming_3=%s, spay_neuter=%s, other_medical_info=%s, vet_clinic=%s, location=%s, HSFC_Bldg_room=%s, feeding_instructions=%s, status=%s, microchip=%s, notes=%s WHERE animal_id=%s",
                       GetSQLValueString($_POST['foster_id'], "int"),
                       GetSQLValueString($_POST['adopter_id'], "int"),
                       GetSQLValueString($_POST['case_no'], "text"),
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['source'], "text"),
                       GetSQLValueString($_POST['source_name'], "text"),
                       GetSQLValueString($_POST['intake_date'], "date"),
                       GetSQLValueString($_POST['adopt_date'], "date"),
                       GetSQLValueString($_POST['animal_type'], "text"),
                       GetSQLValueString($_POST['breed'], "text"),
                       GetSQLValueString($_POST['weight'], "int"),
                       GetSQLValueString($_POST['color'], "text"),
                       GetSQLValueString($_POST['sex'], "text"),
                       GetSQLValueString($_POST['birth_date'], "date"),
                       GetSQLValueString($_POST['death_date'], "date"),
                       GetSQLValueString($_POST['description'], "text"),
                       GetSQLValueString($_POST['image_path'], "text"),
                       GetSQLValueString($_POST['adoptability'], "text"),
                       GetSQLValueString($_POST['heartworm_test'], "date"),
                       GetSQLValueString($_POST['dhlpp_1'], "date"),
                       GetSQLValueString($_POST['dhlpp_2'], "date"),
                       GetSQLValueString($_POST['dhlpp_3'], "date"),
                       GetSQLValueString($_POST['fiv_felv_test'], "date"),
                       GetSQLValueString($_POST['FVRCP_due'], "date"),
                       GetSQLValueString($_POST['fvrcp_1'], "date"),
                       GetSQLValueString($_POST['fvrcp_2'], "date"),
                       GetSQLValueString($_POST['rabies_type_test'], "text"),
                       GetSQLValueString($_POST['rabies_due'], "date"),
                       GetSQLValueString($_POST['rabies_1year'], "date"),
                       GetSQLValueString($_POST['rabies_3year'], "date"),
                       GetSQLValueString($_POST['fecal_date_result_meds'], "text"),
                       GetSQLValueString($_POST['worming_1'], "date"),
                       GetSQLValueString($_POST['worming_2'], "date"),
                       GetSQLValueString($_POST['worming_3'], "date"),
                       GetSQLValueString($_POST['spay_neuter'], "date"),
                       GetSQLValueString($_POST['other_medical_info'], "text"),
                       GetSQLValueString($_POST['vet_clinic'], "text"),
                       GetSQLValueString($_POST['location'], "text"),
                       GetSQLValueString($_POST['HSFC_Bldg_room'], "text"),
                       GetSQLValueString($_POST['feeding_instructions'], "text"),
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['microchip'], "int"),
                       GetSQLValueString($_POST['notes'], "text"),
                       GetSQLValueString($_POST['animal_id'], "int"));

  mysql_select_db($database_ASAL, $ASAL);
  $Result1 = mysql_query($updateSQL, $ASAL) or die(mysql_error());

  $updateGoTo = "http://afghanstrayanimals.org/administration/animal_change.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_rs_animals = "-1";
if (isset($_GET['animal_id'])) {
  $colname_rs_animals = (get_magic_quotes_gpc()) ? $_GET['animal_id'] : addslashes($_GET['animal_id']);
}
mysql_select_db($database_ASAL, $ASAL);
$query_rs_animals = sprintf("SELECT * FROM animals WHERE animal_id = %s", GetSQLValueString($colname_rs_animals, "int"));
$rs_animals = mysql_query($query_rs_animals, $ASAL) or die(mysql_error());
$row_rs_animals = mysql_fetch_assoc($rs_animals);
$totalRows_rs_animals = mysql_num_rows($rs_animals);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/asal_admin.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>

<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>ASAL - Animal Change</title>
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="head" -->
<script type="text/JavaScript">
<!--
function WAAddError(formElement,errorMsg,focusIt,stopIt)  {
  if (document.WAFV_Error)  {
	  document.WAFV_Error += "\n" + errorMsg;
  }
  else  {
    document.WAFV_Error = errorMsg;
  }
  if (!document.WAFV_InvalidArray)  {
    document.WAFV_InvalidArray = new Array();
  }
  document.WAFV_InvalidArray[document.WAFV_InvalidArray.length] = formElement;
  if (focusIt && !document.WAFV_Focus)  {
	document.WAFV_Focus = focusIt;
  }

  if (stopIt == 1)  {
	document.WAFV_Stop = true;
  }
  else if (stopIt == 2)  {
	formElement.WAFV_Continue = true;
  }
  else if (stopIt == 3)  {
	formElement.WAFV_Stop = true;
	formElement.WAFV_Continue = false;
  }
}

function WAGetDateFormat(value, dateFormat) {
  var isUSServ = (new Date("1/2/2006").getMonth() == 0);
  var tValue = value;
  var isEuroDate = ((dateFormat && String(dateFormat).indexOf("[12]\\d|3[0-1]") < String(dateFormat).indexOf("1[0-2]|") && String(dateFormat).indexOf("\\w*") < 0 && (String(dateFormat).indexOf("\\d{4}") < 0 || (String(dateFormat).indexOf("\\d{4}") >= 0 && String(dateFormat).indexOf("\\d{4}") > String(dateFormat).indexOf("[12]\\d|3[0-1]")))) || (!isUSServ));
  if ((isEuroDate && isUSServ) || (!isEuroDate && !isUSServ)) {
    var datePattn = /(\d*)[-\.\/](\d*)[-\.\/](\d*)/;
    var tMatch = tValue.match(datePattn);
    if (tMatch && String(tMatch[1]).length != 4) {
      if (isEuroDate) {
        value = tMatch[2] + "/" + tMatch[1] + "/" + tMatch[3];
      }
      else {
        value = tMatch[1] + "/" + tMatch[2] + "/" + tMatch[3];
      }
      if (tValue.indexOf(" ") > 0) {
        value += tValue.substring(tValue.indexOf(" "));
      }
    }
  }
  return new Date(value.replace(/[-\.]/g,"/"));
}

function WADateFormat(format,dateVar)  { 
  var fullYear = dateVar.getYear();
  if (fullYear <= 10) fullYear += 2000;
  if (fullYear <= 200) fullYear += 1900;
    dateVar.setYear(fullYear);
  var newDate = format;
  var ampm = "A";
  var ampmReplace = "p";
  var month = dateVar.getMonth() +1;
  var monthName = "January";
  if (month == 2) monthName="February";
  if (month == 3) monthName="March";
  if (month == 4) monthName="April";
  if (month == 5) monthName="May";
  if (month == 6) monthName="June";
  if (month == 7) monthName="July";
  if (month == 8) monthName="August";
  if (month == 9) monthName="September";
  if (month == 10) monthName="October";
  if (month == 11) monthName="November";
  if (month == 12) monthName="December";
  var monthNameReplace = "Month";
  var monthReplace = "m";
  var day = dateVar.getDate();
  var dayReplace = "d";
  var year = dateVar.getYear();
  if (String(year).length > 2)
    year = String(year).substring(year.length-2,year.length);
  var yearReplace = "yy";
  var hour = dateVar.getHours();
  var hourReplace = "h";
  var minute = dateVar.getMinutes();
  if (String(minute).length == 1)
    minute = "0" + minute;
  var minuteReplace = "nn";
  var second = dateVar.getSeconds();
  if (String(second).length == 1)
    second = "0" + second;
  var secondReplace = "ss";
  var timeFormat = "";
  if (format.indexOf(":")>=0)  {
    timeFormat = format.substring(format.indexOf(":"));
	newDate = format.substring(0,format.indexOf(":"));
	timeFormat = newDate.substring(newDate.lastIndexOf(" "))+ timeFormat;
	newDate = newDate.substring(0,newDate.lastIndexOf(" "));
  }
  if (timeFormat.indexOf("h:n")>=0)  {
    if (timeFormat.indexOf("p")>=0)  {
	  if (hour >= 12)  {
	    ampm = "P"
	    if (hour>12)
		  hour = hour -12;
	  }
	  if (timeFormat.indexOf("pm")>=0)  {
	    ampm += "M"
		ampmReplace = "pm"
	  }
	}
	if (timeFormat.indexOf("hh")>=0)  {
	  if (String(hour).length == 1)
        hour = "0" + hour;
	  hourReplace = "hh";
	}
    timeFormat = timeFormat.replace(hourReplace,hour).replace(minuteReplace,minute).replace(secondReplace,second).replace(ampmReplace,ampm);
  }
  if (newDate.indexOf("yy")>=0)  {
	if (newDate.indexOf("yyyy")>=0)  {
	  year = fullYear;
	  yearReplace = "yyyy";
	}
	if (newDate.indexOf("mm")>=0)  {
	  if (String(month).length == 1)
        month = "0" + month;
	  monthReplace = "mm";
	}
	if (newDate.indexOf("dd")>=0)  {
	  if (String(day).length == 1)
        day = "0" + day;
	  dayReplace = "dd";
	}
	newDate = newDate.replace(yearReplace,year).replace(monthReplace,month).replace(dayReplace,day).replace(monthNameReplace,monthName);
  }
  return newDate + timeFormat;
}

function WAValidateTheTime(doTime, timeFormat, value, isValid, timeMin, timeMax) {
	if (doTime)  {
      if (timeFormat)  {
	    if (value.search(timeFormat)<0)  {
		  isValid = false;
		}
	  }
	  if (value.indexOf(":")<0)  {
	    isValid = false;
	  }
	  if (isValid)  {
	    var dateVar = new Date(value.replace(/-/g,"/"));
		var fullYear = dateVar.getYear();
		if (isNaN(dateVar.valueOf()) || (dateVar.valueOf() == 0))
          dateVar = new Date("1/1/1 "+value);
        if (isNaN(dateVar.valueOf()) || (dateVar.valueOf() == 0))
          isValid = false;
        if (timeMin != "")  {
	      var Today = new Date(timeMin);
          if (isNaN(Today.valueOf()) || Today.valueOf() == 0)  {
		    Today =  new Date("1/1/1 "+timeMin);
		  }
		  if (isNaN(Today.valueOf()) || Today.valueOf() == 0)  {
		    Today = eval(timeMin);
		  }
	      if (dateVar < Today)
	        isValid = false;
        }
        if (timeMax != "")  {
	      var Today = new Date(timeMax);
		  if (isNaN(Today.valueOf()) || Today.valueOf() == 0)  {
		    Today = new Date("1/1/1 "+timeMax);
		  }
          if (isNaN(Today.valueOf()) || Today.valueOf() == 0)  {
		    Today = eval(timeMax);
		  }
	      if (dateVar > Today)
	        isValid = false;
        }
	  }
	}
  return isValid;
}

function WAValidateDT(formElement,errorMsg,doDate,dateFormat,dateReformat,dateMin,dateMax,doTime,timeFormat,timeReformat,timeMin,timeMax,focusIt,stopIt,required)  {
  var isValid = true;
  var value = formElement.value;
  var Now = new Date();
  var Today = Now;
  Today.setHours(0);
  Today.setMinutes(0);
  Today.setSeconds(0);
  if ((!document.WAFV_Stop && !formElement.WAFV_Stop) && !(!required && value==""))  {
    if (doDate)  {
      if (dateFormat)  {
	    if (value.search(dateFormat)<0)  {
		  isValid = false;
		}
	  }
	  if (isValid)  {
      var dateVar = WAGetDateFormat(value, dateFormat);
		if (isNaN(dateVar.valueOf()) || (dateVar.valueOf() == 0))
          isValid = false;
        if (dateMin != "")  {
	      var compareDay = WAGetDateFormat(dateMin, dateFormat);
		  if (isNaN(compareDay.valueOf()) || compareDay.valueOf() == 0)  {
		    compareDay = eval(dateMin);
		  }
	      if (dateVar < compareDay)
	        isValid = false;
        }
        if (dateMax != "")  {
	      var compareDay = WAGetDateFormat(dateMax, dateFormat);
		  if (isNaN(compareDay.valueOf()) || compareDay.valueOf() == 0)  {
		    compareDay = eval(dateMax);
		  }
	      if (dateVar > compareDay)
	      isValid = false;
        }
	  }
	}
  if (doTime) {
    isValid = WAValidateTheTime(doTime, timeFormat, value, isValid, timeMin, timeMax);
  }
    if (!isValid)  {
      WAAddError(formElement,errorMsg,focusIt,stopIt);
    }
    else  {
      var newVal = "";
      if (doDate)  {
	    if (dateReformat!="")  {
	      var newVal = dateReformat;
        }
	    else  {
	      newVal = value;
		  if (newVal.search(/\s*\d*:/)>0)
		    newVal = newVal.substring(0,newVal.search(/\s*\d*:/));
	    }
        if (doTime && timeReformat == "" && value.search(/\s*\d*:/)>0) newVal += value.substring(value.search(/\s*\d*:/));
	  }
      if (doTime)  {
	    if (newVal != "")
	      newVal += " ";
	    if (timeReformat!="")  {
	      newVal += timeReformat;
        }
	    else if (!doDate) {
	      newVal = value;
	    }
	  }
	  formElement.value = WADateFormat(newVal,dateVar);
    }
  }
}

function WAAlertErrors(errorHead,errorFoot,setFocus,submitForm)  { 
  if (!document.WAFV_StopAlert)  { 
	  document.WAFV_StopAlert = true;
	  if (document.WAFV_InvalidArray)  {  
	    document.WAFV_Stop = true;
        var errorMsg = document.WAFV_Error;
	    if (errorHead!="")
		  errorMsg = errorHead + "\n" + errorMsg;
		if (errorFoot!="")
		  errorMsg += "\n" + errorFoot;
		document.MM_returnValue = false;
		if (document.WAFV_Error!="")
		  alert(errorMsg.replace(/&quot;/g,'"'));
		else if (submitForm)
		  submitForm.submit();
	    if (setFocus && document.WAFV_Focus)  {
		  document.tempFocus = document.WAFV_Focus;
          setTimeout("document.tempFocus.focus();setTimeout('document.WAFV_Stop = false;document.WAFV_StopAlert = false;',1)",1); 
        }
        else {
          document.WAFV_Stop = false;
          document.WAFV_StopAlert = false;
        }
        for (var x=0; x<document.WAFV_InvalidArray.length; x++)  {
	      document.WAFV_InvalidArray[x].WAFV_Stop = false;
	    }
	  }
	  else  {
        document.WAFV_Stop = false;
        document.WAFV_StopAlert = false;
	    if (submitForm)  {
	      submitForm.submit();
	    }
	    document.MM_returnValue = true;
	  }
      document.WAFV_Focus = false;
	  document.WAFV_Error = false;
	  document.WAFV_InvalidArray = false;
  }
}
//-->
</script>
<!-- InstanceEndEditable -->
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
    <h1 align="left">ASAL Administration - Animal Change </h1>
    <p align="left"><strong>1. Two required fields to add an animal (Name, Birth Date, Intake Date); they are asterisked (*)<br />
2. Dates entered for the first time should be entered in the format MM/DD/YYYY (04/24/2006) <br />
3. Dates can be edited using the format: YYYY-MM-DD (2006-04-24) </strong></p>
    <p align="left">&nbsp;</p>
    
    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" onsubmit="WAValidateDT(document.form1.intake_date,'- Invalid date or time',true,/.*/,'yyyy-mm-dd','','',false,/.*/,'','','',document.form1.intake_date,1,false);WAValidateDT(document.form1.adopt_date,'- Invalid date or time',true,/.*/,'yyyy-mm-dd','','',false,/.*/,'','','',document.form1.adopt_date,1,false);WAValidateDT(document.form1.birth_date,'- Invalid date or time',true,/.*/,'yyyy-mm-dd','','',false,/.*/,'','','',document.form1.birth_date,1,true);WAValidateDT(document.form1.death_date,'- Invalid date or time',true,/.*/,'yyyy-mm-dd','','',false,/.*/,'','','',document.form1.death_date,1,false);WAValidateDT(document.form1.heartworm_test,'- Invalid date or time',true,/.*/,'yyyy-mm-dd','','',false,/.*/,'','','',document.form1.heartworm_test,1,false);WAValidateDT(document.form1.dhlpp_1,'- Invalid date or time',true,/.*/,'yyyy-mm-dd','','',false,/.*/,'','','',document.form1.dhlpp_1,1,false);WAValidateDT(document.form1.dhlpp_2,'- Invalid date or time',true,/.*/,'yyyy-mm-dd','','',false,/.*/,'','','',document.form1.dhlpp_2,1,false);WAValidateDT(document.form1.dhlpp_3,'- Invalid date or time',true,/.*/,'yyyy-mm-dd','','',false,/.*/,'','','',document.form1.dhlpp_3,1,false);WAValidateDT(document.form1.fiv_felv_test,'- Invalid date or time',true,/.*/,'yyyy-mm-dd','','',false,/.*/,'','','',document.form1.fiv_felv_test,1,false);WAValidateDT(document.form1.FVRCP_due,'- Invalid date or time',true,/.*/,'yyyy-mm-dd','','',false,/.*/,'','','',document.form1.FVRCP_due,1,false);WAValidateDT(document.form1.fvrcp_1,'- Invalid date or time',true,/.*/,'yyyy-mm-dd','','',false,/.*/,'','','',document.form1.fvrcp_1,1,false);WAValidateDT(document.form1.fvrcp_2,'- Invalid date or time',true,/\b(1[0-2]|0[1-9])\/([12]\d|3[0-1]|0[1-9])\/\d{4}\b/,'yyyy-mm-dd','','',false,/.*/,'','','',document.form1.fvrcp_2,1,false);WAValidateDT(document.form1.rabies_due,'- Invalid date or time',true,/.*/,'yyyy-mm-dd','','',false,/.*/,'','','',document.form1.rabies_due,1,false);WAValidateDT(document.form1.rabies_1year,'- Invalid date or time',true,/.*/,'yyyy-mm-dd','','',false,/.*/,'','','',document.form1.rabies_1year,1,false);WAValidateDT(document.form1.rabies_3year,'- Invalid date or time',true,/.*/,'yyyy-mm-dd','','',false,/.*/,'','','',document.form1.rabies_3year,1,false);WAValidateDT(document.form1.worming_1,'- Invalid date or time',true,/.*/,'yyyy-mm-dd','','',false,/.*/,'','','',document.form1.worming_1,1,false);WAValidateDT(document.form1.worming_2,'- Invalid date or time',true,/.*/,'yyyy-mm-dd','','',false,/.*/,'','','',document.form1.worming_2,1,false);WAValidateDT(document.form1.worming_3,'- Invalid date or time',true,/.*/,'yyyy-mm-dd','','',false,/.*/,'','','',document.form1.worming_3,1,false);WAValidateDT(document.form1.spay_neuter,'- Invalid date or time',true,/.*/,'yyyy-mm-dd','','',false,/.*/,'','','',document.form1.spay_neuter,1,false);WAAlertErrors('The following errors were found','Correct invalid entries to continue',true,false);return document.MM_returnValue">
      <table align="center">
        <tr valign="baseline">
          <td colspan="2" align="right"><label>
            <div align="center"><img src="<?php echo $row_rs_animals['image_path']; ?>" class="photo_center" /></div>
          </label></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Animal_ID:</td>
          <td><?php echo $row_rs_animals['animal_id']; ?></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Foster_id:</td>
          <td><input type="text" name="foster_id" value="<?php echo $row_rs_animals['foster_id']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Adopter_id:</td>
          <td><input type="text" name="adopter_id" value="<?php echo $row_rs_animals['adopter_id']; ?>" size="32"></td>
        </tr>

        <tr valign="baseline">
          <td nowrap align="right">Case No: </td>
          <td><label>
          <input value="<?php echo $row_rs_animals['case_no']; ?>" name="case_no" type="text" id="case_no" />
          </label></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Name:</td>
          <td><input type="text" name="name" value="<?php echo $row_rs_animals['name']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Birth_date:</td>
          <td><label>
            <input name="birth_date" type="text" id="birth_date" value="<?php echo $row_rs_animals['birth_date']; ?>" size="10" />
          </label></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Source:</td>
          <td><select name="source" id="source" title="<?php echo $row_rs_animals['source']; ?>">
            <option value="Abandoned" <?php if (!(strcmp("Abandoned", $row_rs_animals['source']))) {echo "selected=\"selected\"";} ?>>Abandoned</option>
            <option value="Stray" <?php if (!(strcmp("Stray", $row_rs_animals['source']))) {echo "selected=\"selected\"";} ?>>Stray</option>
            <option value="Owner Surrender" <?php if (!(strcmp("Owner Surrender", $row_rs_animals['source']))) {echo "selected=\"selected\"";} ?>>Owner Surrender</option>
            <option value="Confiscated" <?php if (!(strcmp("Confiscated", $row_rs_animals['source']))) {echo "selected=\"selected\"";} ?>>Conficated</option>
            <option value="Transfer" <?php if (!(strcmp("Transfer", $row_rs_animals['source']))) {echo "selected=\"selected\"";} ?>>Transfer</option>
            <option value="Born @ ASAL" <?php if (!(strcmp("Born @ ASAL", $row_rs_animals['source']))) {echo "selected=\"selected\"";} ?>>Born @ ASAL</option>
            <option value="Returned" <?php if (!(strcmp("Returned", $row_rs_animals['source']))) {echo "selected=\"selected\"";} ?>>Returned</option>
            <option value="Other" <?php if (!(strcmp("Other", $row_rs_animals['source']))) {echo "selected=\"selected\"";} ?>>Other</option>
            <?php
do {  
?>
            <option value="<?php echo $row_rs_animals['source']?>"<?php if (!(strcmp($row_rs_animals['source'], $row_rs_animals['source']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_animals['source']?></option>
<?php
} while ($row_rs_animals = mysql_fetch_assoc($rs_animals));
  $rows = mysql_num_rows($rs_animals);
  if($rows > 0) {
      mysql_data_seek($rs_animals, 0);
	  $row_rs_animals = mysql_fetch_assoc($rs_animals);
  }
?>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Source Name:</td>
          <td><label>
            <input name="source_name" type="text" id="source_name" value="<?php echo $row_rs_animals['source_name']; ?>" size="32" />
          </label></td>
		</tr>
        <tr valign="baseline">
          <td nowrap align="right">*Intake_date:</td>
          <td><input name="intake_date" type="text" value="<?php echo $row_rs_animals['intake_date']; ?>" size="10"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Adopt_date:</td>
          <td><input name="adopt_date" type="text" value="<?php echo $row_rs_animals['adopt_date']; ?>" size="10"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Animal_type:</td>
          <td><label>
            <select name="animal_type" id="animal_type">
              <option value="Dog" <?php if (!(strcmp("Dog", $row_rs_animals['animal_type']))) {echo "selected=\"selected\"";} ?>>Dog</option>
              <option value="Cat" <?php if (!(strcmp("Cat", $row_rs_animals['animal_type']))) {echo "selected=\"selected\"";} ?>>Cat</option>
              <option value="Horse" <?php if (!(strcmp("Horse", $row_rs_animals['animal_type']))) {echo "selected=\"selected\"";} ?>>Horse</option>
              <option value="Rabbit" <?php if (!(strcmp("Rabbit", $row_rs_animals['animal_type']))) {echo "selected=\"selected\"";} ?>>Rabbit</option>
<option value="Fish" <?php if (!(strcmp("Fish", $row_rs_animals['animal_type']))) {echo "selected=\"selected\"";} ?>>Fish</option>
              <option value="Bird" <?php if (!(strcmp("Bird", $row_rs_animals['animal_type']))) {echo "selected=\"selected\"";} ?>>Bird</option>
              <option value="Guinea Pig" <?php if (!(strcmp("Guinea Pig", $row_rs_animals['animal_type']))) {echo "selected=\"selected\"";} ?>>Guinea Pig</option>
<option value="Chinchilla" <?php if (!(strcmp("Chinchilla", $row_rs_animals['animal_type']))) {echo "selected=\"selected\"";} ?>>Chinchilla</option>
              <option value="Hamster" <?php if (!(strcmp("Hamster", $row_rs_animals['animal_type']))) {echo "selected=\"selected\"";} ?>>Hamster</option>
              <option value="Pony" <?php if (!(strcmp("Pony", $row_rs_animals['animal_type']))) {echo "selected=\"selected\"";} ?>>Pony</option>
              <?php
do {  
?>
              <option value="<?php echo $row_rs_animals['animal_type']?>"<?php if (!(strcmp($row_rs_animals['animal_type'], $row_rs_animals['animal_type']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_animals['animal_type']?></option>
<?php
} while ($row_rs_animals = mysql_fetch_assoc($rs_animals));
  $rows = mysql_num_rows($rs_animals);
  if($rows > 0) {
      mysql_data_seek($rs_animals, 0);
	  $row_rs_animals = mysql_fetch_assoc($rs_animals);
  }
?>
            </select>
          </label></td>
        </tr>

        <tr valign="baseline">
          <td nowrap align="right">Breed:</td>
          <td><input type="text" name="breed" value="<?php echo $row_rs_animals['breed']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Weight:</td>
          <td><input type="text" name="weight" value="<?php echo $row_rs_animals['weight']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Color:</td>
          <td><input type="text" name="color" value="<?php echo $row_rs_animals['color']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Sex:</td>
          <td><label>
            <select name="sex" id="sex">
              <option value="Male" <?php if (!(strcmp("Male", $row_rs_animals['sex']))) {echo "selected=\"selected\"";} ?>>Male</option>
              <option value="Female" <?php if (!(strcmp("Female", $row_rs_animals['sex']))) {echo "selected=\"selected\"";} ?>>Female</option>
              <option value="Neutered Male" <?php if (!(strcmp("Neutered Male", $row_rs_animals['sex']))) {echo "selected=\"selected\"";} ?>>Neutered Male</option>
              <option value="Spayed Female" <?php if (!(strcmp("Spayed Female", $row_rs_animals['sex']))) {echo "selected=\"selected\"";} ?>>Spayed Female</option>
              <option value="Stallion" <?php if (!(strcmp("Stallion", $row_rs_animals['sex']))) {echo "selected=\"selected\"";} ?>>Stallion</option>
              <option value="Gelding" <?php if (!(strcmp("Gelding", $row_rs_animals['sex']))) {echo "selected=\"selected\"";} ?>>Gelding</option>
              <option value="Mare" <?php if (!(strcmp("Mare", $row_rs_animals['sex']))) {echo "selected=\"selected\"";} ?>>Mare</option>
              <?php
do {  
?><option value="<?php echo $row_rs_animals['sex']?>"<?php if (!(strcmp($row_rs_animals['sex'], $row_rs_animals['sex']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_animals['sex']?></option><?php
} while ($row_rs_animals = mysql_fetch_assoc($rs_animals));
  $rows = mysql_num_rows($rs_animals);
  if($rows > 0) {
      mysql_data_seek($rs_animals, 0);
	  $row_rs_animals = mysql_fetch_assoc($rs_animals);
  }
?>
            </select>
          </label></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Death_date:</td>
       		  <td><input type="text" name="death_date" value="<?php echo $row_rs_animals['death_date']; ?>" size="10"></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap>Description:</td>
          <td><textarea name="description" cols="32" rows="10"><?php echo $row_rs_animals['description']; ?></textarea></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap>Postscript:</td>
          <td><strong>
            <textarea name="adoptability" cols="32" rows="5" id="adoptability"><?php echo $row_rs_animals['adoptability']; ?></textarea>
          </strong></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Image_path:</td>
          <td><input type="text" name="image_path" value="<?php echo $row_rs_animals['image_path']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Heartworm_test:</td>
          <td><input name="heartworm_test" type="text" value="<?php echo $row_rs_animals['heartworm_test']; ?>" size="10"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Dhlpp_1:</td>
          <td><input type="text" name="dhlpp_1" value="<?php echo $row_rs_animals['dhlpp_1']; ?>" size="10"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Dhlpp_2:</td>
          <td><input type="text" name="dhlpp_2" value="<?php echo $row_rs_animals['dhlpp_2']; ?>" size="10"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Dhlpp_3:</td>
          <td><input type="text" name="dhlpp_3" value="<?php echo $row_rs_animals['dhlpp_3']; ?>" size="10"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Fiv_felv_test:</td>
          <td><input type="text" name="fiv_felv_test" value="<?php echo $row_rs_animals['fiv_felv_test']; ?>" size="10"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">FVRCP due: </td>
          <td><label>
            <input name="FVRCP_due" type="text" id="FVRCP_due" value="<?php echo $row_rs_animals['FVRCP_due']; ?>" size="10" />
          </label></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Fvrcp_1:</td>
          <td><input type="text" name="fvrcp_1" value="<?php echo $row_rs_animals['fvrcp_1']; ?>" size="10"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Fvrcp_2:</td>
          <td><input type="text" name="fvrcp_2" value="<?php echo $row_rs_animals['fvrcp_2']; ?>" size="10"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Rabies Tag No.:</td>
          <td><input type="text" name="rabies_type_test" value="<?php echo $row_rs_animals['rabies_type_test']; ?>" size="15"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Rabies due: </td>
          <td><label>
            <input name="rabies_due" type="text" id="rabies_due" value="<?php echo $row_rs_animals['rabies_due']; ?>" size="10" />
          </label></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Rabies_1year:</td>
          <td><input type="text" name="rabies_1year" value="<?php echo $row_rs_animals['rabies_1year']; ?>" size="10"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Rabies_3year:</td>
          <td><input type="text" name="rabies_3year" value="<?php echo $row_rs_animals['rabies_3year']; ?>" size="10"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Fecal_date_result_meds:</td>
          <td><input type="text" name="fecal_date_result_meds" value="<?php echo $row_rs_animals['fecal_date_result_meds']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Worming_1:</td>
          <td><input type="text" name="worming_1" value="<?php echo $row_rs_animals['worming_1']; ?>" size="10"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Worming_2:</td>
          <td><input type="text" name="worming_2" value="<?php echo $row_rs_animals['worming_2']; ?>" size="10"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Worming_3:</td>
          <td><label>
            <input name="worming_3" type="text" id="worming_3" value="<?php echo $row_rs_animals['worming_3']; ?>" size="10" />
          </label></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Spay/Neuter Date: </td>
          <td><input name="spay_neuter" type="text" id="spay_neuter" value="<?php echo $row_rs_animals['spay_neuter']; ?>" size="10" /></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap>Other_medical_info:</td>
          <td><textarea name="other_medical_info" cols="32" rows="5"><?php echo $row_rs_animals['other_medical_info']; ?></textarea></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Vet_clinic:</td>
          <td><input type="text" name="vet_clinic" value="<?php echo $row_rs_animals['vet_clinic']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Location:</td>
          <td><select name="location">
            <option value="tigger_house" <?php if (!(strcmp("tigger_house", $row_rs_animals['location']))) {echo "selected=\"selected\"";} ?>>Tigger House</option>
            <option value="ASAL_foster_Afghan" <?php if (!(strcmp("ASAL_foster_Afghan", $row_rs_animals['location']))) {echo "selected=\"selected\"";} ?>>ASAL Foster Afghan</option>
            <option value="ASAL_foster_US" <?php if (!(strcmp("ASAL_foster_US", $row_rs_animals['location']))) {echo "selected=\"selected\"";} ?>>ASAL Foster US</option>
            <option value="Adopted" <?php if (!(strcmp("Adopted", $row_rs_animals['location']))) {echo "selected=\"selected\"";} ?>>Adopted</option>
          </select>          </td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap>Feeding_instructions:</td>
          <td><textarea name="feeding_instructions" cols="32" rows="5"><?php echo $row_rs_animals['feeding_instructions']; ?></textarea></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Status:</td>
          <td><select name="status">
            <option value="Available" <?php if (!(strcmp("Available", $row_rs_animals['status']))) {echo "selected=\"selected\"";} ?>>Available</option>
            <option value="Adopted" <?php if (!(strcmp("Adopted", $row_rs_animals['status']))) {echo "selected=\"selected\"";} ?>>Adopted</option>
<option value="Not Available" <?php if (!(strcmp("Not Available", $row_rs_animals['status']))) {echo "selected=\"selected\"";} ?>>Not Available</option>
            <option value="Detention" <?php if (!(strcmp("Detention", $row_rs_animals['status']))) {echo "selected=\"selected\"";} ?>>Detention</option>
            <option value="Died" <?php if (!(strcmp("Died", $row_rs_animals['status']))) {echo "selected=\"selected\"";} ?>>Died</option>
            <option value="Euthanized" <?php if (!(strcmp("Euthanized", $row_rs_animals['status']))) {echo "selected=\"selected\"";} ?>>Euthanized</option>
            <option value="Featured" <?php if (!(strcmp("Featured", $row_rs_animals['status']))) {echo "selected=\"selected\"";} ?>>Featured</option>
          </select>          </td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Microchip:</td>
          <td><input type="text" name="microchip" value="<?php echo $row_rs_animals['microchip']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap>Notes:</td>
          <td><textarea name="notes" cols="32" rows="5" id="notes"><?php echo $row_rs_animals['notes']; ?></textarea></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">&nbsp;</td>
          <td><input type="submit" value="Update record"></td>
        </tr>
      </table>
      <div align="center">
        <p><input type="hidden" name="MM_update" value="form1">
          <input type="hidden" name="animal_id" value="<?php echo $row_rs_animals['animal_id']; ?>">
        </p>
        <h1><a href="animal_delete.php?animal_id=<?php echo $row_rs_animals['animal_id']; ?>">Delete This Animal!</a><a href="animal_delete.php?animal_id=<?php echo $row_rs_animals['animal_id']; ?>"><br />
        You Will Have A Chance to Change Your Mind </a></h1>
      </div>
    </form>
    <p>&nbsp;</p>
    <p align="left">&nbsp;</p>
    <p align="left">&nbsp;</p>
  <!-- InstanceEndEditable --></div>
</div>
<div id="footer">&copy;2009 Afghan Stray Animal League and JLS Consulting, Inc </div>
</body><!-- InstanceEnd -->