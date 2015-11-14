<?php
// WebYep
// (C) Objective Development Software GmbH
// http://www.obdev.at

define("od_nil", 0); // NULL object

function webyep_array_insert(&$array, $pos, $value) 
{
	$last = array_splice($array, $pos); 
	array_push($array, $value); 
	$array = array_merge($array, $last); 
}

function webyep_str_murks($s)
{
	return strtr($s, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ", "nopqrstuvwxyzabcdefghijklmNOPQRSTUVWXYZABCDEFGHIJKLM");
}

function webyep_sBackLink()
{
   $s = "";
   $s .= "<a href='javascript:window.history.back()'>";
   $s .= WYTS("BackLink");
   $s .= "</a>";
   return $s;
}

// Now it's getting weird: We need to have a od_clone() function in PHP4
// to be compatible with PHP5 - but PHP5 must not see this function def!!!
// Thanx to Steven Wittens for the hint!
if (ereg("^5", phpversion())) {
   eval('function od_clone($o) { return $o == od_nil ? od_nil:clone($o); }');
}
else {
   eval('function od_clone($o) { return $o; }');
}

// make sure "." is in include path - not needed anymore...
/*
$sIncSep = (substr(PHP_OS, 0, 3) == "WIN") ? ";":":";
$sInc = ini_get("include_path");
if ($sInc) {
	$aInc = explode($sIncSep, $sInc);
	if (!in_array(".", $aInc)) $aInc[] = ".";
}
else $aInc = array(".");
$sInc = implode($sIncSep, $aInc);
ini_set("include_path", $sInc);
*/

?>