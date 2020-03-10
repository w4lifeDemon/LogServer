<?php
	class cLog {
		// Input data
		private $strHTMLLog = "";
		private $strRecyclerReport = "";
		private $strComment = "";
		private $strCleanUp = "";

		private $blnPublic = false;
		private $blnHideCoord = false;
		private $blnHideTech = false;
		private	$blnHideTime = false;
		private $intUserUni = 0;
		private $strUserDomain = "";
		private $intSkin = 0;
        private $strReportPO = "Attacker";
		private $strMusic = "";
		private $intIPMs = 0;
		private $blnFuel = false;
		private $blnPFuel = 1;
		private $bln_post = false;
		private $intPlugin = 0;

		// exp
		private $intActive = 0;
		private $intLoot = 0;
		private $intFleet = 0;
		
		// System data
		private $strStore = "d"; // "d" ~ DB, "f" ~ file
		private $strVersion = "0"; // "0" ~ 0.x, "1" ~ 1.x, "2" ~ 0.x (source), "3" ~ 1.x (source), "s" ~ spy report
		private $strNotUsed1 = "0";
		private $strNotUsed2 = "0";

		// Processed data
		private $objParser;
		private $objHTMLConstructor;
		private $strLogId = "";
		private $strZipLog = "";
		private $blnBackupExists = false;

		// made by Zmei
		private $ownHtmllog ="";
		private $zipown ="";
		private $blnUpd = false;

	
		public function __construct($strInput, $strCommand) {
			if ($strCommand == 'take') {
				$this->__construct_take($strInput);
				return;
			}
			if ($strCommand == 'get') {
				$this->__construct_get($strInput);
				return;
			}
			if ($strCommand == 'empty') {
				return true;
			}
			if ($strCommand =="edit") {
			$this->__construct_get($strInput);
			$blnUpd = true;
			}
		}
		
		public function __construct_take($strInput) {
			if (strlen($strInput) == 32 + 4) {
				$this->strLogId = $strInput;
				$this->strStore = substr($this->strLogId, 0, 1);
				$this->strVersion = substr($this->strLogId, 1, 1);
				$this->strNotUsed1 = substr($this->strLogId, 2, 1);
				$this->strNotUsed2 = substr($this->strLogId, 3, 1);
				return true;
			}
			else{
				LogError("objLog->__construct_take", "Wrong strInput length, strInput = " . $strInput);
				return false;
			}
		}
		
		public function __construct_get(&$strInput) {
			// strHTMLLog
			/*if (trim($strInput['log_taken']) != md5($_SESSION['secpic'])) {
				$strInput['log_textarea'] = false;
				LogError($strInput['log_taken'], md5($_SESSION['secpic']));
			}*/
			if (!$strInput['log_textarea']) {
				LogError("objLog->__construct_take", "Log textarea is empty");
				return false;
			}
			else {
//made by Zmei	
				$this->ownHtmllog =	stripslashes($strInput['log_textarea']);
				$this->strHTMLLog = stripslashes(trim($strInput['log_textarea']));
//--
			}
			$this->strHTMLLog = GarbageFix($this->strHTMLLog);
			// Spy report case
			if ($strInput['rb_spy_report'] == "V2") {
				$this->strVersion = "s";
				if (strlen($this->strHTMLLog) > MAX_LOG_SIZE / 10) {
					LogError("objLog->CheckInput", "Uploaded spy report is too long: " . strlen($this->strHTMLLog) . "/" . MAX_LOG_SIZE / 10);
					return false;
				}
				$varSRId = trim($this->strHTMLLog);

				$varResultSR = apiSR ($varSRId, false);
				if ($varResultSR) {
	    			if ($strInput['comment_textarea']) {
	    			    $this->strComment = trim($strInput['comment_textarea']);
	    			}
	    			$varResultSR->comment = $this->strComment; 					
					$this->strTitle = $varResultSR->generic->defender_planet_name;
					
					$data_array = explode("-", $varSRId);
	            	$this->strDomain = $data_array[1];
	            	$varResultSR->strDomain = $data_array[1];
	            	$this->strUni = $data_array[2];
	            	$varResultSR->strUni = $data_array[2];

	            	$this->intActive = $varResultSR->generic->activity;

	            	$this->intLoot = $varResultSR->details->resources->metal + $varResultSR->details->resources->crystal + $varResultSR->details->resources->deuterium;

					if (!$varResultSR->generic->failed_ships) {
		            	foreach ($varResultSR->details->ships as $key => $ships) {
		            		$this->intFleet += array_sum(GetBaseCost($ships->ship_type)) * $ships->count;
		            	}
					}

					$this->strHTMLLog = $varResultSR;
				} else {
					LogError("objLog->__construct_take", "Log textarea is wrong");
					return false;	            	
	            }

                return true;
			}
			/*
			if ($strInput['protect'] != $_SESSION["protect"]) {
				exit("Protect is wrong");
			}
			*/
			// strRecyclerReport
			if ($strInput['recycler_textarea']) {
			    $this->strRecyclerReport = "";
			    if (isset($strInput['cbx_recycler'])) $this->strRecyclerReport = "*";
			    else foreach ($strInput['recycler_textarea'] as $value) {
                    $varResult = apiRR ($value, false);
                     if ($varResult) {
                    	if ($varResult->generic->recycler_count > 0) {
                    		$intRecText = "Переработчики";
                    		$intRecCont = $varResult->generic->recycler_count;
                    		$intRecCapacity = $varResult->generic->recycler_capacity;
                    	} else {                    		
                    		$intRecText = "Жнецы";
                    		$intRecCont = $varResult->generic->reaper_count;
                    		$intRecCapacity = $varResult->generic->reaper_capacity;
                    	}
                    	$this->strRecyclerReport .= "<!-- id:" . $value . " -->" . $intRecText . " в количестве " . NumberToString($intRecCont) . " штук обладают общей грузоподъёмностью в " . NumberToString($intRecCapacity) . ". Поле обломков содержит " . NumberToString($varResult->generic->metal_in_debris_field) . " металла и " . NumberToString($varResult->generic->crystal_in_debris_field) . " кристалла. Добыто " . NumberToString($varResult->generic->metal_retrieved) . " металла и " . NumberToString($varResult->generic->crystal_retrieved) . " кристалла. \n";
                    }
                }
			}

			// strComment
			if ($strInput['comment_textarea']) {
			    $this->strComment = trim($strInput['comment_textarea']);
			}
			// strCleanUp
			if ($strInput['clean_up_textarea']) {
			    $this->strCleanUp = "";
			    foreach ($strInput['clean_up_textarea'] as $value) {
                    $varResult = apiCR ($value, false);
                    if ($varResult) $this->strCleanUp .= "<!-- id:".$value." --><!-- title_clean_ap -->: " . NumberToString($varResult->generic->loot_metal) . " <!-- metal -->, " . NumberToString($varResult->generic->loot_crystal) . " <!-- crystal --> <!-- and --> " . NumberToString($varResult->generic->loot_deuterium) . " <!-- deuterium -->.\n";
                }
			}
			// Public
			if ($strInput['cbx_public'])
				$this->blnPublic = 1;
			else $this->blnPublic = 0;
			// Hide coordinates
			if ($strInput['cbx_hide_coord'])
                $this->blnHideCoord = true;
			// Hide technologies
			if ($strInput['cbx_hide_tech'])
				$this->blnHideTech = true;
			//Скрытие времени
			if ($strInput['cbx_hide_time'])
				$this->blnHideTime = true;
			if ($strInput['cbx_comments'])
			      $this->bln_post = true;
			// Lang from plugin
			if ($strInput['lang']) {
				switch (strtolower($strInput['lang'])) {
					case "bg": $_SESSION["lang"] = "bg"; break;
					case "de": $_SESSION["lang"] = "de"; break;
					case "en": $_SESSION["lang"] = "en"; break;
					case "ru": $_SESSION["lang"] = "ru"; break;
					case "ua": $_SESSION["lang"] = "ua"; break;
					default: $_SESSION["lang"] = "en"; break;
				}
			}
			// User uni
			if (isset($strInput['plugin_user_key']))
				$this->intPlugin = 1;
			if ($strInput['select_uni'] != 0)
				$this->intUserUni = $strInput['select_uni'];
			// User domain
			if ($strInput['select_domain'] != "0")
				$this->strUserDomain = $strInput['select_domain'];
			// Skin
			if ($strInput['select_skin']) {
				$this->intSkin = $strInput['select_skin'];
				if (isset($_SESSION['account']['id']) && $_SESSION['account']['id'] == 278) $this->intSkin = "zapio";
			}

    		if ($strInput['rb_rec_report'] == "V1")
                $this->strReportPO = "Attacker";
    		if ($strInput['rb_rec_report'] == "V2")
                $this->strReportPO = "Defender";

			if (!$this->ReplaceQuotes()) {
				LogError("objLog->__construct_get", "this->ReplaceQuotes failed");
				return false;
			}
			// IPMs
			if (isset($strInput['cbx_ipm']))
				$this->intIPMs = (integer) $strInput['text_ipm'];
			if (isset($strInput['cbx_fuel']))
				$this->blnFuel = (bool) $strInput['cbx_fuel'];
			if (isset($strInput['select_p_fuel']))
				$this->blnPFuel = $strInput['select_p_fuel'];

			if (isset($strInput['music_input'])){
				$this->strMusic = trim($strInput['music_input']);
			}
			// Checking...
			if (!$this->CheckInput()) {
				LogError("objLog->__construct_get", "this->CheckInput failed");
				return false;
			}
			// Edit ID
			if (isset($strInput['edit'])){
				$this->blnUpd = true;
				$this->strLogId = base64_decode($strInput['edit']);
				}
			if (!$this->DeleteBackSplashes()) {
				LogError("objLog->__construct_get", "this->DeleteBackSplashes failed");
				return false;
			}
			
			if (!$this->DeleteLinks()) {
				LogError("objLog->__construct_get", "this->DeleteLinks failed");
				return false;
			}
			if (!$this->DeleteSession()) {
				LogError("objLog->__construct_get", "this->DeleteSession failed");
				return false;
			}
			return true;
		}
	
		public function Edit() {
			if (!$this->strLogId) {
				LogError("objLog->LoadZipLog2", "Log id is empty");
				return false;
			}

				$strReadResult = cDB::EditLogByID($this->strLogId);
			
			if (!$strReadResult) {
				LogError("objLog->SaveZipLog2", "cDB::LoadLogByID failed (h_log)");
				return false;
			}
			if($strReadResult['html_log'] != ""){
			 $this->zipown = $strReadResult['html_log'] ;
			}
			$this->strZipLog = $strReadResult['obj_log'];
			if ($this->zipown != ""){
		        $this->ownHtmllog = gzuncompress($this->zipown);
		        }

			if (($this->strVersion == "0") || ($this->strVersion == "1"))
				$this->strHTMLLog = gzuncompress($this->strZipLog);
			if (($this->strVersion == "2") || ($this->strVersion == "3") || ($this->strVersion == "6") || ($this->strVersion == "7")) {
				$this->objParser = unserialize(gzuncompress($this->strZipLog));
			}
			return true;
		}
		
//
		public function Process() {
			// Spy report case
			if ($this->strVersion == "s") {
				$this->strLogId = ("f" . $this->strVersion . "0" . "0" . md5(time()."ls"));
				return true;
			}
			// Start parsing...
			$this->objParser = new cParser($this);
			
			$intUni = $this->objParser->GetUni();

			if (!$intUni) {
				LogError("objLog->Process", "objParser->GetUni failed");
				return false;
			}
			
			$this->strStore = "d";
			$this->strNotUsed1 = "0";
			$this->strNotUsed2 = "0";
			//if ($this->strVersion != "3") ($intUni < 101) ? ($this->strVersion = "2") : ($this->strVersion = "6");
			$this->strVersion = "7";
// made by Zmei
			if(!$this->blnUpd) {
				$this->strLogId = $this->GetId();
			}
			if (!$this->objParser->Parse()) {
				LogError("objLog->Process", "objParser->Parse failed");
				return false;
			}
			if ($this->strVersion == 6) $this->objHTMLConstructor = new cHTMLConstructor_6x($this->objParser->Get("source"));
			else if ($this->strVersion == 7) $this->objHTMLConstructor = new cHTMLConstructor_7x($this->objParser->Get("source"));
			else $this->objHTMLConstructor = new cHTMLConstructor($this->objParser->Get("source")); 
			
			if (!$this->objHTMLConstructor->Construct()) {
				LogError("objLog->Process", "objHTMLConstructor->Construct failed");
				return false;
			}
			
			if (DBG_FLAG_PROCESS_RESULT) {
				echo $this->objHTMLConstructor->Get("html");
				exit;
			}
			
			return true;
		}
		
		//#region PUBLIC
		
		public function Get($strWhat) {
			$varReturn = UNDEFINED;
			
			switch (strtolower($strWhat)) {
				// Input data
				case "htmllog":		$varReturn = $this->strHTMLLog; break;
				case "comment":		$varReturn = $this->strComment;	break;
				case "recyclerreport":	$varReturn = $this->strRecyclerReport; break;
				case "cleanup":		$varReturn = $this->strCleanUp; break;
				case "public":		$varReturn = $this->blnPublic; break;
				case "hidecoord":	$varReturn = $this->blnHideCoord; break;
				case "hidetech":	$varReturn = $this->blnHideTech; break;
				case "hidetime":	$varReturn = $this->blnHideTime; break;
				case "useruni":		$varReturn = $this->intUserUni; break;
				case "userdomain":	$varReturn = $this->strUserDomain; break;
				case "skin":		$varReturn = $this->intSkin; break;
				case "reportpo":	$varReturn = $this->strReportPO; break;
				case "ipms":		$varReturn = $this->intIPMs; break;
				case "fuel":		$varReturn = $this->blnFuel; break;
				case "pfuel":		$varReturn = $this->blnPFuel; break;
				case "ownhtmllog" : $varReturn = $this->ownHtmllog; break;
				case "zown" 	:	$varReturn = $this->zipown; break;
				case "objsource" :  $varReturn = $this->objParser; break;
				case "music"	:	$varReturn = $this->strMusic; break;
				case "blnpost"	:	$varReturn = $this->bln_post; break;
				case "plugin":		$varReturn = $this->intPlugin; break;

				case "active":		$varReturn = $this->intActive; break;
				case "loot":		$varReturn = $this->intLoot; break;
				case "fleet":		$varReturn = $this->intFleet; break;

				// New
				case "logid":		$varReturn = $this->strLogId; break;
				case "url":			$varReturn = LOGSERVERURL . "?id=" . $this->strLogId; break;
				case "url2":		$varReturn = ALTLOGSERVERURL . "?id=" . $this->strLogId; break;
				case "htmlnew":		$varReturn = $this->objHTMLConstructor->Get("html"); break;
				case "uni":			if ($this->strVersion == 6 || $this->strVersion == 7) $varReturn = $this->objHTMLConstructor->Get("uni"); else $varReturn = $this->objParser->Get("uni"); break;
				case "domain":		if ($this->strVersion == 6 || $this->strVersion == 7) $varReturn = $this->objHTMLConstructor->Get("domain"); else $varReturn = $this->objParser->Get("domain"); break;
				case "domain":		$varReturn = $this->objParser->Get("domain"); break;
				case "longtitle":	$varReturn = $this->objHTMLConstructor->Get("longtitle"); break;
				case "title":		$varReturn = $this->objHTMLConstructor->Get("title"); break;
				case "losses":		$varReturn = $this->objHTMLConstructor->Get("losses"); break;
				case "bburl":		$varReturn = $this->objHTMLConstructor->Get("bburl"); break;
				case "bburl2":		$varReturn = str_replace(LOGSERVERURL, ALTLOGSERVERURL, $this->objHTMLConstructor->Get("bburl")); break;
				case "bbcode":		$varReturn = $this->objHTMLConstructor->Get("bbcode"); break;
				case "profit":		$varReturn = $this->objHTMLConstructor->Get("profit"); break;
				case "bbcode2":		$varReturn = str_replace(LOGSERVERURL, ALTLOGSERVERURL, $this->objHTMLConstructor->Get("bbcode")); break;
				case "ziplog":		$varReturn = $this->strZipLog; break;
				case "backupexists":	$varReturn = $this->blnBackupExists; break;
				case "version":		$varReturn = $this->strVersion; break;
				
				// Used
				case "tmplogscount":
					$varReturn = $this->GetLogsCount($arrIds, FOLDER_UPLOAD_TMP) - 1;
					break;
				case "logscount":
					$varReturn = NumberToString(cDB::GetLogsCount('ALL'));
					break;
				case "errlogscount":
					$varReturn = $this->GetLogsCount($arrIds, FOLDER_UPLOAD_ERR) - 1;
					break;
				case "spylogscount":
					$varReturn = $this->GetLogsCount($arrIds, FOLDER_UPLOAD_SPY) - 1;
					break;

				
					
				default:
					LogError("objLog->Get", "Unknown input parameter: ".$strWhat);
					break;
			}
			
			return $varReturn;
		}
		
		public function Save() {
			// Spy report case
			if ($this->strVersion == "s") {
				return true;
                //edit
			}
			if (!$this->strLogId) {
				LogError("objLog->Save", "Empty strId");
				return false;
			}
			if (($this->strStore == "f") || ($this->strStore == "d")) {
				if (!$this->Encode()) {
					LogError("objLog->Save", "this->Encode failed");
					return false;
				}
				if ($this->strStore == "f")
					if (!$this->SaveZipLog()) {
						LogError("objLog->Save", "this->SaveZipLog2 falied");
						return false;
					}
				if ($this->strStore == "d") {
					if ($this->blnUpd) {
						$intUserID = GetUserIDFromSession();
			
						if (!cDB::EditLog_save($intUserID, $this)) {
							LogError("objLog->Uptade", "cDB::SaveLog failed");
							return false;
						}
			            return true;
					}
					if (!$this->SaveZipLog2()) {
						LogError("objLog->Save", "this->SaveZipLog falied");
						return false;
					}
				}
			}
			else {
				LogError("objLog->Save", "Wrong strStore: ".$this->strStore);
				return false;
			}
			
			return true;
		}

		public function SaveBackup() {
			// Spy report case
			if ($this->strVersion == "s") {
				return true;
			}
			if (!$this->strLogId) {
				LogError("objLog->SaveBackup", "Empty strId");
				return false;
			}

			error_reporting(0);
			if ($this->strStore == "f") {
				$blnResult = BackupObject($this->strLogId);
			}
			
			if ($this->strStore == "d") {
				$intUserID = GetUserIDFromSession();
				$blnResult = cDB::SaveLogSpec($intUserID, $this);
			}
			error_reporting(E_ALL);
			
			if (!$blnResult) {
				LogError("objLog->SaveBackup", "BackupObject failed");
				return false;
			}

			$this->blnBackupExists = true;
			return true;
		}
		
		public function Load() {
			if (($this->strStore == "f") || ($this->strStore == "d")) {
				if ($this->strStore == "f")
					if (!$this->LoadZipLogF()) {
						LogError("objLog->Load", "this->LoadEspByID falied");
						return false;
					}
				if ($this->strStore == "d")
					if (!$this->LoadZipLog2()) {
					LogError("objLog->Load", "this->LoadZipLog2 falied");
					return false;
				}
				if (!$this->Decode()) {
					LogError("objLog->Load", "this->Decode falied");
					return false;
				}
			}
			else {
				LogError("objLog->Load", "Wrong strStore: ".$this->strStore);
				return false;
			}
			
			return true;
		}
		
		//$endregion
		
		private function Encode() {
			if($this->ownHtmllog != ""){
			$this->zipown = gzcompress($this->ownHtmllog);
			}
			if ($this->strVersion == "s")
				$this->strZipLog = gzcompress(serialize($this->strHTMLLog));
			else
				$this->strZipLog = gzcompress(serialize($this->objParser->Get("source")));
			return true;
		}
		
		private function Decode() {
			if ($this->zipown != ""){
		        $this->ownHtmllog = gzuncompress($this->zipown);
		        }
			if (($this->strVersion == "0") || ($this->strVersion == "1"))
				$this->strHTMLLog = gzuncompress($this->strZipLog);
			if (($this->strVersion == "2") || ($this->strVersion == "3") || ($this->strVersion == "6") || ($this->strVersion == "7")) {
				$objSource = unserialize(gzuncompress($this->strZipLog));
				$objSource->bln_post = $this->bln_post;
				if ($this->strVersion == 6) $this->objHTMLConstructor = new cHTMLConstructor_6x($objSource);
				else if ($this->strVersion == 7) $this->objHTMLConstructor = new cHTMLConstructor_7x($objSource);
				else $this->objHTMLConstructor = new cHTMLConstructor($objSource); 
				if (!$this->objHTMLConstructor->Construct()) {
					LogError("objLog->Decode", "objHTMLConstructor->Construct failed");
					return false;
				}
				$this->strHTMLLog = $this->objHTMLConstructor->Get("html");
			}
			if ($this->strVersion == "s") {
				$this->strHTMLLog = PrepareSpyReport(unserialize(gzuncompress($this->strZipLog)));
			}
			return true;
		}
		
		private function GetId() {
			//return ($this->strStore . $this->strVersion . $this->strNotUsed1 . $this->strNotUsed2 . md5($this->strHTMLLog . $this->strRecyclerReport . $this->strComment . $this->strCleanUp . $this->blnPublic . $this->blnHideCoord . $this->blnHideTech));
			
			$intUserID = GetUserIDFromSession();
			
			return ($this->strStore . $this->strVersion . $this->strNotUsed1 . $this->strNotUsed2 . md5($this->strHTMLLog.time()."ls"));
		}
		
		private function SaveZipLog() {
			if (!$this->CheckDir(FOLDER_UPLOAD)) {
				LogError("objParser->SaveZipLog", "CheckDir failed");
				return false;
			}
			if ($this->Get("logscount") >= MAX_STORAGE) {
				LogError("objParser->SaveZipLog", "Storage limit exceed: ".MAX_STORAGE);
				return false;
			}
			$strLogFile = FOLDER_UPLOAD."/".$this->strLogId;
			if (!file_exists($strLogFile)) {
				if (!file_put_contents($strLogFile, $this->strZipLog)) {
					LogError("objParser->SaveZipLog", "file_put_contents failed");
					return false;
				}
				$arrListFiles = array(FOLDER_UPLOAD . "/public_list.txt", FOLDER_UPLOAD . "/all_list.txt");
				foreach ($arrListFiles as $strListFile) {
					if (($this->blnPublic && ($strListFile == $arrListFiles[0])) || ($strListFile == $arrListFiles[1])) {
						$strOut = "id=<".$this->Get('logid')."> domain=<".$this->Get('domain')."> uni=<".$this->Get('uni')."> title=<".$this->Get("longtitle")."> time=<".time().">\n";
						if (!file_exists($strListFile)) {
							if (!file_put_contents($strListFile, $strOut)) {
								LogError("objParser->SaveZipLog", "Can't write ".$strListFile);
								return false;
							}
						}
						else {
							$fp = fopen($strListFile, 'a+');
							fwrite($fp, $strOut);
							fclose($fp);
						}
					}
				}
				IncReportsCount(1);
				IncReportsCount24(1);
			}
			
			return true;
		}
		
		private function SaveZipLog2() {
			$intUserID = GetUserIDFromSession();
			
			if (!cDB::SaveLog($intUserID, $this)) {
				LogError("objLog->SaveZipLog2", "cDB::SaveLog failed");
				return false;
			}
			return true;
		}
		
		public function SaveErrLog() {
			if (!$this->CheckDir(FOLDER_UPLOAD_ERR)) {
				LogError("objParser->SaveErrLog", "this->CheckDir failed");
				return false;
			}
			if ($this->Get("errlogscount") >= MAX_ERR_STORAGE) {
				if (!$this->DeleteElderLog(FOLDER_UPLOAD_ERR)) {
					LogError("objParser->SaveErrLog", "this->DeleteElderLog failed");
					return false;
				}
			}
			$strHTML = substr($this->Get("htmllog"), 0, MAX_LOG_SIZE);
			$fileName = random_string(7, "lower,numbers,numbers"); 
			$strLogFile = FOLDER_UPLOAD_ERR."/".$fileName;
			if (!file_exists($strLogFile)) {
					if ($strHTML) {
					if (!file_put_contents($strLogFile, $strHTML)) {
						LogError("objParser->SaveErrLog", "file_put_contents failed");
						return false;
					}
					$strErrListFile = FOLDER_UPLOAD_ERR . "/err_list.txt";
					$strOut = "id=<".$fileName."> err_serialize=<".serialize(GetErrStack()).">\n";
					if (!file_exists($strErrListFile)) {
						if (!file_put_contents($strErrListFile, $strOut)) {
							LogError("objParser->SaveErrLog", "file_put_contents failed");
							return false;
						}
					}
					else {
						$fp = fopen($strErrListFile, 'a+');
						fwrite($fp, $strOut);
						fclose($fp);
						LogError("objLog->SaveErrLog", 
							"Object saved, code = <font face='Arial' color='" . RED_LIGHT ."'>" . $fileName . "</font> (pasting it to the developers will help to resolve problem quicker)
							<br>
							<br>
							<div id='result_send'>Name: <input id='name' value=''> Email/skype: <input id='email' value=''> <input type='hidden' id='err' value='" . $fileName . "'> <input id='submit' type='button' value='Сообщить немедленно'></div><div id='result_err'><br></div>
							<script>
								$('#submit').click(function() {
									if ($('#name').val() != '' && $('#email').val() != '') {
		                                $.ajax({
	                                    	url: 'h_ajax.php?page=send&name=' + $('#name').val() + '&email=' + $('#email').val() + '&err=' + $('#err').val(),
	                                    	cache: false,
	                                    	success: function(response) {
	                                        	if (response) {
	                                            	$('#result_send').html(response);
	                                        	} else {
	                                            	$('#result_send').html('<span style=\'color: #FF0000\'>Error Message not sent</span>');
	                                				$('#result_err').html('');
	                                        	}
	                                        }
	                                    });
									} else {
	                                	$('#result_err').html('<span style=\'color: #FF0000\'>Error Name or Email/skype - empty</span>');
									}
								});
							</script>
							");
						$data = array("content" => $fileName . "\n" . $_SERVER["REMOTE_ADDR"] . "\n" . $_SERVER["HTTP_USER_AGENT"] . "```\n" . $strHTML . "\n```");                                                                    
			            $jsonData = json_encode($data);

			            curlSendDiscord ("https://discordapp.com/api/webhooks/605013628940189736/BbYtZufs70CcRJsMUahqCDpg_FYkJprvKow1wk3KMDuP1D4-h4WBXDlD08PpO0ZANwsH", $jsonData);						
					}
				}
			}
			else {
				LogError("objLog->SaveErrLog", "Object have already been saved, md5 = " . md5($strHTML) . " (pasting it to the developers will help to resolve problem quicker)");
			}
			return true;
		}
		
		public function SaveTempLog() {
			// Spy report case
			if ($this->strVersion == "s") {
				if (!$this->Encode()) {
					LogError("objLog->SaveTempLog", "this->Encode failed");
					return false;
				}
				$intUserID = GetUserIDFromSession();
				cDB::SaveEsp($intUserID, $this);
				return true;
				/*
				$strFolder = FOLDER_UPLOAD_SPY;
				$intMaxSize = MAX_SPY_STORAGE;
				$strLogsCountStr = "spylogscount";
				$strListFileName = "spy_list.txt";
                $intUserID = GetUserIDFromSession();
				$strOut = "id=<".$this->Get('logid')."> user_id=<" . $intUserID . "> title=<".$this->strTitle."> uni=<".$this->strUni."> domain=<".$this->strDomain."> time=<".time().">\n";
				$strObjectToSave = $this->Get("ziplog");
				*/
			}
			else {
				$strFolder = FOLDER_UPLOAD_TMP;
				$intMaxSize = MAX_TMP_STORAGE;
				$strLogsCountStr = "tmplogscount";
				$strListFileName = "tmp_list.txt";
				$strOut = "id=<".$this->Get('logid')."> title=<".$this->Get('longtitle')."> time=<".time().">\n";
				$strObjectToSave = $this->Get("htmllog");
			}
			
			if (!$this->CheckDir($strFolder)) {
				LogError("objLog->SaveTempLog", "this->CheckDir failed");
				return false;
			}
			if ($this->Get($strLogsCountStr) >= $intMaxSize) {
				if (!$this->DeleteElderLog($strFolder)) {
					LogError("objLog->SaveTempLog", "this->DeleteElderLog failed");
					return false;
				}
			}
			$strLogFile = $strFolder . "/" . $this->Get('logid');
			if (!file_exists($strLogFile)) {
				if (!file_put_contents($strLogFile, $strObjectToSave)) {
					LogError("logid", $strObjectToSave);
					LogError("objLog->SaveTempLog", "file_put_contents failed");
					return false;
				}
				$strListFile = $strFolder . "/" . $strListFileName;
				if (!file_exists($strListFile)) {
					if (!file_put_contents($strListFile, $strOut)) {
						LogError("objLog->SaveTempLog", "file_put_contents failed");
						return false;
					}
				}
				else {
					$fp = fopen($strListFile, 'a+');
					fwrite($fp, $strOut);
					fclose($fp);
				}
			}
			return true;
		}
		
		private function LoadZipLog() {
			if (!$this->strLogId) {
				LogError("objParser->LoadZipLog", "Log id is empty");
				return false;
			}
			if ($this->strVersion == "s") {
				$strFolder = FOLDER_UPLOAD_SPY;
			}
			else {
				$strFolder = FOLDER_UPLOAD;
			}
			if ($this->strStore == "f") {
				if (!$this->CheckDir($strFolder)) {
					LogError("objParser->LoadZipLog", "Upload directory error");
					return false;
				}
				if (!file_exists($strFolder."/".$this->strLogId)) {
					LogError("objParser->LoadZipLog", "Log file was not found");
					return false;
				}
				
				$strReadResult = file_get_contents($strFolder."/".$this->strLogId);
				if (!$strReadResult) {
					LogError("objParser->LoadZipLog", "Can't read file");
					return false;
				}
				$this->strZipLog = $strReadResult;
			}
			else {
				LogError("objParser->LoadZipLog", "Can't load from database");
				return false;
			}
			
			return true;
		}
		
		private function LoadZipLog2() {
			if (!$this->strLogId) {
				LogError("objLog->LoadZipLog2", "Log id is empty");
				return false;
			}
			if (THIS_IS_BACKUP_SYSTEM) {
				$strReadResult = cDB::LoadLogByIDSpec($this->strLogId);
			}
			else {
				$strReadResult = cDB::LoadLogByID($this->strLogId, "T_LOGS_N");
			}
			/*
			if (!$strReadResult) {
				$strReadResult = cDB::LoadLogByID($this->strLogId, "T_LOGS");
			    if (!$strReadResult) {
    				LogError("objLog->LoadZipLog2", "cDB::LoadLogByID failed");
    				return false;
                }
			}
			*/
			$this->strZipLog = $strReadResult['obj_log'];
			if ($strReadResult['bln_post'] == 0 ){
				$this->bln_post = false;
			} else {
				$this->bln_post = true;
			}
			return true;
		}
		private function LoadZipLogF() {
			if (!$this->strLogId) {
				LogError("objLog->LoadZipLogF", "Log id is empty");
				return false;
			}
			
			$strReadResult = cDB::LoadEspByID($this->strLogId);

			$this->strZipLog = $strReadResult['obj_log'];
			return true;
		}
		
		private function CheckDir($strDir) {
			if (!file_exists($strDir))
				mkdir($strDir);
			return true;
		}
		
		private function CheckInput() {
			if (!$this->strHTMLLog) {
				LogError("objLog->CheckInput", "Uploaded HTML-code is empty");
				return false;
			}			
			if (strlen($this->strHTMLLog) > MAX_LOG_SIZE) {
				LogError("objLog->CheckInput", "Uploaded HTML-code is too long: ".strlen($this->strHTMLLog)."/".MAX_LOG_SIZE);
				return false;
			}			
			if (strlen($this->strRecyclerReport) > MAX_LOG_SIZE / 25) {
				LogError("objLog->CheckInput", "Uploaded recycler report is too long: ".strlen($this->strRecyclerReport)."/".MAX_LOG_SIZE / 25);
				return false;
			}
			if (strlen($this->strComment) > MAX_LOG_SIZE / 50) {
				LogError("objLog->CheckInput", "Uploaded comment is too long: ".strlen($this->strComment)."/".MAX_LOG_SIZE / 50);
				return false;
			}
			if (strlen($this->strCleanUp) > MAX_LOG_SIZE / 25) {
				LogError("objLog->CheckInput", "Uploaded clean-up is too long: ".strlen($this->strCleanUp)."/".MAX_LOG_SIZE / 25);
				return false;
			}
			if (preg_match("/[0-9]{0,10}/", (string) $this->intIPMs) == 0) {
				LogError("objLog->CheckInput", "Wrong IPMs number");
				return false;
			}
		//Fix by Zmei - info
			/*if (!strpos($this->strHTMLLog, "</html>"))
			{
				$poz1 = strpos($this->strHTMLLog, "<!-- master -->") + 16 ;
				if($poz1){
				$str1 = substr($this->strHTMLLog, 0, $poz1 + 1 );
				$this->strHTMLLog = $str1."</body></html>";
				}

			}*/

            //FIX
			// Simple includes
			/*$arrSearch = array("<div", "</div>", "<table", "</table>", "<tr", "</tr>", "<td", "</td>");
			foreach ($arrSearch as $strSearch) {
			    if (substr_count(strtolower($this->strHTMLLog), strtolower($strSearch)) == 0) {
					LogError("objLog->CheckInput", "Uploaded object isn't the correct ogame log (simple includes): can't find \"" . htmlentities($strSearch) . "\"");
					return false;
				}
			}
			// Count includes
/*			$arrSearch = array("<html", "</html>", "<head>", "</head>",  "<title>", "</title>", "<body", "</body>");
			foreach ($arrSearch as $strSearch) {
			    if (substr_count(strtolower($this->strHTMLLog), strtolower($strSearch)) != 1) {
					LogError("objLog->CheckInput", "Uploaded object isn't the correct ogame log (count includes): wrong count of \"" . htmlentities($strSearch) . "\"");
					return false;
				}
			}
*/
			// Custom includes
			/*if (preg_match("/\[[0-9]{1,3}:[0-9]{1,3}:[0-9]{1,3}\]/", $this->strHTMLLog) == 0) {
				LogError("objLog->CheckInput", "Uploaded object isn't the correct ogame log (custom includes)");
				return false;
			}			
			if (fmod(preg_match("/[0-9]{2,3}%/", $this->strHTMLLog), 3) == 0) {
				LogError("objLog->CheckInput", "Uploaded object isn't the correct ogame log (custom includes)");
				return false;
			}*/
			// RegExp
			$arrPatterns = NULL;
			$arrPatterns[] = GetPatterns_0x();
			$arrPatterns[] = GetPatterns_1x();
			foreach ($arrPatterns as $arrPattern) {
				$blnResult = true;
				foreach ($arrPattern as $strPattern) {
					if (!preg_match("/" . $strPattern . "/", str_replace("\\", "", strtolower($this->strHTMLLog)))) {
						$blnResult = false;
						break;
					}
				}
				if ($blnResult) {
					break;
				}
			}
/*			if (!$blnResult) {
				LogError("objLog->CheckInput", "Uploaded object isn't the correct ogame log (RegExp)");
				return false;
			}*/
			return true;
		}

		private function DeleteSession() {
			if (preg_match("/session=[a-zA-Z0-9\"]+/", $this->strHTMLLog, $arrMatches))
				$this->strHTMLLog = str_replace(str_replace("\"", "", str_replace("session=", "", $arrMatches[0])), "", $this->strHTMLLog);
			
			return true;
		}
		
		private function DeleteTechnologies() {
			$this->strHTMLLog = preg_replace("/[0-9]{2,3}%/", "X%", $this->strHTMLLog);
			return true;
		}
		
		private function DeleteCoordinates() {
			$this->strHTMLLog = preg_replace("/\[[0-9]{1,3}:[0-9]{1,3}:[0-9]{1,3}\]/", "[X:X:X]", $this->strHTMLLog);
			return true;
		}
		
		private function DeleteLinks() {
			$this->strHTMLLog = str_replace('</a>', "", $this->strHTMLLog);
			$this->strHTMLLog = preg_replace("/<a[^>]+>/", "", $this->strHTMLLog);
			return true;
		}
		
		private function DeleteStyleSheet($strStyleSheet) {
			$this->strHTMLLog = preg_replace("/<link[^>]+".$strStyleSheet."[^>]+>/", "", $this->strHTMLLog);
			return true;
		}

		private function GetShowUniDomain($strWhere, $strWhat) {
			$varResult = UNDEFINED;
    		$strPattern = "/s[0-9]+\-[a-z]+/";

    		if(!preg_match($strPattern, $strWhere, $intMatches)) {

    			$varResult = GetUniDomainEx($strWhere, $strWhat);
    			if (!$varResult) {
    				LogError("GetUniDomain", "Can't determine universum or domain");
    				$varResult = false;
    			}
    		}
    		else {
                $strTmp = explode("-", $intMatches[0]);
    		    if (strtolower($strWhat) == "uni") {
    			    if (preg_match("/s[0-9]+/", $strTmp[0], $arrMatches)) {
    						$varResult = (integer) str_replace("s", "", $arrMatches[0]);
    				}
    			}
    			if (strtolower($strWhat) == "domain") {
    			    if (preg_match("/[a-z]+/", $strTmp[1], $arrMatches)) {
    				    $varResult = $arrMatches[0];
    				}
    			}
    		}

    		return  $varResult;
		}

		private function MsgShowTitle() {
			$strTagName = "th";
			$arrAttribute['name'] = "class";
			$arrAttribute['value'] = "area";
			$arrAttributes[] = $arrAttribute;

			$arrTmp = GetInnerHTML($this->strHTMLLog, $strTagName, $arrAttributes);

			if (!$arrTmp) {
				LogError("objParser->MsgShowTitle", "area not found");
				return -1;
			}
            $varResult = strip_tags(trim($arrTmp[0]));
            $varResult = str_replace(array("\r","\n"), '', $varResult);

			return $varResult;
        }

		private function MsgShowMessage() {
			$strTagName = "div";
			$arrAttribute['name'] = "class";
			$arrAttribute['value'] = "textWrapper";
			$arrAttributes[] = $arrAttribute;

			$arrTmp = GetInnerHTML($this->strHTMLLog, $strTagName, $arrAttributes);

			if (!$arrTmp) {
				LogError("objParser->MsgShowMessage", "textWrapper not found");
				return -1;
			}

			$this->strHTMLLog = trim($arrTmp[0]);

			return true;
        }

		private function MsgDel($strTagName, $strClassName) {
			$arrAttribute['name'] = "class";
			$arrAttribute['value'] = $strClassName;
			$arrAttributes[] = $arrAttribute;

			$arrTmp = GetInnerHTML($this->strHTMLLog, $strTagName, $arrAttributes);

			if (!$arrTmp) {
				LogError("objParser->MsgDel", "$strClassName not found");
				return -1;
			}
            //echo trim($arrTmp[0]);

			$this->strHTMLLog = str_replace(trim($arrTmp[0]), "", $this->strHTMLLog);

			return true;
        }

		private function DeleteBackSplashes() {
			$this->strHTMLLog = str_replace("\\", "", $this->strHTMLLog);
			$this->strComment = str_replace("\\", "", $this->strComment);
			return true;
		}

		private function ReplaceQuotes() {
			$this->strHTMLLog = str_replace("\"", "'", $this->strHTMLLog);
			$this->strComment = str_replace("\"", "'", $this->strComment);
			return true;
		}

		private function Show() {
			echo $this->strHTMLLog;
		}
		
		//private
		function GetLogsCount(&$arrIds, $strFolder) {
			$intCount = 0;
			$arrIds = array();
			
			if (!file_exists($strFolder))
				return $intCount;
			
			if (is_dir($strFolder)) {
				if ($objDir = opendir($strFolder)) {
					while (false !== ($objFile = readdir($objDir))) {
						if ($objFile != "." && $objFile != "..") {
							$intCount++;
							$arrIds[] = $objFile;
						}
					}
					closedir($objDir);
				}
			}
			
			return $intCount; //-1 public_list.txt
		}
		
		private function DeleteElderLog($strFolder)
		{
			try {
				$arrFiles = array();
				$arrDates = array();
				if (!file_exists($strFolder))
					return false;
				if (is_dir($strFolder)) {
					if ($objDir = opendir($strFolder)) {
						while (false !== ($objFile = readdir($objDir))) {
							if ($objFile != "." && $objFile != "..") {
								$arrFiles[] = $strFolder . "/" . $objFile;
								$arrDates[] = filectime($strFolder . "/" . $objFile);
							}
						}
						closedir($objDir);
					}
				}
				if ($arrFiles) {
					arsort($arrDates);
					end($arrDates);
					unlink($arrFiles[key($arrDates)]);
				}
				return true;
			}
			catch(Exception $e) {
				return false;
			}
		}
		
		//private
		function DeleteAll(&$arrIds) {
			$intCount = 0;
			$arrIds = array();
			
			if (!file_exists(FOLDER_UPLOAD))
				return $intCount;
			
			if (is_dir(FOLDER_UPLOAD)) {
				if ($objDir = opendir(FOLDER_UPLOAD)) {
					while (false !== ($objFile = readdir($objDir))) {
						if ($objFile != "." && $objFile != "..") {
							$intCount++;
							try {
								unlink(FOLDER_UPLOAD."/".$objFile);
								$arrIds[] = $objFile." deleted";
							}
							catch(Exception $e) {
								$arrIds[] = $objFile." can't be deleted";
							}
						}
					}
					closedir($objDir);
				}
			}
			
			return $intCount;
		}
		
		private function GetUniDomain($strWhat) {
			$varResult = UNDEFINED;
    		$strPattern = "/s[0-9]+\-[a-z]+/";

    		if(!preg_match($strPattern, $this->ownHtmllogllog, $intMatches)) {

    			$varResult = GetUniDomainEx($strWhere, $strWhat);
    			if (!$varResult) {
    				LogError("GetUniDomain", "Can't determine universum or domain");
    				$varResult = false;
    			}
    		}
    		else {
                $strTmp = explode("-", $intMatches[0]);
    		    if (strtolower($strWhat) == "uni") {
    			    if (preg_match("/s[0-9]+/", $strTmp[0], $arrMatches)) {
    						$varResult = (integer) str_replace("s", "", $arrMatches[0]);
    				}
    			}
    			if (strtolower($strWhat) == "domain") {
    			    if (preg_match("/[a-z]+/", $strTmp[1], $arrMatches)) {
    				    $varResult = $arrMatches[0];
    				}
    			}
    		}

    		return  $varResult;
		}
		
		private function GetUniDomainEx($strWhat) {
			$varResult = false;
			$arrPattern = GetPatterns_0x();
			$blnResult = true;
			foreach ($arrPattern as $strPattern) {
				if (!preg_match("/" . $strPattern . "/", str_replace("\\", "", $this->strHTMLLog))) {
					$blnResult = false;
					break;
				}
			}
			if ($blnResult) {
				if (strtolower($strWhat) == "uni") {
					$varResult = (integer) -1;
				}
				if (strtolower($strWhat) == "domain") {
					$varResult = UNDEFINED;
				}
			}
			else {
				$arrPattern = GetPatterns_1x();
				$blnResult = true;
				foreach ($arrPattern as $strPattern) {
					if (!preg_match("/" . $strPattern . "/", str_replace("\\", "", $this->strHTMLLog))) {
						$blnResult = false;
						break;
					}
				}
				if ($blnResult) {
					if (strtolower($strWhat) == "uni") {
						$varResult = (integer) 999;
					}
					if (strtolower($strWhat) == "domain") {
						$varResult = UNDEFINED;
					}
				}
			}
			return $varResult;
		}
	}
?>
