<?php // ><!--
$webyep_sIncludePath = "./";
$iDepth = 0;
while (!file_exists($webyep_sIncludePath . "webyep-system")) {
	$iDepth++;
	if ($iDepth > 10) {
		error_log("webyep-system folder not found.", 0);
      break;
	}
	$webyep_sIncludePath = ($webyep_sIncludePath == "./") ? ("../"):("$webyep_sIncludePath../");
}
if (file_exists("$webyep_sIncludePath/webyep-system/programm")) $webyep_sIncludePath .= "webyep-system/programm";
else $webyep_sIncludePath .= "webyep-system/program";
$sMain = "$webyep_sIncludePath/webyep.php";
if (file_exists($sMain)) include($sMain);
// --> <dummy ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>our tales</title>
  <link rel="stylesheet"
        type="text/css"
        media="screen"
        href="../rw_common/themes/usine/styles.css" />
  <link rel="stylesheet"
        type="text/css"
        media="screen"
        href="../rw_common/themes/usine/greybox/gb_styles.css" />
  <link rel="stylesheet"
        type="text/css"
        media="print"
        href="../rw_common/themes/usine/print.css" />
  <link rel="stylesheet"
        type="text/css"
        media="screen"
        href=
        "../rw_common/themes/usine/css/background_image/custom3.css" />
        
  <link rel="stylesheet"
        type="text/css"
        media="screen"
        href=
        "../rw_common/themes/usine/css/header/font_size/small.css" />
        
  <link rel="stylesheet"
        type="text/css"
        media="screen"
        href=
        "../rw_common/themes/usine/css/navbar/font_size/small.css" />
        
  <link rel="stylesheet"
        type="text/css"
        media="screen"
        href=
        "../rw_common/themes/usine/css/fontfamily/century.css" />
  <link rel="stylesheet"
        type="text/css"
        media="screen"
        href="../rw_common/themes/usine/css/breadcrumb/off.css" />
  <link rel="stylesheet"
        type="text/css"
        media="screen"
        href="../rw_common/themes/usine/css/logo/right.css" />
  <link rel="stylesheet"
        type="text/css"
        media="screen"
        href="../rw_common/themes/usine/css/shadow/off.css" />
  <link rel="stylesheet"
        type="text/css"
        media="screen"
        href=
        "../rw_common/themes/usine/css/sidebar/transparency/100.css" />
        
  <link rel="stylesheet"
        type="text/css"
        media="screen"
        href=
        "../rw_common/themes/usine/css/content/transparency/90.css" />
        
  <link rel="stylesheet"
        type="text/css"
        media="screen"
        href=
        "../rw_common/themes/usine/css/navbar/transparency/90.css" />
        
  <link rel="stylesheet"
        type="text/css"
        media="screen"
        href=
        "../rw_common/themes/usine/css/sidebar/position/s_hide_n_left.css" />
  <link rel="stylesheet"
        type="text/css"
        media="screen"
        href=
        "../rw_common/themes/usine/css/image_size/noresize.css" />
  <link rel="stylesheet"
        type="text/css"
        media="screen"
        href=
        "../rw_common/themes/usine/css/navbar/border/on.css" />
  <link rel="stylesheet"
        type="text/css"
        media="screen"
        href="../rw_common/themes/usine/css/defaults/none.css" />
  <link rel="stylesheet"
        type="text/css"
        media="screen"
        href=
        "../rw_common/themes/usine/colourtag-smoothblue_afghan.css" />
        
<script type="text/javascript"
      src="../rw_common/themes/usine/javascript.js">
</script>
<script type="text/javascript">
//<![CDATA[
                        var tmp_image_path="../rw_common/themes/usine/greybox/next.gif";
                        var tmp_path_array=tmp_image_path.split("/");
                        tmp_path_array.pop();
                        var gb_path=tmp_path_array.join("/");
                        var gb_path = gb_path+"/";
                        var GB_ROOT_DIR=gb_path;
//]]>
</script>
<script type="text/javascript"
      src="../rw_common/themes/usine/greybox/AJS.js">
</script>
<script type="text/javascript"
      src="../rw_common/themes/usine/greybox/AJS_fx.js">
</script>
<script type="text/javascript"
      src="../rw_common/themes/usine/greybox/gb_scripts.js">
</script>
  <meta http-equiv="content-type"
        content="text/html; charset=utf-8" />
  <meta name="robots"
        content="all" /><!--[if IE 7]>
                        
                        <link rel="stylesheet" type="text/css" media="all" href="../rw_common/themes/usine/css/ie7fix.css"  />
                <![endif]-->
  <!--[if lt IE 7]>
                <link rel="stylesheet" type="text/css" media="all" href="../rw_common/themes/usine/css/ie6fix.css"  />
                <script defer type="text/javascript" src="../rw_common/themes/usine/pngfix.js"></script>
                <![endif]-->
  <!-- This page was created with the usine 3.0.3 theme by Rapid Ideas - http://www.rapid-ideas.com -->
  <!-- Theme Name: Usine, Version: 3.0.3 -->
</head>

<body>
  <div id="container">
    <div id="background_sidebar_right"></div>

    <div id="background_content"></div>

    <div id="background_sidebar_left"></div>

    <div id="leftbar">
      <div id="logo_left"></div>

      <div class="sideHeader"></div>

      <div class="sidebar"></div>

      <div class="sidebar"></div>
    </div>

    <div id="sidebarContainer">
      <div id="logo_right"></div>

      <div id="navcontainer">
        <ul>
          <li><a href="../index.html"
             rel="self">home</a></li>

          <li><a href="../ourmission/ourmission.php"
             rel="self">our mission</a></li>

          <li><a href="../donate/donate.php"
             rel="self">donate</a></li>

          <li><a href="ourTales.php"
             rel="self"
             id="current"
             name="current">our tales</a></li>

          <li><a href="../adopt/adopt.php"
             rel="self">adopt</a></li>

          <li><a href="../familyphotos/familyphotos.php"
             rel="self">family photos</a></li>
        </ul>
      </div>
    </div>

    <div id="contentContainer">
      <div id="pageHeader">
        <h1>Afghan Stray Animal League</h1>

        <h2>Helping cats, dogs &amp; other small animals in
        Kabul</h2>
      </div>

      <div id="breadcrumbcontainer">
        <ul>
          <li><a href="../index.html">home</a>::</li>

          <li><a href="ourTales.php">our tales</a>::</li>
        </ul>
      </div>

      <div id="content">
        <!-- Mode: publish -->
        <?php
                $iLeftPhotoPadding = 10;
                $iRightPhotoPadding = 10;
                $iLeftPhotoWidth = true ? 200:0;
                $iRightPhotoWidth = true ? 200:0;
                $iCenterPhotoPadding = 10;
                $iCenterPhotoWidth = false ? 300:0;
                $iBlockPadding = 20;

           if (!function_exists("bCheckImage")) {
              function bCheckImage($sN, $iFixedW, &$sWCSS, &$sWATT)
              {
                 $bRet = false;

                 $sWCSS = $sWATT = "";
                 $oElement = new WYImageElement($sN, $false, $iFixedW ? "width=\"$iFixedW\"":"", "", "");
                 $sContent = $oElement->sDisplay();
                 if ($sContent) {
                    if ($iFixedW) $iW = $iFixedW;
                    else {
                       $oImg = $oElement->oImage();
                       $iW = $oImg ? $oImg->iWidth():0;
                    }
                    $sWCSS = $iW ? (" width: " . $iW . "px;"):"";
                    $sWATT = $iFixedW ? "width=\"$iFixedW\"":"";
                    $bRet = true;
                 }
                 return $bRet;
              }
           }
                
                $sWidthCSS = "";
        ?>
        <!-- Loop Start: ======================================== -->
        <?php foreach (WYLoopElement::aLoopIDs("BlockLoop") as $webyep_oCurrentLoop->iLoopID) { $webyep_oCurrentLoop->loopStart(true); ?><!-- Float Container Start: ======================================== -->

        <div style="overflow: hidden;">
          <?php if (webyep_sShortTextContent("BlockHeading", false) || webyep_bIsEditMode()) { ?>

          <h4 class="WebYepBlockHeading">
          <?php webyep_shortText("BlockHeading", false); ?></h4><?php } ?><?php
                  if (bCheckImage("LeftPhoto", $iLeftPhotoWidth, $sWidthCSS, $sWidthAtt) || webyep_bIsEditMode()) {
          ?>
          <!-- Left Photo: ======================================== -->
          <?php
             printf('<div style="float: left; margin-right: %dpx; margin-bottom: %dpx;%s" class="WebYepLeftPhoto"><div>', $iLeftPhotoPadding, $iLeftPhotoPadding, $sWidthCSS);
             webyep_image("LeftPhoto", false, $sWidthAtt);
             echo "</div>";
                  if (webyep_sShortTextContent("LeftPhotoCaption", false) || webyep_bIsEditMode()) {
          ?>

          <div style="padding-top: 4px; text-align: center;">
            <?php webyep_shortText("LeftPhotoCaption", false); ?>
          </div><?php } echo "</div>"; } ?><?php
                  if (bCheckImage("RightPhoto", $iRightPhotoWidth, $sWidthCSS, $sWidthAtt) || webyep_bIsEditMode()) {
          ?>
          <!-- Right Photo: ======================================= -->
          <?php
             printf('<div style="float: right; margin-left: %dpx; margin-bottom: %dpx;%s" class="WebYepRightPhoto"><div>', $iRightPhotoPadding, $iRightPhotoPadding, $sWidthCSS);
             webyep_image("RightPhoto", false, $sWidthAtt);
             echo "</div>";
                  if (webyep_sShortTextContent("RightPhotoCaption", false) || webyep_bIsEditMode()) {
          ?>

          <div style="padding-top: 4px; text-align: center;">
            <?php webyep_shortText("RightPhotoCaption", false); ?>
          </div><?php } echo "</div>"; } ?>
          <!-- Text: =============================================== -->

          <div class="WebYepText">
            <?php webyep_longText("Text", false, "", true); ?>
          </div>
          <!-- Float Container End: =============================================== -->
          <?php
              if(preg_match('/MSIE +6/', $_SERVER['HTTP_USER_AGENT'])) echo "<br clear=\"all\" />\n";
          ?>
        </div>
        <!-- Block Padding: ====================================== -->
        <?php if ($iBlockPadding) printf('<div style="font-size: 0px; line-height: 0px; height: %spx;" class="WebYepBlockPadding"></div>', $iBlockPadding); ?><!-- Loop End: =========================================== -->
        <?php $webyep_oCurrentLoop->loopEnd(); } ?>

        <div>
          <?php webyep_logonButton(true); ?>
        </div>
      </div>
    </div><!-- End Main content -->
  </div>

  <div id="footer"
       style="clear: both;"></div>
</body>
</html>
