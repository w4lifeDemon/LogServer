<?php
error_reporting (0);

require 'h_abox.php';
require 'h_constants.php';
$varPage = KillInjection($_GET['page']);
$varName = KillInjection($_GET['name']);

    switch ($varName) {
        case "InfoCompte 3":                           $varIdUS = 133137; $varHTML = "<tr><td>Информация: сценарий показывает нам, во что вложены ресурсы (в шахты, технику, оборону, флот ...), и наш прогресс</td></tr>"; break;
        case "Activity Indicator":                     $varIdUS = 149332; break;
        case "Activitiy star":                         $varIdUS = 121311; $varHTML = "<tr><td>Показывает активность планеты и луны</td></tr>"; break;
        case "Additional Resource Loading Buttons":    $varIdUS = 81197; $varHTML = "<tr><td>Кнопки для \"Обнуление ресурсов\" и \"загрузка ресурсов в обратном порядке\" на 3-й странице отправки флота </td></tr>"; break;
        case "Alliance Chat":                          $varIdUS = 145822; $varHTML = "<tr><td>Игроки в альянсе общаются в режиме реального времени в игре.</td></tr>"; break;
        case "Alliance icon opens the message box":    $varIdUS = 58545; $varHTML = "<tr><td></td></tr>"; break;
        case "Alliance Stat":                          $varIdUS = 66064; $varHTML = "<tr><td>Статистика игроков в альянсе</td></tr>"; break;
        case "Art Galaxy":                             $varIdUS = 187281; $varHTML = "<tr><td>Визуализация галактики фоном</td></tr>"; break;
        case "Auction events list":                    $varIdUS = 134017; $varHTML = "<tr><td></td></tr>"; break;
        case "Auction Timer":                          $varIdUS = 136012; $varHTML = "<tr><td>Отображает таймер обратного отсчета для аукциона</td></tr>"; break;
        case "Available fields":                       $varIdUS = 178858; $varHTML = "<tr><td></td></tr>"; break;

        case "Cargos necessary":                       $varIdUS = 54539; $varHTML = "<tr><td>Количество транспорта, необходимого для перевозки всех ресурсов </td></tr>"; break;
        case "CerealOgameStats":                       $varIdUS = 134405; $varHTML = "<tr><td>Показывает статистику игроков альянса.</td></tr>"; break;
        case "Colored Moon Sizes in Galaxy View":      $varIdUS = 86403; $varHTML = "<tr><td></td></tr>"; break;
        case "Color Alliance":                         $varIdUS = 86378; $varHTML = "<tr><td>Скрипт для подсвечивания альянсов в галактике</td></tr>"; break;
        case "Color Friends":                          $varIdUS = 85715; $varHTML = "<tr><td>Скрипт для подсвечивания друзей в галактике</td></tr>"; break;
        case "Color Flight Flots":                     $varIdUS = 73289; $varHTML = "<tr><td>Выделение цветом, число свободных слотов флота: красный - нет слотов, желтый - один слот свободен, зеленый - свободно более 1 слота</td></tr>"; break;

        case "Direct Colonization":                    $varIdUS = 83845; $varHTML = "<tr><td>Удаляет пункт колонизации, когда нет слотов для полета.</td></tr>"; break;
        case "Disable attack warner":                  $varIdUS = 139044; $varHTML = "<tr><td>Скрипт отключения предупреждения об атаке</td></tr>"; break;
        case "Disable Espionage if Colonization is Available":$varIdUS = 69957; $varHTML = "<tr><td></td></tr>"; break;
        case "Display Resources [Pantalla Recursos]":  $varIdUS = 73101; $varHTML = "<tr><td>Расширенный показ ресурсов</td></tr>"; break;
        case "Defense Proposer":                       $varIdUS = 151824; $varHTML = "<tr><td></td></tr>"; break;

        case "Easy Rider":                             $varIdUS = 72438; $varHTML = "<tr><td></td></tr>"; break;
        case "Easy Transport":                         $varIdUS = 67948;
        $varGithub = 'https://github.com/hellpain/ogame_scripts/raw/master/scripts/easy_transport/easy_transport.user.js'; $varHTML = "<tr><td>Скрипт на отправку ресурсов необходимых для строительства/исследования</td></tr>"; break;
        case "Espionage report attack button":         $varIdUS = 95264; $varHTML = "<tr><td></td></tr>"; break;
        case "Expeditions statistics":                 $varIdUS = 186966; $varHTML = "<tr><td></td></tr>"; break;
        case "Expo Stats":                             $varIdUS = 158867; $varHTML = "<tr><td></td></tr>"; break;

        case "Fix the Action Icons":                   $varIdUS = 67948; $varHTML = "<tr><td>Показывает доступные с планетой(игроком) действия в меню галактике ( удаляет не нужные действия)</td></tr>"; break;
        case "Fleetpoints":                            $varIdUS = 106544; $varHTML = "<tr><td></td></tr>"; break;
        case "Fleet Contents":                         $varIdUS = 95547; $varHTML = "<tr><td>Показывает содержимое выбранного флота на второй и третьей страницах флота отправки</td></tr>"; break;
        case "Fleet Empty Space":                      $varIdUS = 103449; $varHTML = "<tr><td>Добавляет информацию о свободном месте в обзоре во флоте</td></tr>"; break;
        case "Fleet Proposer":                         $varIdUS = 152182; $varHTML = "<tr><td></td></tr>"; break;
        case "Fleet strength calculator":              $varIdUS = 121338; $varHTML = "<tr><td></td></tr>"; break;
        case "Fix the coordinates links":              $varIdUS = 86259; $varHTML = "<tr><td></td></tr>"; break;

        case "Galaxy Go":                              $varIdUS = 111997; $varHTML = "<tr><td></td></tr>"; break;
        case "Galaxy Info User":                       $varIdUS = 136509; $varHTML = "<tr><td>С помощью нового API OGame, сценарий может показать все данные пользователя доступные на Galaxy View (это доступность напрямую связана с обновлением информации API).</td></tr>"; break;

        case "Highscore improved":                     $varIdUS = 121415;
        $varGithub = 'https://github.com/hellpain/ogame_scripts/raw/master/scripts/highsrore_improved/highsrore_improved.user.js'; $varHTML = "<tr><td>Некоторые улучшения в топ-листе, бар для переключения между списками прокручивается вниз вместе с просмотром</td></tr>"; break;

        case "IRC Webchat module":                     $varIdUS = 94757; $varHTML = "<tr><td></td></tr>"; break;

        case "Keyboard Shortcuts":                     $varIdUS = 83284;
        $varGithub = 'https://github.com/hellpain/ogame_scripts/raw/master/scripts/keyboard_shortcut/keyboard_shortcut.user.js'; $varHTML = "<tr><td>Назначение сочетания клавиш для различных игровых функций</td></tr>"; break;

        case "Loots bbcode exporter":                  $varIdUS = 165249; $varHTML = "<tr><td></td></tr>"; break;
        case "Links for expedition and colonization":  $varIdUS = 111290; $varHTML = "<tr><td>Координаты(список) для экспедиции и колонизации на второй странице флот</td></tr>"; break;

        case "Merchant Warning":                       $varIdUS = 83847; $varHTML = "<tr><td></td></tr>"; break;
        case "Message button in left menu":            $varIdUS = 93205; $varHTML = "<tr><td></td></tr>"; break;
        case "Missing Sats":                           $varIdUS = 81699; $varHTML = "<tr><td>Показывает количество спутников, которые должны быть построены, для того, чтобы энергетический баланс стал положительный.</td></tr>"; break;
        case "Moons to the Right":                     $varIdUS = 71588; $varHTML = "<tr><td>Делает иконку луны больше и справа для более легкого нажатия.</td></tr>"; break;

        case "No tactical retreat tip":                $varIdUS = 119978; $varHTML = "<tr><td></td></tr>"; break;

        case "Odd save report":                        $varIdUS = 119978; $varHTML = "<tr><td></td></tr>"; break;
        case "OGame find player details":              $varIdUS = 136116; $varHTML = "<tr><td>Поиск игрока на маску поиска OGame, и найти подробную информацию анализируется из OGame-API (playerData.xml)</td></tr>"; break;
        case "OGame Fleet Tool":                       $varIdUS = 78537; $varHTML = "<tr><td></td></tr>"; break;
        case "Options in User Name":                   $varIdUS = 75283; $varHTML = "<tr><td>Пункт \"настройки\" перемешает в ник игрока сверху</td></tr>"; break;
        case "Old menu fleet":                         $varIdUS = 78307; $varHTML = "<tr><td></td></tr>"; break;
        case "Open Galaxy - public galaxy map":        $varIdUS = 70418; $varHTML = "<tr><td></td></tr>"; break;
        case "Oprojekt exporter bbcode formatted text":$varIdUS = 92002; $varHTML = "<tr><td></td></tr>"; break;

        case "Perfect Plunder":                        $varIdUS = 50680; $varHTML = "<tr><td></td></tr>"; break;

        case "Rankings in tooltip":                    $varIdUS = 163838; $varHTML = "<tr><td></td></tr>"; break;
        case "Resources on Transit":                   $varIdUS = 80016; $varHTML = "<tr><td></td></tr>"; break;
        case "Resources in Flight [by Bontchev]":                    $varIdUS = 58079;
        $varGithub = 'https://github.com/hellpain/ogame_scripts/raw/master/scripts/resources_in_flight/resources_in_flight.user.js'; $varHTML = "<tr><td>Отображает, сколько ресурсов в полете на странице движения флота</td></tr>"; break;

        case "Search Players Coordinates":             $varIdUS = 54542;
        $varGithub = 'https://github.com/hellpain/ogame_scripts/raw/master/scripts/search_players/search_players.user.js'; $varHTML = "<tr><td></td></tr>"; break;
        case "Small planets":                          $varIdUS = 93656; $varHTML = "<tr><td></td></tr>"; break;
        case "Smilies":                                $varIdUS = 54538; $varHTML = "<tr><td></td></tr>"; break;
        case "Script to adapt speedsim and Dragosim":  $varIdUS = 120885; $varHTML = "<tr><td></td></tr>"; break;
        case "SpyShare":                               $varIdUS = 372993; $varHTML = "<tr><td></td></tr>"; break;

        case "Time left to fill up storage":           $varIdUS = 108188; $varHTML = "<tr><td>Сколько времени осталось заполнить хранилища</td></tr>"; break;
        case "Trade Calculator":                       $varIdUS = 151002; $varHTML = "<tr><td>Добавляет калькулятор торговли</td></tr>"; break;
        case "Warning about last fleet slot":          $varIdUS = 163856; $varHTML = "<tr><td></td></tr>"; break;
        case "War Riders Extended":                    $varIdUS = 125820; $varHTML = "<tr><td></td></tr>"; break;
        case "Websim Extension":                       $varIdUS = 63749; $varHTML = "<tr><td></td></tr>"; break;

        case "OGame Board Improvments":                $varIdUS = 84178; $varHTML = "<tr><td></td></tr>"; break;

        default: $varIdUS = false; break;
    }


if ($varPage == 'showList') {
                        echo "<center><div style='background: url(index_files/transparent_50x50.png);' width='800' id='hide_" . preg_replace("/[^a-zа-я0-9]/i", "_", $varName) . "'>";
                        echo "<div style='height:10px;text-align:right; color:#CCCCCC; cursor: pointer;' onClick='$(\"#hide_" . preg_replace("/[^a-zа-я0-9]/i", "_", $varName) . "\").hide();'>&nbsp;&nbsp;x&nbsp;&nbsp;</div>";
                        echo "<table style='color:#DDDDDD' width='450'>";
                        echo $varHTML;
                        echo "</table>";
                        echo "</div></center>";
}

if ($varPage == 'instalList') {
    if ($varName && $varIdUS) {

                        echo "<center><div style='background: url(index_files/transparent_50x50.png);' width='800' id='hide_" . preg_replace("/[^a-zа-я0-9]/i", "_", $varName) . "'>";
                        echo "<div style='height:10px;text-align:right; color:#CCCCCC; cursor: pointer;' onClick='$(\"#hide_" . preg_replace("/[^a-zа-я0-9]/i", "_", $varName) . "\").hide();'>&nbsp;&nbsp;x&nbsp;&nbsp;</div>";
                        echo "<table style='color:#DDDDDD' width='450'>";
        //if ($varIdUS)   echo "<tr height='30'><td align='center' width='50'><font size='-2' color='#FF0000' style='cursor: pointer;' onClick='location.href=\"#note\"'>NOT WORK</font></td><td align='left' width='200' color='#CCCCCC'>UserScripts.org:</td><td align='center' width='100'>[<a target='_blank' href='http://userscripts.org/scripts/show/".$varIdUS."'>Show</a>]</td><td align='center' width='100'>[<a target='_blank' href='http://userscripts.org/scripts/source/".$varIdUS.".user.js'>Install</a>]</td></tr>";
        if ($varIdUS)   echo "<tr height='30'><td align='center' width='50'></td><td align='left' width='200' color='#CCCCCC'>UserScripts-Mirror.org:</td><td align='center' width='100'>[<a target='_blank' href='http://userscripts-mirror.org/scripts/show/".$varIdUS."'>Show</a>]</td><td align='center' width='100'>[<a target='_blank' href='http://userscripts-mirror.org/scripts/source/".$varIdUS.".user.js'>Install</a>]</td></tr>";
        if ($varIdUS)   echo "<tr height='30'><td align='center' width='50'></td><td align='left' width='200' color='#CCCCCC'>LogServer.net:</td><td align='center' width='100'></td><td align='center' width='100'>[<a target='_blank' href='" . str_replace("/h_script.php", "", $_SERVER["SCRIPT_URI"]) . "/plugin/js/".$varIdUS.".user.js'>Install</a>]</td></tr>";
        if ($varGithub) echo "<tr height='30'><td align='center' width='50'></td><td align='left' width='200' color='#CCCCCC'>GitHub.com:</td><td align='center' width='100'></td><td align='center' width='100'>[<a target='_blank' href='".$varGithub."'>Install</a>]</td></tr>";
                        echo "</table>";
                        echo "</div></center>";
    }
}
?>