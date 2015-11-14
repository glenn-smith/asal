<?php
// WebYep
// (C) Objective Development Software GmbH
// http://www.obdev.at

include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYApplication.php");
include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYHTMLTag.php");

class WYFileUpload extends WYHTMLTag
{
	// instance variables
	// private
	var $dFileInfos;
	
	// instance methods
	function WYFileUpload($sN)
	{
		global $goApp;

		parent::WYHTMLTag("input");
		$this->dAttributes["type"] = "file";
		$this->dAttributes["name"] = $sN;
		$this->dFileInfos = od_nil;
		if (isset($_FILES[$sN])) {
         $this->dFileInfos = $_FILES[$sN];
			if (!$this->bUploadOK())
				$goApp->log("error on file upload: " . $this->iErrorCode() . ": " . $this->sErrorMessage());
         // security check
         $sOFN = isset($this->dFileInfos["name"]) ? $this->dFileInfos["name"]:"";
         $oOFN =& new WYPath($sOFN);
         if (!$oOFN->bCheck(WYPATH_CHECK_NOSCRIPT|WYPATH_CHECK_NOPATH)) {
				$goApp->log("error on file upload: illegal file type/name <$sOFN>");
            @unlink($_FILES[$sN]["tmp_name"]);
            $_FILES[$sN] = array("error" => -1);
            $this->dFileInfos = $_FILES[$sN];
         }
		}
	}
	
   function iErrorCode()
   {
		if (isset($this->dFileInfos["error"])) return $this->dFileInfos["error"];
      else return 0;
   }
   
   function bUploadOK()
   {
      $i = $this->iErrorCode();
      return $i == UPLOAD_ERR_OK || $i == UPLOAD_ERR_NO_FILE;
   }
   
   function bFileUploaded()
   {
      $i = $this->iErrorCode();
      return $i !== UPLOAD_ERR_NO_FILE && $this->dFileInfos['name'];
   }
   
   function sErrorMessage()
   {
      $s = "";
      switch($this->iErrorCode()) {
         case UPLOAD_ERR_INI_SIZE:
         case UPLOAD_ERR_FORM_SIZE:
            $s = WYTS("FileUploadErrorSize");
         break;
         case UPLOAD_ERR_PARTIAL:
            $s = WYTS("FileUploadErrorInterrupted");
         break;
         case UPLOAD_ERR_NO_FILE:
            $s = WYTS("FileUploadErrorNoFile");
         break;
         default:
            $s = WYTS("FileUploadErrorUnknown");
         break;
      }
      return $s;
   }

	function setWidth($i)
	{
		$this->dAttributes["size"] = $i;
	}
	
	function oFilePath()
	{
		$oP = od_nil;
		
		if ($this->bUploadOK()) {
			$oP =& new WYPath($this->dFileInfos["tmp_name"]);
		}
		return $oP;	
	}
	
	function oOriginalFilename()
	{
		$oP = od_nil;
		
		if ($this->bUploadOK()) {
			$oP =& new WYPath($this->dFileInfos["name"]);
		}
		return $oP;	
	}
}
?>