<?php
// WebYep
// (C) Objective Development Software GmbH
// http://www.obdev.at

include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYElement.php");
include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYURL.php");

define("WY_IMAGE_VERSION", 2);
define("WY_DK_IMAGEFILENAME", "FILENAME");
define("WY_DK_THUMBNAIL_FILENAME", "THUMBNAIL");
define("WY_DK_URL", "URL");
define("WY_DK_ALTTEXT", "ALTTEXT");
define("WY_QK_IMAGE_WIDTH", "IMAGE_WIDTH");
define("WY_QK_IMAGE_HEIGHT", "IMAGE_HEIGHT");
define("WY_QK_IS_THUMB", "IS_THUMB");
define("WY_QK_IMAGE_DETAIL", "DETAIL");
define("WY_QK_IMAGE_ALTTEXT", "ALTETXT");

function webyep_image($sFieldName, $bGlobal, $sAttributes = "", $sURL = "", $sTarget = "", $iWidth = 0, $iHeight = 0, $bIsThumb = false)
{
	global $goApp;

	$o =& new WYImageElement($sFieldName, $bGlobal, $sAttributes, $sURL, $sTarget, $iWidth, $iHeight, $bIsThumb);
	$s = $o->sDisplay();
	if ($goApp->bEditMode) {
		echo $o->sEditButtonHTML();
		if (!$s) $s = $o->sName;
	}
	echo $s;
}

class WYImageElement extends WYElement
{
	// instance variables
	var $dAttributes;
	var $sURL;
	var $sTarget;
	var $iWidth;
	var $iHeight;
	var $bIsThumb;

	function WYImageElement($sN, $bG, $sA, $sU, $sT, $iW = 0, $iH = 0, $bThumb = false)
	{
		$aAtts = array();
		$aKV = array();
		$sPair = "";

		parent::WYElement($sN, $bG);
		$this->sEditorPageName = "image.php";
		$this->iEditorWidth = 500;
		$this->iEditorHeight = 280;
		$this->setVersion(WY_IMAGE_VERSION);
		if (!isset($this->dContent[WY_DK_IMAGEFILENAME])) $this->dContent[WY_DK_IMAGEFILENAME] = "";
		if (!isset($this->dContent[WY_DK_THUMBNAIL_FILENAME])) $this->dContent[WY_DK_THUMBNAIL_FILENAME] = "";
		if (!isset($this->dContent[WY_DK_URL])) $this->dContent[WY_DK_URL] = "";
		if (!isset($this->dContent[WY_DK_ALTTEXT])) $this->dContent[WY_DK_ALTTEXT] = "";

		$this->sAttributes = trim($sA);
      $this->iWidth = $iW;
      $this->iHeight = $iH;
      if ($this->iWidth != 0 || $this->iHeight != 0) {
         // remove width and height from attributes string
         $this->sAttributes = preg_replace('/width *= *"?[0-9]"?/', "", $this->sAttributes);
         $this->sAttributes = preg_replace('/height *= *"?[0-9]"?/', "", $this->sAttributes);
         $this->sAttributes = preg_replace('/  +/', " ", $this->sAttributes);
      }
      if ($this->dContent[WY_DK_ALTTEXT]) $this->sAttributes = preg_replace('/alt *= *"[^"]*"/', "", $this->sAttributes);

      $this->bIsThumb = $bThumb;
		$this->sURL = $sU;
		$this->sTarget = $sT;
	}

   function oDetailWindowURL()
   {
      global $goApp;
	   $oURL = od_clone($goApp->oProgramURL);
	   $oURL->addComponent("image-detail.php");
	   return $oURL;
   }
	
	function oImage()
	{
		global $goApp;
		$oImg = od_nil;
		$oURL = od_nil;
		$sFN = $this->dContent[WY_DK_IMAGEFILENAME];
		$sTNFN = $this->dContent[WY_DK_THUMBNAIL_FILENAME];

      $oURL = od_clone($goApp->oDataURL);
      if ($this->bIsThumb && $sTNFN) {
			$oURL->addComponent($sTNFN);
      }
		else if ($sFN) {
			$oURL->addComponent($sFN);
		}
		else $oURL = od_nil;
      if ($oURL) $oImg = new WYImage($oURL);
		return $oImg;
	}
	
	function oDetailImage()
	{
		global $goApp;
		$oImg = od_nil;
		$oURL = od_nil;
		$sFN = $this->dContent[WY_DK_IMAGEFILENAME];

      $oURL = od_clone($goApp->oDataURL);
      $oURL->addComponent($sFN);
      $oImg = new WYImage($oURL);
		return $oImg;
	}
	
	function deleteImage()
	// implicit save!
	{
		global $goApp;
		$oFile = od_nil;
		$sFN = $this->dContent[WY_DK_IMAGEFILENAME];

		if ($sFN) {
			$oPath = od_clone($goApp->oDataPath);
			$oPath->addComponent($sFN);
			$oFile =& new WYFile($oPath);
			if ($oFile->bExists() && !$oFile->bDelete()) $goApp->log("could not delete image file " . $oPath->sPath);
			$this->dContent[WY_DK_IMAGEFILENAME] = "";
		}
		$this->save();
	}

	function deleteThumbnail()
	// no implicit save!
	{
		global $goApp;
		$oFile = od_nil;
		$sFN = $this->dContent[WY_DK_THUMBNAIL_FILENAME];

		if ($sFN) {
			$oPath = od_clone($goApp->oDataPath);
			$oPath->addComponent($sFN);
			$oFile =& new WYFile($oPath);
			if ($oFile->bExists() && !$oFile->bDelete()) $goApp->log("could not delete thumbnail file " . $oPath->sPath);
			$this->dContent[WY_DK_THUMBNAIL_FILENAME] = "";
		}
	}

	function sFieldNameForFile()
	{
		$s = parent::sFieldNameForFile();
		$s = "im-" . $s;
		return $s;
	}

   function oThumbnailPathFor($oP)
   {
      $sExt = $oP->sExtension();
      $sPath = str_replace(".$sExt", "-tn.$sExt", $oP->sPath);
      return new WYPath($sPath);
   }

   function aContrainedSize($iWC, $iHC)
   // assumes $this->iWidth or $this->iHeight is set
   {
      $fXF = (float)$this->iWidth / (float)$iWC;
      $fYF = (float)$this->iHeight / (float)$iHC;

      if ($fXF == 0) $fF = $fYF;
      else if ($fYF == 0) $fF = $fXF;
      else $fF = min($fXF, $fYF);
      $iW = round($iWC * $fF);
      $iH = round($iHC * $fF);
      return array($iW, $iH);
   }
	
	function bUseUploadedImageFile(&$oFromPath, &$oOrgFilename)
	{
		global $goApp;
		$sNewFilename = "";
		$sExt = $oOrgFilename->sExtension();

		if ($oFromPath) {
			$oToPath = od_clone($goApp->oDataPath);
			$sNewFilename = $this->sDataFileName(true) . "-" . mt_rand(1000, 9999) . "." . $sExt;
			$oToPath->addComponent($sNewFilename);
         if ($goApp->bIsWindows()) $sMoveFunc = "copy";
         else $sMoveFunc = "move_uploaded_file";
         if ($sMoveFunc($oFromPath->sPath, $oToPath->sPath)) {
            chmod($oToPath->sPath, 0644);
            if ($this->iWidth != 0 || $this->iHeight != 0) { // resize the image
               list($iWC, $iHC) = WYImage::aGetImageSize($oToPath);
               if ($iWC == 0 || $iHC == 0) { // getting image size failed
                  if ($this->iWidth != 0 && $this->iHeight != 0) { // do we know _both_ dimensions?
	                  $iW = $this->iWidth;
	                  $iH = $this->iHeight;
                  }
                  else $goApp->log("could not get image size of: " . $oToPath->sPath);
               }
               else {
                  list($iW, $iH) = $this->aContrainedSize($iWC, $iHC);
	               if ($this->bIsThumb) {
                     $this->deleteThumbnail();
		               $oResizedPath = $this->oThumbnailPathFor($oToPath);
		               if (@copy($oToPath->sPath, $oResizedPath->sPath)) {
                        $this->dContent[WY_DK_THUMBNAIL_FILENAME] = $oResizedPath->sBasename();
		               }
		               else {
			               $goApp->log("could not copy image file " . $oToPath->sPath . " to " . $oResizedPath->sPath);
			               $oResizedPath = od_clone($oToPath);;
		               }
	               }
	               else $oResizedPath = od_clone($oToPath);
                  if (!WYImage::bResizeImage($oToPath, $oResizedPath, $iW, $iH)) {
                     $goApp->log("resizing failed for: " . $oFromPath->sPath . ", " . $oToPath->sPath);
                     $this->deleteThumbnail();
                  }
               }
            }
            $this->deleteImage();
            $this->dContent[WY_DK_IMAGEFILENAME] = $sNewFilename;
            return true;
         }
         else {
            $goApp->log("could not move uploaded image file: " . $oFromPath->sPath);
            return false;
         }
		}
	}
   
   function setLinkURL($sU)
   {
      $this->dContent[WY_DK_URL] = $sU;
   }

   function sLinkURL()
   {
      return $this->dContent[WY_DK_URL];
   }

   function setAltText($sT)
   {
      $this->dContent[WY_DK_ALTTEXT] = $sT;
   }

   function sAltText()
   {
      return $this->dContent[WY_DK_ALTTEXT];
   }

   function sEditButtonHTML()
   {
      $this->dEditorQuery = array();
      $this->dEditorQuery[WY_QK_IMAGE_WIDTH] = $this->iWidth;
      $this->dEditorQuery[WY_QK_IMAGE_HEIGHT] = $this->iHeight;
      $this->dEditorQuery[WY_QK_IS_THUMB] = $this->bIsThumb;
	   return parent::sEditButtonHTML();
   }

	function sDisplay()
	{
		global $goApp;
		$sHTML = "";
		$oImg = od_clone($this->oImage());
		$sAltText = $this->sAltText();
		$sAtt = "";
		$sAnchor = "";
		$iLoop = 0;
		$oURL = od_nil;

		if ($oImg) {
         if ($this->iWidth != 0 || $this->iHeight != 0) {
            $iW = $oImg->iWidth();
            $iH = $oImg->iHeight();
            if ($iW != 0 && $iH != 0) { // if image size could be determined
               if ($iW > $this->iWidth || $iH > $this->iHeight) { // image has not the correct size
	               list($iW, $iH) = $this->aContrainedSize($iW, $iH);
	               $this->sAttributes .= ($this->sAttributes ? " ":"") . "width=\"$iW\" height=\"$iH\"";
               }
            }
         }
         if ($this->sAttributes) {
				if (stristr($this->sAttributes, "width=") || stristr($this->sAttributes, "height=")) {
               // width or height can only be set in attributes if $this->iWidth/iHeight are _not_ used!
					$oImg->removeAttribute("width");
					$oImg->removeAttribute("height");
				}
				if (stristr($this->sAttributes, "alt=")) {
					$oImg->removeAttribute("alt");
				}
			}
			if ($this->sAttributes) {
				$sAtt = " " . $this->sAttributes;
			}
			if ($sAltText) {
            $oImg->setAttribute("alt", $sAltText);
			}
			$sAtt .= " />";
			$sHTML .= str_replace(" />", " $sAtt", $oImg->sDisplay());

			$oLink = od_nil;
			if ($this->sURL || $this->dContent[WY_DK_URL]) {
				$oURL = new WYURL($this->dContent[WY_DK_URL] ? $this->dContent[WY_DK_URL]:$this->sURL);
            if (!$this->dContent[WY_DK_URL]) {
               $iLoop = $goApp->oDocument->iLoopID();
               if ($iLoop) {
                  $oURL->dQuery[WY_QK_DI] = $goApp->oDocument->iDocumentInstanceForLoopID($iLoop);
               }
            }
				$oLink = new WYLink($oURL, "", true);
				if ($this->sTarget) $oLink->setAttribute("target", $this->sTarget);
			}
			if ($this->bIsThumb) {
            $oURL = $this->oDetailWindowURL();
				$oImg = $this->oDetailImage();
				$iW = $oImg->iWidth();
				$iH = $oImg->iHeight();
				if ($iW == 0) $iW = 400;
				if ($iH == 0) $iH = 400;
				$oURL->dQuery[WY_QK_IMAGE_DETAIL] = $oImg->oURL->sBasename();
				$oURL->dQuery[WY_QK_IMAGE_ALTTEXT] = $sAltText;
				if (!$oLink) $oLink = new WYLink(new WYURL("javascript:void(0)"));
				$oLink->setAttribute("onclick", sprintf("wydw=window.open(\"%s\", \"WYDetail\", \"width=%d,height=%d,status=yes,scrollbars=no,resizable=yes\"); wydw.focus();", $oURL->sURL(true, true, true), $iW, $iH));
			}
			if ($oLink) {
				if ($sAltText) $oLink->setToolTip($sAltText);
				$oLink->setInnerHTML($sHTML);
				$sHTML = $oLink->sDisplay();
			}
		}
		return $sHTML;
	}
}
?>