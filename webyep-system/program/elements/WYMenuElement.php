<?php
// WebYep
// (C) Objective Development Software GmbH
// http://www.obdev.at

include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYElement.php");

define("WY_MENU_VERSION", 1);
define("WY_DK_MAXID", "LIID");
define("WY_DK_ITEMSARRAY", "CONTENT");
define("WY_DK_ITEMID", "ID");
define("WY_DK_ITEMTEXT", "TEXT");

define("WEBYEP_MENU_INDENT", "&nbsp;");
// CSS
define("WEBYEP_MENU_CSS_ITEM", "WebYepMenuItem");
define("WEBYEP_MENU_CSS_SELECTED", "WebYepMenuSelected");
define("WEBYEP_MENU_CSS_TITLE", "WebYepMenuTitle");
define("WEBYEP_MENU_CSS_SEPERATOR", "WebYepMenuSeperator");

function webyep_menu($sFieldName, $bGlobal, $sURL, $sTarget = "", $sDepricatedA = "", $sBulletImg = "")
{
	// $sDepricatedA: was $sSelectedItemStyle, now non configurable WEBYEP_MENU_CSS_SELECTED
	global $goApp;
	$oURL = od_nil;
	$oBulletImg = od_nil;
	
	$oURL =& new WYURL($sURL);
	if ($sBulletImg) $oBulletImg =& new WYImage(new WYURL($sBulletImg));
	$o =& new WYMenuElement($sFieldName, $bGlobal, $oURL, $sTarget, $oBulletImg);
	$s = $o->sDisplay();

	// this should be removed an future versions
	if ($sDepricatedA) {
		$s = str_replace(WEBYEP_MENU_CSS_SELECTED, $sDepricatedA, $s);
	}

	if ($goApp->bEditMode) {
		echo $o->sEditButtonHTML();
		if (!$s) $s = $o->sName;
		else echo "<br>";
	}
	echo $s;
}

class WYMenuElement extends WYElement
{
	// instance variables
	var $oURL;
	var $sTarget;
	var $oBulletImg;

	function WYMenuElement($sN, $bG, $oURL, $sT, $oB)
	// don't pass object by ref here: can be od_nil!
	{
      global $webyep_bUseTablesForMenus;

		parent::WYElement($sN, $bG);
		$this->sEditorPageName = "menu.php";
		$this->iEditorWidth = 470;
		$this->iEditorHeight = 490;
		$this->setVersion(WY_MENU_VERSION);
		if (!isset($this->dContent[WY_DK_ITEMSARRAY])) $this->dContent[WY_DK_ITEMSARRAY] = array();
		if (!isset($this->dContent[WY_DK_MAXID])) $this->dContent[WY_DK_MAXID] = 0;
		$this->oURL =& $oURL;
		$this->sTarget = $sT;
		$this->oBulletImg =& $oB;
		if ($this->oBulletImg) $this->oBulletImg->setAttribute("align", "absmiddle");
      $this->bUseTables = $webyep_bUseTablesForMenus;
	}
	
	function bUseDocumentInstance()
	{
		return false;
	}

	function sFieldNameForFile()
	{
		$s = parent::sFieldNameForFile();
		$s = "mu-" . $s;
		return $s;
	}
	
	function setItems($d)
	{
		$sKey = "";
		$sValue = "";
		$dOneItem = array();
		$iMaxID = 0;

		$this->dContent[WY_DK_ITEMSARRAY] = array();
		foreach ($d as $sKey => $sValue) {
			// replace leading spaces or underscores by undescores
			// spaces would get lost on next save due to JavaScript's <options> handling
			if (ereg("^([ _]+)", $sValue, $aRes)) {
				$sValue = str_repeat("_", strlen($aRes[1])) . ltrim($sValue, " _");
			}
			$dOneItem[WY_DK_ITEMID] = $sKey;
			$iMaxID = max((int)$sKey, $iMaxID);
			$dOneItem[WY_DK_ITEMTEXT] = $sValue;
			$this->dContent[WY_DK_ITEMSARRAY][] =$dOneItem;
		}
		$this->dContent[WY_DK_MAXID] = $iMaxID;
	}
	
	function dItems()
	{
		$d = array();
		$dOneItem = array();
		
		foreach ($this->dContent[WY_DK_ITEMSARRAY] as $dOneItem) {
			$d[$dOneItem[WY_DK_ITEMID]] = $dOneItem[WY_DK_ITEMTEXT];
		}
		return $d;
	}
	
	function iLastItemID()
	{
		return $this->dContent[WY_DK_MAXID];
	}
	
	function sDisplay()
	{
		global $goApp;
		$aEntries = $this->dContent[WY_DK_ITEMSARRAY];
		$dEntry = array();
		$sIndent = "";
		$sBullet = "";
		$oBulletIndentImg = od_nil;
      $sBulletIndent = "";
		$sText = "";
		$sLinkTitle = "";
		$sClass = "";
		$aRes = array();
		$i = 0;
		$bIsTitle = false;
		$sTarget = "";
		$sHTML = "";
		$bFirstTitle = true;
		$sLineBreak = "";
		$bIsTargetPage = false;
		$oFullTargetURL = od_clone($this->oURL);
		$oCurrentURL = WYURL::oCurrentURL();
      $bUseTables = $this->bUseTables;

		if (count($aEntries)) {
			$oFullTargetURL->makeSiteRelative();
			$oCurrentURL->dQuery = array();
			$bIsTargetPage = $oFullTargetURL->sURL() == $oCurrentURL->sURL();
			if ($bUseTables) $sHTML .= "<table cellPadding='0' cellSpacing='0'>\n";
			foreach ($aEntries as $dEntry) {
				$sText = $dEntry[WY_DK_ITEMTEXT];
				$sLinkTitle = $sText;

				// check for submenu title marker "#"
				$bIsTitle = false;
				if (substr($sText, 0, 1) == "#") {
					$sText = substr($sText, 1);
					$bIsTitle = true;
				}
	
				$sIndent = "";
				$sBullet = "";
				if (ereg("^([_ ]+)", $sText, $aRes)) {	// transform leading " " and "_" to "$nbsp;"
					$i = strlen($aRes[1]);
					$sText = substr($sText, $i);	// remove leading " " and "_"
					while ($i) {
						$sIndent .= WEBYEP_MENU_INDENT;
						$i--;
					}
				}
	
				// check for submenu title marker "#" again
				if (!$bIsTitle && substr($sText, 0, 1) == "#") {
					$sText = substr($sText, 1);
					$bIsTitle = true;
				}
	
				$sText = webyep_sHTMLEntities($sText);

            if ($this->oBulletImg) $sBullet .= $this->oBulletImg->sDisplay() . "&nbsp;";
				if ($bUseTables) $sText = str_replace("\\", "<br>", $sText);
            else {
               if ($this->oBulletImg) {
                  $oBulletIndentImg = $goApp->oSpacerImg($this->oBulletImg->iWidth(), 1);
                  $sBulletIndent = $oBulletIndentImg->sDisplay() . "&nbsp;";
               }
               $sLineBreak = "<br>$sBulletIndent$sIndent";
               $sText = str_replace("\\", $sLineBreak, $sText);
            }

				$sLinkTitle = str_replace("\\", " ", $sLinkTitle);
				$sLinkTitle = eregi_replace('^[ _]+', "", $sLinkTitle);
            $sLinkTitle = webyep_sHTMLEntities($sLinkTitle);

				if (($dEntry['ID'] == $goApp->oDocument->iDocumentInstance()) && $bIsTargetPage) {
					$sClass = WEBYEP_MENU_CSS_SELECTED;
				}
				else {
					$sClass = WEBYEP_MENU_CSS_ITEM;
				}
				if ($bIsTitle) {
					if (!$bFirstTitle) $sHTML .= "<div class='" . WEBYEP_MENU_CSS_SEPERATOR . "'>";
					$sHTML .= "<div class='" . WEBYEP_MENU_CSS_TITLE . "'>$sIndent$sBullet$sText</div>\n";
					if (!$bFirstTitle) $sHTML .= "</div>";
					$bFirstTitle = false;
				}
				else {
					$this->oURL->dQuery[WY_QK_DI] = $dEntry[WY_DK_ITEMID];
					$sTarget = ($this->sTarget ? (" target='" . $this->sTarget . "'"):"");
					if ($bUseTables) $sHTML .= "<tr><td align='left' valign='top'>$sIndent$sBullet</td><td align='left' valign='top' class='$sClass'><a title='$sLinkTitle' href='" . $this->oURL->sURL() ."'$sTarget>$sText</a></td></tr>\n";
					else $sHTML .= "<div class='$sClass'>$sIndent$sBullet<a title='$sLinkTitle' href='" . $this->oURL->sURL() ."'$sTarget>$sText</a></div>\n";
				}
			}
			if ($bUseTables) $sHTML .= "</table>";
		}
		return $sHTML;
	}

}
?>