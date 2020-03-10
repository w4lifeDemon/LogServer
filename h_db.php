<?php
	class cDB {
		static function InitDB() {
			$objLink = mysqli_connect(DB_HOST, DB_USER, DB_PSWD, DB_NAME);
			if (!$objLink) {
				LogError('cDB::InitDB', 'mysqli_connect failed, strQuery = ' . $strQuery);
				return false;
			}

			$strDate=date("y-m-d");

			//<T_TEMP_USERS>
				//---------------------------------------------------------------------------------------------------
				$strQuery = 'DROP TABLE `T_TEMP_USERS`;';
				$varResult = mysqli_query($objLink, $strQuery);
				//---------------------------------------------------------------------------------------------------
				$strQuery = "CREATE TABLE `T_TEMP_USERS` (
  								`user_login` text NOT NULL,
								`user_password` text NOT NULL,
								`user_mail` text NOT NULL,
								`reg_date` date NOT NULL default '0000-00-00',
								PRIMARY KEY  (`user_login`(12))
								) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
				$varResult = mysqli_query($objLink, $strQuery);
				if (!$varResult) {
					LogError('cDB::InitDB', 'mysqli_query failed, strQuery = ' . $strQuery);
					return false;
				}
				//---------------------------------------------------------------------------------------------------
			//</T_TEMP_USERS>

			//<T_USERS>
				//---------------------------------------------------------------------------------------------------
				$strQuery = 'DROP TABLE `T_USERS`;';
				$varResult = mysqli_query($objLink, $strQuery);
				//---------------------------------------------------------------------------------------------------
				$strQuery = "CREATE TABLE `T_USERS` (
                                `user_id` int(11) NOT NULL DEFAULT '0',
                                `user_login` text NOT NULL,
                                `user_password` text NOT NULL,
                                `user_mail` text NOT NULL,
                                `role` int(11) NOT NULL DEFAULT '0',
                                `reg_date` date NOT NULL DEFAULT '0000-00-00',
                                `settings` text NOT NULL,
                                `logs_count` int(11) NOT NULL DEFAULT '0',
                                `last_visit` date NOT NULL DEFAULT '0000-00-00',
                                `registration` tinyint(1) NOT NULL DEFAULT '0',
                                `post_status` tinyint(1) NOT NULL DEFAULT '0',
                                PRIMARY KEY (`user_id`)
                              ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

				$varResult = mysqli_query($objLink, $strQuery);
				if (!$varResult) {
					LogError('cDB::InitDB', 'mysqli_query failed, strQuery = ' . $strQuery);
					return false;
				}
				//---------------------------------------------------------------------------------------------------
				$strQuery = "INSERT INTO   `T_USERS` (
								`user_id`,
								`user_login`,
								`user_password`,
								`user_mail`,
								`role`,
								`reg_date`,
								`settings`,
								`logs_count`,

								`last_visit`)
							VALUES (
							'0', 'guest', '084e0343a0486ff05530df6c705c8bb4', 'guest@logserver.net', '0', '$strDate', '', 0, '$strDate'
							);";
				//084e0343a0486ff05530df6c705c8bb4 - guest
				$varResult = mysqli_query($objLink, $strQuery);
				if (!$varResult) {
					LogError('cDB::InitDB', 'mysqli_query failed, strQuery = ' . $strQuery);
					return false;
				}

				$strQuery = "INSERT INTO   `T_USERS` (
								`user_id`,
								`user_login`,
								`user_password`,
								`user_mail`,
								`role`,
								`reg_date`,
								`settings`,
								`logs_count`,
								`last_visit`)
							VALUES (
							'1', 'admin', 'f014714327e5e942cd8cab723dc6e490', 'SNBulgakov@yandex.ru', '9', '$strDate', '', 0, '$strDate'
							);";
				//f014714327e5e942cd8cab723dc6e490 - 110022
				$varResult = mysqli_query($objLink, $strQuery);
				if (!$varResult) {
					LogError('cDB::InitDB', 'mysqli_query failed, strQuery = ' . $strQuery);
					return false;
				}
				//---------------------------------------------------------------------------------------------------
			//</T_USERS>

			//<T_LOGS_ACCOUNT>
				//---------------------------------------------------------------------------------------------------
				$strQuery = 'DROP TABLE `T_LOGS_ACCOUNT`;';
				$varResult = mysqli_query($objLink, $strQuery);

				$strQuery = "CREATE TABLE `T_LOGS_ACCOUNT` (
                                `user_id` int(11) NOT NULL DEFAULT '0',
                                `type` int(1) DEFAULT NULL,
                                `content` LONGBLOB NOT NULL,
                                PRIMARY KEY (`user_id`)
                              ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

				$varResult = mysqli_query($objLink, $strQuery);
				if (!$varResult) {
					LogError('cDB::InitDB', 'mysqli_query failed, strQuery = ' . $strQuery);
					return false;
				}
				//---------------------------------------------------------------------------------------------------
			//</T_LOGS_ACCOUNT>

			//<T_LOGS_N>
				//---------------------------------------------------------------------------------------------------
				$strQuery = 'DROP TABLE `T_LOGS_N`;';
				$varResult = mysqli_query($objLink, $strQuery);
				//---------------------------------------------------------------------------------------------------
				$strQuery = "CREATE TABLE `T_LOGS_N` (
                                `log_id` varchar(36) NOT NULL DEFAULT '',
                                `user_id` int(11) NOT NULL DEFAULT '0',
                                `public` tinyint(1) NOT NULL DEFAULT '0',
                                `date` int(11) NOT NULL DEFAULT '0',
                                `views` int(11) NOT NULL DEFAULT '0',
                                `universe` int(11) NOT NULL DEFAULT '0',
                                `domain` varchar(10) NOT NULL DEFAULT '',
                                `losses` bigint(11) NOT NULL DEFAULT '0',
                                `title` text NOT NULL,
                                `obj_log` blob NOT NULL,
                                `last_view` int(11) NOT NULL DEFAULT '0',
                                `html_log` blob NOT NULL,
                                `bln_post` tinyint(1) NOT NULL DEFAULT '0',
                                PRIMARY KEY (`log_id`),
                                KEY `views` (`views`),
                                KEY `date` (`date`),
                                FULLTEXT KEY `log_id` (`log_id`)
                              ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

				$varResult = mysqli_query($objLink, $strQuery);
				if (!$varResult) {
					LogError('cDB::InitDB', 'mysqli_query failed, strQuery = ' . $strQuery);
					return false;
				}
				//---------------------------------------------------------------------------------------------------
			//</T_LOGS_N>

			//<T_PLAYERS_ID>
				//---------------------------------------------------------------------------------------------------
				$strQuery = 'DROP TABLE `T_PLAYERS_ID`;';
				$varResult = mysqli_query($objLink, $strQuery);
				//---------------------------------------------------------------------------------------------------
				$strQuery = "CREATE TABLE `T_PLAYERS_ID` (
								`player_name` varchar(20) NOT NULL default '',
								`player_id` int(11) NOT NULL auto_increment,
								`logs_count` int(11) NOT NULL default '0',
								PRIMARY KEY  (`player_name`),
								KEY `player_id` (`player_id`)
							) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;";

				$varResult = mysqli_query($objLink, $strQuery);
				if (!$varResult) {
					LogError('cDB::InitDB', 'mysqli_query failed, strQuery = ' . $strQuery);
					return false;
				}
				//---------------------------------------------------------------------------------------------------
			//</T_PLAYERS_ID>

			//<T_PLAYERS_LOGS>
				//---------------------------------------------------------------------------------------------------
				$strQuery = 'DROP TABLE `T_PLAYERS_LOGS`;';
				$varResult = mysqli_query($objLink, $strQuery);
				//---------------------------------------------------------------------------------------------------
				$strQuery = "CREATE TABLE `T_PLAYERS_LOGS` (
								`player_id` int(11) NOT NULL default '0',
								`log_id` varchar(36) NOT NULL,
								`role` tinyint(1) NOT NULL default '0',
								PRIMARY KEY  (`player_id`,`log_id`(36))
							) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

				$varResult = mysqli_query($objLink, $strQuery);
				if (!$varResult) {
					LogError('cDB::InitDB', 'mysqli_query failed, strQuery = ' . $strQuery);
					return false;
				}
				//---------------------------------------------------------------------------------------------------
			//</T_PLAYERS_LOGS>

			//<T_ESP>
				//---------------------------------------------------------------------------------------------------
				$strQuery = 'DROP TABLE `T_ESP`;';
				$varResult = mysqli_query($objLink, $strQuery);

				$strQuery = "CREATE TABLE `T_ESP` (
                                `esp_id` int(11) NOT NULL DEFAULT '0',
                                `user_id` int(11) NOT NULL DEFAULT '0',
                                `type` int(1) DEFAULT NULL,
                                `content` LONGBLOB NOT NULL,
                                PRIMARY KEY (`user_id`)
                              ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

				$varResult = mysqli_query($objLink, $strQuery);
				if (!$varResult) {
					LogError('cDB::InitDB', 'mysqli_query failed, strQuery = ' . $strQuery);
					return false;
				}
				//---------------------------------------------------------------------------------------------------
			//</T_ESP>

			if (!mysqli_close($objLink)) {
				LogError('cDB::InitDB', 'mysqli_close failed, strQuery = ' . $strQuery);
				return false;
			}

			return true;
		}

		static function QueryDB($strQuery) {
			$objLink = mysqli_connect(DB_HOST, DB_USER, DB_PSWD, DB_NAME);

			if (!$objLink) {
				LogError('cDB::QueryDB', 'mysqli_connect failed, strQuery = ' . $strQuery);
				return false;
			}

			if (!mysqli_query($objLink, "SET NAMES UTF8")){
				LogError('cDB::QueryDB', '40101 SET NAMES failed, strQuery = ' . $strQuery);
				return false;
			}

			$varResult = mysqli_query($objLink, $strQuery);

			if (!$varResult) {
				echo mysqli_error($objLink);
				return false;
			}
			if (!mysqli_close($objLink)) {
				LogError('cDB::QueryDB', 'mysqli_close failed, strQuery = ' . $strQuery);
				return false;
			}
			return $varResult;
		}

		static function QueryDBSpec($strQuery) {
			$objLink = mysqli_connect(DB_SPEC_HOST, DB_SPEC_USER, DB_SPEC_PSWD, DB_SPEC_NAME);

			if (!$objLink) {
				LogError('cDB::QueryDBSpec', 'mysqli_connect failed, strQuery = ' . $strQuery);
				return false;
			}

			if (!mysqli_query($objLink, "SET NAMES UTF8")){
				LogError('cDB::QueryDBSpec', '40101 SET NAMES failed, strQuery = ' . $strQuery);
				return false;
			}

			$varResult = mysqli_query($objLink, $strQuery);

			if (!$varResult) {
				LogError('cDB::QueryDBSpec', 'mysqli_query failed, strQuery = ' . $strQuery);
				return false;
			}
			if (!mysqli_close($objLink)) {
				LogError('cDB::QueryDBSpec', 'mysqli_close failed, strQuery = ' . $strQuery);
				return false;
			}
			return $varResult;
		}

//made by Zmei
		static function EditLogByID($strID){
            if ($strID) {
    			$strQuery = "SELECT `log_id`, `user_id`, `date`, `universe`, `domain`, `title`, `obj_log`, `public`, `aprofit`, `dprofit`, `html_log` FROM `T_LOGS_N` WHERE `log_id` = '$strID'  LIMIT 1 ;";
    			$varResult = cDB::QueryDB($strQuery);
    			if (!$varResult) {
    				LogError('cDB::EditLogByID', 'QueryDB failed');
    				return false;
    			}
    			$arrBinLog = $varResult->fetch_array(MYSQLI_ASSOC);
    			return $arrBinLog;
            } else return false;
		}

		static function EditLog_save($intUserID, $objLog) {
			$id = $objLog->Get("logid");
			$intUni		=	$objLog->Get("uni");
			$strDomain	=	$objLog->Get("domain");
			$strDomain	=	strtolower($strDomain);

			if ($strDomain == "undefined") {
				$strDomain = "?";
			}

			$binLog		=	bin2hex($objLog->Get("ziplog"));
			$blnpost = 0;

			if ($objLog->Get("blnpost")){
				$blnpost = 1;
			}

			$intProfit = $objLog->Get("profit");
			$intProfitA = (int) $intProfit["attacker"];
			$intProfitD = (int) $intProfit["defender"];

			$strQuery = "UPDATE `T_LOGS_N`
								SET `obj_log` = 0x$binLog,
									`universe` = '$intUni',
									`domain`  = '$strDomain',
									`aprofit`  = '$intProfitA',
									`dprofit`  = '$intProfitD',
									`bln_post` = '$blnpost'

								WHERE `log_id`  = '$id' AND `user_id` = '$intUserID' LIMIT 1 ;";

			$varResult = cDB::QueryDB($strQuery);
				if (!$varResult) {
					LogError('cDB::EditLog_save', 'QueryDB failed');
					return false;
				}
			return true;
		}

		static function SaveLogSpec($intUserID, $objLog){
			$binOwnlog 	= 	bin2hex($objLog->Get("zown"));

			$strId		=	$objLog->Get("logid");
			$binLog		=	bin2hex($objLog->Get("ziplog"));
			$blnPublic	=	$objLog->Get("public");
			$strDate	=	time();
			$intUni		=	$objLog->Get("uni");
			$strDomain	=	$objLog->Get("domain");
			$strDomain	=	strtolower($strDomain);
			if ($strDomain == "undefined") {
				$strDomain = "?";
			}
			$intLoses	=	$objLog->Get("losses");
			$strTitle	=	$objLog->Get("title");

			$blnpost = 0;

			if ($objLog->Get("blnpost") == true){
			    $blnpost = 1;
			}

			If (cDB::IsLogExists($strId)) {
				IncReportsCount(1);
				IncReportsCount24(1);

				$strQuery = "INSERT INTO  `T_LOGS_N` (
								`log_id` ,
								`user_id` ,
								`public` ,
								`date` ,
								`views` ,
								`universe` ,
								`domain` ,
								`losses` ,
								`title` ,
								`obj_log`,
								`html_log`,
								`bln_post`
							)
							VALUES (
								'$strId', '$intUserID', '$blnPublic', '$strDate', '0', '$intUni', '$strDomain', '$intLoses', '$strTitle',
								0x$binLog, 0x$binOwnlog, '$blnpost'
							);";
				if (!cDB::QueryDBSpec($strQuery)) {
					LogError('cDB::SaveLog', 'QueryDBSpec failed');
					return false;
				}
            }
			return true;
		}

		static function SelectLogsAccount ($intUserID, $type) {
    	    $strQuery = "SELECT `content` FROM `T_LOGS_ACCOUNT` WHERE `user_id` = '$intUserID' AND `type` = '$type';";
    	    $varResult = cDB::QueryDB($strQuery);
			if (!$varResult) {
				LogError('cDB::DeleteLog', 'QueryDB failed');
				return false;
			}
			return true;
		}

		static function DeleteLogsAccount ($intUserID, $type, $strLogId) {
			if ($intUserID == 0) return false;
    	    $strQuery = "SELECT `content` FROM `T_LOGS_ACCOUNT` WHERE `user_id` = '$intUserID' AND `type` = '$type';";
    	    $varResult = cDB::QueryDB($strQuery);
			if (!$varResult) {
				LogError('cDB::DeleteLog', 'QueryDB failed');
				return false;
			}

    	    $arrContent = $varResult->fetch_array(MYSQLI_ASSOC);
    	    $varContent = unserialize(gzuncompress($arrContent['content']));

            foreach ($varContent as $logId => $value) {
                if ($logId != $strLogId) $arr[$logId] = array("date" => $value["date"], "title" => $value["title"], "losses" => $value['losses'], "aprofit" => $value['aprofit'], "dprofit" => $value['dprofit'], "universe" => $value["universe"], "domain" => $value["domain"], "public" => $value["public"]);
            }

            $encodeArr = bin2hex(gzcompress(serialize($arr)));

			$strQuery = "DELETE FROM `T_LOGS_ACCOUNT` WHERE `user_id`='$intUserID' AND `type`='$type';";
			if (!cDB::QueryDB($strQuery)) {
				LogError('cDB::DeleteLog', 'QueryDB failed');
				return false;
			}

    		$strQuery = "INSERT INTO `T_LOGS_ACCOUNT` (`user_id`, `type`, `content`) VALUES ('$intUserID', '$type', 0x$encodeArr)";
			if (!cDB::QueryDB($strQuery)) {
				LogError('cDB::DeleteLog', 'QueryDB failed');
				return false;
			}
			return true;
		}
		static function AddLogsAccount ($intUserID, $type, $addContent) {
            $strQuery = "SELECT `content` FROM `T_LOGS_ACCOUNT` WHERE `user_id` = '$intUserID' AND `type` = '$type';";
        	$varResult = cDB::QueryDB($strQuery);
    		if ($varResult) {
            	$arrContent = $varResult->fetch_array(MYSQLI_ASSOC);
            	$varContent = unserialize(gzuncompress($arrContent['content']));

                foreach ($varContent as $logId => $value) {
                    $arr[$logId] = array("date" => $value["date"], "title" => $value["title"], "losses" => $value['losses'], "aprofit" => $value['aprofit'], "dprofit" => $value['dprofit'], "universe" => $value["universe"], "domain" => $value["domain"], "public" => $value["public"]);
                }
    		}

            foreach ($addContent as $logId => $value) {
                $arr[$logId] = array("date" => $value["date"], "title" => $value["title"], "losses" => $value['losses'], "aprofit" => $value['aprofit'], "dprofit" => $value['dprofit'], "universe" => $value["universe"], "domain" => $value["domain"], "public" => $value["public"]);
            }

            $encodeArr = bin2hex(gzcompress(serialize($arr)));

    		$strQuery = "DELETE FROM `T_LOGS_ACCOUNT` WHERE `user_id`='$intUserID' AND `type`='$type';";
    		if (!cDB::QueryDB($strQuery)) {
        		LogError('cDB::AddLogsAccount', 'QueryDB failed');
        		return false;
    		}

        	$strQuery = "INSERT INTO `T_LOGS_ACCOUNT` (`user_id`, `type`, `content`) VALUES ('$intUserID', '$type', 0x$encodeArr)";
    		if (!cDB::QueryDB($strQuery)) {
        		LogError('cDB::AddLogsAccount', 'QueryDB failed');
        		return false;
			}
			return true;
        }

		static function SaveLog($intUserID, $objLog) {
			if (isset($_POST["plugin_user_key"]) && !empty($_POST["plugin_user_key"])) {
				$pluginUserKey = KillInjection(trim($_POST["plugin_user_key"]));
	            $file = fopen("api/id/" . $pluginUserKey, "r");
	            if ($file) $intUserID = fread ($file, 100);
	            fclose ($file);				
			}
			$binOwnlog 	= 	bin2hex($objLog->Get("zown"));

			$strId		=	$objLog->Get("logid");
			$binLog		=	bin2hex($objLog->Get("ziplog"));
			$blnPublic	=	$objLog->Get("public");
			$strDate	=	time();
			$intUni		=	$objLog->Get("uni");
			$strDomain	=	$objLog->Get("domain");
			$strDomain	=	strtolower($strDomain);
			if ($strDomain == "undefined") {
				$strDomain = "?";
			}
			$intLoses	=	$objLog->Get("losses");
			$strTitle	=	$objLog->Get("title");

			$blnpost = 0;

			if ($objLog->Get("blnpost") == true){
			    $blnpost = 1;
			}

			If (cDB::IsLogExists($strId)) {
				IncReportsCount(1);
				IncReportsCount24(1);

			$intProfit = $objLog->Get("profit");
			$intProfitA = (int) $intProfit["attacker"];
			$intProfitD = (int) $intProfit["defender"];

				$strQuery = "INSERT INTO  `T_LOGS_N` (
								`log_id` ,
								`user_id` ,
								`public` ,
								`date` ,
								`views` ,
								`universe` ,
								`domain` ,
								`losses` ,
								`title` ,
								`obj_log` ,
								`html_log` ,
								`aprofit` ,
								`dprofit` ,
								`bln_post`
							)
							VALUES (
								'$strId', '$intUserID', '$blnPublic', '$strDate', '0', '$intUni', '$strDomain', '$intLoses', '$strTitle',
								0x$binLog, 0x$binOwnlog, '$intProfitA', '$intProfitD', '$blnpost'
							);";
				if (!cDB::QueryDB($strQuery)) {
					LogError('cDB::SaveLog', 'QueryDB failed');
					return false;
				}

                $arr[$strId] = array("date" => $strDate, "title" => $strTitle, "losses" => $intLoses, "aprofit" => $intProfitA, "dprofit" => $intProfitD, "universe" => $intUni, "domain" => $strDomain, "public" => $blnPublic);

				if (!cDB::AddLogsAccount ($intUserID, 0, $arr)) {
					LogError('cDB::SaveLog', 'QueryDB failed');
				 return false;
				}
            }
			return true;
		}

		static function SaveEsp($intUserID, $objLog) {
			if (isset($_POST["plugin_user_key"]) && $_POST["plugin_user_key"] != "") {
				$pluginUserKey = KillInjection(trim($_POST["plugin_user_key"]));
	            $file = fopen("api/id/" . $pluginUserKey, "r");
	            if ($file) $intUserID = fread ($file, 100);
	            fclose ($file);				
			}
			
			$binLog		=	bin2hex($objLog->Get("ziplog"));

			$strUni		=	$objLog->strUni;
			$strDomain	=	$objLog->strDomain;
			$strTitle	=	$objLog->strTitle;

			$intActive	=	(int) $objLog->Get("active");
			$intLoot	=	(int) $objLog->Get("loot");
			$intFleet	=	(int) $objLog->Get("fleet");

			$strName	=	$objLog->Get("htmllog")->generic->defender_name;
			$strCore	=	$objLog->Get("htmllog")->generic->defender_planet_coordinates;
			$strType	=	(int) $objLog->Get("htmllog")->generic->defender_planet_type;

			$strId		=	$objLog->Get("logid");
			$strCode	=	$objLog->Get("htmllog")->generic->sr_id;;
			$strDate	=	time();

			$strQuery = "INSERT INTO  `T_ESP` (
							`log_id` ,
							`code` ,
							`user_id` ,
							`date` ,
							`universe` ,
							`domain` ,
							`title` ,
							`player` ,
							`core` ,
							`active` ,
							`loot` ,
							`fleet` ,
							`type` ,
							`obj_log`
						)
						VALUES (
							'$strId', 
							'$strCode', 
							'$intUserID', 
							'$strDate', 
							'$strUni', 
							'$strDomain', 
							'$strTitle', 
							'$strName', 
							'$strCore', 
							'$intActive', 
							'$intLoot', 
							'$intFleet', 
							'$strType', 
							0x$binLog
						);";
			if (!cDB::QueryDB($strQuery)) {
				LogError('cDB::SaveEsp', 'QueryDB failed');
				return false;
			}

			return true;
		}

		static function LoadEspByID($strID) {
			$strQuery = "SELECT * FROM `T_ESP` WHERE `log_id` = '$strID'  LIMIT 1 ;";
			$varResult = cDB::QueryDB($strQuery);
			if (!$varResult) {
                LogError('cDB::LoadEspByID', 'QueryDB failed');
        		return false;
			}

			$arrBinLog = $varResult->fetch_array(MYSQLI_ASSOC);
			return $arrBinLog;			
		}

		static function LoadEspList($intUserID, $strUni, $strDomain) {
			$strQuery = "SELECT * FROM `T_ESP` WHERE `user_id` = '$intUserID'";
			if ($strUni && $strDomain) $strQuery .= " AND `universe` = '$strUni' AND `domain` = '$strDomain'";
			$strQuery .= " ORDER BY `date` DESC;";
			$varResult = cDB::QueryDB($strQuery);
			if (!$varResult) {
                LogError('cDB::LoadEspByID', 'QueryDB failed');
        		return false;
			}

			while($objRow = $varResult->fetch_array(MYSQLI_ASSOC))
			{
				$arrResult[] = $objRow;
			}

			return $arrResult;		
		}

		static function LoadGroupList($intUserID, $strUni, $strDomain, $strStatus) {
			$strQuery = "SELECT * FROM `T_GROUP` WHERE `user_id` = '$intUserID' AND `status` = '$strStatus'";
			if ($strUni && $strDomain) $strQuery .= " AND `universe` = '$strUni' AND `domain` = '$strDomain'";
			$strQuery .= " ORDER BY `date` DESC;";
			$varResult = cDB::QueryDB($strQuery);
			if (!$varResult) {
                LogError('cDB::LoadEspByID', 'QueryDB failed');
        		return false;
			}

			while($objRow = $varResult->fetch_array(MYSQLI_ASSOC))
			{
				$arrResult[] = $objRow;
			}

			return $arrResult;		
		}

		static function LoadGroup($groupId) {
			$strQuery = "SELECT * FROM `T_GROUP` WHERE `group_id` = '$groupId'";
			$varResult = cDB::QueryDB($strQuery);
			if (!$varResult) {
                LogError('cDB::LoadEspByID', 'QueryDB failed');
        		return false;
			}

			while($objRow = $varResult->fetch_array(MYSQLI_ASSOC))
			{
				$arrResult[] = $objRow;
			}

			return $arrResult;		
		}

		static function CreateGroup($intUserID, $strUserLogin, $strUni, $strDomain) {
			$strDate = time();
			$strGroupId = md5($strDate . "ls");

			$strQuery = "SELECT * FROM `T_GROUP` WHERE `user_id` = '$intUserID' AND `universe` = '$strUni' AND `domain` = '$strDomain';";
			$varResult = cDB::QueryDB($strQuery);
			if (!$varResult) {
				LogError('cDB::CreateGroupSelect', 'QueryDB failed');
				return false;
			}
			if ($varResult->num_rows == "0") {
	    		$strQuery = "INSERT INTO `T_GROUP` (`group_id`, `user_id`, `user_login`, `date`, `universe`, `domain`, `status`) VALUES ('$strGroupId', '$intUserID', '$strUserLogin', '$strDate', '$strUni', '$strDomain', '1')";
				if (!cDB::QueryDB($strQuery)) {
					LogError('cDB::CreateGroup', 'QueryDB failed');
					return false;
				}

				return "save";
			} else {
				return "double";				
			}
		}

		static function InviteForGroup($strGroupId, $strUserLogin, $strUni, $strDomain) {
			$strDate = time();

			$strQuery = "SELECT * FROM `T_USERS` WHERE `user_id` LIKE '$strUserLogin' OR `user_login` LIKE '$strUserLogin' LIMIT 1";
			$varResult = cDB::QueryDB($strQuery);
			if (!$varResult) {
				LogError('cDB::CreateGroupSelect', 'QueryDB failed');
				return false;
			}
			while($objRow = $varResult->fetch_array(MYSQLI_ASSOC))
			{
				$arrResult = $objRow;
			}
			var_dump($arrResult);

			if ($varResult->num_rows == 1) {
				$intUserID = $arrResult["user_id"];
				$strUserLogin = $arrResult["user_login"];

				$strQuery = "SELECT * FROM `T_GROUP` WHERE `user_id` = '$intUserID' AND `universe` = '$strUni' AND `domain` = '$strDomain';";
				$varResultSelect = cDB::QueryDB($strQuery);
				if (!$varResult) {
					LogError('cDB::CreateGroupSelect', 'QueryDB failed');
					return false;
				}
				var_dump($varResultSelect);
				if ($varResultSelect->num_rows == 0) {				
		    		$strQuery = "INSERT INTO `T_GROUP` (`group_id`, `user_id`, `user_login`, `date`, `universe`, `domain`, `status`) VALUES ('$strGroupId', '$intUserID', '$strUserLogin', '$strDate', '$strUni', '$strDomain', '0')";
					if (!cDB::QueryDB($strQuery)) {
						LogError('cDB::CreateGroup', 'QueryDB failed');
						return false;
					}

					return "invite";
				} else {
					return "double";				
				}
			}
			return false;
		}

		static function SaveUpload ($intUserID, $objLog){
			$strDate = time();

				$strQuery = "INSERT INTO  `T_UPLOAD` (
								`log_id` ,
								`user_id` ,
								`date` ,
								`universe` ,
								`domain` ,
								`title` ,
								`obj_log`,
							)
							VALUES (
								'$strId', '$intUserID', '$strDate', '0', '0', '0' 0x$objLog);";
								//'$strId', '$intUserID', '$strDate', '$intUni', '$strDomain', '$strTitle' 0x$objLog);";
				if (!cDB::QueryDB($strQuery)) {
					LogError('cDB::SaveUpload', 'QueryDB failed');
					return false;
				}

			return true;
		}
		static function AddPost($strPost , $Username, $idUser, $idLog) {
			$strQuery = "INSERT INTO T_COMMENTS (id_user, user_name, id_log, data, text, typ) VALUES (
								'$idUser',
								'$Username',
								'$idLog',
								NOW(),
								'$strPost',
								'0')";

			if (!cDB::QueryDB($strQuery)) {
					LogError('cDB::AddPost', 'QueryDB failed');
					return false;
				}
				return true;
		}
		static function GetPost($intLog, $intFirst) {

		$strQuery = "SELECT `user_name`, `data`,`text`  FROM `T_COMMENTS` WHERE `id_log` = '$intLog' ORDER BY `data` DESC ;";

		$varResult = cDB::QueryDB($strQuery);
			if (!$varResult) {
				LogError('cDB::GetPost1', 'QueryDB failed');
				return false;
			}
			return $varResult;
		}
		static function PostAll($intLog)
		{
			$strQuery = "SELECT `user_name` FROM `T_COMMENTS` WHERE `id_log` = '$intLog'  ;";


		$varResult = cDB::QueryDB($strQuery);
			if (!$varResult) {
				LogError('cDB::GetPost2', 'QueryDB failed');
				return false;
			}
		        $intcount = mysqli_num_rows($varResult);
			return $intcount;
		}

		static function IsLogAccess($strLogId, $strUID) {
			If ($strUID < 1) return false;
			$strQuery = "SELECT `log_id` FROM `T_LOGS_N` WHERE `log_id`='$strLogId' AND `user_id` = '$strUID';";
			$varResult = cDB::QueryDB($strQuery);
			if (!$varResult) {
				LogError('cDB::IsLogAccess', 'QueryDB failed');
				return false;
			}
			if (mysqli_num_rows($varResult) == 0) {
				return false;
			}
			/*if (mysqli_num_rows($varResult) > 1) {
				LogError('IsLogAccess', 'there are two logs with id ' . $strLogId);
				return false;
			}*/
			return true;
		}

		static function IsSpyLogAccess($strLogId, $strUID) {
			If ($strUID < 1) return false;
			$strQuery = "SELECT `log_id` FROM `T_ESP` WHERE `log_id`='$strLogId' AND `user_id` = '$strUID';";
			$varResult = cDB::QueryDB($strQuery);
			if (!$varResult) {
				LogError('cDB::IsSpyLogAccess', 'QueryDB failed');
				return false;
			}
			if (mysqli_num_rows($varResult) == 0) {
				return false;
			}
			return true;
		}

		static function DeleteLog($strLogId, $strUID) {
			if (!cDB::IsLogAccess($strLogId, $strUID)) {
				LogError('cDB::DeleteLog', 'Access error. Log ID = ' . $strLogId);
				return false;
			}

			$strQuery = "DELETE FROM `T_LOGS_N` WHERE `log_id`='$strLogId';";
			if (!cDB::QueryDB($strQuery)) {
				LogError('cDB::DeleteLog', 'QueryDB failed');
				return false;
			}
			if (!cDB::DeleteLogsAccount ($strUID, 0, $strLogId)) {
				LogError('cDB::DeleteLog', 'QueryDB failed');
				return false;
			}
			return true;
		}

		static function DeleteSpyLog($strLogId, $strUID) {
			if (!cDB::IsSpyLogAccess($strLogId, $strUID)) {
				LogError('cDB::DeleteSpyLog', 'Access error. Log ID = ' . $strLogId);
				return false;
			}

			$strQuery = "DELETE FROM `T_ESP` WHERE `log_id`='$strLogId';";
			if (!cDB::QueryDB($strQuery)) {
				LogError('cDB::DeleteSpyLog', 'QueryDB failed');
				return false;
			}
			return true;
		}

		static function LoadLogByIDSpec($strID){
			$strQuery = "SELECT `obj_log` FROM `T_LOGS` WHERE `log_id` = '$strID'  LIMIT 1 ;";
			$varResult = cDB::QueryDBSpec($strQuery);
			if (!$varResult) {
				LogError('cDB::LoadLogByIDSpec', 'QueryDBSpec failed');
				return false;
			}
			$arrBinLog = $varResult->fetch_array(MYSQLI_ASSOC);
			$binLog=$arrBinLog['obj_log'];

			$intTime = time();
			$intDelay = time() - 30;

			$strQuery = "UPDATE `ogamespec_ls`.`T_LOGS`
							SET `views` = `views` + 1, `last_view` = '$intTime'
							WHERE `log_id` = '$strID' AND `last_view` < '$intDelay' LIMIT 1 ;";
			$varResult = cDB::QueryDBSpec($strQuery);
			if (!$varResult) {
				LogError('cDB::LoadLogByIDSpec', 'QueryDBSpec failed');
				return false;
			}

			return $binLog;
		}


		static function LoadLogByID($strID, $varBaseLogs){
            $strID = KillInjection($strID);    
			$strQuery = "SELECT `title`, `obj_log`, `bln_post` FROM `" . $varBaseLogs . "` WHERE `log_id` = '$strID'  LIMIT 1 ;";
			$varResult = cDB::QueryDB($strQuery);
			if (!$varResult) {
                LogError('cDB::LoadLogByID', 'QueryDB failed');
        		return false;
			}
			$arrBinLog = $varResult->fetch_array(MYSQLI_ASSOC);
			$binLog=$arrBinLog;

			$intTime = time();
			$intDelay = time() - 30;

			$strQuery = "UPDATE  `".$varBaseLogs."`
							SET `views` = `views` + 1, `last_view` = '$intTime'
							WHERE `log_id` = '$strID' AND `last_view` < '$intDelay' LIMIT 1 ;";
			$varResult = cDB::QueryDB($strQuery);
			if (!$varResult) {
				LogError('cDB::LoadLogByID', 'QueryDB failed');
				return false;
			}

			return $binLog;
		}

		static function LoadViewsByID($strID){
		  if (isset($_SESSION['account']['login']) && !in_array ($_SESSION['account']['login'], listAdmin())) {
    			$intTime = time();
    			$intDelay = time() - 30;
    
    			$strQuery = "UPDATE  `T_LOGS_N`
    							SET `views` = `views` + 1, `last_view` = '$intTime'
    							WHERE `log_id` = '$strID' AND `last_view` < '$intDelay' LIMIT 1 ;";
    			$varResult = cDB::QueryDB($strQuery);
    			if (!$varResult) {
    				LogError('cDB::LoadViewsByID', 'QueryDB failed');
    				return false;
    			}
            }

			return true;
		}

		static function LoadTitleByID($strID) {
			$strQuery = "SELECT * FROM `T_LOGS_N` WHERE `log_id` = '$strID'  LIMIT 1 ;";
			$varResult = cDB::QueryDB($strQuery);
			if (!$varResult) {
				LogError('cDB::LoadTitleByID', 'QueryDB failed');
				return false;
			}
			$arrBinLog = $varResult->fetch_array(MYSQLI_ASSOC);
			$binLog = $arrBinLog;

			return $binLog;
		}

		static function ChangePublic($strLogId, $strUID, $intPublic) {
			if ($strUID == 0) return false;
			if (($intPublic !== 1) && ($intPublic !== 0)) {
				LogError('cDB::ChangePublic', 'invalid input argument: intPublic = ' . $intPublic);
				return false;
			}

			if (!cDB::IsLogAccess($strLogId, $strUID)) {
				LogError('cDB::ChangePublic', 'Access error. Log ID = ' . $strLogId);
				return false;
			}

    	    $strQuery = "SELECT `content` FROM `T_LOGS_ACCOUNT` WHERE `user_id` = '$strUID' AND `type` = '0';";
    	    $varResult = cDB::QueryDB($strQuery);
			if (!$varResult) {
				LogError('cDB::DeleteLog', 'QueryDB failed');
				return false;
			}

    	    $arrContent = $varResult->fetch_array(MYSQLI_ASSOC);
    	    $varContent = unserialize(gzuncompress($arrContent['content']));

            foreach ($varContent as $logId => $value) {
                if ($logId == $strLogId) $arr[$logId] = array("date" => $value["date"], "title" => $value["title"], "losses" => $value['losses'],  "aprofit" => $value['aprofit'], "dprofit" => $value['dprofit'], "universe" => $value["universe"], "domain" => $value["domain"], "public" => $intPublic);
                else $arr[$logId] = array("date" => $value["date"], "title" => $value["title"], "losses" => $value['losses'], "aprofit" => $value['aprofit'], "dprofit" => $value['dprofit'], "universe" => $value["universe"], "domain" => $value["domain"], "public" => $value["public"]);
            }

            $encodeArr = bin2hex(gzcompress(serialize($arr)));

			$strQuery = "DELETE FROM `T_LOGS_ACCOUNT` WHERE `user_id`='$strUID' AND `type`='0';";
			if (!cDB::QueryDB($strQuery)) {
				LogError('cDB::DeleteLog', 'QueryDB failed');
				return false;
			}

    		$strQuery = "INSERT INTO `T_LOGS_ACCOUNT` (`user_id`, `type`, `content`) VALUES ('$strUID', '0', 0x$encodeArr)";
			if (!cDB::QueryDB($strQuery)) {
				LogError('cDB::DeleteLog', 'QueryDB failed');
				return false;
			}

			$strQuery = "UPDATE  `T_LOGS_N`
							SET `public` = '$intPublic'
							WHERE `log_id` = '$strLogId' AND `user_id` = '$strUID';";
			$varResult = cDB::QueryDB($strQuery);
			if (!$varResult) {
				LogError('cDB::ChangePublic', 'QueryDB failed');
				return false;
			}

			return true;
		}

		static function SaveTempUser($objUser) {
			$strLogin		=	$objUser->strLogin;
			$strPassword	=	$objUser->strPassword;
			$strMail		=	$objUser->strMail;
			$strRegDate		=	date("y-m-d");

			$strQuery = "INSERT INTO  `T_TEMP_USERS` (
							`user_login` ,
							`user_password` ,
							`user_mail` ,
							`reg_date` )
						VALUES (
							'$strLogin', '$strPassword', '$strMail', '$strRegDate');";
			if (!cDB::QueryDB($strQuery)) {
				LogError('cDB::SaveTempUser', 'QueryDB failed');
				return false;
			}
			return true;
		}

		static function SaveUser($objUser) {
			$strLogin		=	$objUser->strLogin;
			$strPassword	=	$objUser->strPassword;
			$strMail		=	$objUser->strMail;
			$intId			=	cDB::GetUserId();
			$intRole		=	1;
			$strRegDate		=	date("y-m-d");

			$strQuery = "INSERT INTO  `T_USERS` (
							`user_id` ,
							`user_login` ,
							`user_password` ,
							`user_mail` ,
				 			`role` ,
							`reg_date` ,
							`settings` ,
							`logs_count` ,
							`last_visit` ,
							`registration` ,
							`post_status` )
						VALUES (
							'$intId', '$strLogin', '$strPassword', '$strMail', '$intRole', '$strRegDate', '', 0, 0, 1, 0);";
			if (!cDB::QueryDB($strQuery)) {
				LogError('cDB::SaveUser', 'QueryDB failed');
				return false;
			}
			return true;
		}

		static function ActivateUser($strLogin) {

			$strQuery = "SELECT `registration` FROM `T_USERS` WHERE `user_login`='$strLogin';";
			$varResult = cDB::QueryDB($strQuery);
			if (!$varResult) {
				LogError('cDB::ActivateUser', 'QueryDB failed');
				return false;
			}
			if (mysqli_num_rows($varResult) != 1) {
				LogError('cDB::ActivateUser', 'Error logins count for login ' . $strLogin );
				return false;
			}

			$objRow = $varResult->fetch_array(MYSQLI_ASSOC);
			if (!$objRow) {
				LogError('cDB::ActivateUser', 'Temp user '.$strLogin.' not found');
				return false;
			}

			if ($objRow['registration'] != 0) {
				LogError('cDB::ActivateUser', 'This user has already been activated' );
				return false;
			}

			$strQuery = "UPDATE  `T_USERS`
							SET `registration` = 1
							WHERE `user_login` = '$strLogin';";
			if (!cDB::QueryDB($strQuery)) {
				LogError('cDB::ActivateUser', 'QueryDB failed');
				return false;
			}
			return true;
		}

		/*static function SaveUser($objUser) {
			$strLogin		=	$objUser->strLogin;
			$strPassword	=	$objUser->strPassword;
			$strMail		=	$objUser->strMail;
			$intId			=	$objUser->intId;
			$intRole		=	$objUser->intRole;
			$strRegDate		=	date("y-m-d");

			$strQuery = "INSERT INTO  `T_USERS` (
							`user_id` ,
							`user_login` ,
							`user_password` ,
							`user_mail` ,
				 			`role` ,
							`reg_date`	)
						VALUES (
							'$intId', '$strLogin', '$strPassword', '$strMail', '$intRole', '$strRegDate');";
			if (!cDB::QueryDB($strQuery)) {
				LogError('SaveUser', 'QueryDB failed');
				return false;
			}
			return true;
		}*/

		static function LoadTempUser($strLogin) {
			$objUser = new cUser;

			$strQuery = "SELECT * FROM `T_TEMP_USERS` WHERE `user_login`='$strLogin';";
			$varResult = cDB::QueryDB($strQuery);
			if (!cDB::QueryDB($strQuery)) {
				LogError('cDB::LoadTempUser', 'QueryDB failed');
				return false;
			}
			if (!$varResult) {
				LogError('cDB::LoadTempUser', 'QueryDB failed');
				return false;
			}
			if (mysqli_num_rows($varResult) > 1) {
				LogError('cDB::LoadTempUser', 'there are two users with login '.$strLogin);
				return false;
			}

			$objRow = $varResult->fetch_array(MYSQLI_ASSOC);
			if (!$objRow) {
				LogError('cDB::LoadTempUser', 'Temp user '.$strLogin.' not found');
				return false;
			}

			$objUser->strLogin = $objRow['user_login'];
			$objUser->strPassword = $objRow['user_password'];
			$objUser->strMail = $objRow['user_mail'];
			$objUser->strRegDate = $objRow['reg_date'];

			return $objUser;
		}

		static function LoadUser($strLogin) {
			$objUser = new cUser;

			$strQuery = "SELECT * FROM `T_USERS` WHERE `user_login`='$strLogin';";

			$varResult = cDB::QueryDB($strQuery);
			if (!cDB::QueryDB($strQuery)) {
				LogError('cDB::LoadUser', 'QueryDB failed');
				return false;
			}
			if (!$varResult) {
				LogError('cDB::LoadUser', 'QueryDB failed');
				return false;
			}
			if (mysqli_num_rows($varResult) > 1) {
				LogError('cDB::LoadUser', 'there are two users with login '.$strLogin);
				return false;
			}

			$objRow = $varResult->fetch_array(MYSQLI_ASSOC);
			if (!$objRow) {
				LogError('cDB::LoadUser', 'User '.$strLogin.' not found');
				return false;
			}
			$objUser->intId = $objRow['user_id'];
			$objUser->strLogin = $objRow['user_login'];
			$objUser->strPassword = $objRow['user_password'];
			$objUser->strMail = $objRow['user_mail'];
			$objUser->intRole = $objRow['role'];
			$objUser->strRegDate = $objRow['reg_date'];
			$objUser->blnConfirm = $objRow['registration'];

			return $objUser;
		}

		static function LoadUser2($strLogin, $strPassword) {
			$objUser = new cUser;

			$strQuery = "SELECT * FROM `T_USERS` WHERE (`user_login`='$strLogin') AND (`user_password`='$strPassword');";

			$varResult = cDB::QueryDB($strQuery);
			if (!cDB::QueryDB($strQuery)) {
				LogError('cDB::LoadUser2', 'QueryDB failed');
				return false;
			}
			if (!$varResult) {
				LogError('cDB::LoadUser2', 'QueryDB failed');
				return false;
			}
			if (mysqli_num_rows($varResult) > 1) {
				LogError('cDB::LoadUser2', 'there are two users with this login and password');
				unset($_COOKIE['autologin']);
				return false;
			}

			$objRow = $varResult->fetch_array(MYSQLI_ASSOC);
			if (!$objRow) {
				LogError('cDB::LoadUser2', 'User '.$strLogin.' not found');
				return false;
			}
			$objUser->intId = $objRow['user_id'];
			$objUser->strLogin = $objRow['user_login'];
			$objUser->strPassword = $objRow['user_password'];
			$objUser->strMail = $objRow['user_mail'];
			$objUser->intRole = $objRow['role'];
			$objUser->strRegDate = $objRow['reg_date'];

			return $objUser;
		}

		static function LoadUrlLostpw($strMail) {
			$strQuery = "SELECT * FROM `T_USERS` WHERE `user_mail`='$strMail';";
            $varResult = cDB::QueryDB($strQuery);

			$objRow = $varResult->fetch_array(MYSQLI_ASSOC);
			if (!$objRow) {
				LogError('cDB::LoadUrlLostpw', 'Mail '.$strMail.' not found');
				return false;
			}

			if (!$varResult) {
				return false;
			}

			if (mysqli_num_rows($varResult) == 0) {
				return false;
			}

			return $objRow['user_login'];
	}

		static function LoadLostpw($strLogin, $strMail, $strPassword) {
            $strNewPassword = md5(str_pad($strPassword, 40, '0'));

			$strQuery = "UPDATE  `T_USERS`
							SET `user_password` = '$strNewPassword'
							WHERE `user_login` = '$strLogin' AND `user_mail`='$strMail';";

            $varResult = cDB::QueryDB($strQuery);

			if (!$varResult) {
				return false;
			}

			return true;
	}

		static function LoadChangtpw($strId, $strPassword) {

			$strQuery = "UPDATE  `T_USERS`
							SET `user_password` = '$strPassword'
							WHERE `user_id` = '$strId';";

            $varResult = cDB::QueryDB($strQuery);

			if (!$varResult) {
				return false;
			}

			return true;
	}
		static function DeleteTempUser($strLogin) {
			$strQuery = "DELETE FROM `T_TEMP_USERS` WHERE `user_login`='$strLogin';";
			$varResult = cDB::QueryDB($strQuery);
			if (!cDB::QueryDB($strQuery)) {
				LogError('cDB::DeleteTempUser', 'QueryDB failed');
				return false;
			}
			if (!$varResult) {
				LogError('cDB::DeleteTempUser', 'QueryDB failed');
				return false;
			}
			return true;
		}

		static function IsLoginExists($strLogin) {
			$strQuery = "SELECT `user_login` FROM `T_TEMP_USERS` WHERE `user_login`='$strLogin' LIMIT 1;";
			$varResult = cDB::QueryDB($strQuery);
			if (!$varResult) {
				LogError('cDB::IsLoginExists', 'QueryDB failed');
				return false;
			}
			if (mysqli_num_rows($varResult) != 0) {
				LogError('cDB::IsLoginExists', 'This login is already exists');
				return false;
			}
			$strQuery = "SELECT `user_login` FROM `T_USERS` WHERE `user_login`='$strLogin' LIMIT 1;";
			$varResult = cDB::QueryDB($strQuery);
			if (!$varResult) {
				LogError('cDB::IsLoginExists', 'QueryDB failed');
				return false;
			}
			if (mysqli_num_rows($varResult) != 0) {
				LogError('cDB::IsLoginExists', 'This login is already exists');
				return false;
			}
			return true;
		}

		static function IsMailExists($strMail) {
			$strQuery = "SELECT `user_mail` FROM `T_TEMP_USERS` WHERE `user_mail`='$strMail' LIMIT 1;";
			$varResult = cDB::QueryDB($strQuery);
			if (!$varResult) {
				LogError('cDB::IsMailExists', 'QueryDB failed');
				return false;
			}
			if (mysqli_num_rows($varResult) != 0) {
				LogError('cDB::IsMailExists', 'This mail is already exists');
				return false;
			}
			$strQuery = "SELECT `user_mail` FROM `T_USERS` WHERE `user_mail`='$strMail' LIMIT 1;";
			$varResult = cDB::QueryDB($strQuery);
			if (!$varResult) {
				LogError('cDB::IsMailExists', 'QueryDB failed');
				return false;
			}

			if (mysqli_num_rows($varResult) != 0) {
				LogError('cDB::IsMailExists', 'This mail is already exists');
				return false;
			}
			return true;
		}

		static function IsPassExists($strId, $strPass) {
			$strQuery = "SELECT `user_mail` FROM `T_USERS` WHERE `user_id`='$strId' AND `user_password`='$strPass' LIMIT 1;";
			$varResult = cDB::QueryDB($strQuery);
			if (!$varResult) {
				LogError('cDB::IsPassExists', 'QueryDB failed');
				return false;
			}

			if (mysqli_num_rows($varResult) == 0) {
				LogError('cDB::IsPassExists', 'This mail or pass is already exists');
				return false;
			}
			return true;
		}

		static function IsPlayerExists($strPlayer) {
			$strQuery = "SELECT `player_name` FROM `T_PLAYERS_ID` WHERE `player_name`='$strPlayer' LIMIT 1;";
			$varResult = cDB::QueryDB($strQuery);
			if (!$varResult) {
				LogError('cDB::IsPlayerExists', 'QueryDB failed');
				return false;
			}
			if (mysqli_num_rows($varResult) != 0) {
				return true;
			}

			return false;
		}

		static function IsLogExistsSpec($strLodId) {
			$strQuery = "SELECT `log_id` FROM `T_LOGS` WHERE `log_id`='$strLodId' LIMIT 1;";
			$varResult = cDB::QueryDBSpec($strQuery);
			if (!$varResult) {
				LogError('cDB::IsLogExistsSpec', 'QueryDBSpec failed');
				return false;
			}
			if (mysqli_num_rows($varResult) != 0) {
				return false;
			}

			return true;
		}

		static function IsLogExists($strLodId) {
			$strQuery = "SELECT `log_id` FROM `T_LOGS_N` WHERE `log_id`='$strLodId' LIMIT 1;";
			$varResult = cDB::QueryDB($strQuery);
			if (!$varResult) {
				LogError('cDB::IsLogExists', 'QueryDB failed');
				return false;
			}
			if (mysqli_num_rows($varResult) != 0) {
				return false;
			}

			return true;
		}

		static function GetLogsCount($strTime) {
			$intCount = false;
			if ($strTime == "uploaded") {
                if ((time() - filemtime ("./txt/".$strTime)) >= 4* 60 * 60) {

				    $intTime = 24 * 3600;
				    $intTime = time() - $intTime;
				    $strQuery = "SELECT `log_id` FROM `T_LOGS_N` WHERE `date` >= $intTime;";

			        $varResult = cDB::QueryDB($strQuery);
			            if (!$varResult) {
				            LogError('cDB::GetLogsCount(uploaded)', 'QueryDB failed');
				            return false;
			            }
			        $intCount = mysqli_num_rows($varResult);

                    $fp = fopen("./txt/".$strTime, "a");
                    ftruncate($fp, 0);
                    $test = fwrite($fp, $intCount);
                    fclose($fp);
                }

                $file = fopen("./txt/".$strTime,"r");
                $intCount = fread ($file,100);
                fclose ($file);
            }

			if ($strTime == "total_uploaded") {
                if ((time() - filemtime ("./txt/".$strTime)) >= 3 * 60 * 60){
				    $strQuery = "SELECT `log_id` FROM `T_LOGS_N`";

			        $varResult = cDB::QueryDB($strQuery);
			            if (!$varResult) {
				            LogError('cDB::GetLogsCount(total_uploaded)', 'QueryDB failed');
				            return false;
			            }
			        $intCount = mysqli_num_rows($varResult);

                    $fp = fopen("./txt/".$strTime, "a");
                    ftruncate($fp, 0);
                    $test = fwrite($fp, $intCount);
                    fclose($fp);
                }

                $file = fopen("./txt/".$strTime,"r");
                $intCount = fread ($file,100) + 422431;
                fclose ($file);
                }
			return $intCount;
        }

		static function GetUsersCount($strTime) {
			if ($strTime == "r_users") {
                if ((time() - filemtime ("./txt/".$strTime)) >= 24 * 60 * 60) {

				    $strQuery = "SELECT `user_id` FROM `T_USERS`";

			        $varResult = cDB::QueryDB($strQuery);
			            if (!$varResult) {
				            LogError('cDB::GetUsersCount', 'QueryDB failed');
				            return false;
			            }
			        $intCount = mysqli_num_rows($varResult);

                    $fp = fopen("./txt/".$strTime, "a");
                    ftruncate($fp, 0);
                    $test = fwrite($fp, $intCount);
                    fclose($fp);
                }
                $file = fopen("./txt/".$strTime,"r");
                $intCount = fread ($file,100);
                fclose ($file);

			return $intCount;
            }
        }

		static function GetUserId() {
			$strQuery = "SELECT MAX(`user_id`) FROM `T_USERS`";
			$varResult = cDB::QueryDB($strQuery);
			if (!$varResult) {
				LogError('cDB::GetUserId', 'QueryDB failed');
				return false;
			}
			$arrTemp= mysqli_fetch_array($varResult);
			$intCount=$arrTemp['MAX(`user_id`)'];
			return $intCount+1;
		}

		static function GetPlayerMaxId() {
			$strQuery = "SELECT MAX(`player_id`) FROM `T_PLAYERS_ID`";
			$varResult = cDB::QueryDB($strQuery);
			if (!$varResult) {
				LogError('cDB::GetPlauerId', 'QueryDB failed');
				return false;
			}
			$arrTemp= mysqli_fetch_array($varResult);
			$intCount=$arrTemp['MAX(`player_id`)'];
			return $intCount+1;
		}

		static function GetPlayerId($strName) {
			$strQuery = "SELECT `player_id` FROM `T_PLAYERS_ID` WHERE `player_name`='$strName';";

			$varResult = cDB::QueryDB($strQuery);
			if (!cDB::QueryDB($strQuery)) {
				LogError('cDB::GetPlayerId', 'QueryDB failed');
				return -1;
			}
			if (!$varResult) {
				return 0;
			}
			if (mysqli_num_rows($varResult) > 1) {
				LogError('cDB::GetPlayerId', 'GetPlayerId failed');
				return -1;
			}

			$objRow = $varResult->fetch_array(MYSQLI_ASSOC);
			if (!$objRow) {
				LogError('cDB::GetPlayerId', 'GetPlayerId failed');
				return 0;
			}
			$intId = $objRow['player_id'];

			return $intId;
		}

		static function LogForAdmin($strWhat, $userName) {
			if ($strWhat['text_name']) $textName = $strWhat['text_name'];
			else return false;

			if ($strWhat['select_uni']) $selectUni = $strWhat['select_uni']; 	
			if ($strWhat['select_domain']) $selectDomain = $strWhat['select_domain'];

			$intTime = time();

			$strQuery = "INSERT INTO `T_ADMIN_LOGS` (
								`name` ,
								`text_name` ,
								`universe` ,
								`domain` ,
								`date`
							)
							VALUES (
								'$userName',
                                '$textName',
                                '$selectUni',
                                '$selectDomain',
                                '$intTime',
							);";

			if (!cDB::QueryDB($strQuery)) {
				LogError('cDB::LogForAdmin', 'QueryDB failed');
				return false;
			}
			return true;
		}

		static function LogListSearch($strWhat, $intUserId) {
			$arrResult = array();
			$strFROM = "FROM `T_LOGS_N`";
			$arrWHERE = array();
			$strLIMIT = "";
			$strORDER = " ORDER BY T_LOGS_N.date DESC ";
            $strPer = 15;

            if ($intUserId == 0 && $intUserId !== 'admin') {
			    $arrWHERE[] = " T_LOGS_N.public = 1 ";
			    $strLIMIT = " LIMIT 100 ";
            }
  			elseif ($intUserId !== 0 && $intUserId == 'admin') {
			    if ($strWhat['select_public'] == '1'){ $arrWHERE[] = " T_LOGS_N.public = 1 ";}
			    if ($strWhat['select_public'] == '0'){ $arrWHERE[] = " T_LOGS_N.public = 0 ";}

			    $strLIMIT = " LIMIT 15 ";
			    if ($strWhat['select_limit'] == '25'){ $strLIMIT = " LIMIT 25 ";}
			    if ($strWhat['select_limit'] == '50'){ $strLIMIT = " LIMIT 50 ";}
            } 
            /*else {
				$arrWHERE[] = " T_LOGS_N.user_id = '$intUserId' ";

				$strLIMIT = " LIMIT 5 ";
			}
*/
			if (($strWhat['text_name'] != "last_") && ($strWhat['text_name'] != "last_x")) {

				if ($strWhat['select_domain'])
					$arrWHERE[] = " T_LOGS_N.domain LIKE '%".$strWhat['select_domain']."%' ";

				if ($strWhat['select_uni'])
					$arrWHERE[] = " T_LOGS_N.universe = '".$strWhat['select_uni']."' ";

				if ($strWhat['select_losses'])
					$arrWHERE[] = " T_LOGS_N.losses > '".((pow(2, $strWhat['select_losses'] - 1)) * 100000)."' ";

				if ($strWhat['text_name']) {
					$arrWHERE[] = " `title` LIKE '%".$strWhat['text_name']."%' ";
				}
			}

			$strWHERE = "";
			if ($arrWHERE) {
				$strWHERE .= " WHERE ";
				$n = 0;
				foreach ($arrWHERE as $strVar) {
					if ($n == 1) $strWHERE .= " AND ";
					$strWHERE .= $strVar;
					$n = 1;
				}
			}
//made by Zmei
			//$strSelect = " SELECT DISTINCT T_LOGS_N.log_id, T_LOGS_N.universe, T_LOGS_N.domain, T_LOGS_N.losses, T_LOGS_N.date, T_LOGS_N.title, T_LOGS_N.public, T_LOGS_N.views, T_LOGS_N.html_log ";
			$strSelect = " SELECT * ";

			$strQuery = $strSelect.$strFROM.$strWHERE.$strORDER.$strLIMIT;

			//echo "<br>".$strQuery."<br>";

			$varResult = cDB::QueryDB($strQuery);
			if (!$varResult) {
				LogError('cDB::LogListSearch', 'QueryDB failed');
				return false;
			}

			/*while($objRow = $varResult->fetch_array(MYSQLI_ASSOC))
			{
				$varUni = $objRow['universe'];

				$strLoses = $objRow['losses'];
				$strDomain = $objRow['domain'];
				$strDate = $objRow['date'];
				$strTitle = $objRow['title'];

				$varShortNameUni = ShortNameUni($varUni);
				$varUni = NameUni($varUni);
				$strTitle = "[".$varShortNameUni."] ".$strTitle." (". NumberToString($strLoses) .", ".ucfirst(strtolower($varUni)).".".strtolower($strDomain).", ".$strDate.")";

				$arrResult[] = array("id" => $objRow['log_id'], "title" => $strTitle);
			}*/

			while($objRow = $varResult->fetch_array(MYSQLI_ASSOC))
			{
				$arrResult[] = $objRow;
			}

			return $arrResult;
		}

		static function GetPopularLogs($intCount) {
			$arrResult = array();
			$strLimit = " LIMIT 0, $intCount ";

			$intTime = 1 * 7 * 24 * 3600;
			$intTime = time() - $intTime;

			$strOrderBy = " ORDER BY `views` DESC ";

			$strSelect = "SELECT `log_id`, `universe`, `domain`, `losses`, `date`, `title`, `public`, `views` FROM `T_LOGS_N` WHERE  `public` = '1' AND `date` >= $intTime ";

			$strQuery = $strSelect.$strOrderBy.$strLimit;
			//echo "<br>".$strQuery."<br>";

			$varResult = cDB::QueryDB($strQuery);
			if (!$varResult) {
				LogError('cDB::LogListSearch', 'QueryDB failed');
				return false;
			}

			while($objRow = $varResult->fetch_array(MYSQLI_ASSOC))
			{
				$arrResult[] = $objRow;
			}

			return $arrResult;
		}

		static function LogListSearchEx($strWhat, $intUserId) {
			$arrResult = array();

			$arrWHERE = array();
			$strOrderBy = " ORDER BY `date` DESC ";

			if ($intUserId == 0) {
				$arrWHERE[] = " `public` = 1 ";
			}
			else {
				$arrWHERE[] = " `user_id` = '$intUserId' ";
			}

			$strWHERE = "";
			if ($arrWHERE) {
				$strWHERE .= " WHERE ";
				$n = 0;
				foreach ($arrWHERE as $strVar) {
					if ($n == 1) $strWHERE .= " AND ";
					$strWHERE .= $strVar;
					$n = 1;
				}
			}

			$strSelect = "SELECT `log_id`, `universe`, `domain`, `losses`, `date`, `title`, `public`, `views` FROM `T_LOGS_N`";

			$strQuery = $strSelect.$strWHERE.$strOrderBy;

			//echo "<br>".$strQuery."<br>";

			$varResult = cDB::QueryDB($strQuery);
			if (!$varResult) {
				LogError('cDB::LogListSearchEx', 'QueryDB failed');
				return false;
			}

			while($objRow = $varResult->fetch_array(MYSQLI_ASSOC))
			{
				$arrResult[] = $objRow;
			}

			return $arrResult;
		}

		static function NextLogId ($intDateS, $intDomain, $intUni) {
			if (!$intDateS && !$intDomain && !$intUni) return false;
			$strQuery = "SELECT `log_id` FROM `T_LOGS_N` WHERE `date`>='$intDateS' AND `date`!='$intDateS' AND `domain`='$intDomain' AND `universe`='$intUni' AND `public`='1' LIMIT 1;";
			$varResult = cDB::QueryDB($strQuery);
			if (!$varResult) {
				LogError('cDB::NextLogId', 'QueryDB failed');
				return false;
			}
			$arrBinLog = $varResult->fetch_array(MYSQLI_ASSOC);
			$arrResult = $arrBinLog;

			return $arrResult;
		}

		static function EarlyLogId ($intDateS, $intDomain, $intUni) {
			if (!$intDateS && !$intDomain && !$intUni) return false;
			$strQuery = "SELECT `log_id` FROM `T_LOGS_N` WHERE `date`<='$intDateS' AND `date`!='$intDateS' AND `domain`='$intDomain' AND `universe`='$intUni' AND `public`='1' ORDER BY `date` DESC, `log_id` DESC LIMIT 1;";
			$varResult = cDB::QueryDB($strQuery);
			if (!$varResult) {
				LogError('cDB::EarlyLogId', 'QueryDB failed');
				return false;
			}
			$arrBinLog = $varResult->fetch_array(MYSQLI_ASSOC);
			$arrResult = $arrBinLog;

			return $arrResult;
		}

		static function LoadCreoByID ($strID) {
			$strQuery = "SELECT * FROM `T_CREO` WHERE `log_id` = '$strID'  LIMIT 1 ;";
			$varResult = cDB::QueryDB($strQuery);
			if (!$varResult) {
				LogError('cDB::LoadCreoByID', 'QueryDB failed');
				return false;
			}
			$arrBinLog = $varResult->fetch_array(MYSQLI_ASSOC);
			$binLog = $arrBinLog;

			return $binLog;
		}

		static function LoadCreoForTOP ($strUni, $strSab) {
			$strQuery = "SELECT * FROM `T_CREO` WHERE `universe` = '$strUni' AND `domain` = 'ru' AND `sab` = '$strSab' ORDER BY `losses` DESC LIMIT 10;";
			$varResult = cDB::QueryDB($strQuery);
			if (!$varResult) {
				LogError('cDB::LoadCreoByID', 'QueryDB failed');
				return false;
			}
			while($objRow = $varResult->fetch_array(MYSQLI_ASSOC))
			{
				$arrResult[] = $objRow;
			}

			if (isset($arrResult)) return $arrResult;
		}

		static function LoadCreoForTOPAll ($strSab, $intLimit) {
			$strQuery = "SELECT * FROM `T_CREO` WHERE `domain` = 'ru' AND `sab` = '$strSab' ORDER BY `losses` DESC LIMIT $intLimit;";
			$varResult = cDB::QueryDB($strQuery);
			if (!$varResult) {
				LogError('cDB::LoadCreoByID', 'QueryDB failed');
				return false;
			}
			while($objRow = $varResult->fetch_array(MYSQLI_ASSOC))
			{
				$arrResult[] = $objRow;
			}

			if (isset($arrResult)) return $arrResult;
		}

		// ----------------------------------------------- board/top.php -----------------------------------------------------
		static function LoadLogIdForTOP ($strUni, $strSab) {
			$strQuery = "SELECT `log_id` FROM `T_CREO` WHERE `universe` = '$strUni' AND `domain` = 'ru' AND `sab` = '$strSab' ORDER BY `losses` DESC LIMIT 10;";
			$varResult = cDB::QueryDB($strQuery);
			if (!$varResult) {
				LogError('cDB::LoadCreoByID', 'QueryDB failed');
				return false;
			}
			while($objRow = $varResult->fetch_array(MYSQLI_ASSOC))
			{
				$arrResult[] = $objRow;
			}

			if (isset($arrResult)) return $arrResult;
		}

		static function LoadLogIdForTOPAll ($strSab, $intLimit) {
			$strQuery = "SELECT `log_id` FROM `T_CREO` WHERE `domain` = 'ru' AND `sab` = '$strSab' ORDER BY `losses` DESC LIMIT $intLimit;";
			$varResult = cDB::QueryDB($strQuery);
			if (!$varResult) {
				LogError('cDB::LoadCreoByID', 'QueryDB failed');
				return false;
			}
			while($objRow = $varResult->fetch_array(MYSQLI_ASSOC))
			{
				$arrResult[] = $objRow;
			}

			if (isset($arrResult)) return $arrResult;
		}
		// ----------------------------------------------- board/top.php -----------------------------------------------------

		// ----------------------------------------------- board/stat.php -----------------------------------------------------
	    static function SaveStatResult($postId, $strServer, $strResultName, $strResultKey, $strResultLvl, $strResultCoord) {
	        $strDate = time();
	        $strQuery = "SELECT * FROM `B_STAT` WHERE `post_id` = '$postId' AND `server` = '$strServer' AND `name` = '$strResultName' AND `k` = '$strResultKey' AND `lvl` = '$strResultLvl' AND `coord` = '$strResultCoord' LIMIT 1;";

	        $varResult = cDB::QueryDB($strQuery);
	        if (!$varResult->fetch_array(MYSQLI_ASSOC)) {
	            $strQuery = "INSERT INTO `B_STAT` (`time`, `post_id`, `server`, `name`, `k`, `lvl`, `coord`) VALUES ('$strDate', '$postId', '$strServer', '$strResultName', '$strResultKey', '$strResultLvl', '$strResultCoord');";
	            $varResult = cDB::QueryDB($strQuery);
	            return true;           
	        } else {
	            return false;                
	        }
	    }

	    static function GetStatResult($strServer, $strResultKey) {
	        if ($strServer != 0) $strQuery = "SELECT * FROM `B_STAT` WHERE `server` = '$strServer' AND `k` = '$strResultKey' ORDER BY lvl DESC;";
	        else $strQuery = "SELECT * FROM `B_STAT` WHERE `k` = '$strResultKey' ORDER BY lvl DESC;";

	        $varResult = cDB::QueryDB($strQuery);
	        while($objRow = $varResult->fetch_array(MYSQLI_ASSOC))
	        {
	            $arrResult[] = $objRow;
	        }
	        
	        if (isset($arrResult)) return $arrResult[0];
	        else return false;

	        return true;                
	    }
		// ----------------------------------------------- board/stat.php -----------------------------------------------------

	}
?>
