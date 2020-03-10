<?php
	// child class, 1x ogame engine
	class cParser_1x extends cParser {
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
			$objBattle = new cBattle();
			
			$arrRounds = $this->GetHTML_Rounds($this->objParser->objLog->Get("htmllog"));
			
			//$arrLogBox = $this->objLog->Get("box");
			
			$arrPlayerNames = $this->GetHTML_PlayerNames($this->objParser->objLog->Get("htmllog"));
			if (!$arrPlayerNames) {
				LogError("objParser->Parse", "GetHTML_PlayerFleet failed");
				return false;
			}
			
			for ($i = 0; $i < count($arrRounds); $i++)
			{
				$strRoundAttacker = $this->GetHTML_RoundAttacker($arrRounds[$i]);
				$strRoundDefender = $this->GetHTML_RoundDefender($arrRounds[$i]);
				$objBattle->arrRoundInfo[$i] = $this->GetHTML_RoundInfo($arrRounds[$i]);
				
				
				$arrRoundAttackerPlayers = $this->GetHTML_RoundPlayers($strRoundAttacker);
				$arrRoundDefenderPlayers = $this->GetHTML_RoundPlayers($strRoundDefender);

				foreach ($arrRoundAttackerPlayers as $strPlayer) {
					$strPlayerName = GetPlayerNameFromString($this->GetHTML_PlayerName($strPlayer), $arrPlayerNames['arrAttackers']);
					
					if ($i == 0) {
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
				
				foreach ($arrRoundDefenderPlayers as $strPlayer) {
					$strPlayerName = GetPlayerNameFromString($this->GetHTML_PlayerName($strPlayer), $arrPlayerNames['arrDefenders']);
					if ($i == 0) {
						$arrTechnologies = $this->GetHTML_PlayerTechnologies($strPlayer);
						if ($arrTechnologies)
							$arrTechnologies = ConvertTechnologies($arrTechnologies);
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
						$arrInput['intRound'] = 0;
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
							if ($objBattle->arrDefenders[$key]->Get('roundscount') == $i) {
								$objBattle->arrDefenders[$key]->Set('RoundFleet', $arrInput);
								break;
							}
						}
					}
				}
			}
			
			$objBattle->intRoundsCount = count($arrRounds);
				
			//print_r($objBattle);
			//$arrTmp =GetInnerHTML($objLog->strHTMLLog,"title",false);
			//$objBattle->strTitle = $arrTmp[0];
			
			$objBattle->strTitle = $this->GetTitle();
			$objBattle->strStartInfo = $this->GetStartInfo();
			$objBattle->arrCombatResult = $this->GetCombatResult();
			
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
			$objBattle->blnHideTime = $this->objParser->Get("hidetime");

			$objBattle->intIPMs = $this->objParser->Get("ipms");
			$objBattle->blnFuel = $this->objParser->Get("fuel");
			$objBattle->blnPFuel = $this->objParser->Get("pfuel");
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
		
		private function GetTitle() {
			$strReturn = UNDEFINED;
			$arrTmp = NULL;

			$strHTML = $this->objParser->objLog->Get("htmllog");
			$strTagName = "p";
			$arrAttribute['name'] = "class";
			$arrAttribute['value'] = "start opponents";
			$arrAttributes[] = $arrAttribute;
			
			$arrTmp = GetInnerHTML($strHTML, $strTagName, $arrAttributes);
			
			if (!$arrTmp) {
				LogError("objParser->GetTitle", "GetInnerHTML failed");
				return -1;
			}

			$strReturn = trim($arrTmp[0]);

            function GetID ($strName, $strUni, $strDomain) {
                $url = 'http://s'.$strUni.'-'.$strDomain.'.ogame.gameforge.com/api/players.xml';
                $flashRAW = file_get_contents($url);
                $flashXML = simplexml_load_string($flashRAW);

                $xmlFile = 'xml/'.$strUni.'-'.$strDomain.'_players.xml';

                if ((time() - filectime($xmlFile)) >= 7 * 24 * 3600 || !file_exists($xmlFile)) {
                    $xmlHandle = fopen($xmlFile, "w");
                    $xmlString = $flashXML->asXML();
                    fwrite($xmlHandle, $xmlString);
                    fclose($xmlHandle);
                }

                $xml = simplexml_load_file($xmlFile);

                foreach ($xml->children() as $players) {
                    if ($players['name'] == $strName){
                        $allianceID = trim($players['alliance']);
                        $playerID = trim($players['id']);
                        break;
                    }
                }
                $varResult["playerID"] = $playerID;
                $varResult["allianceID"] = $allianceID;
                return $varResult;
            }

            function GetTOP ($strId, $strUni, $strDomain) {
                $url = 'http://s'.$strUni.'-'.$strDomain.'.ogame.gameforge.com/api/highscore.xml?category=1&type=3';
                $flashRAW = file_get_contents($url);
                $flashXML = simplexml_load_string($flashRAW);

                $xmlFile = 'xml/'.$strUni.'-'.$strDomain.'_highscore.xml';

                if ((time() - filectime($xmlFile)) >= 3600 || !file_exists($xmlFile)) {
                    $xmlHandle = fopen($xmlFile, "w");
                    $xmlString = $flashXML->asXML();
                    fwrite($xmlHandle, $xmlString);
                    fclose($xmlHandle);
                }

                $xml = simplexml_load_file($xmlFile);

                foreach ($xml->children() as $players) {
                    if ($players['id'] == $strId){
                        $playerTOP = trim($players['position']);
                        break;
                    }
                }
                return $playerTOP;
            }

            function GetTags ($strName, $strUni, $strDomain) {
                $arrID = GetID ($strName, $strUni, $strDomain);
                $playerTOP = GetTOP ($arrID['playerID'], $strUni, $strDomain);
                $url = 'http://s'.$strUni.'-'.$strDomain.'.ogame.gameforge.com/api/alliances.xml';
                $flashRAW = file_get_contents($url);
                $flashXML = simplexml_load_string($flashRAW);

                $xmlFile = 'xml/'.$strUni.'-'.$strDomain.'_alliances.xml';

                if ((time() - filectime($xmlFile)) >= 24 * 3600 || !file_exists($xmlFile)) {
                    $xmlHandle = fopen($xmlFile, "w");
                    $xmlString = $flashXML->asXML();
                    fwrite($xmlHandle, $xmlString);
                    fclose($xmlHandle);
                }

                $xml = simplexml_load_file($xmlFile);

                foreach ($xml->children() as $alliance) {
                    if ($alliance['id'] == $arrID['allianceID'])
                    {
                        $allianceTag = trim($alliance['tag']);
                        break;
                    }
                }
                $varResult['allianceTag'] = $allianceTag;
                $varResult['playerTOP'] = $playerTOP;
                return $varResult;

            }

			$intUni = strtolower ($this->objParser->objLog->Get("uni"));
			$strDomain = strtolower ($this->objParser->objLog->Get("domain"));

            if ($strDomain != 999 && ($_COOKIE['index_cbx_aliance'] == "true" || !isset($_COOKIE["index_cbx_aliance"]))) {
            $intTitle = split("vs.", $strReturn);
                $arrAttackers = split(",", $intTitle[0]);
                $arrDefenders = split(",", $intTitle[1]);
                    foreach($arrAttackers as $arrPlayer) {
                        $varSTR = '';
                        $arrPlayer = trim($arrPlayer);
                        $GetTags = GetTags ($arrPlayer, $intUni, $strDomain);
                        if ($GetTags['allianceTag']) {
                            $varSTR = '[' . $GetTags['allianceTag'] . '] ';
                        }
                        $varSTR .= $arrPlayer;
                        if ($GetTags['playerTOP'] && $_COOKIE['index_cbx_top'] == "true") {
                            $varSTR .= ' #' . $GetTags['playerTOP'];
                        }

                        $intTitle[0] = str_replace($arrPlayer, $varSTR, $intTitle[0]);
                    }
                if ($intTitle[1]) {
                    foreach($arrDefenders as $arrPlayer) {
                        $varSTR = '';
                        $arrPlayer = trim($arrPlayer);
                        $GetTags = GetTags ($arrPlayer, $intUni, $strDomain);
                        if ($GetTags['allianceTag']) {
                            $varSTR = '[' . $GetTags['allianceTag'] . '] ';
                        }
                        $varSTR .= $arrPlayer;
                        if ($GetTags['playerTOP'] && $_COOKIE['index_cbx_top'] == "true") {
                            $varSTR .= ' #' . $GetTags['playerTOP'];
                        }

                        $intTitle[1] = str_replace($arrPlayer, $varSTR, $intTitle[1]);
                    }
                } else $intTitle[1] = "space";
            $strReturn = $intTitle[0].' vs. '.$intTitle[1];
            }

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