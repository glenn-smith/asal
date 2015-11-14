<?php
// WebYep
// (C) Objective Development Software GmbH
// http://www.obdev.at

include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYPath.php");
include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYURL.php");
include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYFile.php");
include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/elements/WYLoopElement.php");

define("WY_QK_DI", "WEBYEP_DI"); // query key for document instance
define("WYDOC_DOCFILE", "documents");

$webyep_iLoopID = 0;
if (!isset($webyep_iDILIOffset)) $webyep_iDILIOffset = 0;

class WYDocument
{
	// public
	var $oDocPath;
	// private
	var $iPageID;
	var $iDocumentInstance;

	function WYDocument($oU)
	{
		global $goApp;

		$oU->makeSiteRelative();
		$this->oDocPath =& new WYPath($oU->sURL(false, false, false));
		$this->iPageID = 0;
		$this->iDocumentInstance = (int)$goApp->sFormFieldValue(WY_QK_DI);
	}
	
	function setPageID($i)
	{
		$this->iPageID = $i;
	}
	
	function setDocumentInstance($i)
	{
		$this->iDocumentInstance = $i;
	}

	function setLoopID($i)
	{
		WYLoopElement::setCurrentLoopID($i);
	}
	
	function iPageID($bCreate)
	{
		global $goApp, $webyep_bDocumentPage;

		if (!$this->iPageID && $webyep_bDocumentPage) {
			$oP = od_nil;
			$oF = od_nil;
			$aLines = array();
			$sFileContent = "";
			$aFields = array();
			$sLine = "";
			$iMaxID = 0;

			$oP = od_clone($goApp->oDataPath);
			$oP->addComponent(WYDOC_DOCFILE);
			$oF =& new WYFile($oP);
			if ($oF->bExists()) {
				$aLines = $oF->aContentLines();
			}

			foreach ($aLines as $sLine) {
				$sLine = trim($sLine);
				if ($sLine) {
					$aFields = preg_split("/[ \t]+/", $sLine);
					if (!$aFields || (count($aFields) != 2)) continue;
					$iMaxID = max($iMaxID, $aFields[1]);
					if ($aFields[0] == $this->oDocPath->sPath) {
						$this->iPageID = (int)$aFields[1];
						break;
					}
				}
			}
			if (!$this->iPageID && $bCreate) {
				$this->iPageID = $iMaxID + 1;
				$sFileContent = "\r\n" . $this->oDocPath->sPath . "\t" . $this->iPageID;
				if (!$oF->bAppend($sFileContent)) {
					$sFileContent = $oF->sContent() . $sFileContent;
               $oF->setContent($sFileContent);
               if (!$oF->bWrite()) {
	               $goApp->log("could not store new page iD " . $this->iPageID);
						$this->iPageID = 0;
               }
				}
			}
		}
		return $this->iPageID;
	}
	
	function iDocumentInstance()
	{
		return $this->iDocumentInstance;
	}
	
	function iLoopID()
	{
		return WYLoopElement::iCurrentLoopID();
	}

   function iDocumentInstanceForLoopID($iLoopID)
   {
      global $webyep_iDILIOffset;

      if (!$webyep_iDILIOffset) $webyep_iDILIOffset = $this->iDocumentInstance() * 100;
      return $iLoopID + $webyep_iDILIOffset;
   }

}
?>