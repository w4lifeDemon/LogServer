<?php
session_start();
    error_reporting (0);
    date_default_timezone_set('Europe/Moscow');
    require 'h_abox.php';
    require 'h_constants.php';
    require 'h_db.php';
    require 'h_files.php';
    require 'h_api.php';
    require 'h_functions.php';
    require 'h_mail.php';
    /*
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    */

    function countCookie ($strOption) {
        if ($_COOKIE[$strOption] == "false" || $_COOKIE[$strOption] == "") $strLimit = 10;
        if ($_COOKIE[$strOption] == 10) $strLimit = 10;
        if ($_COOKIE[$strOption] == 15) $strLimit = 15;
        if ($_COOKIE[$strOption] == 20) $strLimit = 20;
        if ($_COOKIE[$strOption] == 25) $strLimit = 25;

        return $strLimit;
    }

    if (isset($_GET['page']) && $_GET['page'] == 'local') {
        $strId = KillInjection($_GET['id']);
        $strVal = KillInjection($_GET['val']);

        if (isset($_COOKIE["lang"])) $lang = strtoupper ($_COOKIE["lang"]);
        else $lang = "RU";

        $fPointer = "localizations/localizations";
        if (!$fHandle = fopen($fPointer, 'rb') ) LogError("LogServer", "Sorry, LogServer in not available now, please wait for a few minutes");
        $g_arrLocalizations_ = unserialize( fread($fHandle, filesize($fPointer)) );
        fclose($fHandle);
        $g_arrLocalizations_[$strId][$lang] = $strVal;

        if (!$fHandle = fopen($fPointer, 'wb')) LogError("LogServer", "Sorry, LogServer in not available now, please wait for a few minutes");
        flock($fHandle, LOCK_EX);
        if (fwrite($fHandle, serialize($g_arrLocalizations_)) === false) LogError("LogServer", "Sorry, LogServer in not available now, please wait for a few minutes");
        flock($fHandle, LOCK_UN);
        fclose($fHandle);
         
        echo "<span style='color: " . GREEN_LIGHT . "'>" . $strVal . "</span>";
    }

    if (isset($_GET['page']) && $_GET['page'] == 'lang') {
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
    }

    if (isset($_GET['page']) && $_GET['page'] == 'game1' && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        $intCount = KillInjection($_GET['i']);
        if ($_SESSION['account']['id'] > 0) {
            $strName = $_SESSION['account']['login'];

            $varUrlBuffer = "./cache/game1";
            $varResult = apiBuffer ($varUrlBuffer);
            if ($varResult[$strName]) {
                if ($varResult[$strName] < $intCount)
                    $varResult[$strName] = $intCount;
            } else 
                $varResult[$strName] = $intCount;

            $varBuffer = serialize($varResult);
            $varFp = fopen($varUrlBuffer, 'w');
            fwrite($varFp, $varBuffer);
            fclose($varFp);            
        }

        arsort($varResult);
        $i = 1;
        echo "<center>";
        echo "<table>";
        foreach ($varResult as $key => $value) {
            echo "<tr style='color: " . WHITE_LIGHT . "'><td style='width:430px'></td><td style='width:20px'>" . $i . ".</td><td>" . $key . "</td><td>" . $value . "</td><td style='width:430px'></tr>";
            $i++;
        }

        if ($_SESSION['account']['id'] > 0)
            echo "<tr><td colspan='5' style='text-align: center;'><span style='color: " . GREEN_LIGHT . "'>Результат сохранен</span></td></tr>";
        else echo "<tr><td colspan='5' style='text-align: center;'><span style='color: " . RED_LIGHT . "'>Залогинтесь что бы сохранить результат</span></td></tr>";
        echo "</table>";
        echo "</center>";
    }

    if (isset($_GET['page']) && $_GET['page'] == 'game2' && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        $intCount = KillInjection($_GET['i']);
        if ($_SESSION['account']['id'] > 0) {
            $strName = $_SESSION['account']['login'];

            $varUrlBuffer = "./cache/game2";
            $varResult = apiBuffer ($varUrlBuffer);
            if ($varResult[$strName]) {
                if ($varResult[$strName] < $intCount)
                    $varResult[$strName] = $intCount;
            } else 
                $varResult[$strName] = $intCount;

            $varBuffer = serialize($varResult);
            $varFp = fopen($varUrlBuffer, 'w');
            fwrite($varFp, $varBuffer);
            fclose($varFp);            
        }

        arsort($varResult);
        $i = 1;
        echo "<center>";
        foreach ($varResult as $key => $value) {
            echo "<tr style='color: " . WHITE_LIGHT . "'><td style='width:430px'></td><td style='width:20px'>" . $i . ".</td><td>" . $key . "</td><td>" . $value . "</td><td style='width:430px'></tr>";
            $i++;
        }

        if ($_SESSION['account']['id'] > 0)
            echo "<tr><td colspan='5' style='text-align: center;'><span style='color: " . GREEN_LIGHT . "'>Результат сохранен</span></td></tr>";
        else echo "<tr><td colspan='5' style='text-align: center;'><span style='color: " . RED_LIGHT . "'>Залогинтесь что бы сохранить результат</span></td></tr>";
        echo "</center>";
    }

    if (isset($_GET['page']) && $_GET['page'] == 'game3' && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        $intCount = KillInjection($_GET['i']);
        if ($_SESSION['account']['id'] > 0) {
            $strName = $_SESSION['account']['login'];

            $varUrlBuffer = "./cache/game3";
            $varResult = apiBuffer ($varUrlBuffer);
            if ($varResult[$strName]) {
                if ($varResult[$strName] < $intCount)
                    $varResult[$strName] = $intCount;
            } else 
                $varResult[$strName] = $intCount;

            $varBuffer = serialize($varResult);
            $varFp = fopen($varUrlBuffer, 'w');
            fwrite($varFp, $varBuffer);
            fclose($varFp);            
        }

        arsort($varResult);
        $i = 1;
        echo "<center>";
        echo "<table>";
        foreach ($varResult as $key => $value) {
            echo "<tr style='color: " . WHITE_LIGHT . "'><td style='width:430px'></td><td style='width:20px'>" . $i . ".</td><td>" . $key . "</td><td>" . $value . "</td><td style='width:430px'></tr>";
            $i++;
        }

        if ($_SESSION['account']['id'] > 0)
            echo "<tr><td colspan='5' style='text-align: center;'><span style='color: " . GREEN_LIGHT . "'>Результат сохранен</span></td></tr>";
        else echo "<tr><td colspan='5' style='text-align: center;'><span style='color: " . RED_LIGHT . "'>Залогинтесь что бы сохранить результат</span></td></tr>";
        echo "</table>";
        echo "</center>";
    }

    if (isset($_GET['page']) && $_GET['page'] == 'game2record') {
        $varUrlBuffer = "./cache/game2";
        $varResult = apiBuffer ($varUrlBuffer);
        arsort($varResult);
        echo current($varResult);
    }

    if (isset($_GET['page']) && $_GET['page'] == 'recycler') {
        $strErorr = "<font color='" . RED_COMMON . "'>Не то поле или неправильный rr-код!</font>";
        $strRrId = KillInjection($_GET['rr_id']);
        if (substr($strRrId, 0, 3) == "rr-") {
            $varResult = apiRR ($strRrId, 1);
            if ($varResult) {
                echo "<table>
                        <tr>
                            <td><span style=\"background: transparent url('" . ICON_RESOURCE . "') no-repeat scroll 0px 0px; background-position: 0px 0px; width: 30px; height: 20px; float: left;\"></span>
                            &nbsp;<font color='" . WHITE_DARK . "'>" . NumberToString($varResult->generic->metal_retrieved) . "</font></td>
                            <td><span style=\"background: transparent url('" . ICON_RESOURCE . "') no-repeat scroll 0px 0px; background-position: -30px 0px; width: 30px; height: 20px; float: left;\"></span>
                            &nbsp;<font color='" . WHITE_DARK . "'>" . NumberToString($varResult->generic->crystal_retrieved) . "</font></td>
                        </tr>
                </table>";
            } else {
                echo $strErorr;
            }
        } else {
            echo $strErorr;
        }
    }

    if (isset($_GET['page']) && $_GET['page'] == 'cleanup') {
        $strErorr = "<font color='" . RED_COMMON . "'>Не то поле или неправильный сr-код!</font>";
        $strCrId = KillInjection($_GET['cr_id']);
        if (substr($strCrId, 0, 3) == "cr-") {
            $varResult = apiCR ($strCrId, 1);
            if ($varResult) {
                echo "<table>
                        <tr>
                            <td><span style=\"background: transparent url('" . ICON_RESOURCE . "') no-repeat scroll 0px 0px; background-position: 0px 0px; width: 30px; height: 20px; float: left;\"></span>
                            &#8194;<font color='" . WHITE_DARK . "'>" . NumberToString($varResult->generic->loot_metal) . "</font></td>
                            <td><span style=\"background: transparent url('" . ICON_RESOURCE . "') no-repeat scroll 0px 0px; background-position: -30px 0px; width: 30px; height: 20px; float: left;\"></span>
                            &#8194;<font color='" . WHITE_DARK . "'>" . NumberToString($varResult->generic->loot_crystal) . "</font></td>
                            <td><span style=\"background: transparent url('" . ICON_RESOURCE . "') no-repeat scroll 0px 0px; background-position: -60px 0px; width: 30px; height: 20px; float: left;\"></span>
                            &#8194;<font color='" . WHITE_DARK . "'>" . NumberToString($varResult->generic->loot_deuterium) . "</font></td>
                        </tr>
                </table>";
            } else echo $strErorr;
        } else {
            echo $strErorr;
        }
    }

    if (isset($_GET['page']) && $_GET['page'] == 'textarea_result') {
        if (isset($_GET['cr_id'])) {
            $strErorr = "<center><font color='" . RED_COMMON . "'>Не то поле или неправильный сr-код!</font></center>";
            $strCrId = KillInjection($_GET['cr_id']);
            if (substr($strCrId, 0, 3) == "cr-") {
                $varResult = apiCR ($strCrId, 1);
                if ($varResult) {
                    echo "<center><table>
                            <tr>
                                <td>" . GetTitle($varResult->attackers, $varResult->defenders) . " </td>
                                <td><span style=\"background: transparent url('" . ICON_RESOURCE . "') no-repeat scroll 0px 0px; background-position: 0px 0px; width: 30px; height: 20px; float: left;\"></span>
                                &nbsp;<font color='" . WHITE_DARK . "'>" . NumberToString($varResult->generic->loot_metal) . "</font></td>
                                <td><span style=\"background: transparent url('" . ICON_RESOURCE . "') no-repeat scroll 0px 0px; background-position: -30px 0px; width: 30px; height: 20px; float: left;\"></span>
                                &nbsp;<font color='" . WHITE_DARK . "'>" . NumberToString($varResult->generic->loot_crystal) . "</font></td>
                                <td><span style=\"background: transparent url('" . ICON_RESOURCE . "') no-repeat scroll 0px 0px; background-position: -60px 0px; width: 30px; height: 20px; float: left;\"></span>
                                &nbsp;<font color='" . WHITE_DARK . "'>" . NumberToString($varResult->generic->loot_deuterium) . "</font></td>
                            </tr>
                    </table></center>";               
                } else echo $strErorr;
            } else echo $strErorr;
        }
        if (isset($_GET['sr_id'])) {
        $strSrId = KillInjection($_GET['sr_id']);
            $strErorr = "<center><font color='" . RED_COMMON . "'>Не то поле или неправильный sr-код!</font></center>";
            if (substr($strSrId, 0, 3) == "sr-") {
                $varResult = apiSR ($strSrId, 1);
                if ($varResult) {
                    echo "<center><table>
                            <tr>
                                <td><font color='" . WHITE_DARK . "'><font color='#6f9fc8'>Разведданные с " . $varResult->generic->defender_planet_name . " [" . $varResult->generic->defender_planet_coordinates . "]</font> Игрок <font color='#008800'>" . $varResult->generic->defender_name . "</font></font></td>
                            </tr>
                    </table></center>";               
                } else echo $strErorr;
            } else echo $strErorr;
        }        
    }

    if (isset($_GET['page']) && $_GET['page'] == 'updatelogs') {
        if ($_SESSION['account']['id'] && $_SESSION['account']['id'] > 0) {
            $userID = $_SESSION['account']['id'];
            $strQuery = "DELETE FROM `T_LOGS_ACCOUNT` WHERE `user_id`='$userID' AND `type` = '0';;";
            if (!cDB::QueryDB($strQuery)) {
                return false;
            }
            if (!GetLogsAccount($_SESSION['account']['id'], 0, "T_LOGS_N")) {
                return false;
            }
            echo "Update!";
        }
    }

    if (isset($_POST) && $_SESSION['account']['id'] > 0 && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && $_GET['page'] == 'logs') {
        $strLogs = '';
        if (isset($_POST["n"])) {
            $varNumber = filter_var($_POST["n"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
            if (!is_numeric($varNumber)){die('Invalid page number!');}
        } else {
            $varNumber = 1;
        }

        $item_per_page = countCookie ("option_select_logs");

        $varResult = sortData(unserialize(gzuncompress(GetLogsAccount($_SESSION['account']['id'], 0, "T_LOGS_N"))));
        if ($varResult && count($varResult) > 0) {       
            $strGetTotalRows = count($varResult);
            $total_pages = ceil($strGetTotalRows/$item_per_page);

            $page_position = (($varNumber-1) * $item_per_page);
            $strNum = 0;
            $strLogs .= '<table class="contents" style="border-collapse: collapse" border="1" bordercolor="#222222" cellpadding="4" width="780">';
            $strLogs .= '<tr height="28">
                            <td align="center" background="index_files/abox/header.png" width="20"><font class="abox_text">#</font></td>
                            <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 1)" align="center" background="index_files/abox/header.png" width="60"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Date</font></td>
                            <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 2)" align="center" background="index_files/abox/header.png" width="280"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Title</font></td>
                            <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 3)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Losses</font></td>
                            <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 3)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Profit</font></td>
                            <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 5)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Uni</font></td>
                            <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 6)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Lang</font></td>
                            <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 9)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Views</font></td>
                            <td align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Del</font></td>
                            <td align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Edit</font></td>
                       </tr>';
            foreach ($varResult as $logId => $value) {
                if ($strNum >= $page_position && $strNum < $page_position + $item_per_page) {
                    $strLogs .= '
                    <tr onmouseover="ActivateRow(this)" id="abox_' . base64_encode($logId) . '" onmouseout="DeactivateRow(this)" src="index_files/abox/row_' . ($strNum % 2 + 1) . '.png" background="index_files/abox/row_' . ($strNum % 2 + 1) . '.png" height="28">
                      <td align="right"><font class="abox_text">' . ($strNum + 1) . '</font></td>
                      <td align="center"><font class="abox_text">' . DateS($value["date"]) . '</font></td>
                      <td align="left"><a href="index.php?id=' . $logId . '" target="_blank"><font size="2">' . cutStr($value["title"], false) . '</font></a></td>
                      <td align="center">' . NumberS($value['losses'], false) . '</td>
                      <td align="center" style="font-size: 12px;">' . PrepareNumber($value['aprofit']) . '/' . PrepareNumber($value['dprofit']) . '</td>
                      <td align="center"><font class="abox_text">' . ShortNameUni($value["universe"],false) . '</font></td>
                      <td align="center"><font class="abox_text">' . strtolower($value["domain"]) . '</font></td>
                      <td align="center"><a href="javascript:ChangePub(\'' . base64_encode($logId) . '\')" title="Make public"><img src="index_files/abox/icon_pub_' . $value["public"] . '.png" id="img_pub_' . base64_encode($logId) . '" alt="" border="0" width="16"></a></td>
                      <td align="center"><a href="javascript:DeleteLog(\'' . base64_encode($logId) . '\')" title="Delete" alt="Delete"><img src="index_files/abox/icon_delete.png" border="0" width="16"></a></td>
                      <td align="center"><a href="index.php?show=edit&amp;log_id=' . base64_encode($logId) . '" title="Edit" alt="Delete"><img src="index_files/abox/icon_edit.png" border="0" width="16"></a></td>
                    </tr>';
                }
                $strNum += 1;
            }
            $strLogs .= '</table>';

            $strLogs .= '<div align="center">';
            $strLogs .= paginate_function($item_per_page, $varNumber, $strGetTotalRows[0], $total_pages);
            $strLogs .= '</div>';

            echo $strLogs;
        } else echo '<font class="abox_text_red">Logs not found.</font>';
    }

    if (isset($_POST) && $_SESSION['account']['id'] > 0 && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && $_GET['page'] == 'oldlogs') {
        $strLogs = '';
        if (isset($_POST["n"])) {
            $varNumber = filter_var($_POST["n"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
            if (!is_numeric($varNumber)){die('Invalid page number!');}
        } else {
            $varNumber = 1;
        }
        $item_per_page = countCookie ("option_select_old_logs");
        //$varResult = sortData(unserialize(gzuncompress(GetLogsAccount($_SESSION['account']['id'], 1, "T_LOGS"))));
        if ($varResult && count($varResult) > 0) {
            $strGetTotalRows = count($varResult);
            $total_pages = ceil($strGetTotalRows/$item_per_page);

            $page_position = (($varNumber-1) * $item_per_page);
            $strNum = 0;
            $strLogs .= '<table class="contents" style="border-collapse: collapse" border="1" bordercolor="#222222" cellpadding="4" width="780">';
            $strLogs .= '<tr height="28">
                        <td align="center" background="index_files/abox/header.png" width="20"><font class="abox_text">#</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 1)" align="center" background="index_files/abox/header.png" width="60"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Date</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 2)" align="center" background="index_files/abox/header.png" width="280"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Title</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 3)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Losses</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 5)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Uni</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 6)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Lang</font></td>
                   </tr>';
            foreach ($varResult as $logId => $value) {
                if ($strNum >= $page_position && $strNum < $page_position + $item_per_page) {
                    $strLogs .= '
                    <tr onmouseover="ActivateRow(this)" id="abox_' . base64_encode($logId) . '" onmouseout="DeactivateRow(this)" src="index_files/abox/row_' . ($strNum % 2 + 1) . '.png" background="index_files/abox/row_' . ($strNum % 2 + 1) . '.png" height="28">
                      <td align="right"><font class="abox_text">' . ($strNum + 1) . '</font></td>
                      <td align="center"><font class="abox_text">' . DateS($value["date"]) . '</font></td>
                      <td align="left"><a href="index.php?id=' . $logId . '" target="_blank"><font size="2">' . cutStr($value["title"], false) . '</font></a></td>
                      <td align="center">' . NumberS($value['losses'], false) . '</td>
                      <td align="center"><font class="abox_text">' . ShortNameUni($value["universe"],false) . '</font></td>
                      <td align="center"><font class="abox_text">' . strtolower($value["domain"]) . '</font></td>
                    </tr>';
                }
                $strNum += 1;
            }
            $strLogs .= '</table>';

            $strLogs .= '<div align="center">';
            $strLogs .= paginate_function($item_per_page, $varNumber, $strGetTotalRows[0], $total_pages);
            $strLogs .= '</div>';

            echo $strLogs;
        } else echo '<font class="abox_text_red">Logs not found.</font>';
    }

    if (isset($_POST) && $_SESSION['account']['id'] > 0 && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && $_GET['page'] == 'spylogs') {
        $strLogs = '';
        if (isset($_POST["n"])) {
            $varNumber = filter_var($_POST["n"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
            if (!is_numeric($varNumber)){die('Invalid page number!');}
        } else {
            $varNumber = 1;
        }
        $item_per_page = 10;
        /*
        $handle = fopen("./upload_spy/spy_list.txt", "r");
        if ($handle) {
            while (!feof($handle)) {
                $strLine = fgets($handle, 4096);

                if (preg_match('/user_id=<[0-9]+?>/', $strLine, $arrMatches)) {
                    $strUserId = str_replace(">" , "", str_replace("user_id=<" , "", $arrMatches[0]));
                    if ($strUserId == $_SESSION['account']['id']) {
                        if (preg_match('/id=<[a-z0-9]+?>/', $strLine, $arrMatches)) {
                            $strId = str_replace(">" , "", str_replace("id=<" , "", $arrMatches[0]));

                            if (preg_match('/title=<.+?>/', $strLine, $arrMatches)) {
                                $strTitle = str_replace(">" , "", str_replace("title=<" , "", $arrMatches[0]));
                                if ($strTitle != " uni=<") {
                                    if (preg_match('/uni=<.+?>/', $strLine, $arrMatches)) {
                                        $strUni = str_replace(">" , "", str_replace("uni=<" , "", $arrMatches[0]));
                                    }
                                    if (preg_match('/domain=<.+?>/', $strLine, $arrMatches)) {
                                        $strDomain = str_replace(">" , "", str_replace("domain=<" , "", $arrMatches[0]));
                                    }
                                    if (preg_match('/time=<[0-9]+?>/', $strLine, $arrMatches)) {
                                        $strTime = str_replace(">" , "", str_replace("time=<" , "", $arrMatches[0]));
                                    }

                                    $varResult[$strId] = array("title" => $strTitle, "universe" => $strUni, "domain" => $strDomain, "date" => $strTime);
                                }
                            }
                        }
                    }
                }
            }
            fclose($handle);
        }
        */
        $intUserID = $_SESSION['account']['id'];
        $varResult = cDB::LoadEspList($intUserID, false, false);
        if ($varResult && count($varResult) > 0) {
            $strGetTotalRows = count($varResult);
            $total_pages = ceil($strGetTotalRows/$item_per_page);

            $page_position = (($varNumber-1) * $item_per_page);
            $strNum = 0;
            $strLogs .= '<table class="contents" style="border-collapse: collapse" border="1" bordercolor="#222222" cellpadding="4" width="780">';
            $strLogs .= '<tr height="28">
                        <td align="center" background="index_files/abox/header.png" width="20"><font class="abox_text">#</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 1)" align="center" background="index_files/abox/header.png" width="60"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Date</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 2)" align="center" background="index_files/abox/header.png" width="350"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Title</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 2)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Player</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 2)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Core</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 5)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Uni</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 6)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Lang</font></td>
                        <td align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Del</font></td>
                   </tr>';
            foreach ($varResult as $logId => $value) {
                if ($strNum >= $page_position && $strNum < $page_position + $item_per_page) {
                    $strLogs .= '
                    <tr onmouseover="ActivateRow(this)" id="abox_' . base64_encode($value["log_id"]) . '" onmouseout="DeactivateRow(this)" src="index_files/abox/row_' . ($strNum % 2 + 1) . '.png" background="index_files/abox/row_' . ($strNum % 2 + 1) . '.png" height="28">
                      <td align="right"><font class="abox_text">' . ($strNum + 1) . '</font></td>
                      <td align="center"><font class="abox_text">' . DateS($value["date"]) . '</font></td>
                      <td align="left"><a href="index.php?id=' . $value["log_id"] . '" target="_blank"><font size="2">' . cutStr($value["title"], 70) . '</font></a></td>
                      <td align="center"><font class="abox_text">' . $value["player"] . '</font></td>
                      <td align="center"><font class="abox_text">' . $value["core"] . '</font></td>
                      <td align="center"><font class="abox_text">' . ShortNameUni($value["universe"],false) . '</font></td>
                      <td align="center"><font class="abox_text">' . strtolower($value["domain"]) . '</font></td>
                      <td align="center"><a href="javascript:DeleteSpyLog(\'' . base64_encode($value["log_id"]) . '\')" title="Delete" alt="Delete"><img src="index_files/abox/icon_delete.png" border="0" width="16"></a></td>
                    </tr>';
                }
                $strNum += 1;
            }
            $strLogs .= '</table>';

            $strLogs .= '<div align="center">';
            $strLogs .= paginate_function($item_per_page, $varNumber, $strGetTotalRows[0], $total_pages);
            $strLogs .= '</div>';

            echo $strLogs;
        } else echo '<font class="abox_text_red">Logs not found.</font>';
    }

    if (isset($_POST) && $_SESSION['account']['id'] > 0 && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && $_GET['page'] == 'altlogs') {
        $strLogs = '';
        if (isset($_POST["n"])) {
            $varNumber = filter_var($_POST["n"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
            if (!is_numeric($varNumber)){die('Invalid page number!');}
        } else {
            $varNumber = 1;
        }
        $item_per_page = countCookie ("option_select_old_logs");
        //$varResult = sortData(unserialize(gzuncompress(GetLogsAccount($_SESSION['account']['id'], 2, "T_LOGS"))));
        if ($varResult && count($varResult) > 0) {
            $strGetTotalRows = count($varResult);
            $total_pages = ceil($strGetTotalRows/$item_per_page);

            $page_position = (($varNumber-1) * $item_per_page);
            $strNum = 0;
            $strLogs .= '<table class="contents" style="border-collapse: collapse" border="1" bordercolor="#222222" cellpadding="4" width="780">';
            $strLogs .= '<tr height="28">
                        <td align="center" background="index_files/abox/header.png" width="20"><font class="abox_text">#</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 1)" align="center" background="index_files/abox/header.png" width="60"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Date</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 2)" align="center" background="index_files/abox/header.png" width="280"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Title</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 3)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Losses</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 5)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Uni</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 6)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Lang</font></td>
                   </tr>';
            foreach ($varResult as $logId => $value) {
                if ($strNum >= $page_position && $strNum < $page_position + $item_per_page) {
                    $strLogs .= '
                    <tr onmouseover="ActivateRow(this)" id="abox_' . base64_encode($logId) . '" onmouseout="DeactivateRow(this)" src="index_files/abox/row_' . ($strNum % 2 + 1) . '.png" background="index_files/abox/row_' . ($strNum % 2 + 1) . '.png" height="28">
                      <td align="right"><font class="abox_text">' . ($strNum + 1) . '</font></td>
                      <td align="center"><font class="abox_text">' . DateS($value["date"]) . '</font></td>
                      <td align="left"><a href="' . $logId . '" target="_blank"><font size="2">' . cutStr($value["title"], false) . '</font></a></td>
                      <td align="center">' . NumberS($value['losses'], false) . '</td>
                      <td align="center"><font class="abox_text">' . ShortNameUni($value["universe"],false) . '</font></td>
                      <td align="center"><font class="abox_text">' . strtolower($value["domain"]) . '</font></td>
                    </tr>';
                }
                $strNum += 1;
            }
            $strLogs .= '</table>';

            $strLogs .= '<div align="center">';
            $strLogs .= paginate_function($item_per_page, $varNumber, $strGetTotalRows[0], $total_pages);
            $strLogs .= '</div>';

            echo $strLogs;
        } else echo '<font class="abox_text_red">Logs not found.</font>';
    }

    if (isset($_POST) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && $_GET['page'] == 'popularlogs') {
        if (isset($_POST["n"])) {
            $varNumber = filter_var($_POST["n"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
            if (!is_numeric($varNumber)){die('Invalid page number!');}
        } else {
            $varNumber = 1;
        }
        $item_per_page = 10;

        $a = KillInjection($_POST['a']);
        if ($a) $_SESSION['popularlogs']['a'] = $a;

        $strLimit = " LIMIT " . countCookie ("option_select_p_logs"); ;

        if ($_SESSION['popularlogs']['a'] == "weekpop" || !isset($_SESSION['popularlogs']['a'])) $intTime = 7 * 24 * 3600;
        if ($_SESSION['popularlogs']['a'] == "yearpop") $intTime = 365 * 24 * 3600;
        if ($_SESSION['popularlogs']['a'] == "allpop") $intTime = false;

        if ($intTime) $intTime = "AND `date` >= " . (time() - $intTime) . " ";

        $strOrderBy = " ORDER BY `views` DESC ";

        $strSelect = "SELECT `log_id`, `universe`, `domain`, `losses`, `aprofit`, `dprofit`, `date`, `title`, `public`, `views` FROM `T_LOGS_N` WHERE  `public` = '1'";

        $strQuery = $strSelect.$intTime.$strOrderBy.$strLimit;

        $arrResult = cDB::QueryDB($strQuery);
        if (!$arrResult) {
            LogError('LoadUserLogs', 'cDB::QueryDB failed');
            return false;
        }

        while($objRow = $arrResult->fetch_array(MYSQLI_ASSOC)) {
            $varResult[] = $objRow;
        }

        if ($varResult && count($varResult) > 0) {
            $strGetTotalRows = count($varResult);
            $total_pages = ceil($strGetTotalRows/$item_per_page);

            $page_position = (($varNumber-1) * $item_per_page);
            $strNum = 0;
            $strLogs .= '<table class="contents" style="border-collapse: collapse" border="1" bordercolor="#222222" cellpadding="4" width="780">';
            $strLogs .= '<tr height="28">
                        <td align="center" background="index_files/abox/header.png" width="20"><font class="abox_text">#</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 1)" align="center" background="index_files/abox/header.png" width="60"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Date</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 2)" align="center" background="index_files/abox/header.png" width="350"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Title</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 2)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Losses</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 2)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Profit</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 5)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Uni</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 6)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Lang</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 9)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Views</font></td>
                   </tr>';
            foreach ($varResult as $value) {
                if ($strNum >= $page_position && $strNum < $page_position + $item_per_page) {
                    $strLogs .= '
                    <tr onmouseover="ActivateRow(this)" id="abox_' . base64_encode($value["log_id"]) . '" onmouseout="DeactivateRow(this)" src="index_files/abox/row_' . ($strNum % 2 + 1) . '.png" background="index_files/abox/row_' . ($strNum % 2 + 1) . '.png" height="28">
                      <td align="right"><font class="abox_text">' . ($strNum + 1) . '</font></td>
                      <td align="center"><font class="abox_text">' . DateS($value["date"]) . '</font></td>
                      <td align="left"><a href="index.php?id=' . $value["log_id"] . '" target="_blank"><font size="2">' . cutStr($value["title"], 50) . '</font></a></td>
                      <td align="center"><font class="abox_text">' . NumberS($value["losses"], false) . '</font></td>
                      <td align="center"><font class="abox_text">' . PrepareNumber($value['aprofit']) . '/' . PrepareNumber($value['dprofit']) . '</font></td>
                      <td align="center"><font class="abox_text">' . ShortNameUni($value["universe"],false) . '</font></td>
                      <td align="center"><font class="abox_text">' . strtolower($value["domain"]) . '</font></td>
                      <td align="center"><font class="abox_text">' . ViewsS($value["views"]) . '</font></td>
                    </tr>';
                }
                $strNum += 1;
            }
            $strLogs .= '</table>';

            $strLogs .= '<div align="center">';
            $strLogs .= paginate_function($item_per_page, $varNumber, $strGetTotalRows[0], $total_pages);
            $strLogs .= '</div>';

            echo $strLogs;
        } else echo '<font class="abox_text_red">Logs not found.</font>';
    }

    if (isset($_POST) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && $_GET['page'] == 'popularlosses') {
        if (isset($_POST["n"])) {
            $varNumber = filter_var($_POST["n"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
            if (!is_numeric($varNumber)){die('Invalid page number!');}
        } else {
            $varNumber = 1;
        }
        $item_per_page = 10;

        $a = KillInjection($_POST['a']);
        if ($a) $_SESSION['popularlosses']['a'] = $a;

        $strLimit = " LIMIT " . (countCookie ("option_select_p_logs") + 20);

        if ($_SESSION['popularlosses']['a'] == "weeklosses" || !isset($_SESSION['popularlosses']['a'])) $intTime = 7 * 24 * 3600;
        if ($_SESSION['popularlosses']['a'] == "yearlosses") $intTime = 365 * 24 * 3600;
        if ($_SESSION['popularlosses']['a'] == "alllosses") $intTime = false;

        if ($intTime) $intTime = "AND `date` >= " . (time() - $intTime) . " ";

        $strOrderBy = " ORDER BY `losses` DESC, `views` DESC";

        $strSelect = "SELECT `log_id`, `universe`, `domain`, `losses`, `aprofit`, `dprofit`, `date`, `title`, `public`, `views`, `html_log` FROM `T_LOGS_N` WHERE  `public` = '1'";

        $strQuery = $strSelect.$intTime.$strOrderBy.$strLimit;

        $arrResult = cDB::QueryDB($strQuery);
        if (!$arrResult) {
            LogError('LoadUserLogs', 'cDB::QueryDB failed');
            return false;
        }

        while($objRow = $arrResult->fetch_array(MYSQLI_ASSOC)) {
            $html_log = gzuncompress($objRow['html_log']);
            if (!isset($varResult[$html_log])) $varResult[$html_log] = $objRow;
        }
        
        $varResult = array_slice($varResult, 0, countCookie ("option_select_p_logs"));

        if ($varResult && count($varResult) > 0) {
            $strGetTotalRows = count($varResult);
            $total_pages = ceil($strGetTotalRows/$item_per_page);

            $page_position = (($varNumber-1) * $item_per_page);
            $strNum = 0;
            $strLogs .= '<table class="contents" style="border-collapse: collapse" border="1" bordercolor="#222222" cellpadding="4" width="780">';
            $strLogs .= '<tr height="28">
                        <td align="center" background="index_files/abox/header.png" width="20"><font class="abox_text">#</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 1)" align="center" background="index_files/abox/header.png" width="60"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Date</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 2)" align="center" background="index_files/abox/header.png" width="350"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Title</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 2)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Losses</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 2)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Profit</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 5)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Uni</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 6)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Lang</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 9)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Views</font></td>
                   </tr>';
            foreach ($varResult as $value) {
                if ($strNum >= $page_position && $strNum < $page_position + $item_per_page) {
                    $strLogs .= '
                    <tr onmouseover="ActivateRow(this)" id="abox_' . base64_encode($value["log_id"]) . '" onmouseout="DeactivateRow(this)" src="index_files/abox/row_' . ($strNum % 2 + 1) . '.png" background="index_files/abox/row_' . ($strNum % 2 + 1) . '.png" height="28">
                      <td align="right"><font class="abox_text">' . ($strNum + 1) . '</font></td>
                      <td align="center"><font class="abox_text">' . DateS($value["date"]) . '</font></td>
                      <td align="left"><a href="index.php?id=' . $value["log_id"] . '" target="_blank"><font size="2">' . cutStr($value["title"], 50) . '</font></a></td>
                      <td align="center"><font class="abox_text">' . NumberS($value["losses"], false) . '</font></td>
                      <td align="center"><font class="abox_text">' . PrepareNumber($value['aprofit']) . '/' . PrepareNumber($value['dprofit']) . '</font></td>
                      <td align="center"><font class="abox_text">' . ShortNameUni($value["universe"],false) . '</font></td>
                      <td align="center"><font class="abox_text">' . strtolower($value["domain"]) . '</font></td>
                      <td align="center"><font class="abox_text">' . ViewsS($value["views"]) . '</font></td>
                    </tr>';
                }
                $strNum += 1;
            }
            $strLogs .= '</table>';

            $strLogs .= '<div align="center">';
            $strLogs .= paginate_function($item_per_page, $varNumber, $strGetTotalRows[0], $total_pages);
            $strLogs .= '</div>';

            echo $strLogs;
        } else echo '<font class="abox_text_red">Logs not found.</font>';
    }

    if ($_GET['page'] == 'lastlogs') {
        if (isset($_POST["n"])) {
            $varNumber = filter_var($_POST["n"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
            if (!is_numeric($varNumber)){die('Invalid page number!');}
        } else {
            $varNumber = 1;
        }
        $item_per_page = 10;

        $strDomain = KillInjection($_POST['domain']);
        $strUni = KillInjection($_POST['uni']);

        $strLimit = " LIMIT 30 ";
        $strAND = "`public` = '1'";
        if ($strDomain)     $strAND .= " AND `domain` = '".mb_strtolower($strDomain)."'";
        if ($strUni)        $strAND .= " AND `universe` = '".$strUni."'";
        $strOrderBy = " ORDER BY `date` DESC ";

        $strSelect = "SELECT * FROM `T_LOGS_N` WHERE ";
        $strQuery = $strSelect.$strAND.$strOrderBy.$strLimit;

        $arrResult = cDB::QueryDB($strQuery);
        if (!$arrResult) {
            LogError('LoadUserLogs', 'cDB::QueryDB failed');
            return false;
        }

        while($objRow = $arrResult->fetch_array(MYSQLI_ASSOC)) {
            $varResult[] = $objRow;
        }

        if ($varResult && count($varResult) > 0) {
            $strGetTotalRows = count($varResult);
            $total_pages = ceil($strGetTotalRows/$item_per_page);

            $page_position = (($varNumber-1) * $item_per_page);
            $strNum = 0;
            $strLogs .= '<table class="contents" style="border-collapse: collapse" border="1" bordercolor="#222222" cellpadding="4" width="780">';
            $strLogs .= '<tr height="28">
                        <td align="center" background="index_files/abox/header.png" width="20"><font class="abox_text">#</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 1)" align="center" background="index_files/abox/header.png" width="60"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Date</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 2)" align="center" background="index_files/abox/header.png" width="350"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Title</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 2)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Losses</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 2)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Profit</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 5)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Uni</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 6)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Lang</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 9)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Views</font></td>
                   </tr>';
            foreach ($varResult as $value) {
                if ($strNum >= $page_position && $strNum < $page_position + $item_per_page) {
                    $strLogs .= '
                    <tr onmouseover="ActivateRow(this)" id="abox_' . base64_encode($value["log_id"]) . '" onmouseout="DeactivateRow(this)" src="index_files/abox/row_' . ($strNum % 2 + 1) . '.png" background="index_files/abox/row_' . ($strNum % 2 + 1) . '.png" height="28">
                      <td align="right"><font class="abox_text">' . ($strNum + 1) . '</font></td>
                      <td align="center"><font class="abox_text">' . DateS($value["date"]) . '</font></td>
                      <td align="left"><a href="index.php?id=' . $value["log_id"] . '" target="_blank"><font size="2">' . cutStr($value["title"], 70) . '</font></a></td>
                      <td align="center"><font class="abox_text">' . NumberS($value["losses"], false) . '</font></td>
                      <td align="center"><font class="abox_text">' . PrepareNumber($value['aprofit']) . '/' . PrepareNumber($value['dprofit']) . '</font></td>
                      <td align="center"><font class="abox_text">' . ShortNameUni($value["universe"],false) . '</font></td>
                      <td align="center"><font class="abox_text">' . strtolower($value["domain"]) . '</font></td>
                      <td align="center"><font class="abox_text">' . ViewsS($value["views"]) . '</font></td>
                    </tr>';
                }
                $strNum += 1;
            }
            $strLogs .= '</table>';

            $strLogs .= '<div align="center">';
            $strLogs .= paginate_function($item_per_page, $varNumber, $strGetTotalRows[0], $total_pages);
            $strLogs .= '</div>';

            echo $strLogs;  
        } else echo '<font class="abox_text_red">Logs not found.</font>';
    }

    if ($_GET['page'] == 'alliance') {
        $intUserID = $_SESSION['account']['id'];
        $strUserLogin = $_SESSION['account']['login'];
        if (!$intUserID) return;

        if (isset($_GET["domain"]) && isset($_GET["uni"]) && !isset($_GET["group_id"])) {
            $strDomain = mb_strtolower(KillInjection($_GET['domain']));
            $strUni = KillInjection($_GET['uni']);

            $varResult = cDB::CreateGroup($intUserID, $strUserLogin, $strUni, $strDomain);

            if ($varResult) {
                if ($varResult == "double")
                    echo "double";
                if ($varResult == "save")
                    echo "save";
            }
        } 
        else if (isset($_GET["group_id"])) {
            $strDomain = mb_strtolower(KillInjection($_GET['domain']));
            $strUni = KillInjection($_GET['uni']);
            $strGroupId = KillInjection($_GET['group_id']);
            $strUserLogin = KillInjection($_GET['user_login']);

            $varResult = cDB::InviteForGroup($strGroupId, $strUserLogin, $strUni, $strDomain);
        }
        else if (isset($_GET["invite"])) {
            $varResult = cDB::LoadGroupList($intUserID, false, false, 0);

            if ($varResult) {
                $strLogs = '<table class="contents" style="border-collapse: collapse" border="1" bordercolor="#222222" cellpadding="4" width="780">';
                $strLogs .= '<tr height="28">
                            <td align="center" background="index_files/abox/header.png" width="20"><font class="abox_text">#</font></td>
                            <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 1)" align="center" background="index_files/abox/header.png" width="60"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Date</font></td>
                            <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 2)" align="center" background="index_files/abox/header.png" width="350"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Title</font></td>
                            <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 5)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Uni</font></td>
                            <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 6)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Lang</font></td>
                            <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 9)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">invite</font></td>
                            <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 9)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Del</font></td>
                       </tr>';
                $strNum = 0;
                foreach ($varResult as $key => $value) {
                    $strLogs .= '
                    <tr onmouseover="ActivateRow(this)" id="abox_' . base64_encode($value["group_id"]) . '" onmouseout="DeactivateRow(this)" src="index_files/abox/row_' . ($strNum % 2 + 1) . '.png" background="index_files/abox/row_' . ($strNum % 2 + 1) . '.png" height="28">
                      <td align="right"><font class="abox_text">' . ($strNum + 1) . '</font></td>
                      <td align="center"><font class="abox_text">' . DateS($value["date"]) . '</font></td>
                      <td align="left"><a href="#"><font size="2">' . cutStr($value["group_id"], 70) . '</font></a></td>
                      <td align="center"><font class="abox_text">' . ShortNameUni($value["universe"],false) . '</font></td>
                      <td align="center"><font class="abox_text">' . strtolower($value["domain"]) . '</font></td>
                      <td align="center"><font class="abox_text"><img src="index_files/ico/add.png" class="userAdd" alt="+"></td>
                      <td align="center"><font class="abox_text"><img src="index_files/ico/close.png" class="userAdd" alt="+"></td>
                    </tr>';
                    $strNum += 1;
                }
                
                $strLogs .= '</table>';
                echo $strLogs;                                 
            }
            else echo '<font class="abox_text_red">Invite not found.</font>';            
        } else {
            $varResult = cDB::LoadGroupList($intUserID, false, false, 1);

            if ($varResult) {
                $strLogs = '<table class="contents" style="border-collapse: collapse" border="1" bordercolor="#222222" cellpadding="4" width="780">';
                $strLogs .= '<tr height="28">
                            <td align="center" background="index_files/abox/header.png" width="20"><font class="abox_text">#</font></td>
                            <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 1)" align="center" background="index_files/abox/header.png" width="60"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Date</font></td>
                            <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 2)" align="center" background="index_files/abox/header.png" width="350"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Title</font></td>
                            <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 5)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Uni</font></td>
                            <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 6)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Lang</font></td>
                            <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 9)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">invite</font></td>
                            <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 9)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Del</font></td>
                       </tr>';
                $strNum = 0;
                foreach ($varResult as $key => $value) {
                    $groupId = $value["group_id"];
                    $arrGroupId = cDB::LoadGroup($groupId);
                    $strLogs .= '
                    <tr onmouseover="ActivateRow(this)" id="abox_' . base64_encode($value["group_id"]) . '" onmouseout="DeactivateRow(this)" src="index_files/abox/row_' . ($strNum % 2 + 1) . '.png" background="index_files/abox/row_' . ($strNum % 2 + 1) . '.png" height="28">
                      <td align="right"><font class="abox_text">' . ($strNum + 1) . '</font></td>
                      <td align="center"><font class="abox_text">' . DateS($value["date"]) . '</font></td>
                      <td align="left"><a href="#"><font size="2">' . cutStr($value["group_id"], 70) . '</font></a></td>
                      <td align="center"><font class="abox_text">' . ShortNameUni($value["universe"],false) . '</font></td>
                      <td align="center"><font class="abox_text">' . strtolower($value["domain"]) . '</font></td>
                      <td align="center"><font class="abox_text"><img src="index_files/ico/add.png" group="' . $value["group_id"] . '" uni="' . $value["universe"] . '" domain="' . $value["domain"] . '" class="groupUserAdd" alt="+"></td>
                      <td align="center"><font class="abox_text"><img src="index_files/ico/close.png" class="groupDel" alt="+"></td>
                    </tr>';
                    foreach ($arrGroupId as $key => $group) {
                        $strLogs .= '
                        <tr onmouseover="ActivateRow(this)" id="abox_' . base64_encode($value["group_id"]) . '" onmouseout="DeactivateRow(this)" src="index_files/abox/row_' . ($strNum % 2 + 1) . '.png" background="index_files/abox/row_' . ($strNum % 2 + 1) . '.png" height="28">
                          <td align="right"><font class="abox_text"></font></td>
                          <td align="center"><font class="abox_text">' . DateS($group["date"]) . '</font></td>
                          <td align="center"><font size="2" class="abox_text">' . $group["user_login"] . '</a></td>
                          <td align="center"><font class="abox_text"></font></td>
                          <td align="center"><font class="abox_text"></font></td>
                          <td align="center"><font class="abox_text">' . $group["status"] . '</td>
                          <td align="center"><font class="abox_text"><img src="index_files/ico/close.png" class="userAdd" alt="+"></td>
                        </tr>';
                    }
                    $strNum += 1;
                }
                
                $strLogs .= '</table>';
                echo $strLogs;                                 
            }
            else echo '<font class="abox_text_red">Alliance not found.</font>';
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
                    $arr[$value["log_id"]] = array("date" => $value["date"], "title" => $value["title"], "losses" => $value['losses'], "aprofit" => $value['aprofit'], "dprofit" => $value['dprofit'], "universe" => $value["universe"], "domain" => $value["domain"], "public" => $value["public"]);
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

    function paginate_function($item_per_page, $current_page, $total_records, $total_pages) {
        $strPagination = '';
        if ($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages) {
            $strPagination .= '<br><ul class="pagination">';

            $right_links    = $current_page + 3;
            $previous       = $current_page - 1; //previous link
            $next           = $current_page + 1; //next link
            $first_link     = true; //boolean var to decide our first link

            if ($current_page > 1){
                $previous_link = ($previous==0)?1:$previous;
                $strPagination .= '<a href="#" data-page="1" title="First"><li class="first">&laquo;</li></a>'; //first link
                $strPagination .= '<a href="#" data-page="'.$previous_link.'" title="Previous"><li>&lt;</li></a>'; //previous link
                    for($i = ($current_page-2); $i < $current_page; $i++){
                        if($i > 0){
                            $strPagination .= '<a href="#" data-page="'.$i.'" title="Page'.$i.'"><li>'.$i.'</li></a>';
                        }
                    }
                $first_link = false; //set first link to false
            }

            if ($first_link) {
                $strPagination .= '<li class="first active">'.$current_page.'</li>';
            }
            elseif ($current_page == $total_pages) {
                $strPagination .= '<li class="last active">'.$current_page.'</li>';
            } else {
                $strPagination .= '<li class="active">'.$current_page.'</li>';
            }

            for ($i = $current_page+1; $i < $right_links ; $i++){
                if ($i <= $total_pages) {
                    $strPagination .= '<a href="#" data-page="'.$i.'" title="Page '.$i.'"><li>'.$i.'</li></a>';
                }
            }
            if ($current_page < $total_pages) {
                    //$next_link = ($i > $total_pages)? $total_pages : $i;
                    $strPagination .= '<a href="#" data-page="'.$next.'" title="Next"><li>&gt;</li></a>'; //next link
                    $strPagination .= '<a href="#" data-page="'.$total_pages.'" title="Last"><li class="last">&raquo;</li></a>'; //last link
            }
            $strPagination .= '</ul>';
        }
        return $strPagination;
    }

    function GetTitle($strAttackers, $strDefenders) {
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

        $strReturn = "<font color='#990000'>" . $strAttacker . "</font> <font color='#848484'>vs.</font> <font color='#008800'>" . $strDefender . "</font>";
        
        return $strReturn;
    }
?>