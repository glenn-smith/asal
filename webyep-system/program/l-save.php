<?php
// WebYep
// (C) Objective Development Software GmbH
// http://www.obdev.at

	$webyep_bDocumentPage = false;
	$webyep_sIncludePath = ".";
	include_once("$webyep_sIncludePath/webyep.php");
	include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYTextField.php");
	
	$bLicenseSaved = false;
	$oP = od_nil;
	$oF = od_nil;
	$s = "";
	$oTF = od_nil;
	$oTF =& new WYTextField("CODE"); // text field name must be adapted in LC also
	$oTF->setWidth(40);
	$sLicenseCode = $oTF->sValue();

   $sHostName = $_SERVER['HTTP_HOST'];
   if (!$sHostName) $sHostName = $_SERVER['SERVER_NAME'];


	if ($sLicenseCode) {
		$oP = od_clone($goApp->oDataPath);
		$oP->addComponent("license");
		$oF =& new WYFile($oP);
		if ($oF->bExists()) $s = $oF->sContent() . "\r\n";
		$oF->setContent($s . $sLicenseCode);
		if (!$oF->bWrite()) $goApp->log("could not write license file");
		$bLicenseSaved = true;
	}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><!-- InstanceBegin template="/Templates/panels.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="HeadPHPCode" -->
<?php
?>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="doctitle" -->
<title>
<?php echo $webyep_sProductName?> <?php echo WYTS("WebYepDemo");?>
</title>
<!-- InstanceEndEditable --><?php echo $goApp->sCharsetMetatag(); ?>
<link href="styles.css" rel="stylesheet" type="text/css">
<!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable -->
</head>

<body leftmargin="5" topmargin="5" marginwidth="5" marginheight="5"<?php if (isset($sOnLoadScript) && $sOnLoadScript) echo " onLoad='$sOnLoadScript'"; ?>>
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="left" valign="top"><h1><!-- InstanceBeginEditable name="headline" --><?php echo $webyep_sProductName?> <?php echo WYTS("WebYepDemo");?><!-- InstanceEndEditable --></h1></td>
        <td align="right"><img src="images/logo.gif"></td>
      </tr>
    </table>
    <hr noshade size="1"></td>
  </tr>
  <tr>
    <td>  <!-- InstanceBeginEditable name="content" --><?php if (!$bLicenseSaved) { ?>
      <p><span class="std"><?php printf(WYTS("DemoNotice"), $webyep_sProductName); ?></span></p>
      <p align="center"><?php echo $webyep_sProductName?> Version: <b><?php echo "$webyep_iMajorVersion.$webyep_iMinorVersion.$webyep_iSubVersion $webyep_sVersionPostfix"?></b></p>
      <div align="center">
        <form name="orderForm" method="post" action="<?php WYTSD("OrderURL"); ?>" target="_blank">
          <input name="submit" type="submit" class="formButton" value="<?php WYTSD("OrderLicenseButtonTitle", true); ?>">
          <br>
          <input type="hidden" name="VERSION" value='<?php echo "$webyep_iMajorVersion.$webyep_iMinorVersion.$webyep_iSubVersion $webyep_sVersionPostfix"?>'>
          <input type="hidden" name="SITE" value='<?php echo urlencode($sHostName)?>'>
        </form>
      </div>
      <span class="std"><?php printf(WYTS("DemoNotice2"), $sHostName); ?></span>
      <div align="center">
        <form name="saveForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
          <p class="licenseField">
            <?php echo $oTF->sDisplay(); ?>
          </p>
          <p>
            <input type="submit" value="<?php WYTSD("LicenseSaveButtonTitle", true); ?>" class="formButton">
          </p>
        </form>
      </div><?php echo $goApp->sHelpLink("demo-mode.php"); ?>
		<?php } else { ?>
		<p class="response"><?php WYTSD("LicenseSaved", true); ?></p>
		<p>&lt;<a href="javascript:window.close();"><?php WYTSD("CloseWindow", true); ?></a>&gt;</p>
		<script language="JavaScript" type="text/javascript">
			window.opener.location.reload();
			window.setTimeout("window.close()", 2000);
		</script>
		<?php } ?><!-- InstanceEndEditable --></td></tr>
</table>
</body>
<!-- InstanceEnd --></html>
