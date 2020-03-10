<?php
function KillInjection ($str)
    {
        $search = array ( "'<script[^>]*?>.*?</script>'si",  // Вырезает javaScript
                            "'<[\/\!]*?[^<>]*?>'si",           // Вырезает HTML-теги
                            "'([\r\n])[\s]+'" );             // Вырезает пробельные символы
        $replace = array ("", "", "\\1", "\\1" );
        $str = preg_replace($search, $replace, $str);
        $str = str_replace ("'", "", $str);
        $str = str_replace ("\"", "", $str);
        $str = str_replace ("%0", "", $str);
        return $str;
    }

$redirect = KillInjection($_GET['r']);
$uni =      KillInjection($_GET['uni']);
$domain =   KillInjection($_GET['domain']);
$player =    KillInjection($_GET['player']);

if ($redirect == 'Infuza.com') {
    header("Location: http://www.infuza.com/ru/Search?pora=Players&server=ogame." . $domain . "&universe=Universe" . $uni . "&value=" . $player . "");
    exit;
}

if ($redirect == 'OpenGalaxy') {
    header("Location: http://opengalaxy.logserver.su/index.php?uni=uni" . $uni . "&domain=" . $domain . "&pname=" . $player . "");
    exit;
}

if ($redirect == 'Ogame-Pb.net') {
    header("Location: http://ogame-pb.net/rep2.php?language=" . $domain . "&uni=" . $uni . "&player=" . $player . "");
    exit;
}

if ($redirect == 'Ogniter.org') {
    if($uni == '1') $u = 152;
    if($uni == '10') $u = 153;
    if($uni == '10') $u = 154;

    if($uni == '101') $u = 140;
    if($uni == '102') $u = 141;
    if($uni == '103') $u = 142;
    if($uni == '104') $u = 143;
    if($uni == '105') $u = 144;
    if($uni == '106') $u = 145;
    if($uni == '107') $u = 146;
    if($uni == '108') $u = 147;
    if($uni == '109') $u = 148;
    if($uni == '110') $u = 149;
    if($uni == '111') $u = 150;
    if($uni == '112') $u = 151;

    if($uni == '113') $u = 399;
    if($uni == '114') $u = 413;
    if($uni == '115') $u = 428;
    if($uni == '116') $u = 446;
    if($uni == '117') $u = 458;
    if($uni == '118') $u = 491;
    if($uni == '119') $u = 508;
    if($uni == '120') $u = 515;
//server=140&search_by=player&name=&search=as
    if ($uni && $domain){
            echo '<form action="http://www.ogniter.org/'.$domain.'/search" method="post">
                        <input type="text" style="display:none" name="server" value="'.$u.'" />
                        <input type="text" style="display:none" name="search_by" value="player" />
            			<input type="text" style="display:none" name="name" value="" />
            			<input type="text" style="display:none" name="search" value="'.$player.'" />
            			<input type="submit" style="display:none" id="loginSubmit" value="Поиск" />
            	</form>';

            echo "<script type=\"text/javascript\">
            document.getElementById(\"loginSubmit\").click();
            </script>";
    }
}
?>
