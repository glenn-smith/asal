<?php
// WebYep
// (C) Objective Development Software GmbH
// http://www.obdev.at

   // Wichtig: In dieser Datei dürfen am Beginn und Ende keine Leerzeilen eingefügt werden!!!
   // Important: Do not insert ANYTHING before or after the PHP block in this file!!!


   // Name und Kennwort für den "Bearbeiten"-Modus.
   // Diese können von Ihnen frei gewählt werden und müssen von den
   // EditorInnen der WebSite beim Anmelden (Klick auf das Schloß-Symbol) angegeben
   // werden, um die Seiten bearbeiten zu dürfen.

   // Username and password for edit mode.
   // You can freely choose these and they must be entered by users after clicking
   // the lock icon to logon before being allowed to start editing.

   $webyep_sAdminName = "pamconstable";
   $webyep_sAdminPassword = "hailpoetry";



   // Wenn Sie spezielle Sonderzeichen in Ihren Seiten verwenden und daher
   // den metatag "Content-Type" mit einer charset-Angabe verwenden
   // können Sie hier das verwendete charset angeben.
   // (zB. "utf-8", oder "iso-8859-2" für osteuropäische Sprachen)
   // Dies hat aber auch zur Folge, dass WebYep Sonderzeichen in Texten
   // NICHT MEHR IN HTML-ENTITIES wandelt! Stattdessen wird WebYep den "Content-Type" metatag auch
   // in die eigenen Editor-Seiten einfügen.

   // If you've set a character set in you pages (with the "Content-Type" meta tag),
   // you should also set this character set here.
   // (e.g. "utf-8" or "iso-8859-2" for eastern european languages)
   // This will make sure, WebYep displays the special characters using this character set. But it will
   // also stop WebYep from converting texts to HTML entities! WebYep will instead insert the meta tag
   // also in each of it's editor pages.

   $webyep_sCharset = "utf-8";


   // In früheren Version wurden Menüs als Tabellen aufgebaut.
   // Sollten Sie dieses fühere Verhalten wiederherstellen wollen, setzen Sie diese
   // Variable auf true statt false.

   // In previous versions menu elements where rendered as tables.
   // If you need to reaktivate this previous behaviour
   // set this variable to true instead of false.

   $webyep_bUseTablesForMenus = false;


   // Wenn Sie eine Lizenz mit der Option erworben haben, WebYep als Ihr eigenes Produkt
   // auszugeben (white label version), dann können Sie hier den Namen des Produktes angeben.
   // (dieser erscheint in den Editoren, der Hilfe und den Hinweis-Fenstern)

   // If you optained a license with the white label option, you can change the product name
   // here (it will appear in the editor windows, the help and the notice windows)

   $webyep_sProductName = 'MyProduct';


   // Wenn Sie die deutsche Version von WebYep verwenden, müssen Sie hier nichts einstellen
   // If you're using the english version of WebYep, you do not need to change anything here
   // Da bi ste aktivirali srpsku verziju WebYep-a, upisite "srpski" umesto "auto" u sledecem redu.

   $webyep_sLang = "auto";


   // Zur Problembehebung bittet Sie der WebYep Support manchmal, den WebYep Debug Modus zu aktivieren
   // Sometimes WebYep Support will ask you to activate WebYep's debug mode to solve a problem

   $webyep_bDebug = false;

   // Wichtig: In dieser Datei dürfen am Beginn und Ende keine Leerzeilen eingefügt werden!!!
   // Important: Do not insert ANYTHING before or after the PHP block in this file!!!
?>