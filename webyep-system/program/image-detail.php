<?php
// WebYep
// (C) Objective Development Software GmbH
// http://www.obdev.at

	$webyep_bDocumentPage = false;
	$webyep_sIncludePath = ".";
	include_once("$webyep_sIncludePath/webyep.php");

	include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYImage.php");
	include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYPath.php");
	include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/elements/WYImageElement.php");
	
	$oImage = $oURL = od_nil;
	$sFilename = "";

	if (isset($_GET[WY_QK_IMAGE_DETAIL])) {
		$sFilename = $_GET[WY_QK_IMAGE_DETAIL];
		$sAltText = $_GET[WY_QK_IMAGE_ALTTEXT];
      $oP =& new WYPath($sFilename);
      if (!$oP->bCheck(WYPATH_CHECK_NOPATH|WYPATH_CHECK_JUSTIMAGE)) {
	      $goApp->log("illegal filename in image-detail: <$sFilename>");
	      exit(-1);
      }
      $oURL = od_clone($goApp->oDataURL);
      $oURL->addComponent($sFilename);
      $oImage =& new WYImage($oURL);
      if ($sAltText) $oImage->setAttribute("alt", $sAltText);
      $iW = $oImage->iWidth();
      $iH = $oImage->iHeight();
      if (!$sAltText) $sAltText = WYTS("GalleryCloseWindow");
	}
	else exit(-1);
?>
<script type="text/javascript">
	function setupWindow()
	{
		var iW = <?php echo $iW?>;
		var iH = <?php echo $iH?>;
		var _iH, _iW, iSW = 0, iSH = 0;

		if (iW && iH) {
         if (<?php echo $goApp->bIsExplorer ? "true":"false"?>) {
            _iH = document.body.clientHeight;
            _iW = document.body.clientHeight;
				window.resizeTo(iW, iH);
            _iH = iH + iH - document.body.clientHeight;
            _iW = iW + iW - document.body.clientWidth;
				window.resizeTo(_iW, _iH);
         }
			else {
				_iH = iH + window.outerHeight - window.innerHeight;
				_iW = iW + window.outerWidth - window.innerWidth;
				window.resizeTo(_iW, _iH);
			}
			if (screen) {
				iSW = screen.width;
				iSH = screen.height;
				if (iSW && iSH) {
					window.moveTo((iSW - _iW) / 2.0, (iSH - _iH) / 2.0);
				}
			}
		}
	}
</script>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php WYTSD("GalleryDetailTitle", true); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="setupWindow()">
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="middle"><?php
		if ($oImage) {
			echo "<a href='javascript:window.close();' title='$sAltText'>";
			echo $oImage->sDisplay();
			echo "</a>";
		}
	?></td>
  </tr>
</table>
</body>
</html>
