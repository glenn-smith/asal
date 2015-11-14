<?php
// WebYep
// (C) Objective Development Software GmbH
// http://www.obdev.at

	$webyep_bDocumentPage = false;
	$webyep_sIncludePath = "..";
	include_once("$webyep_sIncludePath/webyep.php");

   $oEditorsFolder = od_clone($goApp->oProgramPath);
   $oEditorsFolder->addComponent("editors");

   $oP = od_clone($goApp->oProgramPath);
   $oP->addComponent("opt");
   $oP->addComponent("tinymce");
   if ($oP->bExists()) {
      $oEditorsFolder->addComponent("rich-text-tinymce.php");
	   include_once($oEditorsFolder->sPath);
   }
   else {
      unset($oP);
      $oP = od_clone($goApp->oProgramPath);
      $oP->addComponent("opt");
      $oP->addComponent("rte");
      if ($oP->bExists()) {
         $oEditorsFolder->addComponent("rich-text-rte.php");
         include_once($oEditorsFolder->sPath);
      }
      else {
	      unset($oP);
	      $oP = od_clone($goApp->oProgramPath);
	      $oP->addComponent("opt");
	      $oP->addComponent("fckeditor");
	      if ($oP->bExists()) {
	         $oEditorsFolder->addComponent("rich-text-fckeditor.php");
	         include_once($oEditorsFolder->sPath);
	      }
         else {
            $oEditorsFolder->addComponent("rich-text-plain.php");
            include_once($oEditorsFolder->sPath);
         }
      }
   }

?>