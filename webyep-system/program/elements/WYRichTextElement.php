<?php
// WebYep
// (C) Objective Development Software GmbH
// http://www.obdev.at

include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYElement.php");

define("WY_RICHTEXT_VERSION", 1);
define("WY_QK_RICH_TEXT_CSS", "CSS_URL");

// public API

function webyep_sRichTextContent($sFieldName, $bGlobal)
{
	$o =& new WYRichTextElement($sFieldName, $bGlobal, false);
	return $o->sText();
}

// ----------------------------------------------

class WYRichTextElement extends WYElement
{
	// instance variables
   var $oCSSURL;
   var $bObfuscate;

   function webyep_richText($sFieldName, $bGlobal, $sCSSURL = "", $bObfuscate = true)
   {
      global $goApp;

      $o =& new WYRichTextElement($sFieldName, $bGlobal, $sCSSURL, $bObfuscate);
      $s = $o->sDisplay();
      if ($goApp->bEditMode) {
         echo $o->sEditButtonHTML();
         if (!$s) $s = $o->sName;
      }
      echo $s;
   }

	function WYRichTextElement($sN, $bG, $sCSSURL, $bObfuscate)
	{
		parent::WYElement($sN, $bG);
		$this->sEditorPageName = "rich-text.php";
		$this->iEditorWidth = 850;
		$this->iEditorHeight = 620;
		if ($sCSSURL) {
			$this->oCSSURL = new WYURL($sCSSURL);
			$this->oCSSURL->makeSiteRelative();
		}
		else $this->oCSSURL = od_nil;
		$this->bObfuscate = $bObfuscate;
		$this->setVersion(WY_RICHTEXT_VERSION);
		if (!isset($this->dContent[WY_DK_CONTENT])) {
			// check whether a Long Text Element with same field name left some data...
			$oLT = new WYLongTextElement($sN, $bG, false);
			if ($oLT->sText() != "") $this->dContent[WY_DK_CONTENT] = $oLT->sDisplay();
			else $this->dContent[WY_DK_CONTENT] = "";
		}
	}
	
	function sFieldNameForFile()
	{
		$s = parent::sFieldNameForFile();
		$s = "rt-" . $s;
		return $s;
	}
	
   function sEditButtonHTML()
   {
      $this->dEditorQuery = array();
      if ($this->oCSSURL) $this->dEditorQuery[WY_QK_RICH_TEXT_CSS] = $this->oCSSURL->sURL();
      else $this->dEditorQuery[WY_QK_RICH_TEXT_CSS] = "";
	   return parent::sEditButtonHTML();
   }

	function sText()
	{
		return $this->dContent[WY_DK_CONTENT];
	}
	
	function setText($s)
	{
		$this->dContent[WY_DK_CONTENT] = $s;
	}

   function _sObfuscateEMailAddresses($sText)
   {
	   $sText =  preg_replace('|<a[^>]+href=[\'"]mailto:([^\'"@]+)@([^\'"]+)[\'"][^>]*>([^@]+)@(.+)</a>|U', '<script language="JavaScript" type="text/javascript"> document.write(\'<a href="mailto:\\1\'+String.fromCharCode(64)+\'\\2">\\3\'+String.fromCharCode(64)+\'\\4</a>\'); </script><noscript>\\1(_AT_)\\2</noscript>', $sText);
	   return $sText;
   }

	function sDisplay()
	{
		$sContent = "";

		$sContent = $this->dContent[WY_DK_CONTENT];
      if ($this->bObfuscate) $sContent = $this->_sObfuscateEMailAddresses($sContent);
      return $sContent;
	}
}
?>