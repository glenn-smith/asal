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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "adopter_add")) {
  $insertSQL = sprintf("INSERT INTO adopters (animal_id, animal_nu_name, `first`, `last`, telephone, tele_cell, tele_work, address1, address2, city, `state`, zip, email, adoption_date, adoption_fee) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['animal_id'], "int"),
                       GetSQLValueString($_POST['animal_nu_name'], "text"),
                       GetSQLValueString($_POST['first'], "text"),
                       GetSQLValueString($_POST['last'], "text"),
                       GetSQLValueString($_POST['telephone'], "text"),
                       GetSQLValueString($_POST['tele_cell'], "text"),
                       GetSQLValueString($_POST['tele_work'], "text"),
                       GetSQLValueString($_POST['address1'], "text"),
                       GetSQLValueString($_POST['address2'], "text"),
                       GetSQLValueString($_POST['city'], "text"),
                       GetSQLValueString($_POST['state'], "text"),
                       GetSQLValueString($_POST['zip'], "int"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['adoption_date'], "date"),
                       GetSQLValueString($_POST['adoption_fee'], "double"));

  mysql_select_db($database_ASAL, $ASAL);
  $Result1 = mysql_query($insertSQL, $ASAL) or die(mysql_error());

  $insertGoTo = "adopters.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_ASAL, $ASAL);
$query_rs_adopters = "SELECT * FROM adopters";
$rs_adopters = mysql_query($query_rs_adopters, $ASAL) or die(mysql_error());
$row_rs_adopters = mysql_fetch_assoc($rs_adopters);
$totalRows_rs_adopters = mysql_num_rows($rs_adopters);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/asal_admin.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>

<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>adopter add</title>
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

function WAValidateNM(formElement,errorMsg,minLength,maxLength,allowDecimals,roundDecimals,reformatDecimals,punctuationMarks,focusIt,stopIt,required)  {
  var isValid = true;
  var theThousand = punctuationMarks.charAt(0);
  var theDecimal = punctuationMarks.charAt(1);
  var theCheck = 11/10;
  var trueDecimal = (String(theCheck).charAt(1));
  var value = formElement.value;
  var decimalIndex = value.length;
  if (punctuationMarks.indexOf(trueDecimal)<0 && value.indexOf(trueDecimal)>=0)  {
    isValid = false;
  }
  if (value.lastIndexOf(theDecimal)>=0) {
    decimalIndex = value.lastIndexOf(theDecimal);
  }
  while (value.indexOf(theThousand)>=0)  {
    decimalIndex = value.length;
    if (value.lastIndexOf(theDecimal)>=0) {
      decimalIndex = value.lastIndexOf(theDecimal);
    }
    if ((decimalIndex-(value.lastIndexOf(theThousand)+1))%3 != 0)
      isValid = false;
    value = value.substring(0,value.lastIndexOf(theThousand)) + value.substring(value.lastIndexOf(theThousand)+1);
  }
  if (trueDecimal != theDecimal)  {
    while (value.indexOf(theDecimal)>=0)  {
      value = value.substring(0,value.indexOf(theDecimal)) + trueDecimal + value.substring(value.indexOf(theDecimal)+1);
    }
  }
  if ((!document.WAFV_Stop && !formElement.WAFV_Stop) && !(!required && value==""))  {
    for (var x=0; x<value.length; x++)  {
      if  ((value.charAt(x)<0 || value.charAt(x) > 9) && (value.charAt(x) != " " && value.charAt(x) != "," && value.charAt(x) != "."))  {
        isValid = false;
      }
    }
    if (value == "")  {
      isValid = false;
    }
    var oldVal = String(value);
    if (oldVal.indexOf(trueDecimal)>=0)  {
      while (oldVal.charAt(oldVal.length-1)=="0" || oldVal.charAt(oldVal.length-1) == trueDecimal) {
        if (oldVal.charAt(oldVal.length-1) == trueDecimal) {
          oldVal = oldVal.substring(0,oldVal.length-1);
          break;
        }
        else oldVal = oldVal.substring(0,oldVal.length-1);
      }
      if (oldVal.indexOf(trueDecimal)==0)
        oldVal = "0" + oldVal;
    }
    if (String(allowDecimals) !="" )  {
      if (String(value).indexOf(".") > 0 && ((String(value).indexOf(".") + allowDecimals + 2 <= String(value).length) || allowDecimals == 0))  {
        isValid = false;
      }
    }
    value = parseFloat(value);
    if (isNaN(value))  {
      isValid = false;
    }
    else if (String(value).length!=String(oldVal).length && String(oldVal).substring(String(value).length+1).search(/^\.?0*$/) == -1 ) {
	  isValid = false;
    }
    else if ((String(minLength) != "" && minLength > value) || (String(maxLength) != "" && maxLength < value)) {
      isValid = false;
    }
  }
  if (!isValid)  {
    WAAddError(formElement,errorMsg,focusIt,stopIt);
  }
  else  {
    if (value != "")  {
      if (roundDecimals != "")  {
        value = Math.round(value*roundDecimals)/roundDecimals;
      }
      if (reformatDecimals != "")  {
        value = String(value);
        if (value.indexOf(trueDecimal)<0)
          value += trueDecimal;
        if (value.indexOf(trueDecimal) < value.length - reformatDecimals)  {
          value = value.substring(0,value.indexOf(trueDecimal) + reformatDecimals + 1);
        }
        else  {
          while (value.indexOf(trueDecimal) > value.length - reformatDecimals - 1)  {
             value += "0";
          }
        }
      }
    }
    if (trueDecimal != theDecimal)  {
      value = String(value);
      while (value.indexOf(trueDecimal)>=0)  {
        value = value.substring(0,value.indexOf(trueDecimal)) + theDecimal + value.substring(value.indexOf(trueDecimal)+1);
      }
    }
    if (roundDecimals != "" || reformatDecimals != "")
      formElement.value = value;
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
    <h1 align="left">HSFC Administration<br />
    Adopter Add </h1>
    <form action="<?php echo $editFormAction; ?>" method="POST" name="adopter_add" id="adopter_add" onsubmit="WAValidateDT(document.adopter_add.adoption_date,'- Invalid date or time',true,/.*/,'yyyy-mm-dd','','',false,/.*/,'','','',document.adopter_add.adoption_date,0,false);WAValidateNM(document.adopter_add.animal_id,'- Invalid number format',0,100000000,0,'','',',.',document.adopter_add.animal_id,0,true);WAAlertErrors('The following errors were found','Correct invalid entries to continue',true,false);return document.MM_returnValue">
      <table align="center">
        <tr valign="baseline">
          <td nowrap align="right">Adopted Animal ID </td>
          <td>
          <input name="animal_id" type="text" id="animal_id" size="5" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Adopter ID: </td>
          <td>&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Animal_nu_name:</td>
          <td><input type="text" name="animal_nu_name" value="" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">First:</td>
          <td><input type="text" name="first" value="" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Last:</td>
          <td><input type="text" name="last" value="" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Address1:</td>
          <td><input type="text" name="address1" value="" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Address2:</td>
          <td><input type="text" name="address2" value="" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">City:</td>
          <td><input type="text" name="city" value="" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">State:</td>
          <td><input type="text" name="state" value="" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Zip:</td>
          <td><input type="text" name="zip" value="" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right"> Home Tele:</td>
          <td><input name="telephone" type="text" id="telephone" size="12" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Work Tele:</td>
          <td><input name="tele_work" type="text" id="tele_work" size="12" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Cell Tele:</td>
          <td><input name="tele_cell" type="text" id="tele_cell" size="12" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Email:</td>
          <td><input type="text" name="email" value="" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Adoption_date:</td>
          <td><input type="text" name="adoption_date" value="" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Adoption_fee:</td>
          <td><input type="text" name="adoption_fee" value="" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td colspan="2" align="right" nowrap><div align="center">
            <input type="submit" value="Insert record">
          </div></td>
        </tr>
      </table>
      <input type="hidden" name="MM_insert" value="form2">
      <input type="hidden" name="MM_insert" value="adopter_add">
    </form>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <form method="post" name="form1" id="form1">
    </form>
  <!-- InstanceEndEditable --></div>
</div>
<div id="footer">&copy;2009 Afghan Stray Animal League and JLS Consulting, Inc </div>
</body><!-- InstanceEnd -->