<?php
	class cPlayer {
		
		var $strName = "";
		var $strRole = "";
		var $arrTechnologies = NULL;
		var $arrCoordinates = NULL;
		var $arrRoundFleet = NULL;
		
		function __construct($strName, $strRole, $arrCoordinates, $arrTechnologies) {
			$this->strName = $strName;
			$this->strRole = $strRole;
			$this->arrCoordinates = $arrCoordinates;
			$this->arrTechnologies = $arrTechnologies;
		}
		
		public function Get($strWhat)
		{
			$varReturn = UNDEFINED;
			
			switch (strtolower($strWhat))
			{
				case "name":
					$varReturn = $this->strName;
					break;
				case "technologies":
					$varReturn = $this->arrTechnologies ;
					break;
				case "coordinates":
					$varReturn = $this->arrCoordinates ;
					break;
				case "roundscount":
					$varReturn = count($this->arrRoundFleet);
					break;
				case "roundfleet":
					$varReturn = $this->arrRoundFleet;
					break;
				default:
					LogError("objPlayer->Get", "Unknown input parameter: " . $strWhat);
					break;
			}
			
			return $varReturn;
		}
		
		function Set($strWhat, $varValue) {
			switch (strtolower($strWhat)) {
				case "roundfleet":
						$this->arrRoundFleet[$varValue['intRound']] = $varValue['arrFleet'];
					break;
				default:
					LogError("objPlayer->Get", "Unknown input parameter: " . $strWhat);
					return false;
			}
			
			return true;
		}
	}
?>