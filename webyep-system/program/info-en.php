<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>WebYep System Infos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="<?php
	$oCSS = od_clone($goApp->oProgramURL);
	$oCSS->addComponent("styles.css");
	echo $oCSS->sURL();
?>" rel="stylesheet" type="text/css">
<script language="JavaScript"  type="text/javascript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  top[winName] = window.open(theURL,winName,features);
  setTimeout("top['" + winName + "'].focus();", 250);
}
//-->
</script>
</head>
<?php
	// WebYep
	// (C) Objective Development Software GmbH
	// http://www.obdev.at
	
	$bPermOK = false;
	$oP = od_nil;
	$oF = od_nil;
	
	srand ((float) microtime() * 1000000);
	$sFilename = "permission-test" . mt_rand(1000, 9999);
	$oP = od_clone($goApp->oDataPath);
	$oP->addComponent($sFilename);
	$oF = new WYFile($oP);
	$oF->setContent("write test");
	$bPermOK = $oF->bWrite();
	@$oF->bDelete();

    $bRegGlob = ini_get("register_globals") != 0;

    $sMaxUpload = $goApp->sFormattedByteSizeString($goApp->iMaxUploadBytes());
?>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td class="pageTitle" align="left" valign="middle">WebYep System Infos</td>
    <td align="right" valign="top"><a href="http://www.obdev.at/webyep/" target="_blank"><img src="program/images/logo.gif" align="top" border="0" alt="WebYep WebSite"></a></td>
  </tr>
</table>
<hr size="1" noshade>
<h2>Installed WebYep-Version</h2>
<p><b>Version <?php echo "$webyep_iMajorVersion.$webyep_iMinorVersion.$webyep_iSubVersion $webyep_sVersionPostfix"?></b></p>
<?php if ($bRegGlob) { ?>
<h2 class="warning">Security Warning</h2>
<p>This web server has a weak security configuration. Please ask the administrator to switch of the PHP setting &quot;register_globals&quot;!</p>
<?php } ?>
<?php if (!$bPermOK) { ?>
<h2><font color="#990000">Write Permission Error!</font></h2>
<p>WebYep is not &quot;<b>activated</b>&quot; and can not write any data!</p>
<p>--&gt; <a href="program/help/english/activate.php" target="help" onclick="MM_openBrWindow('program/help/english/activate.php','help','toolbar=yes,location=yes,status=yes,menubar=yes,scrollbars=yes,resizable=yes,width=700,height=450'); return false">How do I activate WebYep?</a></p>
<?php } else { ?>
<h2>Write Permission Check</h2>
<p>WebYep was activated correctly and has write permissions.</p>
<?php } ?>
<h2>Image support</h2>
<?php if (WYImage::bCanResizeImages()) { ?>
PHP can resize uploaded images on this server (GD lib with JPG support installed).
<?php } else { ?>
PHP <font color="#990000"><b>cannot</b></font> resize images on this server (no GD lib with JPG support installed)!
<?php } ?>
<p>Maximum size for uploaded files/images: <?php echo $sMaxUpload ?></p>
<h2>Installed Licenses</h2>
<?php
	$oP = od_clone($goApp->oDataPath);
	$oP->addComponent("license");
	$oF = new WYFile($oP);
	$sContent = $oF->sContent();
	$aLines = split("\r|\n|\r\n", $sContent);
	$i = 0;
	$iC = count($aLines);

	if ($iC && $sContent) {
		for ($i = 0; $i < $iC; $i++) {
			echo "Lizenz " . ($i + 1) . ": ";
			echo "<tt>" . $aLines[$i] . "</tt><br>\n";
		}
	} else {
?>
<p>No licenses installed - WebYep is running in demo mode. </p>
<?php } ?>
<p>--&gt; <a href="program/l-save.php" target="help" onclick="MM_openBrWindow('program/l-save.php','help','toolbar=yes,location=yes,status=yes,menubar=yes,scrollbars=yes,resizable=yes,width=500,height=470'); return false">Add License</a></p>
<?php webyep_showSystemInfos() ?>
</body>
</html>
