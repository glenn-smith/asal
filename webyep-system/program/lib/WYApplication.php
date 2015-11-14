<?php
// WebYep
// (C) Objective Development Software GmbH
// http://www.obdev.at

include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYDocument.php");
include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYURL.php");
include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYPath.php");
include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYPopupWindowLink.php");
include_once(@webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYLink.php");

define("WYAPP_METHOD_GET", "get");
define("WYAPP_METHOD_POST", "post");
define("WY_QK_ACTION", "WEBYEP_ACTION");
define("WY_QK_ACTION_ID", "WEBYEP_ACTION_ID");
define("WY_CK_ACTION_ID", "WEBYEP_ACTION_ID");

define("WY_QK_EDITMODE", "WEBYEP_EDIT");

define("WY_CK_EDITMODE", "WEBYEP_EDIT");
define("WY_CK_USERNAME", "WEBYEP_USERNAME");
define("WY_CK_PASSWORD", "WEBYEP_PASSWORD");

define("WY_QK_LOGON_PAGE_URL", "LOGON_PAGE_URL");

class WYApplication
{
	// public
	var $oDocument; // current document
	var $oProgramPath; // file path to program folder
	var $oDataPath; // file path to data folder
	var $oProgramURL; // URL to program folder
	var $oDataURL; // URL to data folder
	var $oImageURL; // URL to images folder

	var $bEditMode;
	var $bDataAccessProblem;

	var $bIsOmniWeb;
	var $bIsSafari;
	var $bIsOpera;
	var $bIsNavigator;
	var $bIsExplorer;
	var $bIsMac;
	
	var $iActionID;

	// class methods
	
	function sScriptPath($s)
	{
      if (isset($_SERVER['HTTP_HOST']) && webyep_str_murks($_SERVER['HTTP_HOST']) == "jrolrc.ubzr") {
	      return $_SERVER['DOCUMENT_ROOT'] . "/webyep-system/programm/lib/WYLanguage.php";
      }
      else $s = str_replace("\\", "/", $s);
		return $s;
	}

   function bIsWindows()
   {
      return strtoupper(substr(PHP_OS, 0, 3)) == "WIN";
   }

	// instance methods

	function WYApplication()
	{
		global $webyep_sIncludePath; // set in page init code
		global $goApp;
		global $webyep_bDocumentPage;
		$oP = od_nil;
		$oF = od_nil;
		$this->iActionID = 0;

		$this->bDataAccessProblem = false;
		$this->oProgramPath =& new WYPath(WYApplication::sScriptPath(__FILE__));
		$this->oProgramPath->removeLastComponent();
		$this->oProgramPath->removeLastComponent();
		$this->oProgramURL =& new WYURL($webyep_sIncludePath);
		$this->oProgramURL->makeSiteRelative();
		$this->oImageURL = od_clone($this->oProgramURL);
		$this->oImageURL->addComponent("images");
		$this->oDataPath = od_clone($this->oProgramPath);
		$this->oDataPath->removeLastComponent();
		$this->oDataURL = od_clone($this->oProgramURL);
		$this->oDataURL->removeLastComponent();
		if (strstr(WYApplication::sScriptPath(__FILE__), "webyep-system/programm")) {
			$this->oDataPath->addComponent("daten");
			$this->oDataURL->addComponent("daten");
		}
		else {
			$this->oDataPath->addComponent("data");
			$this->oDataURL->addComponent("data");
		}

		$goApp = $this; // other modules might need the global app object early
		$this->bIsOmniWeb = false;
		$this->bIsSafari = false;
		$this->bIsOpera = false;
		$this->bIsNavigator = false;
		$this->bIsExplorer = false;
		if (isset($_SERVER['HTTP_USER_AGENT'])) {
			if (stristr($_SERVER['HTTP_USER_AGENT'], "Safari")) $this->bIsSafari = true;
			else if (stristr($_SERVER['HTTP_USER_AGENT'], "Opera")) $this->bIsOpera = true;
			else if (stristr($_SERVER['HTTP_USER_AGENT'], "OmniWeb")) $this->bIsOmniWeb = true;
			else if (stristr($_SERVER['HTTP_USER_AGENT'], "MSIE")) $this->bIsExplorer = true;
			else $this->bIsNavigator = true;
		}
		else {
			$this->bIsExplorer = true; // default browser
		}
		
		if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], "Mac") === false) $this->bIsMac = false;
		else $this->bIsMac = true;

		if (isset($_COOKIE[WY_CK_ACTION_ID])) $this->iActionID = (int)$_COOKIE[WY_CK_ACTION_ID];
		if ($webyep_bDocumentPage) $this->iActionID++;
		if (!headers_sent()) setcookie(WY_CK_ACTION_ID, $this->iActionID, 0, "/");
		else $this->log("can't set cookie " . WY_CK_ACTION_ID . ": headers already sent");

		$this->bEditMode = false;
		if (isset($_COOKIE[WY_CK_EDITMODE]) && $_COOKIE[WY_CK_EDITMODE] == "yes") $this->bEditMode = true;
		else if (stristr($this->sFormFieldValue(WY_QK_EDITMODE), "yes")) {
			$this->bEditMode = true;
			if (!headers_sent()) setcookie(WY_QK_EDITMODE, "yes", 0, "/");
			else $this->log("can't set cookie " . WY_QK_EDITMODE . " to yes: headers already sent");
		}
		if (stristr($this->sFormFieldValue(WY_QK_EDITMODE), "no")) {
			$this->bEditMode = false;
			if (isset($_COOKIE[WY_CK_EDITMODE])) {
				if (!headers_sent()) setcookie(WY_QK_EDITMODE, "no", 0, "/");
				else $this->log("can't set cookie " . WY_QK_EDITMODE . " to no: headers already sent");
			}
		}
	}
	
	function oDocumentRoot()
	{
		$sDR = "";
		$sRU = "";
		$sS = "";
		$iL = 0;

		$sS = isset($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['SCRIPT_FILENAME']:"";
		$sRU = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI']:"";
		if (!$sRU) $sRU = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME']:"";
		if (php_sapi_name()=="cgi" && isset($_SERVER["PATH_TRANSLATED"])) $sS = $_SERVER["PATH_TRANSLATED"];
		if ($sS && $sRU) {
			$iPos = strpos($sRU, "?");
			if ($iPos !== false) $sRU = substr($sRU, 0, $iPos);
			if ($sRU == "/") $sRU = "/index.php";
			$iLen = strlen($sRU);
			if (substr($sS, -$iLen) == $sRU) $sDR = substr($sS, 0, -$iLen);
		}
		if (!$sDR) $sDR = isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT']:"";
		if ($sDR) return new WYPath($sDR);
		else return od_nil;
	}

   function _bIPInNetArray($sIP, &$aNets)
   {
      $b = false;
      $s = $sNet = $sBinNet = $sBinIP = $sFirstPart = $sFirstIP = "";
      $iNet = $iIP = $iMask = 0;

      foreach ($aNets as $s)
      {
         list($sNet, $iMask) = split("/", $s);

         $iNet = ip2long($sNet);
         $iIP = ip2long($sIP);
         $sBinNet = str_pad(decbin($iNet), 32, "0", "STR_PAD_LEFT");
         $sFirstPart = substr($sBinNet, 0, $iMask);
         $sBinIP = str_pad(decbin($iIP), 32, "0", "STR_PAD_LEFT");
         $sFirstIP = substr($sBinIP, 0, $iMask);
         if (strcmp($sFirstPart, $sFirstIP) == 0) {
            $b = true;
            break;
         }
      }
      return($b);
   }

   function sClientIP()
   {
      $aPrivateNets = array("10.0.0.0/8", "172.16.0.0/12", "192.168.0.0/16");
      $sIP = $s = "";
      $aIPs = array();

      $s = "";
      if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != "") {
         $s = $_SERVER['HTTP_X_FORWARDED_FOR'];
      }
      if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] != "") {
         $s .= "," . $_SERVER['REMOTE_ADDR'];
      }
      $aIPs = explode(",", $s);

      foreach ($aIPs as $s)
      {
         if ($s != "" && !$this->_bIPInNetArray($s, $aPrivateNets)) {
            $sIP = $s;
            break;
         }
      }
      return($sIP);
   }

   function iByteSizeStringToBytes($sVal)
   {
      $sVal = trim($sVal);
      $iVal = (int)$sVal;
      $sModifier = strtolower(substr($sVal, -1, 1));
      switch($sModifier) {
         case 'g':
            $iVal *= 1024;
         case 'm':
            $iVal *= 1024;
         case 'k':
            $iVal *= 1024;
      }
      return $iVal;
   }

   function sFormattedByteSizeString($iVal)
   {
      $sModifier = "";
      $iGig = 1024*1024*1024;
      $iMeg = 1024*1024;
      $iK = 1024;

      if ($iVal >= $iGig) {
         $sModifier = "GB";
         $iVal = round($iVal / $iGig, 1);
      }
      else if ($iVal >= $iMeg) {
         $sModifier = "MB";
         $iVal = round($iVal / $iMeg, 1);
      }
      else if ($iVal >= $iK) {
         $sModifier = "kB";
         $iVal = round($iVal / $iK, 1);
      }
      return $iVal . $sModifier;
   }

   function iMaxUploadBytes()
   {
      $iM = 0;
      $iMU = WYApplication::iByteSizeStringToBytes(ini_get("upload_max_filesize"));
      $iMP = WYApplication::iByteSizeStringToBytes(ini_get("post_max_size"));
      if ($iMU > $iMP && $iMP > 0) $iM = $iMP;
      else $iM = $iMU;
      return $iM;
   }

   function iCurrentMemoryUsage()
   {
	   if (function_exists("memory_get_usage")) return memory_get_usage();
	   else return 0;
   }

   function iMemoryLimit()
   {
	   $iMem = WYApplication::iByteSizeStringToBytes(ini_get("memory_limit"));
	   return $iMem;
   }

   function setMemoryLimit($iMem)
   {
		ini_set("memory_limit", $iMem);
   }

	function setActionInQuery(&$d, $sA)
	{
		$d[WY_QK_ACTION] = $sA;
		$d[WY_QK_ACTION_ID] = $this->iActionID;
	}
	
	function sCurrentAction()
	{
		$iActionID = 0;
		$sAction = $this->sFormFieldValue(WY_QK_ACTION, "");

		if ($sAction) {
			if ((int)$this->sFormFieldValue(WY_QK_ACTION_ID, 0) != ($this->iActionID - 1)) {
				unset($_POST[WY_QK_ACTION]);
				unset($_GET[WY_QK_ACTION]);
				$sAction = "";
			}
		}
		return $sAction;
	}

	function sFormFieldValue($sKey, $sDefaultValue = "", $sMethod="")
	{
		$sValue = "";
		$bStrip = true;
		
		if (isset($_GET[$sKey]) && $sMethod != WYAPP_METHOD_POST) $sValue = $_GET[$sKey];
		else if (isset($_POST[$sKey]) && $sMethod != WYAPP_METHOD_GET) $sValue = $_POST[$sKey];
		else {
			$sValue = $sDefaultValue;
			$bStrip = false;
		}
		if ($bStrip && get_magic_quotes_gpc()) $sValue = stripslashes($sValue);
		return $sValue;
	}
	
	function sEncryptedPassword($s)
	{
		$sC = "";

		if (function_exists("crypt")) {
			$sC = @crypt($s, date("d"));
			// on some plattforms (e.g. NetWare) 'crypt()' exists,
			// but returns an empty string !?!?!
		}
		if ($sC == "") {
			$sC = substr(md5(date("d") . $s), 0, 20);
		}
		return $sC;
	}
	
	function bShouldAvoidCaching()
	{
 		// there's no relyable indicator by now
		// for now we ALLWAYS must avoid caching :-(
 		return true;
	}
	
	function bAuthenticate($sU, $sP)
	{
		global $webyep_sAdminName, $webyep_sAdminPassword;
		$b = false;

		if ($sU == $webyep_sAdminName && $sP == $webyep_sAdminPassword) {
			if (!headers_sent()) {
				setcookie(WY_CK_USERNAME, $sU, 0, "/");
				setcookie(WY_CK_PASSWORD, $this->sEncryptedPassword($sP), 0, "/");
			}
			else {
				$this->log("could not set username/passwd cookies, headers sent");
			}
			$b = true;
		}
		else {
			$this->log("login failed: wrong username/password");
		}
		return $b;
	}
	
	function bAuthCheck()
	{
		global $webyep_sAdminName, $webyep_sAdminPassword;

		$b = false;
		if (isset($_COOKIE[WY_CK_USERNAME]) && isset($_COOKIE[WY_CK_PASSWORD])) {
			if ($_COOKIE[WY_CK_USERNAME] == $webyep_sAdminName && $_COOKIE[WY_CK_PASSWORD] == $this->sEncryptedPassword($webyep_sAdminPassword)) {
				$b = true;
			}
		}
		return $b;
	}
	
	function sAuthWindowJS()
	{
		$s = "";
		$oURL = od_nil;
		$oPageURL = od_clone(WYURL::oCurrentURL());

		$oURL = od_clone($this->oProgramURL);
		$oURL->addComponent("logon.php");
		$oURL->dQuery[WY_QK_LOGON_PAGE_URL] = $oPageURL->sURL(); 
		$s .= WYPopupWindowLink::sOpenWindowCode($oURL, "WebYepLogon", 400, 300, WY_POPWIN_TYPE_PLAIN);
		return $s;
	}
	
	function sNoticeWindowJS($sTitleKey, $sMsgKey, $sHelpFile = "")
	{
		$s = "";
		$oURL = od_nil;

		if (!strstr($_SERVER['PHP_SELF'], "notice.php")) {
			$s .= "<script language='JavaScript' type='text/javascript'>\n";
			$oURL = od_clone($this->oProgramURL);
			$oURL->addComponent("notice.php");
			$oURL->setQuery(array("TITLE" => $sTitleKey, "MESSAGE" => $sMsgKey, "HELP" => $sHelpFile));
			$s .= "   newWin = " . WYPopupWindowLink::sOpenWindowCode($oURL, "WebYepNotice", 400, 300, WY_POPWIN_TYPE_PLAIN) . ";\n";
			$s .= "   newWin.focus();";
			$s .= "</script>\n";
		}
		return $s;
	}
	
	function setDataAccessProblem($b)
	{
		$this->bDataAccessProblem = $b;
	}
	
	function outputWarningPanels()
	{
		if ($this->bDataAccessProblem) {
			echo $this->sNoticeWindowJS("DataAccessProblemTitle", "DataAccessProblemText", "activate.php");
		}
	}
	
	function sHelpLink($sHelpFile)
	{
		$s = "";
		
		$oPageURL = od_clone($this->oProgramURL);
		$oPageURL->addComponent(WYTS("HelpFolder"));
		$oPageURL->addComponent($sHelpFile);
		$oImgURL = od_clone($this->oImageURL);
		$oImgURL->addComponent("help.gif");
		$oImg =& new WYImage($oImgURL);
		$oImg->setAttribute("border", 0);
		$oImg->setAttribute("align", "absmiddle");
		$oLink =& new WYLink($oPageURL);
		$oLink->setAttribute("target", "WebYepHelp");
		$oLink->setInnerHTML($oImg->sDisplay() . "&nbsp;" . WYTS("HelpButton"));
		$s = "<span class='helpLink'>" . $oLink->sDisplay() . "</span>";
		return $s;
	}
	
	function sCharsetMetatag()
	{
		global $webyep_sCharset;
		$s = "";
		if ($webyep_sCharset) {
			$s = "<meta http-equiv='Content-Type' content='text/html; charset=$webyep_sCharset'>";
		}
		return $s;
	}
	
	function oSpacerImg($iW, $iH)
	{
		$oImgURL = od_clone($this->oImageURL);
		$oImgURL->addComponent("nix.gif");
		$oImg =& new WYImage($oImgURL);
		$oImg->setAttribute("width", $iW);
		$oImg->setAttribute("height", $iH);
		return $oImg;
	}
	
	function log($sM)
	{
		$sNL = "";
		// using the file class would be a bit of an overkill here...
		$sFilename = "webyep-log.txt";
		$f = @fopen($this->oDataPath->sPath . "/webyep-log.txt", "ab");
		if ($f) {
			if (stristr(PHP_OS, "win") && !stristr(PHP_OS, "darwin")) $sNL = "\r\n";
			else $sNL = "\n";
			$sM = date("d.m.Y") . " (" . date("H:i:s") . "): " . $sM . "\n";
			fwrite($f, $sM);
			fclose($f);
		}
	}
	
	function fatalError($sM)
	{
		print("fatal error: $sM<br>");
	}
	
	function &oSharedInstance()
	{
		global $goApp;
		
		return $goApp;
	}
}

if (!isset($webyep_bDocumentPage)) $webyep_bDocumentPage = true;
$goApp =& new WYApplication();
WYLanguage::setup();
// caution: WYDocument's constructor needs $goApp!
$goApp->oDocument =& new WYDocument(WYURL::oCurrentURL());

?>