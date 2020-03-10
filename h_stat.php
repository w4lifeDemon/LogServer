<?php
/*
ID  Name
1       Player
2       Alliance
correct types are:
ID  Name
0   Total
1   Economy
2   Research
3   Military
5   Military Built
6   Military Destroyed
4   Military Lost
7   Honor
*/
$start = microtime(true);

    define("DB_HOST_STAT", "localhost");
    define("DB_USER_STAT", "logmaster");
    define("DB_PSWD_STAT", "i7*133@Yn4XXSav&=ES5,oU)");
    define("DB_NAME_STAT", "logserver-stat");

    class cDBStat {
        static function QueryDB($strQuery) {
            $objLink = mysqli_connect(DB_HOST_STAT, DB_USER_STAT, DB_PSWD_STAT, DB_NAME_STAT);

            $varResult = mysqli_query($objLink, $strQuery);

            if (!mysqli_close($objLink)) {
                return false;
            }
            return $varResult;
        }

        static function SaveStat($obj) {
            $strTime = time();
            $strQuery = "INSERT INTO  `T_STAT` (
                            `player_id`, 
                            `time`, 
                            `total_p`, 
                            `total_s`, 
                            `economy_p`, 
                            `economy_s`, 
                            `research_p`, 
                            `research_s`, 
                            `military_p`, 
                            `military_s`, 
                            `military_built_p`, 
                            `military_built_s`, 
                            `military_destroyed_p`, 
                            `military_destroyed_s`, 
                            `military_lost_p`, 
                            `military_lost_s`, 
                            `honor_p`, 
                            `honor_s`
                        )
                        VALUES (
                            '$obj[0]', 
                            '$strTime', 
                            '$obj[1]', 
                            '$obj[2]', 
                            '$obj[3]', 
                            '$obj[4]', 
                            '$obj[5]', 
                            '$obj[6]', 
                            '$obj[7]', 
                            '$obj[8]', 
                            '$obj[9]', 
                            '$obj[10]', 
                            '$obj[11]', 
                            '$obj[12]', 
                            '$obj[13]', 
                            '$obj[14]', 
                            '$obj[15]', 
                            '$obj[16]' 
                        );";
            if (!cDBStat::QueryDB($strQuery)) {
                return false;
            }
            return true;
        }
    }

    class cStat {
        function XmlLoad ($strId, $strUni, $strDomain) {
            $strType = array(0, 1, 2, 3, 4, 5, 6, 7);
            foreach ($strType as $type) {
                $url = 'https://s'.$strUni.'-'.$strDomain.'.ogame.gameforge.com/api/highscore.xml?category='.$strId.'&type='.$type;
                $xml = simplexml_load_file($url);

                foreach ($xml->children() as $players) {
                    $id = (int) $players['id'];
                    $position = (int) $players['position'];
                    $score = (int) $players['score'];
                    $varResult[$id][$type] = array('position' => $position, 'score' => $score); 
                }
            }

            return $varResult;
        }
    }

    foreach (cStat::XmlLoad (1, 103, "ru") as $key => $value) {
        $obj = array();
        $obj[0] = $key;
        foreach ($value as $k => $val) {
            $obj[] = $val['position'];
            $obj[] = $val['score'];
        }
        cDBStat::SaveStat($obj);
    }
  echo "Время выполнения скрипта: ".(microtime(true) - $start);

    /*
    echo "<table>";
    foreach (cStat::XmlLoad (1, 1, "ru") as $key => $value) {

        echo "<tr>";
        echo "<td>" . $key . "</td>";
        foreach ($value as $k => $val) {
                echo "<td>" . $val['position'] . "</td><td>" . $val['score'] . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>"
    */

?>