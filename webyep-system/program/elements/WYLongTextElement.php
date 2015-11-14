<?php
// WebYep
// (C) Objective Development Software GmbH
// http://www.obdev.at

include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYElement.php");

define("WY_LONGTEXT_VERSION", 1);

// public API

function webyep_sLongTextContent($sFieldName, $bGlobal)
{
	$o =& new WYLongTextElement($sFieldName, $bGlobal, false);
	return $o->sText();
}

// ----------------------------------------------

class WYLongTextElement extends WYElement
{
	// instance variables
	var $bHideEMailAddress;

   function webyep_longText($sFieldName, $bGlobal, $depricated1 = "", $bHide = false)
   {
      global $goApp;

      $o =& new WYLongTextElement($sFieldName, $bGlobal, $bHide);
      $s = $o->sDisplay();
      if ($goApp->bEditMode) {
         echo $o->sEditButtonHTML();
         if (!$s) $s = $o->sName;
      }
      echo $s;
   }

	function WYLongTextElement($sN, $bG, $bHide)
	{
		parent::WYElement($sN, $bG);
		$this->sEditorPageName = "long-text.php";
		$this->iEditorWidth = 520;
		$this->iEditorHeight = 450;
		$this->bHideEMailAddress = $bHide;
		$this->setVersion(WY_LONGTEXT_VERSION);
		if (!isset($this->dContent[WY_DK_CONTENT])) $this->dContent[WY_DK_CONTENT] = "";
	}
	
	function sFieldNameForFile()
	{
		$s = parent::sFieldNameForFile();
		$s = "lt-" . $s;
		return $s;
	}
	
	function sText()
	{
		return $this->dContent[WY_DK_CONTENT];
	}
	
	function setText($s)
	{
		$this->dContent[WY_DK_CONTENT] = $s;
	}

   function _sFormatEMailLinks($sLine)
   // also used by guestbook!
   {
      return preg_replace("/([-a-zA-Z0-9_.]+)@([-a-zA-Z0-9_.]+[-a-zA-Z0-9_])/", "<script language='JavaScript' type='text/javascript'> document.write(\"<a href='mailto:\\1\"+String.fromCharCode(64)+\"\\2'>\\1\"+String.fromCharCode(64)+\"\\2</a>\"); </script><noscript>\\1(_AT_)\\2</noscript>", $sLine);
   }

   function _aSplitTableCells($sLine)
   {
	   $i = 0;
	   $iLastStart = 0;
	   $iL = strlen($sLine);
	   $aCells = array();
      while ($i < $iL) {
         if ($sLine[$i] == '|' && ($i == 0 || $sLine[$i-1] != '\\')) {
            $iLen = $i - $iLastStart;
            if ($iLen > 0) $aCells[] = substr($sLine, $iLastStart, $iLen);
            else $aCells[] = "";
	         $iLastStart = $i + 1;
         }
	      $i++;
      }
      $iLen = $i - $iLastStart;
      if ($iLen > 0) $aCells[] = substr($sLine, $iLastStart, $iLen);
	   return $aCells;
   }

   function _sFormatContent($sContent, $bHideEMailAddress)
   // also used by guestbook as class method - do not use $this->!
   {
		global $goApp, $webyep_sCharset;
		$aOutLines = array();
		$sASCIIBullets = chr(149) . chr(183) . "+";

		if ($sContent) {
			$sContent = str_replace("\r\n", "\n", $sContent);
			$sContent = str_replace("\r", "\n", $sContent);
			$aInLines = split("\n", $sContent);
		}
		else $aInLines = array();
		
		$dTable = get_html_translation_table(HTML_ENTITIES);
		$dTable["<"] = "<";
		$dTable[">"] = ">";
		$dTable[chr(183)] = chr(183); // leave middot allone - needed for lists
	
		$sOutLine = "";
		$bInLI = false;
      $bInTable = false;
		$bNL = true;
      $sListType = "";
      $iListLevel = 0;
      $iNumLines = count($aInLines);
      $iLine = 0;
		foreach ($aInLines as $sLine)
		{
			$bNL = true;
			$sOutLine = "";
			if (preg_match("/^([*$sASCIIBullets]+)[ \t]+/", $sLine, $aReg) && strpos($sLine, "|") == false) { // bullet list item
            if (substr($aReg[1], 0, 1) == "+") $sListType = "ol";
            else $sListType = "ul";
            $iNewListLevel = strlen($aReg[1]);
				$sLine = preg_replace("/^[*$sASCIIBullets]+[ \t]+/", "", $sLine);
				if ($iNewListLevel > $iListLevel) {
					$sOutLine .= "\n" . "<$sListType>\n";
				}
				else if ($iNewListLevel < $iListLevel) {
               $sOutLine .= "</li>\n";
               while ($iNewListLevel != $iListLevel) {
	               $sOutLine .= "</$sListType>\n";
                  $sOutLine .= "</li>\n";
                  $iListLevel--;
               }
				}
				else
            $sOutLine .= "</li>\n";
            $iListLevel = $iNewListLevel;
				$sOutLine .= "<li>";
				$bInLI = true;
				$bNL = false;
			}
			else if (trim($sLine) != "" && $iListLevel > 0) { // continued bullet list item
				$sLine = ltrim($sLine); // strip indention
				$sOutLine .= "\n<br />    ";
				$bNL = false;
			}

			// if we don't have a defined charset, create HTML entities:
			if (!$webyep_sCharset) $sLine = strtr($sLine, $dTable);

         $sLine = preg_replace('/\\\\>/', '&gt;', $sLine);
         $sLine = preg_replace('/\\\\</', '&lt;', $sLine);

			$sLine = preg_replace("/<FETT ([^>]*)>/", "<strong>\\1</strong>", $sLine);
			$sLine = preg_replace("/<BOLD ([^>]*)>/", "<strong>\\1</strong>", $sLine);
			$sLine = preg_replace("/<([^>a-z:# ]*) ([^>]*)>/", "<span class='\\1'>\\2</span>", $sLine);

			// $sLine = ereg_replace("<LINK: *http(s?)://([^ ]*) ([^>]*)>", "<a href='HtTp\\1://\\2' target='_blank'>\\3</a>", $sLine);
			// $sLine = ereg_replace("<LINK: *HTTP(S?)://([^ ]*) ([^>]*)>", "<a href='HtTp\\1://\\2' target='_blank'>\\3</a>", $sLine);
			$sLine = preg_replace("|<LINK: *http(s?)://([^ ]*) ([^>]*)>|", "<a href='HtTp\\1://\\2'>\\3</a>", $sLine);
			$sLine = preg_replace("|<LINK: *HTTP(S?)://([^ ]*) ([^>]*)>|", "<a href='HtTp\\1://\\2'>\\3</a>", $sLine);
			$sLine = preg_replace("|<LINK: *([^ ]*) ([^>]*)>|", "<a href='\\1'>\\2</a>", $sLine);

			$sLine = preg_replace("{((HTTP)|(http))://([-a-zA-Z0-9_/.~%#?=&;]+[-a-zA-Z0-9_/~%#?=&;])}", "<a href='HtTp://\\4' target='_blank'>http://@@\\4</a>", $sLine);
			while (preg_match("|(.*)http://@@([-a-zA-Z0-9_/.~%]+)(.*)|", $sLine, $aReg)) {
				$sLine = $aReg[1] . "http://" . str_replace("/", "/<wbr>", $aReg[2]) . $aReg[3];
			}
			$sLine = preg_replace("{((HTTPS)|(https))://([-a-zA-Z0-9_/.~%#?=&;]+[-a-zA-Z0-9_/~%#?=&;])}", "<a href='HtTpS://\\4' target='_blank'>https://@@\\4</a>", $sLine);
			while (preg_match("|(.*)https://@@([-a-zA-Z0-9_/.~%]+)(.*)|", $sLine, $aReg)) {
				$sLine = $aReg[1] . "https://" . str_replace("/", "/<wbr>", $aReg[2]) . $aReg[3];
			}

			if ($bHideEMailAddress) {
				if (preg_match("|[-a-zA-Z0-9_.]+@[-a-zA-Z0-9_.]+|", $sLine)) {
					$sLine = WYLongTextElement::_sFormatEMailLinks($sLine);
				}
			}
			else {
				$sLine = preg_replace('|([-a-zA-Z0-9_.]+@[-a-zA-Z0-9_.]+[-a-zA-Z0-9_])|', "<a href='mailto:\\1'>\\1</a>", $sLine);
			}

			if (preg_match('|^-+$|', $sLine)) {
            $sLine = "<hr noshade='noshade' size='1' />\n";
				$bNL = false;
         }

			$sOutLine .= $sLine;

			if ($iListLevel > 0 && (trim($sLine) == "" || !preg_match("|[^ \t*$sASCIIBullets]+|", $sLine))) { // end of bullet list
				while ($iListLevel > 0) {
               $sOutLine .= "</li>\n";
					$sOutLine .= "</$sListType>\n";
					$iListLevel--;
					$bInLI = false;
				} 
				$bNL = false;
				$bInLI = false;
			}

         if ($iListLevel == 0 && preg_match('/([^\\\\]\|)|(^\|)/', $sOutLine)) {
            // $aCells = split('([^\\\\]\|)|(^\|)', $sOutLine);
            $aCells = WYLongTextElement::_aSplitTableCells($sOutLine);
            $sOutLine = "<tr>\n";
            foreach ($aCells as $sCell) {
               $sCell = trim($sCell);
               $sOutLine .= "   <td>$sCell</td>\n";
            }
            $sOutLine .= "</tr>\n";
            if (!$bInTable) {
               $sOutLine = "<table border=0 cellpadding=0 cellspacing=0>\n" . $sOutLine;
               $bInTable = true;
            }
            $bNL = false;
         }
         else if ($bInTable && !preg_match('/([^\\\\]\|)|(^\|)/', $sOutLine)) {
            $sOutLine = "</table>$sOutLine\n";
            $bInTable = false;
            $bNL = false;
         }

         $sOutLine = preg_replace('/\\\\\|/', '|', $sOutLine);
         $iLine++;
         if ($iLine >= $iNumLines) $bNL = false;
			$aOutLines[] = $sOutLine . ($bNL ? "<br />\n":"");
		}

      // close everything left open:
		//if ($bInLI) $aOutLines[] = "</li>\n";
		if ($bInLI) $aOutLines[] = "\n";
      while ($iListLevel) {
         $aOutLines[] .= "</$sListType>\n";
         $iListLevel--;
      } 
		if ($bInTable) $aOutLines[] = "</table>\n";

		$sContent = join("", $aOutLines);
		$sContent = str_replace("<br />\n.<br />", "<br clear='all' />", $sContent);
		$sContent = str_replace("<br />\n<table>", "\n<table>", $sContent);
//		$sContent = str_replace("</ul><ul>", "", $sContent);
//		$sContent = str_replace("</ul><br>\n<ul>", "</ul><ul>", $sContent);
		return $sContent;
   }

	function sDisplay()
	{
		$sContent = "";
		$aInLines = array();
		$aReg = array();
		$sLine = "";
		$iLastLineWasBL = 0;
		$sHTMLBullet = "&bull;";


		$sContent = $this->sText();
      $sContent = $this->_sFormatContent($sContent, $this->bHideEMailAddress);
      return $sContent;
	}
}
?>