<?php
error_reporting(0);
require 'h_abox.php';
require 'h_constants.php';
require 'h_db.php';
require 'h_functions.php';

if (isset($_GET['id'])) {
	$strID = KillInjection(mb_strimwidth($_GET['id'], 0, 36, ""));
	if ($strID{0} == "d") {
		$varResult =  cDB::EditLogByID($strID);

		if ($varResult) {
			$strOponent = explode("vs.", $varResult["title"]);

			$urlImg = "index_files/discord/discord_logserver.png";

			if (strpos($strOponent[0], "fiks") !== false) $urlImg = "index_files/discord/discord_logserver_fiks.png";
			else {
				if ($varResult["user_id"] != 0) {
			        for ($i=1; $i < 4; $i++) { 
			            $varGameResult = unserialize(file_get_contents("./cache/game" . $i));
			            arsort($varGameResult);
			            $strLogin = key($varGameResult);

			            $strQuery = "SELECT * FROM `T_USERS` WHERE `user_login`='$strLogin';";
			            $varResultDb = cDB::QueryDB($strQuery);
			            $objRow = $varResultDb->fetch_array(MYSQLI_ASSOC);

			            if ($objRow["user_id"] == $varResult["user_id"] && $i == 2)
			            	$urlImg = "index_files/discord/discord_logserver_game2.png";
			            if ($objRow["user_id"] == $varResult["user_id"] && $i == 3)
			            	$urlImg = "index_files/discord/discord_logserver_game3.png";
			            if ($objRow["user_id"] == $varResult["user_id"] && $i == 1)
			            	$urlImg = "index_files/discord/discord_logserver_game1.png";
			        }
			    }
			}

			$img = ImageCreateFromPNG($urlImg);

			// определяем цвет, в RGB
			$colorWhite = imagecolorallocate($img, 255, 255, 255);
			$colorRed = imagecolorallocate($img, 159, 0, 0);
			$colorGreen = imagecolorallocate($img, 0, 132, 16);
			 
			// указываем путь к шрифту
			$numFont = 'index_files/ttf/soviet.ttf';
			$textFont = 'index_files/ttf/segoeuib.ttf';
			
			$rA = -1 * (0.9 * strlen(trim($strOponent[0]))) + 50;
			if ($rA < 10) $rA = 10;
			$rD = -1 * (0.9 * strlen(trim($strOponent[1]))) + 50;
			if ($rD < 10) $rD = 10;
			imagettftext($img, $rA, 0, 20, 154, $colorWhite, $textFont, trim($strOponent[0]));
			imagettftext($img, $rD, 0, 20, 263, $colorWhite, $textFont, trim($strOponent[1]));

			$aProfit = trim(number_format($varResult["aprofit"]));
			$dProfit = trim(number_format($varResult["dprofit"]));
			if ($varResult["aprofit"] > 0) {
				$aProfit = "+" . $aProfit;
				$colorAprofit = $colorGreen;
			} elseif ($varResult["aprofit"] == 0) {
				$colorAprofit = $colorWhite;		
			} else $colorAprofit = $colorRed;

			if ($varResult["dprofit"] > 0) {
				$dProfit = "+" . $dProfit;
				$colorDprofit = $colorGreen;
			} elseif ($varResult["dprofit"] == 0) {
				$colorDprofit = $colorWhite;		
			} else $colorDprofit = $colorRed;

			$xApr = 320 - (strlen($aProfit) * 7);
			$xDpr = 320 - (strlen($dProfit) * 7);
			imagettftext($img, 24, 0, $xApr, 104, $colorAprofit, $numFont, $aProfit);
			imagettftext($img, 24, 0, $xDpr, 213, $colorDprofit, $numFont, $dProfit);

			imagettftext($img, 11, 0, 430, 55, $colorWhite, $numFont, date('Y-m-d', $varResult["date"]));
			$strUni = trim($varResult["universe"]);
			if ($NameUni[$strUni][0] && $NameUni[$strUni][0] != 1) $strUni .= ". " . $NameUni[$strUni][0];
			imagettftext($img, 9, 0, 285, 25, $colorWhite, $numFont, $strUni);
			imagettftext($img, 9, 0, 288, 50, $colorWhite, $numFont, trim($varResult["domain"]));
			// 24 - размер шрифта
			// 0 - угол поворота
			// 365 - смещение по горизонтали
			// 159 - смещение по вертикали
		} else {
			$img = ImageCreateFromPNG("index_files/discord/discord_logserver_del.png");
			imagealphablending($img, false);
			imagesavealpha($img, true);
		}
		header('Content-type: image/png');
		imagepng($img);
		imagedestroy($img);
	}
	if ($strID{0} == "f") {
		$varResult = cDB::LoadEspByID($strID);
		if ($varResult) {
			$objLog = unserialize(gzuncompress($varResult["obj_log"]));

			if ($objLog->generic->activity != "-1") {
				$strActivity = "active";
				$intActivityMin = $objLog->generic->activity;
			} else {
				$strActivity = "noactive";				
				$intActivityMin = false;
			}

			if ($objLog->generic->defender_planet_type == 3) {
				$strPlanetType = "moon";
			} else {
				$strPlanetType = "planet";				
			}
			if (isset($_GET["test"]))
				$urlImg = "index_files/discord/test.png";
			else
				$urlImg = "index_files/discord/spy_" . $strPlanetType . "_" . $strActivity . ".png";

			$img = ImageCreateFromPNG($urlImg);

			// определяем цвет, в RGB
			$colorWhite = imagecolorallocate($img, 255, 255, 255);
			$colorRed = imagecolorallocate($img, 159, 0, 0);
			$colorGreen = imagecolorallocate($img, 0, 132, 16);
			$colorMoon = imagecolorallocate($img, 159, 253, 255);
			$colorPlanet = imagecolorallocate($img, 255, 150, 37);

			if ($strPlanetType == "moon")
				$colorCoord = $colorMoon;
			else
				$colorCoord = $colorPlanet;
			 
			// указываем путь к шрифту
			$numFont = 'index_files/ttf/soviet.ttf';
			$textFont = 'index_files/ttf/segoeuib.ttf';
			
			imagettftext($img, 11, 0, 430, 55, $colorWhite, $numFont, date('Y-m-d', $objLog->generic->event_timestamp));
			imagettftext($img, 13, 0, 430, 99, $colorWhite, $numFont, date('H:i:s', $objLog->generic->event_timestamp));
			$strUni = trim($varResult["universe"]);
			if ($NameUni[$strUni][0] && $NameUni[$strUni][0] != 1) $strUni .= ". " . $NameUni[$strUni][0];
			imagettftext($img, 9, 0, 285, 25, $colorWhite, $numFont, $strUni);
			imagettftext($img, 9, 0, 288, 50, $colorWhite, $numFont, trim($varResult["domain"]));

			if ($intActivityMin) {
				if ($intActivityMin > 15) $colorActivity = $colorPlanet;
				else $colorActivity = $colorRed;
				imagettftext($img, 18, 0, 260, 101, $colorActivity, $numFont, $intActivityMin . " min");
			}

			imagettftext($img, 18, 0, 75, 101, $colorCoord, $numFont, $objLog->generic->defender_planet_coordinates);
			if ($objLog->generic->defender_alliance_tag) $strAllTag = "[" . $objLog->generic->defender_alliance_tag . "] ";
			imagettftext($img, 22, 0, 75, 150, $colorWhite, $textFont, $strAllTag . $objLog->generic->defender_name);

			imagettftext($img, 12, 0, 70, 195, $colorWhite, $numFont, number_format($objLog->details->resources->metal));
			imagettftext($img, 12, 0, 70, 235, $colorWhite, $numFont, number_format($objLog->details->resources->crystal));
			imagettftext($img, 12, 0, 70, 275, $colorWhite, $numFont, number_format($objLog->details->resources->deuterium));

			if ($objLog->generic->failed_ships) 
				$baseFleetSumeCost = "-";
            else {
				$baseFleetSumeCost = 0;
            	foreach ($objLog->details->ships as $key => $ships) {
            		$baseCost = array_sum(GetBaseCost($ships->ship_type));
            		$baseFleetSumeCost += array_sum(GetBaseCost($ships->ship_type)) * $ships->count;
            	}
            	$baseFleetSumeCost = number_format($baseFleetSumeCost);
			}

			if ($objLog->generic->failed_defense)			
				$baseDefenderSumeCost = "-";
			else {
				$baseDefenderSumeCost = 0;
	            foreach ($objLog->details->defense as $key => $defense) {
	            	$baseDefenderSumeCost += array_sum(GetBaseCost($defense->defense_type)) * $defense->count;
	            }
            	$baseDefenderSumeCost = number_format($baseDefenderSumeCost);
	        }

			if ($objLog->generic->failed_buildings)			
				$baseBuildingsSume = "-";
			else {
				$baseBuildingsSume = 0;
	            foreach ($objLog->details->buildings as $key => $buildings) {
	            	$baseBuildingsSume += $buildings->level;
	            }
            	$baseBuildingsSume = number_format($baseBuildingsSume);
	        }

			imagettftext($img, 12, 0, 300, 195, $colorWhite, $numFont, $baseFleetSumeCost);
			imagettftext($img, 12, 0, 300, 235, $colorWhite, $numFont, $baseDefenderSumeCost);
			imagettftext($img, 12, 0, 300, 275, $colorWhite, $numFont, $baseBuildingsSume);


		}
		//var_dump($objLog);
		header('Content-type: image/png');
		imagepng($img);
		imagedestroy($img);
	}
}