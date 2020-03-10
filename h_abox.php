<?php
    class cABox {
        var $arrLogBox;
        function __construct() {
            if (isset($_SESSION['account']))
                if (isset($_SESSION['account']['log_box']))
                    $arrLogBox = $_SESSION['account']['log_box'];
        }
        
        function CreateBoxHTML() {
            
        }

            function XmlAlliancesTag ($strName, $strUni, $strDomain){
                $url = 'http://s'.$strUni.'-'.$strDomain.'.ogame.gameforge.com/api/players.xml';
                $xml = simplexml_load_file($url);    //Èíòåðïðåòèðóåò XML-ôàéë â îáúåêò

                foreach ($xml->children() as $players) {
                    if ($players['name'] == $strName){
                        $allianceID = trim($players['alliance']);
                        break;
                    }
                }
                $url = 'http://s'.$strUni.'-'.$strDomain.'.ogame.gameforge.com/api/alliances.xml';
                $xml = simplexml_load_file($url);    //Èíòåðïðåòèðóåò XML-ôàéë â îáúåêò

                foreach ($xml->children() as $alliance) {
                    if ($alliance['id'] == $allianceID)
                    {
                        $varResult = trim($alliance['tag']);
                        break;
                    }
                }

                return $varResult;
            }

    }
    function random_string($length, $chartypes) 
    {
        $chartypes_array=explode(",", $chartypes);
        // задаем строки символов. 
        //Здесь вы можете редактировать наборы символов при необходимости
        $lower = 'abcdefghijklmnopqrstuvwxyz'; // lowercase
        $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'; // uppercase
        $numbers = '1234567890'; // numbers
        $special = '^@*+-+%()!?'; //special characters
        $chars = "";
        // определяем на основе полученных параметров, 
        //из чего будет сгенерирована наша строка.
        if (in_array('all', $chartypes_array)) {
            $chars = $lower . $upper. $numbers . $special;
        } else {
            if(in_array('lower', $chartypes_array))
                $chars = $lower;
            if(in_array('upper', $chartypes_array))
                $chars .= $upper;
            if(in_array('numbers', $chartypes_array))
                $chars .= $numbers;
            if(in_array('special', $chartypes_array))
                $chars .= $special;
        }
        // длина строки с символами
        $chars_length = strlen($chars) - 1;
        // создаем нашу строку,
        //извлекаем из строки $chars символ со случайным 
        //номером от 0 до длины самой строки
        $string = $chars{rand(0, $chars_length)};
        // генерируем нашу строку
        for ($i = 1; $i < $length; $i = strlen($string)) {
            // выбираем случайный элемент из строки с допустимыми символами
            $random = $chars{rand(0, $chars_length)};
            // убеждаемся в том, что два символа не будут идти подряд
            if ($random != $string{$i - 1}) $string .= $random;
        }
        // возвращаем результат
        return $string;
    }
    
    function KillInjection ($str)
    {
        $search = array ( "'<script[^>]*?>.*?</script>'si",  // Âûðåçàåò javaScript
                            "'<[\/\!]*?[^<>]*?>'si",           // Âûðåçàåò HTML-òåãè
                            "'([\r\n])[\s]+'" );             // Âûðåçàåò ïðîáåëüíûå ñèìâîëû
        $replace = array ("", "", "\\1", "\\1" );
        $str = preg_replace($search, $replace, $str);
        $str = str_replace ("'", "", $str);
        $str = str_replace ("\"", "", $str);
        $str = str_replace ("%0", "", $str);
        $str = str_replace ('"', "", $str);
        return $str;
    }

    function listFakeAdmin() {
        $varAdmin = array ('asiman');
        return $varAdmin;
    }

    function listAdmin() {
        $varAdmin = array ('asiman');
        return $varAdmin;
    }

    //al_z - СГО Tottoro
    //rim - ГО SemFor
    function listOperators() {
        //$varAdmin = array (6975 => 'motorhead', 1 => 'asiman', 1819 => 'al_z', 2548 => 'Rim', 2942 => 'orlenok', 6808 => 'BlackDemon');
        $varAdmin = array (1 => 'asiman', 1819 => 'al_z', 2548 => 'Rim', 2942 => 'orlenok', 6808 => 'BlackDemon', 3137 => 'Aoueu');
        return $varAdmin;
    }

    function NumberS($intNumber, $s) {
        if ($intNumber < 0) $intNumber = $intNumber * -1;
        if ($intNumber < 1000) {
            $strValue = $intNumber;
            $strClass = 'abox_text';
        }
        else if ($intNumber < 1000000) {
            $strValue = (round($intNumber / 1000, 1)) . "K";
            $strClass = 'abox_text_yellow';
        }
        else if ($intNumber < 1000000000) {
            $strValue = (round($intNumber / 1000000, 1)) . "KK";
            $strClass = 'abox_text_green';
        }
        else if ($intNumber < 1000000000000) {
            $strValue = (round($intNumber / 1000000000, 1)) . "KKK";
            $strClass = 'abox_text_red';
        }
        else if ($intNumber < 1000000000000000) {
            $strValue = (round($intNumber / 1000000000000, 1)) . "KKKK";
            $strClass = 'abox_text_red';
        }        
        else
            $strClass = 'abox_text_red';

        if ($s) return $strValue;
        else return '<font class="' . $strClass . '">' . $strValue . '</font>';
    }

    function NumberPlugin($intNumber, $s) {
        if ($intNumber < 0) {
            $strMin = "-";
            $intNumber = $intNumber * -1;
        }

        if ($intNumber < 1000) {
            $strValue = $intNumber;
            $strClass = 'abox_text';
        }
        else if ($intNumber < 1000000) {
            $strValue = (round($intNumber / 1000, 1)) . "K";
            $strClass = 'abox_text_yellow';
        }
        else if ($intNumber < 1000000000) {
            $strValue = (round($intNumber / 1000000, 1)) . "Mn";
            $strClass = 'abox_text_green';
        }
        else if ($intNumber < 1000000000000) {
            $strValue = (round($intNumber / 1000000000, 1)) . "Bn";
            $strClass = 'abox_text_red';
        }
        else
            $strClass = 'abox_text_red';

        //if ($trueMin) $strValue = $strValue * -1;
        if ($s) return $strMin.$strValue;
        else return '<font class="' . $strClass . '">' . $strMin.$strValue . '</font>';
    }

    function DateS($strDate) {
        if ($strDate) {
            $strDate = getdate($strDate);
            if (strlen($strDate['mday']) > 1) $strMday = $strDate['mday'];
            else $strMday = "0".$strDate['mday'];
            if (strlen($strDate['mon']) > 1) $strMon = $strDate['mon'];
            else $strMon = "0".$strDate['mon'];
            $strYear = substr($strDate['year'], 2);
            return $strMday . '/' . $strMon . '/' . $strYear;
        }
    }

    function DateConstructor_6x($strDate, $blnHideTime) {
        if ($strDate) {
            $strDate = getdate($strDate);

            if (strlen($strDate['mday']) > 1) $strMday = $strDate['mday'];
            else $strMday = "0".$strDate['mday'];

            if (strlen($strDate['mon']) > 1) $strMon = $strDate['mon'];
            else $strMon = "0".$strDate['mon'];

            if (strlen($strDate['minutes']) > 1) $strMinutes = $strDate['minutes'];
            else $strMinutes = "0".$strDate['minutes'];

            if (strlen($strDate['seconds']) > 1) $strSeconds = $strDate['seconds'];
            else $strSeconds = "0".$strDate['seconds'];

            if ($blnHideTime == 1) return $strMday . '.' . $strMon . '.' . $strDate['year'] . " XX:XX:XX";
            else if ($blnHideTime == 2) return $strMday . '/' . $strMon . '/' . substr($strDate['year'], -2);
            else return $strMday . '.' . $strMon . '.' . $strDate['year'] . " " . $strDate['hours'] . ":" . $strMinutes . ":" . $strSeconds;
        }
    }

    function ViewsS($strViews) {
        if ($strViews) {
            if ($strViews >= 100) $strClass = 'abox_text_yellow';
            else if ($strViews >= 200) $strClass = 'abox_text_green';
            else if ($strViews >= 400) $strClass = 'abox_text_red';
            else $strClass = 'abox_text';
    
            return '<font class="' . $strClass . '">' . $strViews . '</font>';
        } else {
            return '<font class="abox_text">0</font>';
        }
    }
    
    function cutStr($str, $int){
        if (!$int) $int = 40;
        if (strlen($str) > $int) {
            $str = substr($str, 0, $int) . '...';
        }
    
        return $str;
    }
//type: success, notice, warning, error
    class PopM {
        function PopMessage ($type, $text)
        {
//        if ($type || $text){
            $PopMessageJs = "function PopMessageJs() {
                $().toastmessage('showToast', {
                text     : '" . $text . "',
                sticky   : true,
                position : 'top-right',
                type     : '" . $type . "',
                closeText: '',
                close    : function () {
                    console.log('toast is closed ...');
                    }
                });
            }";
        return $PopMessageJs;
//        }
    }
}

    function curlSendDiscord ($strWebHooks, $jsonData) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $strWebHooks);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Connection: Keep-Alive'
        ));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

        $result = curl_exec($ch);
        
        curl_close($ch);
    }

    function curlSendLogDiscord ($data) {
        $strUrl =     $data["url"];
        $strUni =     $data["uni"];
        $strDomain =  $data["domain"];
        $strPublic =  $data["public"];

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
    }
?>
