<?php
// WebYep
// (C) Objective Development Software GmbH
// http://www.obdev.at

include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYApplication.php");
include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYPath.php");
include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYURL.php");
include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/elements/WYLoopElement.php");

define("WY_QK_EDITOR_SAVE", "WEBYEP_EDIT_SAVE");
define("WY_QK_PAGEID", "WEBYEP_PAGEID");
define("WY_QK_DOCINST", "WEBYEP_DI");
define("WY_QK_EDITOR_FIELDNAME", "WEBYEP_FIELDNAME");
define("WY_QK_EDITOR_GLOBAL", "WEBYEP_GLOBAL");
define("WY_EDITOR_RESIZE", 1);
define("WY_EDITOR_OPEN", 2);

class WYEditor
{
	// public
	var $sFieldName;
	var $bGlobal;
	var $bSave;
	// private

	// class methods
	
	function dQueryForElement(&$oElement)
	{
		global $goApp;
		$d = array();
		
		$d[WY_QK_EDITOR_FIELDNAME] = $oElement->sName;
		$d[WY_QK_EDITOR_GLOBAL] = (int)$oElement->bGlobal;
		$d[WY_QK_PAGEID] = $goApp->oDocument->iPageID(true);
		$d[WY_QK_DOCINST] = $goApp->oDocument->iDocumentInstance();
		$d[WY_QK_LOOP_ID] = $goApp->oDocument->iLoopID();
		return $d;
	}
	
	function sHiddenFieldsForElement(&$oElement)
	{
		$s = "";
		$d = WYEditor::dQueryForElement($oElement);
		foreach ($d as $sKey => $sValue) {
			$s .= "<input type='hidden' name='$sKey' value='$sValue'>";
		}
		$s .= "<input type='hidden' name='" . WY_QK_ACTION . "' value='" . WY_QK_EDITOR_SAVE . "'>";
		return $s;
	}

	function sPostSaveScript()
	{
		global $webyep_bDebug;
		$s = "";
		
		$s .= "<script language='JavaScript' type='text/javascript'>\n";
		$s .= "   window.opener.location.reload(true);\n";
		$s .= "   window.opener.focus();\n";
		$s .= "   window.setTimeout('window.close();', 1000);\n";
		$s .= "</script>";
		return $webyep_bDebug ? "":$s;
	}
   
   function getSizeCookieNames($sIdent, &$sW, &$sH)
   {
      $sIdent = str_replace(".", "_", $sIdent);
      $sIdent = str_replace("-", "_", $sIdent);
      $sW = $sIdent . "_w";
      $sH = $sIdent . "_h";
   }
   
   // these stupid browser use different sizes for:
   // window.open
   // window.resizerTo and
   // window.outWidth/document.body.clientWidth
   function tranformSizeForOperation(&$iW, &$iH, $iOP)
   {
      global $goApp;

      if ($goApp->bIsExplorer) {
         if ($iOP == WY_EDITOR_OPEN) {
            $iW -= 12;
            $iH -= 61;
         }
      }
      else if ($goApp->bIsSafari) {
         if ($iOP == WY_EDITOR_OPEN) {
            $iH -= 22;
         }
      }
      else {
         if ($iOP == WY_EDITOR_OPEN) {
            $iH -= 15;
         }
      }
   }

	// instance methods

	function WYEditor()
	{
		global $goApp;

		if (!$goApp->bEditMode) {
			$goApp->log("Editor " . $_SERVER['PHP_SELF'] . " called in non edit mode");
			exit();
		}

		$this->sFieldName = $goApp->sFormFieldValue(WY_QK_EDITOR_FIELDNAME);
		$this->bGlobal = !((int)$goApp->sFormFieldValue(WY_QK_EDITOR_GLOBAL) == 0);
		$this->bSave = false;		
		if ($goApp->sFormFieldValue(WY_QK_ACTION) == WY_QK_EDITOR_SAVE) $this->bSave = true;
		$goApp->oDocument->setPageID((int)$goApp->sFormFieldValue(WY_QK_PAGEID));
		$goApp->oDocument->setDocumentInstance((int)$goApp->sFormFieldValue(WY_QK_DOCINST));
		$goApp->oDocument->setLoopID((int)$goApp->sFormFieldValue(WY_QK_LOOP_ID));
	}
	
}
?>