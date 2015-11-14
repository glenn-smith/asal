<?php
// WebYep
// (C) Objective Development Software GmbH
// http://www.obdev.at

class WYHTMLTag
{
	// instance vars
	// public
	var $dAttributes;
	var $sQuote;
	var $sTagName;
	var $bSingular;
	var $sInnerHTML;
	
	// class methods

	// instance methods
	function WYHTMLTag($s)
	{
		$this->dAttributes = array();
		$this->sQuote = "'";
		$this->bSingular = true;
		$this->sTagName = $s;
		$this->sInnerHTML = "";
	}
	
	function setAttribute($sKey, $sValue)
	// public API, subclasses access dAttributes directly!
	{
		$this->dAttributes[$sKey] = $sValue;
	}
	
	function removeAttribute($sKey)
	{
		if (isset($this->dAttributes[$sKey])) unset($this->dAttributes[$sKey]);
	}
	
	function sAttributeList()
	// leading space!!!
	{
		$s = "";
		$sKey = "";
		$sValue = "";
		$sQ = $this->sQuote;
		
		foreach ($this->dAttributes as $sKey => $sValue) {
			if ($sValue !== "") {
            $s .= " $sKey=$sQ$sValue$sQ";
         }
			else $s .= " $sKey";
		}
		return $s;
	}
	
	function setSingular($b)
	{
		$this->bSingular = $b;
	}
	
	function setInnerHTML($s)
	{
		$this->sInnerHTML = $s;
	}
	
	function sDisplay()
	{
		$s = "";
		
		$s = "<" . $this->sTagName . $this->sAttributeList();
		if (!$this->bSingular) {
			$s .= ">";
			$s .= $this->sInnerHTML;
			$s .= "</" . $this->sTagName;
		}
		else {
			$s .= " /";
		}
		$s .=  ">";
		return $s;
	}
}
?>