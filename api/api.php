<?php
    error_reporting (0);
    header('Access-Control-Allow-Origin: *');
    date_default_timezone_set('Europe/Moscow');
    require '../h_abox.php';
    require '../h_db.php';
    require '../h_dom.php';
    require '../h_functions.php';

    $varVercion = '3.6.4';

    if ($_GET['v'] != $varVercion) {
        echo '<center>Script version is outdated! <br><a href="https://logserver.net/plugin/LogServer.net_GM_script.user.js" target="_blank">Update</a>.</center>';
        exit ();
    }

    if ($_GET['act'] == 'phalanx') {
        echo 1;
    }

    if ($_GET['act'] == 'login') {
        if ($_GET['l']) $strLogin = KillInjection ($_GET['l']);
        else {
            $strResult["err"] = -1;
        }
        if ($_GET['p']) $strPass = md5(str_pad(KillInjection ($_GET['p']), 40, '0'));
        else {
            $strResult["err"] = -2;
        }
        $strQuery = "SELECT `user_password`, `user_id` FROM `T_USERS` WHERE `user_login` = '$strLogin' AND `user_password` = '$strPass' LIMIT 1 ;";
        $varResult = cDB::QueryDB($strQuery);
        if (!$varResult) {
            $strResult["err"] = -2;
        } else {
            $arrResult = $varResult->fetch_array(MYSQLI_ASSOC);
            $strIsId = md5(str_pad(KillInjection ($arrResult['user_password'].$arrResult['user_id']."asdUdsa"), 40, '0'));
            $strId = $arrResult['user_id'];
            if (!$arrResult) echo 0;
            else {
                $fp = fopen("./id/".$strIsId, "a");
                ftruncate($fp, 0);
                $test = fwrite($fp, $strId);
                fclose($fp);

                $strResult["id"] = $strIsId;
            }
        }
        echo json_encode($strResult);

    }

    if ($_GET['act'] == 'logserverContent') {
        if ($_GET['get'] == 'pub') {
            if ($_GET['id']) $strId = KillInjection ($_GET['id']);
            else {echo -1; return;}

            $strLogId = KillInjection ($_GET['logid']);
            $strPublic = KillInjection ($_GET['public']);

            if ($strPublic == 0) $intPublic = 1;
            else $intPublic = 0;

            $file = fopen("./id/".$strId,"r");
            if (!$file) {echo -2; return;}
            $strUserId = fread ($file,100);
            fclose ($file);

            if ($varResult = cDB::ChangePublic($strLogId, $strUserId, $intPublic))
                echo $intPublic; return;
        } 
        if ($_GET['get'] == 'del') {
            if ($_GET['id']) $strId = KillInjection ($_GET['id']);
            else {echo -1; return;}

            $strLogId = KillInjection ($_GET['logid']);

            if ($strPublic == 0) $intPublic = 1;
            else $intPublic = 0;

            $file = fopen("./id/".$strId,"r");
            if (!$file) {echo -2; return;}
            $strUserId = fread ($file,100);
            fclose ($file);

            if ($varResult = cDB::DeleteLog($strLogId, $strUserId))
                echo 1; return;
        }               
        if ($_GET['id']) $strId = KillInjection ($_GET['id']);
        else {echo -1; return;}

        $file = fopen("./id/".$strId,"r");
        if (!$file) {echo -2; return;}
        $strUserId = fread ($file,100);
        fclose ($file);

        $strLogs = '';
        if (isset($_GET["n"])) {
            $varNumber = KillInjection($_GET["n"]);
            if (!is_numeric($varNumber)){die('Invalid page number!');}
        } else {
            $varNumber = 1;
        }
        $item_per_page = 30;

        $varResult = sortData(unserialize(gzuncompress(GetLogsAccount($strUserId, 0, "T_LOGS_NEW"))));
        if ($varResult && count($varResult) > 0) {

            $strGetTotalRows = count($varResult);
            $total_pages = ceil($strGetTotalRows/$item_per_page);

            $page_position = (($varNumber-1) * $item_per_page);
            $strNum = 0;

            $strResult = array();
            $strLogs = array();
            foreach ($varResult as $logId => $value) {
                if ($strNum >= $page_position && $strNum < $page_position + $item_per_page) {
                    $strBase64 = base64_encode($logId);
                    $strDate = DateS($value["date"]);
                    $strTitle = $value["title"];
                    $strLosses = $value["losses"];
                    $strUni = ShortNameUni($value["universe"]);
                    $strDomain = strtolower($value["domain"]);
                    $strPublic = strtolower($value["public"]);
                    $strLogs[] = array('id' => $logId, 'base64' => $strBase64, 'date' => $strDate,  'title' => $strTitle,  'losses' => $strLosses, 'uni' => $strUni, 'domain' => $strDomain, 'public' => $strPublic);
                }
                    $strNum++;
            }
            $strResult["result"] = true;
            $strResult["pages"] = $total_pages;
            $strResult["page"] = $varNumber;
            $strResult["logs"] = $strLogs;
            echo json_encode($strResult);
        } else {
            $strResult["result"] = false;
            echo json_encode($strResult);
        }
    }

    if ($_GET['act'] == 'logserverContentSpy') {
        if ($_GET['get'] == 'del') {
            if ($_GET['id']) $strId = KillInjection ($_GET['id']);
            else {echo -1; return;}

            $strLogId = KillInjection ($_GET['logid']);

            if ($strPublic == 0) $intPublic = 1;
            else $intPublic = 0;

            $file = fopen("./id/".$strId,"r");
            if (!$file) {echo -2; return;}
            $strUserId = fread ($file,100);
            fclose ($file);

            if ($varResult = cDB::DeleteSpyLog($strLogId, $strUserId))
                echo 1; return;
        }               
        if ($_GET['id']) $strId = KillInjection ($_GET['id']);
        else {echo -1; return;}

        $file = fopen("./id/".$strId,"r");
        if (!$file) {echo -2; return;}
        $strUserId = fread ($file,100);
        fclose ($file);

        $strLogs = '';
        if (isset($_GET["n"])) {
            $varNumber = KillInjection($_GET["n"]);
            if (!is_numeric($varNumber)){die('Invalid page number!');}
        } else {
            $varNumber = 1;
        }
        $item_per_page = 30;

        $varResult = cDB::LoadEspList($strUserId);
        if ($varResult && count($varResult) > 0) {

            $strGetTotalRows = count($varResult);
            $total_pages = ceil($strGetTotalRows/$item_per_page);

            $page_position = (($varNumber-1) * $item_per_page);
            $strNum = 0;

            $strResult = array();
            $strLogs = array();
            foreach ($varResult as $value) {
                if ($strNum >= $page_position && $strNum < $page_position + $item_per_page) {
                    $logId = $value["log_id"];
                    $strBase64 = base64_encode($value["log_id"]);
                    $strDate = DateS($value["date"]);
                    $strTitle = $value["title"];
                    $strPlayer = $value["player"];
                    $strUni = ShortNameUni($value["universe"]);
                    $strDomain = strtolower($value["domain"]);
                    $strCore = explode(":", $value["core"]);
                    $strType = $value["type"];
                    $strLogs[] = array('id' => $logId, 'base64' => $strBase64, 'date' => $strDate,  'title' => $strTitle,  'player' => $strPlayer, 'uni' => $strUni, 'domain' => $strDomain, 'g' => $strCore[0], 's' => $strCore[1], 'p' => $strCore[2], 'type' => $strType);
                }
                    $strNum++;
            }
            $strResult["result"] = true;
            $strResult["pages"] = $total_pages;
            $strResult["page"] = $varNumber;
            $strResult["logs"] = $strLogs;
            echo json_encode($strResult);
        } else {
            $strResult["result"] = false;
            echo json_encode($strResult);
        }
    }

    function sortData ($varContent) {
        $strDate = array();
        foreach ($varContent as $key => $arr){
            $strDate[$key] = $arr['date'];
        }

        array_multisort($strDate, SORT_DESC, SORT_NUMERIC, $varContent);

        return $varContent;
    }

    function GetLogsAccount ($userID, $type, $DB) {
        if (!$userID){die('Invalid session!');}
        $strQuery = "SELECT `content` FROM `T_LOGS_ACCOUNT` WHERE `user_id` = '$userID' AND `type` = '$type';";
        $varResult = cDB::QueryDB($strQuery);
        if (mysqli_num_rows($varResult) == 0 && ($type == 0 || $type == 1)) {
            $strQuery = "SELECT * FROM `".$DB."` WHERE `user_id` = '$userID' LIMIT 1000;";
            $varResult = cDB::QueryDB($strQuery);
            if (mysqli_num_rows($varResult) == 0) {
                return false;
            } else {
                while($objRow = $varResult->fetch_array(MYSQLI_ASSOC)) {
                    $arrResult[] = $objRow;
                }
                foreach ($arrResult as $value) {
                    $arr[$value["log_id"]] = array("date" => $value["date"], "title" => $value["title"], "losses" => $value['losses'], "universe" => $value["universe"], "domain" => $value["domain"], "public" => $value["public"]);
                }

                $encodeArr = bin2hex(gzcompress(serialize($arr)));
                $encodeReturn = gzcompress(serialize($arr));

                $strQuery = "INSERT INTO `T_LOGS_ACCOUNT` (`user_id`, `type`, `content`) VALUES ('$userID', '$type', 0x$encodeArr)";
                $varResult = cDB::QueryDB($strQuery);

                return $encodeReturn;
            }
        } else {
            $arrContent = $varResult->fetch_array(MYSQLI_ASSOC);
            $varContent = $arrContent['content'];
            return $varContent;
        }
    }

?>