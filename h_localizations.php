<?php
	function Dictionary($strId) {
		global $g_arrLocalizations_;
		return $g_arrLocalizations_[$strId];
	}

	if (!isset($_SESSION["lang"]) && isset($_COOKIE["lang"])) 
		$_SESSION["lang"] = KillInjection($_COOKIE["lang"]);
	if ($_SESSION["lang"] == "de") include "localizations/DE.php";
	if ($_SESSION["lang"] == "en") include "localizations/EN.php";
	if ($_SESSION["lang"] == "fr") include "localizations/FR.php";
	if ($_SESSION["lang"] == "bg") include "localizations/BG.php";
	if ($_SESSION["lang"] == "ru") include "localizations/RU.php";
	if ($_SESSION["lang"] == "ua") include "localizations/UA.php";

?>
