<?php
class cHTMLConstructor_7x {
		private $objSource = null;
		private $strHTMLLogNew = "";
		private $arrCache = array();

		function __construct(&$objSource) {
			$this->objSource = $objSource;
		}

		public function Get($strWhat) {
			$varReturn = false;
			switch (strtolower($strWhat)) {
				case "uni":			$varReturn = $this->objSource->intUni; break;
				case "domain":		$varReturn = $this->objSource->strDomain; break;
				case "html":		$varReturn = $this->strHTMLLogNew; break;
				case "urlm":		$varReturn = $this->arrCache["urlm"]; break;
				case "longtitle":	$varReturn = $this->arrCache["longtitle"]; break;
				case "title":		$varReturn = $this->objSource->strTitle;  break;
				case "losses":		$varReturn = str_replace(".", "", $this->arrCache["SumLossesMC"]); break;
				case "bburl":		$varReturn = $this->arrCache["bburl"]; break;
				case "bbcode":		$varReturn = $this->arrCache["bbcode"]; break;
				case "profit":		$varReturn = $this->arrCache["profit"]; break;
				default:			LogError("objHTMLConstructor->Get", "Unknown input parameter: " . $strWhat); break;
			}
			return $varReturn;
		}

		public function Construct() {
			$strResult = "";
			$this->arrCache['combat_result'] = $this->ProcessCombatResult();
			$this->arrCache['recycler_report'] = $this->ProcessRecyclerReport();
			$this->arrCache['ipms'] = $this->ProcessIPMs();

			$this->strHTMLLogNew = $this->GetHTML();
			$this->HTMLBugFix();
			$this->strHTMLLogNew = str_replace("</head>", $strResult . "</head>", $this->strHTMLLogNew);
			$this->strHTMLLogNew = $this->ReplaceCSS();
			$strResult = $this->GetFleetsStatistics();
			$this->strHTMLLogNew = str_replace("<div id='combat_result'>", $strResult."<div id='combat_result'>", $this->strHTMLLogNew);
			$strResult = $this->GetStatistics();
			$this->strHTMLLogNew = str_replace("<!-- combat_result -->", $strResult . "<!-- combat_result -->", $this->strHTMLLogNew);
			$strResult = $this->GetSelfLink();
			$this->strHTMLLogNew = str_replace("<!-- master -->", "<!-- master -->" . $strResult, $this->strHTMLLogNew);
			$this->ReplaceTitle();
			$this->strHTMLLogNew .= "<script language='javascript'>arrImgs=document.getElementsByName(\"img_\"); strVis=\"none\"; for (i=0; i<arrImgs.length; i++) arrImgs[i].style.display=strVis</script>";

            return $this->strHTMLLogNew;
		}

		private function GetHTML() {
			$strHTML = $this->GetHeadHTML();
			$strHTML .= "<body id='combatreport' onload='LoadLog(), PopMessageJs();'>";

			if (key_exists('account', $_SESSION)) {
			$strLoginMsg = $_SESSION['account']['login'];
			$strPWMsg = "[<a href='index.php?show=changepass'><font size='1'>Change password</font></a>]";
			$strLogoutMsg = "[<a href='index.php?logout=1'><font size='1'>Logout</font></a>]";
		}
		else {
			$strLoginMsg = "Login"; $strPWMsg = "[<a href='index.php?show=lostpw'><font size='1'>Forgot Password</font></a>]"; $strLogoutMsg = "";
		}

		$strLoginUser = "<div id = 'login_'><font color='#888888' face='Arial' size='1'>User: [<a href='index.php?show=account'><font size='1'>$strLoginMsg</font></a>] $strLogoutMsg</font></div>";
		  $strLangUser = "<td align='left'>
                                        <table>
                                            <tr>
                                                <td width='20' height='14'><img src='index_files/flag_empty.png' height='14' width='20' class='lang' alt='bg' title='Bulgarian' border='0' style='cursor:pointer; background: transparent url(index_files/mmoflags.png) no-repeat; background-position:left -42px !important'></td>
            									<td width='4'>&nbsp;</td>
            									<td width='20' height='14'><img src='index_files/flag_empty.png' height='14' width='20' class='lang' alt='de' title='German' border='0' style='cursor:pointer; background: transparent url(index_files/mmoflags.png) no-repeat; background-position:left -168px !important'></td>
            									<td width='4'>&nbsp;</td>
            									<td width='20' height='14'><img src='index_files/flag_empty.png' height='14' width='20' class='lang' alt='en' title='English' border='0' style='cursor:pointer; background: transparent url(index_files/mmoflags.png) no-repeat; background-position:left -224px !important'></td>
            									<td width='4'>&nbsp;</td>
            									<td width='20' height='14'><img src='index_files/flag_empty.png' height='14' width='20' class='lang' alt='fr' title='French' border='0' style='cursor:pointer; background: transparent url(index_files/mmoflags.png) no-repeat; background-position:left -280px !important'></td>
            									<td width='4'>&nbsp;</td>
            									<td width='20' height='14'><img src='index_files/flag_empty.png' height='14' width='20' class='lang' alt='ru' title='Russian' border='0' style='cursor:pointer; background: transparent url(index_files/mmoflags.png) no-repeat; background-position:left -672px !important'></td>
            									<td width='4'>&nbsp;</td>
            									<td width='20' height='14'><img src='index_files/flag_empty.png' height='14' width='20' class='lang' alt='ua' title='Ukrainian' border='0' style='cursor:pointer; background: transparent url(index_files/mmoflags.png) no-repeat; background-position:left -770px !important'></td>
                                            </tr>
                                        </table>
                                    </td>";

			$strHTML .= "<form name='upload_form' id='upload_form' enctype='multipart/form-data' action='index.php' method='post'>
				<center>
				<div style='height:30px'><table id='stick_menu' width='100%' border='0' style='border-collapse: collapse; z-index:999;' cellpadding='10' background='".TABLE_BACKGROUND."'>
					<tr><td height='4' style='padding: 0'></td></tr>
					<tr>
						<td align='center' valign='center' background='".VISTA_PANEL."' style='padding: 0px'>
                        	<table border='0' style='border-collapse: collapse;'>
								<tr>
									<td height='30' width='20'></td>
									<td style='align:left; vertical-align:middle;'>$strLoginUser</td>
									<td width='20' height='30' align='left' background='"."index_files/vista_panel/vista_exp_20x30.png"."'></td>
									<td height='30' align='center'>";

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
            $strWarURL = "index.php?show=war";
            $strSearchURL = "index.php?show=search";

			$strHTML .= "<table border='0' bordercolor='#000000' style='border-collapse: collapse' cellpadding='0'>
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
											<td>$strLangUser</td>
                                </tr>

							</table></div>

						</td>
					</tr>
					<tr>
							<td align='center'>";

		$strAccountURL = "index.php?show=account";
		$strLogoutURL = "index.php?logout=1";

		$strHTML .= "			</td>";
		$strHTML .= "		</tr>";
		$strHTML .= "	</table>";
		$strHTML .= "	</center>";
		$strHTML .= "	</form>";


				$strHTML .= "<div id='master'>";
					$strHTML .= $this->GetAllRoundsHTML();
					$strHTML .= "<div id='combat_result'>";

                    if ($this->objSource->arrCombatResult->generic->winner == "attacker") {
						if ($_SESSION["lang"] == "ru")
                    	$strWinner = "Атакующий выиграл битву! Он получает " . NumberToString($this->objSource->arrCombatResult->generic->loot_metal) . " " . Dictionary("metal_r") . ", " . NumberToString($this->objSource->arrCombatResult->generic->loot_crystal) . " " . Dictionary("crystal_r") . " и " . NumberToString($this->objSource->arrCombatResult->generic->loot_deuterium) . " " . Dictionary("deuterium_r") . ".";
                    	else
                    	$strWinner = "Attacker(s) win! He gains " . NumberToString($this->objSource->arrCombatResult->generic->loot_metal) . " " . Dictionary("metal_r") . ", " . NumberToString($this->objSource->arrCombatResult->generic->loot_crystal) . " " . Dictionary("crystal_r") . " and " . NumberToString($this->objSource->arrCombatResult->generic->loot_deuterium) . " " . Dictionary("deuterium_r") . ".";
                    }
                    elseif ($this->objSource->arrCombatResult->generic->winner == "defender") {
						if ($_SESSION["lang"] == "ru")
                    		$strWinner = "Обороняющий выиграл битву!";
                    	else
                    		$strWinner = "Defender(s) win!";
                    }
                    else {
						if ($_SESSION["lang"] == "ru")
                    		$strWinner = "Бой оканчивается вничью, оба флота возвращаются на свои планеты.";
                    	else
                    		$strWinner = "Draw!";
                    }

					if ($_SESSION["lang"] == "ru")
					$strHTML .= "<p class='action'>" . $strWinner . "</p><p class='action'>Атакующий потерял " . NumberToString($this->objSource->arrCombatResult->generic->units_lost_attackers) . " единиц.<br>Обороняющийся потерял " . NumberToString($this->objSource->arrCombatResult->generic->units_lost_defenders) . " единиц.<br>Теперь на этих пространственных координатах находится " . NumberToString($this->objSource->arrCombatResult->generic->debris_metal) . " металла и " . NumberToString($this->objSource->arrCombatResult->generic->debris_crystal) . " кристалла.<br></p>";
					else
					$strHTML .= "<p class='action'>" . $strWinner . "</p><p class='action'>Attacker(s) lost " . NumberToString($this->objSource->arrCombatResult->generic->units_lost_attackers) . " units.<br>Defender(s) lost " . NumberToString($this->objSource->arrCombatResult->generic->units_lost_defenders) . " units.<br>" . NumberToString($this->objSource->arrCombatResult->generic->debris_metal) . " " . Dictionary("metal_r") . " and " . NumberToString($this->objSource->arrCombatResult->generic->debris_crystal) . " " . Dictionary("crystal_r") . " are now available for recycling.<br></p>";

					if ($this->objSource->arrCombatResult->generic->moon_created)
						$strHTML .= "<center><div name='img_'><img src=" . ICON_MOON . " alt='Moon'></div><div name='_img'><font color='" . RED_COMMON . "'>&bull;</font></div>Шанс появления луны составил " . $this->objSource->arrCombatResult->generic->moon_chance . "%<br>Невероятные массы свободного металла и кристалла сближаются и образуют форму некоего спутника на орбите планеты.<br>Создана луна размером " . $this->objSource->arrCombatResult->generic->moon_size . "</center>";
					else {
						if ($this->objSource->arrCombatResult->generic->moon_chance) $strHTML .= "<center>Шанс появления луны составил " . $this->objSource->arrCombatResult->generic->moon_chance . "%</center>";
					}
					$strHTML .= "</div><!-- combat_result -->";
				$strHTML .= "</div><!-- master -->";
			$strHTML .= "</body>";
			$strHTML .= "</html>";
			//if ($_GET["id"] == "d60003a8092b6fb04b3e13c88dd410463c66") var_dump($this->objSource->arrCombatResult);
			return $strHTML;
		}

		private function GetHeadHTML() {
            $strResult = "<html xmlns='http://www.w3.org/1999/xhtml'>
				<head>
					<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
						<title></title>";
            $strResult .= "
            			<meta name=\"twitter:card\" content=\"summary_large_image\">
            			<meta name=\"twitter:site\" content=\"LogServer.Net\">
            			<meta name=\"twitter:creator\" content=\"Demon\">
            			<meta name=\"twitter:title\" content=\"replace_title_discord\">
            			<meta name=\"twitter:description\" content=\"replace_longtitle_discord\">
            			<meta name=\"twitter:image\" content=\"https://logserver.net/img.php?id=".$this->objSource->strId."\">";
            /*
            $strResult .= "
            			<meta property=\"og:title\" content=\"LogServer.Net\">
            			<meta property=\"og:site_name\" content=\"LogServer.Net\">
            			<meta property=\"og:description\" content=\"replace_longtitle_discord\">
            			<meta property=\"og:url\" content=\"https://logserver.net/index.php?id=".$this->objSource->strId."\">
            			<meta property=\"og:image\" content=\"https://logserver.net/img.php?id=".$this->objSource->strId."\">
            			<meta property=\"og:image\" content=\"index_files/favicon/favicon-150x150.png\">";
            			<meta property=\"og:image\" content=\"index_files/favicon/discord_logserver_game1.png\">";
            			";
			*/
        	$attacker = $this->objSource->arrCombatResult->attackers[0]->fleet_composition;
        	foreach ($attacker as $key => $value) {
        		$ship_type = trim($value->ship_type);
        	}
        	if ($this->objSource->arrCombatResult->generic->moon_created) {
            	$strResult .= "
            			<link rel='icon' href='index_files/favicon/moon.php?n=" . $this->objSource->arrCombatResult->generic->moon_chance . "' type='image/x-icon'>
						<link rel='shortcut icon' href='index_files/favicon/moon.php?n=" . $this->objSource->arrCombatResult->generic->moon_chance . "' type='image/x-icon'>
						<meta property='og:image' content='index_files/favicon/moon.png'>";
        	}
        	elseif ($this->objSource->arrCombatResult->attackers[0]->fleet_owner == "fiks") {
            	$strResult .= "
						<link rel='apple-touch-icon' sizes='144x144' href='index_files/favicon/apple-touch-icon.png'>
						<link rel='icon' type='image/png' sizes='32x32' href='index_files/favicon/favicon-32x32.png'>
						<link rel='icon' type='image/png' sizes='16x16' href='index_files/favicon/favicon-16x16.png'>
						<meta property='og:image' content='index_files/favicon/fiks.png'>";            	
        	}
        	elseif (count($attacker) == 1 && $ship_type == 214) {
            	$strResult .= "
            			<link rel='icon' href='index_files/favicon/star.png' type='image/x-icon'>
            			<link rel='shortcut icon' href='index_files/favicon/star.png' type='image/x-icon'>
            			<meta property='og:image' content='index_files/favicon/star.png'>";
        	} else {
            	$strResult .= "
						<link rel='apple-touch-icon' sizes='144x144' href='index_files/favicon/apple-touch-icon.png'>
						<link rel='icon' type='image/png' sizes='32x32' href='index_files/favicon/favicon-32x32.png'>
						<link rel='icon' type='image/png' sizes='16x16' href='index_files/favicon/favicon-16x16.png'>
						<meta property='og:image' content='index_files/favicon/favicon-64x64_logserver.png'>";            	
        	}
        	$strResult .= "
					<link rel='manifest' href='index_files/favicon/site.webmanifest'>
					<link rel='mask-icon' href='index_files/favicon/safari-pinned-tab.svg' color='#5bbad5'>
					<meta name='msapplication-TileColor' content='#00a300'>
					<meta name='theme-color' content='#ffffff'>";


            $strResult .= "<link rel='stylesheet' type='text/css' href='".CSS_COMBAT."' media='screen' />
						<link type='text/css' rel='stylesheet' href='index_files/ratings.css'/>
                        <script language='javascript' src='" . LOGJSLIBRARY . "'></script>
    			        <script language='javascript' src='" . JQUERY . "'></script>
    			        <script language='javascript' src='" . MAINJSLIBRARY . "?t=" . microtime(true) . "'></script>
    			        <script language='javascript' src='" . JS_ABOX . "'></script>
    			        <script language='javascript' src='" . JS_XSS . "'></script>
                        <script type='text/javascript'>
                            $(document).ready(function() {
                            	$('.lang').click(function(){
									var request = $.ajax({
									  url: 'h_ajax.php',
									  method: 'GET',
									  data: {page: 'lang', lang: $(this).attr('alt')},
									  dataType: 'html'
									});
									 
									request.done(function(msg) {
									  location.reload();
									});
									 
									request.fail(function( jqXHR, textStatus ) {
									  alert('Request failed:' + textStatus );
									});
								});                            	

                                $('#recycler_report').click(function(){'none'==$('.recycler_report').css('display')?$('.recycler_report').show(500):$('.recycler_report').hide()});
                                $('#user_comments').click(function(){'none'==$('.user_comments').css('display')?$('.user_comments').show(500):$('.user_comments').hide()});
                                $('#url_title').click(function(){'none'==$('.url_title').css('display')?$('.url_title').show(500):$('.url_title').hide()});
                                $('#splitup_title_0').click(function(){'none'==$('.splitup_title_0').css('display')?$('.splitup_title_0').show(500):$('.splitup_title_0').hide()});
                                $('#splitup_title_1').click(function(){'none'==$('.splitup_title_1').css('display')?$('.splitup_title_1').show(500):$('.splitup_title_1').hide()});
                                $('#rs_title').click(function(){'none'==$('.rs_title').css('display')?$('.rs_title').show(500):$('.rs_title').hide()});
                                $('#compounds').click(function(){'none'==$('.compounds').css('display')?$('.compounds').show(500):$('.compounds').hide()});
                                $('#doc').click(function(){'none'==$('.doc').css('display')?$('.doc').show(500):$('.doc').hide()});
                                $('#losses').click(function(){'none'==$('.losses').css('display')?$('.losses').show(500):$('.losses').hide()});
                                $('#user_creo').click(function(){'none'==$('.user_creo').css('display')?$('.user_creo').show(500):$('.user_creo').hide()});
                                var start_pos=$('#stick_menu').offset().top;
                                $(window).scroll(function(){
                                    if ($(window).scrollTop()>=start_pos) {
                                        if ($('#stick_menu').hasClass()==false) $('#stick_menu').addClass('to_top');
                                    }
                                    else $('#stick_menu').removeClass('to_top');
                                });
								$('#ls').dblclick(function(){
								    $(this).text('Thanks lisabon');
								    $(this).css('color', 'red');
								}); 
                                $('.rate_widget').each(function(i) {
                                    var widget = this;
                                    var out_data = {
                                        widget_id : $(widget).attr('id'),
                                        fetch: 1
                                    };
                                    $.post(
                                        'h_ratings.php',
                                        out_data,
                                        function(INFO) {
                                            $(widget).data( 'fsr', INFO );
                                            set_votes(widget);
                                        },
                                        'json'
                                    );
                                });


                                $('.ratings_stars').hover(
                                    function() {
                                        $(this).prevAll().andSelf().addClass('ratings_over');
                                        $(this).nextAll().removeClass('ratings_vote');
                                    },
                                    function() {
                                        $(this).prevAll().andSelf().removeClass('ratings_over');
                                        set_votes($(this).parent());
                                    }
                                );

                                $('.ratings_stars').bind('click', function() {
                                    if (!localStorage.getItem('".$this->objSource->strId."')) {
                                        localStorage.setItem('".$this->objSource->strId."', '1');
                                        var star = this;
                                        var widget = $(this).parent();

                                        var clicked_data = {
                                            clicked_on : $(star).attr('class'),
                                            widget_id : $(star).parent().attr('id'),
                                            clicked : 1
                                        };
                                        $.post(
                                            'h_ratings.php',
                                            clicked_data,
                                            function(INFO) {
                                                widget.data( 'fsr', INFO );
                                                set_votes(widget);
                                            },
                                            'json'
                                        );
                                    }
                                });

                            });

                            function set_votes(widget) {
                                var avg = $(widget).data('fsr').whole_avg;
                                var votes = $(widget).data('fsr').number_votes;
                                var exact = $(widget).data('fsr').dec_avg;
                                window.console && console.log('and now in set_votes, it thinks the fsr is ' + $(widget).data('fsr').number_votes);
                                $(widget).find('.star_' + avg).prevAll().andSelf().addClass('ratings_vote');
                                $(widget).find('.star_' + avg).nextAll().removeClass('ratings_vote');
                                $(widget).find('.total_votes').text( votes + ' votes recorded (' + exact + ' rating)' );
                            }

                            VK.init({
                                apiId: " . VkApiId . ",
                                onlyWidgets: true
                            });
                        </script>
                        <style>
                            .to_top{position:fixed;top:0px;}
                        </style>";
			if ($this->objSource->strMusic) {
				$strResult .= "<div style='position: fixed; z-index: -99; width: 100%; height: 100%''><iframe frameborder='0' height='100%' width='100%' src='https://www.youtube.com/embed/" . $this->objSource->strMusic . "?controls=0&showinfo=0&rel=0&autoplay=1&loop=1&mute=1&playlist=" . $this->objSource->strMusic . "'></iframe></div>";
			}                      
				$strResult .= "</head>";

			return $strResult;
		}

		private function GetLastRoundFleet($arrRoundFleet, $s, $e) {
		    foreach ($arrRoundFleet as $key => $value) {
			    if ($key <= $e && $key >= $s) {
			        $arrResult[] = array("ship_type" => $value->ship_type, "count" => $value->count);
			    }
            }

			return $arrResult;
		}

		private function GetFleetHTML($arrRoundFleet, $s, $e, $blnHideTech) {
			$arrHTML = "";

				$arrHTML .= "<table>";

					$arrHTML .= "<tr>";
					foreach ($arrRoundFleet as $key => $value) {
					    if ($key <= $e && $key >= $s) {
    						$arrHTML .= "<th class='textGrow' nowrap='nowrap'>";
    						$arrHTML .= "<center>";
    						$arrHTML .= "<div name='img_'><img src='" . GetIMG($value->ship_type) . "'><br></div>";
    						$arrHTML .= Dictionary($value->ship_type);
    						$arrHTML .= "</center>";
    						$arrHTML .= "</th>";
                        }
					}
					$arrHTML .= "</tr>";

					$arrHTML .= "<tr>";
					foreach ($arrRoundFleet as $key => $value) {
						if ($key <= $e && $key >= $s) $arrHTML .= "<td><font color='#888888'>" . NumberToString($value->count) . "</font></td>";
					}
					$arrHTML .= "</tr>";

				$arrHTML .= "</table>";

			return $arrHTML;
		}

		private function GetFleetStatisticsHTML ($arrRoundFleet, $lastRoundFleet, $j , $a) {
			$arrHTML = "";

				$arrHTML .= "<table>";

					$arrHTML .= "<tr>";
					foreach ($arrRoundFleet as $key => $value) {
						$arrHTML .= "<th class='textGrow' valign='top' nowrap='nowrap'>";
						$arrHTML .= "<center>";
						$arrHTML .= "<div name='img_'><img src='" . GetIMG($value->ship_type) . "'><br></div>";
						$arrHTML .= Dictionary($value->ship_type);
						$arrHTML .= "</center>";
						$arrHTML .= "</th>";
					}
					$arrHTML .= "</tr>";

					$arrHTML .= "<tr>";
					foreach ($arrRoundFleet as $key => $value) {
						$arrHTML .= "<td><font color='#888888'>" . NumberToString($value->count) . "</font></td>";
                        $arrLossesFleet[] = array("count" => $lastRoundFleet[$key]["count"] - $value->count);
                        if ($a) $this->arrCache["arrLossesFleetAtakers"][$j][] = array("ship_type" => $value->ship_type, "count" => $value->count - $lastRoundFleet[$key]["count"]);
                        else $this->arrCache["arrLossesFleetDefenders"][$j][] = array("ship_type" => $value->ship_type, "count" => $value->count - $lastRoundFleet[$key]["count"]);
					}

					$arrHTML .= "</tr>";

					$arrHTML .= "<tr>";
					foreach ($arrLossesFleet as $key => $value) {
						if ($this->objSource->arrCombatResult->rounds) $arrHTML .= "<td><font color='#888888'>" . PrepareNumber($value["count"]) . "</font></td>";
						else $arrHTML .= "<td><font color='#888888'>" . PrepareNumber(0) . "</font></td>";
					}
					$arrHTML .= "</tr>";

                    $arrHTML .= "<tr name='full_stat'>";
					foreach ($lastRoundFleet as $key => $value) {
						$arrHTML .= "<td><font color='#888888'>" . PrepareNumber($lastRoundFleet[$key]["count"]) . "</font></td>";
					}
					$arrHTML .= "</tr>";

				$arrHTML .= "</table>";

			return $arrHTML;
		}

		private function GetFleetStatisticsBBcode($arrRoundFleet, $RoundsCount) {
			$arrTemp = $arrRoundFleet[$RoundsCount-1];
			$arrOldTemp = $arrRoundFleet[0];

			$arrOldFleet = NULL;
			$arrFleet = NULL;
			$strBBcode = "[size=10]";

			if ($arrTemp['type'] == 'round'){
				$arrFleet = $arrTemp['fleet'];
			}
			if ($arrOldTemp['type'] == 'round'){
				$arrOldFleet = $arrOldTemp['fleet'];
			}

			$arrHTMLTemp = NULL;

			if ($arrOldFleet){

				$i = 0;
				foreach ($arrOldFleet as $key => $value) {
					$intBegin = 0;
					$intEnd = 0;
					$intDelta = 0;

					if ($value['name'] != "th"){

						$intBegin = $arrOldFleet[$key]['count'];

						$blnArrFleetKeyExists = False;

						if ($arrFleet){
							if (key_exists($key,$arrFleet)){
								$blnArrFleetKeyExists = True;
							}
						}

						if ($blnArrFleetKeyExists){
							$intEnd = $arrFleet[$key]['count'];
						}

						$intDelta = $intBegin - $intEnd;

						$strBBcode = $strBBcode."[color=" . WHITE_DARK . "]-".$arrOldFleet[$key]['l_name'].":[/color]";
						$strBBcode = $strBBcode." "."[color=" . WHITE_DARK . "]".NumberToString($intBegin)."[/color]";
						$strBBcode = $strBBcode." "."[color=" . RED_COMMON . "] - ".NumberToString($intDelta)."[/color]";
						$strBBcode = $strBBcode." "."[color=" . WHITE_DARK . "] = ".NumberToString($intEnd)."[/color]";
						$strBBcode = $strBBcode."\n";
					}
				}
			}
			$strBBcode = $strBBcode."[/size]";
			return $strBBcode;
		}

		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		private function GetAllRoundsHTML() {
			$strTitle = $this->Get("title");
            $intTitle = explode("vs.", $strTitle);
			$sRemember = "";

            $arrAttackers = explode(",", $intTitle[0]);
            foreach($arrAttackers as $arrPlayer) {
        			$strDomain = strtolower($this->objSource->strDomain);
        			$strUni = strtolower('uni' . $this->objSource->intUni);
                    $strPName = preg_replace("/\[[\d|\D]+\]\s/", "", trim($arrPlayer));
        			$strPName = str_replace(' ', '%20', $strPName);
        			$intTitle[0] = str_replace($arrPlayer, " " . $arrPlayer . " <stat player='" . $strPName . "' domain='" . str_replace('/org', '', strtolower($this->objSource->strDomain)) . "' uni='" . $this->objSource->intUni . "'></stat>", $intTitle[0]);
			}
            $arrDefenders = explode(",", $intTitle[1]);
			foreach($arrDefenders as $arrPlayer) {
        			$strDomain = strtolower($this->objSource->strDomain);
        			$strUni = strtolower('uni' . $this->objSource->intUni);
                    $strPName = preg_replace("/\[[\d|\D]+\]\s/", "", trim($arrPlayer));
        			$strPName = str_replace(' ', '%20', $strPName);
        			$intTitle[1] = str_replace($arrPlayer, " " . $arrPlayer . " <stat player='" . $strPName . "' domain='" . str_replace('/org', '', strtolower($this->objSource->strDomain)) . "' uni='" . $this->objSource->intUni . "'></stat>", $intTitle[1]);
			}
			$strTitle = $intTitle[0].' vs. '.$intTitle[1];

			$strStartOpponents = "<font color='" . RED_COMMON . "'>" . str_replace("vs.", "</font>vs.<font color='" . GREEN_COMMON . "'>", $strTitle) . "</font>";
			$strUniDomain = "[<font color='" . VIOLET_COMMON . "'>" . NameUni($this->objSource->intUni) . "." . $this->objSource->strDomain . "</font>]";

			if ($this->objSource->blnHideTime) $strHideTime = DateConstructor_6x($this->objSource->arrCombatResult->generic->event_timestamp, 1);
            else $strHideTime = DateConstructor_6x($this->objSource->arrCombatResult->generic->event_timestamp, 0);
            if (strtoupper($_COOKIE["lang"]) == "RU") $strTime = "ДАТА/ВРЕМЯ: (" . $strHideTime . "). ПРОИЗОШЁЛ&nbsp;БОЙ&nbsp;МЕЖДУ&nbsp;СЛЕДУЮЩИМИ&nbsp;ФЛОТАМИ:";
            else $strTime = "DATE/TIME: (" . $strHideTime . ")";

			$strAllRoundsHTML = 			"<div class='combat_round'>";

			$strAllRoundsHTML .= "  <table>
                                        <tr>
                                            <td width='100' align='center'>";
			$strAllRoundsHTML .= "              <div id='back'></div>";
			$strAllRoundsHTML .= "          </td>
                                            <td width='800'>
                                                <p class='start' width='800'>" . $strUniDomain . " " . $strTime . "<br><br><font id='title'>" . $strStartOpponents . "</font></p>
                                            </td>
                                            <td width='100' align='center'>";
			$strAllRoundsHTML .= "              <div id='forward'></div>";
			$strAllRoundsHTML .= "          </td>
                                        </tr>
                                    </table>";

			$strAllRoundsHTML .=  				"<div class='round_info'>
												<p class='start_ex'>
												<table border='0' width='100%'>
													<tr>
														<td width='420' align='left' style='padding-left: 5px'>
															<select size='1' name='select_skin' id='select_skin' style='font-size: 10px; width: 100px;' onchange='ChangeSkin(this.value)'>
																<option value='0' selected disabled >Change skin</option>
																<option value='" . SKIN_LOGSERVERV20 . "'>LogServer v2</option>
																<option value='" . SKIN_DEFAULT . "'>Default</option>
																<option value='" . SKIN_ORIGINAL . "'>Original</option>
																<option value='" . SKIN_ABSTRACT . "'>Abstract</option>
																<option value='" . SKIN_ANIMEX . "'>AnimeX</option>
																<option value='" . SKIN_ANIMEX2 . "'>AnimeX 2</option>
																<option value='" . SKIN_DEATH_NOTE . "'>Death Note</option>
																<option value='" . SKIN_CHAOS . "'>Chaos</option>
																<option value='" . SKIN_DESTROYER . "'>Destroyer</option>
																<option value='" . SKIN_FALLOUT . "'>Fallout</option>
																<option value='" . SKIN_DEADSPACE . "'>Dead Space</option>
																<option value='" . SKIN_NTRVR . "'>?ntrvr[!]</option>
																<option value='" . SKIN_KPACOTA . "'>KPACOTA</option>
																<option value='" . SKIN_DISTURBED . "'>Disturbed</option>
																<option value='" . SKIN_STATICX . "'>Static-X</option>
																<option value='" . SKIN_SYSTEMSHOCK . "'>System shock</option>
																<option value='" . SKIN_BENDER . "'>Bender</option>
																<option value='" . SKIN_OLD . "'>OldAlpha</option>
															</select>
															<input type='button' id='select_img' value='Show images' style='font-size: 10px; width: 100px;' onclick='ChangeIMG(this)'>
															<input type='button' id='select_stat_type' value='Show full statistics' style='font-size: 10px; width: 100px;' onclick='ChangeStatType(this)'>
															<input type='button' id='go_speedsim' value='WebSim' style='font-size: 10px; width: 100px;'>
														</td>
														<td width='5'></td>
														<td align='center'><a href='javascript:JS_ShowRounds()' id='link_UI' style='text-decoration: none'><font color='" . LINK . "' onmouseover='this.color=\"" . LINK_ACTIVE . "\"' onmouseout='this.color=\"" . LINK . "\"'><b>&#9660&nbsp" . Dictionary("expand_all_rounds_title") . "&nbsp&#9660;</b></font></a></td>
														<td width='5'></td>
														<td width='420' align='left'>";
			if ($this->objSource->blnPublic) $strAllRoundsHTML .="<div id='".$this->objSource->strId."' class='rate_widget'>
                                                                <div class='star_1 ratings_stars'></div>
                                                                <div class='star_2 ratings_stars'></div>
                                                                <div class='star_3 ratings_stars'></div>
                                                                <div class='star_4 ratings_stars'></div>
                                                                <div class='star_5 ratings_stars'></div>
                                                            <div class='total_votes'>vote data</div>
                                                            </div>";
			$strAllRoundsHTML .=                         "</td>
													</tr>
												</table>
												</p>
												</div>";


			$strAllRoundsHTML .=  			"</div>";

			$strAllRoundsHTML .= 		"<div id='rounds' style='display: none'>";

			for ($i = 0; $i < $this->objSource->intRoundsCount; $i++) {
				$strAllRoundsHTML .= "<div class='combat_round'>";

				$strAllRoundsHTML .= $this->GetRoundInfoHTML($i);

				$strAllRoundsHTML .= $this->GetRoundTablesHTML($i)."\n";
				$strAllRoundsHTML .= "</div>";
			}
			$strAllRoundsHTML .= "</div>";

			return $strAllRoundsHTML;
		}

		private function GetRoundInfoHTML($intRoundNumber) {
			$strHTML = "<div class='round_info'>";
				$strHTML .= "<div class='battle'>";
					$strHTML .= "<p class='action'>Атакующий флот делает: " . NumberToString($this->objSource->arrCombatResult->rounds[$intRoundNumber]->statistics->attacker_hits) . " выстрела(ов) общей мощностью " . NumberToString($this->objSource->arrCombatResult->rounds[$intRoundNumber]->statistics->attacker_fullstrength) . " по обороняющемуся. Щиты обороняющегося поглощают " . NumberToString($this->objSource->arrCombatResult->rounds[$intRoundNumber]->statistics->defender_absorbed) . " мощности выстрелов.</p>";
					$strHTML .= "<p class='action'>Обороняющийся флот делает: " . NumberToString($this->objSource->arrCombatResult->rounds[$intRoundNumber]->statistics->defender_hits) . " выстрела(ов) общей мощностью " . NumberToString($this->objSource->arrCombatResult->rounds[$intRoundNumber]->statistics->defender_fullstrength) . " по атакующему. Щиты атакующего поглощают " . NumberToString($this->objSource->arrCombatResult->rounds[$intRoundNumber]->statistics->attacker_absorbed) . " мощности выстрелов.</p>";
				$strHTML .= "</div>";
			$strHTML .= "</div>";
			return $strHTML;
		}
		private function GetRoundTablesHTML($intRoundNumber) {
			$strHTML = "<table cellpadding='0' cellspacing='0' style='width:100%;'>";
				$strHTML .= "<tr>";
					$strHTML .= "<td class='round_attacker textCenter'>";
						$strHTML .= "<table cellpadding='0' cellspacing='0'>";

							//Attackers
								$strHTML .= "<tr>";
									for ($j = 0; $j < count($this->objSource->arrCombatResult->attackers); $j++) {
										$strHTML .= "<td class='newBack' valign='top'>";
											$strHTML .= "<center>";
												$strName = $this->objSource->arrCombatResult->attackers[$j]->fleet_owner;
												if (isset($_SESSION['account']['login']) && in_array($_SESSION['account']['login'], listAdmin()))
													$strCoordinate = $this->objSource->arrCombatResult->attackers[$j]->fleet_owner_coordinates;
												else
													$strCoordinate = GetCoordinatesForLog($this->objSource->arrCombatResult->attackers[$j]->fleet_owner_coordinates, $this->objSource->blnHideCoord);
                                                $technologiesForLog = array($this->objSource->arrCombatResult->attackers[$j]->fleet_weapon_percentage, $this->objSource->arrCombatResult->attackers[$j]->fleet_shield_percentage, $this->objSource->arrCombatResult->attackers[$j]->fleet_armor_percentage);
                                                $strTechnologies = GetTechnologiesForLog($technologiesForLog, $this->objSource->blnHideTech, true);
                                                $arrayAttackersComp[$j] = count($this->objSource->arrCombatResult->attackers[$j]->fleet_composition);

                                                if ($intRoundNumber == 0) {
														$strHTML .= "<span class='weapons textBeefy'>";
														$strHTML .= "<font color='" . RED_COMMON . "'>".$strName."</font>";
															$strHTML .=  "<br><font color='" . WHITE_DARK . "'>".$strCoordinate." ".$strTechnologies."</font>";
														$strHTML .= "</span>";
													}
													else {
														$strHTML .= "<span class='name textBeefy'>";
														$strHTML .= "<font color='" . RED_COMMON . "'>".$strName."</font>";
														if ($strCoordinate != "") {
															$strHTML .=  "<br><font color='" . WHITE_DARK . "'>".$strCoordinate."</font>";
														}
														$strHTML .= "</span>";
													}

											$strHTML .= "</center>";
										$strHTML .= "</td>";
									}
								$strHTML .= "</tr>";

							$strHTML .= "<tr>";
                            $lastRoundNumber = $this->objSource->intRoundsCount - 1;
                            $startCoun = 0;
							    foreach ($arrayAttackersComp as $key => $count) {
									$strHTML .= "<td class='newBack'>";
									$strHTML .= 	"<center>";
                                    $endCount = $startCoun + $count - 1;
									$arrRoundFleet = $this->objSource->arrCombatResult->rounds[$intRoundNumber]->attacker_ships;
									$strFleetHTML = $this->GetFleetHTML($arrRoundFleet, $startCoun, $endCount, $this->objSource->blnHideTech);

                                    if ($intRoundNumber == $lastRoundNumber) {
                                        $this->arrCache["lastRoundFleet"]["attacker_ships"][$key] = $this->GetLastRoundFleet($arrRoundFleet, $startCoun, $endCount);
                                    }

                                    $startCoun = $endCount + 1;
									$strHTML .= $strFleetHTML;
									$strHTML .= 	"</center>";
									$strHTML .= "</td>";
							    }
							$strHTML .= "</tr>";

						$strHTML .= "</table>";
					$strHTML .= "</td>";
				$strHTML .= "</tr>";
			$strHTML .= "</table>";

			$strHTML .= "<table cellpadding='0' cellspacing='0' style='width:100%;'>";
				$strHTML .= "<tr>";
					$strHTML .= "<td class='round_defender textCenter'>";
						$strHTML .= "<table cellpadding='0' cellspacing='0'>";

							//Defenders
								$strHTML .= "<tr>";
									for ($j = 0; $j < count($this->objSource->arrCombatResult->defenders); $j++) {
										$strHTML .= "<td class='newBack' valign='top'>";
											$strHTML .= "<center>";
												$strName = $this->objSource->arrCombatResult->defenders[$j]->fleet_owner;
												if (isset($_SESSION['account']['login']) && in_array($_SESSION['account']['login'], listAdmin()))
													$strCoordinate = $this->objSource->arrCombatResult->defenders[$j]->fleet_owner_coordinates;
												else
													$strCoordinate = GetCoordinatesForLog($this->objSource->arrCombatResult->defenders[$j]->fleet_owner_coordinates, $this->objSource->blnHideCoord);
                                                $technologiesForLog = array($this->objSource->arrCombatResult->defenders[$j]->fleet_weapon_percentage, $this->objSource->arrCombatResult->defenders[$j]->fleet_shield_percentage, $this->objSource->arrCombatResult->defenders[$j]->fleet_armor_percentage);
                                                $strTechnologies = GetTechnologiesForLog($technologiesForLog, $this->objSource->blnHideTech, true);
                                                $arrayDefendersComp[$j] = count($this->objSource->arrCombatResult->defenders[$j]->fleet_composition);

													if ($intRoundNumber == 0) {
														$strHTML .= "<span class='weapons textBeefy'>";

														$strHTML .= "<font color='" . GREEN_COMMON . "'>".$strName."</font>";
															$strHTML .=  "<br><font color='" . WHITE_DARK . "'>".$strCoordinate." ".$strTechnologies."</font>";
														$strHTML .= "</span>";
													}
													else {
														$strHTML .= "<span class='name textBeefy'>";
														$strHTML .= "<font color='" . GREEN_COMMON . "'>".$strName."</font>";
														if ($strCoordinate != "") {
															$strHTML .=  "<br><font color='" . WHITE_DARK . "'>".$strCoordinate."</font>";
														}
														$strHTML .= "</span>";
													}
											$strHTML .= "</center>";
										$strHTML .= "</td>";
									}
								$strHTML .= "</tr>";
							$strHTML .= "<tr>";
                            $startCoun = 0;
							    foreach ($arrayDefendersComp as $key => $count) {
									$strHTML .= "<td class='newBack'>";
									$strHTML .= 	"<center>";
                                    $endCount = $startCoun + $count - 1;
									$arrRoundFleet = $this->objSource->arrCombatResult->rounds[$intRoundNumber]->defender_ships;
									$strFleetHTML = $this->GetFleetHTML($arrRoundFleet, $startCoun, $endCount, $this->objSource->blnHideTech);

                                    if ($intRoundNumber == $lastRoundNumber) {
                                        $this->arrCache["lastRoundFleet"]["defender_ships"][$key] = $this->GetLastRoundFleet($arrRoundFleet, $startCoun, $endCount);
                                    }

                                    $startCoun = $endCount + 1;
									$strHTML .= $strFleetHTML;
									$strHTML .= 	"</center>";
									$strHTML .= "</td>";
							    }
							$strHTML .= "</tr>";

						$strHTML .= "</table>";
					$strHTML .= "</td>";
				$strHTML .= "</tr>";
			$strHTML .= "</table>";

			return $strHTML;
		}

		private function ReplaceCSS() {
			$strHTML = $this->strHTMLLogNew;
			$strPattern = "/<link rel='stylesheet'.+?>/";
			$strHTML = preg_replace($strPattern , "", $strHTML);
			$strPattern = "/<link rel=\"stylesheet\".+?>/";
			$strHTML = preg_replace($strPattern , "", $strHTML);

			if ($_COOKIE["exel"] == "true") $strCSS = SKIN_EXEL;
			else if ($this->objSource->intSkin) {
				switch (strtolower($this->objSource->intSkin)) {
					case "original":	    $strCSS = SKIN_ORIGINAL; break;
					case "abstract":	    $strCSS = SKIN_ABSTRACT; break;
					case "animex":	        $strCSS = SKIN_ANIMEX; break;
					case "animex_2":	    $strCSS = SKIN_ANIMEX2; break;
					case "death_note":	    $strCSS = SKIN_DEATH_NOTE; break;
					case "chaos":	        $strCSS = SKIN_CHAOS; break;
					case "destroyer":	    $strCSS = SKIN_DESTROYER; break;
					case "fallout":	        $strCSS = SKIN_FALLOUT; break;
					case "logserver_v20":	$strCSS = SKIN_LOGSERVERV20; break;
					case "dead_space":	    $strCSS = SKIN_DEADSPACE; break;
					case "ntrvr":	        $strCSS = SKIN_NTRVR; break;
					case "krasota":	        $strCSS = SKIN_KPACOTA; break;
					case "disturbed":	    $strCSS = SKIN_DISTURBED; break;
					case "staticx":	        $strCSS = SKIN_STATICX; break;
					case "system_shock":	$strCSS = SKIN_SYSTEMSHOCK; break;
					case "bender":	        $strCSS = SKIN_BENDER; break;
					case "oldalpha":	    $strCSS = SKIN_OLD; break;
					case "zapio":	    	$strCSS = SKIN_ZAPIO; break;
					case "schneeprinz":	    $strCSS = SKIN_SCHN; break;
					default:                $strCSS = SKIN_DEFAULT;
				}
			}
			else {
				$strCSS = SKIN_DEFAULT;
			}

			$strCSS = "<link id='skin_css' rel='stylesheet' type='text/css' href='" . $strCSS . "' media='screen' />";
			$strHTML = str_replace("</head>", $strCSS . "</head>", $strHTML);
			$strCSS = "<link rel='stylesheet' type='text/css' href='index_files/reset.css" . "' media='screen' />";
			$strHTML = str_replace("</head>", $strCSS . "</head>", $strHTML);
			return $strHTML;
		}

		private function GetFleetsStatistics() {
			$strFleetStatisticsHTML = "<div class='combat_round'>";

			$strFleetStatisticsHTML .= 		"<div class='round_info'>";
			$strFleetStatisticsHTML .= 			"<p class='start'><font color='" . YELLOW_COMMON . "'><b>" . Dictionary("fleet_statistics_title") . "</b></font></p>";
			$strFleetStatisticsHTML .= 		"</div>";

			$strFleetStatisticsHTML .=		"<table cellpadding='0' cellspacing='0' style='width:100%;'>";
			$strFleetStatisticsHTML .= 			"<tr>";
			$strFleetStatisticsHTML .= 				"<td class='round_attacker textCenter'>";
			$strFleetStatisticsHTML .= 					"<table cellpadding='0' cellspacing='0'>";


				$strFleetStatisticsHTML .= 	"<tr>";
				$strAllAttackersFleetStatisticsHTML = "";
				for ($j = 0; $j < count($this->objSource->arrCombatResult->attackers); $j++) {
					/*$intEndFleetStructure = GetSumFleetStructure($this->objSource->arrAttackers[$j]->arrRoundFleet[$this->objSource->intRoundsCount - 1]);
					($intEndFleetStructure > 0) ? ($strIcon = VICTORY_ICON) : ($strIcon = DEFETED_ICON);*/
					$strAttackerHTML = "<td class='newBack' align='center' valign='top' >";
					$strAttackerHTML .= "<div name='img_'><img src='" . VICTORY_ICON . "'></div>";
					if ($this->objSource->arrCombatResult->attackers[$j]->fleet_owner) {
						$strAttackerHTML .= "<span class='name textBeefy'>";
						
						$strName = $this->objSource->arrCombatResult->attackers[$j]->fleet_owner;
						if (isset($_SESSION['account']['login']) && in_array($_SESSION['account']['login'], listAdmin()))
							$strCoordinate = $this->objSource->arrCombatResult->attackers[$j]->fleet_owner_coordinates;
						else
							$strCoordinate = GetCoordinatesForLog($this->objSource->arrCombatResult->attackers[$j]->fleet_owner_coordinates, $this->objSource->blnHideCoord);
                        $technologiesForLog = array($this->objSource->arrCombatResult->attackers[$j]->fleet_weapon_percentage, $this->objSource->arrCombatResult->attackers[$j]->fleet_shield_percentage, $this->objSource->arrCombatResult->attackers[$j]->fleet_armor_percentage);
                        $strTechnologies = GetTechnologiesForLog($technologiesForLog, $this->objSource->blnHideTech, true);

						$strAttackerHTML .= "<font color='" . RED_COMMON . "'>" . $strName . "</font>";
						if ((!$this->objSource->blnHideCoord) || (!$this->objSource->blnHideTech)) {
							$strAttackerHTML .=  "<br>";
							$strAttackerHTML .=  " "."<font color='" . WHITE_DARK . "'>".$strCoordinate."</font>"." ";
							$strAttackerHTML .=  " "."<font color='" . WHITE_DARK . "'>".$strTechnologies."</font>"." ";
						}


						$strAttackerHTML .= "</span>";
					}
					$strAttackerHTML .= "</td>";

					$strAllAttackersFleetStatisticsHTML = $strAllAttackersFleetStatisticsHTML.$strAttackerHTML."\n";
				}
				$strFleetStatisticsHTML .= $strAllAttackersFleetStatisticsHTML;
				$strFleetStatisticsHTML .= 	"</tr>";

			$strFleetStatisticsHTML .= 						"<tr>";
			$strAllAttackersFleetStatisticsHTML = "";
			for ($j = 0; $j < count($this->objSource->arrCombatResult->attackers); $j++) {
				$strAttackerHTML = "<td class='newBack' align='center' valign='top'>";
                $lastRoundFleetAttacker = $this->arrCache["lastRoundFleet"]["attacker_ships"][$j];
				$strAttackerHTML .= $this->GetFleetStatisticsHTML($this->objSource->arrCombatResult->attackers[$j]->fleet_composition, $lastRoundFleetAttacker, $this->objSource->arrCombatResult->attackers[$j]->fleet_owner, true);
				$strAttackerHTML .= "</td>";
				$strAllAttackersFleetStatisticsHTML = $strAllAttackersFleetStatisticsHTML.$strAttackerHTML."\n";

                foreach ($this->objSource->arrCombatResult->attackers[$j]->fleet_composition as $key => $value) {
                	if (!isset($CountFleetAttackers[$value->ship_type])) $CountFleetAttackers[$value->ship_type] = 0;
                    $CountFleetAttackers[$value->ship_type] += $value->count;
                }
			}

            $urlWebSim = "http://websim.speedsim.net/index.php?lang=" . $_COOKIE["lang"] . "&ref=logserver";
            foreach ($CountFleetAttackers as $key => $value) {
                $urlWebSim .= "&ship_a0_" . GetWebSimName($key). "_b=" . $value;
            }

			$strFleetStatisticsHTML .= $strAllAttackersFleetStatisticsHTML;
			$strFleetStatisticsHTML .= 						"</tr>";
			$strFleetStatisticsHTML .= 					"</table>";
			$strFleetStatisticsHTML .= 				"</td>";
			$strFleetStatisticsHTML .= 			"</tr>";
			$strFleetStatisticsHTML .= 		"</table>";

			$strFleetStatisticsHTML .= 		"<table cellpadding='0' cellspacing='0' style='width:100%;'>";
			$strFleetStatisticsHTML .= 			"<tr>";
			$strFleetStatisticsHTML .= 				"<td class='round_defender textCenter'>";
			$strFleetStatisticsHTML .= 					"<table cellpadding='0' cellspacing='0'>";

				$strFleetStatisticsHTML .= "<tr>";
				$strAllDefendersFleetStatisticsHTML = "";
				for ($j = 0; $j < count($this->objSource->arrCombatResult->defenders); $j++) {
					/*$intEndFleetStructure = GetSumFleetStructure($this->objSource->arrDefenders[$j]->arrRoundFleet[$this->objSource->intRoundsCount - 1]);
					($intEndFleetStructure > 0) ? ($strIcon = VICTORY_ICON) : ($strIcon = DEFETED_ICON);*/
					$strDefenderHTML = "<td class='newBack' align='center' valign='top' >";
					$strDefenderHTML .= "<div name='img_'><img src='" . DEFETED_ICON . "'></div>";
					if ($this->objSource->arrCombatResult->defenders[$j]->fleet_owner) {
						$strDefenderHTML .= "<span class='name textBeefy'>";

						$strName = $this->objSource->arrCombatResult->defenders[$j]->fleet_owner;
						if (isset($_SESSION['account']['login']) && in_array($_SESSION['account']['login'], listAdmin()))
							$strCoordinate = $this->objSource->arrCombatResult->defenders[$j]->fleet_owner_coordinates;
						else
							$strCoordinate = GetCoordinatesForLog($this->objSource->arrCombatResult->defenders[$j]->fleet_owner_coordinates, $this->objSource->blnHideCoord);
                        $technologiesForLog = array($this->objSource->arrCombatResult->defenders[$j]->fleet_weapon_percentage, $this->objSource->arrCombatResult->defenders[$j]->fleet_shield_percentage, $this->objSource->arrCombatResult->defenders[$j]->fleet_armor_percentage);
                        $strTechnologies = GetTechnologiesForLog($technologiesForLog, $this->objSource->blnHideTech, true);

						$strDefenderHTML .= "<font color='" . GREEN_COMMON . "'>" . $strName . "</font>";
						if ((!$this->objSource->blnHideCoord) || (!$this->objSource->blnHideTech)) {
							$strDefenderHTML .=  "<br>";
							$strDefenderHTML .=  " "."<font color='" . WHITE_DARK . "'>".$strCoordinate."</font>"." ";
							$strDefenderHTML .=  " "."<font color='" . WHITE_DARK . "'>".$strTechnologies."</font>"." ";
						}

						$strDefenderHTML .= "</span>";
					}
					$strDefenderHTML .= "</td>";
					$strAllDefendersFleetStatisticsHTML = $strAllDefendersFleetStatisticsHTML.$strDefenderHTML."\n";
				}
				$strFleetStatisticsHTML .= $strAllDefendersFleetStatisticsHTML;
				$strFleetStatisticsHTML .= "</tr>";

			$strFleetStatisticsHTML .= 						"<tr>";
			$strAllDefendersFleetStatisticsHTML = "";
				for ($j = 0; $j < count($this->objSource->arrCombatResult->defenders); $j++) {
			        if ($this->objSource->arrCombatResult->defenders[$j]->fleet_composition) {
    					$strDefenderHTML = "<td class='newBack' align='center' valign='top'>";
    	                $lastRoundFleetDefender = $this->arrCache["lastRoundFleet"]["defender_ships"][$j];

    					$strDefenderHTML .= $this->GetFleetStatisticsHTML($this->objSource->arrCombatResult->defenders[$j]->fleet_composition, $lastRoundFleetDefender, $this->objSource->arrCombatResult->defenders[$j]->fleet_owner, false);
    					$strDefenderHTML .= "</td>";

    	                foreach ($this->objSource->arrCombatResult->defenders[$j]->fleet_composition as $key => $value) {
                			if (!isset($CountFleetDefenders[$value->ship_type])) $CountFleetDefenders[$value->ship_type] = 0;
    	                    $CountFleetDefenders[$value->ship_type] += $value->count;
    	                }

        			} else {
        				$strDefenderHTML = "<td class='newBack' align='center' valign='top'>";
        				$strDefenderHTML .= "<table>";
        				$strDefenderHTML .= "<tr>";
        				$strDefenderHTML .= "<td><font color='#888888'>Уничтожен</font></td>";
        				$strDefenderHTML .= "</tr>";
        				$strDefenderHTML .= "</table>";
        				$strDefenderHTML .= "</td>";
        			}

                    $strAllDefendersFleetStatisticsHTML = $strAllDefendersFleetStatisticsHTML.$strDefenderHTML."\n";

				}

            foreach ($CountFleetDefenders as $key => $value) {
                $urlWebSim .= "&ship_d0_" . GetWebSimName($key). "_b=" . $value;
            }
            if (!$this->objSource->blnHideCoord) {
	            $urlWebSim .= "&start_pos=" . $this->objSource->arrCombatResult->attackers[0]->fleet_owner_coordinates;
	            $urlWebSim .= "&enemy_pos=" . $this->objSource->arrCombatResult->defenders[0]->fleet_owner_coordinates;
	        }

	        if (!$this->objSource->blnHideTech) {
	            $urlWebSim .= "&tech_a0_0=" . $this->objSource->arrCombatResult->attackers[0]->fleet_weapon_percentage/10;
	            $urlWebSim .= "&tech_a0_1=" . $this->objSource->arrCombatResult->attackers[0]->fleet_shield_percentage/10;
	            $urlWebSim .= "&tech_a0_2=" . $this->objSource->arrCombatResult->attackers[0]->fleet_armor_percentage/10;
	            
	            $urlWebSim .= "&tech_d0_0=" . $this->objSource->arrCombatResult->defenders[0]->fleet_weapon_percentage/10;
	            $urlWebSim .= "&tech_d0_1=" . $this->objSource->arrCombatResult->defenders[0]->fleet_shield_percentage/10;
	            $urlWebSim .= "&tech_d0_2=" . $this->objSource->arrCombatResult->defenders[0]->fleet_armor_percentage/10;
	        }

			$debrisFactor = GetServerData($this->objSource->intUni, $this->objSource->strDomain, 1)["debrisFactor"];
			if (isset($debrisFactor)) $urlWebSim .= "&perc-df=" . $debrisFactor * 100;

			$strFleetStatisticsHTML .= $strAllDefendersFleetStatisticsHTML;
			$strFleetStatisticsHTML .= 						"</tr>";
			$strFleetStatisticsHTML .= "</table>";
			$strFleetStatisticsHTML .= "</td>";
			$strFleetStatisticsHTML .= "</tr>";
			$strFleetStatisticsHTML .= "</table>";
			$strFleetStatisticsHTML .= "</div>";
			$strFleetStatisticsHTML .= "<script>$(document).ready(function() {
                $('#go_speedsim').click(function() {
                    window.open('". $urlWebSim . "', '_blank');
                });
            })</script>";

			$strHTML = $strFleetStatisticsHTML;
			return $strHTML;
		}

		private function MergePlayers($arrPlayers) {
			return false;
		}

		private function ProcessConsumption() {
			if (!$this->objSource->blnFuel) {
				return array('attacker' => 0, 'defender' => 0);
			}
			if (!$this->objSource->blnPFuel) {
				$this->objSource->blnPFuel = 1;
			}
			$arrAttacker = NULL;
			$arrDefender = NULL;
			$arrAttacker['SUM'] = 0;
			$arrDefender['SUM'] = 0;

			$deuteriumSaveFactor = GetServerData($this->objSource->intUni, $this->objSource->strDomain, 1)["globalDeuteriumSaveFactor"];

			if (!isset($deuteriumSaveFactor)) $deuteriumSaveFactor = 1;

			foreach ($this->objSource->arrCombatResult->defenders as $key => $value) {
				$thisFleetCoordinates = explode(":", $value->fleet_owner_coordinates);
				$thisCoordinates[GALAXY] = $thisFleetCoordinates[0]; 
				$thisCoordinates[STAR] = $thisFleetCoordinates[1]; 
				$thisCoordinates[PLANET] = $thisFleetCoordinates[2];

				$targetFleetCoordinates = explode(":", $this->objSource->arrCombatResult->defenders[0]->fleet_owner_coordinates); 
				$targetCoordinates[GALAXY] = $targetFleetCoordinates[0]; 
				$targetCoordinates[STAR] = $targetFleetCoordinates[1]; 
				$targetCoordinates[PLANET] = $targetFleetCoordinates[2];

				$intConsumption = round (GetSumFleetConsumption_v6($thisCoordinates, $targetCoordinates, $value->fleet_composition, $this->objSource->blnPFuel, false) * $deuteriumSaveFactor);
				$arrDefender[] = $intConsumption;
				$arrDefender['SUM'] += $intConsumption;
			}

			foreach ($this->objSource->arrCombatResult->attackers as $key => $value) {
				$thisFleetCoordinates = explode(":", $value->fleet_owner_coordinates);
				$thisCoordinates[GALAXY] = $thisFleetCoordinates[0]; 
				$thisCoordinates[STAR] = $thisFleetCoordinates[1]; 
				$thisCoordinates[PLANET] = $thisFleetCoordinates[2];

				$targetFleetCoordinates = explode(":", $this->objSource->arrCombatResult->defenders[0]->fleet_owner_coordinates); 
				$targetCoordinates[GALAXY] = $targetFleetCoordinates[0]; 
				$targetCoordinates[STAR] = $targetFleetCoordinates[1]; 
				$targetCoordinates[PLANET] = $targetFleetCoordinates[2];

				$intConsumption = round (GetSumFleetConsumption_v6($thisCoordinates, $targetCoordinates, $value->fleet_composition, $this->objSource->blnPFuel, false) * $deuteriumSaveFactor);
				$arrAttacker[] = $intConsumption;
				$arrAttacker['SUM'] += $intConsumption;
			}

			$arrResult['attacker'] = $arrAttacker;
			$arrResult['defender'] = $arrDefender;

			return $arrResult;
		}

		private function GetStatistics() {
			$strResult = "";
			$arrAttacker;
			$arrDefender;

			$arrAttacker['sum_begin'] = 0;
			$arrAttacker['resources_begin']['SUM'] = 0;
			$arrAttacker['resources_begin']['M'] = 0;
			$arrAttacker['resources_begin']['C'] = 0;
			$arrAttacker['resources_begin']['D'] = 0;
			$arrAttacker['players_begin'] = NULL;
			$arrAttacker['sum_end'] = 0;
			$arrAttacker['resources_end']['SUM'] = 0;
			$arrAttacker['resources_end']['M'] = 0;
			$arrAttacker['resources_end']['C'] = 0;
			$arrAttacker['resources_end']['D'] = 0;
			$arrAttacker['players_end'] = NULL;
			//
			$arrDefender['sum_begin'] = 0;
			$arrDefender['resources_begin'] = NULL;
			$arrDefender['resources_in_defense_begin']['SUM'] = 0;
			$arrDefender['resources_begin']['SUM'] = 0;
			$arrDefender['resources_begin']['M'] = 0;
			$arrDefender['resources_begin']['C'] = 0;
			$arrDefender['resources_begin']['D'] = 0;
			$arrDefender['players_begin'] = NULL;
			$arrDefender['sum_end'] = 0;
			$arrDefender['resources_in_defense_end']['SUM'] = 0;
			$arrDefender['resources_end']['SUM'] = 0;
			$arrDefender['resources_end']['M'] = 0;
			$arrDefender['resources_end']['C'] = 0;
			$arrDefender['resources_end']['D'] = 0;
			$arrDefender['players_end'] = NULL;

			$this->arrCache['battle coordinates'] = NULL;


			/*
			//<custom>
				$strResult .= "<div id=\"combat_result\"><center>";
				$strResult .= "<font color='" . YELLOW_COMMON . "'><bCUSTOM STATISTICS</b></font>";
				$strResult .= "<br><br>";
				$strResult .= "</div>";
			//</custom>
			*/

			//<recycler report / comment / clean-up>
				$strResult_ = "";
				if ($this->objSource->strRecyclerReport) {
					$arrRecyclerReport = $this->arrCache['recycler_report'];
					$strTotalRecycled = "";
					if ($arrRecyclerReport['SUM']) {
						$strTotalRecycled = "
								<tr class='" . TD_BG_2 . "'>
									<td align='left' valign='top' colspan='2' style='padding:10; padding-left:20'>
										<font color='" . WHITE_DARK . "'><b>" . Dictionary("total_recycled") . ": " . PrepareNumber($arrRecyclerReport['SUM']) . " (" . NumberToString($arrRecyclerReport['M']) . " " . Dictionary("metal_r") . " " . Dictionary("and_r") . " " . NumberToString($arrRecyclerReport['C']) . " " . Dictionary("crystal_r") . ")</b></font>
									</td>
								</tr>";
						$intLossesRecycled['SUM'] = 0;
						$intLossesRecycled['M'] = $this->objSource->arrCombatResult->generic->debris_metal - $arrRecyclerReport['M'];
						$intLossesRecycled['C'] = $this->objSource->arrCombatResult->generic->debris_crystal - $arrRecyclerReport['C'];
						if ($intLossesRecycled['M'] > 0) {
							$intLossesRecycled['SUM'] += $intLossesRecycled['M'];
						} else $intLossesRecycled['M'] = 0;
						if ($intLossesRecycled['C'] > 0) {
							$intLossesRecycled['SUM'] += $intLossesRecycled['C'];
						} else $intLossesRecycled['C'] = 0;						

						if ($intLossesRecycled['SUM'] > 0) {
							$strLossesRecycled = "
								<tr class='" . TD_BG_2 . "'>
									<td align='left' valign='top' colspan='2' style='padding:10; padding-left:20'>
										<font color='" . WHITE_DARK . "'><b>Лома утеряно: " . PrepareNumber($intLossesRecycled['SUM']) . " (" . NumberToString($intLossesRecycled['M']) . " " . Dictionary("metal_r") . " " . Dictionary("and_r") . " " . NumberToString($intLossesRecycled['C']) . " " . Dictionary("crystal_r") . ")</b></font>
									</td>
								</tr>";
							$strStyleRecycled = "style='cursor: pointer;'";
						}							
					}
					else {
						if ($arrRecyclerReport['ERR'] == 1)
							$strTotalRecycled = "
								<tr class='" . TD_BG_2 . "'>
									<td align='left' valign='top' colspan='2' style='padding:10; padding-left:20'>
										<font color='" . RED_DARK . "'>[".$this->objSource->strRecyclerReport."]<b>Can't parse report: wrong format. See help (click on \"?\" in upload form) on main page.</b></font>
									</td>
								</tr>";

					}
					$strRecyclerReport = "";
					if ($this->objSource->strRecyclerReport != '*') {
						$strRecyclerReport = nl2br($this->objSource->strRecyclerReport);
						if (substr($strRecyclerReport, 0, 1) == '*')
							$strRecyclerReport = substr($strRecyclerReport, 1, strlen($strRecyclerReport) - 1);
						$strRecyclerReport = "
								<tr class='" . TD_BG_2 . " recycler_report'>
									<td align='left' valign='top' colspan='2' style='padding:10; padding-left:20'>
										$strRecyclerReport
									</td>
								</tr>";
					}
					if (($strTotalRecycled != "") || ($strRecyclerReport != "")) {
						$strResult_ .= "
							<table width='800' border='1' style='border-collapse: collapse' bordercolor='" . "#222222" . "'>
								<tr class='" . TD_BG_1 . "' id='recycler_report' $strStyleRecycled style='cursor: pointer;'>
									<td align='left' colspan='2' style='padding:10'>
										<font color='" . WHITE_DARK . "'><b>" . Dictionary("recycler_report_title") . "</b></font>
									</td>
								</tr>
								$strTotalRecycled
								$strLossesRecycled
								$strRecyclerReport
							</table>";
					}
				}
//КРЕО
				$dataCreo = cDB::LoadCreoByID ($this->objSource->strId);
				if ($dataCreo != 0) {
					$strCreo = "
						<tr class='" . TD_BG_2 . " user_creo' style='display:none'>
							<td align='left' valign='top' colspan='2' style='padding:10; padding-left:20'>
								" . htmlspecialchars_decode($dataCreo["obj_creo"]) . "
							</td>
						</tr>";
					if ($strResult_ != "")
						$strResult_ .= "<br>";
					$strResult_ .= "
							<table width='800' border='1' style='border-collapse: collapse' bordercolor='" . "#222222" . "'>
								<tr id='user_creo' class='" . TD_BG_1 . "' style='cursor: pointer;'>
									<td align='left' colspan='2' style='padding:10'>
										<font color='" . WHITE_DARK . "'><b>CREO</b></font>
									</td>
								</tr>
								$strCreo

							</table>";
				}
//КРЕО
				if ($this->objSource->strComment) {
					$strComment  = print_page($this->objSource->strComment);
					$strComment = "
						<tr class='" . TD_BG_2 . "'>
							<td align='left' valign='top' colspan='2' style='padding:10; padding-left:20'>
								$strComment
							</td>
						</tr>";
					if ($strResult_ != "")
						$strResult_ .= "<br>";
					$strResult_ .= "
							<table width='800' border='1' style='border-collapse: collapse' bordercolor='" . "#222222" . "'>
								<tr class='" . TD_BG_1 . "'>
									<td align='left' colspan='2' style='padding:10'>
										<font color='" . WHITE_DARK . "'><b>" . Dictionary("comment_title") . "</b></font>
									</td>
								</tr>
								$strComment

							</table>";
				}

				//очки чести
				if ($this->objSource->arrCombatResult->generic->combat_honorable && ($this->objSource->arrCombatResult->generic->attacker_honorpoints != 0 || $this->objSource->arrCombatResult->generic->defender_honorpoints != 0)) {
					if ($this->objSource->arrCombatResult->generic->attacker_honorable) $strSignAttacker = "+";
					else $strSignAttacker = "-";

					if ($this->objSource->arrCombatResult->generic->defender_honorable) $strSignDefender = "+";
					else $strSignDefender = "-";
					
					if ($this->objSource->arrCombatResult->generic->attacker_honorpoints == 0) $strSignAttacker = "";
					if ($this->objSource->arrCombatResult->generic->defender_honorpoints == 0) $strSignDefender = "";

					$strHonorable = "
						<tr class='" . TD_BG_2 . "'>
							<td width='50%' align='left' valign='top' border='1' style='padding:10; padding-left:20'>
								<font color='" . WHITE_DARK . "'><b>" . Dictionary("att_gains_1") . ": </font>" . PrepareNumber($strSignAttacker . $this->objSource->arrCombatResult->generic->attacker_honorpoints) . "</font>
							</td>
							<td width='50%' align='left' valign='top' border='1' style='padding:10; padding-left:20'>
								<font color='" . WHITE_DARK . "'><b>" . Dictionary("def_gains_1") . ": </font>" . PrepareNumber($strSignDefender . $this->objSource->arrCombatResult->generic->defender_honorpoints) . "</font>
							</td>
						</tr>";
					if ($strResult_ != "")
						$strResult_ .= "<br>";
					$strResult_ .= "
							<table width='800' border='1' style='border-collapse: collapse' bordercolor='" . "#222222" . "'>
								<tr class='" . TD_BG_1 . "'>
									<td align='left' colspan='2' style='padding:10'>
										<font color='" . WHITE_DARK . "'><b>" . Dictionary("рonor_points") . "</b></font>
									</td>
								</tr>
								$strHonorable

							</table>";
				}
//made by Zmei Комменты
				if (!$this->objSource->bln_post ) {

					$strTdTagBB = "align='center' valign='center' height='28' style='padding-left: 2; padding-right: 2;' onmouseover='this.setAttribute(\"background\", \"" . VISTA_PANEL_A_BB_CODE . "\");' onmouseout='this.setAttribute(\"background\", \"\");'";
					$strResult_ .= "<br>
					<table width='800' border='1' style='border-collapse: collapse' bordercolor='" . "#222222" . "'>
													<tr id='user_comments' class='" . TD_BG_1 . "' style='cursor: pointer;'>
														<td align='left' colspan='2' style='padding:10' width='800'>
															<font color='" . WHITE_DARK . "'><b>" . Dictionary("user_comments_title") . "</b></font>
														</td>
													</tr>
													<tr class='" . TD_BG_2 . " user_comments' style='display:none'>
														<td style='vertical-align: top;'>
					                                        <div id='vk_comments'></div>
					                                        <script type='text/javascript'>VK.Widgets.Comments('vk_comments', {limit: 10, width: '400', attach: '*'});</script>
														</td>
														<td style='vertical-align: top;'>
					                                        <div id='fb-root'></div>
					                                        <script>(function(d, s, id) {
					                                          var js, fjs = d.getElementsByTagName(s)[0];
					                                          if (d.getElementById(id)) return;
					                                          js = d.createElement(s); js.id = id;
					                                          js.src = '//connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v2.0';
					                                          fjs.parentNode.insertBefore(js, fjs);
					                                        }(document, 'script', 'facebook-jssdk'));</script>
					                                        <div class='fb-comments' data-href='http://facebook.com/plugins/comments/".$this->objSource->strId."' data-width='400' data-numposts='5' data-colorscheme='dark'></div>
														</td>
													</tr>";

					$strResult_ .= "</table>";
				}

				if ($this->objSource->strCleanUp) {
					$arrCleanUp = $this->ProcessPlanetCleanUpReport();
					$strTotalCleanUp = "";
					if ($arrCleanUp['SUM']) {
						$strTotalCleanUp = "

								<tr class='" . TD_BG_2 . "'>
									<td align='left' valign='top' colspan='2' style='padding:10; padding-left:20'>
										<font color='" . WHITE_DARK . "'><b>" . Dictionary("total_cleanup") . ": " . PrepareNumber($arrCleanUp['SUM']) . " (" . NumberToString($arrCleanUp['M']) . " " . Dictionary("metal_r") . ", " . NumberToString($arrCleanUp['C']) . " " . Dictionary("crystal_r") . " " . Dictionary("and_r") . " " . NumberToString($arrCleanUp['D']) . " " . Dictionary("deuterium_r") . ")</b></font>
									</td>
								</tr>";
					}
					$strCleanUp = nl2br($this->objSource->strCleanUp);
					$strCleanUp = str_replace("<!-- title_clean_ap -->", Dictionary("title_clean_ap"), $strCleanUp);
					$strCleanUp = str_replace("<!-- metal -->", Dictionary("metal_r"), $strCleanUp);
					$strCleanUp = str_replace("<!-- crystal -->", Dictionary("crystal_r"), $strCleanUp);
					$strCleanUp = str_replace("<!-- deuterium -->", Dictionary("deuterium_r"), $strCleanUp);
					$strCleanUp = "
							<tr class='" . TD_BG_2 . "'>
								<td align='left' valign='top' colspan='2' style='padding:10; padding-left:20'>
									$strCleanUp
								</td>
							</tr>";
					if ($strResult_ != "")
						$strResult_ .= "<br>";
					$strResult_ .= "
							<table width='800' border='1' style='border-collapse: collapse' bordercolor='" . "#222222" . "'>
								<tr class='" . TD_BG_1 . "'>
									<td align='left' colspan='2' style='padding:10'>
										<font color='" . WHITE_DARK . "'><b>" . Dictionary("planet_cleanup_title") . "</b></font>
									</td>
								</tr>
								$strTotalCleanUp
								$strCleanUp
							</table>";
				}
				if ($strResult_ != "") {
					$strResult .= "
						<div id=\"combat_result\">
							<center>
							$strResult_
							</center>
						</div>";
				}
			//<recycler report / comment / clean-up>

			//<MAIN TABLE>

			/*PATTERN:
			<table width='800' border='1' style='border-collapse: collapse' bordercolor='" . "#222222" . "'>
				<tr class='" . TD_BG_1 . "'>
					<td align='???' colspan='2' style='padding:10'>
						TITLE
					</td>
				</tr>
				<tr class='" . TD_BG_2 . "'>
					<td align='left' valign='top' colspan='2' style='padding:10; padding-left:20'>
						CONTENT
					</td>
				</tr>
			</table>
			*/

			//<MAIN TABLE>

			$strResult .= "<div id=\"combat_result\"><center>";

			if ($this->objSource->intIPMs) {
				$strIMPs = "
					<tr class='" . TD_BG_2 . "'>
						<td align='center' valign='top' colspan='2' style='padding:10; padding-left:20'>
							<div name='img_'><img src=" . IMG_IPM . " alt='IPMs'><br></div>
							<b><font color='" . WHITE_DARK . "'>" . PrepareNumber($this->objSource->intIPMs) . "</b></font>
						</td>
					</tr>";
				if ($strResult != "")
					$strResult .= "<br>";
				$strResult .= "
					<table width='800' border='1' style='border-collapse: collapse' bordercolor='" . "#222222" . "'>
						<tr class='" . TD_BG_1 . "'>
							<td align='left' colspan='2' style='padding:10'>
								<font color='" . WHITE_DARK . "'><b>" . Dictionary("missle_launch_det") . "</b></font>
							</td>
						</tr>
						$strIMPs
					</table>";
			}
					$debrisFactor = GetServerData($this->objSource->intUni, $this->objSource->strDomain, 1)["debrisFactor"];

					$strDoc = "
						<tr class='" . TD_BG_2 . " doc' style='display:none'>
							<td width='100%' align='center' valign='top' border='1' style='padding:10; padding-left:20'>
								<table style='border-collapse: collapse' bordercolor='#222222' border='1'>
									<tr class='tbg3'>
										<td colspan='4'></td>
										<td colspan='14' align='center'><font color='" . WHITE_DARK . "'>Уровень</font></td>
									</tr>
									<tr>
										<td colspan='4'></td>

										<td style='min-width:100px' align='center'>1 (" . ((1 - $debrisFactor) * 10 * 4.5). "%)</td>
										<td style='width:1px'></td><td style='min-width:100px' align='center'>2 (" . ((1 - $debrisFactor) * 10 * 4.8). "%)</td>
										<td style='width:1px'></td><td style='min-width:100px' align='center'>3 (" . ((1 - $debrisFactor) * 10 * 4.9). "%)</td>
										<td style='width:1px'></td><td style='min-width:100px' align='center'>4 (" . ((1 - $debrisFactor) * 10 * 5). "%)</td>
										<td style='width:1px'></td><td style='min-width:100px' align='center'>5 (" . ((1 - $debrisFactor) * 10 * 5.1). "%)</td>
										<td style='width:1px'></td><td style='min-width:100px' align='center'>6 (" . ((1 - $debrisFactor) * 10 * 5.2). "%)</td>
									</tr>
									<tr>
										<td colspan='16' style='padding:2'></td>
									</tr>";

					$intDocBaseCost = array();
					foreach ($this->arrCache["arrLossesFleetDefenders"] as $value) {
						$i = 0;
						foreach ($value as $val) {
							if ($val["ship_type"] > 216) break;
							if ($val["count"] > 0 && $val["ship_type"] != 210 && $val["ship_type"] != 212) {
								if (!isset($intDocBaseCost["M"])) $intDocBaseCost["M"] = 0;
								if (!isset($intDocBaseCost["C"])) $intDocBaseCost["C"] = 0;
								if (!isset($intDocBaseCost["D"])) $intDocBaseCost["D"] = 0;
								$intDocBaseCost["M"] += GetBaseCost($val["ship_type"])["M"] * $val["count"];
								$intDocBaseCost["C"] += GetBaseCost($val["ship_type"])["C"] * $val["count"];
								$intDocBaseCost["D"] += GetBaseCost($val["ship_type"])["D"] * $val["count"];
								if ($i % 2) $class = "class='tbg4'";
								else $class = "class='tbg3'";

								$strDoc .=  "
								<tr " . $class .">
									<td><font color='" . WHITE_DARK . "'>" . Dictionary($val["ship_type"]) . "</font></td>
									<td></td>
									<td align='center'><font color='" . RED_COMMON . "'>" . NumberToString($val["count"]) . "</font></td>
									<td></td><td align='center'>" . NumberToString(floor($val["count"] * ((1 - $debrisFactor) * 10 * 4.5)/100)) . "</td>
									<td></td><td align='center'>" . NumberToString(floor($val["count"] * ((1 - $debrisFactor) * 10 * 4.8)/100)) . "</td>
									<td></td><td align='center'>" . NumberToString(floor($val["count"] * ((1 - $debrisFactor) * 10 * 4.9)/100)) . "</td>
									<td></td><td align='center'>" . NumberToString(floor($val["count"] * ((1 - $debrisFactor) * 10 * 5)/100)) . "</td>
									<td></td><td align='center'>" . NumberToString(floor($val["count"] * ((1 - $debrisFactor) * 10 * 5.1)/100)) . "</td>
									<td></td><td align='center'>" . NumberToString(floor($val["count"] * ((1 - $debrisFactor) * 10 * 5.2)/100)) . "</td>
								</tr>";
							}
							$i++;
						}
						break;
					}
					$strDoc .=  "<tr><td colspan='16' style='padding:6'></td></tr>";

					$strDoc .=  "
					<tr class='tbg4'>
						<td><font color='" . WHITE_DARK . "'>" . Dictionary("metal") . "</font></td>
						<td></td>
						<td align='center'><font color='" . RED_COMMON . "'>" . NumberToString($intDocBaseCost["M"]) . "</font></td>
						<td></td><td align='center'>" . NumberToString(ceil($intDocBaseCost["M"] * ((1 - $debrisFactor) * 10 * 4.5)/100)) . "</td>
						<td></td><td align='center'>" . NumberToString(ceil($intDocBaseCost["M"] * ((1 - $debrisFactor) * 10 * 4.8)/100)) . "</td>
						<td></td><td align='center'>" . NumberToString(ceil($intDocBaseCost["M"] * ((1 - $debrisFactor) * 10 * 4.9)/100)) . "</td>
						<td></td><td align='center'>" . NumberToString(ceil($intDocBaseCost["M"] * ((1 - $debrisFactor) * 10 * 5)/100)) . "</td>
						<td></td><td align='center'>" . NumberToString(ceil($intDocBaseCost["M"] * ((1 - $debrisFactor) * 10 * 5.1)/100)) . "</td>
						<td></td><td align='center'>" . NumberToString(ceil($intDocBaseCost["M"] * ((1 - $debrisFactor) * 10 * 5.2)/100)) . "</td>
					</tr>";
					$strDoc .=  "
					<tr class='tbg3'>
						<td><font color='" . WHITE_DARK . "'>" . Dictionary("crystal") . "</font></td>
						<td></td>
						<td align='center'><font color='" . RED_COMMON . "'>" . NumberToString($intDocBaseCost["C"]) . "</font></td>
						<td></td><td align='center'>" . NumberToString(ceil($intDocBaseCost["C"] * ((1 - $debrisFactor) * 10 * 4.5)/100)) . "</td>
						<td></td><td align='center'>" . NumberToString(ceil($intDocBaseCost["C"] * ((1 - $debrisFactor) * 10 * 4.8)/100)) . "</td>
						<td></td><td align='center'>" . NumberToString(ceil($intDocBaseCost["C"] * ((1 - $debrisFactor) * 10 * 4.9)/100)) . "</td>
						<td></td><td align='center'>" . NumberToString(ceil($intDocBaseCost["C"] * ((1 - $debrisFactor) * 10 * 5)/100)) . "</td>
						<td></td><td align='center'>" . NumberToString(ceil($intDocBaseCost["C"] * ((1 - $debrisFactor) * 10 * 5.1)/100)) . "</td>
						<td></td><td align='center'>" . NumberToString(ceil($intDocBaseCost["C"] * ((1 - $debrisFactor) * 10 * 5.2)/100)) . "</td>
					</tr>";
					$strDoc .=  "
					<tr class='tbg4'>
						<td><font color='" . WHITE_DARK . "'>" . Dictionary("deuterium") . "</font></td>
						<td></td>
						<td align='center'><font color='" . RED_COMMON . "'>" . NumberToString($intDocBaseCost["D"]) . "</font></td>
						<td></td><td align='center'>" . NumberToString(ceil($intDocBaseCost["D"] * ((1 - $debrisFactor) * 10 * 4.5)/100)) . "</td>
						<td></td><td align='center'>" . NumberToString(ceil($intDocBaseCost["D"] * ((1 - $debrisFactor) * 10 * 4.8)/100)) . "</td>
						<td></td><td align='center'>" . NumberToString(ceil($intDocBaseCost["D"] * ((1 - $debrisFactor) * 10 * 4.9)/100)) . "</td>
						<td></td><td align='center'>" . NumberToString(ceil($intDocBaseCost["D"] * ((1 - $debrisFactor) * 10 * 5)/100)) . "</td>
						<td></td><td align='center'>" . NumberToString(ceil($intDocBaseCost["D"] * ((1 - $debrisFactor) * 10 * 5.1)/100)) . "</td>
						<td></td><td align='center'>" . NumberToString(ceil($intDocBaseCost["D"] * ((1 - $debrisFactor) * 10 * 5.2)/100)) . "</td>
					</tr>";

					//summary
					$strDoc .=  "<tr><td colspan='16' style='padding:2'></td></tr>";					
					$intDocBaseCost["SUM"] = $intDocBaseCost["M"] + $intDocBaseCost["C"] + $intDocBaseCost["D"];
					$strDoc .=  "
					<tr class='tbg3'>
						<td><font color='" . WHITE_DARK . "'>" . Dictionary("summary") . "</font></td>
						<td></td>
						<td align='center'><font color='" . RED_COMMON . "'>" . NumberToString($intDocBaseCost["SUM"]) . "</font></td>
						<td></td><td align='center'><font color='" . GREEN_COMMON . "'>" . NumberToString(ceil($intDocBaseCost["SUM"] * ((1 - $debrisFactor) * 10 * 4.5)/100)) . "</font></td>
						<td></td><td align='center'><font color='" . GREEN_COMMON . "'>" . NumberToString(ceil($intDocBaseCost["SUM"] * ((1 - $debrisFactor) * 10 * 4.8)/100)) . "</font></td>
						<td></td><td align='center'><font color='" . GREEN_COMMON . "'>" . NumberToString(ceil($intDocBaseCost["SUM"] * ((1 - $debrisFactor) * 10 * 4.9)/100)) . "</font></td>
						<td></td><td align='center'><font color='" . GREEN_COMMON . "'>" . NumberToString(ceil($intDocBaseCost["SUM"] * ((1 - $debrisFactor) * 10 * 5)/100)) . "</font></td>
						<td></td><td align='center'><font color='" . GREEN_COMMON . "'>" . NumberToString(ceil($intDocBaseCost["SUM"] * ((1 - $debrisFactor) * 10 * 5.1)/100)) . "</font></td>
						<td></td><td align='center'><font color='" . GREEN_COMMON . "'>" . NumberToString(ceil($intDocBaseCost["SUM"] * ((1 - $debrisFactor) * 10 * 5.2)/100)) . "</font></td>
					</tr>";

					$strDoc .= "</table>
							</td>
						</tr>";

			if (isset($debrisFactor)) {
				if ($strResult != "")
					$strResult .= "<br>";				
				$strResult .= 	"
									<table width='800' border='1' style='border-collapse: collapse' bordercolor='" . "#222222" . "'>
										<tr id='doc' class='" . TD_BG_1 . "'  style='cursor: pointer;'>
											<td align='center' height='38' colspan='2' style='padding:10'>
												<font color='" . YELLOW_COMMON . "'><b>" . Dictionary("space_dock") . "</b></font>
											</td>
										</tr>
									$strDoc

									</table>
								";
			}						

			//<resources>
			$strTitle = "<font color='" . YELLOW_COMMON . "'><b>" . Dictionary("profit_title") . "</b></font>";

			$arrCombatResult = $this->arrCache['combat_result'];
			$arrRecyclerReport = $this->arrCache['recycler_report'];
			$arrIPMs = $this->arrCache['ipms'];
			$arrPlanetCleanUpReport = $this->ProcessPlanetCleanUpReport();
			$arrConsumption = $this->ProcessConsumption();
            if ($this->objSource->strReportPO == "Attacker" || $this->objSource->strReportPO == "Defender") {
                if ($this->objSource->strReportPO == "Attacker") {
    			    $intTotalGains = $arrAttacker['resources_end']['SUM'] - $arrAttacker['resources_begin']['SUM'] + $arrCombatResult[0]['SUM'] + $arrRecyclerReport['SUM'] - $arrIPMs['SUM'] + $arrPlanetCleanUpReport['SUM'] - $arrConsumption['attacker']['SUM'];
                } else {
    			    $intTotalGains = $arrAttacker['resources_end']['SUM'] - $arrAttacker['resources_begin']['SUM'] + $arrCombatResult[0]['SUM'] - $arrIPMs['SUM'] + $arrPlanetCleanUpReport['SUM'] - $arrConsumption['attacker']['SUM'];
                }
            } else {
    		    $intTotalGains = $arrAttacker['resources_end']['SUM'] - $arrAttacker['resources_begin']['SUM'] + $arrCombatResult[0]['SUM'] - $arrIPMs['SUM'] + $arrPlanetCleanUpReport['SUM'] - $arrConsumption['attacker']['SUM'];
            }
            $intTotalGains = $intTotalGains - $this->objSource->arrCombatResult->generic->units_lost_attackers;
            $strAttacker = "<b><font color='" . WHITE_DARK . "'>" . Dictionary("att_gains_2") . ": </font><font color='" . WHITE_DARK . "'>" . PrepareNumber($intTotalGains) . "</font></b>";
			$this->arrCache["profit"]["attacker"] = $intTotalGains;

            if ($this->objSource->strReportPO == "Attacker" || $this->objSource->strReportPO == "Defender") {
                if ($this->objSource->strReportPO == "Defender") {
    			    $intTotalGains = $arrDefender['resources_end']['SUM'] - $arrDefender['resources_begin']['SUM'] - $arrCombatResult[0]['SUM'] + $arrRecyclerReport['SUM'] - $arrPlanetCleanUpReport['SUM'] - $arrConsumption['defender']['SUM'];
                }
                 else {
    			    $intTotalGains = $arrDefender['resources_end']['SUM'] - $arrDefender['resources_begin']['SUM'] - $arrCombatResult[0]['SUM'] - $arrPlanetCleanUpReport['SUM'] - $arrConsumption['defender']['SUM'];
                }
            } else {
    		    $intTotalGains = $arrDefender['resources_end']['SUM'] - $arrDefender['resources_begin']['SUM'] - $arrCombatResult[0]['SUM'] - $arrPlanetCleanUpReport['SUM'] - $arrConsumption['defender']['SUM'];
            }
            $intTotalGains = $intTotalGains - $this->objSource->arrCombatResult->generic->units_lost_defenders;
            $strDefender  = "<b><font color='" . WHITE_DARK . "'>" . Dictionary("def_gains_2") . ": </font><font color='" . WHITE_DARK . "'>" . PrepareNumber($intTotalGains) . "</font></b>";
			$this->arrCache["profit"]["defender"] = $intTotalGains;

			if ($strResult != "")
				$strResult .= "<br>";
			$strResult .= 	"
								<table width='800' border='1' style='border-collapse: collapse' bordercolor='" . "#222222" . "'>
									<tr class='" . TD_BG_1 . "'>
										<td align='center' height='38' colspan='2' style='padding:10'>
											$strTitle
										</td>
									</tr>
									<tr class='" . TD_BG_2 . "'>
										<td width='50%' align='left' valign='top' border='1' style='padding:10; padding-left:20'>
											$strAttacker
										</td>
										<td width='50%' align='left' valign='top' border='1' style='padding:10; padding-left:20'>
											$strDefender
										</td>
									</tr>
								</table>
							";
			//</resources>

			$strResult .= 	"<br>";

			//<losses>
			$intSumLosses = $this->objSource->arrCombatResult->generic->units_lost_attackers + $this->objSource->arrCombatResult->generic->units_lost_defenders;

			$this->arrCache["SumLossesMC"] = NumberToString($intSumLosses);

			$strTitle = "<font color='" . YELLOW_COMMON . "'><b>" . Dictionary("losses_title") . ": " . NumberToString($intSumLosses) . "</b></font>";

			if ($intSumLosses == 0)
				$strTotalLosses = "<font color='" . WHITE_DARK . "'><b>" . Dictionary("summary") . ": " . "</b></font>" . "<b><font color='" . YELLOW_COMMON . "'>" . NumberToString($intSumLosses) . "</font>" . "</b></font>";
			else
				$strTotalLosses = "<font color='" . WHITE_DARK . "'><b>" . Dictionary("summary") . ": " . "</b></font>" . "<b><font color='" . RED_COMMON . "'>" . NumberToString($intSumLosses) . "</font>" . " <font color='" . WHITE_DARK . "'>(" . NumberToString($this->objSource->arrCombatResult->generic->units_lost_attackers) . " + " . NumberToString($this->objSource->arrCombatResult->generic->units_lost_defenders) . ")</font></b></font>";


			$strProgress = "";
			$strLoses = "";
			if ($intSumLosses != 0) {
				$intProcLosses["Atakers"] = round($this->objSource->arrCombatResult->generic->units_lost_attackers * 100 / $intSumLosses, 2);
				$intProcLosses["Defenders"] = round($this->objSource->arrCombatResult->generic->units_lost_defenders * 100 / $intSumLosses, 2);

				$intDelim = round($this->objSource->arrCombatResult->generic->units_lost_attackers * 100 / ($this->objSource->arrCombatResult->generic->units_lost_attackers + $this->objSource->arrCombatResult->generic->units_lost_defenders)); 
				$strProgress = $this->GetStatistics_CreateProgress($intDelim);
				$strProgress = "
                                    <tr class='" . TD_BG_2 . " losses' style='display:none'>
										<td align='center' height='38' colspan='2' style='padding:10'>
											$strTotalLosses
										</td>
									</tr>
									<tr class='" . TD_BG_2 . " losses' style='display:none'>
										<td align='center' colspan='2' style='padding:2'>
											$strProgress
										</td>
									</tr>
								";

				$strAttacker = "<font color='" . WHITE_DARK . "'>" . "<b>" . Dictionary("att_total_losses") . ": " . "<font color='" . RED_COMMON . "'>" . NumberToString($this->objSource->arrCombatResult->generic->units_lost_attackers) . "</font>" ." (" . $intProcLosses["Atakers"] . "%)" . "</font>" . "</b><br>";

				if (count($this->arrCache["arrLossesFleetAtakers"]) > 1) $countArrLossesFleetAtakers = true;
				foreach ($this->arrCache["arrLossesFleetAtakers"] as $key => $value) {
					$baseCost = false;
					$arrLossesFleet = 0;
					foreach ($value as $val) {
						$baseCost = GetBaseCost($val["ship_type"]);
						$arrLossesFleet += $val["count"] * ($baseCost["M"] + $baseCost["C"] + $baseCost["D"]);
						if (!isset($this->arrCache["LossesBaseAtakers"]["M"])) $this->arrCache["LossesBaseAtakers"]["M"] = 0;
						if (!isset($this->arrCache["LossesBaseAtakers"]["C"])) $this->arrCache["LossesBaseAtakers"]["C"] = 0;
						if (!isset($this->arrCache["LossesBaseAtakers"]["D"])) $this->arrCache["LossesBaseAtakers"]["D"] = 0;
						$this->arrCache["LossesBaseAtakers"]["M"] += $val["count"] * $baseCost["M"]; 
						$this->arrCache["LossesBaseAtakers"]["C"] += $val["count"] * $baseCost["C"]; 
						$this->arrCache["LossesBaseAtakers"]["D"] += $val["count"] * $baseCost["D"];

						if (!isset($this->arrCache["LossesBaseNameAtakers"][$key]["M"])) $this->arrCache["LossesBaseNameAtakers"][$key]["M"] = 0;
						if (!isset($this->arrCache["LossesBaseNameAtakers"][$key]["C"])) $this->arrCache["LossesBaseNameAtakers"][$key]["C"] = 0;
						if (!isset($this->arrCache["LossesBaseNameAtakers"][$key]["D"])) $this->arrCache["LossesBaseNameAtakers"][$key]["D"] = 0;
						$this->arrCache["LossesBaseNameAtakers"][$key]["M"] += $val["count"] * $baseCost["M"]; 
						$this->arrCache["LossesBaseNameAtakers"][$key]["C"] += $val["count"] * $baseCost["C"]; 
						$this->arrCache["LossesBaseNameAtakers"][$key]["D"] += $val["count"] * $baseCost["D"]; 

					}
					//if ($countArrLossesFleetAtakers) $strAttacker .= "<font color='" . WHITE_DARK . "'>" . "- " . $key . ": " . $this->arrCache["LossesBaseNameAtakers"][$key]["M"] . " + " . $this->arrCache["LossesBaseNameAtakers"][$key]["C"] . " + " . $this->arrCache["LossesBaseNameAtakers"][$key]["D"] . " = " . NumberToString($arrLossesFleet) . " (" . round($arrLossesFleet * 100 / $this->objSource->arrCombatResult->generic->units_lost_attackers,2) . "%) " . "</font>" . "<br>";
					if (isset($countArrLossesFleetAtakers)) $strAttacker .= "<font color='" . WHITE_DARK . "'>" . "- " . $key . ": " . NumberToString($arrLossesFleet) . " (" . round($arrLossesFleet * 100 / $this->objSource->arrCombatResult->generic->units_lost_attackers,2) . "%) " . "</font>" . "<br>";
				}

				$strDefender = "<font color='" . WHITE_DARK . "'>" . "<b>" . Dictionary("def_total_losses") . ": " . "<font color='" . RED_COMMON . "'>" . NumberToString($this->objSource->arrCombatResult->generic->units_lost_defenders). "</font>" . " (" . $intProcLosses["Defenders"] . "%)" . "</font>" . "</b><br>";

				if (count($this->arrCache["arrLossesFleetDefenders"]) > 1) $countArrLossesFleetDefenders = true;
				foreach ($this->arrCache["arrLossesFleetDefenders"] as $key => $value) {
					$baseCost = false;
					$arrLossesFleet = 0;
					foreach ($value as $val) {
						$baseCost = GetBaseCost($val["ship_type"]);
						$arrLossesFleet += $val["count"] * ($baseCost["M"] + $baseCost["C"] + $baseCost["D"]);
						if (!isset($this->arrCache["LossesBaseDefenders"]["M"])) $this->arrCache["LossesBaseDefenders"]["M"] = 0;
						if (!isset($this->arrCache["LossesBaseDefenders"]["C"])) $this->arrCache["LossesBaseDefenders"]["C"] = 0;
						if (!isset($this->arrCache["LossesBaseDefenders"]["D"])) $this->arrCache["LossesBaseDefenders"]["D"] = 0;
						$this->arrCache["LossesBaseDefenders"]["M"] += $val["count"] * $baseCost["M"]; 
						$this->arrCache["LossesBaseDefenders"]["C"] += $val["count"] * $baseCost["C"]; 
						$this->arrCache["LossesBaseDefenders"]["D"] += $val["count"] * $baseCost["D"];

						if (!isset($this->arrCache["LossesBaseNameDefenders"][$key]["M"])) $this->arrCache["LossesBaseNameDefenders"][$key]["M"] = 0;
						if (!isset($this->arrCache["LossesBaseNameDefenders"][$key]["C"])) $this->arrCache["LossesBaseNameDefenders"][$key]["C"] = 0;
						if (!isset($this->arrCache["LossesBaseNameDefenders"][$key]["D"])) $this->arrCache["LossesBaseNameDefenders"][$key]["D"] = 0;
						$this->arrCache["LossesBaseNameDefenders"][$key]["M"] += $val["count"] * $baseCost["M"]; 
						$this->arrCache["LossesBaseNameDefenders"][$key]["C"] += $val["count"] * $baseCost["C"]; 
						$this->arrCache["LossesBaseNameDefenders"][$key]["D"] += $val["count"] * $baseCost["D"]; 													
					}
					if (isset($countArrLossesFleetDefenders)) $strDefender .= "<font color='" . WHITE_DARK . "'>" . "- " . $key . ": " . NumberToString($arrLossesFleet) . " (" . round($arrLossesFleet * 100 / $this->objSource->arrCombatResult->generic->units_lost_defenders,2) . "%) " . "</font>" . "<br>";
				}

				//<def stat>
				$strDefenderEx = "";
				if ($arrDefender['resources_begin']['SUM'] > 0) {

					$strDefA = round(($arrDefender['resources_in_defense_begin']['SUM']) / ($arrDefender['resources_begin']['SUM'])* 100);
					$strDefB = 100 - $strDefA;

					$strDefenderEx = "
						<br>
						<font color='" . WHITE_DARK . "'>" . Dictionary("defense") . ": $strDefA% / " . Dictionary("fleet") . ": <font color='" . RED_COMMON . "'>$strDefB%</font></font>
						<br>
						<font color='" . WHITE_DARK . "'>" . Dictionary("exp_real_lossed") . ": " . str_replace("-", "", PrepareNumber(round($arrDefender['resources_end']['SUM'] - $arrDefender['resources_begin']['SUM'] + (-$arrDefender['resources_in_defense_end']['SUM'] + $arrDefender['resources_in_defense_begin']['SUM']) * 0.7))) . "</font>";
					}
				//</fdef stat>


				$strLoses = 	"
									<tr class='" . TD_BG_2 . " losses'>
										<td width='50%' align='left' valign='top' border='1' style='padding:10; padding-left:20'>
											$strAttacker
										</td>
										<td width='50%' align='left' valign='top' border='1' style='padding:10; padding-left:20'>
											$strDefender
											$strDefenderEx
										</td>
									</tr>
								";
			}

			$strResult .= 	"
								<table width='800' border='1' style='border-collapse: collapse' bordercolor='" . "#222222" . "'>
									<tr id='losses' class='" . TD_BG_1 . "' style='cursor: pointer;'>
										<td align='center' height='38' colspan='2' style='padding:10'>
											$strTitle
										</td>
									</tr>
									$strProgress
									$strLoses
								</table>
							";
			//</losses>

			//<compounds>
			$compFleetAttackers = 0;
			$compFleetDefenders = 0;
			foreach ($this->objSource->arrCombatResult->attackers as $attackers) {
				foreach ($attackers->fleet_composition as $fleet) {
					$baseCostAttackers = GetBaseCost($fleet->ship_type);
					$compFleetAttackers = $compFleetAttackers + ($baseCostAttackers["M"] + $baseCostAttackers["C"] + $baseCostAttackers["D"]) * $fleet->count;
					if (!isset($arrAttackers[$attackers->fleet_owner])) $arrAttackers[$attackers->fleet_owner] = 0;
					$arrAttackers[$attackers->fleet_owner] = $arrAttackers[$attackers->fleet_owner] + ($baseCostAttackers["M"] + $baseCostAttackers["C"] + $baseCostAttackers["D"]) * $fleet->count;
					$arrAttacker['resources_begin']['M'] += $baseCostAttackers["M"] * $fleet->count;
					$arrAttacker['resources_begin']['C'] += $baseCostAttackers["C"] * $fleet->count;
					$arrAttacker['resources_begin']['D'] += $baseCostAttackers["D"] * $fleet->count;	 
				}
			}
			$arrAttacker['resources_begin']['SUM'] = $arrAttacker['resources_begin']['M'] + $arrAttacker['resources_begin']['C'] + $arrAttacker['resources_begin']['D'];

			foreach ($this->objSource->arrCombatResult->defenders as $defenders) {
				foreach ($defenders->fleet_composition as $fleet) {
					$baseCostDefenders = GetBaseCost($fleet->ship_type);
					$compFleetDefenders = $compFleetDefenders + ($baseCostDefenders["M"] + $baseCostDefenders["C"] + $baseCostDefenders["D"]) * $fleet->count;
					if (!isset($arrDefenders[$defenders->fleet_owner])) $arrDefenders[$defenders->fleet_owner] = 0;
					$arrDefenders[$defenders->fleet_owner] = $arrDefenders[$defenders->fleet_owner] + ($baseCostDefenders["M"] + $baseCostDefenders["C"] + $baseCostDefenders["D"]) * $fleet->count;

					$arrDefender['resources_begin']['M'] += $baseCostDefenders["M"] * $fleet->count; 
					$arrDefender['resources_begin']['C'] += $baseCostDefenders["C"] * $fleet->count; 
					$arrDefender['resources_begin']['D'] += $baseCostDefenders["D"] * $fleet->count; 
				}
			}
			$arrDefender['resources_begin']['SUM'] = $arrDefender['resources_begin']['M'] + $arrDefender['resources_begin']['C'] + $arrDefender['resources_begin']['D'];


			$strTitle = "<font color='" . YELLOW_COMMON . "'><b>" . Dictionary("compounds_title") . ": " . NumberToString($compFleetAttackers + $compFleetDefenders) . "</b></font>";

			$strTotalComp = "<font color='" . WHITE_DARK . "'><b>" . Dictionary("summary") . ": " . "</b></font>" . "<b><font color='" . RED_COMMON . "'>" . NumberToString($compFleetAttackers + $compFleetDefenders) . "</font>" . " <font color='" . WHITE_DARK . "'>(" . NumberToString($compFleetAttackers) . " + " . NumberToString($compFleetDefenders) . ")</font></b></font>";

			$strProgress = $this->GetStatistics_CreateProgress(round($compFleetAttackers / ($compFleetAttackers + $compFleetDefenders) * 100));

			$strAttacker = "<font color='" . WHITE_DARK . "'>" . "<b>" . Dictionary("att_total_comp") . ": "  . " <font color='" . YELLOW_COMMON . "'>" . NumberToString($compFleetAttackers) . "</font>" . "</font></b><br>";
			if (count($arrAttackers) > 1)
				foreach ($arrAttackers as $key => $value) {
					$this->arrCache["compFleetProcAttackers"][$key] = $value * 100 / $compFleetAttackers; 
					$strAttacker .= "<font color='" . WHITE_DARK . "'>" . "- " . $key . ": " . NumberToString($value) . " (" . round($this->arrCache["compFleetProcAttackers"][$key], 2) . "%) " . "</font>" . "<br>";
				}

			$strDefender = "<font color='" . WHITE_DARK . "'>" . "<b>" . Dictionary("def_total_comp") . ": "  . " <font color='" . YELLOW_COMMON . "'>" . NumberToString($compFleetDefenders) . "</font>" . "</font></b><br>";
			if (count($arrDefenders) > 1)
				foreach ($arrDefenders as $key => $value) {
					$this->arrCache["compFleetProcDefenders"][$key] = $value * 100 / $compFleetDefenders; 
					$strDefender .= "<font color='" . WHITE_DARK . "'>" . "- " . $key . ": " . NumberToString($value) . " (" . round($this->arrCache["compFleetProcDefenders"][$key], 2) . "%) " . "</font>" . "<br>";
				}

			$strResult .= 	"<br>";

			$strResult .= 	"
								<table width='800' border='1' style='border-collapse: collapse' bordercolor='" . "#222222" . "'>
									<tr id='compounds' class='" . TD_BG_1 . "' style='cursor: pointer;'>
										<td align='center' height='38' colspan='2' style='padding:10'>
											$strTitle
										</td>
									</tr>
									<tr class='" . TD_BG_2 . " compounds' style='display:none'>
										<td align='center' colspan='2' height='38' style='padding:2'>
											$strTotalComp
										</td>
									</tr>
									<tr class='" . TD_BG_2 . " compounds' style='display:none'>
										<td align='center' colspan='2' style='padding:2'>
											$strProgress
										</td>
									</tr>
									<tr class='" . TD_BG_2 . " compounds' style='display:none'>
										<td width='50%' align='left' valign='top' border='1' style='padding:10; padding-left:20'>
											$strAttacker
										</td>
										<td width='50%' align='left' valign='top' border='1' style='padding:10; padding-left:20'>
											$strDefender
										</td>
									</tr>
								</table>
							";
			//</compounds>

			$strResult .= "</div>";

			//</MAIN TABLE>

			//<RS>
				$strResult .= $this->CreateRSDiv($arrAttacker, $arrDefender);
			//</RS>

			//<split-up>
				if (count($this->arrCache["LossesBaseNameDefenders"]) > 1) $strResult .= $this->CreateSplitUpDiv(false);
				if (count($this->arrCache["LossesBaseNameAtakers"]) > 1) $strResult .= $this->CreateSplitUpDiv(true);
			//</split-up>

			//<rank+url+bbcode>
				$strResult .= $this->CreateRankAndLinksDiv($arrAttacker, $arrDefender);
			//</rank+url+bbcode>

			return $strResult;
		}

		private function GetStatistics_CreateProgress($intDelim) {
			$intWidth = 200;
			$strResult = "";
			$strResult .= "<table>
								<tr>
									<td style='padding:4' width='100' align='right'>
										<font color='" . WHITE_DARK . "'>" . $intDelim . "%  </font>
									</td>
									<td style='padding:4'>
										<img id='' src='" . PROGRESS_20X20_RED . "' border='0' width='" . ($intDelim * $intWidth / 100) . "' height='20'>"."<img id='' src='" . PROGRESS_20X20_GREEN . "' border='0' width='" . ((100 - $intDelim) * $intWidth / 100) . "' height='20'>
									</td>
									<td style='padding:4' width='100' align='left'>
										<font color='" . WHITE_DARK . "'>" . (100-$intDelim) . "%  </font>
									</td>
								</tr>
							</table>";
			return $strResult;
		}

		private function UpdateCombatResult($strCombatResult) {
			$strCombatResult = trim($strCombatResult);

			$strCombatResult = str_replace("<br>","\n",$strCombatResult);
			$strCombatResult = str_replace("\r","\n",$strCombatResult);
			$strCombatResult = str_replace("  "," ",$strCombatResult);
			$strCombatResult = str_replace("\t","",$strCombatResult);
			$strCombatResult = strip_tags($strCombatResult);

			$arrTemp = explode("\n",$strCombatResult);

			$strCombatResult = "";
			foreach ($arrTemp as $strValue) {
				if ($strValue != "")
					$strCombatResult = $strCombatResult.$strValue."\n";
			}

			return $strCombatResult;
		}

		private function GetBBUrl($SERVERURL) {
			$strTitle = $this->Get("title");
			$strDomain = strtolower($this->objSource->strDomain);
			$strUni = strtolower('uni' . $this->objSource->intUni);
			$varUni = $this->objSource->intUni;
			$varShortNameUni = ShortNameUni($varUni,true);
			$varUni = NameUni($varUni);

            if ($this->arrCache["profit"]["attacker"] > $this->arrCache["profit"]["defender"]) $strProfit = $this->arrCache["profit"]["attacker"];
            else $strProfit = $this->arrCache["profit"]["defender"];

            if ($strProfit > 0) {
            	$getStrProfit = "+" . NumberS($strProfit, true);
            	$strProfit = "[color=#009900]+".NumberS($strProfit, true)."[/color]";
            }  else {
            	$getStrProfit = "-" . NumberS($strProfit, true);
            	$strProfit = "[color=#ff0000]-".NumberS($strProfit, true)."[/color]";
            }

            $strLongTitle = "[".$varShortNameUni."] ".$strTitle." (". $this->arrCache["SumLossesMC"] .", ".ucfirst(strtolower($varUni)).".".strtolower($this->objSource->strDomain).", ".DateConstructor_6x($this->objSource->arrCombatResult->generic->event_timestamp, 2).")";
			$strBBUrl = "[url=".$SERVERURL."?id=".$this->objSource->strId."]";
			$strBBUrl .= $strLongTitle;
			$strBBUrl .= "[".$strProfit."]";
			$strBBUrl .= "[/url]";

            if ($SERVERURL == LOGSERVERURL)
			    $this->arrCache["bburl"] = $strBBUrl;

            if ($SERVERURL == ALTLOGSERVERURL)
			    $this->arrCache["bburl2"] = $strBBUrl;

			$this->arrCache["longtitle"] = $strLongTitle . " " . $getStrProfit;
			return $strBBUrl;
		}

		private function GetUrl($SERVERURL) {
			$strTitle = str_replace(" ", "_", $this->Get("title"));
			$strDomain = strtolower($this->objSource->strDomain);
			$strUni = strtolower('uni' . $this->objSource->intUni);
			$varUni = $this->objSource->intUni;
			$varShortNameUni = ShortNameUni($varUni,true);
			$varUni = NameUni($varUni);

            if ($this->arrCache["profit"]["attacker"] > $this->arrCache["profit"]["defender"]) $strProfit = $this->arrCache["profit"]["attacker"];
            else $strProfit = $this->arrCache["profit"]["defender"];

            if ($strProfit > 0) $strProfit = "+" . NumberS($strProfit, true);
            elseif ($strProfit == 0) $strProfit = 0;
            else $strProfit = "-" . NumberS($strProfit, true);

            $strLongTitle = "_[".$varShortNameUni."]_".$strTitle."_(". $this->arrCache["SumLossesMC"] ."_".ucfirst(strtolower($varUni)).".".strtolower($this->objSource->strDomain)."_".DateConstructor_6x($this->objSource->arrCombatResult->generic->event_timestamp, 2).")_[".$strProfit."]";
			$strBBUrl = $SERVERURL."?id=".$this->objSource->strId;
			$strBBUrl .= $strLongTitle;
			$this->arrCache["urlm"] = $strBBUrl;
			return $strBBUrl;
		}

		private function GetBBUrl2($SERVERURL) {
		$strTitle = $this->Get("title");
		$strDomain = strtolower($this->objSource->strDomain);
		$strUni = strtolower('uni' . $this->objSource->intUni);
			$varUni = $this->objSource->intUni;
			$varShortNameUni = ShortNameUni($varUni,true);
			$varUni = NameUni($varUni);

            if ($this->arrCache["profit"]["attacker"] > $this->arrCache["profit"]["defender"]) $strProfit = $this->arrCache["profit"]["attacker"];
            else $strProfit = $this->arrCache["profit"]["defender"];

            if ($strProfit > 0) $strProfit = "[color=#009900]+".NumberS($strProfit, true)."[/color]";
            else $strProfit = "[color=#ff0000]-".NumberS($strProfit, true)."[/color]";

            $strLongTitle = "[".$varShortNameUni."] ".$strTitle." (". $this->arrCache["SumLossesMC"] .", ".ucfirst(strtolower($varUni)).".".strtolower($this->objSource->strDomain).", ".DateConstructor_6x($this->objSource->arrCombatResult->generic->event_timestamp, 2).") [".$strProfit."]";
			$strBBUrl = "[url=".$SERVERURL."?id=".$this->objSource->strId."]";
			$strBBUrl .= $strLongTitle;
			$strBBUrl .= "[/url]";
            if ($SERVERURL == LOGSERVERURL){
			    $this->arrCache["bburl"] = $strBBUrl;
            }
            if ($SERVERURL == ALTLOGSERVERURL){
			    $this->arrCache["bburl2"] = $strBBUrl;
            }
			$this->arrCache["longtitle"] = $strLongTitle;
			return $strBBUrl;
		}

		private function CreateBBCode() {
			//var_dump($this->objSource->arrCombatResult->attackers);
			$idFleet = array(
				'202' => 'Small Cargo',
				'203' => 'Large Cargo',
				'204' => 'Light Fighter',
				'205' => 'Heavy Fighter',
				'206' => 'Cruiser',
				'207' => 'Battleship',
				'208' => 'Colony Ship',
				'209' => 'Recycler',
				'210' => 'Espionage Probe',
				'211' => 'Bomber',
				'212' => 'Solar Satellite',
				'213' => 'Destroyer',
				'214' => 'Deathstar',
				'215' => 'Battlecruiser',
				'401' => 'Rocket Launcher',
				'402' => 'Light Laser',
				'403' => 'Heavy Laser',
				'404' => 'Gauss Cannon',
				'405' => 'Ion Cannon',
				'406' => 'Plasma Turret',
				'407' => 'Small Shield Dome',
				'408' => 'Large Shield Dome',
				'502' => 'Anti-Ballistic Missiles',
				'503' => 'Interplanetary Missiles'
			);
			$lastFleet = end($this->objSource->arrCombatResult->rounds);
			$i = 0;
			foreach ($this->objSource->arrCombatResult->attackers as $attacker) {
				$arrAllianceAttackers[$attacker->fleet_owner] = $attacker->fleet_owner_alliance_tag;
				foreach ($attacker->fleet_composition as $fleetComposition) {
					$key = $lastFleet->attacker_ships[$i]->ship_type;
					$arrLastFleetAttackers[$attacker->fleet_owner][$key] += $lastFleet->attacker_ships[$i]->count;
					$arrFleetAttackers[$attacker->fleet_owner][$fleetComposition->ship_type] += $fleetComposition->count;
					$i++;
				}
			}
			$i = 0;
			foreach ($this->objSource->arrCombatResult->defenders as $defenders) {
				$arrAllianceDefenders[$defenders->fleet_owner] = $defenders->fleet_owner_alliance_tag;
				foreach ($defenders->fleet_composition as $fleetComposition) {
					$key = $lastFleet->defender_ships[$i]->ship_type;
					$arrLastFleetDefenders[$defenders->fleet_owner][$key] += $lastFleet->defender_ships[$i]->count;
					$arrFleetDefenders[$defenders->fleet_owner][$fleetComposition->ship_type] += $fleetComposition->count;
					$i++;
				}
			}

			$strTitle = $this->Get("title");
			$strTitleVs = explode("vs. ", $strTitle);
			$arrCombatResult = $this->arrCache['combat_result'];

			$strBBCode = "";
			$strBBCode = "[align=center]";
			$strBBCode .= "On " . DateConstructor_6x($this->objSource->arrCombatResult->generic->event_timestamp, 2) . ", the following fleets met in battle:";
			$strBBCode .= "\n";
			$strBBCode .= "\n";
			$strBBCode .= "\n";

			foreach ($arrFleetAttackers as $playerName => $attacker) {
				if ($arrAllianceAttackers[$playerName]) $strAllianceAttackers[$playerName] = " [[i]" . $arrAllianceAttackers[$playerName] . "[/i]]";
				$strBBCode .= "[color=#ff0000][b]Attacker[/b][/color] " . $playerName . $strAllianceAttackers[$playerName];
				$strBBCode .= "\n";
				$strBBCode .= "[color=#ff0000]________________________________________________[/color]";
				$strBBCode .= "\n";
				$strBBCode .= "\n";
				foreach ($attacker as $ship_type => $count) {
					$strBBCode .= "[color=#fc850c]" . $idFleet[$ship_type] . " " . NumberToString($count) . "[/color]";
					$strBBCode .= "\n";
				}
				$strBBCode .= "[color=#ff0000]_________________________________________[/color]";
				$strBBCode .= "\n";
				$strBBCode .= "\n";
			}


			foreach ($arrFleetDefenders as $playerName => $defenders) {
				if ($arrAllianceDefenders[$playerName]) $strAllianceDefenders[$playerName] = " [[i]" . $arrAllianceDefenders[$playerName] . "[/i]]";
				$strBBCode .= "[color=#008000][b]Defender[/b][/color] " . $playerName . $strAllianceDefenders[$playerName];
				$strBBCode .= "\n";
				$strBBCode .= "[color=#008000]________________________________________________[/color]";
				$strBBCode .= "\n";
				$strBBCode .= "\n";
				foreach ($defenders as $ship_type => $count) {
					$strBBCode .= "[color=#1c84be]" . $idFleet[$ship_type] . " " . NumberToString($count) . "[/color]";
					$strBBCode .= "\n";
				}
				$strBBCode .= "[color=#008000]_________________________________________[/color]";
				$strBBCode .= "\n";
				$strBBCode .= "\n";
			}
				
			$strBBCode .= "\n";			
			$strBBCode .= "After the battle ...";
			$strBBCode .= "\n";			
			$strBBCode .= "\n";			


			foreach ($arrLastFleetAttackers as $playerName => $attacker) {
				$intAttFleetCount = 0;
				if ($arrAllianceAttackers[$playerName]) $strAllianceAttackers[$playerName] = " [[i]" . $arrAllianceAttackers[$playerName] . "[/i]]";
				$strBBCode .= "[color=#ff0000][b]Attacker[/b][/color] " . $playerName . $strAllianceAttackers[$playerName];
				$strBBCode .= "\n";
				$strBBCode .= "[color=#ff0000]________________________________________________[/color]";
				$strBBCode .= "\n";
				$strBBCode .= "\n";
				$strAttFleetBBCode = "";
				foreach ($attacker as $ship_type => $count) {
					$strAttFleetBBCode .= "[color=#fc850c]" . $idFleet[$ship_type] . " " . NumberToString($count) . " [b](" . NumberToString($count - $arrFleetAttackers[$playerName][$ship_type]) . ")[/b][/color]";
					$strAttFleetBBCode .= "\n";
					$intAttFleetCount += $count;
				}
				if ($intAttFleetCount == 0) {
					$strBBCode .= "Destroyed!";
					$strBBCode .= "\n";
				} else {
					$strBBCode .= $strAttFleetBBCode;					
				}
				$strBBCode .= "[color=#ff0000]_________________________________________[/color]";
				$strBBCode .= "\n";
				$strBBCode .= "\n";
			}


			foreach ($arrLastFleetDefenders as $playerName => $defenders) {
				$intDefFleetCount = 0;
				if ($arrAllianceDefenders[$playerName]) $strAllianceDefenders[$playerName] = " [" . $arrAllianceDefenders[$playerName] . "]";
				$strBBCode .= "Defender " . $playerName . $strAllianceDefenders[$playerName];
				$strBBCode .= "\n";
				$strBBCode .= "[color=#008000]________________________________________________[/color]";
				$strBBCode .= "\n";
				$strBBCode .= "\n";
				$strDefFleetBBCode = "";
				foreach ($defenders as $ship_type => $count) {
					$strDefFleetBBCode .= "[color=#1c84be]" . $idFleet[$ship_type] . " " . NumberToString($count) . " [b](" . NumberToString($count - $arrFleetDefenders[$playerName][$ship_type]) . ")[/b][/color]";
					$strDefFleetBBCode .= "\n";
					$intDefFleetCount += $count;
				}
				if ($intDefFleetCount == 0) {
					$strBBCode .= "Destroyed!";
					$strBBCode .= "\n";
				} else {
					$strBBCode .= $strDefFleetBBCode;					
				}
				$strBBCode .= "[color=#008000]_________________________________________[/color]";
				$strBBCode .= "\n";
			}

            if ($this->objSource->arrCombatResult->generic->winner == "attacker") {
            	$strWinner = "The attacker has won the battle!";
    			$strWinner .= "\n";
    			$strWinner .= "The attacker captured:";
    			$strWinner .= "\n";
        		$strWinner .= NumberToString($this->objSource->arrCombatResult->generic->loot_metal) . " Metal, " . NumberToString($this->objSource->arrCombatResult->generic->loot_crystal) . " Crystal and " . NumberToString($this->objSource->arrCombatResult->generic->loot_deuterium) . " Deuterium.";
    			$strWinner .= "\n";
            }
            elseif ($this->objSource->arrCombatResult->generic->winner == "defender") {
            	$strWinner = "The defender has won the battle!";
    			$strWinner .= "\n";
    			$strWinner .= "\n";
            }
            else {
            	$strWinner = "Draw!";
    			$strWinner .= "\n";
    			$strWinner .= "\n";
            }

			$strBBCode .= $strWinner;
			$strBBCode .= "\n";
			$strBBCode .= "The attacker lost a total of [color=#3183e7][b]" . NumberToString($this->objSource->arrCombatResult->generic->units_lost_attackers) . "[/b][/color] units.";
			$strBBCode .= "\n";
			$strBBCode .= "The defender lost a total of [color=#3183e7][b]" . NumberToString($this->objSource->arrCombatResult->generic->units_lost_defenders) . "[/b][/color] units.";
			$strBBCode .= "\n";
			$strBBCode .= "At these space coordinates now float [color=#3183e7][b]" . NumberToString($this->objSource->arrCombatResult->generic->debris_metal) . "[/b][/color] metal and [color=#3183e7][b]" . NumberToString($this->objSource->arrCombatResult->generic->debris_crystal) . "[/b][/color] crystal.";
			$strBBCode .= "\n";

			if ($this->objSource->arrCombatResult->generic->moon_created) {
				$strBBCode .= "The chance for a moon to be created from the debris was " . $this->objSource->arrCombatResult->generic->moon_chance . "%.";
				$strBBCode .= "\n";
			}
				//$strBBCode .= "Невероятные массы свободного металла и кристалла сближаются и образуют форму некоего спутника на орбите планеты.<br>Создана луна размером " . $this->objSource->arrCombatResult->generic->moon_size . "</center>";
			else {
				if ($this->objSource->arrCombatResult->generic->moon_chance) {
					$strBBCode .= "The chance for a moon to be created from the debris was " . $this->objSource->arrCombatResult->generic->moon_chance . "%.";
					$strBBCode .= "\n";
				}
			}

			$strBBCode .= "\n";
			$strBBCode .= "[size=16][color=#3183e7]Summary of profit/losses:[/color][/size]";
			$strBBCode .= "\n";
			$strBBCode .= "\n";
			$strBBCode .= "[size=14][color=#3183e7]Summary attackers(s)[/color][/size]";
			$strBBCode .= "\n";
			/*
			$strBBCode .= "Metal: [color=#fc850c][b]" . NumberToString($this->arrCache["profit"]["attacker"]["M"]) . "[/b][/color]";
			$strBBCode .= "\n";
			$strBBCode .= "Crystal: [color=#fc850c][b]" . NumberToString($this->arrCache["profit"]["attacker"]["C"]) . "[/b][/color]";
			$strBBCode .= "\n";
			$strBBCode .= "Deuterium: [color=#ff0000][b]" . NumberToString($this->arrCache["profit"]["attacker"]["D"]) . "[/b][/color]";
			$strBBCode .= "\n";
			*/
			$strBBCode .= "The attacker(s) made a profit of [color=#fc850c][b]" . NumberToString($this->arrCache["profit"]["attacker"]) . "[/b][/color] units.";
			$strBBCode .= "\n";
			$strBBCode .= "\n";
			$strBBCode .= "[size=14][color=#3183e7]Summary defender(s)[/color][/size]";
			$strBBCode .= "\n";
			/*
			$strBBCode .= "Metal: [color=#fc850c][b]" . NumberToString($this->arrCache["profit"]["defender"]["M"]) . "[/b][/color]";
			$strBBCode .= "\n";
			$strBBCode .= "Crystal: [color=#fc850c][b]" . NumberToString($this->arrCache["profit"]["defender"]["C"]) . "[/b][/color]";
			$strBBCode .= "\n";
			$strBBCode .= "Deuterium: [color=#ff0000][b]" . NumberToString($this->arrCache["profit"]["defender"]["D"]) . "[/b][/color]";
			$strBBCode .= "\n";
			*/
			$strBBCode .= "The defender(s) made a profit of [color=#fc850c][b]" . NumberToString($this->arrCache["profit"]["defender"]) . "[/b][/color] units.";			
			$strBBCode .= "\n";
			$strBBCode .= "[/align]";

			$this->arrCache['bbcode'] = $strBBCode;

			return $strBBCode;
		}

		private function CreateRankAndLinksDiv($arrAttacker, $arrDefender) {
			$strTable = "
				<table>
					<tr>
						<td valign='top'>
							URL:&nbsp;
						</td>
						<td>
							<input type='text' name='' size='100' value='".$this->GetUrl(LOGSERVERURL)."' style='border:1px solid #888888; color: #888888; font-family: Arial; font-size: 12px; background-color: #000000' onclick='this.select();'>
						</td>
					</tr>
					<tr>
						<td valign='top'>
							BB-URL:&nbsp;
						</td>
						<td>
							<textarea rows='2' name='' cols='120' style='font-size: 12px; font-family: Arial; color:#888888; background-color:#000000; border-style:solid; border: 1px solid #888888;' onclick='this.select();'>".$this->GetBBUrl(LOGSERVERURL)."</textarea>
						</td>
					</tr>
					<tr>
						<td valign='top'>
							TITLE:&nbsp;
						</td>
						<td>
							<input type='text' name='' size='100' value='".$this->Get('longtitle')."' style='border:1px solid #888888; color: #888888; font-family: Arial; font-size: 12px; background-color: #000000' onclick='this.select();'>
						</td>
					</tr>					
					<tr>
						<td valign='top'>
							BB-CODE:&nbsp"."\n"."&nbsp;
						</td>
						<td>
							<textarea rows='4' name='' cols='120' style='font-size: 12px; font-family: Arial; color:#888888; background-color:#000000; border-style:solid; border: 1px solid #888888;' onclick='this.select();'>".$this->CreateBBCode()."</textarea>
						</td>
					</tr>
                    <tr>
                        <td>
                        &nbsp;
                        </td>
                    </tr>
					<tr>
						<td valign='top'>
							ALT. URL:&nbsp;
						</td>
						<td>
							<input type='text' name='' size='100' value='" . ALTLOGSERVERURL . "?id=" . $this->objSource->strId . "' style='border:1px solid #888888; color: #888888; font-family: Arial; font-size: 12px; background-color: #000000' onclick='this.select();'>
						</td>
					</tr>
					<tr>
						<td valign='top'>
							ALT. BB-URL:&nbsp;
						</td>
						<td>
							<textarea rows='2' name='' cols='120' style='font-size: 12px; font-family: Arial; color:#888888; background-color:#000000; border-style:solid; border: 1px solid #888888;' onclick='this.select();'>".$this->GetBBUrl(ALTLOGSERVERURL)."</textarea>
						</td>
					</tr>
				</table>";

            $strResult = "
				<div id=\"combat_result\">
					<center>
					<table width='800' border='1' style='border-collapse: collapse' bordercolor='" . "#222222" . "'>
						<tr id='url_title' class='" . TD_BG_1 . "' style='cursor: pointer;'>
							<td align='center' height='38' colspan='2' style='padding:10'>
								<font color='" . YELLOW_COMMON . "'><b>" . "URL / BB-code" . "</b></font>
							</td>
						</tr>
						<tr class='" . TD_BG_2 . " url_title' style='display:none'>
							<td align='center' valign='top' colspan='2' style='padding:10;'>
								$strTable
							</td>
						</tr>
					</table>
                    </center>
				</div>";

			return $strResult;
		}

		private function CreatePlanetCleanUp() {
			$arrPlanetCleanUpReport = $this->ProcessPlanetCleanUpReport();
			$strResult = "";
			if ($arrPlanetCleanUpReport['SUM']) {
				$strResult .= "<div id=\"combat_result\"><center>";
				$strResult .= "<font color='" . YELLOW_COMMON . "'><b>PLANET CLEAN-UP</b></font>";
				$strResult .= "<br><br>";
				$strResult .= "<table border='1' style='border-collapse: collapse' bordercolor='" . WHITE_DARK . "'>
								<tr>
									<td style='padding:4'>&nbsp;</td>
									<td style='padding:4' align='center'><b><font color='#FF9933'>Metal</td>
									<td style='padding:4' align='center'><b><font color='#3333FF'>Crystal</td>
									<td style='padding:4' align='center'><b><font color='#00CC99'>Deuterium</td>
									<td style='padding:4' align='center'><b><font color='" . YELLOW_COMMON . "'>Summary</td>
								</tr>
								<tr>
									<td style='padding:4' align='center'><b><font color='" . GREEN_COMMON . "'>Planet clean-up</font></b></td>
									<td style='padding:4' align='right'><b>" . PrepareNumber($arrPlanetCleanUpReport['M']) . "</b></td>
									<td style='padding:4' align='right'><b>" . PrepareNumber($arrPlanetCleanUpReport['C']) . "</b></td>
									<td style='padding:4' align='right'><b>" . PrepareNumber($arrPlanetCleanUpReport['D']) . "</b></td>
									<td style='padding:4' align='right'><b>" . PrepareNumber($arrPlanetCleanUpReport['SUM']) . "</b></td>
								</tr>";
				$strResult .= "</table>";
				$strResult .= "</div>";
			}
			return $strResult;
		}

		private function CreateSplitUpDiv($blnAttackerOrDefender) {
			$arrCombatResult = $this->arrCache['combat_result'];
			$arrRecyclerReport = $this->arrCache['recycler_report'];
			$arrPlanetCleanUpReport = $this->ProcessPlanetCleanUpReport();

			$sumProfit["M"] = $arrCombatResult[0]['M'] + $arrRecyclerReport['M'] + $arrPlanetCleanUpReport['M'];
			$sumProfit["C"] = $arrCombatResult[0]['C'] + $arrRecyclerReport['C'] + $arrPlanetCleanUpReport['C'];
			$sumProfit["D"] = $arrCombatResult[0]['D'] + $arrRecyclerReport['D'] + $arrPlanetCleanUpReport['D'];

			if ($blnAttackerOrDefender) {
				$splitupTitle = 0;
				if (count($this->arrCache["LossesBaseNameAtakers"]) > 1) 
				foreach ($this->arrCache["LossesBaseNameAtakers"] as $key => $value) {
					$lossesBaseNameProc[$key]["M"] = $value["M"] * 100 / $this->arrCache["LossesBaseAtakers"]["M"];
					$lossesBaseNameProc[$key]["C"] = $value["C"] * 100 / $this->arrCache["LossesBaseAtakers"]["C"];
					$lossesBaseNameProc[$key]["D"] = $value["D"] * 100 / $this->arrCache["LossesBaseAtakers"]["D"];
					
					if ($this->arrCache["LossesBaseAtakers"]["M"] > $sumProfit["M"]) {
						$arrSplit[$key]["M"] = round($sumProfit["M"] * $lossesBaseNameProc[$key]["M"] / 100);
						$arrSplitEqually[$key]["M"] = round($sumProfit["M"] * $lossesBaseNameProc[$key]["M"] / 100);
					}
					else {
						$arrSplit[$key]["M"] = $value["M"] + round(($sumProfit["M"] - $this->arrCache["LossesBaseAtakers"]["M"]) * $this->arrCache["compFleetProcAttackers"][$key] / 100);
						$arrSplitEqually[$key]["M"] = $value["M"] + round(($sumProfit["M"] - $this->arrCache["LossesBaseAtakers"]["M"]) / count($this->arrCache["LossesBaseNameAtakers"]));
					}

					if ($this->arrCache["LossesBaseAtakers"]["C"] > $sumProfit["C"]) {
						$arrSplit[$key]["C"] = round($sumProfit["C"] * $lossesBaseNameProc[$key]["C"] / 100);
						$arrSplitEqually[$key]["C"] = round($sumProfit["C"] * $lossesBaseNameProc[$key]["C"] / 100);
					}
					else {
						$arrSplit[$key]["C"] = $value["C"] + round(($sumProfit["C"] - $this->arrCache["LossesBaseAtakers"]["C"]) * $this->arrCache["compFleetProcAttackers"][$key] / 100);
						$arrSplitEqually[$key]["C"] = $value["C"] + round(($sumProfit["C"] - $this->arrCache["LossesBaseAtakers"]["C"]) / count($this->arrCache["LossesBaseNameAtakers"]));
					}

					if ($this->arrCache["LossesBaseAtakers"]["D"] > $sumProfit["D"]) {
						$arrSplit[$key]["D"] = round($sumProfit["D"] * $lossesBaseNameProc[$key]["D"] / 100);
						$arrSplitEqually[$key]["D"] = round($sumProfit["D"] * $lossesBaseNameProc[$key]["D"] / 100);
					}
					else {
						$arrSplit[$key]["D"] = $value["D"] + round(($sumProfit["D"] - $this->arrCache["LossesBaseAtakers"]["D"]) * $this->arrCache["compFleetProcAttackers"][$key] / 100);
						$arrSplitEqually[$key]["D"] = $value["D"] + round(($sumProfit["D"] - $this->arrCache["LossesBaseAtakers"]["D"]) / count($this->arrCache["LossesBaseNameAtakers"]));
					}

					$arrSplit[$key]["SUM"] = $arrSplit[$key]["M"] + $arrSplit[$key]["C"] + $arrSplit[$key]["D"];
					$arrSplitEqually[$key]["SUM"] = $arrSplitEqually[$key]["M"] + $arrSplitEqually[$key]["C"] + $arrSplitEqually[$key]["D"];
				}
			} else {
				$splitupTitle = 1;
				if (count($this->arrCache["LossesBaseNameDefenders"]) > 1) foreach ($this->arrCache["LossesBaseNameDefenders"] as $key => $value) {
					$lossesBaseNameProc[$key]["M"] = $value["M"] * 100 / $this->arrCache["LossesBaseDefenders"]["M"];
					$lossesBaseNameProc[$key]["C"] = $value["C"] * 100 / $this->arrCache["LossesBaseDefenders"]["C"];
					$lossesBaseNameProc[$key]["D"] = $value["D"] * 100 / $this->arrCache["LossesBaseDefenders"]["D"];
					
					if ($this->arrCache["LossesBaseDefenders"]["M"] > $sumProfit["M"]) {
						$arrSplit[$key]["M"] = round($sumProfit["M"] * $lossesBaseNameProc[$key]["M"] / 100);
						$arrSplitEqually[$key]["M"] = round($sumProfit["M"] * $lossesBaseNameProc[$key]["M"] / 100);
					}
					else {
						$arrSplit[$key]["M"] = $value["M"] + round(($sumProfit["M"] - $this->arrCache["LossesBaseDefenders"]["M"]) * $this->arrCache["compFleetProcDefenders"][$key] / 100);
						$arrSplitEqually[$key]["M"] = $value["M"] + round(($sumProfit["M"] - $this->arrCache["LossesBaseDefenders"]["M"]) / count($this->arrCache["LossesBaseNameDefenders"]));
					}

					if ($this->arrCache["LossesBaseDefenders"]["C"] > $sumProfit["C"]) {
						$arrSplit[$key]["C"] = round($sumProfit["C"] * $lossesBaseNameProc[$key]["C"] / 100);
						$arrSplitEqually[$key]["C"] = round($sumProfit["C"] * $lossesBaseNameProc[$key]["C"] / 100);
					}
					else {
						$arrSplit[$key]["C"] = $value["C"] + round(($sumProfit["C"] - $this->arrCache["LossesBaseDefenders"]["C"]) * $this->arrCache["compFleetProcDefenders"][$key] / 100);
						$arrSplitEqually[$key]["C"] = $value["C"] + round(($sumProfit["C"] - $this->arrCache["LossesBaseDefenders"]["C"]) / count($this->arrCache["LossesBaseNameDefenders"]));
					}

					if ($this->arrCache["LossesBaseDefenders"]["D"] > $sumProfit["D"]) {
						$arrSplit[$key]["D"] = round($sumProfit["D"] * $lossesBaseNameProc[$key]["D"] / 100);
						$arrSplitEqually[$key]["D"] = round($sumProfit["D"] * $lossesBaseNameProc[$key]["D"] / 100);
					}
					else {
						$arrSplit[$key]["D"] = $value["D"] + round(($sumProfit["D"] - $this->arrCache["LossesBaseDefenders"]["D"]) * $this->arrCache["compFleetProcDefenders"][$key] / 100);
						$arrSplitEqually[$key]["D"] = $value["D"] + round(($sumProfit["D"] - $this->arrCache["LossesBaseDefenders"]["D"]) / count($this->arrCache["LossesBaseNameDefenders"]));
					}

					$arrSplit[$key]["SUM"] = $arrSplit[$key]["M"] + $arrSplit[$key]["C"] + $arrSplit[$key]["D"];
					$arrSplitEqually[$key]["SUM"] = $arrSplitEqually[$key]["M"] + $arrSplitEqually[$key]["C"] + $arrSplitEqually[$key]["D"];
				}
			}

			$strTable = "";
			$strBG = TD_BG_4;

			$strTable .= "<table border='1' style='border-collapse: collapse' bordercolor='" . "#222222" . "'>
							<tr>
								<td class='" . TD_BG_3 . "' style='padding:4' align='center'><b><font color='" . WHITE_DARK . "'></td>
								<td width='4' rowspan='" . (count($arrSplit) + 3) . "'></td>
								<td class='" . TD_BG_3 . "' style='padding:4' align='center' colspan='4'><b><font color='" . WHITE_DARK . "'>" . Dictionary("proportional") . "</td>
								<td width='4' rowspan='" . (count($arrSplit) + 3) . "'></td>
								<td class='" . TD_BG_3 . "' style='padding:4' align='center' colspan='4'><b><font color='" . WHITE_DARK . "'>" . Dictionary("equally") . "</td>
							</tr>
							<tr class='" . TD_BG_4 . "'>
								<td style='padding:4' align='center'><font color='" . WHITE_DARK . "'></td>
								<td style='padding:4' align='center'><font color='" . WHITE_DARK . "'><b>" . Dictionary("metal") . "</b></font></td>
								<td style='padding:4' align='center'><b><font color='" . WHITE_DARK . "'>" . Dictionary("crystal") . "</td>
								<td style='padding:4' align='center'><b><font color='" . WHITE_DARK . "'>" . Dictionary("deuterium") . "</td>
								<td style='padding:4' align='center'><b><font color='" . YELLOW_COMMON . "'>" . Dictionary("summary") . "</td>
								<td style='padding:4' align='center'><b><font color='" . WHITE_DARK . "'>" . Dictionary("metal") . "</td>
								<td style='padding:4' align='center'><b><font color='" . WHITE_DARK . "'>" . Dictionary("crystal") . "</td>
								<td style='padding:4' align='center'><b><font color='" . WHITE_DARK . "'>" . Dictionary("deuterium") . "</td>
								<td style='padding:4' align='center'><b><font color='" . YELLOW_COMMON . "'>" . Dictionary("summary") . "</td>
							</tr>
							<tr height='6'><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
			if(isset($arrSplit)) foreach ($arrSplit as $key => $value) {
			($strBG == TD_BG_3) ? ($strBG = TD_BG_4) : ($strBG = TD_BG_3);
			$strTable .= "	<tr class='" . $strBG . "'>
								<td style='padding:4' align='left'><b>" . $key . "</td>
								<td style='padding:4' align='right'><b>" . PrepareNumber($value['M']) . "</td>
								<td style='padding:4' align='right'><b>" . PrepareNumber($value['C']) . "</td>
								<td style='padding:4' align='right'><b>" . PrepareNumber($value['D']) . "</td>
								<td style='padding:4' align='right'><b>" . PrepareNumber($value['SUM']) . "</td>

								<td style='padding:4' align='right'><b>" . PrepareNumber($arrSplitEqually[$key]['M']) . "</td>
								<td style='padding:4' align='right'><b>" . PrepareNumber($arrSplitEqually[$key]['C']) . "</td>
								<td style='padding:4' align='right'><b>" . PrepareNumber($arrSplitEqually[$key]['D']) . "</td>
								<td style='padding:4' align='right'><b>" . PrepareNumber($arrSplitEqually[$key]['SUM']) . "</td>
							</tr>";
			}

			$strTable .= "</table>";

			$strResult = "
				<div id=\"combat_result\">
					<center>
					<table width='800' border='1' style='border-collapse: collapse' bordercolor='" . "#222222" . "'>
						<tr id='splitup_title_" . $splitupTitle . "' class='" . TD_BG_1 . "'>
							<td align='center' height='38' colspan='2' style='padding:10'>
								<font color='" . YELLOW_COMMON . "'><b>" . Dictionary("splitup_title") . "</b></font>
							</td>
						</tr>
						<tr class='" . TD_BG_2 . " splitup_title_" . $splitupTitle . "' style='display:none'>
							<td align='center' valign='top' colspan='2' style='padding:10;'>
								$strTable
							</td>
						</tr>
					</table>
				</div>";

			if (isset($arrSplit)) return $strResult;
		}

		private function GetRSBG($intFlag) {
			global $intCount_;
			if ($intFlag == 1)
				return TD_BG_3;
			if ($intFlag == 2)
				return TD_BG_4;
			if ($intCount_ % 18 < 9) {
				$intCount_++;
				return TD_BG_3;
			}
			else {
				$intCount_++;
				return TD_BG_4;
			}
		}

		private function CreateRSDiv($arrAttacker, $arrDefender) {
			$intProfit = array();
			$arrCombatResult = $this->arrCache['combat_result'];
			$arrRecyclerReport = $this->arrCache['recycler_report'];
			$arrIPMs = $this->arrCache['ipms'];
			$arrPlanetCleanUpReport = $this->ProcessPlanetCleanUpReport();
			$arrConsumption = $this->ProcessConsumption();

			$intRowSpan = 9;


			$strCaptured = '';
			if ($arrCombatResult[0]['SUM']) {
				$intRowSpan++;
				$strCaptured = "
					<tr>
						<td class='" . $this->GetRSBG(0) . "' style='padding:4'><font color='" . GREEN_COMMON . "'><b>" . Dictionary("captured_t") . "</td>
						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber($arrCombatResult[0]['M']) . "</td>
						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber($arrCombatResult[0]['C']) . "</td>
						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber($arrCombatResult[0]['D']) . "</td>
						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber($arrCombatResult[0]['SUM']) . "</td>
						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber(-1 * $arrCombatResult[0]['M']) . "</td>
						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber(-1 * $arrCombatResult[0]['C']) . "</td>
						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber(-1 * $arrCombatResult[0]['D']) . "</td>
						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber(-1 * $arrCombatResult[0]['SUM']) . "</td>
					</tr>";
				if (!isset($intProfit[0]['M'])) $intProfit[0]['M'] = 0;
				if (!isset($intProfit[0]['C'])) $intProfit[0]['C'] = 0;
				if (!isset($intProfit[0]['D'])) $intProfit[0]['D'] = 0;
				if (!isset($intProfit[0]['SUM'])) $intProfit[0]['SUM'] = 0;
				if (!isset($intProfit[1]['M'])) $intProfit[1]['M'] = 0;
				if (!isset($intProfit[1]['C'])) $intProfit[1]['C'] = 0;
				if (!isset($intProfit[1]['D'])) $intProfit[1]['D'] = 0;
				if (!isset($intProfit[1]['SUM'])) $intProfit[1]['SUM'] = 0;
				$intProfit[0]['M'] += $arrCombatResult[0]['M'];
				$intProfit[0]['C'] += $arrCombatResult[0]['C'];
				$intProfit[0]['D'] += $arrCombatResult[0]['D'];
				$intProfit[0]['SUM'] += $arrCombatResult[0]['SUM'];
				$intProfit[1]['M'] -= $arrCombatResult[0]['M'];
				$intProfit[1]['C'] -= $arrCombatResult[0]['C'];
				$intProfit[1]['D'] -= $arrCombatResult[0]['D'];
				$intProfit[1]['SUM'] -= $arrCombatResult[0]['SUM'];
			}

			$strRecycled = '';
			if ($arrRecyclerReport['SUM']) {
				$intRowSpan++;

                if ($this->objSource->strReportPO == "Attacker" || $this->objSource->strReportPO == "Defender") {
                    if ($this->objSource->strReportPO == "Defender") {
        				$strRecycled .= "
        					<tr>
        						<td class='" . $this->GetRSBG(0) . "' style='padding:4'><font color='" . GREEN_COMMON . "'><b>" . Dictionary("recycled_t") . "</td>
        						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>0</td>
        						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>0</td>
        						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>0</td>
        						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>0</td>
        						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber($arrRecyclerReport["M"]) . "</td>
        						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber($arrRecyclerReport["C"]) . "</td>
        						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber($arrRecyclerReport["D"]) . "</td>
        						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber($arrRecyclerReport["SUM"]) . "</td>
        					</tr>";

        				$intProfitNotPO[1]['M'] = $intProfit[1]['M'];
        				$intProfitNotPO[1]['C'] = $intProfit[1]['C'];
        				$intProfitNotPO[1]['D'] = $intProfit[1]['D'];
        				$intProfitNotPO[1]['SUM'] = $intProfit[1]['SUM'];

        				$intProfit[1]['M'] += $arrRecyclerReport['M'];
        				$intProfit[1]['C'] += $arrRecyclerReport['C'];
        				$intProfit[1]['D'] += $arrRecyclerReport['D'];
        				$intProfit[1]['SUM'] += $arrRecyclerReport['SUM'];
                    } else {
        				$strRecycled .= "
        					<tr>
        						<td class='" . $this->GetRSBG(0) . "' style='padding:4'><font color='" . GREEN_COMMON . "'><b>" . Dictionary("recycled_t") . "</td>
        						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber($arrRecyclerReport["M"]) . "</td>
        						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber($arrRecyclerReport["C"]) . "</td>
        						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber($arrRecyclerReport["D"]) . "</td>
        						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber($arrRecyclerReport["SUM"]) . "</td>
        						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>0</td>
        						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>0</td>
        						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>0</td>
        						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>0</td>
        					</tr>";

        				$intProfitNotPO[0]['M'] = $intProfit[0]['M'];
        				$intProfitNotPO[0]['C'] = $intProfit[0]['C'];
        				$intProfitNotPO[0]['D'] = $intProfit[0]['D'];
        				$intProfitNotPO[0]['SUM'] = $intProfit[0]['SUM'];

        				$intProfit[0]['M'] += $arrRecyclerReport['M'];
        				$intProfit[0]['C'] += $arrRecyclerReport['C'];
        				$intProfit[0]['D'] += $arrRecyclerReport['D'];
        				$intProfit[0]['SUM'] += $arrRecyclerReport['SUM'];
                    }
                } else {
    				$strRecycled .= "
    					<tr>
    						<td class='" . $this->GetRSBG(0) . "' style='padding:4'><font color='" . GREEN_COMMON . "'><b>" . Dictionary("recycled_t") . "</td>
    						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber($arrRecyclerReport["M"]) . "</td>
    						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber($arrRecyclerReport["C"]) . "</td>
    						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber($arrRecyclerReport["D"]) . "</td>
    						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber($arrRecyclerReport["SUM"]) . "</td>
    						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber($arrRecyclerReport["M"]) . "</td>
    						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber($arrRecyclerReport["C"]) . "</td>
    						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber($arrRecyclerReport["D"]) . "</td>
    						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber($arrRecyclerReport["SUM"]) . "</td>
    					</tr>";

    				$intProfitNotPO[0]['M'] = $intProfit[0]['M'];
    				$intProfitNotPO[0]['C'] = $intProfit[0]['C'];
    				$intProfitNotPO[0]['D'] = $intProfit[0]['D'];
    				$intProfitNotPO[0]['SUM'] = $intProfit[0]['SUM'];
    				$intProfitNotPO[1]['M'] = $intProfit[1]['M'];
    				$intProfitNotPO[1]['C'] = $intProfit[1]['C'];
    				$intProfitNotPO[1]['D'] = $intProfit[1]['D'];
    				$intProfitNotPO[1]['SUM'] = $intProfit[1]['SUM'];


    				$intProfit[0]['M'] += $arrRecyclerReport['M'];
    				$intProfit[0]['C'] += $arrRecyclerReport['C'];
    				$intProfit[0]['D'] += $arrRecyclerReport['D'];
    				$intProfit[0]['SUM'] += $arrRecyclerReport['SUM'];
    				$intProfit[1]['M'] += $arrRecyclerReport['M'];
    				$intProfit[1]['C'] += $arrRecyclerReport['C'];
    				$intProfit[1]['D'] += $arrRecyclerReport['D'];
    				$intProfit[1]['SUM'] += $arrRecyclerReport['SUM'];
                }
			}

			$strCleaned = '';
			if ($arrPlanetCleanUpReport['SUM']) {
				$intRowSpan++;
				$strCleaned .= "
					<tr>
						<td class='" . $this->GetRSBG(0) . "' style='padding:4'><font color='" . GREEN_COMMON . "'><b>" . Dictionary("cleanup_1_t") . "</td>
						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber($arrPlanetCleanUpReport['M']) . "</td>
						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber($arrPlanetCleanUpReport['C']) . "</td>
						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber($arrPlanetCleanUpReport['D']) . "</td>
						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber($arrPlanetCleanUpReport['SUM']) . "</td>
						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber(-$arrPlanetCleanUpReport['M']) . "</td>
						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber(-$arrPlanetCleanUpReport['C']) . "</td>
						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber(-$arrPlanetCleanUpReport['D']) . "</td>
						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber(-$arrPlanetCleanUpReport['SUM']) . "</td>
					</tr>";

				$intProfit[0]['M'] += $arrPlanetCleanUpReport['M'];
				$intProfit[0]['C'] += $arrPlanetCleanUpReport['C'];
				$intProfit[0]['D'] += $arrPlanetCleanUpReport['D'];
				$intProfit[0]['SUM'] += $arrPlanetCleanUpReport['SUM'];
				$intProfit[1]['M'] -= $arrPlanetCleanUpReport['M'];
				$intProfit[1]['C'] -= $arrPlanetCleanUpReport['C'];
				$intProfit[1]['D'] -= $arrPlanetCleanUpReport['D'];
				$intProfit[1]['SUM'] -= $arrPlanetCleanUpReport['SUM'];
			}

			$strIPMs = '';
			if ($arrIPMs['SUM']) {
				$intRowSpan++;
				$strIPMs .= "
					<tr>
						<td class='" . $this->GetRSBG(0) . "' style='padding:4'><font color='" . RED_COMMON . "'><b>" . Dictionary("ipms_1_t") . "</td>
						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber(-$arrIPMs['M']) . "</td>
						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber(-$arrIPMs['C']) . "</td>
						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber(-$arrIPMs['D']) . "</td>
						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber(-$arrIPMs['SUM']) . "</td>
						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber(0) . "</td>
						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber(0) . "</td>
						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber(0) . "</td>
						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber(0) . "</td>
					</tr>";

				$intProfit[0]['M'] -= $arrIPMs['M'];
				$intProfit[0]['C'] -= $arrIPMs['C'];
				$intProfit[0]['D'] -= $arrIPMs['D'];
				$intProfit[0]['SUM'] -= $arrIPMs['SUM'];
			}

			$strConsumption = '';
			if ($arrConsumption['attacker']['SUM'] || $arrConsumption['defender']['SUM']) {
				$intRowSpan++;
				$strConsumption .= "
					<tr>
						<td class='" . $this->GetRSBG(0) . "' style='padding:4'><font color='" . RED_COMMON . "'><b>" . Dictionary("deut_consumption") . " (" . ($this->objSource->blnPFuel * 100) . "%)</td>
						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber(0) . "</td>
						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber(0) . "</td>
						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber(-$arrConsumption['attacker']['SUM']) . "</td>
						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber(-$arrConsumption['attacker']['SUM']) . "</td>
						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber(0) . "</td>
						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber(0) . "</td>
						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber(-$arrConsumption['defender']['SUM']) . "</td>
						<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b>" . PrepareNumber(-$arrConsumption['defender']['SUM']) . "</td>
					</tr>";

				$intProfit[0]['D'] -= $arrConsumption['attacker']['SUM'];
				$intProfit[0]['SUM'] -= $arrConsumption['attacker']['SUM'];

				$intProfit[1]['D'] -= $arrConsumption['defender']['SUM'];
				$intProfit[1]['SUM'] -= $arrConsumption['defender']['SUM'];
			}

			$strTable = "";
			$strTable .= "<table border='1' style='border-collapse: collapse' bordercolor='" . "#222222" . "'>
				<tr>
					<td class='" . $this->GetRSBG(1) . "' style='padding:4'>&nbsp;</td>
					<td rowspan='" . $intRowSpan . "' width='4'></td>
					<td class='" . $this->GetRSBG(1) . "' colspan='4' align='center'><b><font color='" . WHITE_DARK . "'>" . Dictionary("attacker") . "</td>
					<td rowspan='" . $intRowSpan . "' width='4'></td>
					<td class='" . $this->GetRSBG(1) . "' colspan='4' align='center'><b><font color='" . WHITE_DARK . "'>" . Dictionary("defender") . "</td>
				</tr>
				<tr>
					<td class='" . $this->GetRSBG(2) . "' style='padding:4'>&nbsp;</td>
					<td class='" . $this->GetRSBG(2) . "' style='padding:4' align='center'><div name='img_'><img src='" . IMG_META . "'></div><div name='_img'><b><font color='" . WHITE_DARK . "'>" . Dictionary("metal") . "</b></font></div></td>
					<td class='" . $this->GetRSBG(2) . "' style='padding:4' align='center'><div name='img_'><img src='" . IMG_CRYS . "'></div><div name='_img'><b><font color='" . WHITE_DARK . "'>" . Dictionary("crystal") . "</b></font></div></td>
					<td class='" . $this->GetRSBG(2) . "' style='padding:4' align='center'><div name='img_'><img src='" . IMG_DEUT . "'></div><div name='_img'><b><font color='" . WHITE_DARK . "'>" . Dictionary("deuterium") . "</b></font></div></td>
					<td class='" . $this->GetRSBG(2) . "' style='padding:4' align='center'><b><font color='" . YELLOW_COMMON . "'>" . Dictionary("summary") . "</b></font></td>
					<td class='" . $this->GetRSBG(2) . "' style='padding:4' align='center'><div name='img_'><img src='" . IMG_META . "'></div><div name='_img'><b><font color='" . WHITE_DARK . "'>" . Dictionary("metal") . "</b></font></div></td>
					<td class='" . $this->GetRSBG(2) . "' style='padding:4' align='center'><div name='img_'><img src='" . IMG_CRYS . "'></div><div name='_img'><b><font color='" . WHITE_DARK . "'>" . Dictionary("crystal") . "</b></font></div></td>
					<td class='" . $this->GetRSBG(2) . "' style='padding:4' align='center'><div name='img_'><img src='" . IMG_DEUT . "'></div><div name='_img'><b><font color='" . WHITE_DARK . "'>" . Dictionary("deuterium") . "</b></font></div></td>
					<td class='" . $this->GetRSBG(2) . "' style='padding:4' align='center'><b><font color='" . YELLOW_COMMON . "'>" . Dictionary("summary") . "</td>
				</tr>
				<tr height='6'><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
				<tr>
					<td class='" . $this->GetRSBG(1) . "' style='padding:4'><font color='" . WHITE_DARK . "'><b id='ls'>" . Dictionary("fleet_t") . "</td>
					<td class='" . $this->GetRSBG(1) . "' style='padding:4' align='right'><b><font color='" . WHITE_DARK . "'>" . NumberToString($arrAttacker['resources_begin']['M']) . "</td>
					<td class='" . $this->GetRSBG(1) . "' style='padding:4' align='right'><b><font color='" . WHITE_DARK . "'>" . NumberToString($arrAttacker['resources_begin']['C']) . "</td>
					<td class='" . $this->GetRSBG(1) . "' style='padding:4' align='right'><b><font color='" . WHITE_DARK . "'>" . NumberToString($arrAttacker['resources_begin']['D']) . "</td>
					<td class='" . $this->GetRSBG(1) . "' style='padding:4' align='right'><b><font color='" . WHITE_DARK . "'>" . NumberToString($arrAttacker['resources_begin']['SUM']) . "</td>
					<td class='" . $this->GetRSBG(1) . "' style='padding:4' align='right'><b><font color='" . WHITE_DARK . "'>" . NumberToString($arrDefender['resources_begin']['M']) . "</td>
					<td class='" . $this->GetRSBG(1) . "' style='padding:4' align='right'><b><font color='" . WHITE_DARK . "'>" . NumberToString($arrDefender['resources_begin']['C']) . "</td>
					<td class='" . $this->GetRSBG(1) . "' style='padding:4' align='right'><b><font color='" . WHITE_DARK . "'>" . NumberToString($arrDefender['resources_begin']['D']) . "</td>
					<td class='" . $this->GetRSBG(1) . "' style='padding:4' align='right'><b><font color='" . WHITE_DARK . "'>" . NumberToString($arrDefender['resources_begin']['SUM']) . "</td>
				</tr>
				<tr>
					<td class='" . $this->GetRSBG(2) . "' style='padding:4'><font color='" . WHITE_DARK . "'><b>" . Dictionary("wreckage_t") . "</td>
					<td class='" . $this->GetRSBG(2) . "' style='padding:4' align='right'><b><font color='" . WHITE_DARK . "'>" . NumberToString($arrCombatResult[1]['M']) . "</font></td>
					<td class='" . $this->GetRSBG(2) . "' style='padding:4' align='right'><b><font color='" . WHITE_DARK . "'>" . NumberToString($arrCombatResult[1]['C']) . "</font></td>
					<td class='" . $this->GetRSBG(2) . "' style='padding:4' align='right'><b><font color='" . WHITE_DARK . "'>" . NumberToString($arrCombatResult[1]['D']) . "</font></td>
					<td class='" . $this->GetRSBG(2) . "' style='padding:4' align='right'><b><font color='" . WHITE_DARK . "'>" . NumberToString($arrCombatResult[1]['SUM']) . "</font></td>
					<td class='" . $this->GetRSBG(2) . "' style='padding:4' align='right'><b><font color='" . WHITE_DARK . "'>" . NumberToString($arrCombatResult[1]['M']) . "</font></td>
					<td class='" . $this->GetRSBG(2) . "' style='padding:4' align='right'><b><font color='" . WHITE_DARK . "'>" . NumberToString($arrCombatResult[1]['C']) . "</font></td>
					<td class='" . $this->GetRSBG(2) . "' style='padding:4' align='right'><b><font color='" . WHITE_DARK . "'>" . NumberToString($arrCombatResult[1]['D']) . "</font></td>
					<td class='" . $this->GetRSBG(2) . "' style='padding:4' align='right'><b><font color='" . WHITE_DARK . "'>" . NumberToString($arrCombatResult[1]['SUM']) . "</font></td>
				</tr>
				<tr height='6'><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
				<tr>
					<td class='" . $this->GetRSBG(1) . "' style='padding:4'><font color='" . RED_COMMON . "'><b>" . Dictionary("losses_t") . "</td>
					<td class='" . $this->GetRSBG(1) . "' style='padding:4' align='right'>" . PrepareNumber(-$this->arrCache["LossesBaseAtakers"]["M"]) . "</td>
					<td class='" . $this->GetRSBG(1) . "' style='padding:4' align='right'>" . PrepareNumber(-$this->arrCache["LossesBaseAtakers"]["C"]) . "</td>
					<td class='" . $this->GetRSBG(1) . "' style='padding:4' align='right'>" . PrepareNumber(-$this->arrCache["LossesBaseAtakers"]["D"]) . "</td>
					<td class='" . $this->GetRSBG(1) . "' style='padding:4' align='right'><b>" . PrepareNumber(-$this->objSource->arrCombatResult->generic->units_lost_attackers) . "</td>
					<td class='" . $this->GetRSBG(1) . "' style='padding:4' align='right'>" . PrepareNumber(-$this->arrCache["LossesBaseDefenders"]["M"]) . "</td>
					<td class='" . $this->GetRSBG(1) . "' style='padding:4' align='right'>" . PrepareNumber(-$this->arrCache["LossesBaseDefenders"]["C"]) . "</td>
					<td class='" . $this->GetRSBG(1) . "' style='padding:4' align='right'>" . PrepareNumber(-$this->arrCache["LossesBaseDefenders"]["D"]) . "</td>
					<td class='" . $this->GetRSBG(1) . "' style='padding:4' align='right'><b>" . PrepareNumber(-$this->objSource->arrCombatResult->generic->units_lost_defenders) . "</td>
				</tr>
				$strCaptured
				$strRecycled
				$strCleaned
				$strIPMs
				$strConsumption
                <tr>
					<td class='" . $this->GetRSBG(0) . "' style='padding:4'><font color='" . GREEN_COMMON . "'><b><u>" . Dictionary("profit_1_t") . "</td>
					<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b><u>" . PrepareNumber($intProfit[0]['M'] - $this->arrCache["LossesBaseAtakers"]["M"]) . "</td>
					<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b><u>" . PrepareNumber($intProfit[0]['C'] - $this->arrCache["LossesBaseAtakers"]["C"]) . "</td>
					<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b><u>" . PrepareNumber($intProfit[0]['D'] - $this->arrCache["LossesBaseAtakers"]["D"]) . "</td>
					<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b><u>" . PrepareNumber($this->arrCache["profit"]["attacker"]) . "</td>
					<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b><u>" . PrepareNumber($intProfit[1]['M'] - $this->arrCache["LossesBaseDefenders"]["M"]) . "</td>
					<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b><u>" . PrepareNumber($intProfit[1]['C'] - $this->arrCache["LossesBaseDefenders"]["C"]) . "</td>
					<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b><u>" . PrepareNumber($intProfit[1]['D'] - $this->arrCache["LossesBaseDefenders"]["D"]) . "</td>
					<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b><u>" . PrepareNumber($this->arrCache["profit"]["defender"]) . "</td>
				</tr>";

			$strTable .= "</table>";

			$strResult = "
				<div id=\"combat_result\">
					<center>
					<table width='800' border='1' style='border-collapse: collapse' bordercolor='" . "#222222" . "'>
						<tr id='rs_title' class='" . TD_BG_1 . "' style='cursor: pointer;'>
							<td align='center' height='38' colspan='2' style='padding:10'>
								<font color='" . YELLOW_COMMON . "'><b>" . Dictionary("rs_title") . "</b></font>
							</td>
						</tr>
						<tr class='" . TD_BG_2 . " rs_title' style='display:none'>
							<td align='center' valign='top' colspan='2' style='padding:10;'>
								$strTable
							</td>
						</tr>
					</table>
				</div>";

			return $strResult;
		}

		private function CreateGainsDiv($arrAttacker, $arrDefender) {
			$strResult = "";
			$arrCombatResult = $this->arrCache['combat_result'];
			$strResult .= "<font color='" . YELLOW_COMMON . "'><b>" . Dictionary("profit_title") . "</b></font>";
			$strResult .= "<br>";
			$strResult .= "<table>";
			$strResult .= "	<tr>";
			$strResult .= "		<td>";
			$intTotalGains = -1 * ($arrAttacker['resources_begin']['SUM'] - $arrAttacker['resources_end']['SUM']) + $arrCombatResult[0]['SUM'] + $arrCombatResult[1]['SUM'];
            $intTotalGains['Attacker'] = $intTotalGains;
			if ($intTotalGains > 0) $strColor = "" . WHITE_DARK . ""; else $strColor = "" . WHITE_DARK . "";
			$strResult .= "			<font color='" . WHITE_DARK . "'><b>" . Dictionary("att_gains_1") . ": </font><font color='$strColor'>" . PrepareNumber($intTotalGains) . "</b></font>";
			//$strResult .= "			<br><font color='" . RED_DARK . "'>Attacker gains </font><font color='#FF9933'>[metal]: " . NumberToString($arrCombatResult[0]['M'] + $arrCombatResult[1]['M'] - ($arrAttacker['resources_begin']['M'] - $arrAttacker['resources_end']['M'])) . "</font>";
			//$strResult .= "			<br><font color='" . RED_DARK . "'>Attacker gains </font><font color='#3333FF'>[crystal]: " . NumberToString($arrCombatResult[0]['C'] + $arrCombatResult[1]['C'] - ($arrAttacker['resources_begin']['C'] - $arrAttacker['resources_end']['C'])) . "</font>";
			//$strResult .= "			<br><font color='" . RED_DARK . "'>Attacker gains </font><font color='#00CC99'>[deiterium]: " . NumberToString($arrCombatResult[0]['D'] + $arrCombatResult[1]['D'] - ($arrAttacker['resources_begin']['D'] - $arrAttacker['resources_end']['D'])) . "</font>";
			$strResult .= "		</td>";
			$strResult .= "		<td>&nbsp;&nbsp;</td>";
			$strResult .= "		<td>";
			$intTotalGains = -1 * ($arrDefender['resources_begin']['SUM'] - $arrDefender['resources_end']['SUM']) - $arrCombatResult[0]['SUM'] + $arrCombatResult[1]['SUM'];
			$intTotalGains['Defender'] = $intTotalGains;
            if ($intTotalGains > 0) $strColor = "" . WHITE_DARK . ""; else $strColor = "" . WHITE_DARK . "";
			$strResult .= "			<font color='" . WHITE_DARK . "'><b>" . Dictionary("def_gains_1") . ": </font><font color='$strColor'>" . PrepareNumber($intTotalGains) . "</b></font>";
			//$strResult .= "			<br><font color='" . GREEN_DARK . "'>Defender gains </font><font color='#FF9933'>[metal]: " . NumberToString($arrCombatResult[1]['M'] - ($arrDefender['resources_begin']['M'] - $arrDefender['resources_end']['M'])) . "</font>";
			//$strResult .= "			<br><font color='" . GREEN_DARK . "'>Defender gains </font><font color='#3333FF'>[crystal]: " . NumberToString($arrCombatResult[1]['C'] - ($arrDefender['resources_begin']['C'] - $arrDefender['resources_end']['C']) ) . "</font>";
			//$strResult .= "			<br><font color='" . GREEN_DARK . "'>Defender gains </font><font color='#00CC99'>[deiterium]: " . NumberToString($arrCombatResult[1]['D'] - ($arrDefender['resources_begin']['D'] - $arrDefender['resources_end']['D']) ) . "</font>";
			$strResult .= "		</td>";
			$strResult .= "	</tr>";
			//$strResult .= "</table>";

			$strResult .= "</br>";

			if ($this->objSource->strRecyclerReport) {
				$arrRecyclerResult = $this->arrCache['recycler_report'];
				if ($arrRecyclerResult) {
					//$strResult .= "<table>";
					$strResult .= "	<tr>";
					$strResult .= "		<td>";
					$intTotalGains = -1 * ($arrAttacker['resources_begin']['SUM'] - $arrAttacker['resources_end']['SUM']) + $arrCombatResult[0]['SUM'] + $arrRecyclerResult['SUM'];
					if ($intTotalGains > 0) $strColor = "" . WHITE_DARK . ""; else $strColor = "" . WHITE_DARK . "";
					$strResult .= "			<font color='" . WHITE_DARK . "'><b>" . Dictionary("att_gains_2") . ": </font><font color='$strColor'>" . PrepareNumber($intTotalGains) . "</b></font>";
					//$strResult .= "			<br><font color='" . RED_DARK . "'>Attacker gains </font><font color='#FF9933'>[metal]: " . NumberToString($arrCombatResult[0]['M'] + $arrRecyclerResult['M'] - ($arrAttacker['resources_begin']['M'] - $arrAttacker['resources_end']['M'])) . "</font>";
					//$strResult .= "			<br><font color='" . RED_DARK . "'>Attacker gains </font><font color='#3333FF'>[crystal]: " . NumberToString($arrCombatResult[0]['C'] + $arrRecyclerResult['C'] - ($arrAttacker['resources_begin']['C'] - $arrAttacker['resources_end']['C'])) . "</font>";
					//$strResult .= "			<br><font color='" . RED_DARK . "'>Attacker gains </font><font color='#00CC99'>[deiterium]: " . NumberToString($arrCombatResult[0]['D'] + $arrRecyclerResult['D'] - ($arrAttacker['resources_begin']['D'] - $arrAttacker['resources_end']['D'])) . "</font>";
					$strResult .= "		</td>";
					$strResult .= "		<td>&nbsp;&nbsp;</td>";
					$strResult .= "		<td>";
					$intTotalGains = -1 * ($arrDefender['resources_begin']['SUM'] - $arrDefender['resources_end']['SUM']) - $arrCombatResult[0]['SUM'] + $arrRecyclerResult['SUM'];
					if ($intTotalGains > 0) $strColor = "" . WHITE_DARK . ""; else $strColor = "" . WHITE_DARK . "";
					$strResult .= "			<font color='" . WHITE_DARK . "'><b>" . Dictionary("def_gains_2") . ": </font><font color='$strColor'>" . PrepareNumber($intTotalGains) . "</b></font>";
					//$strResult .= "			<br><font color='" . GREEN_DARK . "'>Defender gains </font><font color='#FF9933'>[metal]: " . NumberToString($arrRecyclerResult['M'] - ($arrDefender['resources_begin']['M'] - $arrDefender['resources_end']['M'])) . "</font>";
					//$strResult .= "			<br><font color='" . GREEN_DARK . "'>Defender gains </font><font color='#3333FF'>[crystal]: " . NumberToString($arrRecyclerResult['C'] - ($arrDefender['resources_begin']['C'] - $arrDefender['resources_end']['C']) ) . "</font>";
					//$strResult .= "			<br><font color='" . GREEN_DARK . "'>Defender gains </font><font color='#00CC99'>[deiterium]: " . NumberToString($arrRecyclerResult['D'] - ($arrDefender['resources_begin']['D'] - $arrDefender['resources_end']['D']) ) . "</font>";
					$strResult .= "		</td>";
					$strResult .= "	</tr>";
					//$strResult .= "</table>";
				}
			}

			$strResult .= "</table>";

			return $strResult;
		}


		private function GetSelfLink() {
			$strHTML = "";
			$strHTML .= "<div class='combat_round'>";
			$strHTML .= "<div class='round_info'>";
			$strHTML .= "<p class='start'><a href='" . LOGSERVERURL . "' target='_blank' style='text-decoration: none'><img src=" . FAVICON_PNG . " border='0'><br><font color='" . GREEN_COMMON . "'>LOGSERVER</font></a></p>";
			$strHTML .= "</div>";
			$strHTML .= "</div>";
			$strHTML .= "<script>";
			return $strHTML;
		}


		private function ProcessCombatResult() {
		
			$arrReturn = array();
				$arrReturn[0]['M'] = (float) $this->objSource->arrCombatResult->generic->loot_metal;
				$arrReturn[0]['C'] = (float) $this->objSource->arrCombatResult->generic->loot_crystal;
				$arrReturn[0]['D'] = (float) $this->objSource->arrCombatResult->generic->loot_deuterium;
				$arrReturn[0]['SUM'] = $arrReturn[0]['M'] + $arrReturn[0]['C'] + $arrReturn[0]['D'];

				$arrReturn[1]['M'] = (float) $this->objSource->arrCombatResult->generic->debris_metal;
				$arrReturn[1]['C'] = (float) $this->objSource->arrCombatResult->generic->debris_crystal;
				$arrReturn[1]['D'] = 0;
				$arrReturn[1]['SUM'] = $arrReturn[1]['M'] + $arrReturn[1]['C'] + $arrReturn[1]['D'];

			return $arrReturn;
		}

		private function ProcessRecyclerReport() {
			$arrReturn = array();
				$arrReturn['M'] = 0;
				$arrReturn['C'] = 0;
				$arrReturn['D'] = 0;
				$arrReturn['SUM'] = 0;
				$arrReturn['ERR'] = 1;
			$strRecyclerReport = $this->objSource->strRecyclerReport;
			if ($strRecyclerReport) {
				if ($strRecyclerReport == '*') {
					$arrReturn = $this->arrCache['combat_result'][1];
					$arrReturn['ERR'] = 0;
					return $arrReturn;

				}
				//$strRecyclerReport = str_replace(",", "", str_replace(".", "", $strRecyclerReport));
				$strPattern = "/[\D ]+? \d+((\.|,)\d{3})* [\D ]+? \d+((\.|,)\d{3})*\. [\D ]+? \d+((\.|,)\d{3})* [\D ]+? \d+((\.|,)\d{3})* [\D ]+?\. [\D ]+? \d+((\.|,)\d{3})* [\D ]+? \d+((\.|,)\d{3})*/";
				//$strPattern = "/(.+?[0-9]+){6}/";
				if (preg_match_all($strPattern, $strRecyclerReport, $arrMatches)) {
					$arrReturn = array();
					$arrReturn['M'] = 0;
					$arrReturn['C'] = 0;
					$arrReturn['D'] = 0;
					$arrReturn['SUM'] = 0;
					foreach ($arrMatches[0] as $strRecyclerReport) {
						$strRecyclerReport = str_replace(",", "", str_replace(".", "", $strRecyclerReport));
						$strPattern = "/[0-9]+/";
						if(preg_match_all($strPattern, $strRecyclerReport, $arrMatches_)) {
							$arrReturn['M'] += $arrMatches_[0][4];
							$arrReturn['C'] += $arrMatches_[0][5];
							$arrReturn['D'] += 0;
						}
					}
					$arrReturn['SUM'] = $arrReturn['M'] + $arrReturn['C'] + $arrReturn['D'];
					$arrReturn['ERR'] = 0;
				}
				else {
					$strPattern = "/[\D ]+? \d+((\.|,)\d{3})* [\D ]+? \d+((\.|,)\d{3})* [\D ]+?\. [\D ]+? \d+((\.|,)\d{3})* [\D ]+? \d+((\.|,)\d{3})* [\D ]+?\./";
					if (preg_match_all($strPattern, $strRecyclerReport, $arrMatches)) {
						$arrReturn = array();
						$arrReturn['M'] = 0;
						$arrReturn['C'] = 0;
						$arrReturn['D'] = 0;
						$arrReturn['SUM'] = 0;
						foreach ($arrMatches[0] as $strRecyclerReport) {
							$strRecyclerReport = str_replace(",", "", str_replace(".", "", $strRecyclerReport));
							$strPattern = "/[0-9]+/";
							if(preg_match_all($strPattern, $strRecyclerReport, $arrMatches_)) {
								$arrReturn['M'] += $arrMatches_[0][2];
								$arrReturn['C'] += $arrMatches_[0][3];
								$arrReturn['D'] += 0;
							}
						}
						$arrReturn['SUM'] = $arrReturn['M'] + $arrReturn['C'] + $arrReturn['D'];
						$arrReturn['ERR'] = 0;
					}
				}
			}
			return $arrReturn;
		}

		private function ProcessIPMs() {
			$arrReturn = array();
				$arrReturn['M'] = 0;
				$arrReturn['C'] = 0;
				$arrReturn['D'] = 0;
				$arrReturn['SUM'] = 0;
			$intIPMs = $this->objSource->intIPMs;
			if ($intIPMs != 0) {
				$arrCoast = GetBaseCost(INTERPLANETARYMISSILE);
				$arrReturn['M'] = $intIPMs * $arrCoast['M'];
				$arrReturn['C'] = $intIPMs * $arrCoast['C'];
				$arrReturn['D'] = $intIPMs * $arrCoast['D'];
				$arrReturn['SUM'] = $arrReturn['M'] + $arrReturn['C'] + $arrReturn['D'];
			}
			return $arrReturn;
		}

		private function ProcessPlanetCleanUpReport() {
			$arrReturn = array('M' => 0, 'C' => 0, 'D' => 0, 'SUM' => 0);

			$strPlanetCleanUpReport = $this->objSource->strCleanUp;
			if ($strPlanetCleanUpReport) {
				$strPlanetCleanUpReport = str_replace(",", "", str_replace(".", "", $strPlanetCleanUpReport));
				$strPattern = "/(:\s*[0-9]+[^0-9:]+[0-9]+[^0-9:]+[0-9]+\s)|(![^0-9]+[0-9]+[^0-9]+[0-9]+[^0-9]+[0-9]+\s)/";
				if (preg_match_all($strPattern, $strPlanetCleanUpReport, $arrMatches)) {
					$arrReturn = array();
					$arrReturn['M'] = 0;
					$arrReturn['C'] = 0;
					$arrReturn['D'] = 0;
					$arrReturn['SUM'] = 0;
					foreach ($arrMatches[0] as $strPlanetCleanUpReport) {
						$strPattern = "/[0-9]+(?=\s)/";
						if(preg_match_all($strPattern, $strPlanetCleanUpReport, $arrMatches_)) {
							$arrReturn['M'] += $arrMatches_[0][0];
							$arrReturn['C'] += $arrMatches_[0][1];
							$arrReturn['D'] += $arrMatches_[0][2];
						}
					}
					$arrReturn['SUM'] = $arrReturn['M'] + $arrReturn['C'] + $arrReturn['D'];
				}

			}

			return $arrReturn;
		}

		private function HTMLBugFix() {
			$strHTML = $this->strHTMLLogNew;
			$strHTML = str_replace("::", ":", $strHTML);
			$strHTML = str_replace(".:", ":", $strHTML);
			$strHTML = str_replace(" .", ".", $strHTML);
			$this->strHTMLLogNew = $strHTML;
		}

		private function ReplaceTitle() {
			$strHTML = $this->strHTMLLogNew;
			$strHTML = str_replace("<title></title>", "<title>LogServer - " . $this->arrCache["longtitle"] . "</title>", $strHTML);
			$strHTML = str_replace("replace_title_discord", $this->Get("title") . "", $strHTML);
			$strHTML = str_replace("replace_longtitle_discord", $this->arrCache["longtitle"] . "", $strHTML);
			$this->strHTMLLogNew = $strHTML;
		}
	}
?>