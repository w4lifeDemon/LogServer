<?php
    function QueryXPath($strHTML, $strXPath) {
        $arrInnerHTML = null;

        $strHTML = mb_convert_encoding($strHTML, "HTML-ENTITIES", "UTF-8");

        $objDOMDocument = new DOMDocument();


        error_reporting(0);
        $objDOMDocument->loadHTML($strHTML);
        error_reporting(E_ALL);

        $XPath = new DOMXPath($objDOMDocument);
        $arrElements = $XPath->query($strXPath);

        foreach ($arrElements as $objElement) {
            $arrInnerHTML[] = mb_convert_encoding(GetInnerHTML_($objElement), "UTF-8", "HTML-ENTITIES");
        }

        if (!$arrInnerHTML) {
            LogError("QueryXPath", "Nothing found: ".$strXPath);
            return false;
        }

        return $arrInnerHTML;
    }

    function is_mobile() {
        $user_agent = strtolower(getenv('HTTP_USER_AGENT'));
        $accept = strtolower(getenv('HTTP_ACCEPT'));
     
        if ((strpos($accept,'text/vnd.wap.wml')!==false) ||
            (strpos($accept,'application/vnd.wap.xhtml+xml')!==false)) {
            return 1; // Мобильный браузер обнаружен по HTTP-заголовкам
        }
     
        if (isset($_SERVER['HTTP_X_WAP_PROFILE']) ||
            isset($_SERVER['HTTP_PROFILE'])) {
            return 2; // Мобильный браузер обнаружен по установкам сервера
        }
     
        if (preg_match('/(mini 9.5|vx1000|lge |m800|e860|u940|ux840|compal|'.
            'wireless| mobi|ahong|lg380|lgku|lgu900|lg210|lg47|lg920|lg840|'.
            'lg370|sam-r|mg50|s55|g83|t66|vx400|mk99|d615|d763|el370|sl900|'.
            'mp500|samu3|samu4|vx10|xda_|samu5|samu6|samu7|samu9|a615|b832|'.
            'm881|s920|n210|s700|c-810|_h797|mob-x|sk16d|848b|mowser|s580|'.
            'r800|471x|v120|rim8|c500foma:|160x|x160|480x|x640|t503|w839|'.
            'i250|sprint|w398samr810|m5252|c7100|mt126|x225|s5330|s820|'.
            'htil-g1|fly v71|s302|-x113|novarra|k610i|-three|8325rc|8352rc|'.
            'sanyo|vx54|c888|nx250|n120|mtk |c5588|s710|t880|c5005|i;458x|'.
            'p404i|s210|c5100|teleca|s940|c500|s590|foma|samsu|vx8|vx9|a1000|'.
            '_mms|myx|a700|gu1100|bc831|e300|ems100|me701|me702m-three|sd588|'.
            's800|8325rc|ac831|mw200|brew |d88|htc\/|htc_touch|355x|m50|km100|'.
            'd736|p-9521|telco|sl74|ktouch|m4u\/|me702|8325rc|kddi|phone|lg |'.
            'sonyericsson|samsung|240x|x320vx10|nokia|sony cmd|motorola|'.
            'up.browser|up.link|mmp|symbian|smartphone|midp|wap|vodafone|o2|'.
            'pocket|kindle|mobile|psp|treo|android|iphone|ipod|webos|wp7|wp8|'.
            'fennec|blackberry|htc_|opera m|windowsphone)/', $user_agent)) {
            return 3; // Мобильный браузер обнаружен по сигнатуре User Agent
        }
     
        if (in_array(substr($user_agent,0,4),
            Array("1207", "3gso", "4thp", "501i", "502i", "503i", "504i", "505i", "506i",
                  "6310", "6590", "770s", "802s", "a wa", "abac", "acer", "acoo", "acs-",
                  "aiko", "airn", "alav", "alca", "alco", "amoi", "anex", "anny", "anyw",
                  "aptu", "arch", "argo", "aste", "asus", "attw", "au-m", "audi", "aur ",
                  "aus ", "avan", "beck", "bell", "benq", "bilb", "bird", "blac", "blaz",
                  "brew", "brvw", "bumb", "bw-n", "bw-u", "c55/", "capi", "ccwa", "cdm-",
                  "cell", "chtm", "cldc", "cmd-", "cond", "craw", "dait", "dall", "dang",
                  "dbte", "dc-s", "devi", "dica", "dmob", "doco", "dopo", "ds-d", "ds12",
                  "el49", "elai", "eml2", "emul", "eric", "erk0", "esl8", "ez40", "ez60",
                  "ez70", "ezos", "ezwa", "ezze", "fake", "fetc", "fly-", "fly_", "g-mo",
                  "g1 u", "g560", "gene", "gf-5", "go.w", "good", "grad", "grun", "haie",
                  "hcit", "hd-m", "hd-p", "hd-t", "hei-", "hiba", "hipt", "hita", "hp i",
                  "hpip", "hs-c", "htc ", "htc-", "htc_", "htca", "htcg", "htcp", "htcs",
                  "htct", "http", "huaw", "hutc", "i-20", "i-go", "i-ma", "i230", "iac",
                  "iac-", "iac/", "ibro", "idea", "ig01", "ikom", "im1k", "inno", "ipaq",
                  "iris", "jata", "java", "jbro", "jemu", "jigs", "kddi", "keji", "kgt",
                  "kgt/", "klon", "kpt ", "kwc-", "kyoc", "kyok", "leno", "lexi", "lg g",
                  "lg-a", "lg-b", "lg-c", "lg-d", "lg-f", "lg-g", "lg-k", "lg-l", "lg-m",
                  "lg-o", "lg-p", "lg-s", "lg-t", "lg-u", "lg-w", "lg/k", "lg/l", "lg/u",
                  "lg50", "lg54", "lge-", "lge/", "libw", "lynx", "m-cr", "m1-w", "m3ga",
                  "m50/", "mate", "maui", "maxo", "mc01", "mc21", "mcca", "medi", "merc",
                  "meri", "midp", "mio8", "mioa", "mits", "mmef", "mo01", "mo02", "mobi",
                  "mode", "modo", "mot ", "mot-", "moto", "motv", "mozz", "mt50", "mtp1",
                  "mtv ", "mwbp", "mywa", "n100", "n101", "n102", "n202", "n203", "n300",
                  "n302", "n500", "n502", "n505", "n700", "n701", "n710", "nec-", "nem-",
                  "neon", "netf", "newg", "newt", "nok6", "noki", "nzph", "o2 x", "o2-x",
                  "o2im", "opti", "opwv", "oran", "owg1", "p800", "palm", "pana", "pand",
                  "pant", "pdxg", "pg-1", "pg-2", "pg-3", "pg-6", "pg-8", "pg-c", "pg13",
                  "phil", "pire", "play", "pluc", "pn-2", "pock", "port", "pose", "prox",
                  "psio", "pt-g", "qa-a", "qc-2", "qc-3", "qc-5", "qc-7", "qc07", "qc12",
                  "qc21", "qc32", "qc60", "qci-", "qtek", "qwap", "r380", "r600", "raks",
                  "rim9", "rove", "rozo", "s55/", "sage", "sama", "samm", "sams", "sany",
                  "sava", "sc01", "sch-", "scoo", "scp-", "sdk/", "se47", "sec-", "sec0",
                  "sec1", "semc", "send", "seri", "sgh-", "shar", "sie-", "siem", "sk-0",
                  "sl45", "slid", "smal", "smar", "smb3", "smit", "smt5", "soft", "sony",
                  "sp01", "sph-", "spv ", "spv-", "sy01", "symb", "t-mo", "t218", "t250",
                  "t600", "t610", "t618", "tagt", "talk", "tcl-", "tdg-", "teli", "telm",
                  "tim-", "topl", "tosh", "treo", "ts70", "tsm-", "tsm3", "tsm5", "tx-9",
                  "up.b", "upg1", "upsi", "utst", "v400", "v750", "veri", "virg", "vite",
                  "vk-v", "vk40", "vk50", "vk52", "vk53", "vm40", "voda", "vulc", "vx52",
                  "vx53", "vx60", "vx61", "vx70", "vx80", "vx81", "vx83", "vx85", "vx98",
                  "w3c ", "w3c-", "wap-", "wapa", "wapi", "wapj", "wapm", "wapp", "wapr",
                  "waps", "wapt", "wapu", "wapv", "wapy", "webc", "whit", "wig ", "winc",
                  "winw", "wmlb", "wonu", "x700", "xda-", "xda2", "xdag", "yas-", "your",
                  "zeto", "zte-"))) {
            return 4; // Мобильный браузер обнаружен по сигнатуре User Agent
        }
     
        return false; // Мобильный браузер не обнаружен
    }

    function GetHeadHTML($strHTML) {
        return substr($strHTML, 0, strpos($strHTML, "<body"));
    }

    function GetInnerHTML($strHTML, $strTagName, $arrAttributes) {
        $arrInnerHTML = null;

        $strHTML = mb_convert_encoding($strHTML, "HTML-ENTITIES", "UTF-8");

        $objDOMDocument = new DOMDocument();

        error_reporting(0);
        $objDOMDocument->loadHTML($strHTML);
        error_reporting(E_ALL);

        $arrElements = $objDOMDocument->getElementsByTagName($strTagName);

        foreach ($arrElements as $objElement) {
            $blnCheck = true;
            if ($arrAttributes) {
                foreach ($arrAttributes as $Attribute) {
                    if ($objElement->getAttribute($Attribute['name']) != $Attribute['value']) {
                        $blnCheck = false;
                        break;
                    }
                }
            }

            if ($blnCheck) {
                $arrInnerHTML[] = mb_convert_encoding(GetInnerHTML_($objElement), "UTF-8", "HTML-ENTITIES");
            }
        }

        if (!$arrInnerHTML) {
            LogError("GetInnerHTML", "Nothing found: ".$strTagName);
            return false;
        }

        return $arrInnerHTML;
    }

    function GetInnerHTML_($Node) {
        $innerHTML = "";

        $children = $Node->childNodes;
        foreach ($children as $child) {
            $tmp_doc = new DOMDocument();
            $tmp_doc->appendChild($tmp_doc->importNode($child, true));
            $innerHTML .= $tmp_doc->saveHTML();
        }

        if ($innerHTML == "")
            return false;
        else
            return $innerHTML;
    }

    function GetUniDomain($strWhere, $strWhat) {
        $varResult = false;
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

    function GetUniType($strWhere) {
        $varResult = 0;

        $intUniType = GetUniDomainEx($strWhere, "uni");

        if ($intUniType == 999) {
            $varResult = 1;
        }

        return  $varResult;
    }

    function GetUniDomainEx($strWhere, $strWhat) {
        $varResult = false;
        $arrPattern = GetPatterns_0x();
        $blnResult = true;
        foreach ($arrPattern as $strPattern) {
            if (!preg_match("/" . $strPattern . "/", strtolower(str_replace("\\", "", $strWhere)))) {
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
                if (!preg_match("/" . $strPattern . "/", strtolower(str_replace("\\", "", $strWhere)))) {
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
//Zmei не работает
        $varResult = (integer)999;
        return $varResult;
    }

    function GetPatterns_0x() {
        $strPattern_0x = "^\s*?<html>\s*?";
        $strPattern_0x .=   "<head>\s*?";
        $strPattern_0x .=       "<link.+?>\s*?";
        $strPattern_0x .=       "<meta.+?>\s*?";
        $strPattern_0x .=       "<title>.+?<\/title>\s*?";
        $strPattern_0x .=   "<\/head>\s*?";
        $strPattern_0x .=   "<body>\s*?";
        $strPattern_0x .=       "<div id='overdiv'.+?><\/div>\s*?";

        $arrResult[] = $strPattern_0x;
        $strPattern_0x = "";

        /*$strPattern_0x .=         "<table.+>\s*";
        $strPattern_0x .=           "<tr>\s*";
        $strPattern_0x .=               "<td>\s*";
        $strPattern_0x .=                   "(.|\s)+?";
        $strPattern_0x .=               "<\/td>\s*";
        $strPattern_0x .=           "<\/tr>\s*";
        $strPattern_0x .=       "<\/table>\s*";*/

        $strPattern_0x .=   "<\/body>\s*?";
        $strPattern_0x .= "<\/html>\s*?$";

        $arrResult[] = $strPattern_0x;
        $strPattern_0x = "";

        return $arrResult;
    }
//fix by zmei
    function GetPatterns_1x() {
        $strPattern_1x = "^\s*?<!doctype.+?>\s*?";
        $strPattern_1x .= "<html.+?>\s*?";
        $strPattern_1x .=   "<head>\s*";

        $arrResult[] = $strPattern_1x;
        $strPattern_1x = "";

        $strPattern_1x .= "uni[0-9]+\.[\.a-z]*ogame\.[\.a-z]*\s*";

        $arrResult[] = $strPattern_1x;
        $strPattern_1x = "";

        $strPattern_1x .=    "<\/head>\s*?";
        $strPattern_1x .=    "<body id='combatreport'>\s*?";
        $strPattern_1x .=       "<div id='master'>\s*?";
        $strPattern_1x .=           "<div class='combat_round'>\s*?";
        $strPattern_1x .=               "<div class='round_info'>\s*?";
        $strPattern_1x .=                   "<p class='start'>(.|\s)+?<\/p>\s*?";
        $strPattern_1x .=                   "<p class='start opponents'>.+?<\/p>\s*?";
        $strPattern_1x .=               "<\/div>\s*";

        $arrResult[] = $strPattern_1x;
        $strPattern_1x = "";

        /*$strPattern_1x .=             "<table.+>";
        $strPattern_1x .=                   "(.|\s)+";
        $strPattern_1x .=               "<\/table>\s*";*/

        $strPattern_1x .=           "<\/div>\s*?";
        $strPattern_1x .=           "<div id='combat_result'>\s*?";
        $strPattern_1x .=               "<p class='action'>\s*?";
        $strPattern_1x .=               "(.|\s)+?";
        $strPattern_1x .=               "<\/p>\s*?";
        $strPattern_1x .=               "<p class='action'>\s*?";
        $strPattern_1x .=               "(.|\s)+?";
        $strPattern_1x .=               "<\/p>\s*?";
        $strPattern_1x .=           "<\/div><!-- combat_result -->\s*?";
        $strPattern_1x .=       "<\/div><!-- master -->\s*?";
        $strPattern_1x .=    "<\/body>\s*?";
        $strPattern_1x .= "<\/html>\s*$";

        $arrResult[] = $strPattern_1x;
        $strPattern_1x = "";

        return $arrResult;
    }

    function ShowHTML($strTableInner, $strTitle, $strMeta) {
        $varStart = gettimeofday();
        $strHTML = "<html>
            <head>
                <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
                <meta name='theme-color' content='#000000'>
                " . $strMeta . "
                <link rel='apple-touch-icon' sizes='144x144' href='index_files/favicon/apple-touch-icon.png'>
                <link rel='icon' type='image/png' sizes='32x32' href='index_files/favicon/favicon-32x32.png'>
                <link rel='icon' type='image/png' sizes='16x16' href='index_files/favicon/favicon-16x16.png'>
                <link rel='manifest' href='index_files/favicon/site.webmanifest'>
                <link rel='mask-icon' href='index_files/favicon/safari-pinned-tab.svg' color='#5bbad5'>
                <meta name='msapplication-TileColor' content='#00a300'>
                <meta name='theme-color' content='#ffffff'>
                <script language='javascript' src='" . JQUERY . "'></script>
                <script language='javascript' src='" . MAINJSLIBRARY . "?t=" . microtime(true) . "'></script>
                <script language='javascript' src='" . JS_ABOX . "'></script>
                <script language='javascript' src='" . JS_XSS . "'></script>
                <script language='javascript' src='" . BB_EDITOR_JSLIBRARY . "'></script>
                <link id='index_css' rel='stylesheet' type='text/css' href='" . CSS_INDEX . "' media='screen' />
                <link rel='bb-code editor style' href='" . BB_EDITOR_STYLE . "' type=text/css>
                <title>" . $strTitle . "</title>
            </head>
            ";
        $strDBResult = cDB::QueryDB('SELECT `log_id` FROM `T_LOGS_N` WHERE 1 LIMIT 1 ;');
        if (!$strDBResult || isset($_COOKIE["del_bd_logserver"])) $strHTML .= "<div id='formCont'>
                      <div class='formBox lock'> </div>
                      <div id='formReport' class='formBox formReport'>
                        <div class='boxTop'>Технические работы <a href='' class='formClose'>Закрыть</a></div>
                        <div class='formCont'>
                          <fieldset id='formOver'>
                            <center>
                                <br />Отсутствует соединение с базой данных.
                                <br /><br /><a href='https://board.ru.ogame.gameforge.com/index.php/Thread/68584-LogServer-%D0%9B%D0%BE%D0%B3%D0%BE%D0%BC%D0%BE%D0%BB%D0%BE%D1%82%D0%B8%D0%BB%D0%BA%D0%B0-OGame-ru-%D0%BF%D0%BE%D0%B1%D0%B5%D0%B4%D0%B8%D1%82%D0%B5%D0%BB%D1%8C-%D0%BA%D0%BE%D0%BD%D0%BA%D1%83%D1%80%D1%81%D0%B0-%D0%BB%D0%BE%D0%B3%D0%BE%D0%B2%D0%BD%D0%B8%D1%86-%D0%BE%D0%B3%D0%B5%D0%B9%D0%BC%D0%B0/last-post.html' target='_self'>Информация на форуме.</a>
                            </center>
                          </fieldset>
                        </div>
                        <div class='formFooter'></div>
                      </div>
                    </div>";
         $strHTML .= "<body id='body' bgcolor='#000000' background='". BODY_BACKGROUND ."' style='background-attachment: fixed; background-position: center; background-repeat: no-repeat' onload='FP_preloadImgs(\"".PROGRESS_GREEN."\", \"".VISTA_UPLOAD_NORMAL."\", \"".VISTA_UPLOAD_ACTIVE."\", \"".VISTA_PANEL."\", \"".VISTA_BUTTON_NORMAL."\", \"".VISTA_BUTTON_ACTIVE."\", \"".VISTA_BUTTON_PRESSED."\", \"".VISTA_BUTTON_PRESSED_ACTIVE."\"); JS_AnimateLogo(); PopMessageJs();'>";
         //скрипт авторизации визуализации checkbox
         $strHTMLuf = "<form name='upload_form' id='upload_form' enctype='multipart/form-data' action='index.php' method='post'>";
        //<prepare>
            $strTdTagActive = "align='left' valign='center' width='120' height='28' background='".VISTA_BUTTON_PRESSED."' onmouseover='this.setAttribute(\"background\", \"".VISTA_BUTTON_PRESSED_ACTIVE."\")' onmouseout='this.setAttribute(\"background\", \"".VISTA_BUTTON_PRESSED."\")'";
            $strTdTagPassive = "align='left' valign='center' width='120' height='28' background='".VISTA_BUTTON_NORMAL."' onmouseover='this.setAttribute(\"background\", \"".VISTA_BUTTON_ACTIVE."\")' onmouseout='this.setAttribute(\"background\", \"".VISTA_BUTTON_NORMAL."\")' onmousedown='this.setAttribute(\"background\", \"".VISTA_BUTTON_PRESSED."\")'";
            if (!isset($_SESSION["user"])) {
                $_SESSION["user"] = array();
                $_SESSION["user"]["current_page"] = "main";
            }
            ($_SESSION["user"]["current_page"] == "main") ? ($strLogServerTdTag = $strTdTagActive) : ($strLogServerTdTag = $strTdTagPassive);
            $strForumTdTag = $strTdTagPassive;
            ($_SESSION["user"]["current_page"] == "account") ? ($strAccountTdTag = $strTdTagActive) : ($strAccountTdTag = $strTdTagPassive);
            ($_SESSION["user"]["current_page"] == "public" || $_SESSION["user"]["current_page"] == "public_x" || $_SESSION["user"]["current_page"] == "err_x" || $_SESSION["user"]["current_page"] == "tmp_x") ? ($strPublicTdTag = $strTdTagActive) : ($strPublicTdTag = $strTdTagPassive);
            ($_SESSION["user"]["current_page"] == "info" || $_SERVER["QUERY_STRING"] == "show=fixlist" || $_SERVER["QUERY_STRING"] == "show=thx") ? ($strInfoTdTag = $strTdTagActive) : ($strInfoTdTag = $strTdTagPassive);
            ($_SESSION["user"]["current_page"] == "plugin") ? ($strPluginTdTag = $strTdTagActive) : ($strPluginTdTag = $strTdTagPassive);
            ($_SESSION["user"]["current_page"] == "search") ? ($strSearchTdTag = $strTdTagActive) : ($strSearchTdTag = $strTdTagPassive);
            $strLogServerURL = "index.php";
            $strPublicURL = "index.php?show=public";
            $strInfoURL = "index.php?show=info";
            $strPluginURL = "index.php?show=plugin";
            $strAccountURL = "index.php?show=account";
            $strSearchURL = "index.php?show=search";
        //</prepare>
         $strHTML .= "$strHTMLuf
                <center>
                <table width='800' border='0' style='border-collapse: collapse' cellpadding='10' background='".TABLE_BACKGROUND."'>
                    <tr><td height='4' style='padding: 0'></td></tr>
                    <tr>
                        <td align='center' valign='center' background='".VISTA_PANEL."' style='padding: 0px'>
                            <table width='800' border='0' style='border-collapse: collapse'>
                                <tr>
                                    <td width='20' height='30' align='left' background='"."index_files/vista_panel/vista_exp_20x30.png"."'></td>
                                    <td height='30' align='center'>";

        $strHTML .= "
                                        <table class='error' border='0' bordercolor='#000000' style='border-collapse: collapse' cellpadding='0'>
                                            <tr>
                                                <td ".$strLogServerTdTag." onclick='document.location.href=\"".$strLogServerURL."\"'>
                                                    <a href='".$strLogServerURL."' style='text-decoration: none' onmouseover='style.cursor=(\"default\")'><table><tr><td style='padding-left: 6px'><img src='".ICON_LOGSERVER."' border='0' width='16'></td><td style='padding-left: 2px'><font color='" . WHITE_COMMON . "' face='Arial' size='2'>" . Dictionary('menu_main') . "</font></td></tr></table></a>
                                                </td>
                                                <td width='2'></td>
                                                <td ".$strAccountTdTag." onclick='document.location.href=\"".$strAccountURL."\"'>
                                                    <a href='".$strAccountURL."' style='text-decoration: none' onmouseover='style.cursor=(\"default\")'><table><tr><td style='padding-left: 6px'><img src='".ICON_ACCOUNT."' border='0' width='16'></td><td style='padding-left: 2px'><font color='" . WHITE_COMMON . "' face='Arial' size='2'>" . Dictionary('menu_account') . "</font></td></tr></table></a>
                                                </td>
                                                <td width='2'></td>
                                                <td ".$strPublicTdTag." onclick='document.location.href=\"".$strPublicURL."\"'>
                                                    <a href='".$strPublicURL."' style='text-decoration: none' onmouseover='style.cursor=(\"default\")'><table><tr><td style='padding-left: 6px'><img src='".ICON_PUBLIC."' border='0' width='16'></td><td style='padding-left: 2px'><font color='" . WHITE_COMMON . "' face='Arial' size='2'>" . Dictionary('menu_public') . "</font></td></tr></table></a>
                                                </td>
                                                <td width='2'></td>
                                                <td ".$strSearchTdTag." onclick='document.location.href=\"".$strSearchURL."\"'>
                                                    <a href='".$strSearchURL."' style='text-decoration: none' onmouseover='style.cursor=(\"default\")'><table><tr><td style='padding-left: 6px'><img src='".ICON_SEARCH."' border='0' width='16'></td><td style='padding-left: 2px'><font color='" . WHITE_COMMON . "' face='Arial' size='2'>" . Dictionary('menu_search') . "</font></td></tr></table></a>
                                                </td>
                                                <td width='2'></td>
                                                <td ".$strInfoTdTag." onclick='document.location.href=\"".$strInfoURL."\"'>
                                                    <a href='".$strInfoURL."' style='text-decoration: none' onmouseover='style.cursor=(\"default\")'><table><tr><td style='padding-left: 6px'><img src='".ICON_INFO."' border='0' width='16'></td><td style='padding-left: 2px'><font color='" . WHITE_COMMON . "' face='Arial' size='2'>" . Dictionary('menu_info') . "</font></td></tr></table></a>
                                                </td>
                                                <td width='2'></td>
                                                <td ".$strPluginTdTag." onclick='document.location.href=\"".$strPluginURL."\"'>
                                                    <a href='".$strPluginURL."' style='text-decoration: none' onmouseover='style.cursor=(\"default\")'><table><tr><td style='padding-left: 6px'><img src='".ICON_PLUGIN."' border='0' width='16'></td><td style='padding-left: 2px'><font color='" . WHITE_COMMON . "' face='Arial' size='2'>" . Dictionary('menu_plugin') . "</font></td></tr></table></a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td width='20' height='30' align='left' background='"."index_files/vista_panel/vista_exp_20x30_2.png"."'></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                            <td align='center'>";

        $strAccountURL = "index.php?show=account";
        $strChangePass = "index.php?show=changepass";
        $strLogoutURL = "index.php?logout=1";
        $stcAdmin = "index.php?show=tool";
        $stcFakeAdmin = "index.php?show=admin";
        $stcOption = "index.php?show=settings";

        $strChangeP = "";
        $strLoginMsg = "";
        $strFakeAdmin = "";
        $strAdmin = "";

        if (key_exists('account', $_SESSION)) {
            $strLoginMsg = $_SESSION['account']['login'];
            $strLogoutMsg = "[<a href='$strLogoutURL'><font size='1'>Logout</font></a>]";
            $strChangeP = "[<a href='$strChangePass'><font size='1'>Change password</font></a>]";

            if (in_array($_SESSION['account']['login'], listFakeAdmin())) {
                $strFakeAdmin = " [<a href='$stcFakeAdmin'><font size='1' color='#FF0000'>Admin</font></a>]";
            }

            if (in_array($_SESSION['account']['login'], listAdmin())) {
                $strAdmin = " [<a href='$stcAdmin'><font size='1' color='yellow'>Tool</font></a>]";
            }
        }
        else {
            $strLoginMsg = "Login"; $strLogoutMsg = "";
        }
            $strOption = " [<a href='$stcOption'><font size='1' color='#009900'>Settings</font></a>]";

        $strHTML .= "
                                    <table border='0' width='100%' style='border-collapse: collapse' cellpadding='0'>
                                        <tr>
                                            <td width='200' align='left' valign='top'>
                                                <font color='#888888' face='Arial' size='1'>User: [<a href='$strAccountURL'><font size='1'>$strLoginMsg</font></a>] $strChangeP $strFakeAdmin $strAdmin $strOption $strLogoutMsg</font>
                                                <br>
                                                " . ReadNews(3) . "
                                            </td>
                                            <td align='center'>
                                                <img id='logserver_img' src='" . LOGLOGO . "' border='0' width='400' alt='LogServer.net'>
                                            </td>
                                            <td width='200' align='right' valign='top'>
                                                <table border='0' style='border-collapse: collapse' cellpadding='0'>
                                                    <tr>
                                                        <td width=20; height='14' id='snake' style='cursor: pointer; background: transparent url(" . IMG_MMOFLAGS . ") no-repeat; background-position:left -56px !important'><img src='" . IMG_FLAG_EMPTY . "' height='14' alt='SN' title='Snake' border='0'></td>
                                                        <td width='4'></td>
                                                        <td width=20; height='14' style='background: transparent url(" . IMG_MMOFLAGS . ") no-repeat; background-position:left -42px !important'><a href='index.php?lang=bg' onclick='" . JSCookie('lang', 'bg') . ";'><img src='" . IMG_FLAG_EMPTY . "' height='14' alt='BG' title='Bulgarian' border='0'></a></td>
                                                        <td width='4'></td>
                                                        <td width=20; height='14' style='background: transparent url(" . IMG_MMOFLAGS . ") no-repeat; background-position:left -168px !important'><a href='index.php?lang=de' onclick='" . JSCookie('lang', 'de') . ";'><img src='" . IMG_FLAG_EMPTY . "' height='14' alt='DE' title='German' border='0'></a></td>
                                                        <td width='4'></td>
                                                        <td width=20; height='14' style='background: transparent url(" . IMG_MMOFLAGS . ") no-repeat; background-position:left -224px !important'><a href='index.php?lang=en' onclick='" . JSCookie('lang', 'en') . ";'><img src='" . IMG_FLAG_EMPTY . "' height='14' alt='EN' title='English' border='0'></a></td>
                                                        <td width='4'></td>
                                                        <td width=20; height='14' style='background: transparent url(" . IMG_MMOFLAGS . ") no-repeat; background-position:left -280px !important'><a href='index.php?lang=fr' onclick='" . JSCookie('lang', 'fr') . ";'><img src='" . IMG_FLAG_EMPTY . "' height='14' alt='FR' title='French' border='0'></a></td>
                                                        <td width='4'></td>
                                                        <td width=20; height='14' style='background: transparent url(" . IMG_MMOFLAGS . ") no-repeat; background-position:left -672px !important'><a href='index.php?lang=ru' onclick='" . JSCookie('lang', 'ru') . ";'><img src='" . IMG_FLAG_EMPTY . "' height='14' alt='RU' title='Russian' border='0'></a></td>
                                                        <td width='4'></td>
                                                        <td width=20; height='14' style='background: transparent url(" . IMG_MMOFLAGS . ") no-repeat; background-position:left -770px !important'><a href='index.php?lang=ua' onclick='" . JSCookie('lang', 'ua') . ";'><img src='" . IMG_FLAG_EMPTY . "' height='14' alt='UA' title='Ukrainian' border='0'></a></td>
                                                    </tr>
                                                </table>
                                                <table border='0' style='border-collapse: collapse' cellpadding='0'>
                                                    <tr>
                                                        <td align='right'>
                                                            <a href='index.php?show=fixlist'><font size='1'>" . Dictionary('fix_list') . "</font></a>
                                                            <font face='Arial' color='" . WHITE_DARK . "' size='1'> ~ </font>
                                                            <a href='index.php?show=thx'><font size='1'>" . Dictionary('thanks') . "</font></a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align='right'>
                                                            <a href='index.php?show=universes'><font size='1'>" . Dictionary('uni_features') . "</font>
                                                        </td>
                                                    </tr>                                                   
                                                    <tr>
                                                        <td align='right'>
                                                            <font color='#888888' face='Arial' size='1'>" . Dictionary('r_users') . " " . NumberToString(cDB::GetUsersCount('r_users')) . "<!-- ALL --></font>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align='right'>
                                                            <font color='#888888' face='Arial' size='1'>" . Dictionary('uploaded') . " " . NumberToString(cDB::GetLogsCount('uploaded')) . "<!-- 24 --></font>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align='right'>
                                                            <font color='#888888' face='Arial' size='1'>" . Dictionary('total_uploaded') . " " . NumberToString(cDB::GetLogsCount('total_uploaded')) . "<!-- ALL --></font>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan='3' align='center'>
                                                <a href='index.php?show=info'>". TEST . " " . Dictionary("logserver_title") . "</a><br>
                                                <a href='" . PLUGIN . "'><font face='Arial' color='red' size='1'>Plugin: LogServer.net GM script</font></a>
                                            </td>
                                        </tr>
                                    </table>";
        $strHTML .= "           </td>";
        $strHTML .= "       </tr>";
        $strHTML .= $strTableInner;

        $varEnd = gettimeofday();
        $varTimeResult = (float)($varEnd['sec'] - $varStart['sec']) + ((float)($varEnd['usec'] - $varStart['usec'])/1000000);

        $strHTML .= "   </table>";
        $strHTML .= "   </center>";
        $strHTML .= "   </form>";
        $strHTML .= "   <center>";
        $strHTML .= "       <table width='100%' background='index_files/transparent_50x50.png' style='border-collapse: collapse;' cellpadding='0'>";
        $strHTML .= "        <tr>";
        $strHTML .= "            <td align='center'>";
        $strHTML .= "                <center>";
        $strHTML .= "                <font face='Arial' color='#888888' style='font-size: 8pt'>[ LogServer <a href='/index.php?show=fixlist' style='font-size: 8pt'>" . VERSION . "</a> © 2010-" . date('Y') . " Skyline designs: <font style='font-size: 8pt'>ntrvr</font> & SuperSerhio, <div id='sv' style='display: inline; cursor: pointer;' onmousedown='return false' onselectstart='return false'>A</div>siman: <a href='mailto:w4lifedemon@gmail.com'><font style='font-size: 8pt'>e-mail</font></a>, <a href='skype:demonzzz4?chat'><font style='font-size: 8pt'>skype</font></a>, Яьуш / GNU GPL ]<br> " . Dictionary('index_pagegen') . " " . $varTimeResult . " " . Dictionary('index_pagegensec') . "</font>";
        $strHTML .= "                </center>";


        $strHTML .= "            </td>";
        $strHTML .= "        </tr>";
        $strHTML .= "        <tr><td height='4'></td></tr>";
        $strHTML .= "    </table>";

        $strHTML .= "</center>";
        $strHTML .= "</body>";
        $strHTML .= "</html>";
        echo $strHTML;


    }

    function CreateHelpDiv($strId, $intType, $strInnerHTMLBox) {
        switch ($intType) {
            case 0: $strColor = GREEN_COMMON; break;
            case 1: $strColor = YELLOW_COMMON; break;
            case 2: $strColor = RED_COMMON; break;
            default: case 0: $strColor = GREEN_COMMON; break;
        }

        return "
            <div id='$strId' style='display: none; position: absolute; z-index: 1; width: 100%; height: 100%; left: 0px; top: 0px' onclick='ShowHide(\"$strId\");'>
                <table border='0' width='100%' height='100%' background='".TABLE_BACKGROUND."'>
                    <tr>
                        <td align='center' valign='center' height='50%'>
                            <table border='1' background='".TABLE_BACKGROUND."' style='border-collapse: collapse;' bordercolor='$strColor' cellpadding='10'>
                                <tr>
                                    <td>
                                        $strInnerHTMLBox
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr><td height='50%'></td></tr>
                </table>
            </div>";
    }

//made by Zmei
    function ShowEditForm($arrLogs) {
        global $NameUni;
        $html_log;
        $Recycler;
        $Cleanup;
        $Comment;
        $strRecyclerTextarea = "";
        $objSource = $arrLogs->Get("objsource");
        if($arrLogs->Get("ownhtmllog") != "") $html_log = $arrLogs->Get("ownhtmllog");
        ($arrLogs->Get("reportpo") == "Attacker")?($strReportPO[0] = "checked"):($strReportPO[1] = "checked");

        ($objSource->strRecyclerReport != "")?($Recycler = $objSource->strRecyclerReport):($Recycler = false);
        ($objSource->strCleanUp != "")?($Cleanup = $objSource->strCleanUp):($Cleanup = false);

        if($objSource->strComment  != "") $Comment = $objSource->strComment;



        $objDlgWnd = new cDlgWnd();

        $strHelpDiv1 = $objDlgWnd->CreateDlgHTML("help_1", Dictionary("help"),
            Dictionary("for_uploading_title") . ":<br><br>
            1. " . Dictionary("for_uploading_step_1") . "<br>
            2. " . Dictionary("for_uploading_step_2") . "<br>
            3. " . Dictionary("for_uploading_step_3"));

        $strHelpDiv2 = $objDlgWnd->CreateDlgHTML("help_2", Dictionary("help"), "
                            " . Dictionary("hr_field") . "<br><br>
                            " . Dictionary("sample") . ":<br>
                            <font size='1'>rr-xx-xxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx</font>
                            <br><br>

                            <font color='" . RED_COMMON . "'>Оставьте галочку для \"Всё поле обломков переработано\", если всё поле обломков переработано</font>
                            ");

        $strHelpDiv3 = $objDlgWnd->CreateDlgHTML("help_3", Dictionary("help"), "
                            " . Dictionary("cleanup_field") . "
                            <br><br>
                            " . Dictionary("sample") . ":
                            <br>
                            <font size='1'>rr-xx-xxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx</font>
                            ");

        $strHelpDiv4 = $objDlgWnd->CreateDlgHTML("help_4", Dictionary("help"), "
                            " . Dictionary("comment_field") . "
                            ");

        $strHelpDiv5 = $objDlgWnd->CreateDlgHTML("help_5", Dictionary("warning"), "
                            <font color='" . RED_COMMON . "'>" . Dictionary("warning_1") . "</font>
                            <br><br>
                            " . Dictionary("warning_2") . "
                            <br>
                            " . Dictionary("warning_3") . "
                            ");
                            
        $strHelpDiv6 = $objDlgWnd->CreateDlgHTML("help_6", Dictionary("warning"), "
                            <font color='" . RED_COMMON . "'>Wrong Combat report (HTML-code)!</font>
                            ");                            

        $strLogTextarea .= "
            <table style='display:none' border='0' style='border-collapse: collapse'>
                <tr>
                    <td colspan='3'>
                        <input type='radio' name='rb_spy_report' value='V1' checked><font face='Arial' color='" . WHITE_DARK . "' size='2'>" . Dictionary("html_report") . "</font>
                        <input type='hidden' name='edit' value='". base64_encode($arrLogs->Get("logid"))."'>
                        <input type='radio' name='rb_spy_report' value='V2'><font color='" . WHITE_DARK . "' face='Arial' size='2'>" . Dictionary("spy_report") . "</font>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input rows='1' id='log_textarea' name='log_textarea' value='". $html_log . "' cols='178' readonly style='border-radius: 10px 10px 0 10px; font-size: 10px; font-family: Arial; color:" . WHITE_DARK . "; background-color:#000000; border-style:solid; border: 1px solid " . WHITE_DARK . ";' onchange='JS_CheckLog(this.value);' onmouseover='this.style.border=\"1px solid " . AQUA_COMMON . "\"'; onmouseout='this.style.border=\"1px solid " . WHITE_DARK . "\"' onclick=\"this.select();\">
                        <input id='protect' name='protect' type='hidden'>
                    </td>
                    <td width='2'></td>
                    <td>
                        <img src='" . ICON_QUESTION_P . "' alt='?' onmouseover='this.src=\"" . ICON_QUESTION_A . "\";' onmouseout='this.src=\"" . ICON_QUESTION_P . "\";' onclick='ShowHide(\"help_1\");'>
                    </td>
            </table>
        ";

        if ($_COOKIE["cbx_recycler"] == "true") {
            $strCbxRecycler = "checked='checked'"; 
            $strinpRecDisplay = "none"; 
        } 
        else {
            $strCbxRecycler = ""; 
            $strinpRecDisplay = "";
        }

        $strRecyclerTextarea .= "
            <table id='table_recycler' border='0' style='border-collapse: collapse'>
                <tr>
                    <td colspan='3'>
                        <input type='radio' name='rb_rec_report' value='V1' checked><font face='Arial' color='" . WHITE_DARK . "' size='2'>" . Dictionary("com_scpatk") . "</font>
                        <input type='radio' name='rb_rec_report' value='V2'><font color='" . WHITE_DARK . "' face='Arial' size='2'>" . Dictionary("com_scpdef") . "</font>
                        <input type='checkbox' id='cbx_recycler' name='cbx_recycler' value='ON' " . $strCbxRecycler . " onClick='if(!this.checked){document.getElementById(\"inputs_recycler\").style.display=\"\"} else {document.getElementById(\"inputs_recycler\").style.display=\"none\"};' onchange='" . JSCookie("cbx_recycler", "\"+this.checked+\"") . "'><font color='" . WHITE_DARK . "' face='Arial' size='2'>Всё поле обломков переработано</font>
                    </td>
                </tr>";
        if ($Recycler) {
            $arrRecycler = explode ("\n", $Recycler);
            foreach ($arrRecycler as $valueRecycler) {
                preg_match("|<!-- id:(.*?)-->|sei", $valueRecycler, $matches);
                if ($matches[1]) $idRecycler[] = trim($matches[1]);
            }
        }
        if ($Recycler && $idRecycler[0]) {
            foreach ($idRecycler as $keyRecycler => $strIdRecycler) {
                $strRecyclerTextarea .= "<tr class='recycler_tr_" . ($keyRecycler + 1) . "'>
                            <td style='width:625px; valign:top;'>
                                <input class='recycler_input_" . ($keyRecycler + 1) . "' name='recycler_textarea[]' value='" . $strIdRecycler . "' onfocus=\"if(this.value=='rr-xx-xxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx') this.value='';\" onblur=\"if (this.value=='') this.value='rr-xx-xxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'; if(this.value && this.value!='rr-xx-xxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx') ajaxRecycler(this.value, this.className);\" value='rr-xx-xxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx' style='width:525px; text-align:center; border-radius: 10px 10px 0 10px; font-size: 10px; font-family: Arial; color:" . WHITE_DARK . "; background-color:#000000; border-style:solid; border: 1px solid " . WHITE_DARK . ";' onmouseover='this.style.border=\"1px solid " . AQUA_COMMON . "\"'; onmouseout='this.style.border=\"1px solid " . WHITE_DARK . "\"'>";
                
                ($keyRecycler == (count($idRecycler) - 1))?($dispIconAdd = ""):($dispIconAdd = "none");
                $strRecyclerTextarea .= "<img src='" . ICON_ADD . "' class='recycler_add_" . ($keyRecycler + 1) . " recycler_add' style='display:" . $dispIconAdd . "' alt='+' onclick='AddRecyclerInput(this.className); this.style.display=\"none\"'>";
                $strRecyclerTextarea .= "<img src='" . ICON_CLOSE . "' class='recycler_close_" . ($keyRecycler + 1) . "' alt='-' onclick='CloseRecyclerInput(this.className);'>
                            </td>
                            <td class='recycler_ajax_" . ($keyRecycler + 1) . "' style='width:300px; valign:top;'></td>";
                if ($keyRecycler == 0) $strRecyclerTextarea .= "<td>
                                <img src='" . ICON_QUESTION_P . "' alt='?' onmouseover='this.src=\"" . ICON_QUESTION_A . "\";' onmouseout='this.src=\"" . ICON_QUESTION_P . "\";' onclick='ShowHide(\"help_2\");'>
                            </td>
                        </tr>";
                
                $strRecyclerTextarea .= "<script>ajaxRecycler('" . $strIdRecycler . "', 'recycler_input_" . ($keyRecycler + 1) . "');</script>";
            }
            $strRecyclerTextarea .= "</table>";
        } else {
            $strRecyclerTextarea .= "<tr id='inputs_recycler' style='display:" . $strinpRecDisplay . "'>
                    <td style='width:625px; valign:top;'>
                        <input class='recycler_input_1' name='recycler_textarea[]' onfocus=\"if(this.value=='rr-xx-xxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx') this.value='';\" onblur=\"if (this.value=='') this.value='rr-xx-xxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'; if(this.value && this.value!='rr-xx-xxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx') ajaxRecycler(this.value, this.className);\" value='rr-xx-xxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx' style='width:525px; text-align:center; border-radius: 10px 10px 0 10px; font-size: 10px; font-family: Arial; color:" . WHITE_DARK . "; background-color:#000000; border-style:solid; border: 1px solid " . WHITE_DARK . ";' onmouseover='this.style.border=\"1px solid " . AQUA_COMMON . "\"'; onmouseout='this.style.border=\"1px solid " . WHITE_DARK . "\"'>
                        <img src='" . ICON_ADD . "' class='recycler_add_1 recycler_add' alt='+' onclick='AddRecyclerInput(this.className); this.style.display=\"none\"'>
                    </td>
                    <td class='recycler_ajax_1' style='width:300px; valign:top;'></td>
                    <td>
                        <img src='" . ICON_QUESTION_P . "' alt='?' onmouseover='this.src=\"" . ICON_QUESTION_A . "\";' onmouseout='this.src=\"" . ICON_QUESTION_P . "\";' onclick='ShowHide(\"help_2\");'>
                    </td>
                </tr>
            </table>";          
        }
        $strCleanupTextarea .= "
            <table id='table_clean_up' border='0' style='border-collapse: collapse'>
                <tr>
                    <td colspan='3'>
                        <font face='Arial' color='" . WHITE_DARK . "' size='2'>" . Dictionary("clean_up") . "</font><br>
                    </td>
                </tr>";
        if ($Cleanup) {
            $arrCleanup = explode ("\n", $Cleanup);
            foreach ($arrCleanup as $valueCleanup) {
                preg_match("|<!-- id:(.*?)-->|sei", $valueCleanup, $matches);
                if ($matches[1]) $idCleanup[] = trim($matches[1]);
            }
        }
        if ($Cleanup && $idCleanup[0]) {    
            foreach ($idCleanup as $keyCleanup => $strIdCleanup) {
                    $strCleanupTextarea .= "<tr class='clean_up_tr_" . ($keyCleanup + 1) . "'>
                        <td style='width:625px; valign:top;'>
                            <input class='clean_up_input_" . ($keyCleanup + 1) . "' name='clean_up_textarea[]' value='" . $strIdCleanup . "' onfocus=\"if(this.value=='cr-xx-xxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx') this.value='';\" onblur=\"if (this.value=='') this.value='cr-xx-xxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'; if(this.value && this.value!='cr-xx-xxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx') ajaxCleanUp(this.value, this.className);\" value='cr-xx-xxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx' style='width:525px; text-align:center; border-radius: 10px 10px 0 10px; font-size: 10px; font-family: Arial; color:" . WHITE_DARK . "; background-color:#000000; border-style:solid; border: 1px solid " . WHITE_DARK . ";' onmouseover='this.style.border=\"1px solid " . AQUA_COMMON . "\"'; onmouseout='this.style.border=\"1px solid " . WHITE_DARK . "\"'>";
                
                    ($keyCleanup == (count($idCleanup) - 1))?($dispIconAdd = ""):($dispIconAdd = "none"); 
                    $strCleanupTextarea .= "<img src='" . ICON_ADD . "' class='clean_up_add_" . ($keyCleanup + 1) . " clean_up_add' style='display:" . $dispIconAdd . "' alt='+' onclick='AddCleanUpInput(this.className); this.style.display=\"none\"'>";
                
                    $strCleanupTextarea .= "<img src='" . ICON_CLOSE . "' class='clean_up_close_" . ($keyCleanup + 1) . "' alt='-' onclick='CloseCleanUpInput(this.className);'>
                        </td>
                        <td class='clean_up_ajax_" . ($keyCleanup + 1) . "' style='width:300px; valign:top;'></td>";
                
                    if ($keyCleanup == 0) $strCleanupTextarea .= "<td>
                            <img src='" . ICON_QUESTION_P . "' alt='?' onmouseover='this.src=\"" . ICON_QUESTION_A . "\";' onmouseout='this.src=\"" . ICON_QUESTION_P . "\";' onclick='ShowHide(\"help_3\");'>
                        </td>";
                
                $strCleanupTextarea .= "</tr>";
                $strCleanupTextarea .= "<script>ajaxCleanUp('" . $strIdCleanup . "', 'clean_up_input_" . ($keyCleanup + 1) . "');</script>";
            }
            $strCleanupTextarea .= "</table>";
        } else {
            $strCleanupTextarea .= "<tr>
                    <td style='width:625px; valign:top;'>
                        <input class='clean_up_input_1' name='clean_up_textarea[]' onfocus=\"if(this.value=='cr-xx-xxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx') this.value='';\" onblur=\"if (this.value=='') this.value='cr-xx-xxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'; if(this.value && this.value!='cr-xx-xxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx') ajaxCleanUp(this.value, this.className);\" value='cr-xx-xxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx' style='width:525px; text-align:center; border-radius: 10px 10px 0 10px; font-size: 10px; font-family: Arial; color:" . WHITE_DARK . "; background-color:#000000; border-style:solid; border: 1px solid " . WHITE_DARK . ";' onmouseover='this.style.border=\"1px solid " . AQUA_COMMON . "\"'; onmouseout='this.style.border=\"1px solid " . WHITE_DARK . "\"'>
                        <img src='" . ICON_ADD . "' class='clean_up_add_1 clean_up_add' alt='+' onclick='AddCleanUpInput(this.className); this.style.display=\"none\"'>
                    </td>
                    <td class='clean_up_ajax_1' style='width:300px; valign:top;'></td>
                    <td>
                        <img src='" . ICON_QUESTION_P . "' alt='?' onmouseover='this.src=\"" . ICON_QUESTION_A . "\";' onmouseout='this.src=\"" . ICON_QUESTION_P . "\";' onclick='ShowHide(\"help_3\");'>
                    </td>
                </tr>
            </table>";  
        }
///////////////         
        $strTdTagBB = "align='center' valign='center' height='28' style='padding-left: 2; padding-right: 2;' onmouseover='this.setAttribute(\"background\", \"" . VISTA_PANEL_A_BB_CODE . "\");' onmouseout='this.setAttribute(\"background\", \"\");'";
        $strCommentTextarea .= "
            <table border='0' style='border-collapse: collapse'>
                <tr>
                    <td colspan='3'>
                        <font face='Arial' color='" . WHITE_DARK . "' size='2'>" . Dictionary("comment") . "</font>"."<br>
                    </td>
                </tr>
                <tr id='BBCode' style='display: none;'>
                    <td height='28' background='" . VISTA_PANEL_BB_CODE . "' style='padding: 0'>
                        <table border='0' width='1' style='border-collapse: collapse' cellpadding='0'>
                            <tr>
                                <td ".$strTdTagBB." name='btnBold' title='Bold' onClick='doAddTags(\"[b]\",\"[/b]\",\"comment_textarea\")'>
                                    <table border='0' style='border-collapse: collapse' cellpadding='0'><tr><td><img src='" . STYLE_BOLD_BBCODE . "' border='0'></td></tr></table>
                                </td>
                                <td width='2'></td>
                                <td ".$strTdTagBB." name='btnItalic' title='Italic' onClick='doAddTags(\"[i]\",\"[/i]\",\"comment_textarea\")'>
                                    <table border='0' style='border-collapse: collapse' cellpadding='0'><tr><td><img src='" . STYLE_ITALIC_BBCODE . "' border='0'></td></tr></table>
                                </td>
                                <td width='2'></td>
                                <td ".$strTdTagBB." name='btnUnderline' title='Underline' onClick='doAddTags(\"[u]\",\"[/u]\",\"comment_textarea\")'>
                                    <table border='0' style='border-collapse: collapse' cellpadding='0'><tr><td><img src='" . STYLE_UNDERLINE_BBCODE . "' border='0'></td></tr></table>
                                </td>
                                <td width='2'></td>
                                <td ".$strTdTagBB."  name='btnStrikethrough' title='Strikethrough' onClick='doAddTags(\"[s]\",\"[/s]\",\"comment_textarea\")'>
                                    <table border='0' style='border-collapse: collapse' cellpadding='0'><tr><td><img src='" . STYLE_STRIKETROUGH_BBCODE . "' border='0'></td></tr></table>
                                </td>
                                <td width='2'></td>
                                <td>
                                    <table border='0' style='border-collapse: collapse' cellpadding='0'><tr><td><img src='" . SEPARATOR_BBCODE . "' border='0'></td></tr></table>
                                </td>
                                <td width='2'></td>
                                <td ".$strTdTagBB." name='btnAlingLeft' title='Align left' onClick='doAddTags(\"[left]\",\"[/left]\",\"comment_textarea\")'>
                                    <table border='0' style='border-collapse: collapse' cellpadding='0'><tr><td><img src='" . ALIGN_LEFT_BBCODE . "' border='0'></td></tr></table>
                                </td>
                                <td width='2'></td>
                                <td ".$strTdTagBB." name='btnAlingCenter' title='Align center' onClick='doAddTags(\"[center]\",\"[/center]\",\"comment_textarea\")'>
                                    <table border='0' style='border-collapse: collapse' cellpadding='0'><tr><td><img src='" . ALIGN_CENTER_BBCODE . "' border='0'></td></tr></table>
                                </td>
                                <td width='2'></td>
                                <td ".$strTdTagBB." name='btnAlingRight' title='Align right' onClick='doAddTags(\"[right]\",\"[/right]\",\"comment_textarea\")'>
                                    <table border='0' style='border-collapse: collapse' cellpadding='0'><tr><td><img src='" . ALIGN_RIGHT_BBCODE . "' border='0'></td></tr></table>
                                </td>
                                <td width='2'></td>
                                <td ".$strTdTagBB." name='btnAlingJustify' title='Align justify' onClick='doAddTags(\"[justify]\",\"[/justify]\",\"comment_textarea\")'>
                                    <table border='0' style='border-collapse: collapse' cellpadding='0'><tr><td><img src='" . JUSTIFY_BBCODE . "' border='0'></td></tr></table>
                                </td>
                                <td width='2'></td>
                                <td>
                                    <table border='0' style='border-collapse: collapse' cellpadding='0'><tr><td><img src='" . SEPARATOR_BBCODE . "' border='0'></td></tr></table>
                                </td>
                                <td width='2'></td>
                                <td ".$strTdTagBB." name='btnList' title='Unordered List' onClick='doList(\"[LIST]\",\"[/LIST]\",\"comment_textarea\")'>
                                    <table border='0' style='border-collapse: collapse' cellpadding='0'><tr><td><img src='" . STYLE_UNORDERED_BBCODE . "' border='0'></td></tr></table>
                                </td>
                                <td width='2'></td>
                                <td ".$strTdTagBB."  name='btnList' title='Ordered List' onClick='doList(\"[LIST=1]\",\"[/LIST]\",\"comment_textarea\")'>
                                    <table border='0' style='border-collapse: collapse' cellpadding='0'><tr><td><img src='" . STYLE_ORDERED_BBCODE . "' border='0'></td></tr></table>
                                </td>
                                <td width='2'></td>
                                <td>
                                    <table border='0' style='border-collapse: collapse' cellpadding='0'><tr><td><img src='" . SEPARATOR_BBCODE . "' border='0'></td></tr></table>
                                </td>
                                <td width='2'></td>
                                <td ".$strTdTagBB." name='btnLink' title='Insert URL Link' onClick='doURL(\"comment_textarea\")'>
                                    <table border='0' style='border-collapse: collapse' cellpadding='0'><tr><td ><img src='" . LINK_BBCODE . "' border='0'></td></tr></table>
                                </td>
                                <td width='2'></td>
                                <td>
                                    <table border='0' style='border-collapse: collapse' cellpadding='0'><tr><td><img src='" . SEPARATOR_BBCODE . "' border='0'></td></tr></table>
                                </td>
                                <td width='2'></td>
                                <td ".$strTdTagBB." name='btnPicture' title='Insert Image' onClick='doImage(\"comment_textarea\")'>
                                    <table border='0' style='border-collapse: collapse' cellpadding='0'><tr><td><img src='" . INSERT_IMAGE_BBCODE . "' border='0'></td></tr></table>
                                </td>
                                <td width='2'></td>
                                <td>
                                    <table border='0' style='border-collapse: collapse' cellpadding='0'><tr><td><img src='" . SEPARATOR_BBCODE . "' border='0'></td></tr></table>
                                </td>
                                <td width='2'></td>
                                <td ".$strTdTagBB." name='btnQuote' title='Quote' onClick='doAddTags(\"[quote]\",\"[/quote]\",\"comment_textarea\")'>
                                    <table border='0' style='border-collapse: collapse' cellpadding='0'><tr><td><img src='" . QUOTE_BBCODE . "' border='0'></td></tr></table>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td width='2'></td>
                    <td></td>
                </tr>
                <tr>
                    <td>
                        <textarea rows='2' name='comment_textarea' id='comment_textarea' cols='178' style='border-radius: 10px 10px 0 10px; font-size: 10px; font-family: Arial; color:" . WHITE_DARK . "; background-color:#000000; border-style:solid; border: 1px solid " . WHITE_DARK . ";' onclick='this.setAttribute(\"rows\", 4); document.getElementById(\"BBCode\").style.display=\"\"' onmouseover='this.style.border=\"1px solid " . AQUA_COMMON . "\"'; onmouseout='this.style.border=\"1px solid " . WHITE_DARK . "\"'>"
                        .$Comment."</textarea>
                    </td>
                    <td width='2'></td>
                    <td>
                        <img src='" . ICON_QUESTION_P . "' alt='?' onmouseover='this.src=\"" . ICON_QUESTION_A . "\";' onmouseout='this.src=\"" . ICON_QUESTION_P . "\";' onclick='ShowHide(\"help_4\");'>
                    </td>
                </tr>
            </table>";

        $strTextAreas = "
            $strLogTextarea
            $strRecyclerTextarea
            $strCleanupTextarea
            $strCommentTextarea";

        $strUITableX .= "
            <table border='1' style='border-collapse: collapse' width='100%'>
                <tr>
                    <td>
                        <img src='" . ICON_QUESTION . "' alt='Help'>
                    </td>
                    <td>
                        <a href='javascript:JS_ShowUsageInstruction()' onclick='aaa()' id='link_UI' title='" . Dictionary("for_uploading_title") . ": 1) " . Dictionary("for_uploading_step_1") . "; 2. " . Dictionary("for_uploading_step_2") . "; (3) " . Dictionary("for_uploading_step_3") . "'>Ў</a>
                    </td>
                </tr>
            </table>";

        (isset($_COOKIE["index_cbx_public"]) && $_COOKIE["index_cbx_public"] == "false") ? ($strCbxPublic = "") : ($strCbxPublic = "checked");
        (isset($_COOKIE["index_cbx_hide_coord"]) && $_COOKIE["index_cbx_hide_coord"] == "false") ? ($strCbxHideCoord = "") : ($strCbxHideCoord = "checked");
        (isset($_COOKIE["index_cbx_hide_tech"]) && $_COOKIE["index_cbx_hide_tech"] == "false") ? ($strCbxHideTech = "") : ($strCbxHideTech = "checked");
        (isset($_COOKIE["index_cbx_hide_time"]) && $_COOKIE["index_cbx_hide_time"] == "false") ? ($strCbxHideTime = "") : ($strCbxHideTime = "checked");
        (isset($_COOKIE["index_cbx_comments"]) && $_COOKIE["index_cbx_comments"] == "true") ? ($strCbxComments = "checked") : ($strCbxComments = "");
        (isset($_COOKIE["index_cbx_fuel"]) && $_COOKIE["index_cbx_fuel"] == "false") ? ($strCbxFuel = "") : ($strCbxFuel = "checked");
        (isset($_COOKIE["index_cbx_aliance"]) && $_COOKIE["index_cbx_aliance"] == "false") ? ($strCbxAliance = "") : ($strCbxAliance = "checked");
        (isset($_COOKIE["index_cbx_top"]) && $_COOKIE["index_cbx_top"] == "true") ? ($strCbxTOP = "checked") : ($strCbxTOP = "");
        (isset($_COOKIE["index_inp_combustion"])) ? ($strinpCombustion = $_COOKIE["index_inp_combustion"]) : ($strinpCombustion = 0);
        (isset($_COOKIE["index_inp_impulse"])) ? ($strinpImpulse = $_COOKIE["index_inp_impulse"]) : ($strinpImpulse = 0);
        (isset($_COOKIE["index_inp_hyperspace"])) ? ($strinpHyperspace = $_COOKIE["index_inp_hyperspace"]) : ($strinpHyperspace = 0);

        $strCheckBoxes = "
            <input type='checkbox' class='js-checkbox' name='cbx_public' value='ON' $strCbxPublic onchange='" . JSCookie("index_cbx_public", "\"+this.checked+\"") . "'><font color='" . WHITE_DARK . "' face='Arial' size='2'> " . Dictionary("public_log") . "</font>"." "."<a href='index.php?show=public' style='text-decoration: none'>&#9658;</a>
            <br>
            <input type='checkbox' class='js-checkbox' name='cbx_hide_coord' value='ON' $strCbxHideCoord onchange='" . JSCookie("index_cbx_hide_coord", "\"+this.checked+\"") . "'><font color='" . WHITE_DARK . "' face='Arial' size='2'> " . Dictionary("hide_coords") . "</font>
            <br>
            <input type='checkbox' class='js-checkbox' name='cbx_hide_tech' value='ON' $strCbxHideTech onchange='" . JSCookie("index_cbx_hide_tech", "\"+this.checked+\"") . "'><font color='" . WHITE_DARK . "' face='Arial' size='2'> " . Dictionary("hide_techs") . "</font>
            <br>
            <input type='checkbox' class='js-checkbox' name='cbx_hide_time' value='ON' $strCbxHideTime onchange='" . JSCookie("index_cbx_hide_time", "\"+this.checked+\"") . "'><font color='" . WHITE_DARK . "' face='Arial' size='2'> " . Dictionary("hide_time") . "</font>
            <br>
            <input type='checkbox' class='js-checkbox' name='cbx_comments' value='ON' $strCbxComments onchange='" . JSCookie("index_cbx_comments", "\"+this.checked+\"") . "'><font color='" . WHITE_DARK . "' face='Arial' size='2'> " . Dictionary("comments") . "</font><br>
            <input type=hidden name='submited' value='1'>";

        $strListBoxes = "
            <table border='0' style='border-collapse: collapse' cellpadding='2'><tr><td>
            <select size='1' name='select_uni' style='font-size: 10px; width: 120px;' onchange='" . JSCookie("index_select_uni", "this.selectedIndex") . "'>";

        foreach ($NameUni as $key => $value) {
            if ($value[1] != "?") {
                if (gettype($value[0]) == "integer") $value[0] = "Universe";
                $selectedUni = (isset($_COOKIE["index_select_uni"]) && $_COOKIE["index_select_uni"] == $key) ? " selected" : false;
                $strListBoxes .= "<option value='" . $key . "'" . $selectedUni . ">" . $key . ". " . $value[0] . "</option>";
            }
        }
        $strListBoxes .= "
            </select>

            </td><td>

            <select size='1' name='select_domain' style='font-size: 10px; width: 120px;' onchange='" . JSCookie("index_select_domain", "this.value") . "'>
                <option value='0' selected>Domain: auto</option>
                <option value='AR'>AR</option>
                <option value='BG'>BG</option>
                <option value='BR'>BR</option>
                <option value='HU'>HU</option>
                <option value='DE'>DE</option>
                <option value='GR'>GR</option>
                <option value='DK'>DK</option>
                <option value='ES'>ES</option>
                <option value='IT'>IT</option>
                <option value='LV'>LV</option>
                <option value='LT'>LT</option>
                <option value='MX'>MX</option>
                <option value='NL'>NL</option>
                <option value='NO'>NO</option>
                <option value='ORG'>ORG</option>
                <option value='PL'>PL</option>
                <option value='PT'>PT</option>
                <option value='RU'>RU</option>
                <option value='RO'>RO</option>
                <option value='US'>US</option>
                <option value='SK'>SK</option>
                <option value='SI'>SI</option>
                <option value='TW'>TW</option>
                <option value='TR'>TR</option>
                <option value='FI'>FI</option>
                <option value='FR'>FR</option>
                <option value='CZ'>CZ</option>
                <option value='SE'>SE</option>
                <option value='JP'>JP</option>
            </select>

            </td></tr>
            <tr><td>

            <select size='1' name='select_skin' style='font-size: 10px; width: 120px;' onchange='" . JSCookie("index_select_skin", "this.value") . "'>
                <option value='logserver_v20' selected>Skin: LogServer v2</option>
                <option value='0'>Default</option>
                <option value='original'>Original</option>
                <option value='abstract'>Abstract</option>
                <option value='animex'>AnimeX</option>
                <option value='animex_2'>AnimeX 2</option>
                <option value='chaos'>Chaos</option>
                <option value='destroyer'>Destroyer</option>
                <option value='fallout'>Fallout</option>
                <option value='dead_space'>Dead Space</option>
                <option value='ntrvr'>?ntrvr[!]</option>
                <option value='disturbed'>Disturbed</option>
                <option value='staticx'>Static-X</option>
                <option value='system_shock'>System shock</option>
                <option value='bender'>Bender</option>
                <option value='oldalpha'>OldAlpha</option>
            </select>

            </td><td id='music'>

            <select style='display: none' size='1' name='lang' style='font-size: 10px; width: 120px;' onchange='" . JSCookie("index_lang", "this.value") . "'>
                <option value='0' selected>Language: auto</option>
                <option value='en'>English</option>
                <option value='bg'>Bulgarian</option>
                <option value='ru'>Russian</option>
                <option value='ua'>Ukrainian</option>
            </select>
            </td></tr></table>
            ";
        $strListPFuel = "
            <select size='1' name='select_p_fuel' style='font-size: 10px; width: 50px;'>
                <option value='1'>100%</option>
                <option value='0.9'>90%</option>
                <option value='0.8'>80%</option>
                <option value='0.7'>70%</option>
                <option value='0.6'>60%</option>
                <option value='0.5'>50%</option>
                <option value='0.4'>40%</option>
                <option value='0.3'>30%</option>
                <option value='0.2'>20%</option>
                <option value='0.1'>10%</option>
                </select>";
        $strTableInner = "
            <tr>
                <td align='left'>
                    $strHelpDiv1
                    $strHelpDiv2
                    $strHelpDiv3
                    $strHelpDiv4
                    $strHelpDiv5
                    $strHelpDiv6
                    $strTextAreas
                </td>
            </tr>
            <tr>
                <td align='left'>
                    <table border='0' style='border-collapse: collapse' width='754'>
                        <tr>
                            <td align='left' valign='top'>
                                $strCheckBoxes
                            </td>
                            <td width='10'></td>
                            <td align='right' valign='top'>
                                <div id='exsettings' style='display: block;'>
                                    <table border='0' style='border-collapse: collapse'>
                                        <tr>
                                            <td><input type='checkbox' class='js-checkbox' name='cbx_ipm' value='ON' onchange='(this.checked) ? (document.getElementById(\"text_ipm\").disabled = false) : (document.getElementById(\"text_ipm\").disabled = true)'><font color='" . WHITE_DARK . "' face='Arial' size='2'> " . Dictionary("calc_ipms") . ": </font></td>
                                            <td><input disabled type='text' id='text_ipm' name='text_ipm' size='20' value='' style='width: 50px; font-size: 12px; font-family: Arial; color:" . BLACK . "; background-color:" . WHITE_LIGHT . "; border: 1px solid " . WHITE_DARK . ";'></td>
                                            <td rowspan='4' width='10'></td>
                                            <td rowspan='4'>$strListBoxes</td>
                                        <tr>
                                        </tr>
                                            <td><input type='checkbox' class='js-checkbox' name='cbx_fuel' value='ON' $strCbxFuel onchange='" . JSCookie("index_cbx_fuel", "\"+this.checked+\"") . ";'><font color='" . WHITE_DARK . "' face='Arial' size='2'> " . Dictionary("calc_deut_cons") . "</font></td>
                                            <td><font color='" . WHITE_DARK . "' face='Arial' size='2'>" . $strListPFuel . "</font></td>
                                        <tr>
                                        </tr>
                                            <td style='padding-left: 25px;'><font color='" . WHITE_DARK . "' face='Arial' size='2'>Реакт. двигатель: </font></td>
                                            <td><input disabled type='text' onchange='" . JSCookie("index_inp_combustion", "\"+this.value+\"") . "' name='lvl_comb' size='20' value='" . $strinpCombustion . "' style='width: 50px; font-size: 12px; font-family: Arial; color:" . BLACK . "; background-color:" . WHITE_LIGHT . "; border: 1px solid " . WHITE_DARK . ";'></td>
                                        <tr>
                                        </tr>
                                            <td style='padding-left: 25px;'><font color='" . WHITE_DARK . "' face='Arial' size='2'>Имп. двигатель:</font></td>
                                            <td><input disabled type='text' onchange='" . JSCookie("index_inp_impulse", "\"+this.value+\"") . "' name='lvl_imp' size='20' value='" . $strinpImpulse . "' style='width: 50px; font-size: 12px; font-family: Arial; color:" . BLACK . "; background-color:" . WHITE_LIGHT . "; border: 1px solid " . WHITE_DARK . ";'></td>
                                        <tr>
                                        </tr>
                                            <td style='padding-left: 25px;'><font color='" . WHITE_DARK . "' face='Arial' size='2'>Гипер. двигатель: </font></td>
                                            <td><input disabled type='text' onchange='" . JSCookie("index_inp_hyperspace", "\"+this.value+\"") . "' name='lvl_hyp' size='20' value='" . $strinpHyperspace . "' style='width: 50px; font-size: 12px; font-family: Arial; color:" . BLACK . "; background-color:" . WHITE_LIGHT . "; border: 1px solid " . WHITE_DARK . ";'></td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <noscript>
            <tr>
                <td align='center'>
                    <font face='Arial' color='#FF3300' size='2'>" . Dictionary("noscript") . "</font>
                </td>
            </tr>
            </noscript>
            <tr>
                <td id='submit_td' align='center' height='30' background='".VISTA_PANEL."' style='padding: 0'>
                    <table border='0' bordercolor='#000000' style='border-collapse: collapse' cellpadding='0'>
                        <tr>
                            <td align='left' valign='center' width='40' height='30' background='".VISTA_UPLOAD_NORMAL."' onmouseover='this.setAttribute(\"background\", \"".VISTA_UPLOAD_ACTIVE."\")' onmouseout='this.setAttribute(\"background\", \"".VISTA_UPLOAD_NORMAL."\")' onclick='JS_Submit()'>
                            </td>
                            <noscript>
                            <td>
                                <input type='submit' value='&#9668; Submit &#9658;' style='width: 120; height: 24; color: #000000; border: 1px solid #000000; background-image: url(".SUBMIT_BLUE_DARK.")'>
                            </td>
                            </noscript>
                        </tr>
                    </table>
                </td>
            </tr>";

        ShowHTML($strTableInner, SERVER_NAME, false);
    }


    function ShowUploadForm() {
        global $NameUni;
        $selected_uni = false;
        $selected_skin = false;
        $objMobile = is_mobile();
        $objDlgWnd = new cDlgWnd();

        $strHelpDiv1 = $objDlgWnd->CreateDlgHTML("help_1", Dictionary("help"),
            Dictionary("for_uploading_title") . ":<br><br>
            1. " . Dictionary("for_uploading_step_1") . "<br>
            2. " . Dictionary("for_uploading_step_2") . "<br>
            3. " . Dictionary("for_uploading_step_3"));

        $strHelpDiv2 = $objDlgWnd->CreateDlgHTML("help_2", Dictionary("help"), "
                            " . Dictionary("hr_field") . "<br><br>
                            " . Dictionary("sample") . " 1:<br>
                            <font size='1'>" . "Переработчики в количестве X штук обладают общей грузоподъёмностью в X. Поле обломков содержит X металла и X кристалла. Добыто X металла и X кристалла." . "</font>
                            <br><br>
                            " . Dictionary("sample") . " 2:<br>
                            <font size='1'>" . "Поле обломков содержит X металла и X кристалла. Добыто X металла и X кристалла." . "</font>
                            <br><br>
                            <font color='" . RED_COMMON . "'>" . Dictionary("leave_astx") . "</font>
                            ");

        $strHelpDiv3 = $objDlgWnd->CreateDlgHTML("help_3", Dictionary("help"), "
                            " . Dictionary("cleanup_field") . "
                            <br><br>
                            " . Dictionary("sample") . ":
                            <br>
                            <font size='1'>" . "The attacker has won the battle! He captured: X metal, X crystal and X deuterium." . "</font>
                            ");

        $strHelpDiv4 = $objDlgWnd->CreateDlgHTML("help_4", Dictionary("help"), "
                            " . Dictionary("comment_field") . "
                            ");

        $strHelpDiv5 = $objDlgWnd->CreateDlgHTML("help_5", Dictionary("warning"), "
                            <font color='" . RED_COMMON . "'>" . Dictionary("warning_1") . "</font>
                            <br><br>
                            " . Dictionary("warning_2") . "
                            <br>
                            " . Dictionary("warning_3") . "
                            ");

        $strHelpDiv6 = $objDlgWnd->CreateDlgHTML("help_6", Dictionary("warning"), "
                            <font color='" . RED_COMMON . "'>Wrong Combat report (HTML-code)!</font>
                            ");
                            
        $strLogTextarea = "
            <table border='0' style='border-collapse: collapse'>
                <tr>
                    <td colspan='3'>
                        <input type='radio' class='js-checkbox' id='b_spy_report_v1' name='rb_spy_report' value='V1' checked><font face='Arial' color='" . WHITE_DARK . "' size='2'>" . Dictionary("html_report") . "</font>
                        <input type='radio' class='js-checkbox' id='b_spy_report_v2' name='rb_spy_report' value='V2'><font color='" . WHITE_DARK . "' face='Arial' size='2'>" . Dictionary("spy_report") . "</font>
                        <input type='hidden' name='plugin' value=''>
                        <input type='hidden' name='plugin_user_key' value=''>
                        <div class='log_textarea_result'><br></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input id='log_textarea' name='log_textarea' onfocus=\"if(this.value=='cr-xx-xxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx' || this.value=='sr-xx-xxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx') this.value='';\" onblur=\"if(this.value.search(/cr-/)!=-1 && this.value!='cr-xx-xxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx') {ajaxLogTextarea(this.value); document.getElementById('b_spy_report_v1').checked = true;} if(this.value.search(/sr-/)!=-1 && this.value!='sr-xx-xxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx') {ajaxLogTextarea(this.value); document.getElementById('b_spy_report_v2').checked = true;}\" value='cr-xx-xxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx' style='width:925px; text-align:center; border-radius: 10px 10px 0 10px; font-size: 10px; font-family: Arial; color:" . WHITE_DARK . "; background-color:#000000; border-style:solid; border: 1px solid " . WHITE_DARK . ";' onmouseover='this.style.border=\"1px solid " . AQUA_COMMON . "\"'; onmouseout='this.style.border=\"1px solid " . WHITE_DARK . "\"'>
                        <input id='protect' name='protect' type='hidden'>
                    </td>
                    <td width='2'></td>
                    <td>
                        <img src='" . ICON_QUESTION_P . "' alt='?' onmouseover='this.src=\"" . ICON_QUESTION_A . "\";' onmouseout='this.src=\"" . ICON_QUESTION_P . "\";' onclick='ShowHide(\"help_1\");'>
                    </td>
            </table>
        ";
        if (isset($_COOKIE["cbx_recycler"]) && $_COOKIE["cbx_recycler"] == "true") {
            $strCbxRecycler = "checked='checked'"; 
            $strinpRecDisplay = "none"; 
        } 
        else {
            $strCbxRecycler = ""; 
            $strinpRecDisplay = "";
        }
        $strRecyclerTextarea = "
            <table id='table_recycler' border='0' style='border-collapse: collapse'>
                <tr>
                    <td colspan='3'>
                        <input type='radio' name='rb_rec_report' value='V1' checked><font face='Arial' color='" . WHITE_DARK . "' size='2'>" . Dictionary("com_scpatk") . "</font>
                        <input type='radio' name='rb_rec_report' value='V2'><font color='" . WHITE_DARK . "' face='Arial' size='2'>" . Dictionary("com_scpdef") . "</font>
                        <input type='checkbox' id='cbx_recycler' name='cbx_recycler' value='ON' " . $strCbxRecycler . " onClick='if(!this.checked){document.getElementById(\"inputs_recycler\").style.display=\"\"} else {document.getElementById(\"inputs_recycler\").style.display=\"none\"};' onchange='" . JSCookie("cbx_recycler", "\"+this.checked+\"") . "'><font color='" . WHITE_DARK . "' face='Arial' size='2'>Всё поле обломков переработано</font>
                    </td>
                </tr>
                <tr id='inputs_recycler' style='display:" . $strinpRecDisplay . "'>
                    <td style='width:625px; valign:top;'>
                        <input class='recycler_input_1' name='recycler_textarea[]' onfocus=\"if(this.value=='rr-xx-xxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx') this.value='';\" onblur=\"if (this.value=='') this.value='rr-xx-xxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'; if(this.value && this.value!='rr-xx-xxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx') ajaxRecycler(this.value, this.className);\" value='rr-xx-xxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx' style='width:525px; text-align:center; border-radius: 10px 10px 0 10px; font-size: 10px; font-family: Arial; color:" . WHITE_DARK . "; background-color:#000000; border-style:solid; border: 1px solid " . WHITE_DARK . ";' onmouseover='this.style.border=\"1px solid " . AQUA_COMMON . "\"'; onmouseout='this.style.border=\"1px solid " . WHITE_DARK . "\"'>
                        <img src='" . ICON_ADD . "' class='recycler_add_1 recycler_add' alt='+' onclick='AddRecyclerInput(this.className); this.style.display=\"none\"'>
                    </td>
                    <td class='recycler_ajax_1' style='width:300px; valign:top;'></td>
                    <td>
                        <img src='" . ICON_QUESTION_P . "' alt='?' onmouseover='this.src=\"" . ICON_QUESTION_A . "\";' onmouseout='this.src=\"" . ICON_QUESTION_P . "\";' onclick='ShowHide(\"help_2\");'>
                    </td>
                </tr>
            </table>";

        $strCleanupTextarea = "
            <table id='table_clean_up' border='0' style='border-collapse: collapse'>
                <tr>
                    <td colspan='3'>
                        <font face='Arial' color='" . WHITE_DARK . "' size='2'>" . Dictionary("clean_up") . "</font><br>
                    </td>
                </tr>
                <tr>
                    <td style='width:625px; valign:top;'>
                        <input class='clean_up_input_1' name='clean_up_textarea[]' onfocus=\"if(this.value=='cr-xx-xxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx') this.value='';\" onblur=\"if (this.value=='') this.value='cr-xx-xxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'; if(this.value && this.value!='cr-xx-xxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx') ajaxCleanUp(this.value, this.className);\" value='cr-xx-xxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx' style='width:525px; text-align:center; border-radius: 10px 10px 0 10px; font-size: 10px; font-family: Arial; color:" . WHITE_DARK . "; background-color:#000000; border-style:solid; border: 1px solid " . WHITE_DARK . ";' onmouseover='this.style.border=\"1px solid " . AQUA_COMMON . "\"'; onmouseout='this.style.border=\"1px solid " . WHITE_DARK . "\"'>
                        <img src='" . ICON_ADD . "' class='clean_up_add_1 clean_up_add' alt='+' onclick='AddCleanUpInput(this.className); this.style.display=\"none\"'>
                    </td>
                    <td class='clean_up_ajax_1' style='width:300px; valign:top;'></td>
                    <td>
                        <img src='" . ICON_QUESTION_P . "' alt='?' onmouseover='this.src=\"" . ICON_QUESTION_A . "\";' onmouseout='this.src=\"" . ICON_QUESTION_P . "\";' onclick='ShowHide(\"help_3\");'>
                    </td>
                </tr>
            </table>";

        $strTdTagBB = "align='center' valign='center' height='28' style='padding-left: 2; padding-right: 2;' onmouseover='this.setAttribute(\"background\", \"" . VISTA_PANEL_A_BB_CODE . "\");' onmouseout='this.setAttribute(\"background\", \"\");'";
        $strCommentTextarea = "
            <table border='0' style='border-collapse: collapse'>
                <tr>
                    <td colspan='3'>
                        <font face='Arial' color='" . WHITE_DARK . "' size='2'>" . Dictionary("comment") . "</font>"."<br>
                    </td>
                </tr>
                <tr id='BBCode' style='display: none;'>
                    <td height='28' background='" . VISTA_PANEL_BB_CODE . "' style='padding: 0'>
                        <table border='0' width='1' style='border-collapse: collapse' cellpadding='0'>
                            <tr>
                                <td ".$strTdTagBB." name='btnBold' title='Bold' onClick='doAddTags(\"[b]\",\"[/b]\",\"comment_textarea\")'>
                                    <table border='0' style='border-collapse: collapse' cellpadding='0'><tr><td><img src='" . STYLE_BOLD_BBCODE . "' border='0'></td></tr></table>
                                </td>
                                <td width='2'></td>
                                <td ".$strTdTagBB." name='btnItalic' title='Italic' onClick='doAddTags(\"[i]\",\"[/i]\",\"comment_textarea\")'>
                                    <table border='0' style='border-collapse: collapse' cellpadding='0'><tr><td><img src='" . STYLE_ITALIC_BBCODE . "' border='0'></td></tr></table>
                                </td>
                                <td width='2'></td>
                                <td ".$strTdTagBB." name='btnUnderline' title='Underline' onClick='doAddTags(\"[u]\",\"[/u]\",\"comment_textarea\")'>
                                    <table border='0' style='border-collapse: collapse' cellpadding='0'><tr><td><img src='" . STYLE_UNDERLINE_BBCODE . "' border='0'></td></tr></table>
                                </td>
                                <td width='2'></td>
                                <td ".$strTdTagBB."  name='btnStrikethrough' title='Strikethrough' onClick='doAddTags(\"[s]\",\"[/s]\",\"comment_textarea\")'>
                                    <table border='0' style='border-collapse: collapse' cellpadding='0'><tr><td><img src='" . STYLE_STRIKETROUGH_BBCODE . "' border='0'></td></tr></table>
                                </td>
                                <td width='2'></td>
                                <td>
                                    <table border='0' style='border-collapse: collapse' cellpadding='0'><tr><td><img src='" . SEPARATOR_BBCODE . "' border='0'></td></tr></table>
                                </td>
                                <td width='2'></td>
                                <td ".$strTdTagBB." name='btnAlingLeft' title='Align left' onClick='doAddTags(\"[left]\",\"[/left]\",\"comment_textarea\")'>
                                    <table border='0' style='border-collapse: collapse' cellpadding='0'><tr><td><img src='" . ALIGN_LEFT_BBCODE . "' border='0'></td></tr></table>
                                </td>
                                <td width='2'></td>
                                <td ".$strTdTagBB." name='btnAlingCenter' title='Align center' onClick='doAddTags(\"[center]\",\"[/center]\",\"comment_textarea\")'>
                                    <table border='0' style='border-collapse: collapse' cellpadding='0'><tr><td><img src='" . ALIGN_CENTER_BBCODE . "' border='0'></td></tr></table>
                                </td>
                                <td width='2'></td>
                                <td ".$strTdTagBB." name='btnAlingRight' title='Align right' onClick='doAddTags(\"[right]\",\"[/right]\",\"comment_textarea\")'>
                                    <table border='0' style='border-collapse: collapse' cellpadding='0'><tr><td><img src='" . ALIGN_RIGHT_BBCODE . "' border='0'></td></tr></table>
                                </td>
                                <td width='2'></td>
                                <td ".$strTdTagBB." name='btnAlingJustify' title='Align justify' onClick='doAddTags(\"[justify]\",\"[/justify]\",\"comment_textarea\")'>
                                    <table border='0' style='border-collapse: collapse' cellpadding='0'><tr><td><img src='" . JUSTIFY_BBCODE . "' border='0'></td></tr></table>
                                </td>
                                <td width='2'></td>
                                <td>
                                    <table border='0' style='border-collapse: collapse' cellpadding='0'><tr><td><img src='" . SEPARATOR_BBCODE . "' border='0'></td></tr></table>
                                </td>
                                <td width='2'></td>
                                <td ".$strTdTagBB." name='btnList' title='Unordered List' onClick='doList(\"[LIST]\",\"[/LIST]\",\"comment_textarea\")'>
                                    <table border='0' style='border-collapse: collapse' cellpadding='0'><tr><td><img src='" . STYLE_UNORDERED_BBCODE . "' border='0'></td></tr></table>
                                </td>
                                <td width='2'></td>
                                <td ".$strTdTagBB."  name='btnList' title='Ordered List' onClick='doList(\"[LIST=1]\",\"[/LIST]\",\"comment_textarea\")'>
                                    <table border='0' style='border-collapse: collapse' cellpadding='0'><tr><td><img src='" . STYLE_ORDERED_BBCODE . "' border='0'></td></tr></table>
                                </td>
                                <td width='2'></td>
                                <td>
                                    <table border='0' style='border-collapse: collapse' cellpadding='0'><tr><td><img src='" . SEPARATOR_BBCODE . "' border='0'></td></tr></table>
                                </td>
                                <td width='2'></td>
                                <td ".$strTdTagBB." name='btnLink' title='Insert URL Link' onClick='doURL(\"comment_textarea\")'>
                                    <table border='0' style='border-collapse: collapse' cellpadding='0'><tr><td ><img src='" . LINK_BBCODE . "' border='0'></td></tr></table>
                                </td>
                                <td width='2'></td>
                                <td>
                                    <table border='0' style='border-collapse: collapse' cellpadding='0'><tr><td><img src='" . SEPARATOR_BBCODE . "' border='0'></td></tr></table>
                                </td>
                                <td width='2'></td>
                                <td ".$strTdTagBB." name='btnPicture' title='Insert Image' onClick='doImage(\"comment_textarea\")'>
                                    <table border='0' style='border-collapse: collapse' cellpadding='0'><tr><td><img src='" . INSERT_IMAGE_BBCODE . "' border='0'></td></tr></table>
                                </td>
                                <td width='2'></td>
                                <td>
                                    <table border='0' style='border-collapse: collapse' cellpadding='0'><tr><td><img src='" . SEPARATOR_BBCODE . "' border='0'></td></tr></table>
                                </td>
                                <td width='2'></td>
                                <td ".$strTdTagBB." name='btnQuote' title='Quote' onClick='doAddTags(\"[quote]\",\"[/quote]\",\"comment_textarea\")'>
                                    <table border='0' style='border-collapse: collapse' cellpadding='0'><tr><td><img src='" . QUOTE_BBCODE . "' border='0'></td></tr></table>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td width='2'></td>
                    <td></td>
                </tr>
                <tr>
                    <td>
                        <textarea rows='2' cols='178' name='comment_textarea' id='comment_textarea' style='width:925px; border-radius: 10px 10px 0 10px; font-size: 10px; font-family: Arial; color:" . WHITE_DARK . "; background-color:#000000; border-style:solid; border: 1px solid " . WHITE_DARK . ";' onclick='this.setAttribute(\"rows\", 4); document.getElementById(\"BBCode\").style.display=\"\"' onmouseover='this.style.border=\"1px solid " . AQUA_COMMON . "\"'; onmouseout='this.style.border=\"1px solid " . WHITE_DARK . "\"'></textarea>
                    </td>
                    <td width='2'></td>
                    <td>
                        <img src='" . ICON_QUESTION_P . "' alt='?' onmouseover='this.src=\"" . ICON_QUESTION_A . "\";' onmouseout='this.src=\"" . ICON_QUESTION_P . "\";' onclick='ShowHide(\"help_4\");'>
                    </td>
                </tr>
            </table>";

        $strTextAreas = "
            $strLogTextarea
            $strRecyclerTextarea
            $strCleanupTextarea
            $strCommentTextarea";

        $strUITableX = "
            <table border='1' style='border-collapse: collapse' width='100%'>
                <tr>
                    <td>
                        <img src='" . ICON_QUESTION_P . "' alt='Help'>
                    </td>
                    <td>
                        <a href='javascript:JS_ShowUsageInstruction()' onclick='aaa()' id='link_UI' title='" . Dictionary("for_uploading_title") . ": 1) " . Dictionary("for_uploading_step_1") . "; 2. " . Dictionary("for_uploading_step_2") . "; (3) " . Dictionary("for_uploading_step_3") . "'>Ў</a>
                    </td>
                </tr>
            </table>";

        (isset($_COOKIE["index_cbx_public"]) && $_COOKIE["index_cbx_public"] == "false") ? ($strCbxPublic = "") : ($strCbxPublic = "checked");
        (isset($_COOKIE["index_cbx_hide_coord"]) && $_COOKIE["index_cbx_hide_coord"] == "false") ? ($strCbxHideCoord = "") : ($strCbxHideCoord = "checked");
        (isset($_COOKIE["index_cbx_hide_tech"]) && $_COOKIE["index_cbx_hide_tech"] == "false") ? ($strCbxHideTech = "") : ($strCbxHideTech = "checked");
        (isset($_COOKIE["index_cbx_hide_time"]) && $_COOKIE["index_cbx_hide_time"] == "false") ? ($strCbxHideTime = "") : ($strCbxHideTime = "checked");
        (isset($_COOKIE["index_cbx_comments"]) && $_COOKIE["index_cbx_comments"] == "true") ? ($strCbxComments = "checked") : ($strCbxComments = "");
        (isset($_COOKIE["index_cbx_fuel"]) && $_COOKIE["index_cbx_fuel"] == "false") ? ($strCbxFuel = "") : ($strCbxFuel = "checked");
        (isset($_COOKIE["index_cbx_aliance"]) && $_COOKIE["index_cbx_aliance"] == "false") ? ($strCbxAliance = "") : ($strCbxAliance = "checked");
        (isset($_COOKIE["index_cbx_top"]) && $_COOKIE["index_cbx_top"] == "true") ? ($strCbxTOP = "checked") : ($strCbxTOP = "");
        (isset($_COOKIE["index_inp_combustion"])) ? ($strinpCombustion = $_COOKIE["index_inp_combustion"]) : ($strinpCombustion = 0);
        (isset($_COOKIE["index_inp_impulse"])) ? ($strinpImpulse = $_COOKIE["index_inp_impulse"]) : ($strinpImpulse = 0);
        (isset($_COOKIE["index_inp_hyperspace"])) ? ($strinpHyperspace = $_COOKIE["index_inp_hyperspace"]) : ($strinpHyperspace = 0);

        $strCheckBoxes = "
            <input type='checkbox' class='js-checkbox' name='cbx_public' value='ON' $strCbxPublic onchange='" . JSCookie("index_cbx_public", "\"+this.checked+\"") . "'><font color='" . WHITE_DARK . "' face='Arial' size='2'> " . Dictionary("public_log") . "</font>"." "."<a href='index.php?show=public' style='text-decoration: none'>&#9658;</a>
            <br>
            <input type='checkbox' class='js-checkbox' name='cbx_hide_coord' value='ON' $strCbxHideCoord onchange='" . JSCookie("index_cbx_hide_coord", "\"+this.checked+\"") . "'><font color='" . WHITE_DARK . "' face='Arial' size='2'> " . Dictionary("hide_coords") . "</font>
            <br>
            <input type='checkbox' class='js-checkbox' name='cbx_hide_tech' value='ON' $strCbxHideTech onchange='" . JSCookie("index_cbx_hide_tech", "\"+this.checked+\"") . "'><font color='" . WHITE_DARK . "' face='Arial' size='2'> " . Dictionary("hide_techs") . "</font>
            <br>
            <input type='checkbox' class='js-checkbox' name='cbx_hide_time' value='ON' $strCbxHideTime onchange='" . JSCookie("index_cbx_hide_time", "\"+this.checked+\"") . "'><font color='" . WHITE_DARK . "' face='Arial' size='2'> " . Dictionary("hide_time") . "</font>
            <br>
            <input type='checkbox' class='js-checkbox' name='cbx_comments' value='ON' $strCbxComments onchange='" . JSCookie("index_cbx_comments", "\"+this.checked+\"") . "'><font color='" . WHITE_DARK . "' face='Arial' size='2'> " . Dictionary("comments") . "</font>
            <br>
            <input type=hidden name='submited' value='1'>";

        $strTableInner = "";
        $strListBoxes = "
            <table border='0' style='border-collapse: collapse' cellpadding='2'><tr><td>
            <select size='1' name='select_uni' style='font-size: 10px; width: 120px;' onchange='" . JSCookie("index_select_uni", "this.selectedIndex") . "'>";

        foreach ($NameUni as $key => $value) {
            if ($value[1] != "?") {
                if (gettype($value[0]) == "integer") $value[0] = "Universe";
                $selectedUni = (isset($_COOKIE["index_select_uni"]) && $_COOKIE["index_select_uni"] == $key) ? " selected" : false;
                $strListBoxes .= "<option value='" . $key . "'" . $selectedUni . ">" . $key . ". " . $value[0] . "</option>";
            }
        }
        $strListBoxes .= "
            </select>

            </td><td>

            <select size='1' name='select_domain' style='font-size: 10px; width: 120px;' onchange='" . JSCookie("index_select_domain", "this.value") . "'>
                <option value='0' selected>Domain: auto</option>
                <option value='AR'>AR</option>
                <option value='BG'>BG</option>
                <option value='BR'>BR</option>
                <option value='HU'>HU</option>
                <option value='DE'>DE</option>
                <option value='GR'>GR</option>
                <option value='DK'>DK</option>
                <option value='ES'>ES</option>
                <option value='IT'>IT</option>
                <option value='LV'>LV</option>
                <option value='LT'>LT</option>
                <option value='MX'>MX</option>
                <option value='NL'>NL</option>
                <option value='NO'>NO</option>
                <option value='ORG'>ORG</option>
                <option value='PL'>PL</option>
                <option value='PT'>PT</option>
                <option value='RU'>RU</option>
                <option value='RO'>RO</option>
                <option value='US'>US</option>
                <option value='SK'>SK</option>
                <option value='SI'>SI</option>
                <option value='TW'>TW</option>
                <option value='TR'>TR</option>
                <option value='FI'>FI</option>
                <option value='FR'>FR</option>
                <option value='CZ'>CZ</option>
                <option value='SE'>SE</option>
                <option value='JP'>JP</option>
            </select>

            </td></tr>
            <tr><td>";
            if (isset($_COOKIE["index_select_skin"])) {
                if($_COOKIE["index_select_skin"]=='logserver_v20')  $selected_skin[0] = 'selected'; else $selected_skin[0] = ''; 
                if($_COOKIE["index_select_skin"]=='0')              $selected_skin[1] = 'selected'; else $selected_skin[1] = '';
                if($_COOKIE["index_select_skin"]=='original')       $selected_skin[2] = 'selected'; else $selected_skin[2] = '';
                if($_COOKIE["index_select_skin"]=='abstract')       $selected_skin[3] = 'selected'; else $selected_skin[3] = '';
                if($_COOKIE["index_select_skin"]=='animex')         $selected_skin[4] = 'selected'; else $selected_skin[4] = '';
                if($_COOKIE["index_select_skin"]=='animex_2')       $selected_skin[5] = 'selected'; else $selected_skin[5] = '';
                if($_COOKIE["index_select_skin"]=='chaos')          $selected_skin[6] = 'selected'; else $selected_skin[6] = '';
                if($_COOKIE["index_select_skin"]=='destroyer')      $selected_skin[7] = 'selected'; else $selected_skin[7] = '';
                if($_COOKIE["index_select_skin"]=='fallout')        $selected_skin[8] = 'selected'; else $selected_skin[8] = '';
                if($_COOKIE["index_select_skin"]=='dead_space')     $selected_skin[9] = 'selected'; else $selected_skin[9] = '';
                if($_COOKIE["index_select_skin"]=='ntrvr')          $selected_skin[10] = 'selected'; else $selected_skin[10] = '';
                if($_COOKIE["index_select_skin"]=='disturbed')      $selected_skin[11] = 'selected'; else $selected_skin[11] = '';
                if($_COOKIE["index_select_skin"]=='staticx')        $selected_skin[12] = 'selected'; else $selected_skin[12] = '';
                if($_COOKIE["index_select_skin"]=='system_shock')   $selected_skin[13] = 'selected'; else $selected_skin[13] = '';
                if($_COOKIE["index_select_skin"]=='bender')         $selected_skin[14] = 'selected'; else $selected_skin[14] = '';
                if($_COOKIE["index_select_skin"]=='oldalpha')       $selected_skin[15] = 'selected'; else $selected_skin[15] = '';
            }

            $strListBoxes .= "
            <select size='1' name='select_skin' style='font-size: 10px; width: 120px;' onchange='" . JSCookie("index_select_skin", "\"+this.value+\"") . "'>
                <option value='logserver_v20' $selected_skin[0]>Skin: LogServer v2</option>
                <option value='0' $selected_skin[1]>Default</option>
                <option value='original' $selected_skin[2]>Original</option>
                <option value='abstract' $selected_skin[3]>Abstract</option>
                <option value='animex' $selected_skin[4]>AnimeX</option>
                <option value='animex_2' $selected_skin[5]>AnimeX 2</option>
                <option value='chaos' $selected_skin[6]>Chaos</option>
                <option value='destroyer' $selected_skin[7]>Destroyer</option>
                <option value='fallout' $selected_skin[8]>Fallout</option>
                <option value='dead_space' $selected_skin[9]>Dead Space</option>
                <option value='ntrvr' $selected_skin[10]>?ntrvr[!]</option>
                <option value='disturbed' $selected_skin[11]>Disturbed</option>
                <option value='staticx' $selected_skin[12]>Static-X</option>
                <option value='system_shock' $selected_skin[13]>System shock</option>
                <option value='bender' $selected_skin[14]>Bender</option>
                <option value='oldalpha' $selected_skin[15]>OldAlpha</option>
            </select>

            </td><td id='music'>

            <select style='display: none' size='1' name='lang' style='font-size: 10px; width: 120px;' onchange='" . JSCookie("index_lang", "this.value") . "'>
                <option value='0' selected>Language: auto</option>
                <option value='en'>English</option>
                <option value='bg'>Bulgarian</option>
                <option value='ru'>Russian</option>
                <option value='ua'>Ukrainian</option>
            </select>
            </td></tr></table>
            ";

        $strListPFuel = "<select size='1' name='select_p_fuel' style='font-size: 10px; width: 50px'>
                <option value='1'>100%</option>
                <option value='0.9'>90%</option>
                <option value='0.8'>80%</option>
                <option value='0.7'>70%</option>
                <option value='0.6'>60%</option>
                <option value='0.5'>50%</option>
                <option value='0.4'>40%</option>
                <option value='0.3'>30%</option>
                <option value='0.2'>20%</option>
                <option value='0.1'>10%</option>
                </select>";

        $strTableInner = "
            <tr>
                <td align='left'>
                    $strHelpDiv1
                    $strHelpDiv2
                    $strHelpDiv3
                    $strHelpDiv4
                    $strHelpDiv5
                    $strHelpDiv6
                    $strTextAreas
                </td>
            </tr>
            <tr>
                <td align='left'>
                    <table border='0' style='border-collapse: collapse' width='754'>
                        <tr>
                            <td align='left' valign='top'>
                                $strCheckBoxes
                            </td>
                            <td width='10'></td>
                            <td align='right' valign='top'>
                                <div id='exsettings' style='display: block;'>
                                    <table border='0' style='border-collapse: collapse'>
                                        <tr>
                                            <td><input type='checkbox' class='js-checkbox' name='cbx_ipm' value='ON' onchange='(this.checked) ? (document.getElementById(\"text_ipm\").disabled = false) : (document.getElementById(\"text_ipm\").disabled = true)'><font color='" . WHITE_DARK . "' face='Arial' size='2'> " . Dictionary("calc_ipms") . ": </font></td>
                                            <td><input disabled type='text' id='text_ipm' name='text_ipm' size='20' value='' style='width: 50px; font-size: 12px; font-family: Arial; color:" . BLACK . "; background-color:" . WHITE_LIGHT . "; border: 1px solid " . WHITE_DARK . ";'></td>
                                            <td rowspan='4' width='10'></td>
                                            <td rowspan='4'>$strListBoxes</td>
                                        <tr>
                                        </tr>
                                            <td><input type='checkbox' class='js-checkbox' name='cbx_fuel' value='ON' $strCbxFuel onchange='" . JSCookie("index_cbx_fuel", "\"+this.checked+\"") . ";'><font color='" . WHITE_DARK . "' face='Arial' size='2'> " . Dictionary("calc_deut_cons") . "</font></td>
                                            <td><font color='" . WHITE_DARK . "' face='Arial' size='2'>" . $strListPFuel . "</font></td>
                                        <tr>
                                        </tr>
                                            <td style='display:none; padding-left: 25px;'><font color='" . WHITE_DARK . "' face='Arial' size='2'>Реакт. двигатель: </font></td>
                                            <td style='display:none;'><input disabled type='text' onchange='" . JSCookie("index_inp_combustion", "\"+this.value+\"") . "' name='lvl_comb' size='20' value='" . $strinpCombustion . "' style='width: 50px; font-size: 12px; font-family: Arial; color:" . BLACK . "; background-color:" . WHITE_LIGHT . "; border: 1px solid " . WHITE_DARK . ";'></td>
                                        <tr>
                                        </tr>
                                            <td style='display:none; padding-left: 25px;'><font color='" . WHITE_DARK . "' face='Arial' size='2'>Имп. двигатель:</font></td>
                                            <td style='display:none;'><input disabled type='text' onchange='" . JSCookie("index_inp_impulse", "\"+this.value+\"") . "' name='lvl_imp' size='20' value='" . $strinpImpulse . "' style='width: 50px; font-size: 12px; font-family: Arial; color:" . BLACK . "; background-color:" . WHITE_LIGHT . "; border: 1px solid " . WHITE_DARK . ";'></td>
                                        <tr>
                                        </tr>
                                            <td style='display:none; padding-left: 25px;'><font color='" . WHITE_DARK . "' face='Arial' size='2'>Гипер. двигатель: </font></td>
                                            <td style='display:none;'><input disabled type='text' onchange='" . JSCookie("index_inp_hyperspace", "\"+this.value+\"") . "' name='lvl_hyp' size='20' value='" . $strinpHyperspace . "' style='width: 50px; font-size: 12px; font-family: Arial; color:" . BLACK . "; background-color:" . WHITE_LIGHT . "; border: 1px solid " . WHITE_DARK . ";'></td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <noscript>
            <tr>
                <td align='center'>
                    <font face='Arial' color='#FF3300' size='2'>" . Dictionary("noscript") . "</font>
                </td>
            </tr>
            </noscript>
            <tr>
                <td id='submit_td' align='center' height='30' background='".VISTA_PANEL."' style='padding: 0'>
                    <table border='0' bordercolor='#000000' style='border-collapse: collapse' cellpadding='0'>
                        <tr>";
                        if ($objMobile == false)
                        $strTableInner .= "
                            <td align='left' class='submit1' valign='center' width='40' height='30' background='".VISTA_UPLOAD_NORMAL."' onmouseover='this.setAttribute(\"background\", \"".VISTA_UPLOAD_ACTIVE."\")' onmouseout='this.setAttribute(\"background\", \"".VISTA_UPLOAD_NORMAL."\")' onclick='JS_Submit()'>
                            </td>";
                        else 
                        $strTableInner .= "
                            <td>
                                <input type='submit' value='' style='width: 40; height: 30; color: #000000; border: 1px solid #000000; background-image: url(".VISTA_UPLOAD_NORMAL.")'>
                            </td>";

                        $strTableInner .= "
                            <noscript>
                            <td>
                                <input type='submit' value='&#9668; Submit &#9658;' style='width: 120; height: 24; color: #000000; border: 1px solid #000000; background-image: url(".SUBMIT_BLUE_DARK.")'>
                            </td>
                            </noscript>
                            <td>
                                <input type='submit' class='submit2' value='&#9668; Submit &#9658;' style='display: none; width: 120; height: 24; color: #000000; border: 1px solid #000000; background-image: url(".SUBMIT_BLUE_DARK.")'>
                            </td>                           
                        </tr>
                    </table>
                </td>
            </tr>";

        ShowHTML($strTableInner, SERVER_NAME, false);
    }

    function BotHtml() {
        $strTableInner = "<tr width='800'>";
        $strTableInner .= " <td align='center'>";
        $strTableInner .= "     <table width='800'>";
        $strTableInner .= "          <tr>";
        $strTableInner .= "             <td align='left'>";
        $strTableInner .= "                 <a href='index.php'><font style='text-decoration: none'>&#9668;</font>".""."<font face='Arial' color='" . WHITE_DARK . "' size='2'> " . Dictionary('return_to_main') . "</font></a>";
        $strTableInner .= "             </td>";
        $strTableInner .= "             <td align='right'>";
        $strTableInner .= "                 <a href='#body'><font style='text-decoration: none'>&#9650;</font>".""."<font face='Arial' color='" . WHITE_DARK . "' size='2'>" . Dictionary('page_up') . "</font></a>";
        $strTableInner .= "             </td>";
        $strTableInner .= "          </tr>";
        $strTableInner .= "     </table>";
        $strTableInner .= " </td>";
        $strTableInner .= "</tr>";

        return $strTableInner;
    }

    function ShowPublicForm() {
        global $NameUni;
        $selected_uni = false;      
        $strTableInner = "      <tr><td align='center'>
                                    <table><tr><td>
                                        <font class='h2'>" . Dictionary('public_1') . "</font>
                                    </td>
                                    <td width='4'></td>
                                    <td>
                                        <img src='" . "index_files/vista_panel/icon_public_b.png" . "' width='32'>
                                    </td>
                                    <td width='4'></td>
                                    <td>
                                        <font class='h2'>" . Dictionary('public_2') . "</font>
                                    </td></tr></table>
                                </td></tr>";
        $strDomainSelect = "    <select size='1' id='select_domain' style='font-size: 10px; width: 120px;' onchange='" . JSCookie("index_select_domain", "\"+this.selectedIndex+\"") . "'>
                                    <option value='0'>Domain: any</option>
                                    <option value='AR'>AR</option>
                                    <option value='BG'>BG</option>
                                    <option value='BR'>BR</option>
                                    <option value='HU'>HU</option>
                                    <option value='DE'>DE</option>
                                    <option value='GR'>GR</option>
                                    <option value='DK'>DK</option>
                                    <option value='ES'>ES</option>
                                    <option value='IT'>IT</option>
                                    <option value='LV'>LV</option>
                                    <option value='LT'>LT</option>
                                    <option value='MX'>MX</option>
                                    <option value='NL'>NL</option>
                                    <option value='NO'>NO</option>
                                    <option value='ORG'>ORG</option>
                                    <option value='PL'>PL</option>
                                    <option value='PT'>PT</option>
                                    <option value='RU' selected>RU</option>
                                    <option value='RO'>RO</option>
                                    <option value='US'>US</option>
                                    <option value='SK'>SK</option>
                                    <option value='SI'>SI</option>
                                    <option value='TW'>TW</option>
                                    <option value='TR'>TR</option>
                                    <option value='FI'>FI</option>
                                    <option value='FR'>FR</option>
                                    <option value='CZ'>CZ</option>
                                    <option value='SE'>SE</option>
                                    <option value='JP'>JP</option>
                                </select>";
        $strUniSelect = "       <select size='1' id='select_uni' style='font-size: 10px; width: 120px;' onchange='" . JSCookie("index_select_uni", "\"+this.value+\"") . "'>
                                    <option value='0'>Universe: any</option>";
        foreach ($NameUni as $key => $value) {
            if ($value[1] != "?") {
                if (gettype($value[0]) == "integer") $value[0] = "Universe";
                $selectedUni = (isset($_COOKIE["index_select_uni"]) && $_COOKIE["index_select_uni"] == $key) ? " selected" : false;
                $strUniSelect .= "<option value='" . $key . "'" . $selectedUni . ">" . $key . ". " . $value[0] . "</option>";
            }
        }

        $strUniSelect .= "      </select>";

        $strTableInner .= '<script type="text/javascript">
                              $(document).ready(function() {
                                  $("#popularlogs").load("h_ajax.php?page=popularlogs");
                                  $("#popularlogs").on("click", ".pagination a", function (e) {
                                      e.preventDefault();
                                      var page = $(this).attr("data-page");
                                      $("#popularlogs").load("h_ajax.php?page=popularlogs",{"n":page});
                                  });';

        if (isset($_SESSION['popularlogs']['a']) && $_SESSION['popularlogs']['a'] == "weekpop") $strTableInner .= '$("#weekpop").css("color", "#FF0000");';
        if (isset($_SESSION['popularlogs']['a']) && $_SESSION['popularlogs']['a'] == "yearpop") $strTableInner .= '$("#yearpop").css("color", "#FF0000");';
        if (isset($_SESSION['popularlogs']['a']) && $_SESSION['popularlogs']['a'] == "allpop") $strTableInner .= '$("#allpop").css("color", "#FF0000");';

        $strTableInner .= '$("#weekpop").on("click", $("#weekpop"), function (e) {
                                      e.preventDefault();
                                      var a = "weekpop";
                                      $("#weekpop").css("color", "#FF0000");
                                      $("#yearpop").css("color", "#09B");
                                      $("#allpop").css("color", "#09B");
                                      $("#popularlogs").load("h_ajax.php?page=popularlogs",{"a":a});
                                  });
                                  $("#yearpop").on("click", $("#yearpop"), function (e) {
                                      e.preventDefault();
                                      var a = "yearpop";
                                      $("#weekpop").css("color", "#09B");
                                      $("#yearpop").css("color", "#FF0000");
                                      $("#allpop").css("color", "#09B");
                                      $("#popularlogs").load("h_ajax.php?page=popularlogs",{"a":a});
                                  });
                                  $("#allpop").on("click", $("#allpop"), function (e) {
                                      e.preventDefault();
                                      var a = "allpop";
                                      $("#weekpop").css("color", "#09B");
                                      $("#yearpop").css("color", "#09B");
                                      $("#allpop").css("color", "#FF0000");
                                      $("#popularlogs").load("h_ajax.php?page=popularlogs",{"a":a});
                                  });

                                  $("#popularlosses").load("h_ajax.php?page=popularlosses");
                                  $("#popularlosses").on("click", ".pagination a", function (e) {
                                      e.preventDefault();
                                      var page = $(this).attr("data-page");
                                      $("#popularlosses").load("h_ajax.php?page=popularlosses",{"n":page});
                                  });';

        if (isset($_SESSION['popularlosses']['a']) && $_SESSION['popularlosses']['a'] == "weeklosses") $strTableInner .= '$("#weeklosses").css("color", "#FF0000");';
        if (isset($_SESSION['popularlosses']['a']) && $_SESSION['popularlosses']['a'] == "yearlosses") $strTableInner .= '$("#yearlosses").css("color", "#FF0000");';
        if (isset($_SESSION['popularlosses']['a']) && $_SESSION['popularlosses']['a'] == "alllosses") $strTableInner .= '$("#alllosses").css("color", "#FF0000");';

        $strTableInner .= '     $("#weeklosses").on("click", $("#weeklosses"), function (e) {
                                      e.preventDefault();
                                      var a = "weeklosses";
                                      $("#weeklosses").css("color", "#FF0000");
                                      $("#yearlosses").css("color", "#09B");
                                      $("#alllosses").css("color", "#09B");
                                      $("#popularlosses").load("h_ajax.php?page=popularlosses",{"a":a});
                                  });
                                  $("#yearlosses").on("click", $("#yearlosses"), function (e) {
                                      e.preventDefault();
                                      var a = "yearlosses";
                                      $("#weeklosses").css("color", "#09B");
                                      $("#yearlosses").css("color", "#FF0000");
                                      $("#alllosses").css("color", "#09B");
                                      $("#popularlosses").load("h_ajax.php?page=popularlosses",{"a":a});
                                  });
                                  $("#alllosses").on("click", $("#alllosses"), function (e) {
                                      e.preventDefault();
                                      var a = "alllosses";
                                      $("#weeklosses").css("color", "#09B");
                                      $("#yearlosses").css("color", "#09B");
                                      $("#alllosses").css("color", "#FF0000");
                                      $("#popularlosses").load("h_ajax.php?page=popularlosses",{"a":a});
                                  });

                                  $("#ratingslogs").load("h_ratings.php?list=1");

                                  $("#lastlogs").load("h_ajax.php?page=lastlogs");
                                  $("#lastlogs").on("click", ".pagination a", function (e) {
                                      e.preventDefault();
                                      var domain = $("#select_domain").val();
                                      var uni = $("#select_uni").val();
                                      var page = $(this).attr("data-page");
                                      $("#lastlogs").load("h_ajax.php?page=lastlogs",{"n":page, "domain":domain, "uni":uni});
                                  });
                                  $("#selectLastLogs").on("click", "#imgRefreshLastLogs", function (e) {
                                      e.preventDefault();
                                      var domain = $("#select_domain").val();
                                      var uni = $("#select_uni").val();
                                      $("#lastlogs").load("h_ajax.php?page=lastlogs",{"domain":domain, "uni":uni});
                                  });
                              });</script>';

                    $strTableInner .= "
                        <tr>
                            <td align='center'>
                                <table style='border-collapse: collapse' border='1' bordercolor='#222222' cellpadding='4' width='640'>
                                    <tr height='28'>
                                        <td onmouseover='ActivateHeader(this)' onmouseout='DeactivateHeader(this)' onclick='javascript:document.location.href=\"#popularlosses\"' align='center' background='index_files/abox/header.png' width='0'><font class='abox_text' onmouseover='style.cursor=(\"default\")'>" . Dictionary('most_loss_logs') . "</font></td>
                                        <td onmouseover='ActivateHeader(this)' onmouseout='DeactivateHeader(this)' onclick='javascript:document.location.href=\"#ratingslogs\"' align='center' background='index_files/abox/header.png' width='0'><font class='abox_text' onmouseover='style.cursor=(\"default\")'>" . Dictionary('most_rating_logs') . "</font></td>
                                        <td onmouseover='ActivateHeader(this)' onmouseout='DeactivateHeader(this)' onclick='javascript:document.location.href=\"#lastlogs\"' align='center' background='index_files/abox/header.png' width='0'><font class='abox_text' onmouseover='style.cursor=(\"default\")'>" . Dictionary('last') . "  30 " . Dictionary('public_logs') . "</font></td>
                                    </tr>                       
                                </table>                        
                            </td>
                        </tr>
                        <tr>
                            <td align='center'>
                                <font color='" . WHITE_DARK . "' face='Arial' size='3';>" . Dictionary('most_pop_logs') . ":</font>
                            </td>
                        </tr>
                        <tr>
                            <td align='center'>
                                <font color='" . WHITE_DARK . "' face='Arial' style='font-size:12px'><a href='' id='weekpop' style='font-size:12px'>" . Dictionary('public_week') . "</a> | <a href='' id='yearpop' style='font-size:12px'>" . Dictionary('public_year') . "</a> | <a href='' id='allpop' style='font-size:12px'>" . Dictionary('public_all_time') . "</a></font>
                            </td>
                        </tr
                        <tr><td align='center'><div id='popularlogs'><img src='index_files/ajax-loader.gif'></div></td></tr>
                        ";
                        
                    $strTableInner .= "
                        <tr>
                            <td align='center'>
                                <font color='" . WHITE_DARK . "' face='Arial' size='3'>" . Dictionary('most_loss_logs') . ":</font>
                            </td>
                        </tr>
                        <tr>
                            <td align='center'>
                                <font color='" . WHITE_DARK . "' face='Arial' style='font-size:12px'><a href='' id='weeklosses' style='font-size:12px'>" . Dictionary('public_week') . "</a> | <a href='' id='yearlosses' style='font-size:12px'>" . Dictionary('public_year') . "</a> | <a href='' id='alllosses' style='font-size:12px'>" . Dictionary('public_all_time') . "</a></font>
                            </td>
                        </tr>
                        <tr><td align='center'><div id='popularlosses'><img src='index_files/ajax-loader.gif'></div></td></tr>
                        ";

                    $strTableInner .= "
                        <tr>
                            <td align='center'>
                                <font color='" . WHITE_DARK . "' face='Arial' size='3'>" . Dictionary('most_rating_logs') . ":</font>
                            </td>
                        </tr>
                        <tr><td align='center'><div id='ratingslogs'><img src='index_files/ajax-loader.gif'></div></td></tr>
                        ";

                    $strTableInner .= "
                        <tr>
                            <td align='center'>
                                <font color='" . WHITE_DARK . "' face='Arial' size='3'>" . Dictionary('last') . "  30 " . Dictionary('public_logs') . ":</font>
                            </td>
                        </tr>
                        <tr>
                            <td id='selectLastLogs' align='center'>
                                $strDomainSelect $strUniSelect <img src='index_files/refresh.png' style='vertical-align:middle;' width='20' height='20' id='imgRefreshLastLogs' alt='Refresh'>
                            </td>
                        </tr>
                        <tr><td align='center'><div id='lastlogs'><img src='index_files/ajax-loader.gif'></div>
                        ";

        $strTableInner .= BotHtml();

        $strTableInner .= "     <noscript>";
        $strTableInner .= "     <tr>";
        $strTableInner .= "         <td align='center'>";
        $strTableInner .= "             <font face='Arial' color='#FF3300' size='2'>" . Dictionary("noscript") . "</font>";
        $strTableInner .= "         </td>";
        $strTableInner .= "     </tr>";
        $strTableInner .= "     </noscript>";

        $strTableInner .= "     <tr>";
        $strTableInner .= "         <td id='submit_td' align='center' height='30' background='" . VISTA_PANEL . "' style='padding: 0'>";
        $strTableInner .= "             <noscript><input type='submit' value='&#9668; Submit &#9658;' style='width: 120; height: 24; color: #000000; border: 1px solid #000000; background-image: url(".SUBMIT_BLUE_DARK.")'></noscript>";
        $strTableInner .= "         </td>";
        $strTableInner .= "     </tr>";

        ShowHTML($strTableInner, SERVER_NAME, false);
    }

    function LogListCreate($arrInput) {
        $arrLinks = array();

        if (array_key_exists("show_all_flag", $arrInput)) {
            $arrLogs = LogListSearch($arrInput["what"], GetLogListFilePath(FOLDER_UPLOAD . "X"));
        }
        else {
            $intUserID = GetUserIDFromSession();
            $arrLogsD = cDB::LogListSearch($arrInput["what"], 0);
            //$arrLogsF = LogListSearch($arrInput["what"], GetLogListFilePath($arrInput["where"]));
            $arrLogs = $arrLogsD;
            //$arrLogs = array_merge($arrLogsD, $arrLogsF);
        }

        foreach ($arrLogs as $arrLog) {
            //$strTitle = preg_match("/\(.*?,/", $arrLog["title"], &$arrMatches);
            //$arrMatches[0] = str_replace(",", "", str_replace("(", "", $arrMatches[0]));
            //$strTitle = str_replace($arrMatches[0], "<b>" . $arrMatches[0] . "</b>", $arrLog["title"]);
            $strLink = "<a href='index.php?id=" . $arrLog["id"] . "' target='_blank' style='text-decoration: none'>" . $arrLog["title"] . "</a>";

            if (array_key_exists("err_flag", $arrInput))
                $strLink = "<a href='" . $arrInput["where"]. "/" . $arrLog["id"] . "' target='_blank' style='text-decoration: none'>" . $arrLog["id"] . "<br>" . $arrLog["err_serialize"] . "</a>";
            if (array_key_exists("tmp_flag", $arrInput))
                $strLink = "<a href='" . $arrInput["where"]. "/" . $arrLog["id"] . "' target='_blank' style='text-decoration: none'>" . $arrLog["title"] . "</a>";

            if (array_key_exists("delete_flag", $arrInput)) {
                $strDeleteLink = "<a href='index.php?delete_x=" . base64_encode(base64_encode($arrLog["id"])) . "'><font color='" . RED_COMMON . "' face='Arial' size='2'><b> x</b></font></a>";
                $strLink .= $strDeleteLink;
            }

            $arrLinks[] = $strLink;
        }

        if (count($arrLinks) == 0) $arrLinks = UNDEFINED;

        return $arrLinks;
    }
    function PopularAllLogsListCreate($intCount) {
        $arrLinks = array();

        $arrLogs = cDB::GetPopularAllLogs($intCount);

        foreach ($arrLogs as $arrLog) {
            $strLink = "<a href='index.php?id=" . $arrLog["id"] . "' target='_blank' style='text-decoration: none'>" . $arrLog["title"] . "</a>";
            $arrTemp["link"] = $strLink;
            $arrTemp["views"] = $arrLog["views"];
            $arrLinks[] = $arrTemp;
        }

        if (count($arrLinks) == 0) $arrLinks = UNDEFINED;
        return $arrLinks;
    }
    function PopularLogListCreate($intCount) {
        $arrLinks = array();

        $arrLogs = cDB::GetPopularLogs($intCount);

        foreach ($arrLogs as $arrLog) {
            $strLink = "<a href='index.php?id=" . $arrLog["id"] . "' target='_blank' style='text-decoration: none'>" . $arrLog["title"] . "</a>";
            $arrTemp["link"] = $strLink;
            $arrTemp["views"] = $arrLog["views"];
            $arrLinks[] = $arrTemp;
        }

        if (count($arrLinks) == 0) $arrLinks = UNDEFINED;
        return $arrLinks;
    }

    function ShowResult($varInput) {
        $strTableInner = '';

        if (!is_array($varInput)) {
            $strTableInner .= "<style>.error {-moz-transform: rotate(-7deg); -o-transform: rotate(-7deg); -webkit-transform: rotate(-7deg); position: absolute; top: 75px;}</style>";
            $strTableInner .= "<tr><td align='left'><font face='Arial' color='" . RED_COMMON . "' size='2'>" . Dictionary('errors_occured') . ":</font></td></tr>";
            if (IsErrors())
                foreach (GetErrStack() as $value) {
                    $strTableInner .= "<tr><td align='left'><font face='Arial' color='#88888' size='2'>Error source: ".$value['source']."</font><br>";
                    $strTableInner .= "<font face='Arial' color='#88888' size='2'>Error description: ".$value['description']."</font></td></tr>";
                }
            if (($varInput == "ERR_CLOG_PROCESS") || ($varInput == "ERR_CLOG_CONSTRUCT")) {
                $strTableInner .= "<tr><td align='left'><font face='Arial' color='" . RED_COMMON . "' size='2'>" . Dictionary('upload_err') . "</font></td></tr>";
            }
        }
        else {
            $strTableInner .= "<tr>";
            $strTableInner .= " <td align='center'>";
            $strTableInner .= "     <table width='100%'>";
            $strTableInner .= "         <tr>";
            $strTableInner .= "             <td align='left'><font face='Arial' color='#00FF00' size='2'>" . Dictionary('log_uploaded') . ":</font></td>";
            //$strTableInner .= "             <td align='right'><div id ='addWar' class='button' style='width: 55px;'>Save War</div></td>";
            $strTableInner .= "         </tr>";
            $strTableInner .= "     </table>";
            $strTableInner .= " </tr>";
            $strTableInner .= "<tr><td align='left'>";
            foreach ($varInput as $strInfo) {
                $strTableInner .= "<font color='#88888' face='Arial' size='1'>".$strInfo."</font><br>";
            }
            $strTableInner .= "</td></tr>";
        }

        $strTableInner .= BotHtml();

        $strTableInner .= "     <tr>";
        $strTableInner .= "     <td align='center' valign='center' height='30' background='" . VISTA_PANEL . "' style='padding: 0px'>";

        /*
        if (is_array($varInput))
            $strTableInner .= "             <input type='submit' value='Status: OK' name='' style='width: 120; height: 24; color: #000000; border: 1px solid #000000; background-image: url(".SUBMIT_GREEN_DARK.")' onmouseover='JS_SetButtonBG(this, \"".SUBMIT_GREEN_LIGHT."\")' onmouseout='JS_SetButtonBG(this, \"".SUBMIT_GREEN_DARK."\")'>";
        else
            $strTableInner .= "             <input type='submit' value='Status: ERR' name='' style='width: 120; height: 24; color: #000000; border: 1px solid #000000; background-image: url(".SUBMIT_RED_DARK.")' onmouseover='JS_SetButtonBG(this, \"".SUBMIT_RED_LIGHT."\")' onmouseout='JS_SetButtonBG(this, \"".SUBMIT_RED_DARK."\")'>";
        */

        $strTableInner .= "         </td>";
        $strTableInner .= "     </tr>";

        ShowHTML($strTableInner, SERVER_NAME, false);
    }

    function GetUploadResult($objLog, $intTimer) {
        if ($intTimer < 0) $intTimer = 0;

        if ($objLog->Get('version') == "s") {
            $strHTML .=  "<table border='0' style='border-collapse: collapse; margin:0 auto'>
                            <tr>
                                <td>
                                    <font face='Arial' color='" . WHITE_DARK . "' size='2'>URL:&nbsp;</font>
                                </td>
                                <td>
                                    <input type='text' class='text' name='' size='100' value='" . $objLog->Get('url') . "' style='text-align: center; border-radius: 10px 10px 0px; font-size: 10px; font-family: Arial; color: rgb(136, 136, 136); background-color: rgb(0, 0, 0); border: 1px solid rgb(136, 136, 136);'' onclick='this.select();'>
                                    <a href='" . $objLog->Get('url') . "' target='_blank' style='text-decoration: none'><font face='Arial' color='" . WHITE_DARK . "' size='2'>" . Dictionary('navigate') . " </font>&#9658;</a>
                                </td>
                            </tr>
                        </table>";
            $arrOutput[] = $strHTML;
            return $arrOutput;
        }

        // URL
        $strHTML = "";
            //$strHTML .= '<script type="text/javascript">$(document).ready(function(){$("#bb_code_logserver").hide();$("#panelLogserverNet").click(function(){"none"==$("#bb_code_logserver").css("display")?$("#bb_code_logserver").show():$("#bb_code_logserver").hide()});$("#bb_code_ogamecpec").hide();$("#panelOgamecpec").click(function(){"none"==$("#bb_code_ogamecpec").css("display")?$("#bb_code_ogamecpec").show():$("#bb_code_ogamecpec").hide()});$("#imgShahterovNet").hide();$("#panelShahterovNet").click(function(){$("#imgShahterovNet").show();$("#contentShahterovNet").hide();';

        $strHTML .=  "<table border='0' style='border-collapse: collapse'>";
        $strHTML .=  "  <tr>";
        $strHTML .=  "      <td style='cursor: pointer;' height='30' width='800' id='panelLogserverNet' colspan='2' align='center' background='".VISTA_PANEL."'><font face='Arial' color='" . WHITE_DARK . "' size='2'>" . SERVER_NAME . "</font></td>";
        $strHTML .=  "  </tr>";
        $strHTML .=  "  <tr>";
        $strHTML .=  "      <td width='100'>";
        $strHTML .=  "          <font face='Arial' color='" . WHITE_DARK . "' size='2'>URL:&nbsp;</font>";
        $strHTML .=  "      </td>";
        $strHTML .=  "      <td>";
        $strHTML .=  "          <input type='text' class='text' name='' size='110' value='" . $objLog->Get('url') . "' style='border:1px solid " . WHITE_DARK . "; color: " . WHITE_DARK . "; font-family: Arial; font-size: 12px; background-color: #000000' onclick='this.select();'>";
        $strHTML .=  "          <a href='" . $objLog->Get('url') . "' target='_blank' style='text-decoration: none'><font face='Arial' color='" . WHITE_DARK . "' size='2'>" . Dictionary('navigate') . " </font>&#9658;</a>";
        $strHTML .=  "      </td>";
        $strHTML .=  "  </tr>";

        // BB-url
        $strHTML .=  "  <tr>";
        $strHTML .=  "      <td>";
        $strHTML .=  "          <font face='Arial' color='" . WHITE_DARK . "' size='2'>BB-URL:&nbsp;</font>";
        $strHTML .=  "      </td>";
        $strHTML .=  "      <td>";
        $strHTML .=  "          <textarea rows='1' name='' cols='120' style='font-size: 12px; font-family: Arial; color:" . WHITE_DARK . "; background-color:#000000; border-style:solid; border: 1px solid " . WHITE_DARK . ";' onclick='this.select();'>".$objLog->Get('bburl')."</textarea>";
        $strHTML .=  "      </td>";
        $strHTML .=  "  </tr>";

        // BB-img
        $strHTML .=  "  <tr>";
        $strHTML .=  "      <td>";
        $strHTML .=  "          <font face='Arial' color='" . WHITE_DARK . "' size='2'>BB-IMG:&nbsp;</font>";
        $strHTML .=  "      </td>";
        $strHTML .=  "      <td>";
        $strHTML .=  "          <textarea rows='1' name='' cols='120' style='font-size: 12px; font-family: Arial; color:" . WHITE_DARK . "; background-color:#000000; border-style:solid; border: 1px solid " . WHITE_DARK . ";' onclick='this.select();'>[url='" . $objLog->Get('url') . "'][img]" . str_replace("index", "img", $objLog->Get('url')) . "[/img][/url]
</textarea>";
        $strHTML .=  "      </td>";
        $strHTML .=  "  </tr>";

        // BB-title
        $strHTML .=  "  <tr>";
        $strHTML .=  "      <td>";
        $strHTML .=  "          <font face='Arial' color='" . WHITE_DARK . "' size='2'>TITLE:&nbsp;</font>";
        $strHTML .=  "      </td>";
        $strHTML .=  "      <td>";
        $strHTML .=  "          <input type='text' class='text' name='' size='100' value='" . $objLog->Get('longtitle') . "' style='border:1px solid " . WHITE_DARK . "; color: " . WHITE_DARK . "; font-family: Arial; font-size: 12px; background-color: #000000' onclick='this.select();'>";
        $strHTML .=  "      </td>";
        $strHTML .=  "  </tr>";
        
        // BB-code
        $strHTML .=  "  <tr id='bb_code_logserver'>";
        $strHTML .=  "      <td valign='top'>";
        $strHTML .=  "          <font face='Arial' color='" . WHITE_DARK . "' size='2'>BB-CODE:&nbsp"."\n"."&nbsp;</font>";
        $strHTML .=  "      </td>";
        $strHTML .=  "      <td>";
        $strHTML .=  "          <textarea rows='4' name='' cols='120' style='font-size: 12px; font-family: Arial; color:" . WHITE_DARK . "; background-color:#000000; border-style:solid; border: 1px solid " . WHITE_DARK . ";' onclick='this.select();'>" . $objLog->Get("bbcode") . "</textarea>";
        $strHTML .=  "      </td>";
        $strHTML .=  "  </tr>";
        $strHTML .=  "</table>";

        //curlSendLogDiscord ($objLog->Get('url') . "\n```fix\n" . $objLog->Get('longtitle') . "\n```");
        $discordArrDate["public"] = $objLog->Get('public');
        $discordArrDate["uni"] = $objLog->Get('uni');
        $discordArrDate["domain"] = $objLog->Get('domain');
        $discordArrDate["url"] = $objLog->Get('url');
        curlSendLogDiscord ($discordArrDate);

        // http://logserver.org/index.php?id=d300f3569b283f90bf724464b388be1c0e68
        if ($objLog->Get('backupexists')) {
            $strHTML .=  "<br>";
            // URL
            $strHTML .=  "<table border='0' style='border-collapse: collapse'>";
            $strHTML .=  "  <tr>";
            $strHTML .=  "      <td style='cursor: pointer;' height='30' width='800' id='panelOgamecpec' colspan='2' align='center' background='".VISTA_PANEL."'><font face='Arial' color='" . WHITE_DARK . "' size='2'>" . ALT_SERVER_NAME . "</font></td>";
            $strHTML .=  "  </tr>";
            $strHTML .=  "  <tr>";
            $strHTML .=  "      <td width='100'>";
            $strHTML .=  "          <font face='Arial' color='" . WHITE_DARK . "' size='2'>ALT. URL:&nbsp;</font>";
            $strHTML .=  "      </td>";
            $strHTML .=  "      <td>";
            $strHTML .=  "          <input type='text' class='text' name='' size='110' value='" . $objLog->Get('url2') . "' style='border:1px solid " . WHITE_DARK . "; color: " . WHITE_DARK . "; font-family: Arial; font-size: 12px; background-color: #000000' onclick='this.select();'>";
            $strHTML .=  "          <a href='" . $objLog->Get('url2') . "' target='_blank' style='text-decoration: none'><font face='Arial' color='" . WHITE_DARK . "' size='2'>" . Dictionary('navigate') . " </font>&#9658;</a>";
            $strHTML .=  "      </td>";
            $strHTML .=  "  </tr>";

            // BB-url
            $strHTML .=  "  <tr>";
            $strHTML .=  "      <td>";
            $strHTML .=  "          <font face='Arial' color='" . WHITE_DARK . "' size='2'>ALT.  BB-URL:&nbsp;</font>";
            $strHTML .=  "      </td>";
            $strHTML .=  "      <td>";
            $strHTML .=  "          <textarea rows='1' name='' cols='120' style='font-size: 12px; font-family: Arial; color:" . WHITE_DARK . "; background-color:#000000; border-style:solid; border: 1px solid " . WHITE_DARK . ";' onclick='this.select();'>".$objLog->Get('bburl2')."</textarea>";
            $strHTML .=  "      </td>";
            $strHTML .=  "  </tr>";

            $strHTML .=  "</table>";
        }

        $_SESSION["htmllog"] = gzuncompress($objLog->Get('zown'));
        $_SESSION["id"] = $objLog->Get('logid');
        $_SESSION["url"] = $objLog->Get('url');
        $_SESSION["bburl"] = $objLog->Get("bburl");
        $_SESSION["recyclerreport"] = $objLog->Get("recyclerreport");
        $_SESSION["comment"] = $objLog->Get("comment");

/*
        //ShahterovNet
        $strHTML .=  "<br>";
        $strHTML .=  "<table border='0' style='border-collapse: collapse'>";
        $strHTML .=  "  <tr>";
        $strHTML .=  "      <td style='cursor: pointer;' height='30' width='800' id='panelShahterovNet' colspan='2' align='center' background='".VISTA_PANEL."'><font face='Arial' color='" . WHITE_DARK . "' size='2'>Shahterov.Net</font></td>";
        $strHTML .=  "  </tr>";
        $strHTML .=  "  <tr>";
        $strHTML .=  "      <td style='colspan: 2; align: center;'><center><img width='128' src='index_files/ajax-loader.gif' id='imgShahterovNet'></center></td>";
        $strHTML .=  "  </tr>";
        $strHTML .=  "  <tr id='contentShahterovNet'>";
        $strHTML .=  "  </tr>";
        $strHTML .=  "</table>";

        //WarLogs
        $strHTML .=  "<br>";
        $strHTML .=  "<table border='0' style='border-collapse: collapse'>";
        $strHTML .=  "  <tr>";
        $strHTML .=  "      <td style='cursor: pointer;' height='30' width='800' id='panelWarLogs' colspan='2' align='center' background='".VISTA_PANEL."'><font face='Arial' color='" . WHITE_DARK . "' size='2'>WarLogs.Ru</font></td>";
        $strHTML .=  "  </tr>";
        $strHTML .=  "  <tr>";
        $strHTML .=  "      <td style='colspan: 2; align: center;'><center><img width='128' src='index_files/ajax-loader.gif' id='imgWarLogs'></center></td>";
        $strHTML .=  "  </tr>";
        $strHTML .=  "  <tr id='contentWarLogs'>";
        $strHTML .=  "  </tr>";
        $strHTML .=  "</table>";

        //http://ogame.gstrategy.ru/
        $strHTML .=  "<br>";
        $strHTML .=  "<table border='0' style='border-collapse: collapse'>";
        $strHTML .=  "  <tr>";
        $strHTML .=  "      <td style='cursor: pointer;' height='30' width='800' id='panelGstrategy' colspan='2' align='center' background='".VISTA_PANEL."'><font face='Arial' color='" . WHITE_DARK . "' size='2'>OGame.Gstrategy.Ru</font></td>";
        $strHTML .=  "  </tr>";
        $strHTML .=  "  <tr>";
        $strHTML .=  "      <td style='colspan: 2; align: center;'><center><img width='128' src='index_files/ajax-loader.gif' id='imgGstrategy'></center></td>";
        $strHTML .=  "  </tr>";
        $strHTML .=  "  <tr id='contentGstrategy'>";
        $strHTML .=  "  </tr>";
        $strHTML .=  "</table>";
*/
        $arrOutput[] = $strHTML;
        $arrOutput[] = $objLog->Get('title');
        $arrOutput[] = "id = ".$objLog->Get('logid');
        $arrOutput[] = "uploaded log number: ".$objLog->Get('logscount')."/".MAX_STORAGE;
        $arrOutput[] = "uploaded in (ms): ".round($intTimer * 1000, 2);
        $arrOutput[] = "html log length (bytes) = ".strlen($objLog->Get('htmllog'));
        $arrOutput[] = "compressed log length (bytes): ".strlen($objLog->Get('ziplog'));
        $arrOutput[] = "compression rate (%): ".round(((strlen($objLog->Get('htmllog')) - strlen($objLog->Get('ziplog'))) / strlen($objLog->Get('htmllog')) * 100), 2);

        return $arrOutput;
    }

    function ShowInfo() {
        // F.A.Q.
        $strFAQ = Dictionary("faq_xml");
        $strFAQ = str_replace("<title>", "<font face='Arial' color='" . GREEN_COMMON . "' size='2'>", $strFAQ);
        $strFAQ = str_replace("</title>", "</font><br>", $strFAQ);
        $strFAQ = str_replace("<text>", "&nbsp;&nbsp;&nbsp;&nbsp;<font face='Arial' color='" . WHITE_DARK . "' size='2'>", $strFAQ);
        $strFAQ = str_replace("</text>", "</font><br>", $strFAQ);

        $strTableInner = "      <tr><td align='center'>
                                    <table><tr><td>
                                        <img src='" . "index_files/vista_panel/icon_info_b.png" . "' width='32'>
                                    </td>
                                    <td width='4'></td>
                                    <td>
                                        <font class='h2'>F.A.Q.</font>
                                    </td></tr></table>
                                </td></tr>";

        $strTableInner .= "     <tr><td align='center'>";
        $strTableInner .= "     <a href='http://ogame.de/' target='_blank'><img src='" . OGAME_PNG . "' border='0' alt='OGame'></a>";
        $strTableInner .= "     </td></tr>";

        $strTableInner .= "     <tr><td align='left' style='padding-top: 0'>";
        $strTableInner .= $strFAQ;
        $strTableInner .= "     </td></tr>";

        $strTableInner .= BotHtml();

        $strTableInner .= "     <tr>";
        $strTableInner .= "         <td id='submit_td' align='center' height='30' background='" . VISTA_PANEL . "' style='padding: 0'>";
        $strTableInner .= "         </td>";
        $strTableInner .= "     </tr>";

        ShowHTML($strTableInner, SERVER_NAME, false);
    }

    function ShowFixList() {
        // Fix list
        $strTableInner = "      <tr><td align='center'>";
        $strTableInner .= "         <font class='h2'>.:: Fix List ::.</font>";
        $strTableInner .= "     </td></tr>";

        $strTableInner .= "     <tr><td align='left' style='padding-top: 0'>";
        $strTableInner .= "         <center><table border='0' style='border-collapse: collapse' cellpadding='0'>";

        $strTableInner .= "             <tr height='10'><td width='100'></td><td width='600'></td></tr>";

        $strTableInner .= "             <tr background='index_files/abox/row_1.png'>";
        $strTableInner .= "                 <td style='text-align: center; color: " . WHITE_DARK . ";'>07/03/20</td>";
        $strTableInner .= "                 <td><font face='Arial' size='2' color='" . WHITE_DARK . "'>
                                                <ul>
                                                    <li>LogServer is now v3.4.1</li>
                                                    <li>Исправление процентов в потерях лога.</li>
                                                    <li>Правильный парсер жнецов.</li>
                                                </ul>
                                            </font></td>";
        $strTableInner .= "             </tr>";


        $strTableInner .= "             <tr background='index_files/abox/row_2.png'>";
        $strTableInner .= "                 <td style='text-align: center; color: " . WHITE_DARK . ";'>02/02/20</td>";
        $strTableInner .= "                 <td><font face='Arial' size='2' color='" . WHITE_DARK . "'>
                                                <ul>
                                                    <li>LogServer is now v3.4</li>
                                                    <li>Переход парсера проекта на 7ю версию игры (d700) для избежания конфликтов с предыдущими версиями.</li>
                                                </ul>
                                            </font></td>";
        $strTableInner .= "             </tr>";

        $strTableInner .= "             <tr background='index_files/abox/row_1.png'>";
        $strTableInner .= "                 <td style='text-align: center; color: " . WHITE_DARK . ";'>20/10/17</td>";
        $strTableInner .= "                 <td><font face='Arial' size='2' color='" . WHITE_DARK . "'><ul><li>LogServer is now v3.3</li><li>Учитываются особенности вселенной для расчета затрат дейтерия (пока только для потребление дейтерия флотом).</li><li>Кнопка на TrashSim в шпионских докладах.</li><li>Исправлены незначительные баги.</li></ul></font></td>";
        $strTableInner .= "             </tr>";

        $strTableInner .= "             <tr background='index_files/abox/row_2.png'>";
        $strTableInner .= "                 <td style='text-align: center; color: " . WHITE_DARK . ";'>14/10/17</td>";
        $strTableInner .= "                 <td><font face='Arial' size='2' color='" . WHITE_DARK . "'><ul><li>LogServer is now v3.2</li><li>Добавлен космический док</li></ul></font></td>";
        $strTableInner .= "             </tr>";

        $strTableInner .= "             <tr background='index_files/abox/row_1.png'>";
        $strTableInner .= "                 <td style='text-align: center; color: " . WHITE_DARK . ";'>14/04/17</td>";
        $strTableInner .= "                 <td><font face='Arial' size='2' color='" . WHITE_DARK . "'><ul><li>LogServer is now v3.1</li><li>Наполнен фильтр по вселенным</li><li>Исправлены незначительные баги</li></ul></font></td>";
        $strTableInner .= "             </tr>";

        $strTableInner .= "             <tr background='index_files/abox/row_2.png'>";
        $strTableInner .= "                 <td style='text-align: center; color: " . WHITE_DARK . ";'>24/11/14</td>";
        $strTableInner .= "                 <td><font face='Arial' size='2' color='" . WHITE_DARK . "'><ul><li>LogServer is now v2.8</li><li>Добавлен в бб-код ссылки на лог топ по флотам игроков (отключено по умолчанию).</li><li>Обновлены настройки.</li></ul></font></td>";
        $strTableInner .= "             </tr>";

        $strTableInner .= "             <tr background='index_files/abox/row_1.png'>";
        $strTableInner .= "                 <td style='text-align: center; color: " . WHITE_DARK . ";'>21/11/14</td>";
        $strTableInner .= "                 <td><font face='Arial' size='2' color='" . WHITE_DARK . "'><ul><li>Востановлен поиск публичных логов</li></ul></font></td>";
        $strTableInner .= "             </tr>";

        $strTableInner .= "             <tr background='index_files/abox/row_2.png'>";
        $strTableInner .= "                 <td style='text-align: center; color: " . WHITE_DARK . ";'>20/11/14</td>";
        $strTableInner .= "                 <td><font face='Arial' size='2' color='" . WHITE_DARK . "'><ul><li>Исправлено востановление и смена пароля</li><li>Добавлен рейтинг логов и лучший рейтинг логов за неделю.</li><li>Исправлен баг с запоминанием языка для ресурса.</li></ul></font></td>";
        $strTableInner .= "             </tr>";

        $strTableInner .= "             <tr background='index_files/abox/row_1.png'>";
        $strTableInner .= "                 <td style='text-align: center; color: " . WHITE_DARK . ";'>29/06/14</td>";
        $strTableInner .= "                 <td><font face='Arial' size='2' color='" . WHITE_DARK . "'><ul><li>Обновление скрипта v.3.4.1</li></ul></font></td>";
        $strTableInner .= "             </tr>";

        $strTableInner .= "             <tr background='index_files/abox/row_2.png'>";
        $strTableInner .= "                 <td style='text-align: center; color: " . WHITE_DARK . ";'>20/08/14</td>";
        $strTableInner .= "                 <td><font face='Arial' size='2' color='" . WHITE_DARK . "'><ul><li>Добавлена в бб-кодах прибыль.</li><li>В меню Плагины теперь <a href='index.php?show=plugin'>список официально разрешенных скриптов</a></li></ul></font></td>";
        $strTableInner .= "             </tr>";

        $strTableInner .= "             <tr background='index_files/abox/row_1.png'>";
        $strTableInner .= "                 <td style='text-align: center; color: " . WHITE_DARK . ";'>18/06/14</td>";
        $strTableInner .= "                 <td><font face='Arial' size='2' color='" . WHITE_DARK . "'><ul><li>Исправление тега для вселенной Ursa ([U] -> [Ur])</li></ul></font></td>";
        $strTableInner .= "             </tr>";

        $strTableInner .= "             <tr background='index_files/abox/row_2.png'>";
        $strTableInner .= "                 <td style='text-align: center; color: " . WHITE_DARK . ";'>28/05/14</td>";
        $strTableInner .= "                 <td><font face='Arial' size='2' color='" . WHITE_DARK . "'><ul><li>LogServer is now v2.7.7</li><li>Оптимзация загрузки лога</li><li>Оптимизация запросов в бд для загрузки списков</li><li>Добавлен доступ к старым логам в аккаунте</li><li>Отображение по 10 логов в аккаунте</li><li>Добавлена паджинация</li><li>\"Правильная\" реакция, если на аккаунте нет логов</li></ul></font></td>";
        $strTableInner .= "             </tr>";

        $strTableInner .= "             <tr background='index_files/abox/row_1.png'>";
        $strTableInner .= "                 <td style='text-align: center; color: " . WHITE_DARK . ";'>03/03/14</td>";
        $strTableInner .= "                 <td><font face='Arial' size='2' color='" . WHITE_DARK . "'><ul><li>LogServer is now v2.7.6</li><li>Добавлен Лом атакера (по умолчанию) и Лом дефа</li></ul></font></td>";
        $strTableInner .= "             </tr>";

        $strTableInner .= "             <tr background='index_files/abox/row_2.png'>";
        $strTableInner .= "                 <td style='text-align: center; color: " . WHITE_DARK . ";'>12/02/14</td>";
        $strTableInner .= "                 <td><font face='Arial' size='2' color='" . WHITE_DARK . "'><ul><li>Кеш Xml для тегов альянса</li><li>Кеш логов</li></ul></font></td>";
        $strTableInner .= "             </tr>";

        $strTableInner .= "             <tr background='index_files/abox/row_1.png'>";
        $strTableInner .= "                 <td style='text-align: center; color: " . WHITE_DARK . ";'>30/12/13</td>";
        $strTableInner .= "                 <td><font face='Arial' size='2' color='" . WHITE_DARK . "'><ul><li>LogServer is now v2.7</li></ul></font></td>";
        $strTableInner .= "             </tr>";

        $strTableInner .= "             <tr background='index_files/abox/row_1.png'>";
        $strTableInner .= "                 <td style='text-align: center; color: " . WHITE_DARK . ";'>28/12/13</td>";
        $strTableInner .= "                 <td><font face='Arial' size='2' color='" . WHITE_DARK . "'><ul><li>Add ogame.gstrategy.ru for Alt system</li><li>Update code display alliace</li></ul></font></td>";
        $strTableInner .= "             </tr>";

        $strTableInner .= "             <tr background='index_files/abox/row_2.png'>";
        $strTableInner .= "                 <td style='text-align: center; color: " . WHITE_DARK . ";'>25/12/13</td>";
        $strTableInner .= "                 <td><font face='Arial' size='2' color='" . WHITE_DARK . "'><ul><li>Add Alt system (shahterov.net, warlogs.ru)</li></ul></font></td>";
        $strTableInner .= "             </tr>";

        $strTableInner .= "             <tr background='index_files/abox/row_1.png'>";
        $strTableInner .= "                 <td style='text-align: center; color: " . WHITE_DARK . ";'>13/01/13</td>";
        $strTableInner .= "                 <td><font face='Arial' size='2' color='" . WHITE_DARK . "'><ul><li>LogServer is now v2.6</li><li>Ajax display logs</li><li>Add Interface War</li></ul></font></td>";
        $strTableInner .= "             </tr>";

        $strTableInner .= "             <tr background='index_files/abox/row_2.png'>";
        $strTableInner .= "                 <td style='text-align: center; color: " . WHITE_DARK . ";'>04/03/12</td>";
        $strTableInner .= "                 <td><font face='Arial' size='2' color='" . WHITE_DARK . "'><ul><li>Updating the language pack v2.5.1</li></ul></font></td>";
        $strTableInner .= "             </tr>";

        $strTableInner .= "             <tr background='index_files/abox/row_1.png'>";
        $strTableInner .= "                 <td style='text-align: center; color: " . WHITE_DARK . ";'>24/06/10</td>";
        $strTableInner .= "                 <td><font face='Arial' size='2' color='" . WHITE_DARK . "'><ul><li>LogServer is now v2.5</li><li>Added change and lost password</li></ul></font></td>";
        $strTableInner .= "             </tr>";

        $strTableInner .= "             <tr background='index_files/abox/row_2.png'>";
        $strTableInner .= "                 <td style='text-align: center; color: " . WHITE_DARK . ";'>26/11/10</td>";
        $strTableInner .= "                 <td><font face='Arial' size='2' color='" . WHITE_DARK . "'><ul><li>LogServer is now v2.4</li></ul></font></td>";
        $strTableInner .= "             </tr>";

        $strTableInner .= "             <tr background='index_files/abox/row_1.png'>";
        $strTableInner .= "                 <td style='text-align: center; color: " . WHITE_DARK . ";'>15/09/10</td>";
        $strTableInner .= "                 <td><font face='Arial' size='2' color='" . WHITE_DARK . "'><ul><li>New skins: ?ntrvr[!], Disturbed, Static-X, System shock</li></ul></font></td>";
        $strTableInner .= "             </tr>";

        $strTableInner .= "             <tr background='index_files/abox/row_2.png'>";
        $strTableInner .= "                 <td style='text-align: center; color: " . WHITE_DARK . ";'>18/05/10</td>";
        $strTableInner .= "                 <td><font face='Arial' size='2' color='" . WHITE_DARK . "'><ul><li>New background every month</li></ul></font></td>";
        $strTableInner .= "             </tr>";

        $strTableInner .= "             <tr background='index_files/abox/row_1.png'>";
        $strTableInner .= "                 <td style='text-align: center; color: " . WHITE_DARK . ";'>14/05/10</td>";
        $strTableInner .= "                 <td><font face='Arial' size='2' color='" . WHITE_DARK . "'><ul><li>Link on OpenGalaxy in CR</li></ul></font></td>";
        $strTableInner .= "             </tr>";

        $strTableInner .= "             <tr background='index_files/abox/row_2.png'>";
        $strTableInner .= "                 <td style='text-align: center; color: " . WHITE_DARK . ";'>15/03/10</td>";
        $strTableInner .= "                 <td><font face='Arial' size='2' color='" . WHITE_DARK . "'><ul><li>LogServer is now v2.0</li></ul></font></td>";
        $strTableInner .= "             </tr>";

        $strTableInner .= "             <tr background='index_files/abox/row_1.png'>";
        $strTableInner .= "                 <td style='text-align: center; color: " . WHITE_DARK . ";'>21/02/10</td>";
        $strTableInner .= "                 <td><font face='Arial' size='2' color='" . WHITE_DARK . "'><ul><li>Plugin updated up to v1.4</li><li>Plugin published on userscripts.org</li></ul></font></td>";
        $strTableInner .= "             </tr>";

        $strTableInner .= "             <tr background='index_files/abox/row_2.png'>";
        $strTableInner .= "                 <td style='text-align: center; color: " . WHITE_DARK . ";'>20/02/10</td>";
        $strTableInner .= "                 <td><font face='Arial' size='2' color='" . WHITE_DARK . "'><ul><li>Spy reports support added</li><li>Upload statistics added</li></ul></font></td>";
        $strTableInner .= "             </tr>";

        $strTableInner .= "             <tr background='index_files/abox/row_1.png'>";
        $strTableInner .= "                 <td style='text-align: center; color: " . WHITE_DARK . ";'>15/02/10</td>";
        $strTableInner .= "                 <td><font face='Arial' size='2' color='" . WHITE_DARK . "'><ul><li>LogServer has won the battle!!!</li></ul></font></td>";
        $strTableInner .= "             </tr>";

        $strTableInner .= "             <tr background='index_files/abox/row_2.png'>";
        $strTableInner .= "                 <td style='text-align: center; color: " . WHITE_DARK . ";'>12/02/10</td>";
        $strTableInner .= "                 <td><font face='Arial' size='2' color='" . WHITE_DARK . "'><ul><li>List of compatible plugins</li></ul></font></td>";
        $strTableInner .= "             </tr>";

        $strTableInner .= "             <tr background='index_files/abox/row_1.png'>";
        $strTableInner .= "                 <td style='text-align: center; color: " . WHITE_DARK . ";'>11/02/10</td>";
        $strTableInner .= "                 <td><font face='Arial' size='2' color='" . WHITE_DARK . "'><ul><li>Search page updated</li><li>Plugin updated up to v1.3</li></ul></font></td>";
        $strTableInner .= "             </tr>";

        $strTableInner .= "             <tr background='index_files/abox/row_2.png'>";
        $strTableInner .= "                 <td style='text-align: center; color: " . WHITE_DARK . ";'>10/02/10</td>";
        $strTableInner .= "                 <td><font face='Arial' size='2' color='" . WHITE_DARK . "'><ul><li>Split-up fixed</li></ul></font></td>";
        $strTableInner .= "             </tr>";

        $strTableInner .= "             <tr background='index_files/abox/row_1.png'>";
        $strTableInner .= "                 <td style='text-align: center; color: " . WHITE_DARK . ";'>06/02/10</td>";
        $strTableInner .= "                 <td><font face='Arial' size='2' color='" . WHITE_DARK . "'><ul><li>Starting news publishing</li><li>New skin added: Chaos</li><li>Plugin updated up to v1.2</li></ul></font></td>";
        $strTableInner .= "             </tr>";

        $strTableInner .= "         </table></center>";
        $strTableInner .= "     </td></tr>";

        $strTableInner .= BotHtml();

        $strTableInner .= "     <tr>";
        $strTableInner .= "         <td id='submit_td' align='center' height='30' background='" . VISTA_PANEL . "' style='padding: 0'>";
        $strTableInner .= "         </td>";
        $strTableInner .= "     </tr>";

        ShowHTML($strTableInner, SERVER_NAME, false);
    }

    function ShowThx() {
        // Thx
        $strTableInner = "      <tr><td align='center'>";
        $strTableInner .= "         <font class='h2'>.:: Special thanks ::.</font>";
        $strTableInner .= "     </td></tr>";

        $strTableInner .= "     <tr><td align='left'>
                                    <font face='Arial' color='" . GREEN_LIGHT . "' size='2'><b>Nate River</b> </font><font face='Arial' color='" . GREEN_COMMON . "' size='2'>[Skyline designs]</font><font face='Arial' color='" . WHITE_DARK . "' size='2'> - developing, design</font><br>
                                    <font face='Arial' color='" . GREEN_LIGHT . "' size='2'><b>Serhio</b> </font><font face='Arial' color='" . GREEN_COMMON . "' size='2'>[Skyline designs]</font><font face='Arial' color='" . WHITE_DARK . "' size='2'> - developing</font><br>
                                    <font face='Arial' color='" . GREEN_LIGHT . "' size='2'><b>motorhead</b> </font><font face='Arial' color='" . GREEN_COMMON . "' size='2'>[LogServer]</font><font face='Arial' color='" . WHITE_DARK . "' size='2'> - server control</font><br>
                                    <font face='Arial' color='" . GREEN_LIGHT . "' size='2'><b>DominatoR</b> </font><font face='Arial' color='" . GREEN_COMMON . "' size='2'>[ogame.ru, uni18, Red Army]</font><font face='Arial' color='" . WHITE_DARK . "' size='2'> - logs sharing, testing, comments</font><br>
                                    <font face='Arial' color='" . GREEN_LIGHT . "' size='2'><b>Bontchev</b> </font><font face='Arial' color='" . GREEN_COMMON . "' size='2'>[bg.ogame.org, andromeda]</font><font face='Arial' color='" . WHITE_DARK . "' size='2'> - logs sharing, testing, comments, localization (Bulgarian)</font><br>
                                    <font face='Arial' color='" . GREEN_LIGHT . "' size='2'><b>Sanya (AlexT)</b> </font><font face='Arial' color='" . GREEN_COMMON . "' size='2'>[ogame.ru, uni18, Red Army]</font><font face='Arial' color='" . WHITE_DARK . "' size='2'> - localization (Ukrainian)</font><br>
                                    <font face='Arial' color='" . GREEN_LIGHT . "' size='2'><b>lombrounet</b> </font><font face='Arial' color='" . GREEN_COMMON . "' size='2'>[ogame.fr]</font><font face='Arial' color='" . WHITE_DARK . "' size='2'> - localization (French)</font><br>
                                    <font face='Arial' color='" . GREEN_LIGHT . "' size='2'><b>DozoR</b> </font><font face='Arial' color='" . GREEN_COMMON . "' size='2'>[ogame.ru]</font><font face='Arial' color='" . WHITE_DARK . "' size='2'> - design discord image</font><br>
                                    <br>
                                    <font face='Arial' color='" . ORANGE_COMMON . "' size='2'><b>Kain Ekin</b> </font><font face='Arial' color='" . GREEN_COMMON . "' size='2'>[board.ogame.ru]</font><font face='Arial' color='" . WHITE_DARK . "' size='2'> - ex`ogame operator</font><br>
                                    <font face='Arial' color='" . GREEN_LIGHT . "' size='2'><b>Asta</b> </font><font face='Arial' color='" . GREEN_COMMON . "' size='2'>[board.ogame.ru]</font><font face='Arial' color='" . WHITE_DARK . "' size='2'> - ex`forum moderator</font><br>
                                    <font face='Arial' color='" . GREEN_LIGHT . "' size='2'><b>Andorianin</b> </font><font face='Arial' color='" . GREEN_COMMON . "' size='2'>[board.ogame.ru]</font><font face='Arial' color='" . WHITE_DARK . "' size='2'> - ex`forum moderator</font><br>
                                    <font face='Arial' color='" . ORANGE_COMMON . "' size='2'><b>Taro</b> </font><font face='Arial' color='" . GREEN_COMMON . "' size='2'>[board.ogame.ru]</font><font face='Arial' color='" . WHITE_DARK . "' size='2'> - ex`ogame operator</font><br>
                                    <div class='blink'><font face='Arial' color='" . ORANGE_COMMON . "' size='2'><b>Illian</b> </font><font face='Arial' color='" . GREEN_COMMON . "' size='2'>[board.ogame.ru]</font><font face='Arial' color='" . WHITE_DARK . "' size='2'> - ex`operator</font></div><br>
                                    <br>
                                    <font face='Arial' color='" . WHITE_DARK . "' size='2'>...and all people who supported us:</font>
                                    <br>
                                    <table border='0'>
                                        <tr>
                                            <td valign='top'>
                                                <font face='Arial' color='" . GREEN_COMMON . "' size='2'>
                                                <font face='Arial' color='" . ORANGE_COMMON . "' size='2'>zZLO</font><br>
                                                trytodo<br>
                                                skyline_designes<br>
                                                S a n y a<br>
                                                Glorfindel66<br>
                                                Mc`OTLLIEJlHuK<br>
                                                Doctor Dre<br>
                                                Typ4uK<br>
                                                Hassel<br>
                                                bontchev<br>
                                                DominatoR13<br>
                                                sh1t<br>
                                                Altair<br>
                                                zelevar<br>
                                                mazik<br>
                                                paSHOK939<br>
                                                Dp_Xayc<br>
                                                XameL1on<br>
                                                Snoww<br>
                                                Serhio<br>
                                                </font>
                                            </td>
                                            <td width='10'></td>
                                            <td valign='top'>
                                                <font face='Arial' color='" . GREEN_COMMON . "' size='2'>
                                                Dmitriy RA<br>
                                                <font face='Arial' color='" . AQUA_COMMON . "' size='2'>B@SiLio</font><br>
                                                led-zeppelin<br>
                                                SolnishkO:)))<br>
                                                Tarja<br>
                                                rand<br>
                                                <font face='Arial' color='" . ORANGE_COMMON . "' size='2'>Andryusha</font><br>
                                                4-e-k-a<br>
                                                Ангелочек во Тьме<br>
                                                KaPaTeJIb<br>
                                                distance<br>
                                                poHenoe3D<br>
                                                bublik<br>
                                                Demon1<br>
                                                SLIM<br>
                                                virus troin<br>
                                                awe<br>
                                                silas<br>
                                                Enemu<br>
                                                konsai
                                                </font>
                                            </td>
                                            <td width='10'></td>
                                            <td valign='top'>
                                                <font face='Arial' color='" . GREEN_COMMON . "' size='2'>
                                                igiz0l<br>
                                                Sebesam<br>
                                                Imperator Timon<br>
                                                [_Arkantos_]<br>
                                                Tiger_13<br>
                                                Ohayo<br>
                                                Serg<br>
                                                нубасік<br>
                                                Всебесцветный<br>
                                                nic88888888<br>
                                                br_took<br>
                                                Lord_Nelson<br>
                                                <font face='Arial' color='" . ORANGE_COMMON . "' size='2'>~iZoTope~</font><br>
                                                Engine<br>
                                                Poligonomorf<br>
                                                TrueGodOfDeath<br>
                                                Klutch<br>
                                                IIa3uTuqp4uk<br>
                                                psaiker<br>
                                                AdreNaL1N<br>
                                                </font>
                                            </td>
                                            <td width='10'></td>
                                            <td valign='top'>
                                                <font face='Arial' color='" . GREEN_COMMON . "' size='2'>
                                                SPACECRAFTIK<br>
                                                nochziboorz31<br>
                                                KARATEL_13<br>
                                                4aynik<br>
                                                GLAMkuz<br>
                                                Strangerr<br>
                                                <font face='Arial' color='" . ORANGE_COMMON . "' size='2'>Наталинка</font><br>
                                                Last Exile<br>
                                                Letchik<br>
                                                Zolan<br>
                                                Derchaotischeprinz<br>
                                                Sopark<br>
                                                Angel1740<br>
                                                EuroService<br>
                                                sas_zar<br>
                                                Dуushа<br>
                                                Raider<br>
                                                Korgull<br>
                                                CTAKAH<br>
                                                AKTEP<br>
                                                </font>
                                            </td>
                                            <td width='10'></td>
                                            <td valign='top'>
                                                <font face='Arial' color='" . GREEN_COMMON . "' size='2'>
                                                nemo mow<br>
                                                Bergon<br>
                                                LFox<br>
                                                Zabiyaka<br>
                                                Lord_Infernos<br>
                                                JIAMEP<br>
                                                Fasd<br>
                                                Пулеметчик Джо<br>
                                                IH}I{EHEP<br>
                                                OP19<br>
                                                Xamit<br>
                                                iHy6<br>
                                                Snejik<br>
                                                nbIXApb<br>
                                                CzeCH<br>
                                                RedSadnessTint<br>
                                                Dracos<br>
                                                Gwyn Bleidd<br>
                                                shorkun<br>
                                                NoGame<br>
                                                </font>
                                            </td>
                                            <td width='10'></td>
                                            <td valign='top'>
                                                <font face='Arial' color='" . GREEN_COMMON . "' size='2'>
                                                KoMAR<br>
                                                <div class='blink'>KPACOTA</div><br>
                                                </font>
                                            </td>
                                        </tr>
                                    </table>
                                </td></tr>";

        $strTableInner .= BotHtml();

        $strTableInner .= "     <tr>";
        $strTableInner .= "         <td id='submit_td' align='center' height='30' background='" . VISTA_PANEL . "' style='padding: 0'>";
        $strTableInner .= "         </td>";
        $strTableInner .= "     </tr>";

        ShowHTML($strTableInner, SERVER_NAME, false);
    }

    function ShowBlackList() {
        $strTableInner .= '<link rel="stylesheet" href="index_files/timer/jquery.countdown.css" />';
        $strTableInner .= '<script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>';
        $strTableInner .= '<script src="index_files/timer/jquery.countdown.js"></script>';
        $strTableInner .= "     <tr><td align='center'>";
        $strTableInner .= "         <font class='h2'>.:: Black List ::.</font>";
        $strTableInner .= "     </td></tr>";

        $strTableInner .= "     <tr><td align='left'>
                                <div id='countdown'></div>
                                </td></tr>";

        $strTableInner .= BotHtml();

        $strTableInner .= "     <tr>";
        $strTableInner .= "         <td id='submit_td' align='center' height='30' background='" . VISTA_PANEL . "' style='padding: 0'>";
        $strTableInner .= "         </td>";
        $strTableInner .= "     </tr>";
        $strTableInner .= "<script>
                              $(function(){

                                    ts = new Date(2013, 8, 16);

                                $('#countdown').countdown({
                                    timestamp   : ts,
                                    callback    : function(days, hours, minutes, seconds){
                                    }
                                });

                              });
                            </script>";

        ShowHTML($strTableInner, SERVER_NAME, false);
    }

    function ShowSettings() {

        (isset($_COOKIE["index_cbx_public"]) && $_COOKIE["index_cbx_public"] == "false") ? ($strCbxPublic = "") : ($strCbxPublic = "checked");
        (isset($_COOKIE["index_cbx_hide_coord"]) && $_COOKIE["index_cbx_hide_coord"] == "false") ? ($strCbxHideCoord = "") : ($strCbxHideCoord = "checked");
        (isset($_COOKIE["index_cbx_hide_tech"]) && $_COOKIE["index_cbx_hide_tech"] == "false") ? ($strCbxHideTech = "") : ($strCbxHideTech = "checked");
        (isset($_COOKIE["index_cbx_hide_time"]) && $_COOKIE["index_cbx_hide_time"] == "false") ? ($strCbxHideTime = "") : ($strCbxHideTime = "checked");
        (isset($_COOKIE["index_cbx_comments"]) && $_COOKIE["index_cbx_comments"] == "true") ? ($strCbxComments = "checked") : ($strCbxComments = "");
        (isset($_COOKIE["index_cbx_aliance"]) && $_COOKIE["index_cbx_aliance"] == "false") ? ($strCbxAliance = "") : ($strCbxAliance = "checked");
        (isset($_COOKIE["index_cbx_top"]) && $_COOKIE["index_cbx_top"] == "false") ? ($strCbxTOP = "checked") : ($strCbxTOP = "");
        (isset($_COOKIE["option_select_f_b"]) && $_COOKIE["option_select_f_b"] == "false") ? ($strCbxFB = "") : ($strCbxFB = "checked");
        (isset($_COOKIE["exel"]) && $_COOKIE["exel"] == "true") ? ($strCbxExel = "checked") : ($strCbxExel = "");

        $strTableInner = "      <tr><td align='center'>";
        $strTableInner .= "         <font class='h2'>.:: Settings ::.</font>";
        $strTableInner .= "     </td></tr>";
        $strTableInner .= "     <tr>
                                    <td align='center'>
                                        <table>
                                            <tr background='index_files/abox/row_2.png'>
                                                <td align='center' width='350'>
                                                    <font color='" . WHITE_DARK . "' face='Arial' size='2'>" . Dictionary("option") . "</font>
                                                </td>
                                                <td align='center' width='200'>
                                                    <font color='" . WHITE_DARK . "' face='Arial' size='2'>" . Dictionary("setting") . "</font>
                                                </td>
                                                <td align='center' width='200'>
                                                    <font color='" . WHITE_DARK . "' face='Arial' size='2'>" . Dictionary("set") . "</font>
                                                </td>
                                            </tr>
                                            <tr background='index_files/abox/row_1.png'>
                                                <td align='left' width='350'>
                                                    <font color='" . WHITE_DARK . "' face='Arial' size='2'>Язык</font>
                                                </td>
                                                <td align='center' width='200'>
                                                    <select size='1' style='font-size: 10px; width: 120px;' onchange='" . JSCookie("lang", "\"+this.value+\"") . "; getOptionHtml(\"lang\", this.value); window.location.reload();'>
                                                    <option value='en'>English</option>
                                                    <option value='de'>German</option>
                                                    <option value='ru'>Russian</option>
                                                    <option value='bg'>Bulgarian</option>
                                                    <option value='ua'>Ukrainian</option>
                                                    </select>
                                                </td>
                                                <td align='center' width='200'>
                                                    <div id='lang' style='color:#009900'></div>
                                                </td>
                                            </tr>
                                            <tr background='index_files/abox/row_2.png'>
                                                <td align='left' width='350'>
                                                    <font color='" . WHITE_DARK . "' face='Arial' size='2'>" . Dictionary("public_log") . "</font>
                                                </td>
                                                <td align='center' width='200'>
                                                    <input type='checkbox' class='js-checkbox' value='true' $strCbxPublic onchange='" . JSCookie("index_cbx_public", "\"+this.checked+\"") . ";' onclick='getOptionHtml(\"index_cbx_public\");'>
                                                </td>
                                                <td align='center' width='200'>
                                                    <div id='index_cbx_public' style='color:#009900'></div>
                                                </td>
                                            </tr>
                                            <tr background='index_files/abox/row_1.png'>
                                                <td align='left' width='350'>
                                                    <font color='" . WHITE_DARK . "' face='Arial' size='2'>" . Dictionary("hide_techs") . "</font>
                                                </td>
                                                <td align='center' width='200'>
                                                    <input type='checkbox' class='js-checkbox' value='ON' $strCbxHideCoord onchange='" . JSCookie("index_cbx_hide_coord", "\"+this.checked+\"") . ";' onclick='getOptionHtml(\"index_cbx_hide_coord\");'>
                                                </td>
                                                <td align='center' width='200'>
                                                    <div id='index_cbx_hide_coord' style='color:#009900'></div>
                                                </td>
                                            </tr>
                                            <tr background='index_files/abox/row_2.png'>
                                                <td align='left' width='350'>
                                                    <font color='" . WHITE_DARK . "' face='Arial' size='2'>" . Dictionary("hide_coords") . "</font>
                                                </td>
                                                <td align='center' width='200'>
                                                    <input type='checkbox' class='js-checkbox' value='ON' $strCbxHideTech onchange='" . JSCookie("index_cbx_hide_tech", "\"+this.checked+\"") . ";' onclick='getOptionHtml(\"index_cbx_hide_tech\");'>
                                                </td>
                                                <td align='center' width='200'>
                                                    <div id='index_cbx_hide_tech' style='color:#009900'></div>
                                                </td>
                                            </tr>
                                            <tr background='index_files/abox/row_1.png'>
                                                <td align='left' width='350'>
                                                    <font color='" . WHITE_DARK . "' face='Arial' size='2'>" . Dictionary("hide_time") . "</font>
                                                </td>
                                                <td align='center' width='200'>
                                                    <input type='checkbox' class='js-checkbox' value='ON' $strCbxHideTime onchange='" . JSCookie("index_cbx_hide_time", "\"+this.checked+\"") . ";' onclick='getOptionHtml(\"index_cbx_hide_time\");'>
                                                </td>
                                                <td align='center' width='200'>
                                                    <div id='index_cbx_hide_time' style='color:#009900'></div>
                                                </td>
                                            </tr>
                                            <tr background='index_files/abox/row_2.png'>
                                                <td align='left' width='350'>
                                                    <font color='" . WHITE_DARK . "' face='Arial' size='2'>" . Dictionary("comments") . "</font>
                                                </td>
                                                <td align='center' width='200'>
                                                    <input type='checkbox' class='js-checkbox' value='ON' $strCbxComments onchange='" . JSCookie("index_cbx_comments", "\"+this.checked+\"") . ";' onclick='getOptionHtml(\"index_cbx_comments\");'>
                                                </td>
                                                <td align='center' width='200'>
                                                    <div id='index_cbx_comments' style='color:#009900'></div>
                                                </td>
                                            </tr>
                                            <tr background='index_files/abox/row_1.png'>
                                                <td align='left' width='350'>
                                                    <font color='" . WHITE_DARK . "' face='Arial' size='2'>" . Dictionary("display_alliances") . "</font>
                                                </td>
                                                <td align='center' width='200'>
                                                    <input type='checkbox' class='js-checkbox' value='ON' $strCbxAliance onchange='" . JSCookie("index_cbx_aliance", "\"+this.checked+\"") . ";' onclick='getOptionHtml(\"index_cbx_aliance\");'>
                                                </td>
                                                <td align='center' width='200'>
                                                    <div id='index_cbx_aliance' style='color:#009900'></div>
                                                </td>
                                            </tr>
                                            <tr background='index_files/abox/row_2.png'>
                                                <td align='left' width='350'>
                                                    <font color='" . WHITE_DARK . "' face='Arial' size='2'>" . Dictionary("display_top") . "</font>
                                                </td>
                                                <td align='center' width='200'>
                                                    <input type='checkbox' class='js-checkbox' value='ON' $strCbxTOP onchange='" . JSCookie("index_cbx_top", "\"+this.checked+\"") . ";' onclick='getOptionHtml(\"index_cbx_top\");'>
                                                </td>
                                                <td align='center' width='200'>
                                                    <div id='index_cbx_top' style='color:#009900'></div>
                                                </td>
                                            </tr>
                                            <tr background='index_files/abox/row_1.png'>
                                                <td align='left' width='350'>
                                                    <font color='" . WHITE_DARK . "' face='Arial' size='2'>Предыдущий/Следующий лог</font>
                                                </td>
                                                <td align='center' width='200'>
                                                    <input type='checkbox' class='js-checkbox' value='Off' $strCbxFB onchange='" . JSCookie("option_select_f_b", "\"+this.checked+\"") . ";' onclick='getOptionHtml(\"option_select_f_b\");'>
                                                </td>
                                                <td align='center' width='200'>
                                                    <div id='option_select_f_b' style='color:#009900'></div>
                                                </td>
                                            </tr>";
        if (isset($_SESSION['account']['id']) && $_SESSION['account']['id'] > 0) $strTableInner .= "<tr background='index_files/abox/row_2.png'>
                                                <td align='left' width='350'>
                                                    <font color='" . WHITE_DARK . "' face='Arial' size='2'>" . Dictionary("acc_list") . "</font>
                                                </td>
                                                <td align='center' width='200'>
                                                    <select size='1' style='font-size: 10px; width: 120px;' onchange='" . JSCookie("option_select_logs", "\"+this.value+\"") . "; getOptionHtml(\"option_select_logs\", this.value);'>
                                                    <option value='10'>10</option>
                                                    <option value='15'>15</option>
                                                    <option value='20'>20</option>
                                                    <option value='25'>25</option>
                                                    </select>
                                                </td>
                                                <td align='center' width='200'>
                                                    <div id='option_select_logs' style='color:#009900'></div>
                                                </td>
                                            </tr>
                                <script>$(document).ready(function() {
                                    $('#update_logs').click(function() {
                                        $('#update_logs').prop('disabled', true);
                                        $('#result_update_logs').html('<img src=\'index_files/ajax-loader.gif\'>');
                                        $.ajax({
                                            url: 'h_ajax.php?page=updatelogs',
                                            cache: false,
                                            success: function(response){
                                                if (response) {
                                                    $('#result_update_logs').html(response);
                                                } else {
                                                    $('#result_update_logs').html('<span style=\'color: #FF0000\'>Error</span>');
                                                    $('#update_logs').prop('disabled', false);
                                                }
                                            }
                                        });
                                    });
                              });</script>
                                            <tr background='index_files/abox/row_1.png'>
                                                <td align='left' width='350'>
                                                    <font color='" . WHITE_DARK . "' face='Arial' size='2'>Обновить список логов</font>
                                                </td>
                                                <td align='center'>
                                                    <input id='update_logs' type='button' value='Обновить'>
                                                </td>
                                                <td align='center'>
                                                    <div id='result_update_logs' style='color:#009900'></div>
                                                </td>
                                            </tr>";
        $strTableInner .= "                 <tr background='index_files/abox/row_2.png'>
                                                <td align='left' width='350'>
                                                    <font color='" . WHITE_DARK . "' face='Arial' size='2'>" . Dictionary("most_pop_logs") . "</font>
                                                </td>
                                                <td align='center' width='200'>
                                                    <select size='1' style='font-size: 10px; width: 120px;' onchange='" . JSCookie("option_select_p_logs", "\"+this.value+\"") . "; getOptionHtml(\"option_select_p_logs\", this.value);'>
                                                    <option value='10'>10</option>
                                                    <option value='15'>15</option>
                                                    <option value='20'>20</option>
                                                    <option value='25'>25</option>
                                                    </select>
                                                </td>
                                                <td align='center' width='200'>
                                                    <div id='option_select_p_logs' style='color:#009900'></div>
                                                </td>
                                            </tr>
                                            <tr background='index_files/abox/row_1.png'>
                                                <td align='left' width='350'>
                                                    <font color='" . WHITE_DARK . "' face='Arial' size='2'>Информационная база данных на игрока</font>
                                                </td>
                                                <td align='center' width='200'>
                                                    <select size='1' style='font-size: 10px; width: 120px;' onchange='" . JSCookie("option_select_search", "\"+this.value+\"") . "; getOptionHtml(\"option_select_search\", this.value);'>
                                                    <option value='Infuza.com'>Infuza.com</option>
                                                    <option value='Ogniter.org'>Ogniter.org</option>
                                                    <option value='OpenGalaxy'>OpenGalaxy</option>
                                                    <option value='Ogame-Pb.net'>Ogame-Pb.net</option>
                                                    </select>
                                                </td>
                                                <td align='center' width='200'>
                                                    <div id='option_select_search' style='color:#009900'></div>
                                                </td>
                                            </tr>
                                            <tr background='index_files/abox/row_2.png'>
                                                <td align='left' width='350'>
                                                    <font color='" . WHITE_DARK . "' face='Arial' size='2'>Включить маскировку</font>
                                                </td>
                                                <td align='center' width='200'>
                                                    <input type='checkbox' class='js-checkbox' value='Off' $strCbxExel onchange='" . JSCookie("exel", "\"+this.checked+\"") . ";' onclick='getOptionHtml(\"exel\");'>
                                                </td>
                                                <td align='center' width='200'>
                                                    <div id='exel' style='color:#009900'></div>
                                                </td>
                                            </tr>                                            
                                        </table>
                                    </td>
                                </tr>";
        //xxx
        $strTableInner .= "     <script>function getOptionSelect(a){var b=document.getElementById(a);b&&(html_select=getCookie(a),b.innerHTML=html_select?html_select:'" . Dictionary("default") . "')};";
        $strTableInner .= "     getOptionSelect('lang');";
        $strTableInner .= "     getOptionSelect('index_cbx_public');";
        $strTableInner .= "     getOptionSelect('index_cbx_hide_coord');";
        $strTableInner .= "     getOptionSelect('index_cbx_hide_tech');";
        $strTableInner .= "     getOptionSelect('index_cbx_hide_time');";
        $strTableInner .= "     getOptionSelect('index_cbx_comments');";
        $strTableInner .= "     getOptionSelect('index_cbx_aliance');";
        $strTableInner .= "     getOptionSelect('index_cbx_top');";
        $strTableInner .= "     getOptionSelect('option_select_logs');";
        $strTableInner .= "     getOptionSelect('option_select_f_b');";
        $strTableInner .= "     getOptionSelect('option_cbx_alt_logs');";
        $strTableInner .= "     getOptionSelect('option_select_p_logs');";
        $strTableInner .= "     getOptionSelect('option_select_search');";
        $strTableInner .= "     getOptionSelect('exel');";
        $strTableInner .= "     </script>";

        $strTableInner .= BotHtml();

        $strTableInner .= "     <tr>";
        $strTableInner .= "         <td id='submit_td' align='center' height='30' background='" . VISTA_PANEL . "' style='padding: 0'>";
        $strTableInner .= "         </td>";
        $strTableInner .= "     </tr>";

        ShowHTML($strTableInner, SERVER_NAME, false);
    }

    function ShowPluginInfo() {
        /*$strGM = "http://www.greasespot.net";
        $strUS = "http://userscripts.org/scripts/show/69560";
        $strLGMS = str_replace("index.php", "", LOGSERVERURL) . "plugin/_logserver_gms_combatreport.user.js";

        $strPluginInfo = Dictionary("plugin_info_xml");
        $strPluginInfo = str_replace("<text>", "<font face='Arial' color='" . WHITE_DARK . "' size='2'>", $strPluginInfo);
        $strPluginInfo = str_replace("</text>", "</font><br>", $strPluginInfo);
        $strPluginInfo = str_replace("<url_greasemonkey />", "<a href='" . $strGM . "' target='_blank'>" . $strGM . "</a>", $strPluginInfo);
        $strPluginInfo = str_replace("<url_logserver_greasemonkey_script />", "<a href='" . $strUS . "' target='_blank'>" . $strUS . "</a> / <a href='" . $strLGMS . "' target='_blank'>" . $strLGMS . "</a>", $strPluginInfo);
        $strPluginInfo = str_replace("Firefox", "<font color='" . ORANGE_COMMON . "'>Firefox</font>", $strPluginInfo);
        $strPluginInfo = str_replace("Greasemonkey", "<font color='" . ORANGE_COMMON . "'>Greasemonkey</font>", $strPluginInfo);
        $strPluginInfo = str_replace("LogServer GM script", "<font color='" . ORANGE_COMMON . "'>LogServer GM script</font>", $strPluginInfo);*/

        function CreateList($varName, $varDownload, $varInstall, $varWebsite, $varNote) {
            $strPluginInfo = "";
            $strPluginInfo .= " <tr background='" . VISTA_PANEL . "'>";
            $varNote = ($varNote) ? $varNote : "";
            $strPluginInfo .= "     <td align='center' height='30' width='50' style='padding: 0; cursor: pointer;' onClick='location.href=\"#note\"'>" . $varNote . "</td>";
            $strPluginInfo .= "     <td class='pluginShow' show='show_" . preg_replace("/[^a-zа-я0-9]/i", "_", $varName) . "' id='" . $varName . "' align='left' height='30' style='padding: 0; cursor: pointer;'><font face='Arial' color='#CCCCCC' size='3'>&nbsp;&nbsp;" . $varName . "</font></td>";
            $urlDownload = ($varDownload) ? "[<a href='" . $varDownload . "' target='_blank'>Download</a>]" : "";
            $strPluginInfo .= "     <td align='center' height='30' width='100' style='padding: 0'>" . $urlDownload . "</td>";
            $urlInstall =  ($varInstall) ? "[<a href='javascript:void(0)' class='pluginInstall' name='" . $varName . "' show='show_" . preg_replace("/[^a-zа-я0-9]/i", "_", $varName) . "'>Install</a>]" : "";
            $strPluginInfo .= "     <td align='center' height='30' width='100' style='padding: 0'>" . $urlInstall . "</td>";
            $urlWebsite =  ($varWebsite) ? "[<a href='" . $varWebsite . "' target='_blank'>Website</a>]" : "";
            $strPluginInfo .= "     <td align='center' height='30' width='100' style='padding: 0'>" . $urlWebsite . "</td>";
            $strPluginInfo .= " </tr>";
            $strPluginInfo .= " <tr><td colspan='5'><div id='show_" . preg_replace("/[^a-zа-я0-9]/i", "_", $varName) . "'></div></tr>";
            return $strPluginInfo;
        }
        //General Addons & Scripts
        $strPluginInfo = "<div id='pluginContent'></div>";
        $strPluginInfo .= "<script type='text/javascript'>
                            $(document).ready(function(){
                                function ajaxScript(page, name, id){
                                    $('#'+id).html('<center><img src=\'index_files/ajax-loader.gif\'></center>');
                                    $.ajax({
                                        url: 'h_script.php',
                                        type: 'GET',
                                        data: {'page': page,
                                               'name': name
                                        },
                                        cache: false,
                                        success: function(response){
                                            if (response){
                                                $('#'+id).html(response);
                                            }
                                        }
                                    });
                                }
                                $('.pluginInstall').click(function(){
                                    ajaxScript('instalList', this.name, this.getAttribute('show'));
                                });
                                $('.pluginShow').click(function(){
                                    ajaxScript('showList', this.id, this.getAttribute('show'));
                                });
                            });
                            </script>";

        $strPluginInfo .= "<table width='800'>";
        $strPluginInfo .= CreateList("Greasemonkey (Firefox)", false, false, "https://addons.mozilla.org/ru/firefox/addon/greasemonkey/", false, false);
        $strPluginInfo .= CreateList("Tampermonkey (Google Chrome)", false, false, "https://chrome.google.com/webstore/detail/tampermonkey/dhdgffkkebhmkfjojejmpbldmpobfkfo?hl=ru", false, false);
        $strPluginInfo .= "</table>";
        $strTableInner = "      <tr><td align='center'>
                                    <font class='h2'>Addons for scripts</font>
                                </td></tr>";
        $strTableInner .= "     <tr><td align='left' style='padding-top: 0'>
                                    $strPluginInfo
                                </td></tr>";

        $strPluginInfo = "<table width='800'>";
        $strPluginInfo .= CreateList("LogServer.net GM script v.3.4.1", "https://logserver.net/plugin/LogServer.net_GM_script.user.js", false, false, false);
        $strPluginInfo .= "</table>";
        $strTableInner .= "     <tr><td align='center'>
                                    <font class='h2'>LogServer.net scripts</font>
                                </td></tr>";
        $strTableInner .= "     <tr><td align='left' style='padding-top: 0'>
                                    $strPluginInfo
                                </td></tr>";

        $strPluginInfo = "<table width='800'>";
        $strPluginInfo .= CreateList("Antigame Origin", "http://antigame.de/home.php?page=download", false, "http://antigame.de/", false, false);
        $strPluginInfo .= CreateList("Galaxytoolbar", "https://addons.mozilla.org/en-US/firefox/addon/8588/", false, false, false);
        $strPluginInfo .= CreateList("InfoCompte 3", false, true, false, false);
        $strPluginInfo .= CreateList("Skynet", "https://addons.mozilla.org/de/firefox/addon/skynet-1", false, "http://www.martin-burchard.de", false, false);
        $strPluginInfo .= "</table>";
        $strTableInner .= "     <tr><td align='center'>
                                    <font class='h2'>General Addons & Scripts</font>
                                </td></tr>";
        $strTableInner .= "     <tr><td align='left' style='padding-top: 0'>
                                    $strPluginInfo
                                </td></tr>";
        //Addons, Scripts & Bugfixes
        $strPluginInfo = "";
        $strPluginInfo .= "<table width='800'>";
        $strPluginInfo .= CreateList("Activity Indicator", false, true, false, "<font size='-2' color='#FFFF33'>OUT OF DATE</font>");
        $strPluginInfo .= CreateList("Activitiy star", false, true, false, false);
        $strPluginInfo .= CreateList("Additional Resource Loading Buttons", false, true, false, "<font size='-2' color='#FFFF33'>OUT OF DATE</font>");
        $strPluginInfo .= CreateList("Alliance Chat", false, true, false, false);
        $strPluginInfo .= CreateList("Alliance icon opens the message box", false, true, false, false);
        $strPluginInfo .= CreateList("Alliance Stat", false, true, false, false);
        $strPluginInfo .= CreateList("Antigame OSimulate Mod", "http://antigame-osimulate.user.js", false, "http://forum.osimulate.com/viewforum.php?f=16", "<font size='-2' color='#FF0000'>NOT WORK</font>");
        $strPluginInfo .= CreateList("Art Galaxy", false, true, false, false);
        $strPluginInfo .= CreateList("Auction events list", false, true, false, false);
        $strPluginInfo .= CreateList("Auction Timer", false, true, false, "<font size='-2' color='#00CC00'>FIXED</font>");
        $strPluginInfo .= CreateList("Available fields", false, true, false, false);

        $strPluginInfo .= CreateList("Cargos necessary", false, true, false, false);
        $strPluginInfo .= CreateList("CerealOgameStats", false, true, false, false);
        $strPluginInfo .= CreateList("Colored Moon Sizes in Galaxy View", false, true, false, false);
        $strPluginInfo .= CreateList("CR Converter in ogame page", "http://olderogamers.altervista.org/utility/plugin/CCREN.user.js", false, false, "<font size='-2' color='#FF0000'>NOT WORK</font>");
        $strPluginInfo .= CreateList("Color Alliance", false, true, false, false);
        $strPluginInfo .= CreateList("Color Friends", false, true, false, false);
        $strPluginInfo .= CreateList("Color Flight Flots", false, true, false, false);

        $strPluginInfo .= CreateList("Direct Colonization", false, true, false, false);
        $strPluginInfo .= CreateList("Disable attack warner", false, true, false, false);
        $strPluginInfo .= CreateList("Disable Espionage if Colonization is Available", false, true, false, false);
        $strPluginInfo .= CreateList("Display Resources", false, true, false, false);
        $strPluginInfo .= CreateList("Defense Proposer", false, true, false, false);

        $strPluginInfo .= CreateList("Easy Rider", false, true, false, false);
        $strPluginInfo .= CreateList("Easy Transport", false, true, false, "<font size='-2' color='#FFFF33'>OUT OF DATE</font>");
        $strPluginInfo .= CreateList("ECA-MEF : Script formatting your Ogame empire for forums", false, false, "http://ogame.david-m.fr/empire.html", false, false);
        $strPluginInfo .= CreateList("Espionage report attack button", false, true, false, false);
        $strPluginInfo .= CreateList("Expeditions statistics", false, true, false, false);
        $strPluginInfo .= CreateList("Expo Stats", false, true, false, false);

        $strPluginInfo .= CreateList("Fix the Action Icons", false, true, false, false);
        $strPluginInfo .= CreateList("Fleetpoints", false, true, false, false);
        $strPluginInfo .= CreateList("Fleet Contents", false, true, false, "<font size='-2' color='#FFFF33'>OUT OF DATE</font>");
        $strPluginInfo .= CreateList("Fleet Empty Space", false, true, false, false);
        $strPluginInfo .= CreateList("Fleet escape Calculator", "http://www.mediafire.com/?vfz6kstm1yk56bf", false, false, false);
        $strPluginInfo .= CreateList("Fleet Proposer", false, true, false, false);
        $strPluginInfo .= CreateList("Fleet strength calculator", false, true, false, false);
        $strPluginInfo .= CreateList("Fix the coordinates links", false, true, false, false);

        $strPluginInfo .= CreateList("Galaxy Go", false, true, false, false);
        $strPluginInfo .= CreateList("Galaxy Info User", false, true, false, false);
        $strPluginInfo .= CreateList("Glotr", "http://hynner.github.com/glotr/", false, "http://hynner.github.com/glotr/", false, false);

        $strPluginInfo .= CreateList("Highscore improved", false, true, false, false);

        $strPluginInfo .= CreateList("Infocompte CR Converter", "http://www.projet-alternative.fr/AlTools/upraid.php", false, "http://vulca.projet-alternative.fr/infoCompte/index.php?page=upraid", false, false);

        $strPluginInfo .= CreateList("IRC Webchat module", false, true, false, false);

        $strPluginInfo .= CreateList("Keyboard Shortcuts", false, true, false, false);

        $strPluginInfo .= CreateList("Loots bbcode exporter", false, true, false, false);
        $strPluginInfo .= CreateList("Links for expedition and colonization", false, true, false, false);

        $strPluginInfo .= CreateList("Merchant Warning", false, true, false, false);
        $strPluginInfo .= CreateList("Message button in left menu", false, true, false, false);
        $strPluginInfo .= CreateList("Mine optimization", false, false, "http://www.apinx.dk/ogame_public/", false, false);
        $strPluginInfo .= CreateList("Missing Sats", false, true, false, "<font size='-2' color='#FFFF33'>OUT OF DATE</font>");
        $strPluginInfo .= CreateList("Moons to the Right", false, true, false, "<font size='-2' color='#FFFF33'>OUT OF DATE</font>");

        $strPluginInfo .= CreateList("No tactical retreat tip", false, true, false, false);
        $strPluginInfo .= CreateList("Odd save report", false, true, false, "<font size='-2' color='#FF0000'>NOT WORK</font>");
        $strPluginInfo .= CreateList("OGame find player details", false, true, false, false);
        $strPluginInfo .= CreateList("OGame Fleet Tool", false, true, false, false);
        $strPluginInfo .= CreateList("Ogame Notepad", false, false, "https://chrome.google.com/webstore/detail/gihfdpdkenhfijllbhhfifieckihlffh", false, false);
        $strPluginInfo .= CreateList("ODB - Ogniter", false, false, "http://www.ogniter.org/", false, false);
        $strPluginInfo .= CreateList("OgAPI", "http://home.arcor.de/brendelj/OgAPI.zip", false, false, false);
        $strPluginInfo .= CreateList("OPA (Ogame Pratico per Android)", "http://www.mediafire.com/?116y6v7uhxjwm28", false, "http://olderogamers.altervista.org/utility/", false, false);
        $strPluginInfo .= CreateList("Options in User Name", false, true, false, false);
        $strPluginInfo .= CreateList("Old menu fleet", false, true, false, false);
        $strPluginInfo .= CreateList("Open Galaxy - public galaxy map", false, true, false, "<font size='-2' color='#FF0000'>NOT WORK</font>");
        $strPluginInfo .= CreateList("Oprojekt exporter bbcode formatted text", false, true, "http://mines.oprojekt.net/", false, false);

        $strPluginInfo .= CreateList("[phpBB] OGame-Mod", "https://github.com/Un1matr1x/phpbb3-mod-OGame/zipball/0.2.3", false, "http://un1matr1x.de/viewtopic.php?p=2031#p2031", false, false);
        $strPluginInfo .= CreateList("Perfect Plunder", false, true, false, false);
        $strPluginInfo .= CreateList("Rankings in tooltip", false, true, false, false);
        $strPluginInfo .= CreateList("Resources on Transit", false, true, false, false);
        $strPluginInfo .= CreateList("Resources in Flight", false, true, false, false);

        $strPluginInfo .= CreateList("Search Players Coordinates", false, true, false, false);
        $strPluginInfo .= CreateList("Small planets", false, true, false, false);
        $strPluginInfo .= CreateList("Smilies", false, true, false, false);
        $strPluginInfo .= CreateList("Script to adapt speedsim and Dragosim", false, true, false, false);
        $strPluginInfo .= CreateList("SpyShare", false, true, false, false);

        $strPluginInfo .= CreateList("The All-seeing eye", "http://sourceforge.net/projects/theallseeingeye/", false, false, false);
        $strPluginInfo .= CreateList("Time left to fill up storage", false, true, false, false);
        $strPluginInfo .= CreateList("Trade Calculator", false, true, false, false);

        $strPluginInfo .= CreateList("UniverseView Script", "http://universeview.be/?p=download", false, "http://universeview.be/", false, false);

        $strPluginInfo .= CreateList("Warning about last fleet slot", false, true, false, false);
        $strPluginInfo .= CreateList("War Riders Extended", false, true, false, false);
        $strPluginInfo .= CreateList("Websim Extension", false, true, false, false);
        $strPluginInfo .= "</table>";
        $strTableInner .= "     <tr><td align='center'>
                                    <font class='h2'>Addons, Scripts & Bugfixes</font>
                                </td></tr>";
        $strTableInner .= "     <tr><td align='left' style='padding-top: 0'>
                                    $strPluginInfo
                                </td></tr>";
        //Addons, Scripts & Bugfixes Board
        $strPluginInfo = "";
        $strPluginInfo .= "<table width='800'>";
        $strPluginInfo .= CreateList("OGame Board Improvments", false, "http://userscripts.org/scripts/show/84178", false, false);
        $strPluginInfo .= "</table>";
        $strTableInner .= "     <tr><td align='center'>
                                    <font class='h2'>Addons, Scripts & Bugfixes Board</font>
                                </td></tr>";
        $strTableInner .= "     <tr><td align='left' style='padding-top: 0'>
                                    $strPluginInfo
                                </td></tr>";
        //Tools
        $strPluginInfo = "";
        $strPluginInfo .= "<table width='800'>";
        $strPluginInfo .= CreateList("Share profit after ACS atac", "http://snaquekiller.free.fr/ogame/tableau.rar", false, "http://snaquekiller.free.fr/ogame/tableau.html", false);
        $strPluginInfo .= CreateList("Share profit after ACS defense", "http://snaquekiller.free.fr/ogame/script/tableau_dg_partage_complet.rar", false, "http://snaquekiller.free.fr/ogame/script/tableau_dg_partage.html", false);
        $strPluginInfo .= CreateList("ACS Profit divider windows application 32bit", "http://sourceforge.net/projects/acspftdvdr/", false, "http://sourceforge.net/projects/acspftdvdr/", false);
        $strPluginInfo .= CreateList("Anti-ninja calculator & Reminder windows application 32bit", "http://sourceforge.net/projects/antininjarmndr/", false, "http://sourceforge.net/projects/antininjarmndr/", false);
        $strPluginInfo .= CreateList("Apportionment resource after ACS", "http://snaquekiller.free.fr/ogame/tableau.rar", false, "http://snaquekiller.free.fr/ogame/tableau.html", false);
        $strPluginInfo .= CreateList("Auctions - Script for easy trade.", false, false, "http://ogame.veygr.pl/aukcje/", false);
        $strPluginInfo .= CreateList("Calculator", "https://dl.dropboxusercontent.com/u/26173055/CalculadoraOgame.exe", false, false, false);
        $strPluginInfo .= CreateList("CIAP Statistics", false, false, "http://www.przyrostyogame.cba.pl/t.html", false);
        $strPluginInfo .= CreateList("CR Converter", false, false, "http://crconv.dejma.net/?FS_lang=en", false);
        $strPluginInfo .= CreateList("CR-Hosting", false, false, "http://kb.un1matr1x.de/", false);
        $strPluginInfo .= CreateList("Cumulative Cost", false, false, "http://calc.antigame.de/", false);
        $strPluginInfo .= CreateList("DragoSim", false, false, "http://drago-sim.com/", false);
        $strPluginInfo .= CreateList("Expeditions Calculator", false, false, "http://www.17buddies.net/ogame/expe.htm", false);
        $strPluginInfo .= CreateList("Feed Commander (iPhone RSS Reader)", "https://itunes.apple.com/de/app/feed-commander/id645288704?mt=8", false, "http://www.feedcommander.de/", false);
        $strPluginInfo .= CreateList("Fleet & defence converter win app 32bit", "http://sourceforge.net/projects/fltndfnccnvrtr/", false, "http://sourceforge.net/projects/fltndfnccnvrtr/", false);
        $strPluginInfo .= CreateList("Fleetsize - Tool", false, false, "http://fleetsize.altervista.org/", false);
        $strPluginInfo .= CreateList("Galaxytool hosting", false, false, "http://hosting.ogamecentral.com/", false);
        $strPluginInfo .= CreateList("Gamestats", false, false, "http://ogame.gamestats.org/", false);
        $strPluginInfo .= CreateList("Geologist windows application 32bit", false, false, "http://sourceforge.net/projects/glgst/", false);
        $strPluginInfo .= CreateList("History of changes", false, false, "http://dsgbeard.cba.pl/", false);
        $strPluginInfo .= CreateList("Kopernik system", false, false, "http://ogame.kopernik.idl.pl/", false);
        $strPluginInfo .= CreateList("Moon vs Death Star", "http://sourceforge.net/projects/moonvsds/", false, "http://sourceforge.net/projects/moonvsds/", false);
        $strPluginInfo .= CreateList("New OGame tools collection started", false, false, "http://proxyforgame.com/", "<font size='-2' color='#FF0000'>NOT WORK</font>", false);
        $strPluginInfo .= CreateList("Next Gen Ogame Battle Simulator", false, false, "http://www.osimulate.com/", false);
        $strPluginInfo .= CreateList("O-Calc", false, false, "http://o-calc.com/", false);
        $strPluginInfo .= CreateList("O-Tools", false, false, "http://www.ghiroblu.com/o-tools/en/", false);
        $strPluginInfo .= CreateList("Ogame RSS Reader", "http://ogame.timestorm.de/download/", false, false, false);
        $strPluginInfo .= CreateList("OGameTools.com", false, false, "http://www.ogametools.com/", false);
        $strPluginInfo .= CreateList("Ogameadviser", false, false, "http://www.ogameadviser.net/", false);
        $strPluginInfo .= CreateList("OgDroid (Ogame client for Android)", "https://market.android.com/details?id=com.jejedroid.androgamebeta", false, false, false);
        $strPluginInfo .= CreateList("Optifleet", "https://sourceforge.net/projects/optifleet/files/", false, "http://sourceforge.net/projects/optifleet/", false);
        $strPluginInfo .= CreateList("OGotcha CR converter", false, false, "http://converter.dijkman-winters.nl/", false);
        $strPluginInfo .= CreateList("OGSpy", "https://bitbucket.org/ogsteam/ogspy/downloads", false, "http://www.ogsteam.fr/", false);
        $strPluginInfo .= CreateList("OProjekt", false, false, "http://mines.oprojekt.net/", false);
        $strPluginInfo .= CreateList("Phalanx Timer", "http://www.exg-clan.com/web/index.php?site=files&file=47", false, false, false);
        $strPluginInfo .= CreateList("Projet AlTernative", false, false, "http://www.projet-alternative.fr/", false);
        $strPluginInfo .= CreateList("PwnGameReader - Android OGame RSS Reader", false, false, "http://duschkumpane.org/index.php/ogame", false);
        $strPluginInfo .= CreateList("Resource converter windows application 32bit", "http://sourceforge.net/projects/rsrccnvrtr/", false, "http://sourceforge.net/projects/rsrccnvrtr/", false);
        $strPluginInfo .= CreateList("SaveCR", false, false, "http://en.savecr.com/", false);
        $strPluginInfo .= CreateList("SaveKB", false, false, "http://www.savekb.de/", false);
        $strPluginInfo .= CreateList("SpeedSim", "http://www.speedsim.net/index.php?page=downloads", false, "http://www.speedsim.net/", false);
        $strPluginInfo .= CreateList("Trade Terminal", false, false, "http://ogamespec.com/tools/trade.php", false);
        $strPluginInfo .= CreateList("World Ogame Stats of Players and Alliances", false, false, "http://www.infuza.com/", false);
        $strPluginInfo .= "</table>";
        $strTableInner .= "     <tr><td align='center'>
                                    <font class='h2'>Tools</font>
                                </td></tr>";
        $strTableInner .= "     <tr><td align='left' style='padding-top: 0'>
                                    $strPluginInfo
                                </td></tr>";
        //Skins
        $strPluginInfo = "";
        $strPluginInfo .= "<table width='800'>";
        $strPluginInfo .= CreateList("Magistorm V5", false, false, false, false);
        $strPluginInfo .= "</table>";
        $strTableInner .= "     <tr><td align='center'>
                                    <font class='h2'>Skins</font>
                                </td></tr>";
        $strTableInner .= "     <tr><td align='left' style='padding-top: 0'>
                                    $strPluginInfo
                                </td></tr>";

        $strTableInner .= "     <tr><td align='left' style='padding-top: 0'>";
        $strTableInner .= "         <table width='800' id='note'>";
        $strTableInner .= "             <tr height='30'><td align='center' width='50'><font size='-2' color='#FF0000'>NOT WORK</font></td><td><font color='#DDDDDD'> - не работает</font></td></tr>";
        $strTableInner .= "             <tr height='30'><td align='center' width='50'><font size='-2' color='#FFFF33'>OUT OF DATE</font></td><td><font color='#DDDDDD'> - неактуальный, реализован в игре или в Antigame Origin</font></td></tr>";
        $strTableInner .= "             <tr height='30'><td align='center' width='50'><font size='-2' color='#00CC00'>FIXED</font></td><td><font color='#DDDDDD'> - исправлен</font></td></tr>";
        $strTableInner .= "         </table>";
        $strTableInner .= "     </td></tr>";

        /*$strTableInner .= "       <tr><td align='left' style='padding-top: 0'>
                                    <font face='Arial' color='" . YELLOW_COMMON . "' size='2'>Notice: </font><font face='Arial' color='" . WHITE_DARK . "' size='2'>Plugin works in Opera browser as well as in Firefox, see instruction how to add scripts in Opera</font>
                                </td></tr>";

        $strTableInner .= "     <tr>
                                    <td align='left' style='padding-top: 0'>
                                        <font face='Arial' color='" . WHITE_DARK . "' size='2'>Greasemonkey web page:</font>
                                        <a href='" . $strGM . "' target='_blank'><img src='" . GM_LOGO . "' border='0' width='780' alt='Greasemonkey'></a>
                                    </td>
                                </tr>";

        $strTableInner .= "     <tr>
                                    <td align='left' style='padding-top: 0'>
                                        <font face='Arial' color='" . WHITE_DARK . "' size='2'>Plugin published on userscripts.org:</font>
                                        <a href='" . $strUS . "' target='_blank'><img src='" . US_LOGO . "' border='0' width='780' alt='LogServer.net GM script for OGame'></a>
                                    </td>
                                </tr>";*/

        $strTableInner .= BotHtml();

        $strTableInner .= "     <tr>";
        $strTableInner .= "         <td id='submit_td' align='center' height='30' background='" . VISTA_PANEL . "' style='padding: 0'>";
        $strTableInner .= "         </td>";
        $strTableInner .= "     </tr>";

        ShowHTML($strTableInner, SERVER_NAME, false);
    }

    function ShowLostpw($intFlag) {
        $strTableInner = "      <tr><td align='center'>";
        $strTableInner .= "         <font class='h2'>.:: Lost Password ::.</font>";
        $strTableInner .= "     </td></tr>";

        $strTableInnerMsg = '';
        $objDlgWnd = new cDlgWnd();
        if (isset($intFlag))
            switch ($intFlag) {
                case "ERR_LOSTPSW":
                    $strTableInnerMsg = str_replace('display: none; position', 'display: block; position', $objDlgWnd->CreateDlgHTML("error_1", Dictionary("error"), "
                        Error: not mail or code.
                        <br><br>
                        <font color=" . RED_COMMON . ">Try again and check data you entered.</font>
                        "));
                    break;
                case "ERR_LOSTPSW_MAIL":
                    $strTableInnerMsg = str_replace('display: none; position', 'display: block; position', $objDlgWnd->CreateDlgHTML("error_2", Dictionary("error"), "
                        Error: wrong mail.
                        <br><br>
                        <font color=" . RED_COMMON . ">Try again and check data you entered.</font>
                        "));
                    break;
                case "ERR_LOSTPSW_MAIL_DB":
                    $strTableInnerMsg = str_replace('display: none; position', 'display: block; position', $objDlgWnd->CreateDlgHTML("error_2", Dictionary("error"), "
                        Error: mail not found.
                        <br><br>
                        <font color=" . RED_COMMON . ">Try again and check data you entered.</font>
                        "));
                    break;
                case "ERR_LOSTPSW_CODE":
                    $strTableInnerMsg = str_replace('display: none; position', 'display: block; position', $objDlgWnd->CreateDlgHTML("error_2", Dictionary("error"), "
                        Error: wrong code.
                        <br><br>
                        <font color=" . RED_COMMON . ">Try again and check data you entered.</font>
                        "));
                    break;
                case "TRUE_URL_LOSTPSW":
                    $strTableInnerMsg = str_replace('display: none; position', 'display: block; position', $objDlgWnd->CreateDlgHTML("error_2", Dictionary("info"), "
                        Ссылка для сброса пароля отправлена на Вашу электронную почту (<span style='color: #CC0000'>действительная в течении 1 часа</span>).
                        "));
                    break;
            }

        $strTableInner .= "
        <tr>
            <td align='center' style='padding-top: 0'>
                <table id='table_login' border='0' style='border-collapse: collapse; border-radius: 1em;' background='index_files/transparent_blue_50x50.png'>
                        <tr>
                            <td align='center' valign='top'>
                                <table border='0' cellspacing='4'>
                                <tr>
                                    <td align='right' width='100'>
                                        <font face='Arial' size='1' color='#888888'>Mail: </font>
                                    </td>
                                    <td align='center'>
                                        <input type='text' class='text' value='' name='account_mail' size='20' style='border-radius: 5px 5px 5px 5px; text-align: center; width: 120; font-size: 12px; font-family: Arial; color:#FFFFFF; background-color:#000000; border: 1px solid #888888;' onmouseover='this.style.border=\"1px solid #0099bb\"'; onmouseout='this.style.border=\"1px solid #888888\"'>
                                    </td>
                                    <td align='left' width='100'></td>
                                </tr>
                                <tr>
                                    <td align='right' width='100'>
                                        <font face='Arial' size='1' color='#888888'>Picture ID: </font>
                                    </td>
                                    <td align='center'>
                                        <img src='index_files/secpic.php' id='secpic' alt='Update Code' onClick='location.href=\"" . LOGSERVERURL . "?show=lostpw\"'/>
                                    </td>
                                    <td align='left' width='100'>
                                    </td>
                                </tr>
                                <tr>
                                    <td align='right' width='100'>
                                        <font face='Arial' size='1' color='#888888'>Code (5): </font>
                                    </td>
                                    <td align='center'>
                                        <input type='text' class='text' value='' name='account_code' size='20' style='border-radius: 5px 5px 5px 5px; text-align: center; width: 120; font-size: 12px; font-family: Arial; color:#FFFFFF; background-color:#000000; border: 1px solid #888888;' onmouseover='this.style.border=\"1px solid #0099bb\"'; onmouseout='this.style.border=\"1px solid #888888\"'>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td align='right' width='100'></td>
                                    <td align='center'>
                                        <input type='submit' class='button' style='width: 120;' name='B3' value='Send' />
                                        <input type=hidden name='account_lostpw_form' value='1'>
                                    </td>
                                    <td></td>
                                </tr>
                                </table>
                                ". $strTableInnerMsg . "
                            </td>
                        </tr>
                </table>
            </td>
        </tr>";

        $strTableInner .= BotHtml();

        $strTableInner .= "     <tr>";
        $strTableInner .= "         <td id='submit_td' align='center' height='30' background='" . VISTA_PANEL . "' style='padding: 0'>";
        $strTableInner .= "         </td>";
        $strTableInner .= "     </tr>";

        ShowHTML($strTableInner, SERVER_NAME, false);
    }
    function ShowChangePass($intFlag) {
        $strTableInner = "      <tr><td align='center'>";
        $strTableInner .= "         <font class='h2'>.:: Change Password ::.</font>";
        $strTableInner .= "     </td></tr>";

        $strTableInnerMsg = '';
        $objDlgWnd = new cDlgWnd();
        switch($intFlag) {
            case "ERR_CHANGT_OLDPSW":
                $strTableInnerMsg = str_replace('display: none; position', 'display: block; position', $objDlgWnd->CreateDlgHTML("error_1", Dictionary("error"), "
                    Error: Не введен старый пароль.
                    <br><br>
                    <font color=" . RED_COMMON . ">Попробуйте еще раз и проверьте введенные данные.</font>
                    "));
                break;
            case "ERR_CHANGT_ISPSW":
                $strTableInnerMsg = str_replace('display: none; position', 'display: block; position', $objDlgWnd->CreateDlgHTML("error_1", Dictionary("error"), "
                    Error: Неверный старый пароль.
                    <br><br>
                    <font color=" . RED_COMMON . ">Попробуйте еще раз и проверьте введенные данные.</font>
                    "));
                break;
            case "ERR_CHANGT_NEWPSW":
                $strTableInnerMsg = str_replace('display: none; position', 'display: block; position', $objDlgWnd->CreateDlgHTML("error_1", Dictionary("error"), "
                    Error: Не заполнено поле для нового пароля или он меньше 6 символов.
                    <br><br>
                    <font color=" . RED_COMMON . ">Попробуйте еще раз и проверьте введенные данные.</font>
                    "));
                break;
            case "ERR_CHANGT_NEWPSW2":
                $strTableInnerMsg = str_replace('display: none; position', 'display: block; position', $objDlgWnd->CreateDlgHTML("error_1", Dictionary("error"), "
                    Error: Не заполнено поле для повторного нового пароля или пароли не совпадают.
                    <br><br>
                    <font color=" . RED_COMMON . ">Попробуйте еще раз и проверьте введенные данные.</font>
                    "));
                break;
            case "ERR_CHANGT_LOADPSW":
                $strTableInnerMsg = str_replace('display: none; position', 'display: block; position', $objDlgWnd->CreateDlgHTML("error_1", Dictionary("error"), "
                    Error: Ошибка базы данных.
                    "));
                break;
            case "INFO_CHANGT_FINISH":
                $strTableInnerMsg = str_replace('display: none; position', 'display: block; position', $objDlgWnd->CreateDlgHTML("error_2", Dictionary("info"), "
                    Пароль изменен.
                    "));
                break;

        }

        $strTableInner .= "
        <tr>
            <td align='center' style='padding-top: 0'>
                <table id='table_login' border='0' style='border-collapse: collapse; border-radius: 1em;' background='index_files/transparent_blue_50x50.png'>
                        <tr>
                            <td align='center' valign='top'>
                                <table border='0' cellspacing='4'>
                                <tr>
                                    <td align='right' width='150'>
                                        <font face='Arial' size='1' color='#888888'>Введите старый пароль: </font>
                                    </td>
                                    <td align='center'>
                                        <input type='password' class='text' value='' name='old_pass' size='20' style='border-radius: 5px 5px 5px 5px; text-align: center; width: 120; font-size: 12px; font-family: Arial; color:#FFFFFF; background-color:#000000; border: 1px solid #888888;' onmouseover='this.style.border=\"1px solid #0099bb\"'; onmouseout='this.style.border=\"1px solid #888888\"'>
                                    </td>
                                    <td align='left' width='50'>
                                    </td>
                                </tr>
                                <tr>
                                    <td align='right' width='150'>
                                        <font face='Arial' size='1' color='#888888'>Новый пароль: </font>
                                    </td>
                                    <td align='center'>
                                        <input type='password' class='text' value='' name='new_pass' size='20' style='border-radius: 5px 5px 5px 5px; text-align: center; width: 120; font-size: 12px; font-family: Arial; color:#FFFFFF; background-color:#000000; border: 1px solid #888888;' onmouseover='this.style.border=\"1px solid #0099bb\"'; onmouseout='this.style.border=\"1px solid #888888\"'>
                                    </td>
                                    <td align='left' width='50'>
                                    </td>
                                </tr>
                                <tr>
                                    <td align='right' width='150'>
                                        <font face='Arial' size='1' color='#888888'>Повторите новый пароль: </font>
                                    </td>
                                    <td align='center'>
                                        <input type='password' class='text' value='' name='new_pass2' size='20' style='border-radius: 5px 5px 5px 5px; text-align: center; width: 120; font-size: 12px; font-family: Arial; color:#FFFFFF; background-color:#000000; border: 1px solid #888888;' onmouseover='this.style.border=\"1px solid #0099bb\"'; onmouseout='this.style.border=\"1px solid #888888\"'>
                                    </td>
                                    <td align='left' width='50'>
                                    </td>
                                </tr>
                                <tr>
                                    <td align='right' width='150'></td>
                                    <td align='center'>
                                        <input type='submit' class='button' style='width: 120;' name='B3' value='Send' />
                                        <input type=hidden name='account_changpw_form' value='1'>
                                    </td>
                                    <td></td>
                                </tr>
                                </table>
                                ". $strTableInnerMsg . "
                            </td>
                        </tr>
                </table>
            </td>
        </tr>";

        $strTableInner .= BotHtml();

        $strTableInner .= "     <tr>";
        $strTableInner .= "         <td id='submit_td' align='center' height='30' background='" . VISTA_PANEL . "' style='padding: 0'>";
        $strTableInner .= "         </td>";
        $strTableInner .= "     </tr>";

        ShowHTML($strTableInner, SERVER_NAME, false);
    }

    function ShowRegistration($intFlag) {
        $strTableInner = "      <tr><td align='center'>";
        $strTableInner .= "         <font class='h2'>.:: Registration ::.</font>";
        $strTableInner .= "     </td></tr>";

        $strTableInnerMsg = '';
        if (isset($intFlag))
            switch($intFlag) {
                case "ERR_REG":
                    if (IsErrors()) {
                        $strTableInnerErr = "<table border='0' style='border-collapse: collapse'>";
                        foreach (GetErrStack() as $value) {
                            $strTableInnerErr .= "<tr><td align='left'><font face='Arial' color='#444444' size='2'>Error source: ".$value['source']."</font><br>";
                            $strTableInnerErr .= "<font face='Arial' color='#444444' size='2'>Error description: ".$value['description']."</font></td></tr>";
                        }
                        $strTableInnerErr .= "</table>";

                    }

                    $objDlgWnd = new cDlgWnd();
                    $strTableInnerMsg = str_replace('display: none; position', 'display: block; position', $objDlgWnd->CreateDlgHTML("error_1", Dictionary("error"), "
                        Registration error
                        <br><br>
                        $strTableInnerErr
                        "));
                    break;
            }

        $strTableInner .= "
            <tr>
                <td align='center' style='padding-top: 0'>
                    <table border='0' id='table_register' bordercolor='" . GRAY . "' style='border-radius: 1em' background='"."index_files/transparent_blue_50x50.png"."'>
                    <tr><td align='center' valign='top'>
                    <table border='0' cellspacing='4'>
                        <tr>
                            <td width='60' align='right'><font face='Arial' size='1' color='" . WHITE_DARK ."'>Login: </font></td>
                            <td align='center'>
                                <input type='text' class='text' value='' name='account_login' size='20' style='text-align: center; width: 120; font-size: 12px; font-family: Arial; color:" . WHITE_LIGHT . "; background-color:#000000; border: 1px solid " . WHITE_DARK . ";' onmouseover='this.style.border=\"1px solid " . AQUA_COMMON . "\"'; onmouseout='this.style.border=\"1px solid " . WHITE_DARK . "\"'>
                            </td>
                            <td width='140'><font face='Arial' size='1' color='" . WHITE_DARK ."'>3-12 symbols [A-Za-z0-9_ ]</font></td>
                        </tr>
                        <tr>
                            <td align='right'><font face='Arial' size='1' color='" . WHITE_DARK ."'>Password: </font></td>
                            <td align='center'>
                                <input type='text' class='text' value='' name='account_pswd' size='20' style='text-align: center; width: 120; font-size: 12px; font-family: Arial; color:" . WHITE_LIGHT . "; background-color:#000000; border: 1px solid " . WHITE_DARK . ";' onmouseover='this.style.border=\"1px solid " . AQUA_COMMON . "\"'; onmouseout='this.style.border=\"1px solid " . WHITE_DARK . "\"'>
                            </td>
                            <td><font face='Arial' size='1' color='" . WHITE_DARK ."'>3-12 symbols [A-Za-z0-9_ ]</font></td>
                        </tr>
                        <tr>
                            <td align='right'><font face='Arial' size='1' color='" . WHITE_DARK ."'>E-mail: </font></td>
                            <td align='center'>
                                <input type='text' class='text' value='' name='account_mail' size='20' style='text-align: center; width: 120; font-size: 12px; font-family: Arial; color:" . WHITE_LIGHT . "; background-color:#000000; border: 1px solid " . WHITE_DARK . ";' onmouseover='this.style.border=\"1px solid " . AQUA_COMMON . "\"'; onmouseout='this.style.border=\"1px solid " . WHITE_DARK . "\"'>
                            </td>
                            <td><font face='Arial' size='1' color='" . WHITE_DARK ."'>max 40 symbols</font></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td align='center'>
                                <input type='submit' class='button' style='width: 120;' name='B3' value='Register' />
                                <input type=hidden name='registration_form' value='1'>
                            </td>
                            <td></td>
                        </tr>
                    </table>
                    </td></tr>
                    </table>
                    $strTableInnerMsg
                </td>
            </tr>";

        $strTableInner .= BotHtml();

        $strTableInner .= "     <tr>";
        $strTableInner .= "         <td id='submit_td' align='center' height='30' background='" . VISTA_PANEL . "' style='padding: 0'>";
        $strTableInner .= "         </td>";
        $strTableInner .= "     </tr>";

        ShowHTML($strTableInner, SERVER_NAME, false);
    }

    function ShowError() {
        $strTableInner = "      <tr><td align='center'>
                                    <table>
                                        <tr>
                                            <td>
                                                <font class='h2'>Error list</font>
                                            </td>
                                        </tr>
                                    </table>
                                </td></tr>";

        function _PriceCmp ( $a, $b ){ 
            if ( $a['price'] == $b['price'] ) return 0; 
            if ( $a['price'] < $b['price'] ) return -1; return 1;
        }

        $papka = "upload_err";
        $papka = str_replace ('\\', '/', getcwd ()) . "/" . $papka . "/";

        if (is_readable ($papka)) {
            $handle = opendir ($papka);
            while ($file = readdir ($handle)) {
                if ($file!="." and $file!="..") {
                    if (is_file($papka.$file)) {
                        $filectime = @filemtime ($papka.$file);
                        $filesize = @filesize ($papka.$file);
                        $ARR[] = array ('price' => $filectime, 'name' => array ($file, $filectime, $filesize));
                    }
                }
            }
            clearstatcache ();
            closedir ($handle);
            usort ($ARR, '_PriceCmp');

            $strTableInner .= "<tr>";
            $strTableInner .= "<td align='center'>";
            $strTableInner .= "<table>";
            for ($i = 0; $i < count($ARR); $i++){
                $file_name = $ARR[$i]['name'][0];
                $date = @date ('d.m.Y H:i', $ARR[$i]['name'][1]);
                $size = $ARR[$i]['name'][2];
                $strTableInner .= "<tr><td><a href='/upload_err/" . $file_name . "'>" . $file_name . "</a></td><td><font face='Arial' size='1' color='" . WHITE_DARK ."'>" . $date . "</font></td><td align='right'><font face='Arial' size='1' color='" . WHITE_DARK ."'>" . $size . " байт</font></td></tr>\n";
            }
            $strTableInner .= "</table>";           
            $strTableInner .= "</td>";
            $strTableInner .= "</tr>";
        } 



        $strTableInner .= "     <tr>";
        $strTableInner .= "         <td id='submit_td' align='center' height='30' background='" . VISTA_PANEL . "' style='padding: 0'>";
        $strTableInner .= "         </td>";
        $strTableInner .= "     </tr>";
        
        ShowHTML($strTableInner, SERVER_NAME, false);                              
    }

    function ShowUniverses($strDomain) {
        $varServersLobby = GetServersLobby ();
        foreach ($varServersLobby as $key => $value) {
            if (!isset($varServersPlayers[$value->language])) $varServersPlayers[$value->language] = 0;
            if (!isset($varServersOnline[$value->language])) $varServersOnline[$value->language] = 0;
            $arrDomain[$value->language] = $value->number;
            $varServersPlayers[$value->language] += $value->playerCount;
            $varServersOnline[$value->language] += $value->playersOnline;
        }

        if (!isset($strDomain) || $strDomain == "") $strDomain = "ru";
        $strTableInner = "      <tr><td align='center'>
                                    <table>
                                        <tr>
                                            <td>
                                                <font class='h2'>" . strtoupper($strDomain) . " UNIVERSES</font>
                                            </td>
                                        </tr>
                                    </table>
                                </td></tr>";        
        $strTableInner .= "<tr>";
        $strTableInner .= "<td align='center'>";
        $strTableInner .= '<table class="contents" style="border-collapse: collapse" border="1" bordercolor="#222222" cellpadding="4" width="840">';
        $strTableInner .= '<tr height="28">';
        $strTableInner .= ' <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" align="left" background="index_files/abox/header.png" width="25"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Country:</font></td>';

        foreach ($arrDomain as $key => $value) {
            if ($strDomain == $key) $imgHeader[$key] = "header_a_";
            else $imgHeader[$key] = "header";
           
            $strTableInner .= ' <td onClick="window.location = \'index.php?show=universes&country=' . $key . '\';" onmouseover="ActivateHeader(this); style.cursor=(\'pointer\')" onmouseout="DeactivateHeader(this)" align="center" background="index_files/abox/' . $imgHeader[$key] . '.png" width="25"><font class="abox_text">' . $key . '</font></td>';
        }
        $strTableInner .= '</tr>';
        $strTableInner .= '<tr height="28">';
        $strTableInner .= ' <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" align="left" background="index_files/abox/header.png" width="25"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Online:</font></td>';

        foreach ($arrDomain as $key => $value) {
            if ($strDomain == $key) $imgHeader[$key] = "header_a_";
            else $imgHeader[$key] = "header";            
            if ($varServersOnline[$key] > 0) $varText = "<font color='" . GREEN_DARK . "'>" . $varServersOnline[$key] . "</font>";
            if ($varServersOnline[$key] > 500) $varText = "<font color='" . YELLOW_LIGHT . "'>" . $varServersOnline[$key] . "</font>";
            if ($varServersOnline[$key] > 1000) $varText = "<font color='" . RED_LIGHT . "'>" . $varServersOnline[$key] . "</font>";

            $strTableInner .= ' <td onClick="window.location = \'index.php?show=universes&country=' . $key . '\';" onmouseover="ActivateHeader(this); style.cursor=(\'pointer\')" onmouseout="DeactivateHeader(this)" align="center" background="index_files/abox/' . $imgHeader[$key] . '.png" width="25"><font class="abox_text" onmouseover="style.cursor=(\'default\')">' . $varText . '</font></td>';
        }
       
        $strTableInner .= '</tr>';                  
        $strTableInner .= "</table>";           
        $strTableInner .= "<br>";           

        $strTableInner .= '<table class="contents" style="border-collapse: collapse" border="1" bordercolor="#222222" cellpadding="4" width="840">';
        $strTableInner .= '<tr height="28">
                        <td align="center" background="index_files/abox/header.png" width="20"><font class="abox_text">№</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">' . Dictionary('uni') . '</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">' . Dictionary('eco') . '</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">' . Dictionary('fleet') . '</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">research</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">' . Dictionary('size') . '</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">' . Dictionary('sab') . '</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">' . Dictionary('debris') . '</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">' . Dictionary('ovo') . '</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">' . Dictionary('top') . '</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">' . Dictionary('gamers') . '</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">' . Dictionary('online') . '</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">' . Dictionary('fields') . '</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">' . Dictionary('circular_gal') . '</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">' . Dictionary('circular_sys') . '</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">' . Dictionary('deuterium') . '</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">' . Dictionary('probe') . '</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">market</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">' . Dictionary('opening_date') . '</font></td>
                   </tr>';
        
        $strNum = 0;
        $strServer = $arrDomain[$strDomain];

        $xmlFile = 'xml/'.$strServer.'-'.$strDomain.'_universes.xml';

        if (!file_exists($xmlFile) || ((time() - filemtime ($xmlFile)) >= 1 * 24 * 60 * 60)) {
            $url = 'https://s' . $strServer . '-' . $strDomain . '.ogame.gameforge.com/api/universes.xml';
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
                $strUni = $serverData["id"];
                foreach ($varServersLobby as $value) {
                    if ($value->language == $strDomain && $value->number == $strUni) {
                        $varGamers = $value->playerCount;
                        $varOnline = $value->playersOnline;
                        $varOpened = date("d/m/Y", strtotime($value->opened));
                    }
                }               
                //$strDomain = explode("-", explode(".", parse_url($serverData["href"])["host"])[0])[1];

                $varResult = GetServerData($strUni, $strDomain, 3);

                $varResult["name"] = (isset($varResult["name"])) ? $varResult["name"] : $strUni;
                $varResult["debrisFactorDef"] = ($varResult["debrisFactorDef"] > 0) ? $varResult["debrisFactorDef"] * 100 . "%" : "<font color='" . RED_DARK . "'>✘</font>";
                $varResult["bonusFields"] = ($varResult["bonusFields"] > 0) ? "+" . $varResult["bonusFields"] : "<font color='" . RED_DARK . "'>✘</font>";
                $varResult["acs"] = ($varResult["acs"] == 1) ? "<font color='" . GREEN_DARK . "'>✔</font>" : "<font color='" . RED_DARK . "'>✘</font>";
                $varResult["donutGalaxy"] = ($varResult["donutGalaxy"] == 1) ? "<font color='" . GREEN_DARK . "'>✔</font>" : "<font color='" . RED_DARK . "'>✘</font>";
                $varResult["donutSystem"] = ($varResult["donutSystem"] == 1) ? "<font color='" . GREEN_DARK . "'>✔</font>" : "<font color='" . RED_DARK . "'>✘</font>";
                $varResult["probeCargo"] = ($varResult["probeCargo"] > 0) ? "<font color='" . GREEN_DARK . "'>" . $varResult["probeCargo"] . "</font>" : "<font color='" . RED_DARK . "'>✘</font>";
                $varOnline = ($varOnline > 0) ? "<font color='" . GREEN_DARK . "'>" . $varOnline . "</font>" : $varOnline;

                if ($varResult["researchDurationDivisor"] > 1) $classRDD = "abox_text_green";
                else $classRDD = "abox_text";

                $strTableInner .= '
                <tr onmouseover="ActivateRow(this)" onmouseout="DeactivateRow(this)" src="index_files/abox/row_' . ($strNum % 2 + 1) . '.png" background="index_files/abox/row_' . ($strNum % 2 + 1) . '.png" height="28">
                  <td align="left"><font class="abox_text">' . $strUni . '</font></td>
                  <td align="left"><font class="abox_text">' . $varResult["name"] . '</font></td>
                  <td align="center"><font class="abox_text">x' . $varResult["speed"] . '</font></td>
                  <td align="center"><font class="abox_text">x' . $varResult["speedFleet"] . '</font></td>
                  <td align="center"><font class="' . $classRDD . '">x' . ($varResult["speed"] * $varResult["researchDurationDivisor"]) . '</font></td>
                  <td align="center"><font class="abox_text">' . $varResult["galaxies"] . ':' . $varResult["systems"] . '</font></td>
                  <td align="center"><font class="abox_text">' . $varResult["acs"] . '</font></td>
                  <td align="center"><font class="abox_text">' . ($varResult["debrisFactor"] * 100) . '%</font></td>
                  <td align="center"><font class="abox_text">' . $varResult["debrisFactorDef"] . '</font></td>
                  <td align="center" title="' . number_format ($varResult["topScore"]) . '"><font class="abox_text">' . NumberS($varResult["topScore"], false) . '</font></td>
                  <td align="center" title="' . number_format ($varGamers) . '"><font class="abox_text">' . NumberS($varGamers, false) . '</font></td>
                  <td align="center"><font class="abox_text">' . NumberS($varOnline, false) . '</font></td>
                  <td align="center"><font class="abox_text">' . $varResult["bonusFields"] . '</font></td>
                  <td align="center"><font class="abox_text">' . $varResult["donutGalaxy"] . '</font></td>
                  <td align="center"><font class="abox_text">' . $varResult["donutSystem"] . '</font></td>
                  <td align="center"><font class="abox_text">' . ($varResult["globalDeuteriumSaveFactor"] * 100) . '%</font></td>
                  <td align="center"><font class="abox_text">' . $varResult["probeCargo"] . '</font></td>
                  <td align="center"><font class="abox_text">' . $varResult["marketplaceBasicTradeRatioMetal"] . '/' . $varResult["marketplaceBasicTradeRatioCrystal"] . '/' . $varResult["marketplaceBasicTradeRatioDeuterium"] . '</font></td>
                  <td align="center"><font class="abox_text">' . $varOpened . '</font></td>

                </tr>';             
                $strNum += 1;
            }
        }

        $strTableInner .= "</table>";           
        $strTableInner .= "</td>";
        $strTableInner .= "</tr>";



        $strTableInner .= "     <tr>";
        $strTableInner .= "         <td id='submit_td' align='center' height='30' background='" . VISTA_PANEL . "' style='padding: 0'>";
        $strTableInner .= "         </td>";
        $strTableInner .= "     </tr>";
        
        ShowHTML($strTableInner, SERVER_NAME, false);                              
    }

    function ShowAccount($intFlag) {
        $strLoad = "";
        $strLogs = "";
        $strTableInner = "      <tr><td align='center'>
                                    <table><tr><td>
                                        <font class='h2'>" . Dictionary('account_1') . "</font>
                                    </td>
                                    <td width='4'></td>
                                    <td>
                                        <img src='" . "index_files/vista_panel/icon_account_b.png" . "' width='32'>
                                    </td>
                                    <td width='4'></td>
                                    <td>
                                        <font class='h2'>" . Dictionary('account_2') . "</font>
                                    </td></tr></table>
                                </td></tr>";

        $strTableInnerMsg = '';
        $objDlgWnd = new cDlgWnd();
        switch ($intFlag) {
            case "ERR_LOGIN":
                $strTableInnerMsg = str_replace('display: none; position', 'display: block; position', $objDlgWnd->CreateDlgHTML("error_1", Dictionary("error"), "
                    Error: wrong login or password
                    <br><br>
                    Try again and check data you entered
                    "));
                break;
            case "ERR_NOT_CONF_REG":
                $strTableInnerMsg = str_replace('display: none; position', 'display: block; position', $objDlgWnd->CreateDlgHTML("error_2", Dictionary("info"), "
                    Request was sent to your e-mail, but you can already log in.
                    <br><br>
                    <font color=" . RED_COMMON . ">In case of account will not be activated, it can be deleted in a month after last visit.</font>
                    "));
                break;
            case "ERR_LOSTPSW_MAIL_DB":
                $strTableInnerMsg = str_replace('display: none; position', 'display: block; position', $objDlgWnd->CreateDlgHTML("error_2", Dictionary("error"), "
                    Error.
                    <br><br>
                    <font color=" . RED_COMMON . ">Неправильная ссылка или вышел срок её действия.</font>
                    "));
                break;
            case "TRUE_CONFIRM_LOSTPSW":
                $strTableInnerMsg = str_replace('display: none; position', 'display: block; position', $objDlgWnd->CreateDlgHTML("error_2", Dictionary("info"), "
                    Новый пароль отправлен на Вашу электронную почту.
                    "));
                break;              
        }

        if (!isset($_SESSION['account']['login'])) {
            $strTableInner .= "
                <tr style='padding: 0'>
                    <td align='center' style='padding-top: 0'>
                        <table id='table_login' border='0' style='border-radius: 1em' background='"."index_files/transparent_blue_50x50.png"."'>
                        <tr><td align='center' valign='top'>
                        <table border='0' cellspacing='4'>
                            <tr>
                                <td width='100' align='right'><font face='Arial' size='1' color='" . WHITE_DARK ."'>Login: </font></td>
                                <td align='center'>
                                    <input type='text' class='text' value='' name='account_login' size='20' style='border-radius: 5px 5px 5px 5px; text-align: center; width: 120; font-size: 12px; font-family: Arial; color:" . WHITE_LIGHT . "; background-color:#000000; border: 1px solid " . WHITE_DARK . ";' onmouseover='this.style.border=\"1px solid " . AQUA_COMMON . "\"'; onmouseout='this.style.border=\"1px solid " . WHITE_DARK . "\"'>
                                </td>
                                <td width='100'></td>
                            </tr>
                            <tr>
                                <td align='right'><font face='Arial' size='1' color='" . WHITE_DARK ."'>Password: </font></td>
                                <td align='center'>
                                    <input type='password' class='text' value='' name='account_pswd' size='20' style='border-radius: 5px 5px 5px 5px; text-align: center; width: 120; font-size: 12px; font-family: Arial; color:" . WHITE_LIGHT . "; background-color:#000000; border: 1px solid " . WHITE_DARK . ";' onmouseover='this.style.border=\"1px solid " . AQUA_COMMON . "\"'; onmouseout='this.style.border=\"1px solid " . WHITE_DARK . "\"'>
                                </td>
                                <td><a href='index.php?show=lostpw'><font size='1'>" . Dictionary('forgot_password') . "</font></a></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td align='center'>
                                    <input type='submit' class='button' style='width: 120;' name='B3' value='" . Dictionary('login') . "' />
                                    <input type=hidden name='B3' value='1'>
                                    <input type=hidden name='account_login_form' value='1'>
                                </td>
                                <td><a href='index.php?show=registration'><font size='1'>" . Dictionary('registration') . "</font></a></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td align='center'>
                                    <table>
                                        <tr>
                                            <td>
                                                <input type='checkbox' name='account_remember'>
                                            </td>
                                            <td>
                                                <font face='Arial' size='1' color='" . WHITE_DARK ."'>" . Dictionary('remember_me') . "</font>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td></td>
                            </tr>
                        </table>
                        </td></tr>
                        </table>
                        $strTableInnerMsg
                    </td>
                </tr>";
        }
        else {
            $SLA["oldlogs"] = cDB::SelectLogsAccount ($_SESSION['account']['id'], 1);

            $strTableInner .= '<script type="text/javascript">
                              $(document).ready(function() {
                                  $("#logs").load("h_ajax.php?page=logs&uni=0");

                                  $("#logs").on("click", ".pagination a", function (e){
                                      e.preventDefault();
                                      var page = $(this).attr("data-page");
                                      $("#logs").load("h_ajax.php?page=logs",{"n":page});
                                  });';

            $strTableInner .= '     $("#spylogs").load("h_ajax.php?page=spylogs");

                                  $("#spylogs").on("click", ".pagination a", function (e){
                                      e.preventDefault();
                                      var page = $(this).attr("data-page");
                                      $("#spylogs").load("h_ajax.php?page=spylogs",{"n":page});
                                  });';
            $strTableInner .= '});
                              </script>';

                $strLogs = "<tr><td align='center'><font color='" . WHITE_DARK . "' face='Arial' size='2'>" . Dictionary("acc_list") . ":</font></td></tr>";
                $strLogs .= "<tr><td align='center'><div id='logs'><img src='index_files/ajax-loader.gif'></div></td></tr>";

                $strLogs .= "<tr><td align='center'><font color='" . WHITE_DARK . "' face='Arial' size='2'>" . Dictionary("acc_spyrpt") . "</font></td></tr>";
                $strLogs .= "<tr><td align='center'><div id='spylogs'><img src='index_files/ajax-loader.gif'></div></td></tr>";
        }

        $strTableInner .= $strLogs . $strLoad;

        $strTableInner .= BotHtml();

        $strTableInner .= "     <tr>";
        $strTableInner .= "         <td id='submit_td' align='center' height='30' background='" . VISTA_PANEL . "' style='padding: 0'>";
        $strTableInner .= "         </td>";
        $strTableInner .= "     </tr>";

        ShowHTML($strTableInner, SERVER_NAME, false);
    }

    function ShowAlliance($intFlag) {
        $strLoad = "";
        $strLogs = "";
        $strTableInner = "      <tr><td align='center'>
                                    <table><tr><td>
                                        <font class='h2'>" . Dictionary('account_1') . "</font>
                                    </td>
                                    <td width='4'></td>
                                    <td>
                                        <img src='" . "index_files/vista_panel/icon_account_b.png" . "' width='32'>
                                    </td>
                                    <td width='4'></td>
                                    <td>
                                        <font class='h2'>alliace</font>
                                    </td></tr></table>
                                </td></tr>";


        if (!isset($_SESSION['account']['login'])) {
            $strTableInner .= "
                <tr style='padding: 0'>
                    <td align='center' style='padding-top: 0'>
                        <table id='table_login' border='0' style='border-radius: 1em' background='"."index_files/transparent_blue_50x50.png"."'>
                        <tr><td align='center' valign='top'>
                        <table border='0' cellspacing='4'>
                            <tr>
                                <td width='100' align='right'><font face='Arial' size='1' color='" . WHITE_DARK ."'>Login: </font></td>
                                <td align='center'>
                                    <input type='text' class='text' value='' name='account_login' size='20' style='border-radius: 5px 5px 5px 5px; text-align: center; width: 120; font-size: 12px; font-family: Arial; color:" . WHITE_LIGHT . "; background-color:#000000; border: 1px solid " . WHITE_DARK . ";' onmouseover='this.style.border=\"1px solid " . AQUA_COMMON . "\"'; onmouseout='this.style.border=\"1px solid " . WHITE_DARK . "\"'>
                                </td>
                                <td width='100'></td>
                            </tr>
                            <tr>
                                <td align='right'><font face='Arial' size='1' color='" . WHITE_DARK ."'>Password: </font></td>
                                <td align='center'>
                                    <input type='password' class='text' value='' name='account_pswd' size='20' style='border-radius: 5px 5px 5px 5px; text-align: center; width: 120; font-size: 12px; font-family: Arial; color:" . WHITE_LIGHT . "; background-color:#000000; border: 1px solid " . WHITE_DARK . ";' onmouseover='this.style.border=\"1px solid " . AQUA_COMMON . "\"'; onmouseout='this.style.border=\"1px solid " . WHITE_DARK . "\"'>
                                </td>
                                <td><a href='index.php?show=lostpw'><font size='1'>" . Dictionary('forgot_password') . "</font></a></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td align='center'>
                                    <input type='submit' class='button' style='width: 120;' name='B3' value='" . Dictionary('login') . "' />
                                    <input type=hidden name='B3' value='1'>
                                    <input type=hidden name='account_login_form' value='1'>
                                </td>
                                <td><a href='index.php?show=registration'><font size='1'>" . Dictionary('registration') . "</font></a></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td align='center'>
                                    <table>
                                        <tr>
                                            <td>
                                                <input type='checkbox' name='account_remember'>
                                            </td>
                                            <td>
                                                <font face='Arial' size='1' color='" . WHITE_DARK ."'>" . Dictionary('remember_me') . "</font>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td></td>
                            </tr>
                        </table>
                        </td></tr>
                        </table>
                        $strTableInnerMsg
                    </td>
                </tr>";
        }
        else {
            global $NameUni;
            $strDomainSelect = "    <select size='1' id='select_domain' style='font-size: 12px; width: 120px; height: 36px;' onchange='" . JSCookie("index_select_domain", "\"+this.selectedIndex+\"") . "'>
                                        <option value='AR'>AR</option>
                                        <option value='BG'>BG</option>
                                        <option value='BR'>BR</option>
                                        <option value='HU'>HU</option>
                                        <option value='DE'>DE</option>
                                        <option value='GR'>GR</option>
                                        <option value='DK'>DK</option>
                                        <option value='ES'>ES</option>
                                        <option value='IT'>IT</option>
                                        <option value='LV'>LV</option>
                                        <option value='LT'>LT</option>
                                        <option value='MX'>MX</option>
                                        <option value='NL'>NL</option>
                                        <option value='NO'>NO</option>
                                        <option value='ORG'>ORG</option>
                                        <option value='PL'>PL</option>
                                        <option value='PT'>PT</option>
                                        <option value='RU' selected>RU</option>
                                        <option value='RO'>RO</option>
                                        <option value='US'>US</option>
                                        <option value='SK'>SK</option>
                                        <option value='SI'>SI</option>
                                        <option value='TW'>TW</option>
                                        <option value='TR'>TR</option>
                                        <option value='FI'>FI</option>
                                        <option value='FR'>FR</option>
                                        <option value='CZ'>CZ</option>
                                        <option value='SE'>SE</option>
                                        <option value='JP'>JP</option>
                                    </select>";
            $strUniSelect = "       <select size='1' id='select_uni' style='font-size: 12px; width: 120px; height: 36px;' onchange='" . JSCookie("index_select_uni", "\"+this.value+\"") . "'>";
            foreach ($NameUni as $key => $value) {
                if ($value[1] != "?") {
                    if (gettype($value[0]) == "integer") $value[0] = "Universe";
                    $selectedUni = (isset($_COOKIE["index_select_uni"]) && $_COOKIE["index_select_uni"] == $key) ? " selected" : false;
                    $strUniSelect .= "<option value='" . $key . "'" . $selectedUni . ">" . $key . ". " . $value[0] . "</option>";
                }
            }
            $strTableInner .= '<script type="text/javascript">
                            $(document).ready(function() {
                                $("#alliance").load("h_ajax.php?page=alliance");
                                $("#invite").load("h_ajax.php?page=alliance&invite");

                                $("#btn_create").click(function () {
                                    $.ajax({
                                        type: "GET",
                                        url: "h_ajax.php",
                                        data: {page: "alliance", domain: $("#select_domain").val(), uni: $("#select_uni").val()},
                                        success: function(data) {
                                            if (data == "save") {
                                                $("#alliance").load("h_ajax.php?page=alliance");
                                                //$(this).attr("disabled", "disabled");
                                            }
                                            if (data == "double")
                                                $("#err").html("<font class=\"abox_text_red\">Double.</font>");
                                        }
                                    });
                                }); 

                                $("#alliance").on("click", ".groupUserAdd", function () {
                                    $("#tbl_invite").show();
                                    $("#invite_domain").val($(this).attr("domain"));
                                    $("#invite_uni").val($(this).attr("uni"));
                                    $("#group_id").val($(this).attr("group"));
                                });

                                $("#btn_invite").click(function () {
                                    $.ajax({
                                        type: "GET",
                                        url: "h_ajax.php",
                                        data: {page: "alliance", group_id: $("#group_id").val(), user_login: $("#user_login").val(), domain: $("#invite_domain").val(), uni: $("#invite_uni").val()},
                                        success: function(data) {
                                            alert (data)
                                            if (data == "save") {
                                                $("#alliance").load("h_ajax.php?page=alliance");
                                                //$(this).attr("disabled", "disabled");
                                            }
                                            if (data == "double")
                                                $("#err").html("<font class=\"abox_text_red\">Double.</font>");
                                        }
                                    });
                                });

                            });
                              </script>';

                $strLogs = "";
                $strLogs .= "<tr><td align='center'>";
                $strLogs .= "    <table>";
                $strLogs .= "        <tr>";
                $strLogs .= "            <td>" . $strDomainSelect . "</td>";
                $strLogs .= "            <td>" . $strUniSelect . "</td>";
                $strLogs .= "            <td><input type='button' class='button' id='btn_create' style='width: 120px;' value='Create'></td>";
                $strLogs .= "        </tr>";
                $strLogs .= "        <tr>";
                $strLogs .= "            <td colspan='3' id='err' align='center'></td>";
                $strLogs .= "        </tr>";                
                $strLogs .= "    </table>";
                $strLogs .= "</td></tr>";

                $strLogs .= "<tr id='tbl_invite' style='display: none'><td align='center'>";
                $strLogs .= "    <table>";
                $strLogs .= "        <tr>";
                $strLogs .= "            <td><input type='text' id='invite_domain' style='display: none'></td>";
                $strLogs .= "            <td><input type='text' id='invite_uni' style='display: none'></td>";
                $strLogs .= "            <td><input type='text' id='group_id' style='display: none'></td>";
                $strLogs .= "            <td><input type='text' id='user_login' style='font-size: 12px; width: 245px; height: 36px;'></td>";
                $strLogs .= "            <td><input type='button' class='button' id='btn_invite' style='width: 120px;' value='Invite'></td>";
                $strLogs .= "        </tr>";
                $strLogs .= "        <tr>";
                $strLogs .= "            <td colspan='3' id='err' align='center'></td>";
                $strLogs .= "        </tr>";                
                $strLogs .= "    </table>";
                $strLogs .= "</td></tr>";

                $strLogs .= "<tr><td align='center'><font color='" . WHITE_DARK . "' face='Arial' size='2'>List of alliance:</font></td></tr>";
                $strLogs .= "<tr><td align='center'><div id='alliance'><img src='index_files/ajax-loader.gif'></div></td></tr>";

                $strLogs .= "<tr><td align='center'><font color='" . WHITE_DARK . "' face='Arial' size='2'>List of invite:</font></td></tr>";
                $strLogs .= "<tr><td align='center'><div id='invite'><img src='index_files/ajax-loader.gif'></div></td></tr>";

        }

        $strTableInner .= $strLogs . $strLoad;

        $strTableInner .= BotHtml();

        $strTableInner .= "     <tr>";
        $strTableInner .= "         <td id='submit_td' align='center' height='30' background='" . VISTA_PANEL . "' style='padding: 0'>";
        $strTableInner .= "         </td>";
        $strTableInner .= "     </tr>";

        ShowHTML($strTableInner, SERVER_NAME, false);
    }

    function ShowFakeAdmin() {
        global $NameUni;
        $strTableInner = "      <tr><td align='center'>
                                    <table><tr><td>
                                        <font class='h2'>Admin</font>
                                    </td>
                                    <td width='4'></td>
                                    <td>
                                        <img src='" . "index_files/vista_panel/icon_admin_b.png" . "' width='32'>
                                    </td>
                                    <td width='4'></td>
                                    <td>
                                        <font class='h2'>Tool</font>
                                    </td></tr></table>
                                </td></tr>";

        if(in_array($_SESSION['account']['login'], listFakeAdmin())) {
            //$_COOKIE["del_bd_logserver"]
            //$_COOKIE["del_logserver"]
//                                <td width='320' align='center'><input type='button' value='&#9658; Удалить Базу Логсервера' onclick='" . JSCookie("del_bd_logserver", "1") . "; document.location.reload(true);' style='width:200'></td>
//                                <td width='320' align='center'><input type='button' value='&#9658; Удалить Логсервер' onclick='" . JSCookie("del_logserver", "1") . "; document.location.reload(true);' style='width:200'></td>

            $strTableInner .= "
                <tr style='padding: 0'>
                    <td align='center' style='padding-top: 0'>
                        <table id='table_login' border='0' style='border-radius: 1em' background='"."index_files/transparent_blue_50x50.png"."'>
                        <tr><td align='center' valign='top'>
                        <table border='0' cellspacing='4'>
                            <tr>
                                <td align='center' width='32px'><img src='index_files/admin/lock_off.png' style='width:32px'></td>
                                <td align='center' width='320px'><img src='index_files/admin/button_standby.png' style='width:56px'></td>
                                <td align='center' width='32px'><img src='index_files/admin/lock_off.png' style='width:32px'></td>
                            </tr>
                            <tr>
                                <td align='center' width='32px'><img src='index_files/admin/lock_off.png' style='width:32px'></td>
                                <td align='center'><img src='index_files/admin/button_shutdown.png' style='width:56px'></td>
                                <td align='center' width='32px'><img src='index_files/admin/lock_off.png' style='width:32px'></td>
                            </tr>
                        </table>
                        </td></tr>
                        </table>
                        $strTableInnerMsg
                    </td>
                </tr>";
        } else {
            $strTableInner .= "
                <tr style='padding: 0'>
                    <td align='center' style='padding-top: 0'>
                        <table id='table_login' border='0' style='border-radius: 1em' background='"."index_files/transparent_blue_50x50.png"."'>
                        <tr><td align='center' valign='top'>
                        <table border='0' cellspacing='4'>
                            <tr>
                                <td width='320' align='center'><font color='#FF0000'>Access denied</font></td>
                            </tr>
                        </table>
                        </td></tr>
                        </table>
                        $strTableInnerMsg
                    </td>
                </tr>";
        }

        $strTableInner .= "
                <tr>
                    <td align='center'>
                        <font color='" . WHITE_DARK . "' face='Arial' size='2'>$strMsg</font>
                    </td>
                </tr>
                $strJSBox";

        $strTableInner .= BotHtml();

        $strTableInner .= "     <tr>";
        $strTableInner .= "         <td id='submit_td' align='center' height='30' background='" . VISTA_PANEL . "' style='padding: 0'>";
        $strTableInner .= "         </td>";
        $strTableInner .= "     </tr>";

        ShowHTML($strTableInner, SERVER_NAME, false);
    }

    function ShowAdmin($arrLogs, $intFlag) {
        global $NameUni;
        $strTableInner = "      <tr><td align='center'>
                                    <table><tr><td>
                                        <font class='h2'>Admin</font>
                                    </td>
                                    <td width='4'></td>
                                    <td>
                                        <img src='" . "index_files/vista_panel/icon_admin_b.png" . "' width='32'>
                                    </td>
                                    <td width='4'></td>
                                    <td>
                                        <font class='h2'>Tool</font>
                                    </td></tr></table>
                                </td></tr>";


        //if($_SESSION['account']['login'] == 'asiman' || $_SESSION['account']['login'] == 'gluga' || $_SESSION['account']['login'] == 'prostor' || $_SESSION['account']['login'] == 'Breakneck' || $_SESSION['account']['login'] == 'AntonGreat' || $_SESSION['account']['login'] == 'iZoTope' || $_SESSION['account']['login'] == 'Jafar') {
        if(in_array($_SESSION['account']['login'], listAdmin())) {
                        
        $strDomainSelect = "    <select size='1' name='select_domain' style='font-size: 10px; width: 120px;' onchange='" . JSCookie("index_select_domain", "\"+this.selectedIndex+\"") . "'>
                                    <option value='0'>Domain: any</option>
                                    <option value='AR'>AR</option>
                                    <option value='BG'>BG</option>
                                    <option value='BR'>BR</option>
                                    <option value='HU' >HU</option>
                                    <option value='DE'>DE</option>
                                    <option value='GR'>GR</option>
                                    <option value='DK'>DK</option>
                                    <option value='ES'>ES</option>
                                    <option value='IT'>IT</option>
                                    <option value='LV'>LV</option>
                                    <option value='LT'>LT</option>
                                    <option value='MX'>MX</option>
                                    <option value='NL'>NL</option>
                                    <option value='NO'>NO</option>
                                    <option value='ORG'>ORG</option>
                                    <option value='PL'>PL</option>
                                    <option value='PT'>PT</option>
                                    <option value='RU' selected>RU</option>
                                    <option value='RO'>RO</option>
                                    <option value='US'>US</option>
                                    <option value='SK'>SK</option>
                                    <option value='SI'>SI</option>
                                    <option value='TW'>TW</option>
                                    <option value='TR'>TR</option>
                                    <option value='FI'>FI</option>
                                    <option value='FR'>FR</option>
                                    <option value='CZ'>CZ</option>
                                    <option value='SE'>SE</option>
                                    <option value='JP'>JP</option>
                                </select>";
        $strUniSelect = "       <select size='1' name='select_uni' style='font-size: 10px; width: 120px;' onchange='" . JSCookie("index_select_uni", "\"+this.value+\"") . "'>
                                    <option value='0'>Universe: any</option>";
        foreach ($NameUni as $key => $value) {
            if ($value[1] != "?") {
                if (gettype($value[0]) == "integer") $value[0] = "Universe";
                $selectedUni = (isset($_COOKIE["index_select_uni"]) && $_COOKIE["index_select_uni"] == $key) ? " selected" : false;
                $strUniSelect .= "<option value='" . $key . "'" . $selectedUni . ">" . $key . ". " . $value[0] . "</option>";
            }
        }

        $strUniSelect .= "      </select>";
                                    for ($i = 0; $i < 17; $i++){
                                        if($_COOKIE["index_select_losses"]==$i)  $selectedLosses[$i] = 'selected';
                                    }
        $strLossesSelect = "    <select size='1' name='select_losses' style='font-size: 10px; width: 120px;' onchange='" . JSCookie("index_select_losses", "\"+this.selectedIndex+\"") . "'>
                                    <option value='0' $selectedLosses[0]>Losses: any</option>
                                    <option value='1' $selectedLosses[1]>100.000+</option>
                                    <option value='2' $selectedLosses[2]>200.000+</option>
                                    <option value='3' $selectedLosses[3]>400.000+</option>
                                    <option value='4' $selectedLosses[4]>800.000+</option>
                                    <option value='5' $selectedLosses[5]>1.600.000+</option>
                                    <option value='6' $selectedLosses[6]>3.200.000+</option>
                                    <option value='7' $selectedLosses[7]>6.400.000+</option>
                                    <option value='8' $selectedLosses[8]>12.800.000+</option>
                                    <option value='9' $selectedLosses[9]>25.600.000+</option>
                                    <option value='10' $selectedLosses[10]>51.200.000+</option>
                                    <option value='11' $selectedLosses[11]>102.400.000+</option>
                                    <option value='12' $selectedLosses[12]>204.800.000+</option>
                                    <option value='13' $selectedLosses[13]>409.600.000+</option>
                                    <option value='14' $selectedLosses[14]>819.200.000+</option>
                                    <option value='15' $selectedLosses[15]>1.638.400.000+</option>
                                    <option value='16' $selectedLosses[16]>3.276.800.000+</option>
                                </select>";
                                    for ($i = 0; $i < 3; $i++){
                                        if($_COOKIE["index_select_public"]==$i)  $selectedPublic[$i] = 'selected';
                                    }
        $strPublicSelect = "
                                <select size='1' name='select_public' style='font-size: 10px; width: 120px;' onchange='" . JSCookie("index_select_public", "\"+this.selectedIndex+\"") . "'>
                                    <option value='' $selectedPublic[0]>Public: any</option>
                                    <option value='1' $selectedPublic[1]>Public: yes</option>
                                    <option value='0' $selectedPublic[2]>Public: no</option>
                                </select>";
                                    for ($i = 0; $i < 3; $i++){
                                        if($_COOKIE["index_select_limit"]==$i)  $selectedLimit[$i] = 'selected';
                                    }
        $strLimitSelect = "
                                <select size='1' name='select_limit' style='font-size: 10px; width: 120px;' onchange='" . JSCookie("index_select_limit", "\"+this.selectedIndex+\"") . "'>
                                    <option value='15' $selectedLimit[0]>Limit: 15</option>
                                    <option value='25' $selectedLimit[1]>Limit: 25</option>
                                    <option value='50' $selectedLimit[2]>Limit: 50</option>
                                </select>";
        $strTableInner .= "
                            <tr>
                                <td align='center'>
                                    <table background='" . SEARCH_IMG . "' width='640' height='110'>
                                        <tr align='left'>
                                            <td width='120'><input type='text' class='text' name='text_name' id='text_name' size='20' value='' style='width: 120; font-size: 12px; font-family: Arial; color:" . BLACK . "; background-color:" . WHITE_LIGHT . "; border: 1px solid " . WHITE_DARK . ";'></td>
                                            <td><input type='submit' value='&#9658; Search' name='SubmitButton' id='SubmitButton' style='width:120'></td>
                                        </tr>
                                        <tr>
                                            <td>$strDomainSelect</td>
                                            <td>$strPublicSelect</td>
                                        </tr>
                                        <tr>
                                            <td>$strUniSelect</td>
                                            <td>$strLimitSelect</td>
                                        </tr>
                                        <tr>
                                            <td>$strLossesSelect</td>
                                            <td></td>
                                        </tr>
                                    </table>
                                    <input type='hidden' name='admin_search_form' value='1'>
                                </td>
                            </tr>";

            //$varInput['type'] = "my"; !!!

            $strMsg = (!$arrLogs) ? "<font color='" . RED_COMMON . "' face='Arial' size='2'>No logs found</font>" : "";

            $strJSBox = '';

            if (count($arrLogs)) {
                foreach ($arrLogs as $value) {
                    $strLogs .= "arrABox['data'].push([";
                    $strLogs .= "'" . $value['log_id'] . "',";
                    $strLogs .= "" . $value['date'] . ",";
                    $strLogs .= "'" . $value['title'] . "',";
                    $strLogs .= "" . ($value['losses']) . ",";
                    $strLogs .= "'" . "?" . "',";
                    $strLogs .= "'" . ShortNameUni($value['universe'],false) . "',";
                    $strLogs .= "'" . strtolower($value['domain']) . "',";
                    $strLogs .= "'" . "?" . "',";
                    $strLogs .= "" . $value['public'] . ",";
                    $strLogs .= "" . $value['views'] . ",";
                    $strLogs .= "'" . base64_encode($value['log_id']) . "',";
                    // made by Zmei
                    if($value['html_log'] != "")$strLogs .= "1";
                    else $strLogs .= "0";
                    //
                    $strLogs .= "]);\n";
                }
                $strJSBox = "
                    <tr><td>
                    <script language='javascript'>
                        var g_arrMegaABox = [];

                        var arrABox = {id:'abox_0', header:[], visible:[], sort:[], sort_f:1, sort_o:1, align:[], width:[], maxlen:[], data:[]};
                            arrABox['header'] = ['Id', 'Date', 'Title', 'Losses', 'Profit', 'Uni', 'Lang', 'Res', 'Pub', 'Views', 'Del', 'Edit'];
                            arrABox['visible'] = [0, 1, 1, 1, 0, 1, 1, 0, 1, 1, 0,0];
                            arrABox['sort'] = [0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0,0];
                            arrABox['align'] = [0, 1, 0, 2, 2, 1, 1, 1, 1, 2, 1,1];
                            arrABox['width'] = [0, 0, 280, 0, 0, 0, 0, 0, 0, 0, 0, 0];
                            arrABox['maxlen'] = [36, 20, 40, 20, 20, 20, 20, 20, 20, 20, 40,160];
                            $strLogs

                        g_arrMegaABox[0] = arrABox;
                        CreateABox(0);
                    </script>
                    </td></tr>
                ";
            }
        } else {
            if (!$_SESSION['account']['login']) {
            $strTableInner .= "
                <tr style='padding: 0'>
                    <td align='center' style='padding-top: 0'>
                        <table id='table_login' border='0' style='border-radius: 1em' background='"."index_files/transparent_blue_50x50.png"."'>
                        <tr><td align='center' valign='top'>
                        <table border='0' cellspacing='4'>
                            <tr>
                                <td width='100' align='right'><font face='Arial' size='1' color='" . WHITE_DARK ."'>Login: </font></td>
                                <td align='center'>
                                    <input type='text' class='text' value='' name='account_login' size='20' style='text-align: center; width: 120; font-size: 12px; font-family: Arial; color:" . WHITE_LIGHT . "; background-color:#000000; border: 1px solid " . WHITE_DARK . ";' onmouseover='this.style.border=\"1px solid " . AQUA_COMMON . "\"'; onmouseout='this.style.border=\"1px solid " . WHITE_DARK . "\"'>
                                </td>
                                <td width='100'></td>
                            </tr>
                            <tr>
                                <td align='right'><font face='Arial' size='1' color='" . WHITE_DARK ."'>Password: </font></td>
                                <td align='center'>
                                    <input type='password' class='text' value='' name='account_pswd' size='20' style='text-align: center; width: 120; font-size: 12px; font-family: Arial; color:" . WHITE_LIGHT . "; background-color:#000000; border: 1px solid " . WHITE_DARK . ";' onmouseover='this.style.border=\"1px solid " . AQUA_COMMON . "\"'; onmouseout='this.style.border=\"1px solid " . WHITE_DARK . "\"'>
                                </td>
                                <td><a href='index.php?show=lostpw'><font size='1'>" . Dictionary('forgot_password') . "</font></a></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td align='center'>
                                    <input type='submit' value='" . Dictionary('login') . "' name='B3' style='width: 120px'>
                                    <input type=hidden name='account_login_form' value='1'>
                                </td>
                                <td><a href='index.php?show=registration'><font size='1'>" . Dictionary('registration') . "</font></a></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td align='center'>
                                    <table>
                                        <tr>
                                            <td>
                                                <input type='checkbox' name='account_remember'>
                                            </td>
                                            <td>
                                                <font face='Arial' size='1' color='" . WHITE_DARK ."'>" . Dictionary('remember_me') . "</font>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td></td>
                            </tr>
                        </table>
                        </td></tr>
                        </table>
                        $strTableInnerMsg
                    </td>
                </tr>";
            } else
            {
            $strTableInner .= "
                <tr style='padding: 0'>
                    <td align='center' style='padding-top: 0'>
                        <table id='table_login' border='0' style='border-radius: 1em' background='"."index_files/transparent_blue_50x50.png"."'>
                        <tr><td align='center' valign='top'>
                        <table border='0' cellspacing='4'>
                            <tr>
                                <td width='320' align='center'><font color='#FF0000'>Access denied</font></td>
                            </tr>
                        </table>
                        </td></tr>
                        </table>
                        $strTableInnerMsg
                    </td>
                </tr>";
            }
        }

            $strTableInner .= "
                <tr>
                    <td align='center'>
                        <font color='" . WHITE_DARK . "' face='Arial' size='2'>$strMsg</font>
                    </td>
                </tr>
                $strJSBox";

        $strTableInner .= BotHtml();

        $strTableInner .= "     <tr>";
        $strTableInner .= "         <td id='submit_td' align='center' height='30' background='" . VISTA_PANEL . "' style='padding: 0'>";
        $strTableInner .= "         </td>";
        $strTableInner .= "     </tr>";

        ShowHTML($strTableInner, SERVER_NAME, false);
    }

    function ShowSearch($arrLogs, $intFlag, $what, $vs) {
        global $NameUni;
        $strTableInner = "      <tr><td align='center'>
                                    <table><tr><td>
                                        <font class='h2'>Search</font>
                                    </td>
                                    <td width='4'></td>
                                    <td>
                                        <img src='" . "index_files/vista_panel/icon_search.png" . "' width='32'>
                                    </td>
                                    <td width='4'></td>
                                    <td>
                                        <font class='h2'>Logs</font>
                                    </td></tr></table>
                                </td></tr>";

        $strTableInnerMsg = '';
        $objDlgWnd = new cDlgWnd();
        switch($intFlag) {
            case "ERR_USERSEARCH_CODE":
                $strTableInnerMsg = str_replace('display: none; position', 'display: block; position', $objDlgWnd->CreateDlgHTML("error_1", Dictionary("error"), "
                    Error: Неправильный код
                    <br><br>
                    Попробуйте еще раз и проверьте данные, которые вы вводили.
                    "));
                break;
        }                       

        $strDomainSelect = "    <select size='1' name='select_domain' style='font-size: 10px; width: 120px;' onchange='" . JSCookie("index_select_domain", "\"+this.selectedIndex+\"") . "'>
                                    <option value='0'>Domain: any</option>
                                    <option value='AR'>AR</option>
                                    <option value='BG'>BG</option>
                                    <option value='BR'>BR</option>
                                    <option value='HU' >HU</option>
                                    <option value='DE'>DE</option>
                                    <option value='GR'>GR</option>
                                    <option value='DK'>DK</option>
                                    <option value='ES'>ES</option>
                                    <option value='IT'>IT</option>
                                    <option value='LV'>LV</option>
                                    <option value='LT'>LT</option>
                                    <option value='MX'>MX</option>
                                    <option value='NL'>NL</option>
                                    <option value='NO'>NO</option>
                                    <option value='ORG'>ORG</option>
                                    <option value='PL'>PL</option>
                                    <option value='PT'>PT</option>
                                    <option value='RU' selected>RU</option>
                                    <option value='RO'>RO</option>
                                    <option value='US'>US</option>
                                    <option value='SK'>SK</option>
                                    <option value='SI'>SI</option>
                                    <option value='TW'>TW</option>
                                    <option value='TR'>TR</option>
                                    <option value='FI'>FI</option>
                                    <option value='FR'>FR</option>
                                    <option value='CZ'>CZ</option>
                                    <option value='SE'>SE</option>
                                    <option value='JP'>JP</option>
                                </select>";
        $strUniSelect = "       <select size='1' name='select_uni' style='font-size: 10px; width: 120px;' onchange='" . JSCookie("index_select_uni", "\"+this.value+\"") . "'>
                                    <option value='0'>Universe: any</option>";
        foreach ($NameUni as $key => $value) {
            if ($value[1] != "?") {
                if (gettype($value[0]) == "integer") $value[0] = "Universe";
                $selectedUni = (isset($_COOKIE["index_select_uni"]) && $_COOKIE["index_select_uni"] == $key) ? " selected" : false;
                $strUniSelect .= "<option value='" . $key . "'" . $selectedUni . ">" . $key . ". " . $value[0] . "</option>";
            }
        }

        $strUniSelect .= "      </select>";
                                    for ($i = 0; $i < 17; $i++){
                                        $selectedLosses[$i] = (isset($_COOKIE["index_select_losses"]) && $_COOKIE["index_select_losses"] == $i) ? "selected" : false;
                                    }
        $strLossesSelect = "    <select size='1' name='select_losses' style='font-size: 10px; width: 120px;' onchange='" . JSCookie("index_select_losses", "\"+this.selectedIndex+\"") . "'>
                                    <option value='0' $selectedLosses[0]>Losses: any</option>
                                    <option value='1' $selectedLosses[1]>100.000+</option>
                                    <option value='2' $selectedLosses[2]>200.000+</option>
                                    <option value='3' $selectedLosses[3]>400.000+</option>
                                    <option value='4' $selectedLosses[4]>800.000+</option>
                                    <option value='5' $selectedLosses[5]>1.600.000+</option>
                                    <option value='6' $selectedLosses[6]>3.200.000+</option>
                                    <option value='7' $selectedLosses[7]>6.400.000+</option>
                                    <option value='8' $selectedLosses[8]>12.800.000+</option>
                                    <option value='9' $selectedLosses[9]>25.600.000+</option>
                                    <option value='10' $selectedLosses[10]>51.200.000+</option>
                                    <option value='11' $selectedLosses[11]>102.400.000+</option>
                                    <option value='12' $selectedLosses[12]>204.800.000+</option>
                                    <option value='13' $selectedLosses[13]>409.600.000+</option>
                                    <option value='14' $selectedLosses[14]>819.200.000+</option>
                                    <option value='15' $selectedLosses[15]>1.638.400.000+</option>
                                    <option value='16' $selectedLosses[16]>3.276.800.000+</option>
                                </select>";
                                    for ($i = 0; $i < 3; $i++){
                                        $selectedPublic[$i] = (isset($_COOKIE["index_select_public"]) && $_COOKIE["index_select_public"] == $i) ? "selected" : false;
                                    }
        $strPublicSelect = "
                                <select size='1' name='select_public' style='font-size: 10px; width: 120px;' onchange='" . JSCookie("index_select_public", "\"+this.selectedIndex+\"") . "'>
                                    <option value='' $selectedPublic[0]>Public: any</option>
                                    <option value='1' $selectedPublic[1]>Public: yes</option>
                                    <option value='0' $selectedPublic[2]>Public: no</option>
                                </select>";
                                    for ($i = 0; $i < 3; $i++){
                                        $selectedLimit[$i] =  (isset($_COOKIE["index_select_limit"]) && $_COOKIE["index_select_limit"] == $i) ? "selected" : false;
                                    }
        $strLimitSelect = "
                                <select size='1' name='select_limit' style='font-size: 10px; width: 120px;' onchange='" . JSCookie("index_select_limit", "\"+this.selectedIndex+\"") . "'>
                                    <option value='15' $selectedLimit[0]>Limit: 15</option>
                                    <option value='25' $selectedLimit[1]>Limit: 25</option>
                                    <option value='50' $selectedLimit[2]>Limit: 50</option>
                                </select>";
        $strTableInner .= "
                            <tr>
                                <td align='center'>
                                    <table background='" . SEARCH_IMG . "' width='640' height='110'>
                                        <tr align='left'>
                                            <td width='120'><input type='text' class='text' name='text_name' id='text_name' size='20' value='" . $what . "' style='border-radius: 5px; width: 120; font-size: 12px; font-family: Arial; color:" . BLACK . "; background-color:" . WHITE_LIGHT . "; border: 1px solid " . WHITE_DARK . ";'></td>
                                            <td width='10'>vs.</td>
                                            <td width='120'><input type='text' class='text' name='vs' id='vs' size='20' value='" . $vs . "' style='border-radius: 5px; width: 120; font-size: 12px; font-family: Arial; color:" . BLACK . "; background-color:" . WHITE_LIGHT . "; border: 1px solid " . WHITE_DARK . ";'></td>
                                            <td><input type='submit' value='&#9658; Search' name='SubmitButton' id='SubmitButton' style='width:120'></td>
                                        </tr>
                                        <tr>
                                            <td>$strDomainSelect</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>$strUniSelect</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>$strLossesSelect</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </table>
                                    <input id='protect' name='protect' type='hidden'>
                                    <input type='hidden' name='user_search_form' value='1'>
                                    $strTableInnerMsg
                                </td>
                            </tr>";

            //$varInput['type'] = "my"; !!!

            $strMsg = (!$arrLogs) ? "<font color='" . RED_COMMON . "' face='Arial' size='2'>No logs found</font>" : "";

            $strJSBox = '';

            if (count($arrLogs)) {
                $strLogs = "";
                foreach ($arrLogs as $value) {
                    if ($vs) {
                        $strTitle = explode("vs.", $value['title']);
                        $strPosA = stripos($strTitle[1], $what);
                        $strPosD = stripos($strTitle[1], $vs);
                        if ($strPosA === false && $strPosD !== false) {
                            $strLogs .= "arrABox['data'].push([";
                            $strLogs .= "'" . $value['log_id'] . "',";
                            $strLogs .= "" . $value['date'] . ",";
                            $strLogs .= "'" . $value['title'] . "',";
                            $strLogs .= "" . ($value['losses']) . ",";
                            $strLogs .= "'" . "?" . "',";
                            $strLogs .= "'" . ShortNameUni($value['universe'],false) . "',";
                            $strLogs .= "'" . strtolower($value['domain']) . "',";
                            $strLogs .= "'" . "?" . "',";
                            $strLogs .= "" . $value['views'] . ",";
                            $strLogs .= "]);\n";
                        }
                    } else {
                        $strLogs .= "arrABox['data'].push([";
                        $strLogs .= "'" . $value['log_id'] . "',";
                        $strLogs .= "" . $value['date'] . ",";
                        $strLogs .= "'" . $value['title'] . "',";
                        $strLogs .= "" . ($value['losses']) . ",";
                        $strLogs .= "'" . "?" . "',";
                        $strLogs .= "'" . ShortNameUni($value['universe'],false) . "',";
                        $strLogs .= "'" . strtolower($value['domain']) . "',";
                        $strLogs .= "'" . "?" . "',";
                        $strLogs .= "" . $value['views'] . ",";
                        $strLogs .= "]);\n";
                    }

                }
                $strJSBox = "
                    <tr><td>
                    <script language='javascript'>
                        var g_arrMegaABox = [];

                        var arrABox = {id:'abox_0', header:[], visible:[], sort:[], sort_f:1, sort_o:1, align:[], width:[], maxlen:[], data:[]};
                            arrABox['header'] = ['Id', 'Date', 'Title', 'Losses', 'Profit', 'Uni', 'Lang', 'Res', 'Views',];
                            arrABox['visible'] = [0, 1, 1, 1, 0, 1, 1, 0, 1, 1, 0,0];
                            arrABox['sort'] = [0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0,0];
                            arrABox['align'] = [0, 1, 0, 2, 2, 1, 1, 1, 1, 2, 1,1];
                            arrABox['width'] = [0, 0, 280, 0, 0, 0, 0, 0, 0, 0, 0, 0];
                            arrABox['maxlen'] = [36, 20, 40, 20, 20, 20, 20, 20, 20, 20, 40,160];
                            $strLogs

                        g_arrMegaABox[0] = arrABox;
                        CreateABox(0);
                    </script>
                    </td></tr>
                ";
            }
            $strTableInner .= "
                <tr>
                    <td align='center'>
                        <font color='" . WHITE_DARK . "' face='Arial' size='2'>$strMsg</font>
                    </td>
                </tr>
                $strJSBox";

        $strTableInner .= BotHtml();

        $strTableInner .= "     <tr>";
        $strTableInner .= "         <td id='submit_td' align='center' height='30' background='" . VISTA_PANEL . "' style='padding: 0'>";
        $strTableInner .= "         </td>";
        $strTableInner .= "     </tr>";

        ShowHTML($strTableInner, SERVER_NAME, false);
    }
    
    function ReadNews($s) {
            $arrLines = read_file_tail(FILE_NEWS, $s);
            $strHTML = "<table border='0' style='border-collapse: collapse' cellpadding='0'>";
            $strHTML .= "<tr><td height='10'></td></tr>";
            $intGreen = 15;
            foreach ($arrLines as $strNews) {
                $strHTML .= "<tr><td><font face='Arial' size='1' color='#00" . dechex($intGreen) . dechex($intGreen) . "00'>" . $strNews . "</font></td></tr>";
                $intGreen -= 4;
            }
            $strHTML .= "</table>";
        return $strHTML;
    }

    function JSCookie($strKey, $strValue) {
        return 'document.cookie="' . $strKey . '=' . $strValue . '; expires=' . COOKIE_EXP . '"';
    }

    function PrepareSpyReport($strReturn) {
        global $NameUni;

        $urlWebSim = "http://websim.speedsim.net/index.php?lang=" . $_COOKIE["lang"];
        $strTableInner = "
            <tr>
                <td>
                    <link id='index_css' rel='stylesheet' type='text/css' href='" . CSS_ESPI . "' media='screen' />
                    <center>
                    <div class='detail_msg'>
                        <div class='detail_msg_head'>
                            <span class='msg_title new blue_txt'>Разведданные с " . $strReturn->generic->defender_planet_name . " [" . $strReturn->generic->defender_planet_coordinates . "]</span>
                            <span class='msg_date'>" . $strReturn->generic->event_time . "</span><br/>
                        </div>
                    <div class='detail_msg_ctn'>                        
                        <div class='detail_txt'><span>Игрок " . $strReturn->generic->defender_name . "</span></div>
                        <div class='detail_txt'>Шанс на защиту от шпионажа: " . $strReturn->generic->spy_fail_chance . "%";
                    if ($strReturn->generic->activity != "-1")
                        $strTableInner .= "
                            <div class=''>Ваш зонд установил аномалии в атмосфере планеты,<br>указывающие на активность в последние <font color='red'>" . $strReturn->generic->activity . "</font> минут.</div>
                        </div>";                        
                        else
                        $strTableInner .= "
                            <div class=''>Ваш зонд не нашёл никаких аномалий. Активности не обнаружено.</div>
                        </div>";

                    $strCore = explode(":", $strReturn->generic->defender_planet_coordinates);

                    $urlTrashSim = "https://trashsim.universeview.be/?SR_KEY=sr-"  . $strReturn->strDomain . "-"  . $strReturn->strUni . "-" . $strReturn->generic->sr_id; 

                    $strTableInner .= "
                    <div class='msg_actions clearfix'>
                        <a target='_blank' href='https://s" . $strReturn->strUni . "-" . $strReturn->strDomain . ".ogame.gameforge.com/game/index.php?page=fleet1&galaxy=" . $strCore[0] . "&system=" . $strCore[1] . "&position=" . $strCore[2] . "&type=1&mission=1' class='icon_nf_link fleft'>
                            <span class='icon_nf icon_attack tooltip js_hideTipOnMobile' title='Атака'></span>
                        </a>
                        
                        <a target='_blank' href='https://s" . $strReturn->strUni . "-" . $strReturn->strDomain . ".ogame.gameforge.com/game/index.php?page=fleet1&galaxy=" . $strCore[0] . "&system=" . $strCore[1] . "&position=" . $strCore[2] . "&type=1&mission=6' class='icon_nf_link fleft'>
                            <span class='icon_nf icon_espionage tooltip js_hideTipOnMobile' title='Шпионаж'></span>
                        </a>                    

                        <a id='go_speedsim' target='_blank' href='#' class='icon_nf_link fleft'>
                            <span class='icon_nf icon_speedsim tooltip js_hideTipOnMobile' title='SpeedSim'></span>
                        </a>

                        <a target='_blank' href='" . $urlTrashSim . "' class='icon_nf_link fleft'>
                            <span class='icon_nf icon_trashsim tooltip js_hideTipOnMobile' title='Trashsim'></span>
                        </a>
                    </div>";
                    $strTableInner .= "                                     
                    <div class='section_title'>
                        <div class='c-left'></div>
                        <div class='c-right'></div>
                        <span class='title_txt'>Сырьё</span>
                    </div>
                    <div style='height:25px; margin:0 25% 0 25%; width:100%;'>
                        <div class='resource_list_el tooltipCustom' title='" . NumberToString($strReturn->details->resources->metal) . "'>
                            <div class='resourceIcon metal'></div> 
                            <span class='res_value'>" . NumberToString($strReturn->details->resources->metal) . "</span>
                        </div>
                        <div class='resource_list_el tooltipCustom' title='" . NumberToString($strReturn->details->resources->crystal) . "'>
                            <div class='resourceIcon crystal'></div> 
                            <span class='res_value'>" . NumberToString($strReturn->details->resources->crystal) . "</span>
                        </div>
                        <div class='resource_list_el tooltipCustom' title='" . NumberToString($strReturn->details->resources->deuterium) . "'>
                            <div class='resourceIcon deuterium'></div>
                            <span class='res_value'>" . NumberToString($strReturn->details->resources->deuterium) . "</span>
                        </div>
                    </div>";

                    $strTableInner .= "<div class='section_title'><div class='c-left'></div><div class='c-right'></div><span class='title_txt'>Флоты</span></div>";
                    $strTableInner .= "<ul class='detail_list clearfix'>";
                    if ($strReturn->generic->failed_ships)
                        $strTableInner .= "<li class='detail_list_fail'>Мы не смогли получить достоверную информацию этого типа при проверке.</li>";
                    else {
                        foreach ($strReturn->details->ships as $key => $ships) {
                            $urlWebSim .= "&ship_d0_" . GetWebSimName($ships->ship_type). "_b=" . $ships->count;
                            $strTableInner .= "<li class='detail_list_el'>
                                <div class='shipImage float_left'>
                                    <img class='tech" . $ships->ship_type . "' width='28' height='28' src='http://gf2.geo.gfsrv.net/cdndf/3e567d6f16d040326c7a0ea29a4f41.gif'>
                                </div>
                                <span class='detail_list_txt'>" . Dictionary("sp_" . $ships->ship_type) . "</span>
                                <span class='fright' style='margin-right: 10px;'>" . $ships->count . "</span>
                            </li>";
                        }
                    }                       
                    $strTableInner .= "</ul>";   

                    $strTableInner .= "<div class='section_title'><div class='c-left'></div><div class='c-right'></div><span class='title_txt'>Оборона</span></div>";
                    $strTableInner .= "<ul class='detail_list clearfix'>";
                    if ($strReturn->generic->failed_defense)
                        $strTableInner .= "<li class='detail_list_fail'>Мы не смогли получить достоверную информацию этого типа при проверке.</li>";
                    else {                  
                        foreach ($strReturn->details->defense as $key => $defense) {
                            $urlWebSim .= "&ship_d0_" . GetWebSimName($defense->defense_type). "_b=" . $defense->count;
                            $strTableInner .= "<li class='detail_list_el'>
                                <div class='defense_image float_left'>
                                    <img class='defense" . $defense->defense_type . "' width='28' height='28' src='http://gf2.geo.gfsrv.net/cdndf/3e567d6f16d040326c7a0ea29a4f41.gif'>
                                </div>
                                <span class='detail_list_txt'>" . Dictionary("sp_" . $defense->defense_type) . "</span>
                                <span class='fright' style='margin-right: 10px;'>" . $defense->count . "</span>
                            </li>";
                        }                   
                    }
                    $strTableInner .= "</ul>";  

                    $strTableInner .= "<div class='section_title'><div class='c-left'></div><div class='c-right'></div><span class='title_txt'>Постройки</span></div>";
                    $strTableInner .= "<ul class='detail_list clearfix'>";
                    if ($strReturn->generic->failed_buildings)
                        $strTableInner .= "<li class='detail_list_fail'>Мы не смогли получить достоверную информацию этого типа при проверке.</li>";
                    else {
                        foreach ($strReturn->details->buildings as $key => $buildings)
                        $strTableInner .= "<li class='detail_list_el'>   
                                <div class='building_image float_left'>
                                    <img class='building" . $buildings->building_type . "' width='28' height='28' src='http://gf2.geo.gfsrv.net/cdndf/3e567d6f16d040326c7a0ea29a4f41.gif'>
                                </div>
                                <span class='detail_list_txt'>" . Dictionary("sp_" . $buildings->building_type) . "</span>
                                <span class='fright' style='margin-right: 10px;'>" . $buildings->level . "</span>
                            </li>";
                    }
                    $strTableInner .= "</ul>";

                    $strTableInner .= "<div class='section_title'><div class='c-left'></div><div class='c-right'></div><span class='title_txt'>Исследования</span></div>";
                    $strTableInner .= "<ul class='detail_list clearfix'>";
                    if ($strReturn->generic->failed_research)
                        $strTableInner .= "<li class='detail_list_fail'>Мы не смогли получить достоверную информацию этого типа при проверке.</li>";
                    else {                  
                        if ($strReturn->details->research) foreach ($strReturn->details->research as $key => $research) {
                            if ($research->research_type == 109) $urlWebSim .= "&tech_d0_0=" . $research->level;                        
                            if ($research->research_type == 110) $urlWebSim .= "&tech_d0_1=" . $research->level;                        
                            if ($research->research_type == 111) $urlWebSim .= "&tech_d0_2=" . $research->level;                        
                            $strTableInner .= "<li class='detail_list_el'>
                                <div class='research_image float_left'>
                                    <img class='research" . $research->research_type . "' width='28' height='28' src='http://gf2.geo.gfsrv.net/cdndf/3e567d6f16d040326c7a0ea29a4f41.gif'>
                                </div>
                                <span class='detail_list_txt'>" . Dictionary("sp_" . $research->research_type) . "</span>
                                <span class='fright' style='margin-right: 10px;'>" . $research->level . "</span>
                            </li>";
                        }
                    }
                    $strTableInner .= "</ul>";
                        
                    $strTableInner .= "<script>document.getElementById('go_speedsim').href='" . $urlWebSim . "'</script>";
                    $strTableInner .= "
                    </div>
                </div>
                </center>
            </td>
        </tr>";

        $strTableInner .= BotHtml();

        $strTableInner .= "     <tr>";
        $strTableInner .= "         <td id='submit_td' align='center' height='30' background='" . VISTA_PANEL . "' style='padding: 0'>";
        $strTableInner .= "         </td>";
        $strTableInner .= "     </tr>";

        $strTitle = SERVER_NAME . " - [" . $NameUni[$strReturn->strUni][1] . "] " . $strReturn->generic->defender_name . " [" . $strReturn->generic->defender_planet_coordinates . "]";

        $strMeta = "<meta name=\"twitter:card\" content=\"summary_large_image\">
                <meta name=\"twitter:site\" content=\"LogServer.Net\">
                <meta name=\"twitter:creator\" content=\"Demon\">
                <meta name=\"twitter:title\" content=\"[" . $NameUni[$strReturn->strUni][1] . "] " . $strReturn->generic->defender_name . " [" . $strReturn->generic->defender_planet_coordinates . "]\">
                <meta name=\"twitter:image\" content=\"https://logserver.net/img.php?id=" . KillInjection(mb_strimwidth($_GET['id'], 0, 36, "")) . "\">";

        ShowHTML($strTableInner, $strTitle, $strMeta); 
        exit;

        return $strReturn;
    }
?>