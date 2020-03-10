<?php
	// parent class. Used for html log processing. Has child subclasses for x0 and x1 ogame engines
	class cParser {
		// initial attributes
		protected $objLog = null;
		
		// group 1 attributes - objParser gets this attributes himself
		protected $strId = "";
		protected $intUni = 0;
		protected $intUniType = 0;
		protected $strDomain = "";
		
		// group 2 attributes - objParser gets this attributes using objParser_1x or objParser_0x
		protected $objSource = null;
		
		function __construct(&$objLog) {
			$this->objLog = $objLog;
		}
		
		public function Get($strWhat) {
			$varReturn = false;
			switch (strtolower($strWhat)) {
				case "uni":				$varReturn = $this->intUni; break;
				case "domain":			$varReturn = $this->strDomain; break;
				case "source":			$varReturn = $this->objSource; break;
				case "html":			$varReturn = $this->objLog->Get("htmllog"); break;
				case "comment":			$varReturn = $this->objLog->Get("comment"); break;
				case "recyclerreport":	$varReturn = $this->objLog->Get("recyclerreport"); break;
				case "cleanup":			$varReturn = $this->objLog->Get("cleanup"); break;
				case "id":				$varReturn = $this->objLog->Get("logid"); break;
				case "url":				$varReturn = $this->objLog->Get("url"); break;
				case "title":			$varReturn = $this->objSource->strTitle; break;
				case "skin":			$varReturn = $this->objLog->Get("skin"); break;
				case "reportpo":		$varReturn = $this->objLog->Get("reportpo"); break;
				case "music":			$varReturn = $this->objLog->Get("music"); break;
				case "hidetech":		$varReturn = $this->objLog->Get("hidetech"); break;
				case "public":			$varReturn = $this->objLog->Get("public"); break;
				case "hidecoord":		$varReturn = $this->objLog->Get("hidecoord"); break;
				case "hidetime":		$varReturn = $this->objLog->Get("hidetime"); break;
				case "ipms":		    $varReturn = $this->objLog->Get("ipms"); break;
				case "fuel":		    $varReturn = $this->objLog->Get("fuel"); break;
				case "pfuel":		    $varReturn = $this->objLog->Get("pfuel"); break;
				case "ownhtmllog" :     $varReturn = $this->objLog->Get("ownhtmllog"); break;
				case "plugin" :     	$varReturn = $this->objLog->Get("plugin"); break;

				case "active" :     	$varReturn = $this->objLog->Get("active"); break;
				case "loot" :     		$varReturn = $this->objLog->Get("loot"); break;
				case "fleet" :     		$varReturn = $this->objLog->Get("fleet"); break;
				default:				LogError("objParser->Get", "Unknown input parameter: " . $strWhat); break;
			}
			return $varReturn;
		}

		public function GetUni() {
			$intUni = 0;
			if ($this->objLog->Get("useruni")) return $this->objLog->Get("useruni");
			$intUni = GetUniDomain($this->objLog->Get("ownhtmllog"), 'uni');
			if (!$intUni) {
				LogError("objParser->GetUni", "GetUniDomain failed");
				return false;
			}
			return $intUni;
		}
		
		public function Parse() {
			if ($this->objLog->Get("useruni")) {
				$this->intUni = $this->objLog->Get("useruni");
			}
			else {
				$this->intUni = GetUniDomain($this->objLog->Get("ownhtmllog"), 'uni');
			}
			
			$this->intUniType = GetUniType($this->objLog->Get("ownhtmllog"), 'unitype');
//////
			
			
				
			



			if (!$this->intUni) {
				LogError("objParser->__construct", "GetUniDomain failed");
				return false;
			}
			
			if ($this->objLog->Get("userdomain") != "") {
				$this->strDomain = $this->objLog->Get("userdomain");
			}
			else {
				$this->strDomain = GetUniDomain($this->objLog->Get("ownhtmllog"), 'domain');
			}
			
			/*if ($this->intUniType == 0) {
				$objParserEx = new cParser_0x($this);
				//LogError("objParser->Parse", "Processing old logs is under construction, sorry");
				//return false;
			}
			else {
			   	$dataArrayCR = explode("-", $this->objLog->Get("ownhtmllog"));

 	            if ($dataArrayCR[0] == 'cr' && strlen($this->objLog->Get("ownhtmllog")) < 55 && strlen($this->objLog->Get("ownhtmllog")) > 45) {
				    $objParserEx = new cParser_6x($this);
 	            } else {
				    $objParserEx = new cParser_1x($this);
                }
			}*/
			
			$objParserEx = new cParser_7x($this);

			if (!$objParserEx->Parse()) {
				LogError("objParser->Parse", "objParserEx->Parse failed");
				return false;
			}
			
			return true;
		}
	}
?>
