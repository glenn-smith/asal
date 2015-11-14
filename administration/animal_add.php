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
	function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
	{
	  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;
	
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
	
	mysql_select_db($database_ASAL, $ASAL);
	$query_rs_animals = "SELECT * FROM animals";
	$rs_animals = mysql_query($query_rs_animals, $ASAL) or die(mysql_error());
	$row_rs_animals = mysql_fetch_assoc($rs_animals);
	$totalRows_rs_animals = mysql_num_rows($rs_animals);
	
	$editFormAction = $_SERVER['PHP_SELF'];
	if (isset($_SERVER['QUERY_STRING'])) {
	  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	}
	
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "animal_add")) {
	  $insertSQL = sprintf("INSERT INTO animals (foster_id, adopter_id, case_no, name, source, source_name, intake_date, adopt_date, animal_type, breed, weight, color, sex, birth_date, death_date, `description`, image_path, adoptability, heartworm_test, dhlpp_1, dhlpp_2, dhlpp_3, fiv_felv_test, FVRCP_due, fvrcp_1, fvrcp_2, rabies_type_test, rabies_due, rabies_1year, rabies_3year, fecal_date_result_meds, worming_1, worming_2, worming_3, spay_neuter, other_medical_info, vet_clinic, location, HSFC_Bldg_room, feeding_instructions, status, microchip, notes) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
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
						   GetSQLValueString($_POST['HSFC_Bldg_room'], "int"),
						   GetSQLValueString($_POST['feeding_instructions'], "text"),
						   GetSQLValueString($_POST['status'], "text"),
						   GetSQLValueString($_POST['microchip'], "int"),
						   GetSQLValueString($_POST['notes'], "text"));
	
	  mysql_select_db($database_ASAL, $ASAL);
	  $Result1 = mysql_query($insertSQL, $ASAL) or die(mysql_error());
	
	  $insertGoTo = "/administration/animal_inventory.php";
	  if (isset($_SERVER['QUERY_STRING'])) {
		$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
		$insertGoTo .= $_SERVER['QUERY_STRING'];
	  }
	  header(sprintf("Location: %s", $insertGoTo));
	}
	
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "animal_add")) {
	  $insertSQL = sprintf("INSERT INTO animals (foster_id, adopter_id, animal_id, name, source, source_name, intake_date, adopt_date, animal_type, breed, weight, color, sex, birth_date, death_date, `description`, adomptability, image_path, heartworm_test, dhlpp_1, dhlpp_2, dhlpp_3, fiv_felv_test, FVRCP_due, fvrcp_1, fvrcp_2, rabies_type_test, rabies_due, rabies_1year, rabies_3year, fecal_date_result_meds, worming_1, worming_2, worming_3, spay_neuter, other_medical_info, vet_clinic, location, HSFC_Bldg_room, feeding_instructions, status, microchip, notes) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, ,%s, %s%s, %s, %s, %s, %s, %s, %s, %s)",
						   GetSQLValueString($_POST['foster_id'], "int"),
						   GetSQLValueString($_POST['adopter_id'], "int"),
						   GetSQLValueString($_POST['animal_id'], "int"),
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
						   GetSQLValueString($_POST['adoptability'], "text"),
						   GetSQLValueString($_POST['image_path'], "text"),
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
						   GetSQLValueString($_POST['HSFC_Bldg_room'], "int"),
						   GetSQLValueString($_POST['feeding_instructions'], "text"),
						   GetSQLValueString($_POST['status'], "text"),
						   GetSQLValueString($_POST['microchip'], "int"),
						   GetSQLValueString($_POST['notes'], "int"),
	  mysql_select_db($database_ASAL, $ASAL));
	  $Result1 = mysql_query($insertSQL, $ASAL) or die(mysql_error());
	
	  $insertGoTo = "http://hsfc.org/administration/animal_inventory.php";
	  if (isset($_SERVER['QUERY_STRING'])) {
		$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
		$insertGoTo .= $_SERVER['QUERY_STRING'];
	  }
	  header(sprintf("Location: %s", $insertGoTo));
	}
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/asal_admin.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>

<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>ASAL - Animal Add</title>
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="head" -->
<script type="text/JavaScript">
<!--
function WAtrimIt(theString,leaveLeft,leaveRight)  {
  if (!leaveLeft)  {
    while (theString.charAt(0) == " ")
      theString = theString.substring(1);
  }
  if (!leaveRight)  {
    while (theString.charAt(theString.length-1) == " ")
      theString = theString.substring(0,theString.length-1);
  }
  return theString;
}

function WAFV_GetValueFromInputType(formElement,inputType,trimWhite) {
  var value="";
  if (inputType == "select")  {
    if (formElement.selectedIndex != -1 && formElement.options[formElement.selectedIndex].value && formElement.options[formElement.selectedIndex].value != "") {
      value = formElement.options[formElement.selectedIndex].value;
    }
  }
  else if (inputType == "checkbox")  {
    if (formElement.length)  {
      for (var x=0; x<formElement.length ; x++)  {
        if (formElement[x].checked && formElement[x].value!="")  {
          value = formElement[x].value;
          break;
        }
      }
    }
    else if (formElement.checked)
      value = formElement.value;
  }
  else if (inputType == "radio")  {
    if (formElement.length)  {
      for (var x=0; x<formElement.length; x++)  {
        if (formElement[x].checked && formElement[x].value!="")  {
          value = formElement[x].value;
          break;
        }
      }
    }
    else if (formElement.checked)
      value = formElement.value;
  }
  else if (inputType == "radiogroup")  {
    for (var x=0; x<formElement.length; x++)  {
      if (formElement[x].checked && formElement[x].value!="")  {
        value = formElement[x].value;
        break;
      }
    }
  }
  else  {
    var value = formElement.value;
  }
  if (trimWhite)  {
    value = WAtrimIt(value);
  }
  return value;
}

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

function WAValidateRQ(formElement,errorMsg,focusIt,stopIt,trimWhite,inputType)  {
  var isValid = true;
  if (!document.WAFV_Stop && !formElement.WAFV_Stop)  {
    var value=WAFV_GetValueFromInputType(formElement,inputType,trimWhite);
    if (value == "")  {
	    isValid = false;
    }
  }
  if (!isValid)  {
    WAAddError(formElement,errorMsg,focusIt,stopIt);
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
    <h1 align="left">ASAL Administration - Animal Add</h1>
    <p align="left"> <strong>1. Three required fields to add an animal (Name, Birth Date, Intake Date); they are asterisked (*)<br /> 
      2. Dates entered for the first time should be entered in the format MM/DD/YYYY (04/24/2006) <br />
      3. Dates can be edited using the format: YYYY-MM-DD (2006-04-24)
    </strong></p>
    <p align="left">&nbsp;</p>
    
    <form action="<?php echo $editFormAction; ?>" method="POST" name="animal_add" id="animal_add" onsubmit="WAValidateRQ(document.animal_add.name,'- Entry is required',document.animal_add.name,1,false,'text');WAValidateDT(document.animal_add.birth_date,'- Invalid date or time',true,/.*/,'yyyy-mm-dd','','',false,/.*/,'','','',document.animal_add.birth_date,0,true);WAValidateDT(document.animal_add.intake_date,'- Invalid date or time',true,/.*/,'yyyy-mm-dd','','',false,/.*/,'','','',document.animal_add.intake_date,0,true);WAValidateDT(document.animal_add.adopt_date,'- Invalid date or time',true,/.*/,'yyyy-mm-dd','','',false,/.*/,'','','',document.animal_add.adopt_date,0,false);WAValidateDT(document.animal_add.death_date,'- Invalid date or time',true,/.*/,'yyyy-mm-dd','','',false,/.*/,'','','',document.animal_add.death_date,0,false);WAValidateDT(document.animal_add.heartworm_test,'- Invalid date or time',true,/.*/,'yyyy-mm-dd','','',false,/.*/,'','','',document.animal_add.heartworm_test,0,false);WAValidateDT(document.animal_add.dhlpp_1,'- Invalid date or time',true,/.*/,'yyyy-mm-dd','','',false,/.*/,'','','',document.animal_add.dhlpp_1,0,false);WAValidateDT(document.animal_add.dhlpp_2,'- Invalid date or time',true,/.*/,'yyyy-mm-dd','','',false,/.*/,'','','',document.animal_add.dhlpp_2,0,false);WAValidateDT(document.animal_add.dhlpp_3,'- Invalid date or time',true,/.*/,'yyyy-mm-dd','','',false,/.*/,'','','',document.animal_add.dhlpp_3,0,false);WAValidateDT(document.animal_add.fiv_felv_test,'- Invalid date or time',true,/.*/,'yyyy-mm-dd','','',false,/.*/,'','','',document.animal_add.fiv_felv_test,0,false);WAValidateDT(document.animal_add.FVRCP_due,'- Invalid date or time',true,/.*/,'yyyy-mm-dd','','',false,/.*/,'','','',document.animal_add.FVRCP_due,0,false);WAValidateDT(document.animal_add.fvrcp_1,'- Invalid date or time',true,/.*/,'yyyy-mm-dd','','',false,/.*/,'','','',document.animal_add.fvrcp_1,0,false);WAValidateDT(document.animal_add.fvrcp_2,'- Invalid date or time',true,/.*/,'yyyy-mm-dd','','',false,/.*/,'','','',document.animal_add.fvrcp_2,0,false);WAValidateDT(document.animal_add.rabies_due,'- Invalid date or time',true,/.*/,'yyyy-mm-dd','','',false,/.*/,'','','',document.animal_add.rabies_due,0,false);WAValidateDT(document.animal_add.rabies_1year,'- Invalid date or time',true,/.*/,'yyyy-mm-dd','','',false,/.*/,'','','',document.animal_add.rabies_1year,0,false);WAValidateDT(document.animal_add.rabies_3year,'- Invalid date or time',true,/.*/,'yyyy-mm-dd','','',false,/.*/,'','','',document.animal_add.rabies_3year,0,false);WAValidateDT(document.animal_add.worming_1,'- Invalid date or time',true,/.*/,'yyyy-mm-dd','','',false,/.*/,'','','',document.animal_add.worming_1,0,false);WAValidateDT(document.animal_add.worming_2,'- Invalid date or time',true,/.*/,'yyyy-mm-dd','','',false,/.*/,'','','',document.animal_add.worming_2,0,false);WAValidateDT(document.animal_add.worming_3,'- Invalid date or time',true,/.*/,'yyyy-mm-dd','','',false,/.*/,'','','',document.animal_add.worming_3,0,false);WAValidateDT(document.animal_add.spay_neuter,'- Invalid date or time',true,/.*/,'yyyy-mm-dd','','',false,/.*/,'','','',document.animal_add.spay_neuter,0,false);WAAlertErrors('The following errors were found','Correct invalid entries to continue',true,false);return document.MM_returnValue">
      <table align="center">
        <tr valign="baseline">
          <td nowrap align="right">Foster_id:</td>
          <td><input type="text" name="foster_id" value="" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Adopter_id:</td>
          <td><input type="text" name="adopter_id" value="" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Case_No:</td>
          <td><label>
            <input name="case_no" type="text" id="case_no" />
          </label></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">*Name:</td>
          <td><input type="text" name="name" value="" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">*Birth_date:</td>
          <td><input type="text" name="birth_date" size="10" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Source:</td>
          <td><label>
            <select name="source" id="source">
              <option value=" " selected="selected"> </option>
              <option value="Abandoned">Abandoned</option>
              <option value="Stray">Stray</option>
              <option value="Owner Surrender">Owner Surrender</option>
              <option value="Confiscated">Conficated</option>
              <option value="Transfer">Transfer</option>
              <option value="Born @ HSFC">Born @ HSFC</option>
              <option value="Returned">Returned</option>
              <option value="Other">Other</option>
            </select>
          </label></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Source Name: </td>
          <td><label>
            <input name="source_name" type="text" id="source_name" size="32" />
          </label></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">*Intake_date:</td>
          <td><input type="text" name="intake_date" size="10"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Adopt_date:</td>
          <td><input type="text" name="adopt_date" size="10"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Animal_type:</td>
          <td><select name="animal_type" id="animal_type">
            <option value="Dog">Dog</option>
            <option value="Cat">Cat</option>
            <option value="Horse">Horse</option>
            <option value="Rabbit">Rabbit</option>
            <option value="Fish">Fish</option>
            <option value="Bird">Bird</option>
            <option value="Guinea Pig">Guinea Pig</option>
            <option value="Chinchilla">Chinchilla</option>
            <option value="Hamster">Hamster</option>
            <option value="Pony">Pony</option>
            <option value="Other">Other</option>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Breed:</td>
          <td><input type="text" name="breed" value="" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Weight:</td>
          <td><input type="text" name="weight" value="" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Color:</td>
          <td><input type="text" name="color" value="" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Sex:</td>
          <td><select name="sex" id="sex">
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Neutered Male">Neutered Male</option>
            <option value="Spayed Female">Spayed Female</option>
            <option value="Stallion">Stallion</option>
            <option value="Gelding">Gelding</option>
            <option value="Mare">Mare</option>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Death_date:</td>
          <td><input type="text" name="death_date" size="10"></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap>Description:</td>
          <td><textarea name="description" cols="32" rows="10"></textarea></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Adoptability:</td>
          <td><input name="adoptability" type="text" id="adoptability" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Image_path:</td>
          <td><input type="text" name="image_path" value="/images/picture_coming_soon.jpg" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Heartworm_test:</td>
          <td><input type="text" name="heartworm_test" size="10"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Dhlpp_1:</td>
          <td><input type="text" name="dhlpp_1" size="10"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Dhlpp_2:</td>
          <td><input type="text" name="dhlpp_2" size="10"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Dhlpp_3:</td>
          <td><input type="text" name="dhlpp_3" size="10"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Fiv_felv_test:</td>
          <td><input type="text" name="fiv_felv_test" size="10"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">FVRCP_due:</td>
          <td><input type="text" name="FVRCP_due" size="10"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Fvrcp_1:</td>
          <td><input type="text" name="fvrcp_1" size="10"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Fvrcp_2:</td>
          <td><input type="text" name="fvrcp_2" size="10"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Rabies Tag No.:</td>
          <td><input type="text" name="rabies_type_test" value="" size="15"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Rabies_due:</td>
          <td><input type="text" name="rabies_due" size="10"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Rabies_1year:</td>
          <td><input type="text" name="rabies_1year" size="10"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Rabies_3year:</td>
          <td><input type="text" name="rabies_3year" size="10"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Fecal_date_result_meds:</td>
          <td><input type="text" name="fecal_date_result_meds" value="" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Worming_1:</td>
          <td><input type="text" name="worming_1" size="10"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Worming_2:</td>
          <td><input type="text" name="worming_2" size="10"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Worming_3:</td>
          <td><input type="text" name="worming_3" size="10"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Spay_neuter:</td>
          <td><input type="text" name="spay_neuter" size="10"></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap>Other_medical_info:</td>
          <td><textarea name="other_medical_info" cols="32" rows="5"></textarea></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Vet_clinic:</td>
          <td><input type="text" name="vet_clinic" value="" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Location:</td>
          <td><select name="location" id="location">
            <option value="tiggerhouse" selected="selected">Tigger House</option>
            <option value="foster_afghan">ASAL Foster Afghan</option>
            <option value="foster_us">ASAL Foster US</option>
            <option value="Adopted">Adopted</option>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">HSFC_Bldg_room:</td>
          <td><input type="text" name="HSFC_Bldg_room" value="" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap>Feeding_instructions:</td>
          <td><textarea name="feeding_instructions" cols="32" rows="5"></textarea></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Status:</td>
          <td><select name="status" id="status">
            <option value="Not Available">Not Available</option>
            <option value="Available">Available</option>
            <option value="Adopted">Adopted</option>
            <option value="Detention">Detention</option>
            <option value="Featured">Featured</option>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Microchip:</td>
          <td><input type="text" name="microchip" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap>Notes:</td>
          <td><textarea name="notes" cols="32" rows="5" id="notes"></textarea></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">&nbsp;</td>
          <td><input type="submit" value="Insert record"></td>
        </tr>
      </table>
      
      <input type="hidden" name="MM_insert" value="animal_add">
    </form>
    <p>&nbsp;</p>
    <p align="left">&nbsp;</p>
    <p align="left">&nbsp;</p>
  <!-- InstanceEndEditable --></div>
</div>
<div id="footer">&copy;2009 Afghan Stray Animal League and JLS Consulting, Inc </div>
</body><!-- InstanceEnd -->