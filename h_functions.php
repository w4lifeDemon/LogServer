<?php
	function Main() {
		if (file_exists("index_files/dbg.flag")) {
			LogError("LogServer", "Sorry, LogServer in not available now, please wait for a few minutes");
			ShowResult(ERR_SERVER_OFF);
			return false;
		}
		else {
			if ($_GET)
				Get();
			else
				Post();
		}
		return;
	}

	function Get() {
		if (isset($_GET['id'])) {
			if ($_GET['id'] == '1 1' || $_GET['id'] == '\'1 1') {
				if ($_COOKIE['leet'] == "hydj8dm1337"){ echo "Поздравляю, ты прошел/ла эту хню!"; exit();}
				echo "<body bgcolor='black'><center><img src='index_files/hello_hack.jpg'></center></body>";
				if ($_COOKIE['leet'] == "1") echo "<!-- 1zm3n1 zn443n1y3 c00k13 l337 n4 'hydj8dm1337' 1 0nbn0v1 57r4n14ku -->";
				exit();
			}
			if ($_GET['id'] == 'qr-code') {
				echo "<center><img src='index_files/qr-code.gif'></center>";
				SetCookie("leet","1");
				exit();
			}			
		    if (isset($_GET['lang'])) {
    			switch (strtolower($_GET['lang'])) {
    				case "bg":
    					$_SESSION["lang"] = "bg";
    					break;
    				case "de":
    					$_SESSION["lang"] = "de";
    					break;
    				case "en":
    					$_SESSION["lang"] = "en";
    					break;
    				case "fr":
    					$_SESSION["lang"] = "fr";
    					break;
    				case "ru":
    					$_SESSION["lang"] = "ru";
    					break;
    				case "ua":
    					$_SESSION["lang"] = "ua";
    					break;
    				default:
    					$_SESSION["lang"] = "en";
    					break;
    			}
        		setcookie("lang", $_SESSION["lang"], strtotime("+1 year"), "/");
		    }

		    $intUserID = GetUserIDFromSession();

			$objLog = new cLog(KillInjection(mb_strimwidth($_GET['id'], 0, 36, "")), 'take');
			if (IsErrors()) {
				ShowResult(ERR_CLOG_CONSTRUCT);
				return false;
			}
			if (!$objLog->Load()) {
				ShowResult(ERR_CLOG_LOAD);
				return false;
			}
			
			echo $objLog->Get('htmllog');

            //Предыдущий/Следующий лог
            ($_COOKIE["option_select_f_b"] == "false" || !$_COOKIE["option_select_f_b"]) ? ($strCbxFB = false) : ($strCbxFB = true);
            if ($strCbxFB) {
                $strReadResult = cDB::LoadTitleByID (KillInjection($_GET['id']));
    			$strDateS = $strReadResult['date'];
    			$strDomain = $strReadResult['domain'];
    			$strUni = $strReadResult['universe'];
                $nextLogId = cDB::NextLogId ($strDateS, $strDomain, $strUni);
                $earlyLogId = cDB::EarlyLogId ($strDateS, $strDomain, $strUni);
            }
            //Информационная база данных на игрока
            $optSelecSearch = $_COOKIE['option_select_search'];
            if (!$optSelecSearch || $optSelecSearch == 'Infuza.com'){
                $inSelecSearch = 'Infuza.com';
            }
            if ($optSelecSearch == 'OpenGalaxy'){
                $inSelecSearch = 'OpenGalaxy';
            }
            if ($optSelecSearch == 'Ogniter.org'){
                $inSelecSearch = 'Ogniter.org';
            }
            if ($optSelecSearch == 'Ogame-Pb.net'){
                $inSelecSearch = 'Ogame-Pb.net';
            }

            echo '<script>var stat = document.getElementsByTagName("stat"); for (var i = 0; i < stat.length; i++) {stat[i].innerHTML = \'<a title="Search [\'+stat[i].getAttribute("player")+\'] in ' . $inSelecSearch . '" target="_blank" href="' . str_replace("/index.php", "", LOGSERVERURL) . '/redirect.php?r=' . $inSelecSearch . '&domain=\'+stat[i].getAttribute("domain")+\'&uni=\'+stat[i].getAttribute("uni")+\'&player=\'+stat[i].getAttribute("player")+\'"><img width="20" border="0" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAgY0hSTQAAeiYAAICEAAD6AAAAgOgAAHUwAADqYAAAOpgAABdwnLpRPAAAAnFJREFUOE+Vkl0sFWAYx9/DVG5SHYXjM1NOQyLTLLS0mg4JxUFXkk45KhcWMVHqqqWstZpma6ssa7KK0lq1KVSKirSOQpoMoyGar/16HXLT2czz7r/99+55ftvzoRAmwsrZEnsvpUC+mVCIb9XdYnxoSmEq/78/Te4mZmJA6pfRCds52vwM70RHWfKTr2OVtI0/kr4HYbYggKss+sDH4Zs0/74lfQvCfAGAjUlujFBFTX8htQOFTPESYbEAgF+Smk6KqejKorIrk17K/k1z/v6nMwJ0G3jHWUraUqQOyQaKjYD+F6dpKNfRcDeZ7vozhAfZmwYH6fwo7gshq86LE7XrKJ3QGRNnNjMiNWx0qQk+pgFbDwdwrsOPo8/d0D9zomhw3yygl8nex0z2TG9mAH28t2mAT5IvJ1s8OFDlROJDWwr69swCGplsv8ZEW5EEvEEf54W4FLodQ342TcfT+JSTTmv0Qezi3Eirdya+fBWxZcvI/66ZBdxnrDlP6pQElKDXeiLeZ6fLtjqgqwn6P8O9pyyOsCOp2obdt60IK1lChmGb8ZDoTeVPlY+UH3RGoo9VI96mJDNxJY/BCzkMX8yB3ALMIlRon1ix44YlIdfNONYciFgkAT+0jD5wZLRCHtoXf/QxaxF1cdF0e9rR7utK+3oVhCWgiHEgtFzgf1Xge1mQ+FpOe7EEtG5mpFQwcscCGq3R73VDvNLGwM4tEK2BXSGgy2J1hi+ZhkCSa7zZX+3B+SEdtmumt5gMBrUEecJYMEe07ogQpZJolWpO4SttWOpiiVpjj7tGZZQ6zIHl1uZEBSmJDFphVFSwEhdbC/4CmPuuyq2BiTYAAAAASUVORK5CYII="></img></a>\';}';

            if ($earlyLogId) {
                echo 'var back = document.getElementById("back"); back.innerHTML = "<a href=\'index.php?id=' . $earlyLogId['log_id'] . '\' target=\'_self\'><img src=\'index_files/back.png\' width=\'42\' height=\'42\' alt=\'back\' />";';
            }
            if ($nextLogId) {
                echo 'var forward = document.getElementById("forward"); forward.innerHTML = "<a href=\'index.php?id=' . $nextLogId['log_id'] . '\' target=\'_self\'><img src=\'index_files/forward.png\' width=\'42\' height=\'42\' alt=\'forward\' />";';
            }
            echo '</script>';

			return true;
		}
		if (THIS_IS_BACKUP_SYSTEM) {
			echo THIS_IS_BACKUP_SYSTEM_MSG;
			exit;
		}
		if (isset($_GET['lang'])) {
			switch (strtolower($_GET['lang'])) {
				case "bg":
					$_SESSION["lang"] = "bg";
					break;
				case "de":
					$_SESSION["lang"] = "de";
					break;
				case "en":
					$_SESSION["lang"] = "en";
					break;
				case "fr":
					$_SESSION["lang"] = "fr";
					break;
				case "ru":
					$_SESSION["lang"] = "ru";
					break;
				case "ua":
					$_SESSION["lang"] = "ua";
					break;
				default:
					$_SESSION["lang"] = "en";
					break;
			}
			Post();
			return true;
		}
        if(isset($_GET['page'])) {
            $strPage = KillInjection($_GET['page']);
        }
		if (isset($_GET['show'])) {
			$_SESSION["user"]["last_url"] = LOGSERVERURL . "?show=" . KillInjection($_GET['show']);
			if ($_GET['show'] == "public") {
				$_SESSION["user"]["current_page"] = "public";
				$arrInput["what"]["text_name"] = "last_";

				ShowPublicForm();
				return true;
			}
			if ($_GET['show'] == "info") {
				$_SESSION["user"]["current_page"] = "info";
				ShowInfo();
				return true;
			}
			if ($_GET['show'] == "fixlist") {
				$_SESSION["user"]["current_page"] = "fixlist";
				ShowFixList();
				return true;
			}
			if ($_GET['show'] == "thx") {
				$_SESSION["user"]["current_page"] = "thx";
				ShowThx();
				return true;
			}
			if ($_GET['show'] == "sado9asdjIsd") {
				$_SESSION["user"]["current_page"] = "local";
				ShowLocalizations();
				return true;
			}			
			if ($_GET['show'] == "blacklist") {
				$_SESSION["user"]["current_page"] = "blacklist";
				ShowBlackList();
				return true;
			}
			if ($_GET['show'] == "settings") {
				$_SESSION["user"]["current_page"] = "option";
				ShowSettings();
				return true;
			}
			if ($_GET['show'] == "plugin") {
				$_SESSION["user"]["current_page"] = "plugin";
				ShowPluginInfo();
				return true;
			}
			if ($_GET['show'] == "account") {
				$_SESSION["user"]["current_page"] = "account";
				$intUserID = GetUserIDFromSession();
				ShowAccount(false);
				return true;
			}
			if ($_GET['show'] == "alliance") {
				$_SESSION["user"]["current_page"] = "alliance";
				$intUserID = GetUserIDFromSession();
				ShowAlliance(false);
				return true;
			}			
			if ($_GET['show'] == "admin") {
				$_SESSION["user"]["current_page"] = "admin";
				$arrInput["what"]["text_name"] = "last_";
				ShowFakeAdmin();
				return true;
			} 			
			if ($_GET['show'] == "tool") {
				$_SESSION["user"]["current_page"] = "tool";
				$arrInput["what"]["text_name"] = "last_";
				$arrLogs = cDB::LogListSearch($arrInput["what"], 'admin');
				ShowAdmin($arrLogs, 0);
				return true;
			}            
			if ($_GET['show'] == "search") {
				$_SESSION["user"]["current_page"] = "search";
				$arrInput["what"]["text_name"] = "last_";
				$arrLogs = cDB::LogListSearch($arrInput["what"], 0);
				ShowSearch($arrLogs, false, false, false);
				return true;
			}
			//made by Asiman
            if ($_GET['show'] == "lostpw") {
				$_SESSION["user"]["current_page"] = "account";
				ShowLostpw(false);
				return true;
			}
            if ($_GET['show'] == "changepass") {
				$_SESSION["user"]["current_page"] = "account";
				if ($_SESSION['account']['login']) ShowChangePass(false);
                else ShowAccount(false);
				return true;
			}
			if ($_GET['show'] == "registration") {
				$_SESSION["user"]["current_page"] = "account";
				ShowRegistration(false);
				return true;
			}
			if ($_GET['show'] == "universes") {
				$_SESSION["user"]["current_page"] = "universes";
				ShowUniverses(KillInjection($_GET['country']));
				return true;
			}			
			//made by Zmei
			if ($_GET['show'] == "edit") {
				$_SESSION["user"]["current_page"] = "account";
				$intUserID = GetUserIDFromSession();
				$input_id = base64_decode(KillInjection($_GET['log_id']));
                
                if (cDB::IsLogAccess($input_id, $intUserID)) {
    				$objLog = new cLog( $input_id, 'take');
    				if (IsErrors()) {
    					ShowResult(ERR_CLOG_CONSTRUCT);
    					return false;
    				}
    				if (!$objLog->Edit()) {
    					ShowResult(ERR_CLOG_LOAD);
    					return false;
    				}
    				ShowEditForm($objLog);
    				return true;
                } else {
   					ShowResult(ERR_CLOG_CONSTRUCT);
   					return false;                   
                }
			}
		}

		if ($_GET['delete_x']){
			DeleteLog($_GET['delete_x'], FOLDER_UPLOAD);
			$arrInput["what"] = $_POST;
			$arrLogs = cDB::LogListSearch($arrInput["what"], 0);
			$arrPopularLogs = cDB::GetPopularLogs(10);
			ShowPublicForm($arrLogs, $arrPopularLogs, "last");
			return true;
		}
		if ($_GET['logout'] == "1") {
			cRegSrv::ProcessLogout();
			if ($_SESSION["user"]["last_url"])
				echo "<html><head><meta http-equiv='refresh' content='0; url=" . $_SESSION["user"]["last_url"] . "'/></head><body></body></html>";
			else
				echo "<html><head><meta http-equiv='refresh' content='0; url=" . LOGSERVERURL . "'/></head><body></body></html>";
			exit;
		}
		if ($_GET['reg']) {
			if (!cRegSrv::ConfirmRegistration(KillInjection($_GET['reg']))) {
				ShowResult(ERR_UNKNOWN);
				return false;
			}
			ShowAccount(false);
			return true;
		}
		if ($_GET['lostpw']) {
			$varResult = cRegSrv::ConfirmLostpw(KillInjection($_GET['lostpw']), KillInjection($_GET['mail']));

			ShowAccount($varResult);

			return true;
		}
		if ($_GET['show'] == "error") {
			ShowError();
			return true;
		}		
		if ($_GET['cmd']) {
			DoCommand(KillInjection($_GET['cmd']));
			return true;
		}
		LogError("Get", "This URL parameters can't be processed");
		ShowResult(ERR_URL);
		return false;
	}

	function Post() {
		if (THIS_IS_BACKUP_SYSTEM) {
			echo THIS_IS_BACKUP_SYSTEM_MSG;
			exit;
		}
		if (isset($_POST['account_login_form'])) {
			if (!cRegSrv::ProcessLogin(KillInjection($_POST))) {
				ShowAccount(ERR_LOGIN);
				return false;
			}

			$intUserID = GetUserIDFromSession();
			$arrInput["what"]["text_name"] = "last_";
			ShowAccount(false);

			return true;
		}
		if (isset($_POST['account_lostpw_form'])) {
		    ShowLostpw(cRegSrv::ProcessLostpw(KillInjection($_POST)));
			return true;
		}

		if (isset($_POST['account_changpw_form'])) {
		    ShowChangePass(cUser::ProcessChangtpw(KillInjection($_POST)));
			return true;
		}

		if (isset($_POST['registration_form'])) {
			if (!cRegSrv::ProcessRegistration(KillInjection($_POST))) {
				ShowRegistration("ERR_REG");
				return false;
			}
			ShowAccount(ERR_NOT_CONF_REG);
			return true;
		}
		if (isset($_POST['public_form'])) {
			//$_SESSION["user"]["current_page"] = "public";
			$arrInput["what"] = KillInjection($_POST);
			$arrLogs = cDB::LogListSearch($arrInput["what"], 0);
			$arrPopularLogs = cDB::GetPopularLogs(10);
			ShowPublicForm($arrLogs, $arrPopularLogs, "search");
			return;
		}
		if (isset($_POST['account_search_form'])) {
			//$_SESSION["user"]["current_page"] = "public";
			$intUserID = GetUserIDFromSession();
			$arrInput["what"] = KillInjection($_POST);
			$arrLogs = cDB::LogListSearch($arrInput["what"], $intUserID);
			ShowAccount(false);
			return;
		}
		if (isset($_POST['admin_search_form'])) {
			$_SESSION["user"]["current_page"] = "tool";
			$arrInput["what"] = KillInjection($_POST);
			$arrLogs = cDB::LogListSearch($arrInput["what"], 'admin');
			
			cDB::LogForAdmin($arrInput["what"], $_SESSION['account']['login']);
			
			ShowAdmin($arrLogs, 0);
			return;
		}
		if (isset($_POST['user_search_form'])) {
		      $arrInput["what"] = KillInjection($_POST);
              		  
            if ($_SESSION['protect'] != $arrInput["what"]['protect']) {
    			$_SESSION["user"]["current_page"] = "search";                
                ShowSearch(false, 'ERR_USERSEARCH_CODE', false, false);
            } else {		  
    			$_SESSION["user"]["current_page"] = "search";
    			$arrLogs = cDB::LogListSearch($arrInput["what"], 0);
    			ShowSearch($arrLogs, false, $arrInput["what"]["text_name"], $arrInput["what"]['vs']);
            }
			return;
		}        
		if (isset($_POST['xss'])) {
			if (($_POST['xss'] == 1) && isset($_POST['del'])) {
				$strLogId = base64_decode(KillInjection($_POST['del']));
				$strUID = $_SESSION['account']['id'];
                if (!$strUID) exit;
				if (cDB::DeleteLog($strLogId, $strUID)) {
					echo("<script>window.name = \"" . KillInjection($_POST['del']) . "\"</script>");
				}
			}
			if (($_POST['xss'] == 1) && isset($_POST['cpub']) && isset($_POST['val'])) {
				$strLogId = base64_decode(KillInjection($_POST['cpub']));
				$strUID = $_SESSION['account']['id'];
				if (!$strUID) exit;
				$intPub = (integer) $_POST['val'];
				if (cDB::ChangePublic($strLogId, $strUID, $intPub)) {
					echo("<script>window.name = \"" . $_POST['cpub'] . "\"</script>");
				}
			}
			if (($_POST['xss'] == 2) && isset($_POST['del'])) {
				$strLogId = base64_decode(KillInjection($_POST['del']));
				$strUID = $_SESSION['account']['id'];
                if (!$strUID) exit;
				if (cDB::DeleteLogWarLogs($strLogId, $strUID)) {
					echo("<script>window.name = \"" . KillInjection($_POST['del']) . "\"</script>");
				}
			}
			if (($_POST['xss'] == 3) && isset($_POST['del'])) {
				$strLogId = base64_decode(KillInjection($_POST['del']));
				$strUID = $_SESSION['account']['id'];
                if (!$strUID) exit;
				if (cDB::DeleteSpyLog($strLogId, $strUID)) {
					echo("<script>window.name = \"" . KillInjection($_POST['del']) . "\"</script>");
				}
			}			
			exit;
		}
		if (!isset($_POST['submited'])) {
			$_SESSION["user"]["current_page"] = "main";
			ShowUploadForm();
		}
		else {
			$intTimer = microtime();
			$objLog = new cLog($_POST, 'get');


			if (IsErrors()) {
				$objLog->SaveErrlog();
				ShowResult(ERR_CLOG_CONSTRUCT);
				return false;
			}


			if (!$objLog->Process()) {
				$objLog->SaveErrlog();
				ShowResult(ERR_CLOG_PROCESS);
				return false;
			}

			if (!$objLog->SaveTempLog()) {
				LogError("Post", "objLog->SaveTempLog failed");
				ShowResult(ERR_CLOG_SAVE_TEMP);
				return false;
			}

			if (!$objLog->Save()) {
				LogError("Post", "objLog->Save failed");
				ShowResult(ERR_CLOG_SAVE);
				return false;
			}
			if (!$objLog->SaveBackup()) {
				LogError("Post", "objLog->SaveBackup failed");
				//ShowResult(ERR_CLOG_SAVE_BACKUP);
				//return false;
			}
			if ($_POST["plugin"] == 1) {
				$varResult["url"] = $objLog->Get('url');
				$varResult["bbcode"] = $objLog->Get('bburl');

	            $strPublic = $objLog->Get('public');
	            $strUni = $objLog->Get('uni');
	            $strDomain = $objLog->Get('domain');
	            $strUrl = $objLog->Get('url');

		        $strNData = array("content" => $strUrl . "&t=" . time());                                                                    
		        $jsonData = json_encode($strNData);

		        if ($strPublic == 1) {
		            //OGame (unf)
		            curlSendDiscord ("https://ptb.discordapp.com/api/webhooks/553853418276519936/kLnhdhJap6j7f9ZKNTihipZSNgn2MLqEXDI595SJ5xXpBmEfX7kthXJ2Oi_3vxMmUUJX", $jsonData);

		            //curlSendDiscord ("https://discordapp.com/api/webhooks/561198461303914511/vRZ8kASDsmYMn9H0mAGW1oFUKeqUJOLB6T9kdE9vpHNh38mOy36I6iHZnieFToU6qNx2", $jsonData);
		            //JEW
		            if ($strUni == 156 && $strDomain == "ru")
		                curlSendDiscord ("https://discordapp.com/api/webhooks/603967661210075146/amgjwa-bElvA_TD-FjzM4SVuWHFpoAsxZqeswfllXGEoZAaDTgu5Cpdvx0AH6Lj1MWp6", $jsonData);
		            //oblom
		            if ($strUni == 164 && $strDomain == "pl")
		                curlSendDiscord ("https://discordapp.com/api/webhooks/644514279831371796/nU4vk3x6c7CxA-WOrKsJYKgWYCWROy2rwuaLrmsM1e4esOxhRsA295tjjfuDxI46-0fO", $jsonData);
			        if ($strUni == 163 && $strDomain == "en")
		            	curlSendDiscord ("https://discordapp.com/api/webhooks/632914397932814337/2FzyIkd5wtv3WQ7HcF4a0tRt066qq_hNGjeizjfh7s84Ro782YTIe4bQjOh6PdeTLim9", $jsonData);		        
		        } else 
		        if ($strUni == 163 && $strDomain == "en")
		            curlSendDiscord ("https://discordapp.com/api/webhooks/656472268335087656/QZP20DgYpSse0nYUZyYlCqOvepuh8TmBTaOyK77VMaEkmLdgxNvz3gZaDV_mQPYC1Odg", $jsonData); 
			    
				echo json_encode($varResult);
			} elseif ($_POST["plugin"] == 2) {
				$varResult["url"] = $objLog->Get('url');
				echo json_encode($varResult);
			}
			else
				ShowResult(GetUploadResult($objLog, (float) microtime() - (float) $intTimer));
			return true;
		}
	}

	function NumberToString($intNumber) {
		$strTmp = (string) $intNumber;
		$strResult = "";
		$strMinus = "";

		if (stristr($strTmp, "-")) {
			$strTmp = str_replace("-", "", $strTmp);
			$strMinus = "-";
		}

		if (stristr($strTmp, "+")) {
			$strTmp = str_replace("+", "", $strTmp);
			$strMinus = "+";
		}

		if (strlen($strTmp) > 3) {
			while (strlen($strTmp) > 3) {
				if ($strResult == "")
					$strResult = substr($strTmp, strlen($strTmp) - 3, 3);
				else
					$strResult = substr($strTmp, strlen($strTmp) - 3, 3) . '.' . $strResult;

				$strTmp = substr($strTmp, 0, strlen($strTmp) - 3);
			}
			$strResult = $strTmp . '.' . $strResult;
			return $strMinus . $strResult;
		}
		else {
			$strResult = $strTmp;
			return $strMinus . $strResult;
		}
	}

function NumberToString2($intNumber) {
	$Znak = '';
    if ($intNumber < 0) {
        $intNumber = $intNumber * -1;
        $Znak = '-';
    }
	if ($intNumber < pow(1000, 1)) {
		$strValue = $intNumber;
		$strClass = 'abox_text';
	}
	else
	if ($intNumber < pow(1000, 2)) {
		$strValue = (round($intNumber / pow(1000, 1) * 10) / 10) . "K";
		$strClass = 'abox_text_yellow';
	}
	else
	if ($intNumber < pow(1000, 3)) {
		$strValue = (round($intNumber / pow(1000, 2) * 10) / 10) . "KK";
		$strClass = 'abox_text_green';
	}
	else
	if ($intNumber < pow(1000, 4)) {
		$strValue = (round($intNumber / pow(1000, 3) * 10) / 10) . "KKK";
		$strClass = 'abox_text_red';
	}

	if ($intNumber < 1000000) {
		$strClass = 'abox_text';
	}
	else
	if ($intNumber < 100000000) {
		$strClass = 'abox_text_yellow';
	}
	else
	if ($intNumber < 1000000000) {
		$strClass = 'abox_text_green';
	}
	else
		$strClass = 'abox_text_red';

	return '<font size="+2" class="' . $strClass . '">' . $Znak . '' . $strValue . '</font>';
}

function EditNameWar($intName) {
    $intName = str_replace ('[', "", $intName);
    $intName = str_replace (']', "", $intName);
	return $intName;
}

function EditNumber($intNumber) {
    if ($intNumber < 0) {$intNumber = $intNumber * (-1);}
	return $intNumber;
}

	function GetBaseStructure($strId) {
		switch ($strId) {
			case 204: return 4000;
			case 205: return 10000;
			case 206: return 27000;
			case 207: return 60000;
			case 215: return 70000;
			case 211: return 75000;
			case 213: return 110000;
			case 214: return 9000000;
			case 202: return 4000;
			case 203: return 12000;
			case 208: return 30000;
			case 209: return 16000;
			case 210: return 1000;
			case 212: return 2000;

			case 217: return 4000;
			case 218: return 140000;
			case 219: return 23000;
			//
			case 401: return 2000;
			case 402: return 2000;
			case 403: return 8000;
			case 404: return 35000;
			case 405: return 8000;
			case 406: return 100000;
			case 407: return 20000;
			case 408: return 100000;

			case LIGHTFIGHTER: return 4000;
			case HEAVYFIGHTER: return 10000;
			case CRUISER: return 27000;
			case BATTLESHIP: return 60000;
			case BATTLECRUISER: return 70000;
			case BOMBER: return 75000;
			case DESTROYER: return 110000;
			case DEATHSTAR: return 9000000;
			case SMALLTRANSPORTER: return 4000;
			case BIGTRANSPORTER: return 12000;
			case COLONY: return 30000;
			case RECYCLER: return 16000;
			case SPY: return 1000;
			case SUNSAT: return 2000;
			//
			case ROCKETLAUNCHER: return 2000;
			case LIGHTLASER: return 2000;
			case HEAVYLASER: return 8000;
			case GAUSSCANNON: return 35000;
			case IONCANNON: return 8000;
			case PLASMACANNON: return 100000;
			case SMALLSHIELDDOME: return 20000;
			case LARGESHIELDDOME: return 100000;
			case MISSILEINTERCEPTOR: return -1;
			case INTERPLANETARYMISSILE: return -1;
			default: break;
		}

		return false;
	}

	function GetBaseCost($strId) {
		switch ($strId) {
			case 204: return array("M" => 3000, "C" => 1000, "D" => 0);
			case 205: return array("M" => 6000, "C" => 4000, "D" => 0);
			case 206: return array("M" => 20000, "C" => 7000, "D" => 2000);
			case 207: return array("M" => 45000, "C" => 15000, "D" => 0);
			case 215: return array("M" => 30000, "C" => 40000, "D" => 15000);
			case 211: return array("M" => 50000, "C" => 25000, "D" => 15000);
			case 213: return array("M" => 60000, "C" => 50000, "D" => 15000);
			case 214: return array("M" => 5000000, "C" => 4000000, "D" => 1000000);
			case 202: return array("M" => 2000, "C" => 2000, "D" => 0);
			case 203: return array("M" => 6000, "C" => 6000, "D" => 0);
			case 208: return array("M" => 10000, "C" => 20000, "D" => 10000);
			case 209: return array("M" => 10000, "C" => 6000, "D" => 2000);
			case 210: return array("M" => 0, "C" => 1000, "D" => 0);
			case 212: return array("M" => 0, "C" => 2000, "D" => 500);

			case 217: return array("M" => 2000, "C" => 2000, "D" => 1000);
			case 218: return array("M" => 85000, "C" => 55000, "D" => 2000);
			case 219: return array("M" => 8000, "C" => 15000, "D" => 8000);
			//
			case 401: return array("M" => 2000, "C" => 0, "D" => 0);
			case 402: return array("M" => 1500, "C" => 500, "D" => 0);
			case 403: return array("M" => 6000, "C" => 2000, "D" => 0);
			case 404: return array("M" => 20000, "C" => 15000, "D" => 2000);
			case 405: return array("M" => 2000, "C" => 6000, "D" => 0);
			case 406: return array("M" => 50000, "C" => 50000, "D" => 30000);
			case 407: return array("M" => 10000, "C" => 10000, "D" => 0);
			case 408: return array("M" => 50000, "C" => 50000, "D" => 0);
			case 409: return array("M" => 8000, "C" => 2000, "D" => 10000);
			case 410: return array("M" => 12500, "C" => 2500, "D" => 10000);
			case 502: return array("M" => 8000, "C" => 0, "D" => 2000);
			case 503: return array("M" => 12500, "C" => 2500, "D" => 10000);

			case LIGHTFIGHTER: return array("M" => 3000, "C" => 1000, "D" => 0);
			case HEAVYFIGHTER: return array("M" => 6000, "C" => 4000, "D" => 0);
			case CRUISER: return array("M" => 20000, "C" => 7000, "D" => 2000);
			case BATTLESHIP: return array("M" => 45000, "C" => 15000, "D" => 0);
			case BATTLECRUISER: return array("M" => 30000, "C" => 40000, "D" => 15000);
			case BOMBER: return array("M" => 50000, "C" => 25000, "D" => 15000);
			case DESTROYER: return array("M" => 60000, "C" => 50000, "D" => 15000);
			case DEATHSTAR: return array("M" => 5000000, "C" => 4000000, "D" => 1000000);
			case SMALLTRANSPORTER: return array("M" => 2000, "C" => 2000, "D" => 0);
			case BIGTRANSPORTER: return array("M" => 6000, "C" => 6000, "D" => 0);
			case COLONY: return array("M" => 10000, "C" => 20000, "D" => 10000);
			case RECYCLER: return array("M" => 10000, "C" => 6000, "D" => 2000);
			case SPY: return array("M" => 0, "C" => 1000, "D" => 0);
			case SUNSAT: return array("M" => 0, "C" => 2000, "D" => 500);
			//
			case ROCKETLAUNCHER: return array("M" => 2000, "C" => 0, "D" => 0);
			case LIGHTLASER: return array("M" => 1500, "C" => 500, "D" => 0);
			case HEAVYLASER: return array("M" => 6000, "C" => 2000, "D" => 0);
			case GAUSSCANNON: return array("M" => 20000, "C" => 15000, "D" => 2000);
			case IONCANNON: return array("M" => 2000, "C" => 6000, "D" => 0);
			case PLASMACANNON: return array("M" => 50000, "C" => 50000, "D" => 30000);
			case SMALLSHIELDDOME: return array("M" => 10000, "C" => 10000, "D" => 0);
			case LARGESHIELDDOME: return array("M" => 50000, "C" => 50000, "D" => 0);
			case MISSILEINTERCEPTOR: return array("M" => 8000, "C" => 2000, "D" => 10000);
			case INTERPLANETARYMISSILE: return array("M" => 12500, "C" => 2500, "D" => 10000);
			default: break;
		}

		return false;
	}

	function GetCapacity($strId) {
		switch ($strId) {
			case 204: return 50;
			case 205: return 100;
			case 206: return 800;
			case 207: return 1500;
			case 215: return 750;
			case 211: return 500;
			case 213: return 2000;
			case 214: return 1000000;

			case 202: return 5000;
			case 203: return 25000;
			case 208: return 7500;
			case 209: return 2000;
			case 210: return 0;
			case 212: return 0;

			case 217: return 0;
			case 218: return 10000;
			case 219: return 10000;
			//
			case 401: return 0;
			case 402: return 0;
			case 403: return 0;
			case 404: return 0;
			case 405: return 0;
			case 406: return 0;
			case 407: return 0;
			case 408: return 0;
			default: break;
		}

		return false;					
	}
	function GetBaseConsumption($strId) {
		switch ($strId) {
			case 204: return 20;
			case 205: return 75;
			case 206: return 300;
			case 207: return 500;
			case 215: return 250;
			case 211: return 1000;
			case 213: return 1000;
			case 214: return 1;
			case 202: return 20;
			case 203: return 50;
			case 208: return 1000;
			case 209: return 300;
			case 210: return 1;
			case 212: return 0;

			case 217: return 0;
			case 218: return 1100;
			case 219: return 300;
			//
			case 401: return -1;
			case 402: return -1;
			case 403: return -1;
			case 404: return -1;
			case 405: return -1;
			case 406: return -1;
			case 407: return -1;
			case 408: return -1;

			case LIGHTFIGHTER: return 20;
			case HEAVYFIGHTER: return 75;
			case CRUISER: return 300;
			case BATTLESHIP: return 500;
			case BATTLECRUISER: return 250;
			case BOMBER: return 1000;
			case DESTROYER: return 1000;
			case DEATHSTAR: return 1;
			case SMALLTRANSPORTER: return 20;
			case BIGTRANSPORTER: return 50;
			case COLONY: return 1000;
			case RECYCLER: return 300;
			case SPY: return 1;
			case SUNSAT: return 0;
			//
			case ROCKETLAUNCHER: return -1;
			case LIGHTLASER: return -1;
			case HEAVYLASER: return -1;
			case GAUSSCANNON: return -1;
			case IONCANNON: return -1;
			case PLASMACANNON: return -1;
			case SMALLSHIELDDOME: return -1;
			case LARGESHIELDDOME: return -1;
			case MISSILEINTERCEPTOR: return -1;
			case INTERPLANETARYMISSILE: return -1;
			default: break;
		}

		return false;
	}

    function GetServerData($strUni, $strDomain, $strTime) {
    	if (!isset($strTime)) $strTime = 1;
    	$varReturn = false;
        $xmlFile = 'xml/'.$strUni.'-'.$strDomain.'_serverData.xml';

        if (!file_exists($xmlFile) || ((time() - filemtime ($xmlFile)) >= $strTime * 24 * 60 * 60)) {
	        $url = 'https://s' . $strUni . '-' . $strDomain . '.ogame.gameforge.com/api/serverData.xml';
	        $flashRAW = file_get_contents($url);
	        $flashXML = simplexml_load_string($flashRAW);

            $xmlHandle = fopen($xmlFile, "w");
            $xmlString = $flashXML->asXML();
            fwrite($xmlHandle, $xmlString);
            fclose($xmlHandle);
        }

        $xml = simplexml_load_file($xmlFile);

		if ($xml != false) {
	        foreach ($xml->children() as $key => $serverData) {
	        	if ($key == "name") 									$varReturn["name"] = (string) $serverData; 
	        	if ($key == "speed") 									$varReturn["speed"] = (string) $serverData; 
	        	if ($key == "speedFleet") 								$varReturn["speedFleet"] = (string) $serverData; 
	        	if ($key == "galaxies") 								$varReturn["galaxies"] = (string) $serverData; 
	        	if ($key == "systems") 									$varReturn["systems"] = (string) $serverData; 
	        	if ($key == "acs") 										$varReturn["acs"] = (string) $serverData; 
	        	if ($key == "rapidFire") 								$varReturn["rapidFire"] = (string) $serverData; 
	        	if ($key == "defToTF") 									$varReturn["defToTF"] = (string) $serverData; 
	        	if ($key == "debrisFactor") 							$varReturn["debrisFactor"] = (string) $serverData; 
	        	if ($key == "debrisFactorDef") 							$varReturn["debrisFactorDef"] = (string) $serverData; 
	        	if ($key == "repairFactor") 							$varReturn["repairFactor"] = (string) $serverData; 
	        	if ($key == "newbieProtectionLimit") 					$varReturn["newbieProtectionLimit"] = (string) $serverData; 
	        	if ($key == "newbieProtectionHigh") 					$varReturn["newbieProtectionHigh"] = (string) $serverData; 
	        	if ($key == "topScore") 								$varReturn["topScore"] = (string) $serverData; 
	        	if ($key == "bonusFields") 								$varReturn["bonusFields"] = (string) $serverData; 
	        	if ($key == "donutGalaxy") 								$varReturn["donutGalaxy"] = (string) $serverData; 
	        	if ($key == "donutSystem") 								$varReturn["donutSystem"] = (string) $serverData; 
	        	if ($key == "wfEnabled") 								$varReturn["wfEnabled"] = (string) $serverData; 
	        	if ($key == "wfMinimumRessLost") 						$varReturn["wfMinimumRessLost"] = (string) $serverData; 
	        	if ($key == "wfMinimumLossPercentage") 					$varReturn["wfMinimumLossPercentage"] = (string) $serverData; 
	        	if ($key == "wfBasicPercentageRepairable") 				$varReturn["wfBasicPercentageRepairable"] = (string) $serverData; 
	        	if ($key == "globalDeuteriumSaveFactor") 				$varReturn["globalDeuteriumSaveFactor"] = (string) $serverData; 
	        	if ($key == "bashlimit") 								$varReturn["bashlimit"] = (string) $serverData; 
	        	if ($key == "probeCargo") 								$varReturn["probeCargo"] = (string) $serverData; 
	        	if ($key == "researchDurationDivisor") 					$varReturn["researchDurationDivisor"] = (int) $serverData; 
	        	if ($key == "marketplaceBasicTradeRatioMetal") 			$varReturn["marketplaceBasicTradeRatioMetal"] = (float) $serverData; 
	        	if ($key == "marketplaceBasicTradeRatioCrystal") 		$varReturn["marketplaceBasicTradeRatioCrystal"] = (float) $serverData; 
	        	if ($key == "marketplaceBasicTradeRatioDeuterium") 		$varReturn["marketplaceBasicTradeRatioDeuterium"] = (float) $serverData; 
	        	if ($key == "marketplaceTaxNotSold") 					$varReturn["marketplaceTaxNotSold"] = (float) $serverData; 
	        }
	    	return $varReturn;
	    }
	    else return false;
    }

	function GetConsumption($thisGalaxy, $thisSystem, $thisPlanet, $targetGalaxy, $targetSystem, $targetPlanet, $intBaseConsumption, $intShipsCount, $dblSpeed) {
		if (($thisSystem == $targetSystem) && ($thisGalaxy == $targetGalaxy)) {
			if ($thisPlanet == $targetPlanet) return 0;
			return round($intShipsCount * $intBaseConsumption * ((1000000 + 5000 * min(abs(500 - $thisPlanet + $targetPlanet), abs($thisPlanet - $targetPlanet))) / 35000000) * pow(($dblSpeed + 1), 2) + 1);
		}
		if (($thisSystem != $targetSystem) && ($thisGalaxy == $targetGalaxy)) {
			return round($intShipsCount * $intBaseConsumption * ((2700000 + 95000 * min(abs(500 - $targetSystem + $thisSystem), abs($targetSystem - $thisSystem))) / 35000000) * pow(($dblSpeed + 1), 2) + 1);
		}
		if ($thisGalaxy != $targetGalaxy) {
            $intDist = abs($thisGalaxy - $targetGalaxy);
            if ($intDist > 4) {
                switch ($intDist) {
                    case 5: return round($intShipsCount * $intBaseConsumption * ((20000000 * 4) / 35000000) * pow(($dblSpeed + 1), 2) + 1);
                    break;
                    case 6: return round($intShipsCount * $intBaseConsumption * ((20000000 * 3) / 35000000) * pow(($dblSpeed + 1), 2) + 1);
                    break;
                    case 7: return round($intShipsCount * $intBaseConsumption * ((20000000 * 2) / 35000000) * pow(($dblSpeed + 1), 2) + 1);
                    break;
                    case 8: return round($intShipsCount * $intBaseConsumption * ((20000000 * 1) / 35000000) * pow(($dblSpeed + 1), 2) + 1);
                    break;
                }
            } else return round($intShipsCount * $intBaseConsumption * ((20000000 * $intDist) / 35000000) * pow(($dblSpeed + 1), 2) + 1);
		}
	}

	function GetSumFleetConsumption($thisCoordinates, $targetCoordinates, $arrTemp, $dblSpeed) {
		$intResul = 0;

		$intResult = 0;
		$arrFleet = NULL;
		if ($arrTemp['type'] == 'round'){
			$arrFleet = $arrTemp['fleet'];
		}

		if ($arrFleet) {
			foreach ($arrFleet as $key => $value) {
				if ($value['name'] != "th") {
					if ($value['name'] == INTERPLANETARYMISSILE) {
						$name = SUNSAT;
					}
					else {
						$name = $value['name'];
					}
					$intBaseConsumption = GetBaseConsumption($name);
					if ($intBaseConsumption > 0) {
						$intResult +=  GetConsumption($thisCoordinates[GALAXY], $thisCoordinates[STAR], $thisCoordinates[PLANET], $targetCoordinates[GALAXY], $targetCoordinates[STAR], $targetCoordinates[PLANET], $intBaseConsumption, $value['count'], $dblSpeed);
					}
				}
			}
		}
		else {
			$intResult = 0;
		}

		return $intResult;
	}
	//Новая система расчета дейта под лвл движков

	/*
  	function GetOptionsShips($strId) {
    	switch ($strId) {
	      	case 202: return ['small-cargo', 5000, 0, 10, 5000];
	      	case 203: return ['large-cargo', 7500, 0, 50, 25000];
	      	case 204: return ['light-fighter', 12500, 0, 20, 50];
	      	case 205: return ['heavy-fighter', 10000, 1, 75, 100];
	      	case 206: return ['cruiser', 15000, 1, 300, 800];
	      	case 207: return ['battleship', 10000, 2, 500, 1500];
	      	case 208: return ['colony-ship', 2500, 1, 1000, 7500];
	      	case 209: return ['recycler', 2000, 0, 300, 20000];
	      	case 210: return ['esp-probe', 100000000, 0, 1, 5];
	      	case 211: return ['bomber', 4000, 1, 1000, 500];
	      	case 213: return ['destroyer', 5000, 2, 1000, 2000];
	      	case 214: return ['death-star', 100, 2, 1, 1000000];
	      	case 215: return ['battlecruiser', 10000, 2, 250, 750];
      		default: break;
    	}
    	return false;
  	}
  	function GetDriveBonuses($strId) {
    	switch ($strId) {
      		case 0: return 10;
      		case 1: return 20;
      		case 2: return 30;
      		default: break;
    	}
    	return false;
  	} 

  function getDistance($thisCoordinates, $targetCoordinates, $circularSystems, $numberOfSystems) {
    var_dump($targetCoordinates);
    $dst = 0;
    if (($thisCoordinates["GALAXY"] - $targetCoordinates["GALAXY"]) != 0) {
      $dst = abs($thisCoordinates["GALAXY"] - $targetCoordinates["GALAXY"]);
      if ($circularSystems)
        $dst = min($dst, $numberOfSystems - $dst);
      $dst *= 20000;
    } else if (($thisCoordinates["STAR"] - $targetCoordinates["STAR"]) != 0) {
      $dst = abs($thisCoordinates["STAR"] - $targetCoordinates["STAR"]);
      if ($circularSystems)
        $dst = min($dst, $numberOfSystems - $dst);
      $dst = $dst * 95 + 2700;
    } else if (($thisCoordinates["PLANET"] - $targetCoordinates["PLANET"]) != 0) {
      $dst = Math.abs($thisCoordinates["PLANET"] - $targetCoordinates["PLANET"]) * 5 + 1000;
    } else {
      $dst = 5;
    }
    return $dst;
  }
	function getFlightDuration($minSpeed, $distance, $speedPercent, $uniSpeedFactor) {
	  return round(((35000 / ($speedPercent / 10) * sqrt($distance * 10 / $minSpeed) + 10) / $uniSpeedFactor ));
	}

	function getDeutConsumption($arrFleet, $minSpeed, $distance, $duration, $uniSpeedFactor) {
	  $totalConsumption = 0;
	  $shipConsumption = 0;
	    foreach ($arrFleet as $key => $value) {
	      if ($value->count > 0) {
	          //$baseShipSpeed = (GetOptionsShips($value->ship_type)[1] * (1 + (GetDriveBonuses(GetOptionsShips($value->ship_type)[2])/100)));
	          //$shipSpeedValue =  35000 / ($duration * $uniSpeedFactor - 10) * sqrt($distance * 10 / $baseShipSpeed);
	          $shipSpeedValue =  35000 / ($duration * $uniSpeedFactor - 10) * sqrt($distance * 10 / GetOptionsShips($value->ship_type)[1]);
	          $shipConsumption = GetOptionsShips($value->ship_type)[3] * $value->count;
	          $totalConsumption += $shipConsumption * $distance / 35000 * (($shipSpeedValue / 10) + 1) * (($shipSpeedValue / 10) + 1);
	      }
	  }
	    $totalConsumption = round($totalConsumption) + 1;
	  return $totalConsumption;
	}
      $distance = getDistance($thisCoordinates, $targetCoordinates, true, 499);  
      $duration = getFlightDuration(7500, $distance, 100, 1);
      foreach ($attackers as $key => $value) {
        echo getDeutConsumption($value->fleet_composition, $minSpeed, $distance, $duration, 1);
      }	  	
	*/
	function GetSumFleetConsumption_v6 ($thisCoordinates, $targetCoordinates, $arrFleet, $dblSpeed, $deuteriumSaveFactor) {
		$intResul = 0;

		if ($arrFleet) {
			foreach ($arrFleet as $key => $value) {
				$intBaseConsumption = GetBaseConsumption($value->ship_type);
				if ($intBaseConsumption > 0) {
					$intResult +=  GetConsumption($thisCoordinates[GALAXY], $thisCoordinates[STAR], $thisCoordinates[PLANET], $targetCoordinates[GALAXY], $targetCoordinates[STAR], $targetCoordinates[PLANET], $intBaseConsumption, $value->count, $dblSpeed);
				}
			}
		}
		else {
			$intResult = 0;
		}

		return $intResult;
	}

	function GetIMG($strId) {
		switch ($strId) {
			case LIGHTFIGHTER: return LIGHTFIGHTER_IMG;
			case HEAVYFIGHTER: return HEAVYFIGHTER_IMG;
			case CRUISER: return CRUISER_IMG;
			case BATTLESHIP: return BATTLESHIP_IMG;
			case BATTLECRUISER: return BATTLECRUISER_IMG;
			case BOMBER: return BOMBER_IMG;
			case DESTROYER: return DESTROYER_IMG;
			case DEATHSTAR: return DEATHSTAR_IMG;
			case SMALLTRANSPORTER: return SMALLTRANSPORTER_IMG;
			case BIGTRANSPORTER: return BIGTRANSPORTER_IMG;
			case COLONY: return COLONY_IMG;
			case RECYCLER: return RECYCLER_IMG;
			case SPY: return SPY_IMG;
			case SUNSAT: return SUNSAT_IMG;
			//
			case ROCKETLAUNCHER: return ROCKETLAUNCHER_IMG;
			case LIGHTLASER: return LIGHTLASER_IMG;
			case HEAVYLASER: return HEAVYLASER_IMG;
			case GAUSSCANNON: return GAUSSCANNON_IMG;
			case IONCANNON: return IONCANNON_IMG;
			case PLASMACANNON: return PLASMACANNON_IMG;
			case SMALLSHIELDDOME: return SMALLSHIELDDOME_IMG;
			case LARGESHIELDDOME: return LARGESHIELDDOME_IMG;
			case MISSILEINTERCEPTOR: return MISSILEINTERCEPTOR_IMG;
			case INTERPLANETARYMISSILE: return INTERPLANETARYMISSILE_IMG;

			case 204: return LIGHTFIGHTER_IMG;
			case 205: return HEAVYFIGHTER_IMG;
			case 206: return CRUISER_IMG;
			case 207: return BATTLESHIP_IMG;
			case 215: return BATTLECRUISER_IMG;
			case 211: return BOMBER_IMG;
			case 213: return DESTROYER_IMG;
			case 214: return DEATHSTAR_IMG;
			case 202: return SMALLTRANSPORTER_IMG;
			case 203: return BIGTRANSPORTER_IMG;
			case 208: return COLONY_IMG;
			case 209: return RECYCLER_IMG;
			case 210: return SPY_IMG;
			case 212: return SUNSAT_IMG;
			//
			case 401: return ROCKETLAUNCHER_IMG;
			case 402: return LIGHTLASER_IMG;
			case 403: return HEAVYLASER_IMG;
			case 404: return GAUSSCANNON_IMG;
			case 405: return IONCANNON_IMG;
			case 406: return PLASMACANNON_IMG;
			case 407: return SMALLSHIELDDOME_IMG;
			case 408: return LARGESHIELDDOME_IMG;
			
			default: break;
		}

		return false;
	}

	function GetBaseAttack($strId) {
		switch ($strId) {
			case LIGHTFIGHTER: return 50;
			case HEAVYFIGHTER: return 150;
			case CRUISER: return 400;
			case BATTLESHIP: return 1000;
			case BATTLECRUISER: return 700;
			case BOMBER: return 1000;
			case DESTROYER: return 2000;
			case DEATHSTAR: return 200000;
			case SMALLTRANSPORTER: return 5;
			case BIGTRANSPORTER: return 5;
			case COLONY: return 50;
			case RECYCLER: return 1;
			case SPY: return 0.01;
			case SUNSAT: return 1;
			//
			case ROCKETLAUNCHER: return 80;
			case LIGHTLASER: return 100;
			case HEAVYLASER: return 250;
			case GAUSSCANNON: return 1100;
			case IONCANNON: return 150;
			case PLASMACANNON: return 3000;
			case SMALLSHIELDDOME: return 1;
			case LARGESHIELDDOME: return 1;
			case MISSILEINTERCEPTOR: return -1;
			case INTERPLANETARYMISSILE: return -1;
			default: break;
		}

		return false;
	}

	function GetWebSimName($strId) {
		switch ($strId) {
			case 202: return 0;
			case 203: return 1;
			case 204: return 2;
			case 205: return 3;
			case 206: return 4;
			case 207: return 5;
			case 215: return 13;
			case 211: return 9;
			case 213: return 11;
			case 214: return 12;
			case 208: return 6;
			case 209: return 7;
			case 210: return 8;
			case 212: return 10;
			//
			case 401: return 14;
			case 402: return 15;
			case 403: return 16;
			case 404: return 17;
			case 405: return 18;
			case 406: return 19;
			case 407: return 20;
			case 408: return 21;
			//
			default: return false;
		}
	}

	function ConvertTechnologies($arrTechnologies) {
		foreach ($arrTechnologies as $key => $value) {
			$arrTechnologies[$key] = (double) ((str_replace('%', '', $arrTechnologies[$key]) / 100) + 1);
		}

		return $arrTechnologies;
	}

	function RestoreTechnologies($arrTechnologies) {
		foreach ($arrTechnologies as $key => $value) {
			$arrTechnologies[$key] = (string) (($arrTechnologies[$key] - 1)*100);
		}

		return $arrTechnologies;
	}

	function GetTechnologiesForLog($arrTechnologies, $blnHideTech, $v6) {
		if ((!$arrTechnologies) || ($arrTechnologies == UNDEFINED)) {
			return "";
		}

		if ($blnHideTech) {
	 		$strReturn = "(X%,X%,X%)";
	 	}
	 	else {
			if (!isset($v6)) $arrTechnologies = RestoreTechnologies($arrTechnologies);

	            if ($v6) $strReturn = "(".$arrTechnologies[0]."%,".$arrTechnologies[1]."%,".$arrTechnologies[2]."%)";
	            else $strReturn = "(".$arrTechnologies[WEAPONS]."%,".$arrTechnologies[SHIELDS]."%,".$arrTechnologies[ARMORS]."%)";		}

		return $strReturn;
	}

	function GetCoordinatesForLog($arrCoordinates, $blnHideCoord) {
		if ((!$arrCoordinates) || ($arrCoordinates == UNDEFINED)) {
			return "";
		}

		if ($blnHideCoord) {
	 		$strReturn = "[X:X:X]";
	 	}
	 	else {
	 		$strReturn = $arrCoordinates;
		}

		return $strReturn;
	}

	function DetermineObjectName($intArmors, $intWeapons, $arrTechnologies) {
		$arrIds = array(LIGHTFIGHTER, HEAVYFIGHTER, CRUISER, BATTLESHIP, BATTLECRUISER, BOMBER, DESTROYER, DEATHSTAR, SMALLTRANSPORTER, BIGTRANSPORTER, COLONY, RECYCLER, SPY, SUNSAT, ROCKETLAUNCHER, LIGHTLASER, HEAVYLASER, GAUSSCANNON, IONCANNON, PLASMACANNON, SMALLSHIELDDOME, LARGESHIELDDOME);
		$intBaseStructure = round($intArmors / $arrTechnologies[ARMORS] * 10);
		$intBaseAttack = round($intWeapons / $arrTechnologies[WEAPONS]);

		for ($i = 0; $i < count($arrIds); $i++) {
			if (($intBaseStructure == round(GetBaseStructure($arrIds[$i]))) && ($intBaseAttack == round(GetBaseAttack($arrIds[$i])))) {
				return $arrIds[$i];
			}
			/*else
			{
				echo $intBaseStructure .' != ' . GetBaseStructure($arrIds[$i]) . '<br>';
				echo $intBaseAttack .' != ' . GetBaseAttack($arrIds[$i]) . '<br>';
			}*/
		}

		return false;
	}

	function GetSumFleetStructure($arrTemp) {
		$intResult = 0;
		$arrFleet = NULL;
		if ($arrTemp['type'] == 'round'){
			$arrFleet = $arrTemp['fleet'];
		}
		if ($arrFleet) {
			foreach ($arrFleet as $key => $value) {
				if ($value['name'] != "th") {
					if ($value['name'] == INTERPLANETARYMISSILE) {
						$name = SUNSAT;
					}
					else {
						$name = $value['name'];
					}
					$intResult += GetBaseStructure($name) * $value['count'];
				}
			}
		}
		else {
			$intResult = 0;
		}

		return $intResult;
	}

	function IsDefense($strId) {
		switch ($strId) {
			case ROCKETLAUNCHER: return true;
			case LIGHTLASER: return true;
			case HEAVYLASER: return true;
			case GAUSSCANNON: return true;
			case IONCANNON: return true;
			case PLASMACANNON: return true;
			case SMALLSHIELDDOME: return true;
			case LARGESHIELDDOME: return true;
			default: false;
		}
		return false;
	}

	function GetSumFleetResources($arrTemp, $blnOnlyDefense) {
		$arrResult['SUM'] = 0;
		$arrResult['M'] = 0;
		$arrResult['C'] = 0;
		$arrResult['D'] = 0;

		$arrFleet = NULL;
		if ($arrTemp['type'] == 'round'){
			$arrFleet = $arrTemp['fleet'];
		}
		if ($arrFleet) {
			foreach ($arrFleet as $key => $value) {
				if ($value['name'] != "th") {
					if (
							(
								($blnOnlyDefense) && (IsDefense($value['name']))
							) ||
							(!$blnOnlyDefense)
						) {

						if ($value['name'] == INTERPLANETARYMISSILE) {
							$name = SUNSAT;
						}
						else {
							$name = $value['name'];
						}

						$arrTempResult = GetBaseCost($name);
						$arrResult['M'] += $arrTempResult['M'] * $value['count'];
						$arrResult['C'] += $arrTempResult['C'] * $value['count'];
						$arrResult['D'] += $arrTempResult['D'] * $value['count'];
						$arrResult['SUM'] += ($arrTempResult['M'] + $arrTempResult['C'] + $arrTempResult['D']) * $value['count'];
					}
				}
			}
		}
		else {
			$arrResult['SUM'] = 0;
			$arrResult['M'] = 0;
			$arrResult['C'] = 0;
			$arrResult['D'] = 0;
		}

		return $arrResult;
	}

	function GetPlayerNameFromString($strInput, $arrPlayerNames)
	{
		foreach ($arrPlayerNames as $strPlayeName) {
			if (stristr($strInput, $strPlayeName)) {
				return $strPlayeName;
				//return substr(stristr($strInput, $strPlayeName), 0, strlen($strPlayeName));
			}
		}

		return false;
	}

	function GetLogListFilePath($strFolder) {
		$strResult = "";
		switch ($strFolder) {
			case FOLDER_UPLOAD:
				$strResult = FOLDER_UPLOAD . "/public_list.txt";
				break;
			case FOLDER_UPLOAD . "X":
				$strResult = FOLDER_UPLOAD . "/all_list.txt";
				break;
			case FOLDER_UPLOAD_ERR:
				$strResult = FOLDER_UPLOAD_ERR . "/err_list.txt";
				break;
			case FOLDER_UPLOAD_TMP:
				$strResult = FOLDER_UPLOAD_TMP . "/tmp_list.txt";
				break;
			default:
				break;
		}
		return $strResult;
	}

	function DeleteLog($strX, $strSource) {
		$strId = base64_decode(base64_decode($strX));
		DeleteFromList($strId, GetLogListFilePath(FOLDER_UPLOAD)); //FIX ME
		DeleteFromList($strId, GetLogListFilePath(FOLDER_UPLOAD . "X")); //FIX ME
		$strPath = $strSource . "/" . $strId;
		if (file_exists($strPath))
			return unlink($strPath);
		else
			return false;
	}

	function DeleteFromList($strId, $strListPath) {
		$strFile = file_get_contents($strListPath);
		$strReplaced = preg_replace("/id=<" . $strId . "> domain=<.+?> uni=<.+?> title=<.+?> time=<.+?>/", "", $strFile);
		if ($strFile != $strReplaced) {
			$strReplaced = str_replace("\n\n", "\n", $strReplaced);
			file_put_contents($strListPath, $strReplaced);
		}
	}

	function DoCommand($strCommand) {
		$strTableInner .= "<tr><td align='left'>";
		$strTableInner .= "<font face='Arial' color='" . GREEN_COMMON . "' size='2'>Command: </font><font face='Arial' color='" . RED_COMMON . "' size='2'>".$strCommand."</font><br>";

		if (md5($strCommand) == '762f77e2256c543f893bac364de6ec84') {
			$varNothing = false;
			$objLog = new cLog($varNothing, 'empty');
			$arrF = array(FOLDER_UPLOAD, FOLDER_UPLOAD_ERR, FOLDER_UPLOAD_TMP, FOLDER_UPLOAD_SPY);
			foreach ($arrF as $strF) {
				if ($objLog->GetLogsCount($arrIds, $strF)) {
					$strTableInner .= "<font face='Arial' color='" . GREEN_COMMON . "' size='2'><br>Records found in " . $strF . ": ".count($arrIds)."</font><br>";
					foreach ($arrIds as $key => $value)
					{
					    $strTableInner .= "<font face='Arial' color='" . GREEN_DARK . "' size='2'>".$key.".\t".$value."</font><br>";
					}
				}
				else {
					$strTableInner .= "<font face='Arial' color='" . GREEN_COMMON . "' size='2'><br>Records found in " . $strF . ": ".count($arrIds)."</font><br>";
					$strTableInner .= "<font face='Arial' color='" . YELLOW_COMMON . "' size='2'>Records found: 0</font><br>";
				}
			}
		}

		if (md5($strCommand) == '230d98ada14549015642de9b92f8bf32') {
			$objLog = new cLog(false, 'empty');

			if ($objLog->DeleteAll($arrIds)) {
				$strTableInner .= "<font face='Arial' color='" . GREEN_COMMON . "' size='2'>Records deleted: ".count($arrIds)."</font><br>";
				foreach ($arrIds as $key => $value)
				{
				    $strTableInner .= "<font face='Arial' color='" . GREEN_DARK . "' size='2'>".$key.". ".$value."</font><br>";
				}
			}
			else {
				$strTableInner .= "<font face='Arial' color='" . GREEN_COMMON . "' size='2'><br>Records found in " . FOLDER_UPLOAD . ": ".count($arrIds)."</font><br>";
				$strTableInner .= "<font face='Arial' color='" . YELLOW_COMMON . "' size='2'>Records found: 0</font><br>";
			}
		}

		$strTableInner .= "</td></tr>";

		ShowHTML($strTableInner);
	}

	function NameUni($intUni) {
		global $NameUni;
		foreach ($NameUni as $key => $value) {
			if ($key == $intUni) $strResult = $value[0];
		}
		if (!isset($strResult)) return "uni" . $intUni;
		else return $strResult;
	}

	function ShortNameUni($intUni) {
		global $NameUni;
		foreach ($NameUni as $key => $value) {
			if ($key == $intUni) $strResult = $value[1];
		}
		if (!isset($strResult)) return $intUni;
		else return $strResult;
	}

	function read_file_tail($file, $lines) //http://ru2.php.net/manual/en/function.fseek.php
	{
		$handle = fopen($file, "r");
		$linecounter = $lines;
		$pos = -2;
		$beginning = false;
		$text = array();
		while ($linecounter > 0) {
			$t = " ";
			while ($t != "\n") {
				if(fseek($handle, $pos, SEEK_END) == -1) {
					$beginning = true; break;
				}
				$t = fgetc($handle);
				$pos --;
			}
			$linecounter --;
			if($beginning) rewind($handle);
			$text[$lines-$linecounter-1] = fgets($handle);
			if($beginning) break;
		}
		fclose ($handle);
		return $text;
		//return array_reverse($text); // array_reverse is optional: you can also just return the $text array which consists of the file's lines.
	}

	function PrepareNumber($varN) {
		if ($varN > 0) return "<font color='" . GREEN_COMMON . "'>" . NumberToString($varN) . "</font>";
		if ($varN == 0) return "<font color='" . YELLOW_COMMON . "'>" . NumberToString($varN) . "</font>";
		if ($varN < 0) return "<font color='" . RED_COMMON . "'>" . NumberToString($varN) . "</font>";
	}

	function EchoXSS($strEcho) {
		echo "<script>window.name = \"" . $strEcho . "\"</script>";
		return true;
	}

	function GarbageFix($strHTML) {
		$search = array(
					"'<style.+?<\/style>'is",
					"'<script.+?</script>'is",
					"'<div style.+?</div>'is",
					"'<div firebugversion.+?</div>'is"
					);
		$strHTML = preg_replace($search, "", $strHTML);
		$strHTML = preg_replace("'<center><form.+$'is", "</body></html>", $strHTML);
		return $strHTML;
	}

	function getUnorderedList( $matches )
	{
	  $list = '<ul>';
	  $tmp = trim( $matches[1] );
	  $tmp = substr( $tmp, 3 );
	  $tmpArray = explode( '[*]', $tmp );
	  $elements = '';
	  foreach ( $tmpArray as $value ) {
	    $elements = $elements.'<li>'.trim($value).'</li>';
	  }
	  $list = $list.$elements;
	  $list = $list.'</ul>';
	  return $list;
	}

	function split_text($matches)
	{
	  return wordwrap($matches[1], 35, ' ',1);
	}

	function getOrderedList( $matches )
	{
	  if ( $matches[1] == '1' )
	    $list = '<ol type="1">';
	  else
	    $list = '<ol type="a">';
	  $tmp = trim( $matches[2] );
	  $tmp = substr( $tmp, 3 );
	  $tmpArray = explode( '[*]', $tmp );

	  $elements = '';
	  foreach ( $tmpArray as $value ) {
	    $elements = $elements.'<li>'.trim($value).'</li>';
	  }
	  $list = $list.$elements;
	  $list = $list.'</ol>';
	  return $list;
	}

	// Функция обработки bbCode
	function print_page($message)
	{
	  // Разрезаем слишком длинные слова
	    $message = preg_replace_callback(
	              "|([a-zа-я\d!]{35,})|i",
	              "split_text",
	              $message);

	  // Тэги - [code], [php]
	  preg_match_all( "#\[php\](.+)\[\/php\]#isU", $message, $matches );
	  $cnt = count( $matches[0] );
	  for ( $i = 0; $i < $cnt; $i++ ) {
	    $phpBlocks[] = '<div class="codePHP">'.highlight_string( $matches[1][$i], true ).'</div>';
	    $uniqidPHP = '[php_'.uniqid('').']';
	    $uniqidsPHP[] = $uniqidPHP;
	    $message = str_replace( $matches[0][$i], $uniqidPHP, $message );
	  }

	  $spaces = array( ' ', "\t" );
	  $entities = array( '&nbsp;', '&nbsp;&nbsp;&nbsp;&nbsp;' );

	  preg_match_all( "#\[code\](.+)\[\/code\]#isU", $message, $matches );
	  $cnt = count( $matches[0] );

	  for ( $i = 0; $i < $cnt; $i++ ) {
	    $codeBlocks[] = '<div class="code">'.nl2br( str_replace( $spaces, $entities, htmlspecialchars( $matches[1][$i] ) ) ).'</div>';
	    $uniqidCode = '[code_'.uniqid('').']';
	    $uniqidsCode[] = $uniqidCode;
	    $message = str_replace( $matches[0][$i], $uniqidCode, $message );
	  }

	  $strQuote =	'
		 				<blockquote  class="quoteBox">
								<h3>
									Quoted
								</h3>
								\\1
						</blockquote>
					';

	  $message = htmlspecialchars( $message );
	  $message = preg_replace("#\[b\](.+)\[\/b\]#isU", '<b>\\1</b>', $message);
	  $message = preg_replace("#\[s\](.+)\[\/s\]#isU", '<strike>\\1</strike>', $message);

	  $message = preg_replace("#\[left\](.+)\[\/left\]#isU", '<div align="left">\\1</div>', $message);
	  $message = preg_replace("#\[right\](.+)\[\/right\]#isU", '<div align="right">\\1</div>', $message);
	  $message = preg_replace("#\[justify\](.+)\[\/justify\]#isU", '<div align="justify">\\1</div>', $message);
	  $message = preg_replace("#\[center\](.+)\[\/center\]#isU", '<div style="margin: auto; text-align: center; width: 100%;">\\1</div>', $message);

	  $message = preg_replace("#\[i\](.+)\[\/i\]#isU", '<i>\\1</i>', $message);
	  $message = preg_replace("#\[u\](.+)\[\/u\]#isU", '<u>\\1</u>', $message);
	  $message = preg_replace("#\[quote\](.+)\[\/quote\]#isU",$strQuote,$message);
	  $message = preg_replace("#\[quote=&quot;([- 0-9a-zа-яА-Я]{1,30})&quot;\](.+)\[\/quote\]#isU",



	   '<div class="quoteHead">\\1 пишет:</div><div class="quoteContent">\\2</div>', $message);
	  $message = preg_replace("#\[url\][\s]*([\S]+)[\s]*\[\/url\]#isU",'<a href="\\1" target="_blank">\\1</a>',$message);
	  $message = preg_replace("#\[url[\s]*=[\s]*([\S]+)[\s]*\][\s]*([^\[]*)\[/url\]#isU",
	                             '<a href="\\1" target="_blank">\\2</a>',
	                             $message);
	  $message = preg_replace("#\[img\][\s]*([\S]+)[\s]*\[\/img\]#isU",'<img src="\\1" alt="" />',$message);
	  $message = preg_replace("#\[color=red\](.+)\[\/color\]#isU",'<span style="color:#FF0000">\\1</span>',$message);
	  $message = preg_replace("#\[color=green\](.+)\[\/color\]#isU",'<span style="color:#008000">\\1</span>',$message);
	  $message = preg_replace("#\[color=blue\](.+)\[\/color\]#isU",'<span style="color:#0000FF">\\1</span>',$message);
	  $message = preg_replace_callback("#\[list\]\s*((?:\[\*\].+)+)\[\/list\]#siU",'getUnorderedList',$message);
	  $message = preg_replace_callback("#\[list=([a|1])\]\s*((?:\[\*\].+)+)\[\/list\]#siU", 'getOrderedList',$message);

	  $message = nl2br( $message);

	  if ( isset( $uniqidCode ) ) $message = str_replace( $uniqidsCode, $codeBlocks, $message );
	  if ( isset( $uniqidPHP ) ) $message = str_replace( $uniqidsPHP, $phpBlocks, $message );

	  return $message;
	}

	function my_hex2bin($hexdata) {
	  $bindata="";

	  for ($i=0;$i<strlen($hexdata);$i+=2) {
	    $bindata.=chr(hexdec(substr($hexdata,$i,2)));
	  }

	  return $bindata;
	}

	function GetUserIDFromSession() {
		$intUserID = 0;
		if (key_exists('account',$_SESSION))
		if (key_exists('id',$_SESSION['account']))
		$intUserID = $_SESSION['account']['id'];

	 	return $intUserID;
	}

	function SetLang() {
		if (!$_SESSION["lang"]) {
			if (!$_COOKIE["lang"]) {
				$strLang = get_lang();
				$strLang = $strLang[0];
			}
			else {
				$strLang = $_COOKIE["lang"];
			}
			switch (strtolower($strLang)) {
				case "bg": $_SESSION["lang"] = "bg"; break;
				case "de": $_SESSION["lang"] = "de"; break;
				case "en": $_SESSION["lang"] = "en"; break;
				case "ru": $_SESSION["lang"] = "ru"; break;
				case "ua": $_SESSION["lang"] = "ua"; break;
				default: $_SESSION["lang"] = "en";
			}
		}
	}

	function GetOpponentsByTitle($strTitle) {
		$strAttackers = explode("vs.", $strTitle);
		$strAttackers = $strAttackers[0];
		$strDefenders = explode("vs.", $strTitle);
		$strDefenders = $strDefenders[1];

		$arrAttackers = explode(",", $strAttackers);
		$arrDefenders = explode(",", $strDefenders);

		foreach ($arrAttackers as $key => $value) {
			$arrAttackers[$key] = trim($arrAttackers[$key]);
		}

		foreach ($arrDefenders as $key => $value) {
			$arrDefenders[$key] = trim($arrDefenders[$key]);
		}

		$arrOpponents = NULL;
		$arrOpponents['arrAttackers'] = $arrAttackers;
		$arrOpponents['arrDefenders'] = $arrDefenders;

	 	return $arrOpponents;
	}

	function ShortPName($sPName) {
		if (mb_detect_encoding($sPName) == 'UTF-8') {
			$iWhiteSpaces = substr_count(substr($sPName, 0, 11 * 2), ' ');
			if (strlen($sPName) <= 12 * 2 - $iWhiteSpaces) return $sPName;
			return htmlspecialchars(substr($sPName, 0, 11 * 2 - $iWhiteSpaces) . '...');
		}
		else {
			if (strlen($sPName) <= 12) return $sPName;
			return htmlspecialchars(substr($sPName, 0, 11) . '...');
		}
	}

	function AbbrShipName($sSName) {
		$sReturn = mb_convert_encoding($sSName, "UTF-8", "auto");
		$sReturn = str_replace('Бомбардировщик', 'Бомб.', $sReturn);
		$sReturn = str_replace('Переработчик', 'Перераб.', $sReturn);
		$sReturn = str_replace('Тяж. лазер', 'Т. лазер', $sReturn);
		$sReturn = str_replace('Лёг. лазер', 'Л. лазер', $sReturn);
		$sReturn = str_replace('Солн. спутник', 'С. спут.', $sReturn);
		$sReturn = str_replace('М. купол', 'М. куп.', $sReturn);
		$sReturn = str_replace('Б. купол', 'Б. куп.', $sReturn);
		$sReturn = str_replace('Колонизатор', 'Колониз.', $sReturn);

		return $sReturn;
	}

	function ShortShipName($strName) {
		$strName = mb_convert_encoding($strName, "UTF-8", "auto");

		if (strlen($strName) <= 8 * 2) {
			return $strName;
		}

		$intN = preg_match('/\./', $strName);
		$intN2 = preg_match('/ /', $strName);

		if (($intN == 0) && ($intN2 == 0)) {
			return (substr($strName, 0, 7 * 2) . '~');
		}

		if (($intN == 0) && ($intN2 == 1)) {
			$arrName = explode(' ', $strName);
			return (substr($arrName[0], 0, 1 * 2) . '. ' . substr($arrName[1], 0, 6 * 2) . '~');
		}

		if (($intN == 1) && ($intN2 == 1)) {
			return (substr($strName, 0, 7 * 2) . '~');
		}
		if ($intN == 2) {
			return (substr($strName, 0, 7 * 2) . '~');
		}
		return $strName;
	}

	function utf8_strlen($sInput) {
	    $iCount = 0;
	    for ($i = 0; $i < strlen($sInput); $i++) {
	        $sValue = ord($sInput[$i]);
	        if($sValue > 127) {
	            if($sValue >= 192 && $sValue <= 223)
	                $i++;
	            elseif($sValue >= 224 && $sValue <= 239)
	                $i = $i + 2;
	            elseif($sValue >= 240 && $sValue <= 247)
	                $i = $i + 3;
	            else
	                //die('Not a UTF-8 compatible string');
	                return iconv_strlen($sInput);
			}
	        $iCount++;
		}
	    return $iCount;
    }

    function GetServersLobby () {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://lobby.ogame.gameforge.com/api/servers');
        curl_setopt($ch, CURLOPT_REFERER, 'https://lobby.ogame.gameforge.com?language=ru');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:61.0) Gecko/20100101 Firefox/61.0');
        $varResult = json_decode(curl_exec($ch));
        curl_close($ch);
        return $varResult;
    }
?>
