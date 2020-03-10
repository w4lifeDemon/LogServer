<?php
	// child class, 6x ogame engine
	class cParser_7x extends cParser {
		// initial attributes
		private $objParser = null;

		function __construct($objParser) {
			$this->objParser = $objParser;
		}

		public function Parse() {
			//$this->BugFix();
			$this->objParser->strVersion = 6;
			$objBattle = new cBattle();
        	$api_version = 'v1';
         	$path ='combat/report';
         	//$key = 'cr-en-671-3dfe8c0568d1aec2207c652aa3502ac6be867058';

         	$url_template = 'https://s%d-%s.ogame.gameforge.com/api/%s/%s?api_key=%s';
         	$data_array = explode("-", $this->objParser->objLog->Get("htmllog"));

         	$language = $data_array[1];
         	$serverId = $data_array[2];
         	$cr_id = $data_array[3];

         	$url = sprintf($url_template, $serverId, $language, $api_version, $path, OGAME_API);

        	$url = $url . "&cr_id=" . $cr_id;

        	if ($language && $serverId && $cr_id) $result = json_decode(file_get_contents($url));


            if(!isset($result->RESULT_CODE) || $result->RESULT_CODE != 1000 || $result == NULL) {
				LogError("RESULT_CODE", "Error from OGame Server!");
				return false;
			}

			$objBattle->intRoundsCount = count($result->RESULT_DATA->rounds);

            $evenTime = explode("T", $result->RESULT_DATA->generic->event_time);
            $evenTimeM = explode("+", $evenTime[1]);
			$objBattle->strStartInfo = "TIME: (" . $evenTime[0] . " " . $evenTimeM[0] . ")";

			$objBattle->arrCombatResult = $result->RESULT_DATA;

			// + additional data in $objBattle used in cHTMLConstructor
			$objBattle->strId = $this->objParser->Get("id");

			$objBattle->strDate = date("d/m/y");

			$objBattle->intUni = trim($serverId);
			$objBattle->strDomain = trim($language);
			$objBattle->intSkin = $this->objParser->Get("skin");
			$objBattle->strReportPO = $this->objParser->Get("reportpo");
			$objBattle->strMusic = $this->objParser->Get("music");

			$objBattle->strTitle = $this->GetTitle($result->RESULT_DATA->attackers, $result->RESULT_DATA->defenders, $objBattle->intUni, $objBattle->strDomain);

			$objBattle->strRecyclerReport = $this->objParser->Get("recyclerreport");
			$objBattle->strComment = $this->objParser->Get("comment");
			$objBattle->strCleanUp = $this->objParser->Get("cleanup");

			$objBattle->blnPublic = $this->objParser->Get("public");
			$objBattle->blnHideTech = $this->objParser->Get("hidetech");
			$objBattle->blnHideCoord = $this->objParser->Get("hidecoord");
			$objBattle->blnHideTime = $this->objParser->Get("hidetime");

			$objBattle->intIPMs = $this->objParser->Get("ipms");
			$objBattle->blnFuel = $this->objParser->Get("fuel");
			$objBattle->blnPFuel = $this->objParser->Get("pfuel");
			$objBattle->intPlugin = $this->objParser->Get("plugin");
			// Return objBattle as objSource ByRef (!)
			$this->objParser->objSource = $objBattle;

			return true;
		}
		//изменить логирование
		private function GetPlayerWeaponsForLog($strInput) {

			if (stristr($strInput, "destroyed textBeefy"))
				return "";

			$strHTML = $strInput;

			$strTagName = "span";
			$arrAttribute['name'] = "class";
			$arrAttribute['value'] = "weapons textBeefy";
			$arrAttributes[] = $arrAttribute;

			$arrTmp = GetInnerHTML($strHTML, $strTagName, $arrAttributes);

			if (!$arrTmp) {
				LogError("objParser->GetPlayerWeaponsForLog", "Technologies not found");
				return -1;
			}

			$strReturn = trim($arrTmp[0]);

			preg_match_all('/[0-9]+%/', $strReturn, $arrMatches);
			$strReturn = "(".$arrMatches[0][0].",".$arrMatches[0][1].",".$arrMatches[0][2].")";

			if ($this->objParser->objLog->Get("hidetech")) {
				//$strReturn = preg_replace("/[0-9]{2,3}%/", "X%", $strReturn);
				$strReturn = "";
			}

			return $strReturn;
		}

		private function GetPlayerNameForLog($strInput) {
			$arrResult = UNDEFINED;

			if (stristr($strInput, "destroyed textBeefy"))
				return "";

			$strHTML = $strInput;

			$strTagName = "span";
			$arrAttribute['name'] = "class";
			$arrAttribute['value'] = "name textBeefy";
			$arrAttributes[] = $arrAttribute;

			$arrTmp = GetInnerHTML($strHTML, $strTagName, $arrAttributes);

			if (!$arrTmp) {
				LogError("objParser->GetPlayerNameForLog", "GetInnerHTML failed");
				return -1;
			}

			$strReturn = trim($arrTmp[0]);

			if ($this->objParser->objLog->Get("hidecoord")) {
				//$strReturn = preg_replace("/\[[0-9]{1,3}:[0-9]{1,3}:[0-9]{1,3}\]/", "[X:X:X]", $strReturn);
				$strReturn = preg_replace("/\[[0-9]{1,3}:[0-9]{1,3}:[0-9]{1,3}\]/", "", $strReturn);
			}

			return $strReturn;
		}

		private function GetTitle($strAttackers, $strDefenders, $intUni, $strDomain) {
			$nameAttacker = array();
			$nameDefender = array();
			foreach ($strAttackers as $key => $value) {
				if (!in_array($value->fleet_owner, $nameAttacker)) {
					if ($value->fleet_owner_alliance_tag) $allianceTagAttacker[$key] = "[" . $value->fleet_owner_alliance_tag . "] ";  
					$arrAttacker[$key] = $allianceTagAttacker[$key] . $value->fleet_owner;
				} 
				$nameAttacker[$key] = $value->fleet_owner;
			}

			foreach ($strDefenders as $key => $value) {
				if (!in_array($value->fleet_owner, $nameDefender)) {
					if ($value->fleet_owner_alliance_tag) $allianceTagDefender[$key] = "[" . $value->fleet_owner_alliance_tag . "] ";  
					$arrDefender[$key] = $allianceTagDefender[$key] . $value->fleet_owner;
				} 
				$nameDefender[$key] = $value->fleet_owner;
			}
			
			$strAttacker = join(", ", $arrAttacker);
			$strDefender = join(", ", $arrDefender);

			$strReturn = $strAttacker . " vs. " . $strDefender;
            
			return $strReturn;
		}

		private function GetStartInfo() {
			$strReturn = UNDEFINED;
			$arrTmp = NULL;

			$strHTML = $this->objParser->objLog->Get("htmllog");
			$strTagName = "p";
			$arrAttribute['name'] = "class";
			$arrAttribute['value'] = "start";
			$arrAttributes[] = $arrAttribute;

			$arrTmp = GetInnerHTML($strHTML, $strTagName, $arrAttributes);

			if (!$arrTmp) {
				LogError("objParser->GetStartInfo", "GetInnerHTML failed");
				return -1;
			}

			$strReturn = trim($arrTmp[0]);

			return $strReturn;
		}

		private function GetCombatResult() {
			$arrReturn = NULL;
			$arrTmp = NULL;

			$strHTML = $this->objParser->objLog->Get("htmllog");
			$strTagName = "div";
			$arrAttribute['name'] = "id";
			$arrAttribute['value'] = "combat_result";
			$arrAttributes = NULL;
			$arrAttributes[] = $arrAttribute;

			$arrTmp = GetInnerHTML($strHTML, $strTagName, $arrAttributes);
			if (!$arrTmp) {
				LogError("objParser->GetCombatResult", "GetInnerHTML failed");
				return $arrReturn;
			}

			$arrReturn["all"] = str_replace(",999", "", $arrTmp[0]);

			$strHTML = $arrTmp[0];
			$strTagName = "p";
			$arrAttribute['name'] = "class";
			$arrAttribute['value'] = "action";
			$arrAttributes = NULL;
			$arrAttributes[] = $arrAttribute;

			$arrTmp = GetInnerHTML($strHTML, $strTagName, $arrAttributes);
			if (!$arrTmp) {
				LogError("objParser->GetCombatResult", "GetInnerHTML failed");
				return $arrReturn;
			}

			$arrReturn["part"] = str_replace(",999", "", $arrTmp);

			return $arrReturn;
		}


		// Returns rounds (string array)
		private function GetHTML_Rounds($strInput) {
			$arrResult = NULL;

			$strHTML = $strInput;
			$strTagName = "div";
			$arrAttribute['name'] = "class";
			$arrAttribute['value'] = "combat_round";
			$arrAttributes[] = $arrAttribute;

			$arrResult = GetInnerHTML($strHTML, $strTagName, $arrAttributes);

			if (!$arrResult) {
				LogError("objParser->GetHTML_Rounds", "GetInnerHTML failed");
				return false;
			}

			return $arrResult;
		}

		// Returns round attacker table (string)
		private function GetHTML_RoundAttacker($strInput) {
			$arrResult = NULL;

			$strHTML = $strInput;
			$strTagName = "td";
			$arrAttribute['name'] = "class";
			$arrAttribute['value'] = "round_attacker textCenter";
			$arrAttributes[] = $arrAttribute;

			$arrResult = GetInnerHTML($strHTML, $strTagName, $arrAttributes);

			if (!$arrResult) {
				LogError("objParser->GetHTML_RoundAttacker", "GetInnerHTML failed");
				return false;
			}

			return $arrResult[0];
		}

		// Returns round_info (array)
		private function GetHTML_RoundInfo($strInput) {
			$arrResult = NULL;

			$strHTML = $strInput;
			$strTagName = "div";
			$arrAttribute['name'] = "class";
			$arrAttribute['value'] = "round_info";
			$arrAttributes[] = $arrAttribute;

			$arrResult = GetInnerHTML($strHTML, $strTagName, $arrAttributes);

			if (!$arrResult) {
				LogError("objParser->GetHTML_RoundInfo", "GetHTML_RoundInfo failed");
				return false;
			}
			$strHTML = $arrResult[0];
			$strTagName = "p";
			$arrAttribute['name'] = "class";
			$arrAttribute['value'] = "action";
			$arrAttributes = NULL;
			$arrAttributes[] = $arrAttribute;

			$arrResult = GetInnerHTML($strHTML, $strTagName, $arrAttributes);

			if (!$arrResult) {
				LogError("objParser->GetHTML_RoundInfo", "GetHTML_RoundInfo failed (div[round_info]->p[action])");
			}

			return $arrResult;
		}

		// Returns round defender table (string)
		private function GetHTML_RoundDefender($strInput) {
			$arrResult = NULL;

			$strHTML = $strInput;
			$strTagName = "td";
			$arrAttribute['name'] = "class";
			$arrAttribute['value'] = "round_defender textCenter";
			$arrAttributes[] = $arrAttribute;

			$arrResult = GetInnerHTML($strHTML, $strTagName, $arrAttributes);

			if (!$arrResult) {
				LogError("objParser->GetHTML_RoundDefender", "GetInnerHTML failed");
				return false;
			}

			return $arrResult[0];
		}

		// Returns players in round attacker or defender (string array)
		private function GetHTML_RoundPlayers($strInput) {
			$arrResult = NULL;

			$strHTML = $strInput;
			$strTagName = "td";
			$arrAttribute['name'] = "class";
			$arrAttribute['value'] = "newBack";
			$arrAttributes[] = $arrAttribute;

			$arrResult = GetInnerHTML($strHTML, $strTagName, $arrAttributes);

			if (!$arrResult) {
				LogError("objParser->GetHTML_RoundPlayers", "GetInnerHTML failed");
				return false;
			}

			return $arrResult;
		}

		// Returns player name (string)
		private function GetHTML_PlayerName($strInput) {
			$arrResult = NULL;

			$strHTML = $strInput;
			$strTagName = "span";
			$arrAttribute['name'] = "class";
			if (!stristr($strInput, "destroyed textBeefy"))
				$arrAttribute['value'] = "name textBeefy";
			else
				$arrAttribute['value'] = "destroyed textBeefy";
			$arrAttributes[] = $arrAttribute;

			$arrResult = GetInnerHTML($strHTML, $strTagName, $arrAttributes);

			if (!$arrResult) {
				LogError("objParser->GetHTML_PlayerName", "GetInnerHTML failed");
				return false;
			}

			return $arrResult[0];
		}

		// Returns player coordinates (array)
		private function GetPlayerCoordinates($strInput) {
			$arrResult = NULL;

			if (stristr($strInput, "destroyed textBeefy"))
				return UNDEFINED;

			$strHTML = $strInput;

			$strTagName = "span";
			$arrAttribute['name'] = "class";
			$arrAttribute['value'] = "name textBeefy";
			$arrAttributes[] = $arrAttribute;

			$arrTmp = GetInnerHTML($strHTML, $strTagName, $arrAttributes);

			if (!$arrTmp) {
				LogError("objParser->GetPlayerCoordinates", "GetInnerHTML failed");
				return NULL;
			}

			if (preg_match("/\[[0-9]{1,3}:[0-9]{1,3}:[0-9]{1,3}\]/", trim($arrTmp[0]), $arrMatches)) {
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

		// Returns player technologies (string)
		private function GetHTML_PlayerTechnologies($strInput) {
			$arrResult = NULL;

			if (stristr($strInput, "destroyed textBeefy")) {
				return false;
			}

			$strHTML = $strInput;
			$strTagName = "span";
			$arrAttribute['name'] = "class";
			$arrAttribute['value'] = "weapons textBeefy";
			$arrAttributes[] = $arrAttribute;

			$arrResult = GetInnerHTML($strHTML, $strTagName, $arrAttributes);

			if (!$arrResult) {
				LogError("objParser->GetHTML_PlayerTechnologies", "GetInnerHTML failed");
				return false;
			}

			preg_match_all('/[0-9]+%/', $arrResult[0], $arrMatches);

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

			if (stristr($strInput, "destroyed textBeefy")) {

				$strHTML = $strInput;
				$strTagName = "span";
				$arrAttribute['name'] = "class";
				$arrAttribute['value'] = "destroyed textBeefy";
				$arrAttributes[] = $arrAttribute;
				$arrResult = GetInnerHTML($strHTML, $strTagName, $arrAttributes);
				if (!$arrResult) {
					LogError("objParser->GetHTML_PlayerFleet", "GetInnerHTML failed");
					return false;
				}
				$strMessage = $arrResult[0];
				$arrResult = NULL;
				$arrResult['type'] = 'end';
				$arrResult['message'] = $strMessage;
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

			$strTagName = "th";
			$arrAttribute['name'] = "class";
			$arrAttribute['value'] = "textGrow";
			$arrAttributes[] = $arrAttribute;
			$arrTable[] = GetInnerHTML($strHTML, $strTagName, $arrAttributes);

			$strTagName = "tr";
			$arrAttributes = NULL;

			$arrResult = GetInnerHTML($strHTML, $strTagName, $arrAttributes);
			if (!$arrResult) {
				LogError("objParser->GetHTML_PlayerFleet", "GetInnerHTML failed");
				return false;
			}
			for ($i = 1; $i < count($arrResult); $i++) {
				$strHTML = $arrResult[$i];
				$strTagName = "td";
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

			return $arrResult;
		}

		// Returns player technologies (string)
		private function GetHTML_PlayerNames($strInput) {
			$arrResult = NULL;

			$strHTML = $strInput;
			$strTagName = "p";
			$arrAttribute['name'] = "class";
			$arrAttribute['value'] = "start opponents";
			$arrAttributes[] = $arrAttribute;

			$arrResult = GetInnerHTML($strHTML, $strTagName, $arrAttributes);

			if (!$arrResult) {
				LogError("objParser->GetHTML_PlayerNames", "start opponents");
				return false;
			}

			$arrResult = GetOpponentsByTitle($arrResult[0]);

			return $arrResult;
		}
	}
?>