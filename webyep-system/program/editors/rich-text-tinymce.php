<?php
// WebYep
// (C) Objective Development Software GmbH
// http://www.obdev.at

	$webyep_bDocumentPage = false;
	$webyep_sIncludePath = "..";
	include_once("$webyep_sIncludePath/webyep.php");

	include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/elements/WYRichTextElement.php");
	include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYTextArea.php");
	include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYEditor.php");

   function sTinyMCELang()
   {
      global $webyep_iLanguageID;
      global $goApp;
      
      $sL = $webyep_iLanguageID == WYLANG_GERMAN ? "de":"en";

      if ($sL != "en") {
         $oP = od_clone($goApp->oProgramPath);
         $oP->addComponent("opt");
         $oP->addComponent("tinymce");
         $oP->addComponent("jscripts");
         $oP->addComponent("tiny_mce");
         $oP->addComponent("langs");
         $oP->addComponent("de.js");
         if (!$oP->bExists()) $sL = "en";
      }

      return $sL;
   }

   $bOK = false;
	$sResponse = WYTS("RichTextSaved");
	$sHelpFile = "richtext-tinymce-element.php";

	$oEditor =& new WYEditor();
	$oElement =& new WYRichTextElement($oEditor->sFieldName, $oEditor->bGlobal, "", false);
	if ($oEditor->bSave) {
		$oElement->setText($goApp->sFormFieldValue('editorArea'));
		$oElement->save();
      $bOK = true;
	}
	else {
      $sContent = $oElement->sText();
      $sCSSURL = $goApp->sFormFieldValue(WY_QK_RICH_TEXT_CSS);
	}
	
	$goApp->outputWarningPanels(); // give App a chance to say something
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head>

<title>
<?php WYTSD("RichTextEditorTitle", true); ?>
</title>
<?php
	if (!isset($bOK)) $bOK = false;
	if ($oEditor->bSave) $bDidSave = true;
	else if (!isset($bDidSave)) $bDidSave = false;
?>
<?php echo $goApp->sCharsetMetatag(); ?>
<link href="../styles.css" rel="stylesheet" type="text/css">
<?php if (!$bDidSave) { ?>
<!-- TinyMCE -->
<script language="javascript" type="text/javascript" src="../opt/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
	tinyMCE.init({
		mode : "exact",
		theme : "advanced",
		elements : "editorArea",
      convert_urls : false,
      relative_urls : false,
      language : "<?php echo sTinyMCELang() ?>",
		plugins : "style,table,save,advhr,advlink,iespell,insertdatetime,preview,searchreplace,print,contextmenu,paste,directionality,noneditable,visualchars,nonbreaking",
		theme_advanced_buttons1_add_before : "newdocument,separator",
		theme_advanced_buttons1_add : "separator,forecolor,backcolor",
		theme_advanced_buttons2_add : "separator,advsearchreplace",
		theme_advanced_buttons2_add_before: "cut,copy,paste,pastetext,pasteword,separator,search,replace,separator",
		theme_advanced_buttons3_add_before : "tablecontrols,separator",
		theme_advanced_buttons4 : "styleprops,separator,visualchars,nonbreaking",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_path_location : "bottom",
		content_css : "<?php echo $sCSSURL ?>",
		extended_valid_elements : "hr[class|width|size|noshade],span[class|align|style],strong",
		theme_advanced_resize_horizontal : false,
		theme_advanced_resizing : true,
		theme_advanced_resizing_use_cookie: true,
		nonbreaking_force_tab : true,
		apply_source_formatting : true
	});

</script>
<!-- /TinyMCE -->
<?php } ?>
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
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" style="margin:0; padding:0;" onload="wy_restoreSize();" onresize="wy_saveSize();">
<table style="height:100%; width:100%;" border="0" cellspacing="0" cellpadding="6">
  <tr>
    <td style="height: 30px"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="left" valign="top"><?php if ($webyep_bDebug) echo "<h1 class='warning'>WebYep Debug Mode!</h1>"; ?>
<h1><span class="editorTitle"><?php echo WYTS("RichTextEditorTitle") . " (TinyMCE):</span> " . $oEditor->sFieldName; ?></h1></td>
        <td align="right"><img src="../images/logo.gif"></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td valign="top"><?php if (!$bDidSave) { ?>
      <table border="0" cellspacing="0" cellpadding="6" style="height: 95%; width: 100%;">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <tr>
          <td align="left" valign="top"><textarea id="editorArea" name="editorArea" style="width: 100%; height: 420px"><?php echo $sContent?></textarea>
          </td>
        </tr>
        <tr>
          <td style="height: 30px"><input name="Button" type="button" class="formButton" value="<?php WYTSD("CancelButton", true); ?>" onClick="window.close();"><input type="submit" class="formButton" value="<?php WYTSD("SaveButton", true); ?>"><?php echo WYEditor::sHiddenFieldsForElement($oElement); ?></td>
        </tr>
        </form>
      </table>
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
