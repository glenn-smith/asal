<?php
// WebYep
// (C) Objective Development Software GmbH
// http://www.obdev.at

// setup conversion tables for HTML entities
$oApp =& WYApplication::oSharedInstance();
$oP = od_clone($oApp->oProgramPath);
$oP->addComponent("html-tables.dat");
$oF =& new WYFile($oP);
$s = $oF->sContent();
$sTableFrom = ";A(B)C[D]E{F}GH\"IJ\'K+L-M=N/O*PzQyRxSwTvUuVtWsXrYqZponmlkjihgfedcba\t \r\n";
$sTableTo = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ;()[]{}\"\'+-=/*\r\n\t ";
$s = strtr($s, $sTableFrom, $sTableTo); eval($s);
$wy_sHTMLTables = $s ? "":$s;

function webyep_sHTMLEntities($s, $bAll = true)
{
   global $webyep_sCharset;

   if ($webyep_sCharset == "") {
	   if (!$bAll) {
         $dTable = get_html_translation_table(HTML_ENTITIES);
         $dTable["<"] = "<";
         $dTable[">"] = ">";
         $dTable[chr(183)] = chr(183); // leave middot allone - needed for lists
         $s = strtr($s, $dTable);
	   }
	   else $s = htmlentities($s);
   }
   return $s;
}

?>