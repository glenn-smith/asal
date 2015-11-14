<?php
// WebYep
// (C) Objective Development Software GmbH
// http://www.obdev.at

include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYLanguage.php");
include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYPath.php");
include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYURL.php");
include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYFile.php");
include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYImage.php");
include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYPopupWindowLink.php");
include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYEditor.php");

define("WY_DK_VERSION", "VERSION");
define("WY_DK_CONTENT", "CONTENT");

class WYElement
{
	// public
	var $sName;
	var $bGlobal;
	var $dContent;
	// private
	var $sEditorPageName;
	var $iEditorWidth;
	var $iEditorHeight;
	var $dEditorQuery; // additional infos passed to editor

	function WYElement($sN, $bG)
	{
		$this->sName = $sN;
		$this->bGlobal = $bG;
		$this->sEditorPageName = "";
		$this->iEditorWidth = 100;
		$this->iEditorHeight = 100;
		$this->dEditorQuery = array();
		$this->dContent = array();
		$this->setVersion(1);
		$this->_loadContent();
		// supclasses should call parents initializer first
		// then check version and if nec. convert contents
		// then set new version number
	}
	
	function setVersion($i)
	{
		$this->dContent[WY_DK_VERSION] = $i;
	}
	
	function iVersion()
	{
		return $this->dContent[WY_DK_VERSION];
	}

	function bUseDocumentInstance()
	{
		return true;
	}

	function bUseLoopID()
	{
		return true;
	}

	function sFieldNameForFile()
	{
		return WYPath::sMakeFilename($this->sName);
	}

	function sDataFileName($bCreate)
	{
		global $goApp;
		$sFilename = "";
		$sPrefix = "";
		$iPageID = 0;
		$i = 0;

		$sFilename = $this->sFieldNameForFile();
		if (!$this->bGlobal) {
         $iPageID = $goApp->oDocument->iPageID($bCreate);
         if ($iPageID) {
	         $sPrefix = $iPageID . "-";
				if ($this->bUseDocumentInstance()) {
					$i = $goApp->oDocument->iDocumentInstance();
					if ($i) $sPrefix .= $i . "-";
				}
				if ($this->bUseLoopID()) {
					$i = $goApp->oDocument->iLoopID();
					if ($i) $sPrefix .= $i . "-";
				}
				$sFilename = $sPrefix . $sFilename;
         }
         else $sFilename = "";
		}

      if ($sFilename) {
	      $oP =& new WYPath($sFilename);
	      if (!$oP->bCheck(WYPATH_CHECK_NOPATH)) $sFilename = "";
      }
		
		return $sFilename;
	}

	function &oDataFilePath($bCreate)
	{
		global $goApp;
		$sFilename = $this->sDataFileName($bCreate);
		$oP = od_nil;

      if ($sFilename) {
	      $oP = od_clone($goApp->oDataPath);
			$oP->addComponent($sFilename);
      }
		return $oP;
	}

	function _loadContent()
	{
      global $goApp;
		$oP = od_nil;
		$oF = od_nil;

		$oP =& $this->oDataFilePath($goApp->bEditMode);
		if ($oP) {
			$oF =& new WYFile($oP);
			if ($oF->bExists()) {
				$this->dContent = unserialize($oF->sContent());
			}
		}
		else $this->dContent = array();
	}
	
	function save()
	{
		global $goApp;

		$oP =& $this->oDataFilePath(true);
		$oF =& new WYFile($oP);
		$oF->setContent(serialize($this->dContent));
		if (!$oF->bWrite()) {
			$goApp->log("could not write element data to " . $oP->sPath);
			$goApp->setDataAccessProblem(true);
		}
		@$oF->chmod(0644);
	}
	
   function getSizeCookieNames(&$sW, &$sH)
   {
      return WYEditor::getSizeCookieNames($this->sEditorPageName, $sW, $sH);
   }
   
	function sEditButtonHTML($sButtonImage = "edit-button.gif", $sToolTip = "")
	{
		global $goApp;
		$oImgURL = od_nil;
		$oImg = od_nil;
		$oEditorURL = od_nil;
		$oNeedJSURL = od_nil;
		$dQuery = array();
      $iEW = $iEH = 0;
      $sWCookie = $sHCookie = "";

      if (!$sToolTip) $sToolTip = sprintf(WYTS("editTheField"), $this->sName);
		$oImgURL = od_clone($goApp->oImageURL);
		$oImgURL->addComponent($sButtonImage);
		$oImg =& new WYImage($oImgURL);
		$oImg->setAttribute("border", 0);
		$oImg->setAttribute("alt", $sToolTip);
		$oEditorURL = od_clone($goApp->oProgramURL);
		$oEditorURL->addComponent("editors/" . $this->sEditorPageName);
		$dQuery = array_merge(WYEditor::dQueryForElement($this), $this->dEditorQuery); 
		$oEditorURL->setQuery($dQuery);
      $this->getSizeCookieNames($sWCookie, $sHCookie);
      if (isset($_COOKIE[$sWCookie])) {
         $iEW = (int)$_COOKIE[$sWCookie];
         $iEH = (int)$_COOKIE[$sHCookie];
         WYEditor::tranformSizeForOperation($iEW, $iEH, WY_EDITOR_OPEN);
      }
      else {
         $iEW = $this->iEditorWidth;
         $iEH = $this->iEditorHeight;
      }
		$oWin =& new WYPopupWindowLink($oEditorURL, "WebYepEditor" . mt_rand(1000, 9999), $iEW, $iEH, WY_POPWIN_TYPE_PLAIN);
		$oNeedJSURL = od_clone($goApp->oProgramURL);
		$oNeedJSURL->addComponent(WYTS("LogonURL"));
		$oWin->setAttribute("href", $oNeedJSURL->sURL()); // special href: JS warning
		$oWin->setInnerHTML($oImg->sDisplay());
		$oWin->setToolTip($sToolTip);
		return $oWin->sDisplay();
	}
	
	function sDisplay()
	{
		return "";
	}
}
?>