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
    <td align="right" valign="top"><a href="http://www.obdev.at/webyep/" target="_blank"><img src="programm/images/logo.gif" align="top" border="0" alt="WebYep WebSite"></a></td>
  </tr>
</table>
<hr size="1" noshade>
<h2> Installierte WebYep-Version</h2>
<p><b>Version <?php echo "$webyep_iMajorVersion.$webyep_iMinorVersion.$webyep_iSubVersion $webyep_sVersionPostfix"?></b></p>
<?php if ($bRegGlob) { ?>
<h2 class="warning">Sicherheits-Warnung</h2>
<p>Dieser Webserver weist eine unsichere Konfiguration auf. Bitte weisen Sie den/die Administrator/in an, die PHP-Einstellung &quot;register_globals&quot; auszuschalten!</p>
<?php } ?>
<?php if (!$bPermOK) { ?>
<h2><font color="#990000">Berechtigungs-Problem!</font></h2>
<p>WebYep ist nicht &quot;<b>aktiviert</b>&quot; und kann daher keine Daten auf 
  dem Web-Server ablegen!</p>
<p>--&gt; <a href="programm/help/deutsch/activate.php" target="help" onclick="MM_openBrWindow('programm/help/deutsch/activate.php','help','toolbar=yes,location=yes,status=yes,menubar=yes,scrollbars=yes,resizable=yes,width=700,height=450'); return false">Wie 
  aktiviere ich WebYep?</a></p>
<?php } else { ?>
<h2> Zugriffsrechte-Prüfung</h2>
<p>Die Zugriffsrechte sind richtig gesetzt - WebYep wurde korrekt aktiviert.</p>
<?php } ?>
<h2>Bildverarbeitung</h2>
<?php if (WYImage::bCanResizeImages()) { ?>
PHP kann auf diesem Server Bilder verkleinern und vergrößern (GD-lib mit JPG installiert).
<?php } else { ?>
PHP kann auf diesem Server Bilder <font color="#990000"><b>nicht</b></font> verkleinern und vergrößern (keine GD-lib mit JPG installiert)!
<?php } ?>
<p>Maximale Größe für Datei/Bild-Uploads: <?php echo $sMaxUpload ?></p>
<h2>Installierte Lizenzen</h2>
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
<p>Keine Lizenzen installiert - WebYep läuft im Demo-Modus. </p>
<?php } ?>
<p>--&gt; <a href="programm/l-save.php" target="help" onclick="MM_openBrWindow('programm/l-save.php','help','toolbar=yes,location=yes,status=yes,menubar=yes,scrollbars=yes,resizable=yes,width=500,height=470'); return false">Lizenz 
  hinzuf&uuml;gen</a></p>
<?php webyep_showSystemInfos() ?>
</body>
</html>
