<?php
	session_start();
    date_default_timezone_set('Europe/Moscow');
    require 'h_abox.php';
    require 'h_constants.php';
    require 'h_localizations.php';
    require 'h_dom.php';
    require 'h_db.php';

    $page = KillInjection($_GET['page']);

    function raidHtml($strUrl, $postData) {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $strUrl);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

            $outPut = curl_exec($ch);

            curl_close($ch);

            $outPutDom = str_get_html($outPut);

            return $outPutDom;

            $outPutDom->clear();
            unset($outPutDom);
    }

    function strHTML($varResult, $strDomen) {
            if (strpos($varResult, $strDomen) !== false) {
                $varBBurl = str_replace ($_SESSION["url"], $varResult, $_SESSION["bburl"]);

                $strHTML = "";
    		    $strHTML .=  "	<tr id='contentShahterovNet'>";
        		$strHTML .=  "		<td valign='top' width='100'>";
        		$strHTML .=  "			<font face='Arial' color='" . WHITE_DARK . "' size='2'>ALT. URL:&nbsp;</font>";
        		$strHTML .=  "		</td>";
        		$strHTML .=  "		<td>";
    			$strHTML .=  "			<input type='text' class='text' name='' size='100' value='" . $varResult . "' style='border:1px solid " . WHITE_DARK . "; color: " . WHITE_DARK . "; font-family: Arial; font-size: 12px; background-color: #000000' onclick='this.select();'>";
    		    $strHTML .=  "			<a href='" . $varResult . "' target='_blank' style='text-decoration: none'><font face='Arial' color='" . WHITE_DARK . "' size='2'>" . Dictionary('navigate') . " </font>&#9658;</a>";
        		$strHTML .=  "		</td>";
    		    $strHTML .=  "	</tr>";
    		    $strHTML .=  "	<tr>";
        		$strHTML .=  "		<td valign='top' width='100'>";
        		$strHTML .=  "			<font face='Arial' color='" . WHITE_DARK . "' size='2'>ALT. BB-URL:&nbsp;</font>";
        		$strHTML .=  "		</td>";
        		$strHTML .=  "		<td>";
    			$strHTML .=  "			<textarea rows='2' name='' cols='120' style='font-size: 12px; font-family: Arial; color:" . WHITE_DARK . "; background-color:#000000; border-style:solid; border: 1px solid " . WHITE_DARK . ";' onclick='this.select();'>".$varBBurl."</textarea>";
        		$strHTML .=  "		</td>";
    		    $strHTML .=  "	</tr>";
                echo $strHTML;
            } else echo 0;
    }

    function GetAltShahterov($strLog, $strRecycler, $strComment, $strUrl, $strRepl) {
        if ($strLog) {
            if ($strRepl) {
                $strLog = str_replace('<figure class="planetIcon planet tooltip js_hideTipOnMobile" title="Планета"></figure>', '', $strLog);
                $strLog = str_replace('<figure class="planetIcon moon tooltip js_hideTipOnMobile" title="Луна"></figure>', '', $strLog);
            }

            $strLog = iconv("UTF-8", "WINDOWS-1251", $strLog);
            if ($strRecycler != "*") $strRecycler = iconv("UTF-8", "WINDOWS-1251", $strRecycler);
            else $strRecycler = "";
            $strComment = iconv("UTF-8", "WINDOWS-1251", $strComment);
            $postData = array (
                "log" => $strLog,
                "rep" => $strRecycler,
                "comment" => $strComment
            );

            $outPutDom = raidHtml($strUrl, $postData);

            $strUrl = $outPutDom->find('input');

            return $strUrl[0]->value;
        } else return false;
    }

    function GetAltWarLogs($strLog, $strRecycler, $strComment, $strUrl) {
        if ($strLog) {
            if ($strRecycler == "*") $strRecycler = "";
            $postData = array (
                "raw_html" => $strLog,
                "recyclers" => $strRecycler,
                "comment" => $strComment,
                "uni" => "1",
                "save_log" => "1"
            );

            $outPutDom = raidHtml($strUrl, $postData);

            $strUrl = $outPutDom->find('input');

            return $strUrl[4]->value;
        } else return false;
    }

    function GetAltGstrategy($strLog, $strUrl) {
        if ($strLog) {
            $strLog = iconv("UTF-8", "WINDOWS-1251", $strLog);
            $postData = array (
                "nm" => "0",
                "log" => $strLog,
                "allb" => "11"
            );

            $outPutDom = raidHtml($strUrl, $postData);

            $strUrl = $outPutDom->find('a');

            return $strUrl[5]->href;
        } else return false;
    }

    if($page == 'shahterov') {
        $strRepl = true;
        $varResult = GetAltShahterov($_SESSION["htmllog"], $_SESSION["recyclerreport"], $_SESSION["comment"], 'http://shahterov.net/tool/plugin_save.php', $strRepl);
        if ($varResult) {
            $varLogResult = cDB::LoadTitleByID($_SESSION["id"]);
            $arr[$varResult] = array("date" => $varLogResult["date"], "title" => $varLogResult["title"], "losses" => $varLogResult["losses"], "universe" => $varLogResult["universe"], "domain" => $varLogResult["domain"], "public" => $varLogResult["public"]);
            cDB::AddLogsAccount ($_SESSION['account']['id'], 2, $arr);
            strHTML($varResult, "shahterov.net");
        } else echo 0;
    }

    if($page == 'shahterov_plugin') {
        $strLog = $_POST['log'];
        $strRep = $_POST['rep'];
        $strComment = $_POST['comment'];
        $strRepl = false;
        $varResult = GetAltShahterov($strLog, $strRep, $strComment, 'http://shahterov.net/tool/plugin_save.php', $strRepl);
        if ($varResult) {
            echo '<a href="'.$varResult.'">'.$varResult.'</a>';
        } else echo 0;
    }

    if($page == 'warlogs') {
        $varResult = GetAltWarLogs($_SESSION["htmllog"], $_SESSION["recyclerreport"], $_SESSION["comment"], 'http://warlogs.ru/logs/');
        if ($varResult) {
            $varLogResult = cDB::LoadTitleByID($_SESSION["id"]);
            $arr[$varResult] = array("date" => $varLogResult["date"], "title" => $varLogResult["title"], "losses" => $varLogResult["losses"], "universe" => $varLogResult["universe"], "domain" => $varLogResult["domain"], "public" => $varLogResult["public"]);
            cDB::AddLogsAccount ($_SESSION['account']['id'], 2, $arr);
            strHTML($varResult, "warlogs.ru");
        } else echo 0;
    }

    if($page == 'gstrategy') {
        $varResult = GetAltGstrategy($_SESSION["htmllog"], 'http://ogame.gstrategy.ru/index.php');
        if ($varResult) {
            $varLogResult = cDB::LoadTitleByID($_SESSION["id"]);
            $arr[$varResult] = array("date" => $varLogResult["date"], "title" => $varLogResult["title"], "losses" => $varLogResult["losses"], "universe" => $varLogResult["universe"], "domain" => $varLogResult["domain"], "public" => $varLogResult["public"]);
            cDB::AddLogsAccount ($_SESSION['account']['id'], 2, $arr);
            strHTML($varResult, "ogame.gstrategy.ru");
        } else echo 0;
    }
?>