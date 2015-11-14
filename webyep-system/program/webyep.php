<?php
// WebYep
// (C) Objective Development Software GmbH
// http://www.obdev.at

// setup php
set_magic_quotes_runtime(false);
error_reporting(0);
ini_set("display_errors", "Off");

$webyep_bDebug = false;
$webyep_bWL = false;

function webyep_sConfigValue($sVN)
{
	return $GLOBALS[$sVN];
}

// in case config can't be found
$webyep_sAdminName = (string)mt_rand(10000, 99999);
$webyep_sAdminPassword = (string)mt_rand(10000, 99999);

if (isset($_GET['webyep_sIncludePath']) || isset($_POST['webyep_sIncludePath']) || isset($_COOKIE['webyep_sIncludePath']) || isset($_SESSION['webyep_sIncludePath'])) exit(-1);
if (strpos($webyep_sIncludePath, ":") !== false) exit(-1);
if (!file_exists(webyep_sConfigValue("webyep_sIncludePath"))) exit(-1);
include_once(webyep_sConfigValue("webyep_sIncludePath") . "/lib/foundation.php");

$webyep_dTesting = (isset($_SERVER['HTTP_HOST']) && webyep_str_murks($_SERVER['HTTP_HOST'])=="jrolrc.ubzr");
// if ($webyep_dTesting) $webyep_bDebug = true;

$sConfigFilePath = webyep_sConfigValue("webyep_sIncludePath") . "/../config-inc.php";
if (!@include_once($sConfigFilePath)) {
	$sConfigFilePath = webyep_sConfigValue("webyep_sIncludePath") . "/../konfiguration.php";
	if (!@include_once($sConfigFilePath))
		$goApp->log("could not find config file");
}

// if ($webyep_dTesting) $webyep_sCharset = "iso-8859-2";
// if ($webyep_dTesting) $webyep_iForcedLanguageID = 0;

if ($webyep_bDebug) {
   error_reporting(E_ALL & ~E_NOTICE);
   ini_set("display_errors", "On");
}

include_once(webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYApplication.php");
include_once(webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYLanguage.php");

include_once(webyep_sConfigValue("webyep_sIncludePath") . "/lib/WYHTMLEntities.php");

// backward compatibility with 1.0.x
if ( isset($webyep_sAdminPasswort) && !isset($webyep_sAdminPassword) ) {
	$webyep_sAdminPassword = $webyep_sAdminPasswort;
}

$webyep_sVersionPostfix = "";

$_wy_sIncludePath = webyep_sConfigValue("webyep_sIncludePath");
include_once("$_wy_sIncludePath/elements/WYShortTextElement.php");
include_once("$_wy_sIncludePath/elements/WYLongTextElement.php");
include_once("$_wy_sIncludePath/elements/WYImageElement.php");
include_once("$_wy_sIncludePath/elements/WYMenuElement.php");
include_once("$_wy_sIncludePath/elements/WYLoopElement.php");
include_once("$_wy_sIncludePath/elements/WYLogonButtonElement.php");
include_once("$_wy_sIncludePath/elements/WYAttachmentElement.php");

if (file_exists("$_wy_sIncludePath/elements/WYRichTextElement.php")) include_once("$_wy_sIncludePath/elements/WYRichTextElement.php");
if (file_exists("$_wy_sIncludePath/elements/WYGalleryElement.php")) include_once("$_wy_sIncludePath/elements/WYGalleryElement.php");
if (file_exists("$_wy_sIncludePath/elements/WYAudioElement.php")) include_once("$_wy_sIncludePath/elements/WYAudioElement.php");
if (file_exists("$_wy_sIncludePath/elements/WYGuestbookElement.php")) include_once("$_wy_sIncludePath/elements/WYGuestbookElement.php");

// public API

function webyep_bIsEditMode()
{
	global $goApp;

	return $goApp->bEditMode;
}

// ----------

if (!function_exists("version_compare") || version_compare(PHP_VERSION, "4.2.0") < 0) {
	list($fUSec, $fSec) = explode(' ', microtime());
	mt_srand($fSec + ((float)$fUSec * 100000));
}

if (!headers_sent()) {
	if ($goApp->bShouldAvoidCaching()) {
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
	}
	else {
		header("Last-Modified: " . gmdate("D, d M Y H:i:s", filemtime($goApp->oDataPath->sPath)) . " GMT");
	}
}

// headers should not be sent at this point
if (headers_sent() && !ereg("webyep-system/.*/notice.php", $_SERVER['PHP_SELF']) && !ereg("webyep-system/.*/logon.php", $_SERVER['PHP_SELF'])) {
	echo $goApp->sNoticeWindowJS("HeaderProblemTitle", "HeaderProblemMessage");
}
$goApp->outputWarningPanels();
if ($goApp->bEditMode && !$goApp->bAuthCheck() && !ereg("webyep-system/.*/logon.php", $_SERVER['PHP_SELF'])) {
	echo "<script language='JavaScript' type='text/javascript'>\n";
	echo "   " . $goApp->sAuthWindowJS();
	echo "</script>\n";
	$goApp->bEditMode = false;
}

if ($webyep_bShowLogoNotice) {
   echo $goApp->sNoticeWindowJS("LogoProblemTitle", "LogoProblemMessage");
}

if ($webyep_bDocumentPage) {
   // LightBox ---------------------------
   $webyep_bUseLightBox = false;
   $sLightBoxSub = "opt/lightbox";
   $oLightBoxPath = od_clone($goApp->oProgramPath);
   $oLightBoxPath->addComponent($sLightBoxSub);
   if (file_exists($oLightBoxPath->sPath)) {
      $webyep_bUseLightBox = true;
      $oLightBoxURL = od_clone($goApp->oProgramURL);
      $oLightBoxURL->addComponent($sLightBoxSub);
      $sLightBoxURL = $oLightBoxURL->sURL();
      echo "<script language='JavaScript'>\n";
      echo "   window.WebYep_LightBoxPath = '$sLightBoxURL/';\n";
      printf("   window.WebYep_LightBoxLang = '%s';\n", $webyep_iLanguageID == WYLANG_ENGLISH ? "en":"de");
      echo "</script>\n";
      echo "<script type='text/javascript' src='$sLightBoxURL/js/prototype.js'></script>\n";
      echo "<script type='text/javascript' src='$sLightBoxURL/js/scriptaculous.js?load=effects'></script>\n";
      echo "<script type='text/javascript' src='$sLightBoxURL/js/lightbox.js'></script>\n";
      echo "<link rel='stylesheet' href='$sLightBoxURL/css/lightbox.css' type='text/css' media='screen' />\n";
   }
   // ------------------------------------
}

?>
