<?php
// WebYep
// (C) Objective Development Software GmbH
// http://www.obdev.at

	$webyep_bDocumentPage = false;
	$webyep_sIncludePath = ".";
	include_once("$webyep_sIncludePath/webyep.php");

	include_once("lib/WYApplication.php");
	include_once("lib/WYTextField.php");
	include_once("lib/WYHiddenField.php");
	include_once("lib/WYEditor.php");
	
	define("WY_QV_LOGON", "LOGON");

	$bSuccess = false;
	$oPageURL = od_nil;
	$bDoLogon = $goApp->sFormFieldValue(WY_QK_ACTION) == WY_QV_LOGON;
	
	$oHFPageURL =& new WYHiddenField(WY_QK_LOGON_PAGE_URL);
	if ($oHFPageURL->sValue()) {
		$oPageURL =& new WYURL($oHFPageURL->sValue());
		$oPageURL->dQuery[WY_QK_EDITMODE] = "yes" . mt_rand(1000, 9999);
	}
	$oTFUsername =& new WYTextField("USERNAME");
	$oTFPassword =& new WYTextField("PASSWORD");
	$oTFPassword->makePasswordField();
	if ($bDoLogon) {
		$bSuccess = $goApp->bAuthenticate($oTFUsername->sValue(), $oTFPassword->sValue());

      webyep_configureUserLanguage();
	}
	
	if (!$bDoLogon) $sOnLoadScript = 'document.forms[0].USERNAME.focus();';
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
<?php echo $webyep_sProductName?> <?php echo WYTS("WebYepLogon");?>
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
        <td align="left" valign="top"><h1><!-- InstanceBeginEditable name="headline" --><?php echo $webyep_sProductName?> <?php echo WYTSD("WebYepLogon", true);?><!-- InstanceEndEditable --></h1></td>
        <td align="right"><img src="images/logo.gif"></td>
      </tr>
    </table>
    <hr noshade size="1"></td>
  </tr>
  <tr>
    <td>  <!-- InstanceBeginEditable name="content" -->
	 <?php if (!$bDoLogon) { ?>
	 	<?php if ($webyep_bDebug) echo "<h1 class='warning'>WebYep Debug Mode!</h1>"; ?>
      <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <table border="0" align="center" cellpadding="6" cellspacing="0">
        <tr>
          <td class="formFieldTitle"><?php WYTSD("Username", true); ?>:</td>
          <td><?php echo $oTFUsername->sDisplay(); ?></td>
        </tr>
        <tr>
          <td class="formFieldTitle"><?php WYTSD("Password", true); ?>:</td>
          <td><?php echo $oTFPassword->sDisplay(); ?></td>
        </tr>
        <tr>
          <td><input name="CACHE_KILLER" type="hidden" id="CACHE_KILLER" value="<?php echo mt_rand(1000, 9999); ?>">
            <?php echo $oHFPageURL->sDisplay(); ?></td>
          <td><input type="submit" class="formButton" value="<?php WYTSD("LogonButtonTitle", true); ?>">
            <input name="<?php echo WY_QK_ACTION; ?>" type="hidden" value="<?php echo WY_QV_LOGON; ?>"></td>
        </tr>
      </table>
      </form>
    <?php } else {
	 	if ($bSuccess) {
			echo "<p class='response'>" . WYTS("LogonSucceded") . "</p>";
			if ($oPageURL) {
				echo "<script language='JavaScript' type='text/javascript'>\n";
				echo "   window.opener.location = '" . $oPageURL->sURL() . "'\n";
				echo "   window.opener.focus();\n";
				echo "   window.setTimeout('window.close();', 1000);\n";
				echo "</script>";
			}
			else {
				echo WYEditor::sPostSaveScript();
			}
		}
		else {
			echo "<p class='response'>" . WYTS("LogonFailed") . "</p>";
			echo "<p class='textButton'>&lt;<a href='javascript:window.close();'>" . WYTS("CloseWindow") . "</a>&gt;</p>";
		}
	 }?>
    <table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
    <td valign="top" align="left"><?php echo $goApp->sHelpLink("access-denied.php"); ?></td>
    <td valign="top" align="right"><?php if (!$webyep_bWL) { ?><a href="http://www.obdev.at/" target="_blank"><img src="images/obdev-logo.gif" border="0" title="Objective Development Software GmbH"></a><?php } else echo "&nbsp;"?></td>
    </tr></table>
	 <!-- InstanceEndEditable --></td></tr>
</table>
</body>
<!-- InstanceEnd --></html>
