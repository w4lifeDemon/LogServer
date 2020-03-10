<?php
	session_start();
    date_default_timezone_set('Europe/Moscow');
    require 'h_abox.php';
    require 'h_constants.php';
    require 'h_localizations.php';
    require 'h_db.php';
//unserialize
    $strId = KillInjection ($_GET["id"]);
    $strtA = KillInjection ((int)$_GET["tA"]);
    $strtD = KillInjection ((int)$_GET["tD"]);

    if (!$strtA) $strtA = 0;
    if (!$strtD) $strtD = 0;

      function GetClans ($strTitle) {
      	    $varClans["Attacker"] = array();
      	    $varClans["Defender"] = array();
      	    $varResult = array();

            $strTitle = explode("vs.", $strTitle);
            $strPattern = "/\[(\s|\w|-)+\]/ui";

            if (preg_match_all($strPattern, $strTitle[0], $intMatches, PREG_PATTERN_ORDER)) {
                $intMatches[0] = array_unique($intMatches[0]);
                if (count($intMatches[0]) == 0) return false;
                for ($i=0; $i<=count($intMatches[0]); $i++) {
                    if ($intMatches[0][$i]) {
                        $varClans["Attacker"][] = $intMatches[0][$i];
                    }
                }
            }

            if (preg_match_all($strPattern, $strTitle[1], $intMatches, PREG_PATTERN_ORDER)) {
                $intMatches[0] = array_unique($intMatches[0]);
                if (count($intMatches[0]) == 0) return false;
                for ($i=0; $i<=count($intMatches[0]); $i++) {
                    if ($intMatches[0][$i]) {
                        $varClans["Defender"][] = $intMatches[0][$i];
                    }
                }
            }
            return $varClans;
      }
      function SaveClans ($strId, $strDate, $intUni, $strDomain, $intLoses, $strtA, $strtD, $strTitle) {
            $varClans = GetClans ($strTitle);
            if ($varClans) {
                if (count($varClans["Attacker"]) <= count($varClans["Defender"])) {
                    for ($i=0; $i<count($varClans["Attacker"]); $i++) {
                        for ($z=0; $z<count($varClans["Defender"]); $z++) {
                            if ($varClans["Attacker"][$i] != $varClans["Defender"][$z]) {
                                $varClanAttacker = $varClans["Attacker"][$i];
                                $varClanDefender = $varClans["Defender"][$z];

    			                $strQuery = "SELECT `war_id` FROM `T_WARS` WHERE (`all1` = '$varClanAttacker' AND `all2` = '$varClanDefender') OR (`all1` = '$varClanDefender' AND `all2` = '$varClanAttacker');";

                                $varResult = cDB::QueryDB($strQuery);
			                    if (mysqli_num_rows($varResult) == 0) {
    			                    $strWarDate = time();
    			                    $strWarId = md5($strWarDate);
                    				$strQuery = "INSERT INTO  `T_WARS` (
                    								`war_id` ,
                    								`date` ,
                    								`all1` ,
                    								`all2` ,
                    								`universe` ,
                    								`domain`
                    							)
                    							VALUES (
                    								'$strWarId', '$strWarDate', '$varClanAttacker', '$varClanDefender', '$intUni', '$strDomain'
                    							);";
                                    echo "T_WARS new1";
                    				if (!cDB::QueryDB($strQuery)) {
                    					LogError('cDB::SaveClans', 'QueryDB failed');
                    					return false;
                    				}
                    			} else {
                        			$arrBinLog = $varResult->fetch_array(MYSQLI_ASSOC);
                        			$binLog = $arrBinLog;
                                    $strWarId = $binLog["war_id"];
                                }
                                echo $strWarId . "<br>";
                                echo $strId . "<br>";
                                echo $strDate . "<br>";
                                echo $intUni . "<br>";
                                echo $strDomain . "<br>";
                                echo $intLoses . "<br>";
                                echo $strTitle . "<br>";
                    			$strQuery = "INSERT INTO  `T_WARS_LOGS` (
                    								`war_id` ,
                    								`log_id` ,
                    								`date` ,
                    								`universe` ,
                    								`domain` ,
                    								`losses` ,
                    								`total_a` ,
                    								`total_d` ,
                    								`title`
                    							)
                    							VALUES (
                    								'$strWarId', '$strId', '$strDate', '$intUni', '$strDomain', '$intLoses', '$strtA', '$strtD', '$strTitle'
                    							);";
                                echo "save";
                    			if (!cDB::QueryDB($strQuery)) {
                    			    LogError('cDB::SaveClans', 'QueryDB failed');
                    			    return false;
                    			}
                            }
                        }
                    }
                } else {
                    for ($i=0; $i<count($varClans["Defender"]); $i++) {
                        for ($z=0; $z<count($varClans["Attacker"]); $z++) {
                            if ($varClans["Attacker"][$z] != $varClans["Defender"][$i]) {
                                $varClanAttacker = $varClans["Attacker"][$z];
                                $varClanDefender = $varClans["Defender"][$i];
    			                $strQuery = "SELECT * FROM `T_WARS` WHERE (`all1` = '$varClanAttacker' AND `all2` = '$varClanDefender') OR (`all2` = '$varClanAttacker' AND `all1` = '$varClanDefender');";

                                $varResult = cDB::QueryDB($strQuery);
			                    if (mysqli_num_rows($varResult) == 0) {
    			                    $strWarDate = time();
    			                    $strWarId = md5($strWarDate);
                    				$strQuery = "INSERT INTO  `T_WARS` (
                    								`war_id` ,
                    								`date` ,
                    								`all1` ,
                    								`all2` ,
                    								`universe` ,
                    								`domain`
                    							)
                    							VALUES (
                    								'$strWarId', '$strWarDate', '$varClanAttacker', '$varClanDefender', '$intUni', '$strDomain'
                    							);";
                                    echo "T_WARS new2";
                    				if (!cDB::QueryDB($strQuery)) {
                    					LogError('cDB::SaveClans', 'QueryDB failed');
                    					return false;
                    				}
                    			} else {
                        			$arrBinLog = $varResult->fetch_array(MYSQLI_ASSOC);
                        			$binLog = $arrBinLog;
                                    $strWarId = $binLog["war_id"];
                                }

                    			$strQuery = "INSERT INTO  `T_WARS_LOGS` (
                    								`war_id` ,
                    								`log_id` ,
                    								`date` ,
                    								`universe` ,
                    								`domain` ,
                    								`losses` ,
                    								`total_a` ,
                    								`total_d` ,
                    								`title`
                    							)
                    							VALUES (
                    								'$strWarId', '$strId', '$strDate', '$intUni', '$strDomain', '$intLoses', '$strtA', '$strtD', '$strTitle'
                    							);";
                    			if (!cDB::QueryDB($strQuery)) {
                    			    LogError('cDB::SaveClans', 'QueryDB failed');
                    			    return false;
                    			}
                            }
                        }
                    }
                }
            } else {
                return false;
            }
        }

    if ($strId) {
		$varResult = cDB::LoadTitleByID($strId);
        echo $varResult["title"] . "<br>";
        $varClans = GetClans ($varResult["title"]);
        echo $varClans["Defender"][2];
        SaveClans ($strId, $varResult["date"], $varResult["universe"], $varResult["domain"], $varResult["losses"], $strtA, $strtD, $varResult["title"]);
    }
/*$varResult = GetClans("[SERENITY] Woodoo #6, [s] Woodoo #6 vs. [DOM] man #243 ");
    if ($varResult) {
        for ($i=0; $i<=count($varResult["Attacker"]); $i++) {
            if ($varResult["Attacker"][$i]) echo $varResult["Attacker"][$i]."<br>";
        }
    } else echo 0;*/
        
?>