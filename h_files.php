<?php
	function LogListSearch($strWhat, $strLogListFile) {
		$arrResult = array();
		if (true) {
		//if ($strWhat['text_name']) {
			if (($strWhat['text_name'] == "last_") || ($strWhat['text_name'] == "last_x")) { // Auto lists
				$intLN = 40;
				if ($strWhat['text_name'] == "last_x") $intLN = 1000;
				$arrLines = read_file_tail($strLogListFile, $intLN);
				//print_r($arrLines);
				foreach ($arrLines as $key => $value) {
					if(preg_match('/id=<[a-z0-9]+?>/', $value, $arrMatches)) {
						$strId = str_replace(">" , "", str_replace("id=<" , "", $arrMatches[0]));
						if(preg_match('/title=<.+?>/', $value, $arrMatches)) {
							$strTitle = str_replace(">" , "", str_replace("title=<" , "", $arrMatches[0]));
							$arrResult[] = array("id" => $strId, "title" => $strTitle);
						}
						else
						{
							$value = str_replace("->", "&#8594;", $value);
							if(preg_match('/err_serialize=<.+?>/', $value, $arrMatches)) {
								$strTitle = str_replace(">" , "", str_replace("err_serialize=<" , "", $arrMatches[0]));
								$arrResult[] = array("id" => $strId, "err_serialize" => $strTitle);
								//print_r($arrResult);exit;
							}
							else
								$arrResult[] = array("id" => $strId);
						}
					}
				}
			}
			else { // User search list
				$handle = fopen($strLogListFile, "r");
				if ($handle) {
				    while (!feof($handle) && count($arrResult) < 100) {
				    	$strLine = fgets($handle, 4096);
				    	if(preg_match('/id=<[a-z0-9]+?>/', $strLine, $arrMatches)) {
							$strId = str_replace(">" , "", str_replace("id=<" , "", $arrMatches[0]));
							if (preg_match('/title=<.+?>/', $strLine, $arrMatches)) {
								$strTitle = str_replace(">" , "", str_replace("title=<" , "", $arrMatches[0]));
								
								if (preg_match('/\([0-9\.]+?, [A-Za-z0-9\.\/]+?, [0-9\/]+?\)/', $strTitle, $arrMatches)) {
									$strInfo = $arrMatches[0];
								}
								else {
									continue;
									//LogError("LogListSearch", "Internal error");
									//return false;
								}
								
								$strOpponents = trim(str_replace($strInfo, "", $strTitle));
								$strInfo = str_replace("(", "", $strInfo);
								$strInfo = str_replace(")", "", $strInfo);
								$arrTmp = explode(",", $strInfo);
								$intLosses = (integer) str_replace(".", "", trim($arrTmp[0]));
								$strLocation = trim($arrTmp[1]);
								$strUploaded = trim($arrTmp[2]);
								$arrTmp = explode(".", $strLocation);
								$strUni = $arrTmp[0];
								$strDomain = $arrTmp[1];
								
								/*preg_match('/Losses: [0-9\.]+/', $strInfo, &$arrMatches);
								$intLosses = str_replace(".", "", str_replace("Losses: ", "", $arrMatches[0]));
								
								preg_match('/Location: [A-Za-z0-9\.]+/', $strInfo, &$arrMatches);
								$strLocation = str_replace("Location: ", "", $arrMatches[0]);
								$arrTmp = explode(".", $strLocation);
								$strUni = $arrTmp[0];
								$strDomain = $arrTmp[1];
								
								preg_match('/Uploaded: [0-9\/]+/', $strInfo, &$arrMatches);
								$strUploaded = str_replace("Uploaded: ", "", $arrMatches[0]);*/
								
								if ($strWhat['select_domain']) {
									if (!stristr($strDomain, $strWhat['select_domain'])) {
										continue;
									}
								}
								
								if ($strWhat['select_uni']) {
									if (strtolower($strUni) != strtolower(NameUni($strWhat['select_uni']))) {
										continue;
									}
								}
								
								if ($strWhat['select_losses']) {
									if ($intLosses < (pow(2, $strWhat['select_losses'] - 1)) * 100000) {
										continue;
									}
								}
								
								if (!$strWhat['text_name']) {
									$arrResult[] = array("id" => $strId, "title" => $strTitle);
								}
								else {
									if (stristr($strOpponents, $strWhat['text_name'])) {
										$arrResult[] = array("id" => $strId, "title" => $strTitle);
									}
								}
							}
							if (preg_match('/err_serialize=<.+?>/', $value, $arrMatches)) {
								$strTitle = str_replace(">" , "", str_replace("err_serialize=<" , "", $arrMatches[0]));
								if (stristr($strTitle, $strWhat['text_name'])) {
									$arrResult[] = array("id" => $strId, "err_serialize" => $strTitle);
								}
							}
						}
				    }
				    fclose($handle);
				    $arrResult = array_reverse($arrResult);
				}
			}
		}
		return $arrResult;
	}
	
	function IncReportsCount($intInput) {
		$strFileCounter = "index_files/counter_total";
		if (!file_exists($strFileCounter))
			file_put_contents($strFileCounter, $intInput);
		else
			file_put_contents($strFileCounter, $intInput + (integer) file_get_contents($strFileCounter));
	}
	
	function GetReportsCount() {
		$intReturn = 0;
		$strFileCounter = "index_files/counter_total";
		if (file_exists($strFileCounter))
			$intReturn = (integer) file_get_contents($strFileCounter);
		return $intReturn;
	}
	
	function IncReportsCount24($intInput) {
		$strFileCounter = "index_files/counter_24";
		$strFileNew = "";
		if (file_exists($strFileCounter)) {
			$strFile = file_get_contents($strFileCounter);
			if ($strFile) {
				$arrFile = explode("\n", $strFile);
				foreach ($arrFile as $strTime) {
					$intTime = (integer) $strTime;
					if (time() - $intTime < 24 * 60 * 60)
						if ($strFileNew == "")
							$strFileNew = $intTime;
						else
							$strFileNew .= "\n" . $intTime;
				}
			}
		}
		for ($i = 0; $i < $intInput; $i++) {
			if ($strFileNew == "")
				$strFileNew = time();
			else
				$strFileNew .= "\n" . time();
		}
		file_put_contents($strFileCounter, $strFileNew);
	}
	
	function GetReportsCount24() {
		$intReturn = 0;
		$strFileCounter = "index_files/counter_24";
		IncReportsCount24(0);
		if (file_exists($strFileCounter)) {
			$strFile = file_get_contents($strFileCounter);
			if ($strFile != "")
				$intReturn = count(explode("\n", $strFile));
		}
		return $intReturn;
	}
 	class cFiles {
        static function IsFileBuffer ($strURL) {
            if (file_exists($strURL)) return true;
            else return false;
        }
        static function LoadFileBuffer ($strURL) {
            return gzinflate (file_get_contents($strURL));
        }        
        static function DeleteFileBuffer ($strURL) {
            if (cFiles::IsFileBuffer($strURL)) unlink($strURL);
        }        
    }
?>