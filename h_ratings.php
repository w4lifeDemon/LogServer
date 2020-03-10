<?php
error_reporting (0);
date_default_timezone_set('Europe/Moscow');
    require 'h_abox.php';
    require 'h_constants.php';
    require 'h_db.php';
    require 'h_files.php';
    require 'h_functions.php';
        
    $rating = new ratings();
    
    if (!$_GET['list'])
    isset($_POST['fetch']) ? $rating->get_ratings() : $rating->vote();
    else $rating->get_all_ratings();
    
    class ratings {
        var $data_file = './txt/ratings';
        private $widget_id;
        private $data = array();
    
        function __construct() {
    
            $this->widget_id = KillInjection($_POST['widget_id']);
    
            $all = file_get_contents($this->data_file);
    
            if($all) {
                $this->data = unserialize($all);
            }
        }

        public function get_all_ratings() {
            foreach ($this->data as $key1 => $value1) {
                if ($value1 != "") 
                    foreach ($value1 as $key2 => $value2) {
                        if ($key2 == "number_votes") {
                            $oneVotes = $value2;                        
                        } 
                        if ($key2 == "dec_avg") {
                            $oneAvg = $value2;                        
                        }
                        if ($key2 == "time" && $oneVotes > 2) {
                            $varTime[$key1] = $value2;
                            $varAvg[$key1] = $oneAvg;
                            $varVotes[$key1] = $oneVotes;
                        }
                }
            }
            arsort($varTime);
                        
            $a = 0;                        
            foreach ($varTime as $key => $value) {
                $a += 1;                               
                $varResult[$key] = $varAvg[$key];
                if ($a == 10) break;                                
            }
                                    
            arsort($varResult);

            $strNum = 0;
        	$strLogs = '<table class="contents" style="border-collapse: collapse" border="1" bordercolor="#222222" cellpadding="4" width="780">';
    			    $strLogs .= '<tr height="28">
                        <td align="center" background="index_files/abox/header.png" width="20"><font class="abox_text">#</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 1)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Date</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 2)" align="center" background="index_files/abox/header.png" width="280"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Title</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 3)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Losses</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 3)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Profit</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 5)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Uni</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 6)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Lang</font></td>
                        <td onmouseover="ActivateHeader(this)" onmouseout="DeactivateHeader(this)" onclick="StartSort(0, 9)" align="center" background="index_files/abox/header.png" width="0"><font class="abox_text" onmouseover="style.cursor=(\'default\')">Rating</font></td>
                   </tr>';
                   
            foreach ($varResult as $key => $value) {
                $strNum += 1;
                //echo $key . " => " . $value . "  " . $varTime[$key] . "<br>";
                $strQuery = "SELECT `log_id`, `universe`, `domain`, `losses`, `aprofit`, `dprofit`, `date`, `title`, `public`, `views` FROM `T_LOGS_N` WHERE `log_id` = '$key' LIMIT 1";
    			$arrResult = cDB::QueryDB($strQuery);

                $arrBinLog = $arrResult->fetch_array(MYSQLI_ASSOC);
    			if ($arrBinLog) {                     
    					$strLogs .= '<tr onmouseover="ActivateRow(this)" id="abox_' . base64_encode($arrBinLog["log_id"]) . '" onmouseout="DeactivateRow(this)" src="index_files/abox/row_' . ($strNum % 2 + 1) . '.png" background="index_files/abox/row_' . ($strNum % 2 + 1) . '.png" height="28">
                            <td align="right"><font class="abox_text">' . $strNum . '</font></td>
                            <td align="center"><font class="abox_text">' . DateS($arrBinLog["date"]) . '</font></td>
                            <td align="left"><a href="index.php?id=' . $arrBinLog["log_id"] . '" target="_blank"><font size="2">' . cutStr($arrBinLog["title"]) . '</font></a></td>
                            <td align="center">' . NumberS($arrBinLog['losses']) . '</td>
                            <td align="center" style="font-size: 12px;">' . PrepareNumber($arrBinLog['aprofit']) . '/' . PrepareNumber($arrBinLog['dprofit']) . '</td>
                            <td align="center"><font class="abox_text">' . ShortNameUni($arrBinLog["universe"],false) . '</font></td>
                            <td align="center"><font class="abox_text">' . strtolower($arrBinLog["domain"]) . '</font></td>
                            <td align="center"><font class="abox_text">' . $value . '(' . PrepareNumber($varVotes[$key]) . ')</font></td>
                        </tr>';     
                }
            }
    	    $strLogs .= '</table>';
            echo $strLogs;            
        }
            
        public function get_ratings() {
            if($this->data[$this->widget_id]) {
                echo json_encode($this->data[$this->widget_id]);
            }
            else {
                $data['widget_id'] = $this->widget_id;
                $data['number_votes'] = 0;
                $data['total_points'] = 0;
                $data['dec_avg'] = 0;
                $data['whole_avg'] = 0;
                echo json_encode($data);
            }
        }
    
        public function vote() {
            # Get the value of the vote
            preg_match('/star_([1-5]{1})/', $_POST['clicked_on'], $match);
            $vote = $match[1];
    
            $ID = $this->widget_id;
            # Update the record if it exists
            if($this->data[$ID]) {
                $this->data[$ID]['number_votes'] += 1;
                $this->data[$ID]['total_points'] += $vote;
            }
            # Create a new one if it doesn't
            else {
                $this->data[$ID]['number_votes'] = 1;
                $this->data[$ID]['total_points'] = $vote;
            }
    
            $this->data[$ID]['dec_avg'] = round( $this->data[$ID]['total_points'] / $this->data[$ID]['number_votes'], 1 );
            $this->data[$ID]['whole_avg'] = round( $this->data[$ID]['dec_avg'] );
            $this->data[$ID]['time'] = time();
    
    
            file_put_contents($this->data_file, serialize($this->data));
            $this->get_ratings();
        }
    }
?>