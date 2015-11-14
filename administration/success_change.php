<?php require_once('../Connections/ASAL.php'); ?>
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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "success_change")) {
  $updateSQL = sprintf("UPDATE success SET `first`=%s, `last`=%s, animal_name=%s, animal_type=%s, telephone=%s, address=%s, city=%s, `state`=%s, zip=%s, email=%s, story_date=%s, story=%s, image_path=%s, image_2_path=%s, show_web=%s WHERE success_id=%s",
                       GetSQLValueString($_POST['first'], "text"),
                       GetSQLValueString($_POST['last'], "text"),
                       GetSQLValueString($_POST['animal_name'], "text"),
                       GetSQLValueString($_POST['animal_type'], "text"),
                       GetSQLValueString($_POST['telephone'], "text"),
                       GetSQLValueString($_POST['address'], "text"),
                       GetSQLValueString($_POST['city'], "text"),
                       GetSQLValueString($_POST['state'], "text"),
                       GetSQLValueString($_POST['zip'], "int"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['story_date'], "date"),
                       GetSQLValueString($_POST['story'], "text"),
                       GetSQLValueString($_POST['image_path'], "text"),
                       GetSQLValueString($_POST['image_2_path'], "text"),
                       GetSQLValueString($_POST['show_web'], "text"),
                       GetSQLValueString($_POST['success_id'], "int"));

  mysql_select_db($database_ASAL, $ASAL);
  $Result1 = mysql_query($updateSQL, $ASAL) or die(mysql_error());

  $updateGoTo = "/administration/success_stories.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_rs_success = "-1";
if (isset($_GET['success_id'])) {
  $colname_rs_success = (get_magic_quotes_gpc()) ? $_GET['success_id'] : addslashes($_GET['success_id']);
}
mysql_select_db($database_ASAL, $ASAL);
$query_rs_success = sprintf("SELECT * FROM success WHERE success_id = %s", GetSQLValueString($colname_rs_success, "int"));
$rs_success = mysql_query($query_rs_success, $ASAL) or die(mysql_error());
$row_rs_success = mysql_fetch_assoc($rs_success);
$totalRows_rs_success = mysql_num_rows($rs_success);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/asal_admin.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>

<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>ASAL Success Change</title>
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
</script><!-- InstanceEndEditable -->
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
    <h1 align="left">ASAL Administration<br />
      Success Story Change</h1>
    <p align="center">
      <input type="image" name="imageField" src="<?php echo $row_rs_success['image_path']; ?>" />
    </p>
    
    
    <form action="<?php echo $editFormAction; ?>" method="post" name="success_change" id="success_change" onsubmit="WAValidateDT(document.success_change.story_date,'- Invalid date or time',true,/.*/,'yyyy-mm-dd','','',false,/.*/,'','','',document.success_change.story_date,0,true);WAAlertErrors('The following errors were found','Correct invalid entries to continue',true,false);return document.MM_returnValue">
      <table align="center">
        <tr valign="baseline">
          <td nowrap align="right">Success_id:</td>
          <td><?php echo $row_rs_success['success_id']; ?></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">First:</td>
          <td><input type="text" name="first" value="<?php echo $row_rs_success['first']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Last:</td>
          <td><input type="text" name="last" value="<?php echo $row_rs_success['last']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Animal Type: </td>
          <td><select name="animal_type" id="animal_type">
            <option value="Dog" selected="selected" <?php if (!(strcmp("Dog", $row_rs_success['animal_type']))) {echo "selected=\"selected\"";} ?>>Dog</option>
            <option value="Cat" <?php if (!(strcmp("Cat", $row_rs_success['animal_type']))) {echo "selected=\"selected\"";} ?>>Cat</option>
            <option value="Horse" <?php if (!(strcmp("Horse", $row_rs_success['animal_type']))) {echo "selected=\"selected\"";} ?>>Horse</option>
            <option value="Rabbit" <?php if (!(strcmp("Rabbit", $row_rs_success['animal_type']))) {echo "selected=\"selected\"";} ?>>Rabbit</option>
<option value="Fish" <?php if (!(strcmp("Fish", $row_rs_success['animal_type']))) {echo "selected=\"selected\"";} ?>>Fish</option>
            <option value="Bird" <?php if (!(strcmp("Bird", $row_rs_success['animal_type']))) {echo "selected=\"selected\"";} ?>>Bird</option>
            <option value="Guinea Pig" <?php if (!(strcmp("Guinea Pig", $row_rs_success['animal_type']))) {echo "selected=\"selected\"";} ?>>Guinea Pig</option>
            <option value="Chinchilla" <?php if (!(strcmp("Chinchilla", $row_rs_success['animal_type']))) {echo "selected=\"selected\"";} ?>>Chinchilla</option>
            <option value="Hamster" <?php if (!(strcmp("Hamster", $row_rs_success['animal_type']))) {echo "selected=\"selected\"";} ?>>Hamster</option>
            <option value="Pony" <?php if (!(strcmp("Pony", $row_rs_success['animal_type']))) {echo "selected=\"selected\"";} ?>>Pony</option>
            <option value="Other" <?php if (!(strcmp("Other", $row_rs_success['animal_type']))) {echo "selected=\"selected\"";} ?>>Other</option>
            <?php
do {  
?>
            <option value="<?php echo $row_rs_success['animal_type']?>"<?php if (!(strcmp($row_rs_success['animal_type'], $row_rs_success['animal_type']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_success['animal_type']?></option>
            <?php
} while ($row_rs_success = mysql_fetch_assoc($rs_success));
  $rows = mysql_num_rows($rs_success);
  if($rows > 0) {
      mysql_data_seek($rs_success, 0);
	  $row_rs_success = mysql_fetch_assoc($rs_success);
  }
?>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Animal Name: </td>
          <td><input name="animal_name" type="text" id="animal_name" value="<?php echo $row_rs_success['animal_name']; ?>" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Telephone:</td>
          <td><input type="text" name="telephone" value="<?php echo $row_rs_success['telephone']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Address:</td>
          <td><input type="text" name="address" value="<?php echo $row_rs_success['address']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">City:</td>
          <td><input type="text" name="city" value="<?php echo $row_rs_success['city']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">State:</td>
          <td><input type="text" name="state" value="<?php echo $row_rs_success['state']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Zip:</td>
          <td><input type="text" name="zip" value="<?php echo $row_rs_success['zip']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Email:</td>
          <td><input type="text" name="email" value="<?php echo $row_rs_success['email']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Story_date:</td>
          <td><input type="text" name="story_date" value="<?php echo $row_rs_success['story_date']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap>Story:</td>
          <td><textarea name="story" cols="32" rows="12"><?php echo $row_rs_success['story']; ?></textarea></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Image_path:</td>
          <td><input type="text" name="image_path" value="<?php echo $row_rs_success['image_path']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Image_2_path:</td>
          <td><input type="text" name="image_2_path" value="<?php echo $row_rs_success['image_2_path']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Show Web? </td>
          <td><select name="show_web" id="show_web">
            <option value="Yes">Yes</option>
            <option value="No">No</option>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">&nbsp;</td>
          <td><input type="submit" value="Update record"></td>
        </tr>
      </table>
      <input type="hidden" name="MM_update" value="success_change">
      <input type="hidden" name="success_id" value="<?php echo $row_rs_success['success_id']; ?>">
    </form>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p align="left">&nbsp;</p>
  <!-- InstanceEndEditable --></div>
</div>
<div id="footer">&copy;2009 Afghan Stray Animal League and JLS Consulting, Inc </div>
</body><!-- InstanceEnd -->