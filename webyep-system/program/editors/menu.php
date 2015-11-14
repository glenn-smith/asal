<?php
// WebYep
// (C) Objective Development Software GmbH
// http://www.obdev.at

	$webyep_bDocumentPage = false;
	$webyep_sIncludePath = "..";
	include_once("$webyep_sIncludePath/webyep.php");

	include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/elements/WYMenuElement.php");
	include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYTextField.php");
	include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYHiddenField.php");
	include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYSelectMenu.php");
	include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYEditor.php");


   $bOK = false;
	$sResponse = WYTS("MenuSaved");
	$sHelpFile = "menu-element.php";

	$sMenuName = "MENU";
	$sTextFieldName = "ITEM";

	$aTitles = array();
	$aIDs = array();
	$dItems = array();
	$i = 0;
	$iC = 0;
	$oSM = od_nil;
	$oTFItem = od_nil;
	$oHFTitles =& new WYHiddenField("MENU_TITLES");
	$oHFIDs =& new WYHiddenField("MENU_IDS");
	$oEditor =& new WYEditor();
	$oElement =& new WYMenuElement($oEditor->sFieldName, $oEditor->bGlobal, od_nil, "", "", od_nil);

	if ($oEditor->bSave) {
		if ($oHFIDs->sValue()) {
			$aIDs = explode("|", $oHFIDs->sValue());
			$aTitles = explode("|", $oHFTitles->sValue());
		}
		$iC = count($aIDs);
		for ($i = 0; $i < $iC; $i++) {
			$dItems[$aIDs[$i]] = $aTitles[$i];
		}
		$oElement->setItems($dItems); 
		$oElement->save();
      $bOK = true;
	}
	else {
		$oTFItem =& new WYTextField($sTextFieldName);
		$oTFItem->setAttribute("class", "textField");
		$oTFItem->setAttribute("tabindex", "2");
		$oTFItem->setAttribute("onKeyDown", "bEntryDirty = true;");
		// $oTFItem->setAttribute("onKeyDown", "bEntryDirty = true; changeSelectedItem();");
		$oSM =& new WYSelectMenu($sMenuName, $oElement->dItems(), "", $webyep_sCharset=="");
		$oSM->setLines(5);
		$oSM->setAllowsMultiple(false);
		$oSM->setAttribute("class", "selectMenu");
		$oSM->setAttribute("tabindex", "1");
		$oSM->setAttribute("onChange", "selectItem(this.selectedIndex);");
		$sOnLoadScript = 'document.forms[0].ITEM.focus();';
	}
	
	$goApp->outputWarningPanels(); // give App a chance to say something
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head>

<title><?php WYTSD("MenuEditorTitle", true); ?></title>
<?php echo $goApp->sCharsetMetatag(); ?>
<link href="../styles.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.selectMenu {
	width: 100%;
	height: 100%;
}
.textField {
	width: 100%;
}
.menuButton {
	font-family: Verdana, Geneva, sans-serif;
	font-size: 12px;
	font-weight: bold;
	width: 100px;
}
.menuButtonTitle {
	font-family: Verdana, Geneva, sans-serif;
	font-size: 9px;
	font-weight: bold;
	color: #0099CC;
	text-decoration: none;
}
-->
</style>
<script language="JavaScript" type="text/javascript">
<!--
<?php echo "var iLIID = " . $oElement->iLastItemID() . ";\n"; ?>
var bMaySubmit = false;
var sLastSelectedValue = "";
var iLastSelectedIndex = -1;

function bItemExists(s)
{
	var bFlag, i;

	bFlag = false;
	a = document.editForm.<?php echo $sMenuName; ?>.options;
	for (i = 0; i < a.length; i++) {
		if (a[i].text == s) {
			bFlag = true;
			break;
		}
	}
	return bFlag;
}

function sPrepareItem(s)
{
	s = s.replace(/  /g, "__");
	s = s.replace(/_ /g, "__");
	s = s.replace(/^ /g, "_");
	return s;
}

function addItem(s)
{
	var a, o, i;

	if (s == "") return;
	a = document.editForm.<?php echo $sMenuName; ?>.options;
	sU = s;
	i = 1;
	while (bItemExists(sU)) {
		sU = s + i++;
	}
	s = sU;
	o = new Option;
	s = sPrepareItem(s);
	o.text = s;
	o.value = ++iLIID;
	i = a.length;
	a[i] = o;
	iLastSelectedIndex = -1; // avoid autochange
	selectItem(i);
	document.editForm.<?php echo $sMenuName; ?>.selectedIndex = i;
}

function changeSelectedItemIfChanged()
{
	var o, sNewValue;
	
	if (iLastSelectedIndex >= 0) {
		sNewValue = document.editForm.<?php echo $sTextFieldName; ?>.value;
		o = document.editForm.<?php echo $sMenuName; ?>.options[iLastSelectedIndex];
		if (o && (sNewValue != sLastSelectedValue)) {
			changeItem(iLastSelectedIndex, sNewValue)
		}
	}
}

function changeItem(i, s)
{
	var a, s;
	
	a = document.editForm.<?php echo $sMenuName; ?>.options;
	if (!isNaN(i) && (i >= 0) && (i < a.length)) {
		s = sPrepareItem(s);
		document.editForm.<?php echo $sMenuName; ?>.options[i].text = s;
	}
	sLastSelectedValue = s;
}

function moveItem(iIndex, iDir)
{
	var a, iNewIndex, sText, iID;

	changeSelectedItemIfChanged();
	a = document.editForm.<?php echo $sMenuName; ?>.options;
	if (!isNaN(iIndex) && (iIndex >= 0) && (iIndex < a.length)) {
		iNewIndex = iIndex + iDir;
		if ((iNewIndex >= 0) && (iNewIndex < a.length)) {
			sText = a[iIndex].text;
			iID = a[iIndex].value;
			a[iIndex].text = a[iNewIndex].text;
			a[iIndex].value = a[iNewIndex].value;
			a[iNewIndex].text = sText;
			a[iNewIndex].value = iID;
			document.editForm.<?php echo $sMenuName; ?>.selectedIndex = iNewIndex;
			selectItem(iNewIndex);
		}
	}
}

function deleteItem(i)
{
	if (!isNaN(i) && (i >= 0) && (i < document.editForm.<?php echo $sMenuName; ?>.options.length)) {
		if (confirm("<?php WYTSD("MenuDelConfirmPre"); ?>\n\"" + document.editForm.<?php echo $sMenuName; ?>.options[i].text + "\"\n<?php WYTSD("MenuDelConfirmPost"); ?>")) {
			document.editForm.<?php echo $sMenuName; ?>.options[i] = null;
			if (i >= document.editForm.<?php echo $sMenuName; ?>.options.length) {
				document.editForm.<?php echo $sMenuName; ?>.selectedIndex = document.editForm.<?php echo $sMenuName; ?>.options.length - 1;
			}
			else {
				document.editForm.<?php echo $sMenuName; ?>.selectedIndex = i;
			}
		}
	}
}

function selectItem(i)
{
	var o;

	changeSelectedItemIfChanged();
	if (!isNaN(i) && (i >= 0)) {
		o = document.editForm.<?php echo $sMenuName; ?>.options[i];
		sLastSelectedValue = o.text;
		document.editForm.<?php echo $sTextFieldName; ?>.value = o.text;
		iLastSelectedIndex = i;
	}
	else {
		document.editForm.<?php echo $sMenuName; ?>.selectedIndex = 0;
	}
}

function encodeContentAndSubmit()
{
	var a, i, sIDs, sTitles, sSep;
	changeSelectedItemIfChanged();
	sSep = "|";
	sIDs = sTitles = "";
	a = document.editForm.<?php echo $sMenuName; ?>.options;
	for (i = 0; i < a.length; i++) {
		sIDs += (sIDs ? sSep:"") + a[i].value;
      s = a[i].text.replace(/\|/, " ");
		sTitles += (sTitles ? sSep:"") + s;
	}
	document.editForm.<?php echo $oHFIDs->dAttributes["name"]; ?>.value = sIDs;
	document.editForm.<?php echo $oHFTitles->dAttributes["name"]; ?>.value = sTitles;
	bMaySubmit = true;
	document.editForm.submit();
}
//-->
</script>
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
<h1><span class="editorTitle"><?php echo WYTS("MenuEditorTitle") . ":</span> " . $oEditor->sFieldName; ?></h1></td>
        <td align="right"><img src="../images/logo.gif"></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td valign="top"><?php if (!$bDidSave) { ?>
      <form style="margin:0; padding:0; height:95%; width:100%;" name="editForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onSubmit="return bMaySubmit;">
      <table border="0" cellspacing="0" cellpadding="6" style="height: 95%; width: 100%;">
        <tr>
          <td align="left" valign="top">
            <table border="0" cellspacing="0" cellpadding="6" style="height: 100%; width: 100%;">
              <tr>
                <td align="left" valign="top"><?php echo $oSM->sDisplay(); ?></td>
                <td style="width: 120px" align="left" valign="top" nowrap><a class="menuButtonTitle" href="javascript:moveItem(document.editForm.<?php echo $sMenuName; ?>.selectedIndex, -1);" title="<?php WYTSD("MenuUpButtonTitle", true); ?>" onDblClick="moveItem(document.editForm.<?php echo $sMenuName; ?>.selectedIndex, -1);"><img src="../images/up-button.gif" width="33" height="22" align="absmiddle" border="0" alt="<?php WYTSD("MenuUpButtonTitle", true); ?>">&nbsp;<?php WYTSD("MenuUpButtonTitle", true); ?></a><br>
					 <a class="menuButtonTitle" href="javascript:moveItem(document.editForm.<?php echo $sMenuName; ?>.selectedIndex, 1);" title="<?php WYTSD("MenuDownButtonTitle", true); ?>" onDblClick="moveItem(document.editForm.<?php echo $sMenuName; ?>.selectedIndex, 1);"><img src="../images/down-button.gif" width="33" height="22" align="absmiddle" border="0" alt="<?php WYTSD("MenuDownButtonTitle", true); ?>">&nbsp;<?php WYTSD("MenuDownButtonTitle", true); ?></a><br>
                <img src="../images/nix.gif" width="8" height="16"><br>
					 <a class="menuButtonTitle" href="javascript:deleteItem(document.editForm.<?php echo $sMenuName; ?>.selectedIndex);" value="<?php WYTSD("MenuRemoveButtonTitle", true); ?>" title="<?php WYTSD("MenuRemoveButtonTitle", true); ?>"><img src="../images/remove-button.gif" width="33" height="22" align="absmiddle" border="0" alt="<?php WYTSD("MenuRemoveButtonTitle", true); ?>">&nbsp;<?php WYTSD("MenuRemoveButtonTitle", true); ?></a></td>
              </tr>
              <tr>
                <td align="left" valign="middle" style="height: 40px"><?php echo $oTFItem->sDisplay(); ?></td>
                <td align="left" valign="middle" nowrap><a class="menuButtonTitle" href="javascript:addItem(document.editForm.<?php echo $sTextFieldName; ?>.value);" value="<?php WYTSD("MenuAddButtonTitle", true); ?>" title="<?php WYTSD("MenuAddButtonTitle", true); ?>"><img src="../images/add-button.gif" width="33" height="22" align="absmiddle" border="0" alt="<?php WYTSD("MenuAddButtonTitle", true); ?>">&nbsp;<?php WYTSD("MenuAddButtonTitle", true); ?></a><br>
                  <img src="../images/nix.gif" width="8" height="12"><br>					 <a class="menuButtonTitle" href="javascript:changeSelectedItemIfChanged();" value="<?php WYTSD("MenuChangeButtonTitle", true); ?>" title="<?php WYTSD("MenuChangeButtonTitle", true); ?>"><img src="../images/change-button.gif" width="33" height="22" align="absmiddle" border="0" alt="<?php WYTSD("MenuChangeButtonTitle", true); ?>">&nbsp;<?php WYTSD("MenuChangeButtonTitle", true); ?></a></td>
              </tr>
            </table>
            <?php echo $oHFIDs->sDisplay(); ?>
            <?php echo $oHFTitles->sDisplay(); ?>
            </td>
        </tr>
        <tr>
          <td style="height: 20px;">
			 <input type="button" class="formButton" value="<?php WYTSD("CancelButton", true); ?>" onClick="window.close();">
          <input type="button" class="formButton" value="<?php WYTSD("SaveButton", true); ?>" onClick="encodeContentAndSubmit();">
            <?php echo WYEditor::sHiddenFieldsForElement($oElement); ?></td>
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
