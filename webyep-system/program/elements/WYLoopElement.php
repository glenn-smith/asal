<?php
// WebYep
// (C) Objective Development Software GmbH
// http://www.obdev.at

include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYElement.php");
include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYLink.php");

define("WY_LOOP_VERSION", 1);
define("WY_DK_LOOPIDARRAY", "CONTENT");
define("WY_QV_LOOP_ADD", "LOOP_ADD");
define("WY_QV_LOOP_REMOVE", "LOOP_REMOVE");
define("WY_QV_LOOP_UP", "LOOP_UP");
define("WY_QV_LOOP_DOWN", "LOOP_DOWN");
define("WY_QK_LOOP_ID", "WEBYEP_LOOP_ID");

$webyep_oCurrentLoop = od_nil;

class WYLoopElement extends WYElement
{
	var $iLoopID;
	var $iElementsLeft;

	// class functions
	function aLoopIDs($sN)
	{
		global $webyep_oCurrentLoop;
		
		$webyep_oCurrentLoop = new WYLoopElement($sN);
		return $webyep_oCurrentLoop->_aLoopIDs();
	}

	function iCurrentLoopID()
	{
		global $webyep_oCurrentLoop;

		if ($webyep_oCurrentLoop != od_nil && $webyep_oCurrentLoop->iElementsLeft > 0) return $webyep_oCurrentLoop->iLoopID;
		else return 0;
	}

	function setCurrentLoopID($i)
	// used by WYDocument to 'simulate" loop ID for editors
	{
		global $webyep_oCurrentLoop;

		// as we store the loopID in an instance, we need an instance...
		if (!$webyep_oCurrentLoop) $webyep_oCurrentLoop = new WYLoopElement("");
		$webyep_oCurrentLoop->iLoopID = $i;
	}

	// instance functions
	function WYLoopElement($sN)
	{
		global $goApp;

		parent::WYElement($sN, false);
		$this->sEditorPageName = "";
		$this->setVersion(WY_LOOP_VERSION);
		if (!isset($this->dContent[WY_DK_LOOPIDARRAY])) $this->dContent[WY_DK_LOOPIDARRAY] = array(1);
		else if (!count($this->dContent[WY_DK_LOOPIDARRAY])) $this->dContent[WY_DK_LOOPIDARRAY] = array(1);
		$this->iLoopID = 0;
		if ($goApp->bEditMode) $this->dispatchEditAction();
		$this->iElementsLeft = count($this->_aLoopIDs());
	}
	
	function bUseLoopID()
	{
		return false;
	}

	function sFieldNameForFile()
	{
		$s = parent::sFieldNameForFile();
		$s = "lo-" . $s;
		return $s;
	}
	
	function _aLoopIDs()
	{
		return $this->dContent[WY_DK_LOOPIDARRAY];
	}

	function _setLoopIDs($a)
	{
		$this->dContent[WY_DK_LOOPIDARRAY] = $a;
	}

	function loopStart($bShowControls = true)
	{
		global $goApp;

		if ($bShowControls) $this->showEditButtons();
		$goApp->outputWarningPanels(); // give App a chance to say something
	}
	
	function showEditButtons()
	{
		global $goApp;
		
		if ($goApp->bEditMode) {
         $oURL = od_clone(WYURL::oCurrentURL());
         $oLink = od_nil;
         $oImg = od_nil;
         $oImgURL = od_clone($goApp->oImageURL);
         $dEditQuery = WYEditor::dQueryForElement($this);
         $aLoopIDs = $this->_aLoopIDs();
         $iCount = count($aLoopIDs);

			$dEditQuery[WY_QK_LOOP_ID] = $this->iCurrentLoopID();
			$goApp->setActionInQuery($dEditQuery, WY_QV_LOOP_ADD);
			$oURL->setQuery(array_merge($oURL->dQuery, $dEditQuery));
			$oLink = new WYLink($oURL, WYTS("LoopAddButton"));
			$oImgURL->addComponent("add-button.gif");
			$oImg = new WYImage($oImgURL);
			$oImg->setAttribute("alt", WYTS("LoopAddButton"));
			$oLink->setInnerHTML($oImg->sDisplay());
			echo $oLink->sDisplay();
			if ($iCount > 1) {
				$dEditQuery = $oURL->dQuery;

				$goApp->setActionInQuery($dEditQuery, WY_QV_LOOP_REMOVE);
				$oURL->setQuery($dEditQuery);
				$oLink = new WYLink($oURL, WYTS("LoopRemoveButton"));
				$oImgURL->removeLastComponent();
				$oImgURL->addComponent("remove-button.gif");
				$oImg = new WYImage($oImgURL);
            $oImg->setAttribute("alt", WYTS("LoopRemoveButton"));
				$oLink->setInnerHTML($oImg->sDisplay());
				$oLink->setAttribute("onclick", "return confirm(\"" . WYTS("LoopRemoveConfirm") . "\");");
				echo $oLink->sDisplay();

				$oLink->removeAttribute("onclick");

				$goApp->setActionInQuery($dEditQuery, WY_QV_LOOP_UP);
				$oURL->setQuery($dEditQuery);
				$oLink = new WYLink($oURL, WYTS("LoopUpButton"));
				$oImgURL->removeLastComponent();
				$oImgURL->addComponent("up-button.gif");
				$oImg = new WYImage($oImgURL);
            $oImg->setAttribute("alt", WYTS("LoopUpButton"));
				$oLink->setInnerHTML($oImg->sDisplay());
				echo $oLink->sDisplay();

				$goApp->setActionInQuery($dEditQuery, WY_QV_LOOP_DOWN);
				$oURL->setQuery($dEditQuery);
				$oLink = new WYLink($oURL, WYTS("LoopDownButton"));
				$oImgURL->removeLastComponent();
				$oImgURL->addComponent("down-button.gif");
				$oImg = new WYImage($oImgURL);
            $oImg->setAttribute("alt", WYTS("LoopDownButton"));
				$oLink->setInnerHTML($oImg->sDisplay());
				echo $oLink->sDisplay();
			}
		}
	}

	function loopEnd()
	{
		$this->iElementsLeft--;
	}
	
	function dispatchEditAction()
	{
		global $goApp;
		$sAction = $goApp->sCurrentAction();
		$sFieldName = $goApp->sFormFieldValue(WY_QK_EDITOR_FIELDNAME, "");
		$iLoopID = (int)$goApp->sFormFieldValue(WY_QK_LOOP_ID, 0);
		$aLoopIDs = $this->_aLoopIDs();
		$iCount = count($aLoopIDs);
		$iNewID = 0;
		$iPos = 0;
		$bChanged = false;
	
		if ($sFieldName != $this->sName) return;
		
		if ($sAction == WY_QV_LOOP_ADD) {
			if ($iCount) {
				$iNewID = max($aLoopIDs) + 1;
				$iPos = array_search($iLoopID, $aLoopIDs);
				if ($iPos !== false) { // should be in there, but who knows...
					$iPos++;
					if ($iPos >= $iCount) $aLoopIDs[] = $iNewID;
					else webyep_array_insert($aLoopIDs, $iPos, $iNewID); 
				}
				else $aLoopIDs[] = $iNewID;
			}
			else $aLoopIDs = array(1);
			$bChanged = true;
		}
		else if ($sAction == WY_QV_LOOP_REMOVE) {
			if ($iCount > 1) {
				$iPos = array_search($iLoopID, $aLoopIDs);
				if ($iPos !== false) array_splice($aLoopIDs, $iPos, 1);
				$bChanged = true;
			}
		}
		else if ($sAction == WY_QV_LOOP_UP) {
			$iPos = array_search($iLoopID, $aLoopIDs);
			if ($iCount > 1 && $iPos !== false && $iPos > 0) {
				array_splice($aLoopIDs, $iPos, 1);
				webyep_array_insert($aLoopIDs, $iPos - 1, $iLoopID); 
				$bChanged = true;
			}
		}
		else if ($sAction == WY_QV_LOOP_DOWN) {
			$iPos = array_search($iLoopID, $aLoopIDs);
			if ($iCount > 1 && $iPos !== false && $iPos < ($iCount - 1)) {
				array_splice($aLoopIDs, $iPos, 1);
				webyep_array_insert($aLoopIDs, $iPos + 1, $iLoopID); 
				$bChanged = true;
			}
		}
		if ($bChanged) {
			$this->_setLoopIDs($aLoopIDs);
			$this->save();
		}
	}

	function sDisplay()
	{
		return "";
	}
}
?>