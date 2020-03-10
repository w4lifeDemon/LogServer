<?php
	// child class, 0x ogame engine
	class cParser_0x extends cParser {
		// initial attributes
		private $objParser = null;
		
		function __construct(&$objParser) {
			$this->objParser = $objParser;
		}
		
		public function Get($strWhat) {
			$varReturn = false;
			switch (strtolower($strWhat)) {
				case "something":	$varReturn = "something"; break;
				default:			LogError("objParser->Get", "Unknown input parameter: " . $strWhat); break;
			}
			return $varReturn;
		}
		
		public function Parse() {
			//$this->BugFix();
			$strHTMLLog = $this->objParser->objLog->Get("htmllog");
			$strHTMLLog = str_replace("</th></th>", "</th>", $strHTMLLog); // bug fix
			$objBattle = new cBattle();
			
			$arrRounds = $this->GetHTML_Rounds($strHTMLLog);
			if (!$arrRounds) {
				LogError("objParser->Parse", "GetHTML_Rounds failed");
				return false;
			}
			
			$strRoundAttacker = $this->GetHTML_RoundAttacker($arrRounds);
			$strRoundDefender = $this->GetHTML_RoundDefender($arrRounds);
			
			$strDefenders = "";
			$strAttackers = "";
			
			if (!$strRoundAttacker) {
				LogError("objParser->Parse", "GetHTML_RoundAttacker failed");
				return false;
			}
			
			for ($i = 0; $i < count($strRoundAttacker); $i++) {
				$arrRoundAttackerPlayers =  $this->GetHTML_RoundPlayers($strRoundAttacker[$i]);
				
				if (!$arrRoundAttackerPlayers) {
					LogError("objParser->Parse", "GetHTML_RoundPlayers failed");
					return false;
				}
				
				foreach ($arrRoundAttackerPlayers as $strPlayer) {
					$strPlayerName = $this->GetNameFromTitle($strPlayer);
					
					if ($i == 0) {
						if (!ereg($strPlayerName,$strAttackers)) {
							if ($strAttackers <> "") {
								$strAttackers .= ", ";
							}
							$strAttackers .= $strPlayerName;
						}
						
						$arrTechnologies = $this->GetHTML_PlayerTechnologies($strPlayer);
						if ($arrTechnologies) {
							$arrTechnologies = ConvertTechnologies($arrTechnologies);
						}
						
						$arrFleet = $this->GetHTML_PlayerFleet($strPlayer, $arrTechnologies);
						
						if (!$arrFleet) {
							LogError("objParser->Parse", "GetHTML_PlayerFleet failed");
							return false;
						}
						
						if ($arrFleet['type'] != 'round') {
							$arrFleet['message'] = str_replace($strPlayerName,"",$arrFleet['message']);
						}
						
						$arrCoordinates = $this->GetPlayerCoordinates($strPlayer);
						if (!$arrCoordinates) {
							LogError("objParser->Parse", "GetPlayerCoordinates failed");
							return false;
						}
						
						$objPlayer = new cPlayer($strPlayerName, ATTACKER, $arrCoordinates, $arrTechnologies);
						
						$arrInput = NULL;
						$arrInput['intRound'] = $i;
						$arrInput['arrFleet'] = $arrFleet;
						$objPlayer->Set('RoundFleet', $arrInput);
						
						$objBattle->arrAttackers[] = $objPlayer;
					}
					else {
						
						foreach ($objBattle->arrAttackers as $objPlayer) {
							if ($objPlayer->Get('name') == $strPlayerName) {
								$arrTechnologies = $objPlayer->Get('technologies');
							}
						}
						$arrFleet = $this->GetHTML_PlayerFleet($strPlayer, $arrTechnologies);
						
						if (!$arrFleet) {
							LogError("objParser->Parse", "GetHTML_PlayerFleet failed");
							return false;
						}
						
						if ($arrFleet['type'] != 'round') {
							$arrFleet['message'] = str_replace($strPlayerName,"",$arrFleet['message']);
						}
						
						$arrInput = NULL;
						$arrInput['intRound'] = $i;
						$arrInput['arrFleet'] = $arrFleet;
						
						foreach ($objBattle->arrAttackers as $key => $value) {
							if (($objBattle->arrAttackers[$key]->Get('roundscount') == $i) && ($objBattle->arrAttackers[$key]->Get('name') == $strPlayerName)) {
								$objBattle->arrAttackers[$key]->Set('RoundFleet', $arrInput);
								break;
							}
						}
						
					}
				}
			}
			
			if (!$strRoundDefender) {
				LogError("objParser->Parse", "GetHTML_RoundDefender failed");
				return false;
			}
			
			for ($i = 0; $i < count($strRoundDefender); $i++) {
				$arrRoundDefenderPlayers =  $this->GetHTML_RoundPlayers($strRoundDefender[$i]);
				
				if (!$arrRoundDefenderPlayers) {
					LogError("objParser->Parse", "GetHTML_RoundPlayers failed");
					return false;
				}
				
				foreach ($arrRoundDefenderPlayers as $strPlayer) {
					
					
					$strPlayerName = $this->GetNameFromTitle($strPlayer);
					if ($i == 0) {
						if (!ereg($strPlayerName,$strDefenders)) {
							if ($strDefenders <> "") {
								$strDefenders .= ", ";
							}
							$strDefenders .= $strPlayerName;
						}
						
						$arrTechnologies = $this->GetHTML_PlayerTechnologies($strPlayer);
						if ($arrTechnologies) {
							$arrTechnologies = ConvertTechnologies($arrTechnologies);
						}
						
						$arrFleet = $this->GetHTML_PlayerFleet($strPlayer, $arrTechnologies);
						
						if (!$arrFleet) {
							LogError("objParser->Parse", "GetHTML_PlayerFleet failed");
							return false;
						}
						
						if ($arrFleet['type'] != 'round') {
							$arrFleet['message'] = str_replace($strPlayerName,"",$arrFleet['message']);
						}
						
						$arrCoordinates = $this->GetPlayerCoordinates($strPlayer);
						if (!$arrCoordinates) {
							LogError("objParser->Parse", "GetPlayerCoordinates failed");
							return false;
						}
						
						$objPlayer = new cPlayer($strPlayerName, DEFENDER, $arrCoordinates, $arrTechnologies);
						
						$arrInput = NULL;
						$arrInput['intRound'] = $i;
						$arrInput['arrFleet'] = $arrFleet;
						$objPlayer->Set('RoundFleet', $arrInput);
						
						$objBattle->arrDefenders[] = $objPlayer;
					}
					else {
						
						foreach ($objBattle->arrDefenders as $objPlayer) {
							if ($objPlayer->Get('name') == $strPlayerName) {
								$arrTechnologies = $objPlayer->Get('technologies');
							}
						}
						$arrFleet = $this->GetHTML_PlayerFleet($strPlayer, $arrTechnologies);
						
						if (!$arrFleet) {
							LogError("objParser->Parse", "GetHTML_PlayerFleet failed");
							return false;
						}
						
						if ($arrFleet['type'] != 'round') {
							$arrFleet['message'] = str_replace($strPlayerName,"",$arrFleet['message']);
						}
						
						$arrInput = NULL;
						$arrInput['intRound'] = $i;
						$arrInput['arrFleet'] = $arrFleet;
						
						foreach ($objBattle->arrDefenders as $key => $value) {
							if (($objBattle->arrDefenders[$key]->Get('roundscount') == $i) && ($objBattle->arrDefenders[$key]->Get('name') == $strPlayerName)) {
								$objBattle->arrDefenders[$key]->Set('RoundFleet', $arrInput);
								break;
							}
						}
						
					}
				}
			}
			
			$objBattle->intRoundsCount = count($strRoundDefender);
			$objBattle->arrRoundInfo = $this->GetHTML_RoundInfo($strHTMLLog);
			
			

			$objBattle->strTitle = $strAttackers." vs. ".$strDefenders;
			$objBattle->strStartInfo = $this->GetStartInfo($strHTMLLog);
			$objBattle->arrCombatResult = $this->GetCombatResult($strHTMLLog);
			
			$objBattle->arrRoundInfo[0][0] = $objBattle->strStartInfo;
			$objBattle->arrRoundInfo[0][1] = $objBattle->strTitle;
			
			// + additional data in $objBattle used in cHTMLConstructor
			$objBattle->strId = $this->objParser->Get("id");
			$objBattle->strDate = date("d/m/y");
			
			$objBattle->intUni = $this->objParser->Get("uni");
			$objBattle->strDomain = $this->objParser->Get("domain");
			$objBattle->intSkin = $this->objParser->Get("skin");
			$objBattle->strReportPO = $this->objParser->Get("reportpo");

			$objBattle->strRecyclerReport = $this->objParser->Get("recyclerreport");
			$objBattle->strComment = $this->objParser->Get("comment");
			$objBattle->strCleanUp = $this->objParser->Get("cleanup");
			
			$objBattle->blnPublic = $this->objParser->Get("public");
			$objBattle->blnHideTech = $this->objParser->Get("hidetech");
			$objBattle->blnHideCoord = $this->objParser->Get("hidecoord");

			$objBattle->intIPMs = $this->objParser->Get("ipms");
			$objBattle->blnFuel = $this->objParser->Get("fuel");
			$objBattle->blnPFuel = $this->objParser->Get("pfuel");
			
			// Return objBattle as objSource ByRef (!)
			$this->objParser->objSource = $objBattle;
			
			return true;
		}
		
		private function GetRoundPlayerHead($strInput) {
			$strResult = $strInput;
			if (strpos($strResult, "<table")) {
				$strResult = substr($strInput, 0, strpos($strResult, "<table"));
			}
			
			return $strResult;
		}
		
		private function GetPlayerWeaponsForLog($strInput) {
			
			$strResult = $this->GetRoundPlayerHead($strInput);
			$arrResult = explode("<br>",$strResult);
			
			if (!$arrResult[1]) {
				LogError("objParser->GetPlayerWeaponsForLog", "Technologies not found");
				return "";
			}
			
			$strReturn = trim($arrResult[1]);
			
			preg_match_all('/[0-9]+%/', $strReturn, $arrMatches);
			$strReturn = "(".$arrMatches[0][0].",".$arrMatches[0][1].",".$arrMatches[0][2].")";
			
			if ($this->objParser->objLog->Get("hidetech")) {
				//$strReturn = preg_replace("/[0-9]{2,3}%/", "X%", $strReturn);
				$strReturn = "";
			}
			
			return $strReturn;
		}
		
		private function GetPlayerCoordinates($strInput) {
			$arrResult = NULL;
			
			$strTemp = substr($strInput, 0, strpos($strInput, "<br>"));
			
			if (!$strTemp) {
				$strTemp = substr($strInput, 0, strpos($strInput, "<table"));
			}
			
			if (!$strTemp) {
				LogError("objParser->GetHTML_PlayerName", "Player name not found");
				return NULL;
			}
			
			if (preg_match("/\[[0-9]{1,3}:[0-9]{1,3}:[0-9]{1,3}\]/", trim($strTemp), $arrMatches)) {
				if (preg_match_all('/[0-9]+/', $arrMatches[0], $arrMatches_)) {
					$arrResult[GALAXY] = $arrMatches_[0][0];
					$arrResult[STAR] = $arrMatches_[0][1];
					$arrResult[PLANET] = $arrMatches_[0][2];
				}
				else {
					LogError("objParser->GetPlayerCoordinates", "Player coordinates not found");
					return NULL;
				}
			}
			else {
				LogError("objParser->GetPlayerCoordinates", "Player coordinates not found");
				return NULL;
			}
			
			return $arrResult;
		}
		
		private function GetNameFromTitle($strInput) {
			$strReturn = UNDEFINED;
			
			$strReturn = substr($strInput, 0, strpos($strInput, "<br>"));
			
			if (!$strReturn) {
				$strReturn = substr($strInput, 0, strpos($strInput, "<table"));
			}
			
			if (!$strReturn) {
				LogError("objParser->GetNameFromTitle", "Player name not found");
				return -1;
			}
			
			if(!preg_match('/[0-9A-Za-z_-]+ \(/', $strReturn, $arrMatches)) {
				LogError("objParser->GetNameFromTitle", "preg_match failed");
				return -1;
			}
			
			$strReturn = str_replace(" (", "", $arrMatches[0]);
			
			return $strReturn;
		}
		
		private function GetStartInfo($strInput) {
			$arrResult = QueryXPath($strInput, "/html/body/table/tr/td");
			if (!$arrResult) {
				LogError("objParser->GetStartInfo", "GetStartInfo failed");
				return false;
			}
			$strResult = substr($arrResult[0], 0, strpos($arrResult[0], "<br>"));
			return $strResult;
		}
		
		private function GetCombatResult($strInput) {
			$arrResult = QueryXPath($strInput, "/html/body/table/tr/td/p");
			if (!$arrResult) {
				LogError("objParser->GetCombatResult", "GetCombatResult failed");
				return false;
			}
			$arrReturn["all"] = "";
			for ($i = 0; $i < count($arrResult); $i++) {
				$arrReturn["all"] = $arrReturn["all"]."<p>".$arrResult[$i]."</p>";
			}
			$arrReturn["part"] = $arrResult;
			return $arrReturn;
		}
		
		// Returns rounds (string array)
		private function GetHTML_Rounds($strInput) {
			$arrResult = NULL;
			
			$arrResult = QueryXPath($strInput, "/html/body/table/tr/td/table");
			if (!$arrResult) {
				LogError("objParser->GetHTML_Rounds", "GetHTML_Rounds failed");
				return false;
			}
			return $arrResult;
		}
		
		// Returns round attacker table (string)
		private function GetHTML_RoundAttacker($arrRounds) {
			$arrResult = NULL;
			
			$j = 0;
			for ($i = 0; $i < count($arrRounds); $i++) {
				if ($i%2 == 0) {
					$arrResult[$j] = $arrRounds[$i];
					$j++;
				}
			}
			
			return $arrResult;
		}
		
		// Returns round_info (string)
		private function GetHTML_RoundInfo($strInput) {
			$arrResult = NULL;
			$arrRoundInfo = NULL;
			
			$arrResult = QueryXPath($strInput, "/html/body/table/tr/td/center");
			if (!$arrResult) {
				LogError("objParser->GetHTML_RoundInfo", "GetHTML_RoundInfo failed");
				return false;
			}
			for ($i = 0; $i < count($arrResult); $i++) {
				$arrTemp = explode("<br>",$arrResult[$i]);
				$arrRoundInfo[$i+1][0] = $arrTemp[0];
				$arrRoundInfo[$i+1][1] = $arrTemp[1];
			}
			
			return $arrRoundInfo;
		}
		
		// Returns round defender table (string)
		private function GetHTML_RoundDefender($arrRounds) {
			$arrResult = NULL;
			
			$j = 0;
			for ($i = 0; $i < count($arrRounds); $i++) {
				if ($i%2 != 0) {
					$arrResult[$j] = $arrRounds[$i];
					$j++;
				}
			}
			
			return $arrResult;
		}
		
		// Returns players in round attacker or defender (string array)
		private function GetHTML_RoundPlayers($strInput) {
			$arrResult = NULL;
			
			$strHTML = $strInput;
			$strTagName = "center";
			$arrAttributes = NULL;
			
			$arrResult = GetInnerHTML($strHTML, $strTagName, $arrAttributes);
			
			if (!$arrResult) {
				LogError("objParser->GetHTML_RoundPlayers", "GetInnerHTML failed");
				return false;
			}
			
			return $arrResult;
		}
		
		// Returns player name (string)
		private function GetHTML_PlayerName($strInput) {
			
			/*if (stristr($strInput, "destroyed textBeefy")) {
				return false;
			}*/
			
			$strReturn = substr($strInput, 0, strpos($strInput, "<br>"));
			
			if (!$strReturn) {
				$strReturn = substr($strInput, 0, strpos($strInput, "<table"));
			}
			
			if (!$strReturn) {
				LogError("objParser->GetHTML_PlayerName", "Player name not found");
				return -1;
			}
			
			$strReturn = str_replace("([", "[", $strReturn);
			$strReturn =str_replace("])", "]", $strReturn);
			
			if ($this->objParser->objLog->Get("hidecoord")) {
				//$strReturn = preg_replace("/\[[0-9]{1,3}:[0-9]{1,3}:[0-9]{1,3}\]/", "[X:X:X]", $strReturn);
				$strReturn = preg_replace("/\[[0-9]{1,3}:[0-9]{1,3}:[0-9]{1,3}\]/", "", $strReturn);
			}
			
			return $strReturn;
		}
		
		// Returns player technologies (string)
		private function GetHTML_PlayerTechnologies($strInput) {
			$arrResult = NULL;
			
			/*if (stristr($strInput, "destroyed textBeefy")) {
				return false;
			}*/
			
			$strResult = $this->GetRoundPlayerHead($strInput);
			$arrResult = explode("<br>",$strResult);
			
			if (!$arrResult[1]) {
				LogError("objParser->GetHTML_PlayerTechnologies", "Technologies not found");
				return false;
			}
			
			preg_match_all('/[0-9]+%/', $arrResult[1], $arrMatches);
			
			$arrResult = NULL;
			$arrResult[WEAPONS] = $arrMatches[0][0];
			$arrResult[SHIELDS] = $arrMatches[0][1];
			$arrResult[ARMORS] = $arrMatches[0][2];
			
			return $arrResult;
		}
		
		// Returns player fleet (array)
		private function GetHTML_PlayerFleet($strInput, $arrTechnologies) {
			$arrResult = NULL;
			$arrTable = NULL;
			$strObjectName = false;
			$intObjectCount = '';
			
			if (!stristr($strInput, "table")) {
				
				$arrResult['type'] = 'end';
				$arrTemp = explode("<br>",$strInput);
				if (!$arrTemp[0]) {
					LogError("objParser->GetHTML_PlayerFleet", "GetHTML_PlayerFleet failed");
					return false;
				}
				
				$arrTemp[0] = str_replace("([", "[", $arrTemp[0]);
				$arrTemp[0] =str_replace("])", "]", $arrTemp[0]);
				
				$arrTemp[0] = preg_replace("/\[[0-9]{1,3}:[0-9]{1,3}:[0-9]{1,3}\]/", "", $arrTemp[0]);
					
				$strResult = $arrTemp[0]."<br>".$arrTemp[count($arrTemp)-1];
				$arrResult['message'] = $strResult;
				return $arrResult;
			}
			
			$strHTML = $strInput;
			$strTagName = "table";
			$arrAttributes = NULL;
			
			$arrResult = GetInnerHTML($strHTML, $strTagName, $arrAttributes);
			if (!$arrResult) {
				LogError("objParser->GetHTML_PlayerFleet", "GetInnerHTML failed");
				return false;
			}
			$strHTML = $arrResult[0];
			
			$strTagName = "tr";
			$arrAttributes = NULL;
			
			$arrResult = GetInnerHTML($strHTML, $strTagName, $arrAttributes);
			if (!$arrResult) {
				LogError("objParser->GetHTML_PlayerFleet", "GetInnerHTML failed");
				return false;
			}
			
			for ($i = 0; $i < count($arrResult); $i++) {
				$strHTML = $arrResult[$i];
				$strTagName = "th";
				$arrAttributes = NULL;
				$arrTable[] = GetInnerHTML($strHTML, $strTagName, $arrAttributes);
			}
			
			//print_r($arrTable);
			
			$arrFleet = NULL;
			
			$arrObject = NULL;
			
			$arrObject['name'] = "th";
			
			$arrObject['l_name'] = trim($arrTable[0][0]);
			$arrObject['l_count'] = trim($arrTable[1][0]);
			$arrObject['l_armors'] = trim($arrTable[4][0]);
			$arrObject['l_weapons'] = trim($arrTable[2][0]);
			$arrObject['l_shields'] = trim($arrTable[3][0]);
			
			$arrFleet["th"] = $arrObject;
			
			for ($i = 1; $i < count($arrTable[0]); $i++) {
				$intArmors = (integer) str_replace(",", "", str_replace('.', '', $arrTable[4][$i]));
				$intWeapons = (integer) str_replace(",", "", str_replace('.', '', $arrTable[2][$i]));
				
				if (!$arrTechnologies) {
					LogError("objParser->GetHTML_PlayerFleet", "empty arrTechnologies");
					return false;
				}
				
				$strObjectName = DetermineObjectName($intArmors, $intWeapons, $arrTechnologies);
				if (!$strObjectName) {
					LogError("objParser->GetHTML_PlayerFleet", "DetermineObject failed");
					return false;
				}
				
				$intObjectCount = (integer) str_replace(",", "", str_replace('.', '', $arrTable[1][$i]));
				$arrObject = NULL;
				
				$arrObject['name'] = $strObjectName;
				$arrObject['count'] = $intObjectCount;

				$arrObject['l_name'] = trim($arrTable[0][$i]);
				$arrObject['l_armors'] = trim($arrTable[4][$i]);
				$arrObject['l_weapons'] = trim($arrTable[2][$i]);
				$arrObject['l_shields'] = trim($arrTable[3][$i]);
				
				$arrFleet[$strObjectName] = $arrObject;
				//print_r($arrResult);
			}
			
			$arrResult = NULL;
			$arrResult['type'] = 'round';
			$arrResult['fleet'] = $arrFleet;
			return $arrResult;
		}
	}
?>