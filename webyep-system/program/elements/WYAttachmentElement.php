<?php
// WebYep
// (C) Objective Development Software GmbH
// http://www.obdev.at

include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYElement.php");
include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYURL.php");

define("WY_ATTACHMENT_VERSION", 1);
define("WY_DK_ATTACHMENT_FILENAME", "FILENAME");
define("WY_QK_DOWNLOAD_FILENAME", "FILENAME");
define("WY_QK_ORIGINAL_FILENAME", "ORG_FILENAME");

function webyep_attachment($sFieldName)
{
	global $goApp;

	$o =& new WYAttachmentElement($sFieldName);
	$s = $o->sDisplay();
	if ($goApp->bEditMode) {
		echo $o->sEditButtonHTML();
		if (!$s) $s = $o->sName;
	}
	echo $s;
}

class WYAttachmentElement extends WYElement
{
	// instance variables

	function WYAttachmentElement($sN)
	{
		parent::WYElement($sN, false);
		$this->sEditorPageName = "attachment.php";
		$this->iEditorWidth = 650;
		$this->iEditorHeight = 250;
		$this->setVersion(WY_ATTACHMENT_VERSION);
		if (!isset($this->dContent[WY_DK_ATTACHMENT_FILENAME])) $this->dContent[WY_DK_ATTACHMENT_FILENAME] = "";
	}
	
   function sDownloadFileName()
   {
      $sFN = "";
      $sOrg = $this->sOriginalFilename();
      if ($sOrg) {
         $oOrg =& new WYPath($sOrg);
         $sExt = $oOrg->sExtension();
         $sFN = $this->sDataFileName(false) . ($sExt !== "" ? ".$sExt":".dat");
      }
      return $sFN;
   }
   
   function sOriginalFilename()
   {
      $sOrg = $this->dContent[WY_DK_ATTACHMENT_FILENAME];
      return $sOrg;
   }

   function setOriginalFilename($s)
   {
      $s = str_replace(" ", "_", $s);
      $this->dContent[WY_DK_ATTACHMENT_FILENAME] = $s;
   }

	function oFile()
	{
		global $goApp;
		$oFile = od_nil;
		$oURL = od_nil;
		$sFN = $this->sDownloadFileName();

      $oURL = od_clone($goApp->oDataURL);
      $oURL->addComponent($sFN);
      $oFile =& new WYFile($oURL);
		return $oFile;
	}
	
	function deleteFile()
	{
		global $goApp;
		$oFile = od_nil;
		$sFN = $this->sDownloadFileName();

      if ($sFN) {
         $oPath = od_clone($goApp->oDataPath);
         $oPath->addComponent($sFN);
         $oFile =& new WYFile($oPath);
         if ($oFile->bExists() && !$oFile->bDelete()) $goApp->log("could not delete attachment file " . $oPath->sPath);
         $this->setOriginalFilename("");
         $this->save();
      }
	}

	function sFieldNameForFile()
	{
		$s = parent::sFieldNameForFile();
		$s = "at-" . $s;
		return $s;
	}
	
	function useUploadedFile(&$oFromPath, &$oOrgFilename)
	{
		global $goApp;
		$sFN = "";

		if ($oFromPath) {
         $this->deleteFile();
         $this->setOriginalFilename($oOrgFilename->sPath);
         $sFN = $this->sDownloadFileName();
			$oToPath = od_clone($goApp->oDataPath);
         $oToPath->addComponent($sFN);
         if ($goApp->bIsWindows()) $sMoveFunc = "copy";
         else $sMoveFunc = "move_uploaded_file";
			if (!$sMoveFunc($oFromPath->sPath, $oToPath->sPath)) {
				$goApp->log("could not move uploaded attachment file: " . $oFromPath->sPath);
            $this->deleteFile();
            $this->setOriginalFilename("");
			}
         else {
				chmod($oToPath->sPath, 0644);
         }
		}
	}

	function sDisplay()
	{
		global $goApp;
		$sHTML = "";
      $sFN = $this->sDownloadFileName();
      $oURL = $oLink = od_nil;

      if ($sFN) {
         $oURL = od_clone($goApp->oProgramURL);
         $oURL->addComponent("download.php");
         $oURL->dQuery[WY_QK_DOWNLOAD_FILENAME] = $sFN;
         $oURL->dQuery[WY_QK_ORIGINAL_FILENAME] = $this->sOriginalFilename();
         $oLink =& new WYLink($oURL, WYTS("DownloadHint"));
         $oLink->setInnerHTML($this->sOriginalFilename());
         $sHTML .= $oLink->sDisplay();
      }

		return $sHTML;
	}
}
?>