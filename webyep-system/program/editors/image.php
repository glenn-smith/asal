<?php
// WebYep
// (C) Objective Development Software GmbH
// http://www.obdev.at

	$webyep_bDocumentPage = false;
	$webyep_sIncludePath = "..";
	include_once("$webyep_sIncludePath/webyep.php");

	include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/elements/WYImageElement.php");
	include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYFileUpload.php");
	include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYHiddenField.php");
	include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYTextField.php");
	include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYEditor.php");


   $bOK = false;
	$sHelpFile = "image-element.php";


	$oEditor =& new WYEditor();
	$oHFDelete =& new WYHiddenField("DELETE_IMAGE");
	$oHFWidth =& new WYHiddenField(WY_QK_IMAGE_WIDTH);
	$oHFHeight =& new WYHiddenField(WY_QK_IMAGE_HEIGHT);
	$oHFIsThumb =& new WYHiddenField(WY_QK_IS_THUMB);
	$oFU =& new WYFileUpload("IMAGE_FILE");
   $oTFURL =& new WYTextField("LINK_URL");
   $oTFURL->setWidth(40);
   $oTFAltText =& new WYTextField("ALT_TEXT");
   $oTFAltText->setWidth(40);
	$oElement =& new WYImageElement($oEditor->sFieldName, $oEditor->bGlobal, "", "", "", (int)$oHFWidth->sValue(), (int)$oHFHeight->sValue(), ((int)$oHFIsThumb->sValue()) == 1 ? true:false);
	$oFP = od_nil;

   $sMaxUpload = $goApp->sFormattedByteSizeString($goApp->iMaxUploadBytes());


	if ((int)$oHFDelete->sValue() == 1) {
		$oElement->deleteThumbnail();
		$oElement->deleteImage(); // implicit save
		$sResponse = WYTS("ImageDeleted");
      $bOK = true;
	} else if ($oEditor->bSave) {
      if ($oFU->bFileUploaded()) {
         if ($oFU->bUploadOK()) {
            $oFP =& $oFU->oFilePath();
            $oOFP =& $oFU->oOriginalFilename();
            if ($oOFP->bCheck(WYPATH_CHECK_JUSTIMAGE|WYPATH_CHECK_NOPATH)) {
	            if ($oElement->bUseUploadedImageFile($oFP, $oOFP)) {
		            $oElement->setLinkURL($oTFURL->sValue());
                  $oElement->setAltText($oTFAltText->sValue());
		            $oElement->save();
		            $sResponse = WYTS("ImageSaved");
		            $bOK = true;
	            }
	            else {
		            $sResponse = WYTS("ImageStoreFailed");
		            $bOK = false;
	            }
            }
            else {
	            $goApp->log("Illegal file/type on image upload: " . $oOFP->sPath);
	            @unlink($oFP->sPath);
	            $sResponse = WYTS("FileUploadErrorUnknown");
            }
         }
         else $sResponse = $oFU->sErrorMessage();
      }
      else { // maybe only URL changed
         $oElement->setLinkURL($oTFURL->sValue());
         $oElement->setAltText($oTFAltText->sValue());
         $oElement->save();
         $sResponse = WYTS("ImageSaved");
         $bOK = true;
      }
	}
   else {
      $oTFURL->setValue($oElement->sLinkURL());
      $oTFAltText->setValue($oElement->sAltText());
   }

	$goApp->outputWarningPanels(); // give App a chance to say something
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head>

<title>
<?php WYTSD("ImageEditorTitle", true); ?>
</title>
<?php echo $goApp->sCharsetMetatag(); ?>
<link href="../styles.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.textAreaField {
	font-family: "Courier New", Courier, mono;
	font-size: 12px;
	height: 70%;
	width: 98%;
}
-->
</style>
<script language="JavaScript">
	<?php
		$WYEditor_sWC = $WYEditor_sHC = "";
		$oElement->getSizeCookieNames($WYEditor_sWC, $WYEditor_sHC);
	?>
	function wy_saveSize()
   {
      var iW = <?php echo $goApp->bIsExplorer ? "document.body.clientWidth+29":"window.outerWidth" ?>;
      var iH = <?php echo $goApp->bIsExplorer ? "document.body.clientHeight+61":"window.outerHeight" ?>;
      
      document.cookie = "<?php echo $WYEditor_sWC?>=" + iW + "; path=/";
      document.cookie = "<?php echo $WYEditor_sHC?>=" + iH + "; path=/";
   }

   function wy_sTrimString(sInString)
   {
     sInString = sInString.replace( /^\s+/g, "" );
     return sInString.replace( /\s+$/g, "" );
   }

   function wy_restoreSize()
   {
		iW = <?php echo isset($_COOKIE[$WYEditor_sWC]) ? (int)$_COOKIE[$WYEditor_sWC]:0 ?>;
		iH = <?php echo isset($_COOKIE[$WYEditor_sHC]) ? (int)$_COOKIE[$WYEditor_sHC]:0 ?>;
      if (iW>0 && iH>0) {
         window.resizeTo(iW, iH);
      }
   }
</script>
</head>
<?php
	if (!isset($bOK)) $bOK = false;
	if ($oEditor->bSave) $bDidSave = true;
	else if (!isset($bDidSave)) $bDidSave = false;
?>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" style="margin:0; padding:0;" onload="wy_restoreSize();<?php echo isset($sOnLoadScript) ? $sOnLoadScript:""?>" onresize="wy_saveSize();">
<table style="height:100%; width:100%;" border="0" cellspacing="0" cellpadding="6">
  <tr>
    <td style="height: 30px"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="left" valign="top"><?php if ($webyep_bDebug) echo "<h1 class='warning'>WebYep Debug Mode!</h1>"; ?>
<h1><span class="editorTitle"><?php echo WYTS("ImageEditorTitle") . ":</span> " . $oEditor->sFieldName; ?></h1></td>
        <td align="right"><img src="../images/logo.gif"></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td valign="top"><?php if (!$bDidSave) { ?>
<script language="JavaScript" type="text/javascript">
function confirmDelete()
{
	if (confirm("<?php WYTSD('DeleteImageQuestion'); ?>")) {
		document.forms[0].<?php echo $oHFDelete->dAttributes["name"]; ?>.value = 1;
		document.forms[0].submit();
	}
}
</script>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" style="margin: 0px">
      <table border="0" cellspacing="0" cellpadding="6">
        <tr>
          <td align="left" valign="bottom" nowrap class="formFieldTitle"><?php WYTSD("ImageFile", true); ?>:</td>
          <td align="left" valign="bottom" nowrap><span class="remark"><?php echo "(max. $sMaxUpload)&nbsp;"; ?></span><?php echo $oFU->sDisplay(); ?></td>
        </tr>
        <tr>
          <td align="left" valign="baseline" nowrap class="formFieldTitle"><?php WYTSD("ImageLinkURL", true); ?>:</td>
          <td align="left" valign="baseline" nowrap><?php echo $oTFURL->sDisplay(); ?></td>
        </tr>
        <tr>
          <td align="left" valign="baseline" nowrap class="formFieldTitle"><?php WYTSD("ImageAltText", true); ?>:</td>
          <td align="left" valign="baseline" nowrap><?php echo $oTFAltText->sDisplay(); ?></td>
        </tr>
        <tr>
          <td nowrap>&nbsp;</td>
          <td align="left" valign="top" nowrap><input name="Button" type="button" class="formButton" value="<?php WYTSD("DeleteImageButton", true); ?>" onClick="confirmDelete();"><img src="../images/nix.gif" width="24" height="8"><input type="button" class="formButton" value="<?php WYTSD("CancelButton"); ?>" onClick="window.close();">&nbsp;<input type="submit" class="formButton" value="<?php WYTSD("SaveButton", true); ?>">
<?php if (!WYImage::bCanResizeImages()) {  ?>
   <div class="warning remark" style="padding-top: 8px"><?php echo WYTS("ImageCannotResize")?></div>
<?php } ?>
            <?php
					echo WYEditor::sHiddenFieldsForElement($oElement);
					echo $oHFDelete->sDisplay();
					echo $oHFWidth->sDisplay();
					echo $oHFHeight->sDisplay();
					echo $oHFIsThumb->sDisplay();
				?></td>
        </tr>
      </table>
      </form>
	<?php echo $goApp->sHelpLink($sHelpFile); ?>
	<?php } else {
      echo "<blockquote>";
		echo "<div class='response'>$sResponse</div>";
		if ($bOK) echo WYEditor::sPostSaveScript();
      else echo "<p class='textButton'>" . webyep_sBackLink() . "</p>";
      echo "</blockquote>";
	}?></td>
  </tr>
</table>
</body>
</html>
