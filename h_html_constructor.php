<?php
class cHTMLConstructor {
		private $objSource = null;
		private $strHTMLLogNew = "";
		private $arrCache = array();

		function __construct(&$objSource) {
			$this->objSource = $objSource;
		}

		public function Get($strWhat) {
			$varReturn = false;
			switch (strtolower($strWhat)) {
				case "uni":			$varReturn = $this->objParser->Get("uni"); break;
				case "domain":		$this->objParser->Get("domain"); break;
				case "html":		$varReturn = $this->strHTMLLogNew; break;
				case "longtitle":	$varReturn = $this->arrCache["longtitle"]; break;
				case "title":		$varReturn = $this->objSource->strTitle;  break;
				case "losses":		$varReturn =  str_replace(".", "", $this->arrCache["SumLossesMC"]); break;
				case "bburl":		$varReturn = $this->arrCache["bburl"]; break;
				case "bbcode":		$varReturn = $this->arrCache["bbcode"]; break;
				default:			LogError("objHTMLConstructor->Get", "Unknown input parameter: " . $strWhat); break;
			}
			return $varReturn;
		}

		public function Construct() {
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

            if ($_GET['lang_log']){
                $lang_log = KillInjection ($_GET['lang_log']);
                if ($lang_log=='en'){
                $this->strHTMLLogNew = str_replace("М. трансп.", "S.Cargo", $this->strHTMLLogNew);
                $this->strHTMLLogNew = str_replace("Б. трансп.", "L.Cargo", $this->strHTMLLogNew);
                $this->strHTMLLogNew = str_replace("Л. истр.", "L.Fighter", $this->strHTMLLogNew);
                $this->strHTMLLogNew = str_replace("Т. истр.", "H.Fighter", $this->strHTMLLogNew);
                $this->strHTMLLogNew = str_replace("Крейсер", "Cruiser", $this->strHTMLLogNew);
                $this->strHTMLLogNew = str_replace("Линк", "Battleship", $this->strHTMLLogNew);
                $this->strHTMLLogNew = str_replace("Перераб.", "Recy.", $this->strHTMLLogNew);
                $this->strHTMLLogNew = str_replace("Колониз.", "Col. Ship", $this->strHTMLLogNew);
                $this->strHTMLLogNew = str_replace("Шп. зонд", "Esp.Probe", $this->strHTMLLogNew);
                $this->strHTMLLogNew = str_replace("Бомб.", "Bomber", $this->strHTMLLogNew);
                $this->strHTMLLogNew = str_replace("С. спут.", "Sol. Sat", $this->strHTMLLogNew);
                $this->strHTMLLogNew = str_replace("Уничт.", "Dest.", $this->strHTMLLogNew);
                $this->strHTMLLogNew = str_replace("Лин. Кр.", "Battlecr.", $this->strHTMLLogNew);
                $this->strHTMLLogNew = str_replace("ЗС", "Deathstar", $this->strHTMLLogNew);
                $this->strHTMLLogNew = str_replace("РУ", "R.Launcher", $this->strHTMLLogNew);
                $this->strHTMLLogNew = str_replace("Л. лазер", "L.Laser", $this->strHTMLLogNew);
                $this->strHTMLLogNew = str_replace("Т. лазер", "H.Laser", $this->strHTMLLogNew);
                $this->strHTMLLogNew = str_replace("Ион", "Ion C.", $this->strHTMLLogNew);
                $this->strHTMLLogNew = str_replace("Гаусс", "Gauss", $this->strHTMLLogNew);
                $this->strHTMLLogNew = str_replace("Плазма", "Plasma", $this->strHTMLLogNew);
                $this->strHTMLLogNew = str_replace("М. куп.", "S.Dome", $this->strHTMLLogNew);
                $this->strHTMLLogNew = str_replace("Б. куп.", "L.Dome", $this->strHTMLLogNew);

                $this->strHTMLLogNew = str_replace("Атакующий выиграл битву! Он получает", "L.Dome", $this->strHTMLLogNew);
                }
            }

            return $this->strHTMLLogNew;
		}

		private function GetHTML() {
			echo $varReturn = $this->arrCache["longtitle"];
			$strHTML = $this->GetHeadHTML(); // FIX ME SERHIO
			// !!!
			$strHTML .= "<body id='combatreport' onload='LoadLog(), PopMessageJs();'>";

			if (key_exists('account', $_SESSION)) {
			$strLoginMsg = $_SESSION['account']['login'];
            //Add for Asiman
			$strPWMsg = "[<a href='index.php?show=changepass'><font size='1'>Change password</font></a>]";
            //
			$strLogoutMsg = "[<a href='index.php?logout=1'><font size='1'>Logout</font></a>]";
		}
		else {
			$strLoginMsg = "Login"; $strPWMsg = "[<a href='index.php?show=lostpw'><font size='1'>Forgot Password</font></a>]"; $strLogoutMsg = "";
		}

		/*$strLoginUser = "<div id = 'login_'><font color='#888888' face='Arial' size='1'>User: [<a href='index.php?show=account'><font size='1'>$strLoginMsg</font></a>] $strPWMsg $strLogoutMsg</font></div>";
		  $strLangUser = "<td width='20' height='30' align='left'>
                                        <table>
                                            <tr>
                                                <td width='20' height='14'><a href='index.php?id=".$this->objSource->strId ."&lang=bg&cache=1' onclick='document.cookie=\"lang=bg; expires=Monday, 01-Oct-2014 10:0:0 GMT\";'><img src='index_files/flag_empty.png' height='14' width='20' alt='BG' title='Bulgarian' border='0' style='background: transparent url(index_files/mmoflags.png) no-repeat; background-position:left -42px !important'></a></td>
            									<td width='4'>&nbsp;</td>
            									<td width='20' height='14'><a href='index.php?id=".$this->objSource->strId ."&lang=de&cache=1' onclick='document.cookie=\"lang=de; expires=Monday, 01-Oct-2014 10:0:0 GMT\";'><img src='index_files/flag_empty.png' height='14' width='20' alt='DE' title='German' border='0' style='background: transparent url(index_files/mmoflags.png) no-repeat; background-position:left -168px !important'></a></td>
            									<td width='4'>&nbsp;</td>
            									<td width='20' height='14'><a href='index.php?id=".$this->objSource->strId ."&lang=en&cache=1' onclick='document.cookie=\"lang=en; expires=Monday, 01-Oct-2014 10:0:0 GMT\";'><img src='index_files/flag_empty.png' height='14' width='20' alt='EN' title='English' border='0' style='background: transparent url(index_files/mmoflags.png) no-repeat; background-position:left -224px !important'></a></td>
            									<td width='4'>&nbsp;</td>
            									<td width='20' height='14'><a href='index.php?id=".$this->objSource->strId ."&lang=fr&cache=1' onclick='document.cookie=\"lang=fr; expires=Monday, 01-Oct-2014 10:0:0 GMT\";'><img src='index_files/flag_empty.png' height='14' width='20' alt='FR' title='French' border='0' style='background: transparent url(index_files/mmoflags.png) no-repeat; background-position:left -280px !important'></a></td>
            									<td width='4'>&nbsp;</td>
            									<td width='20' height='14'><a href='index.php?id=".$this->objSource->strId ."&lang=ru&cache=1' onclick='document.cookie=\"lang=ru; expires=Monday, 01-Oct-2014 10:0:0 GMT\";'><img src='index_files/flag_empty.png' height='14' width='20' alt='RU' title='Russian' border='0' style='background: transparent url(index_files/mmoflags.png) no-repeat; background-position:left -672px !important'></a></td>
            									<td width='4'>&nbsp;</td>
            									<td width='20' height='14'><a href='index.php?id=".$this->objSource->strId ."&lang=ua&cache=1' onclick='document.cookie=\"lang=ua; expires=Monday, 01-Oct-2014 10:0:0 GMT\";'><img src='index_files/flag_empty.png' height='14' width='20' alt='UA' title='Ukrainian' border='0' style='background: transparent url(index_files/mmoflags.png) no-repeat; background-position:left -770px !important'></a></td>
                                            </tr>
                                        </table>
                                    </td>";*/

			$strHTML .= "<form name='upload_form' id='upload_form' enctype='multipart/form-data' action='index.php' method='post'>
				<center>
				<div style='height:30px'><table id='stick_menu' width='100%' border='0' style='border-collapse: collapse; z-index:999;' cellpadding='10' background='".TABLE_BACKGROUND."'>
					<tr><td height='4' style='padding: 0'></td></tr>
					<tr>
						<td align='center' valign='center' background='".VISTA_PANEL."' style='padding: 0px'>
                        	<table border='0' style='border-collapse: collapse'>
								<tr>
									<td height='30'>$strLoginUser</td>
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

			$strHTML .= "
										<table border='0' bordercolor='#000000' style='border-collapse: collapse' cellpadding='0'>
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
                                    $strLangUser
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
		//$strHTML .= $strTableInner;
		$strHTML .= "	</table>";
		$strHTML .= "	</center>";
		$strHTML .= "	</form>";


				$strHTML .= "<div id='master'>";
					$strHTML .= $this->GetAllRoundsHTML();
					$strHTML .= "<div id='combat_result'>";
					$strHTML .= $this->objSource->arrCombatResult->all;
					$arrCombatResult = $this->arrCache['combat_result'];
					if ($arrCombatResult[2])
						$strHTML .= "<center><div name='img_'><img src=" . ICON_MOON . " alt='Moon'></div><div name='_img'><font color='" . RED_COMMON . "'>?</font></div></center>";
					$strHTML .= "</div><!-- combat_result -->";
				$strHTML .= "</div><!-- master -->";
			$strHTML .= "<!-- Yandex.Metrika counter --><script type=\"text/javascript\">(function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter14981155 = new Ya.Metrika({id:14981155, enableAll: true}); } catch(e) {} }); var n = d.getElementsByTagName(\"script\")[0], s = d.createElement(\"script\"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = \"text/javascript\"; s.async = true; s.src = (d.location.protocol == \"https:\" ? \"https:\" : \"http:\") + \"//mc.yandex.ru/metrika/watch.js\"; if (w.opera == \"[object Opera]\") { d.addEventListener(\"DOMContentLoaded\", f); } else { f(); } })(document, window, \"yandex_metrika_callbacks\");</script><noscript><div><img src=\"//mc.yandex.ru/watch/14981155\" style=\"position:absolute; left:-9999px;\" alt=\"\" /></div></noscript><!-- /Yandex.Metrika counter -->";
			$strHTML .= "</body>";
			$strHTML .= "</html>";

			return $strHTML;
		}

		private function GetHeadHTML() { // FIX ME SERHIO
            $strResult = "<html xmlns='http://www.w3.org/1999/xhtml'>
				<head>
					<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
                    <meta property='fb:admins' content='{dmitry.shevchenko.754}'/>
						<title></title>
						<link rel='icon' href='".FAVICON."' type='image/x-icon'>
						<link rel='shortcut icon' href='".FAVICON."' type='image/x-icon'>
						<link rel='stylesheet' type='text/css' href='".CSS_COMBAT."' media='screen' />
						<link type='text/css' rel='stylesheet' href='index_files/ratings.css'/>
                        <script language='javascript' src='".LOGJSLIBRARY."'></script>
    			        <script language='javascript' src='" . MAINJSLIBRARY . "'></script>
    			        <script language='javascript' src='" . JS_ABOX . "'></script>
    			        <script language='javascript' src='" . JS_XSS . "'></script>
    			        <script language='javascript' src='" . JQUERY . "'></script>
    			        <script src='http://userapi.com/js/api/openapi.js' type='text/javascript' charset='windows-1251'></script>
                        <script type='text/javascript'>
                            $(document).ready(function() {
                                $('.user_comments').hide();
                                $('.url_title').hide();
                                $('.log_rank').hide();
                                $('.explodeup_title').hide();
                                $('.rs_title').hide();
                                $('.compounds').hide();
                                $('.losses').hide();

                                $('#user_comments').click(function(){'none'==$('.user_comments').css('display')?$('.user_comments').show():$('.user_comments').hide()});
                                $('#url_title').click(function(){'none'==$('.url_title').css('display')?$('.url_title').show():$('.url_title').hide()});
                                $('#log_rank').click(function(){'none'==$('.log_rank').css('display')?$('.log_rank').show():$('.log_rank').hide()});
                                $('#explodeup_title').click(function(){'none'==$('.explodeup_title').css('display')?$('.explodeup_title').show():$('.explodeup_title').hide()});
                                $('#rs_title').click(function(){'none'==$('.rs_title').css('display')?$('.rs_title').show():$('.rs_title').hide()});
                                $('#compounds').click(function(){'none'==$('.compounds').css('display')?$('.compounds').show():$('.compounds').hide()});
                                $('#losses').click(function(){'none'==$('.losses').css('display')?$('.losses').show():$('.losses').hide()});
                                var start_pos=$('#stick_menu').offset().top;
                                $(window).scroll(function(){
                                    if ($(window).scrollTop()>=start_pos) {
                                        if ($('#stick_menu').hasClass()==false) $('#stick_menu').addClass('to_top');
                                    }
                                    else $('#stick_menu').removeClass('to_top');
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
                        </style>
				</head>";


			return $strResult;
		}

		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		private function GetFleetHTML($arrRoundFleet, $intRound, $blnHideTech) {
			$arrTemp = $arrRoundFleet[$intRound];

			$arrOldTemp = NULL;
			$arrOldFleet = NULL;
			$arrFleet = NULL;
			$arrHTML = "";

			if ($intRound > 0){
				$arrOldTemp = $arrRoundFleet[$intRound-1];
			}

			if ($arrTemp['type'] == 'round'){
				$arrFleet = $arrTemp['fleet'];
			}

			if ($arrOldTemp){
				if ($arrOldTemp['type'] == 'round'){
					$arrOldFleet = $arrOldTemp['fleet'];
				}
			}
			else{
				$arrOldFleet = $arrFleet;
			}

			if ($arrOldFleet){
				$arrHTML = $arrHTML."<table>";
				//-----------------------------------------
					$arrHTML = $arrHTML."<tr>";
					foreach ($arrOldFleet as $key => $value) {
						$arrHTML = $arrHTML."<th class='textGrow' nowrap='nowrap'>";
						$arrHTML = $arrHTML."<center>";

						if ($value['name'] == INTERPLANETARYMISSILE) {
							$name = SUNSAT;
						}
						else {
							$name = $value['name'];
						}

						if (GetIMG($name)){
							$arrHTML = $arrHTML."<div name='img_'><img src='".GetIMG($name)."'><br></div>";
						}
						$arrHTML = $arrHTML.AbbrShipName($value['l_name']);
						$arrHTML = $arrHTML."</center>";
						$arrHTML = $arrHTML."</th>";
					}
					$arrHTML = $arrHTML."</tr>";
				//-----------------------------------------
				//-----------------------------------------
					$arrHTML = $arrHTML."<tr>";
					foreach ($arrOldFleet as $key => $value) {
						if ($value['name'] != "th"){
							$blnArrFleetKeyExists = False;
							if ($arrFleet){
								if (key_exists($key,$arrFleet)){
									$blnArrFleetKeyExists = True;
								}
							}
							if ($blnArrFleetKeyExists){
								$intI = $arrOldFleet[$key]['count'] - $arrFleet[$key]['count'];
								if ($intI > 0){
									$strLCount = NumberToString($arrFleet[$key]['count'])." <font color='" . RED_COMMON . "'>"."-".NumberToString($intI)."</font>";
								}
								else{
									$strLCount = NumberToString($arrFleet[$key]['count']);
								}
							}
							else
							{
									$strLCount = "<font color='" . RED_COMMON . "'>"."-".NumberToString($arrOldFleet[$key]['count'])."</font>";
							}
						}
						else{
							$strLCount = $arrOldFleet[$key]['l_count']; //string!
						}

						$arrHTML = $arrHTML."<td nowrap='nowrap' >";
						$arrHTML = $arrHTML.$strLCount;
						$arrHTML = $arrHTML."</td>";
					}
					$arrHTML = $arrHTML."</tr>";
				//-----------------------------------------

				if (!$blnHideTech) {
					//-----------------------------------------
						$arrHTML = $arrHTML."<tr>";
						foreach ($arrOldFleet as $key => $value) {
							$arrHTML = $arrHTML."<td nowrap='nowrap'>";
							$arrHTML = $arrHTML.$value['l_weapons'];
							$arrHTML = $arrHTML."</td>";
						}
						$arrHTML = $arrHTML."</tr>";
					//-----------------------------------------
					//-----------------------------------------
						$arrHTML = $arrHTML."<tr>";
						foreach ($arrOldFleet as $key => $value) {
							$arrHTML = $arrHTML."<td nowrap='nowrap'>";
							$arrHTML = $arrHTML.$value['l_shields'];
							$arrHTML = $arrHTML."</td>";
						}
						$arrHTML = $arrHTML."</tr>";
					//-----------------------------------------
					//-----------------------------------------
						$arrHTML = $arrHTML."<tr>";
						foreach ($arrOldFleet as $key => $value) {
							$arrHTML = $arrHTML."<td nowrap='nowrap'>";
							$arrHTML = $arrHTML.$value['l_armors'];
							$arrHTML = $arrHTML."</td>";
						}
						$arrHTML = $arrHTML."</tr>";
					//-----------------------------------------
				}
				$arrHTML = $arrHTML."</table>";
			}

			return $arrHTML;
		}

		private function GetFleetStatisticsHTML ($arrRoundFleet, $RoundsCount) {
			$arrTemp = $arrRoundFleet[$RoundsCount-1];
			$arrOldTemp = $arrRoundFleet[0];


			$arrOldFleet = NULL;
			$arrFleet = NULL;
			$arrHTML = "";

			if ($arrTemp['type'] == 'round'){
				$arrFleet = $arrTemp['fleet'];
			}
			if ($arrOldTemp['type'] == 'round'){
				$arrOldFleet = $arrOldTemp['fleet'];
			}

			$arrHTMLTemp = NULL;

			if ($arrOldFleet){

				$arrHTML = "<table>";


				$arrHTML = $arrHTML."<tr>";
				//-----------------------------------------
				foreach ($arrOldFleet as $key => $value) {
					if ($value['name'] != "th"){
						$arrHTML = $arrHTML."<th class='textGrow' valign='top' nowrap='nowrap'>";
						$arrHTML = $arrHTML."<center>";

						if ($value['name'] == INTERPLANETARYMISSILE) {
							$name = SUNSAT;
						}
						else {
							$name = $value['name'];
						}

						if (GetIMG($name)){
							$arrHTML = $arrHTML."<div name='img_'><img src='".GetIMG($name)."'><br></div>";
						}
						$arrHTML = $arrHTML.AbbrShipName($value['l_name']);
						$arrHTML = $arrHTML."</center>";
						$arrHTML = $arrHTML."</th>";
					}
				}
				//-----------------------------------------
				$arrHTML = $arrHTML."</tr>";

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

						$arrHTMLTemp[$i]['begin'] = NumberToString($intBegin);
						$arrHTMLTemp[$i]['end'] = NumberToString($intEnd);
						$arrHTMLTemp[$i]['delta'] = NumberToString($intDelta);
						$i++;
					}
				}

				if ($arrHTMLTemp){
					$arrHTML = $arrHTML."<tr>";
					for ($i = 0; $i < count($arrHTMLTemp); $i++){
						$arrHTML = $arrHTML."<td>";
						$arrHTML = $arrHTML."<font color='" . WHITE_DARK . "'>".$arrHTMLTemp[$i]['begin']."</font>";
						$arrHTML = $arrHTML."</td>";
					}
					$arrHTML = $arrHTML."</tr>";

					$arrHTML = $arrHTML."<tr>";
					for ($i = 0; $i < count($arrHTMLTemp); $i++){
						$arrHTML = $arrHTML."<td nowrap='nowrap'>";
						if ($arrHTMLTemp[$i]['delta'] > 0){
							$arrHTML = $arrHTML."<font color='" . RED_COMMON . "'>"."-".$arrHTMLTemp[$i]['delta']."</font>";
						}
						else{
							$arrHTML = $arrHTML.$arrHTMLTemp[$i]['delta'];
						}
						$arrHTML = $arrHTML."</td>";
					}
					$arrHTML = $arrHTML."</tr>";

					$arrHTML = $arrHTML."<tr name='full_stat' style='display: none'>";
					for ($i = 0; $i < count($arrHTMLTemp); $i++){
						$arrHTML = $arrHTML."<td>";
						$arrHTML = $arrHTML."<font color='" . WHITE_DARK . "'>".$arrHTMLTemp[$i]['end']."</font>";
						$arrHTML = $arrHTML."</td>";
					}
					$arrHTML = $arrHTML."</tr>";

				}

				$arrHTML = $arrHTML."</table>";
			}
			else {
				$arrHTML = $arrHTML."<span class='destroyed textBeefy'>";
				$arrHTML = $arrHTML."<font color='" . RED_COMMON . "'>".$arrOldTemp['message']."</font>";
				$arrHTML = $arrHTML."</span>";
			}
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

//made by Zmei Проверка скрытия времени

$strTimeHide = "";

			if ($this->objSource->blnHideTime ) {
				$intpoz =  strpos($this->objSource->strStartInfo , "(") + 1;
				$strTimeHide = substr($this->objSource->strStartInfo , 0 , strpos($this->objSource->strStartInfo , " ", $intpoz) + 1);

				$strTimeHide .= "XX:XX:XX)";
				$intpoz = strpos($this->objSource->strStartInfo, ")") + 1 ;
				$strTimeHide .= substr($this->objSource->strStartInfo , $intpoz ,
				strlen($this->objSource->strStartInfo) - 1 - $intpoz);
            } else {
                $strTimeHide = $this->objSource->strStartInfo;
            }

			$strSum = $strUniDomain . " " . $strTimeHide . "<br><br>" . $strStartOpponents;
//

			$strAllRoundsHTML = 			"<div class='combat_round'>";

			$strAllRoundsHTML .= "  <table>
                                        <tr>
                                            <td width='100' align='center'>";
			$strAllRoundsHTML .= "              <div id='back'></div>";
			$strAllRoundsHTML .= "          </td>
                                            <td width='800'>
                                                <p class='start' width='800'>" . $strSum . "</p>
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
																<option value='" . str_replace("/index.php", "", LOGSERVERURL) . "/" . SKIN_LOGSERVERV20 . "'>LogServer v2</option>
																<option value='" . str_replace("/index.php", "", LOGSERVERURL) . "/" . SKIN_DEFAULT . "'>Default</option>
																<option value='" . str_replace("/index.php", "", LOGSERVERURL) . "/" . SKIN_ORIGINAL . "'>Original</option>
																<option value='" . str_replace("/index.php", "", LOGSERVERURL) . "/" . SKIN_ABSTRACT . "'>Abstract</option>
																<option value='" . str_replace("/index.php", "", LOGSERVERURL) . "/" . SKIN_ANIMEX . "'>AnimeX</option>
																<option value='" . str_replace("/index.php", "", LOGSERVERURL) . "/" . SKIN_ANIMEX2 . "'>AnimeX 2</option>
																<option value='" . str_replace("/index.php", "", LOGSERVERURL) . "/" . SKIN_CHAOS . "'>Chaos</option>
																<option value='" . str_replace("/index.php", "", LOGSERVERURL) . "/" . SKIN_DESTROYER . "'>Destroyer</option>
																<option value='" . str_replace("/index.php", "", LOGSERVERURL) . "/" . SKIN_FALLOUT . "'>Fallout</option>
																<option value='" . str_replace("/index.php", "", LOGSERVERURL) . "/" . SKIN_DEADSPACE . "'>Dead Space</option>
																<option value='" . str_replace("/index.php", "", LOGSERVERURL) . "/" . SKIN_NTRVR . "'>?ntrvr[!]</option>
																<option value='" . str_replace("/index.php", "", LOGSERVERURL) . "/" . SKIN_DISTURBED . "'>Disturbed</option>
																<option value='" . str_replace("/index.php", "", LOGSERVERURL) . "/" . SKIN_STATICX . "'>Static-X</option>
																<option value='" . str_replace("/index.php", "", LOGSERVERURL) . "/" . SKIN_SYSTEMSHOCK . "'>System shock</option>
																<option value='" . str_replace("/index.php", "", LOGSERVERURL) . "/" . SKIN_BENDER . "'>Bender</option>
																<option value='" . str_replace("/index.php", "", LOGSERVERURL) . "/" . SKIN_OLD . "'>OldAlpha</option>
															</select>
															<input type='button' id='select_img' value='Show images' style='font-size: 10px; width: 100px;' onclick='ChangeIMG(this)'>
															<input type='button' id='select_stat_type' value='Show full statistics' style='font-size: 10px; width: 100px;' onclick='ChangeStatType(this)'>
															<input type='button' id='go_speedsim' value='WebSim' style='font-size: 10px; width: 100px;'>
														</td>
														<td width='5'></td>
														<td align='center'><a href='javascript:JS_ShowRounds()' id='link_UI' style='text-decoration: none'><font color='" . LINK . "' onmouseover='this.color=\"" . LINK_ACTIVE . "\"' onmouseout='this.color=\"" . LINK . "\"'><b>&#9660 " . Dictionary("expand_all_rounds_title") . " &#9660;</b></font></a></td>
														<td width='5'></td>
														<td width='400' align='left'>";
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

			$strAllRoundsHTML .= "<div class='combat_round'>";
			$strAllRoundsHTML .= $this->GetRoundTablesHTML(0)."\n";
			$strAllRoundsHTML .= "</div>";
			for ($i = 1; $i < $this->objSource->intRoundsCount; $i++) {
				$strAllRoundsHTML .= "<div class='combat_round'>";

				$strAllRoundsHTML .= $this->GetRoundInfoHTML($i);

				$strAllRoundsHTML .= $this->GetRoundTablesHTML($i)."\n";
				$strAllRoundsHTML .= "</div>";
			}
			$strAllRoundsHTML .= "</div>";

			/*$strAllRoundsHTML .= "
				<div style='display: none; position: absolute; left:5px; top:55px; z-index: 1' id='layer1'>
					<select size='1' name='select_skin' style='font-size: 10px; width: 80px;' onchange='ChangeSkin(this.value)'>
						<option value='0' selected disabled >Change skin</option>
						<option value='" . str_replace("/index.php", "", LOGSERVERURL) . "/" . SKIN_DEFAULT . "'>Default</option>
						<option value='" . str_replace("/index.php", "", LOGSERVERURL) . "/" . SKIN_ORIGINAL . "'>Original</option>
						<option value='" . str_replace("/index.php", "", LOGSERVERURL) . "/" . SKIN_ABSTRACT . "'>Abstract</option>
						<option value='" . str_replace("/index.php", "", LOGSERVERURL) . "/" . SKIN_ANIMEX . "'>AnimeX</option>
						<option value='" . str_replace("/index.php", "", LOGSERVERURL) . "/" . SKIN_ANIMEX2 . "'>AnimeX 2</option>
						<option value='" . str_replace("/index.php", "", LOGSERVERURL) . "/" . SKIN_DEATH_NOTE . "'>Death Note</option>
						<option value='" . str_replace("/index.php", "", LOGSERVERURL) . "/" . SKIN_DESTROYER . "'>Destroyer</option>
						<option value='" . str_replace("/index.php", "", LOGSERVERURL) . "/" . SKIN_FALLOUT . "'>Fallout</option>
					</select>
					<input type='button' value='Show images' style='font-size: 10px; width: 80px;' onclick='ChangeIMG(this)'>
				</div>";*/

			//$strAllRoundsHTML .= "</center>";
			return $strAllRoundsHTML;
		}

		private function GetRoundInfoHTML($intRoundNumber) {
			$strHTML = "<div class='round_info'>";
				$strHTML .= "<div class='battle'>";
					$strHTML .= "<p class='action'>".$this->objSource->arrRoundInfo[$intRoundNumber][0]."</p>";
					$strHTML .= "<p class='action'>".$this->objSource->arrRoundInfo[$intRoundNumber][1]."</p>";
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
									for ($j = 0; $j < count($this->objSource->arrAttackers); $j++) {
										$strHTML .= "<td class='newBack' valign='top'>";
											$strHTML .= "<center>";
												$arrRoundFleet = $this->objSource->arrAttackers[$j]->arrRoundFleet;

												$strName = $this->objSource->arrAttackers[$j]->strName;
												$strCoordinate = GetCoordinatesForLog($this->objSource->arrAttackers[$j]->arrCoordinates, $this->objSource->blnHideCoord);
												$strTechnologies = GetTechnologiesForLog($this->objSource->arrAttackers[$j]->arrTechnologies, $this->objSource->blnHideTech, false);

												/*
												if ($arrRoundFleet[$intRoundNumber]['type'] != 'round') {
														$strHTML .= "<span class='name textBeefy'>";
															$strHTML .= "<font color='" . RED_COMMON . "'>".$arrRoundFleet[$intRoundNumber]['message']."</font>";
														$strHTML .= "</span>";
												}
												*/

													if ($intRoundNumber == 0) {
														$strHTML .= "<span class='weapons textBeefy'>";
														$strHTML .= "<font color='" . RED_COMMON . "'>".$strName."</font>";
														if (($strCoordinate != "") || ($strTechnologies != "")) {
															$strHTML .=  "<br><font color='" . WHITE_DARK . "'>".$strCoordinate." ".$strTechnologies."</font>";
														}
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
								for ($j = 0; $j < count($this->objSource->arrAttackers); $j++) {
									$strHTML .= "<td class='newBack'>";
									$strHTML .= 	"<center>";
									$arrRoundFleet = $this->objSource->arrAttackers[$j]->arrRoundFleet;
									$strFleetHTML = $this->GetFleetHTML($arrRoundFleet, $intRoundNumber, $this->objSource->blnHideTech);
									if ($strFleetHTML != "") {
										$strHTML .= $strFleetHTML;
									}
									else {
											$strHTML .= "<div name='_img'><b><font color='" . RED_COMMON . "'>".$arrRoundFleet[$intRoundNumber]['message']."</font></b>"."<br></div>";
											$strHTML .= "<div name='img_'><img src='" . DEFETED_ICON2 . "'></div>";
									}
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
									for ($j = 0; $j < count($this->objSource->arrDefenders); $j++) {
										$strHTML .= "<td class='newBack' valign='top'>";
											$strHTML .= "<center>";
												$arrRoundFleet = $this->objSource->arrDefenders[$j]->arrRoundFleet;

												$strName = $this->objSource->arrDefenders[$j]->strName;
												$strCoordinate = GetCoordinatesForLog($this->objSource->arrDefenders[$j]->arrCoordinates, $this->objSource->blnHideCoord);
												$strTechnologies = GetTechnologiesForLog($this->objSource->arrDefenders[$j]->arrTechnologies, $this->objSource->blnHideTech, false);

												/*
												if ($arrRoundFleet[$intRoundNumber]['type'] != 'round') {
														$strHTML .= "<span class='name textBeefy'>";
															$strHTML .= "<font color='" . RED_COMMON . "'>".$arrRoundFleet[$intRoundNumber]['message']."</font>";
														$strHTML .= "</span>";
												}
												*/

													if ($intRoundNumber == 0) {
														$strHTML .= "<span class='weapons textBeefy'>";

														$strHTML .= "<font color='" . GREEN_COMMON . "'>".$strName."</font>";
														if (($strCoordinate != "") || ($strTechnologies != "")) {
															$strHTML .=  "<br><font color='" . WHITE_DARK . "'>".$strCoordinate." ".$strTechnologies."</font>";
														}
														$strHTML .= "</span>";
													}
													else {
														$strHTML .= "<span class='name textBeefy'>";
														$strHTML .= "<font color='" . GREEN_COMMON . "'>".$this->objSource->arrDefenders[$j]->strName."</font>";
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
								for ($j = 0; $j < count($this->objSource->arrDefenders); $j++) {
									$strHTML .= "<td class='newBack'>";
									$strHTML .= 	"<center>";
									$arrRoundFleet = $this->objSource->arrDefenders[$j]->arrRoundFleet;
									$strFleetHTML = $this->GetFleetHTML($arrRoundFleet, $intRoundNumber, $this->objSource->blnHideTech);
									if ($strFleetHTML != "") {
										$strHTML .= $strFleetHTML;
									}
									else {
											$strHTML .= "<div name='_img'><b><font color='" . RED_COMMON . "'>".$arrRoundFleet[$intRoundNumber]['message']."</font></b>"."<br></div>";
											$strHTML .= "<div name='img_'><img src='" . DEFETED_ICON2 . "'><br></div>";
									}
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

			if ($this->objSource->intSkin) {
				switch (strtolower($this->objSource->intSkin)) {
					case "original":	$strCSS = SKIN_ORIGINAL; break;
					case "abstract":	$strCSS = SKIN_ABSTRACT; break;
					case "animex":	$strCSS = SKIN_ANIMEX; break;
					case "animex_2":	$strCSS = SKIN_ANIMEX2; break;
					case "chaos":	$strCSS = SKIN_CHAOS; break;
					case "destroyer":	$strCSS = SKIN_DESTROYER; break;
					case "fallout":	$strCSS = SKIN_FALLOUT; break;
					case "logserver_v20":	$strCSS = SKIN_LOGSERVERV20; break;
					case "dead_space":	$strCSS = SKIN_DEADSPACE; break;
					case "ntrvr":	$strCSS = SKIN_NTRVR; break;
					case "disturbed":	$strCSS = SKIN_DISTURBED; break;
					case "staticx":	$strCSS = SKIN_STATICX; break;
					case "system_shock":	$strCSS = SKIN_SYSTEMSHOCK; break;
					case "bender":	$strCSS = SKIN_BENDER; break;
					case "oldalpha":	$strCSS = SKIN_OLD; break;
					default: $strCSS = SKIN_DEFAULT;
				}
			}
			else {
				$strCSS = SKIN_DEFAULT;
			}

			$strCSS = "<link id='skin_css' rel='stylesheet' type='text/css' href='" . $strCSS . "' media='screen' />";
			$strHTML = str_replace("</head>", $strCSS . "</head>", $strHTML);
			$strCSS = "<link rel='stylesheet' type='text/css' href='index_files/reset.css" . "' media='screen' />";
			$strHTML = str_replace("</head>", $strCSS . "</head>", $strHTML);
			$objLog->strHTMLLog = $strHTML;
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
				for ($j = 0; $j < count($this->objSource->arrAttackers); $j++) {
					$intEndFleetStructure = GetSumFleetStructure($this->objSource->arrAttackers[$j]->arrRoundFleet[$this->objSource->intRoundsCount - 1]);
					($intEndFleetStructure > 0) ? ($strIcon = VICTORY_ICON) : ($strIcon = DEFETED_ICON);
					$strAttackerHTML = "<td class='newBack' align='center' valign='top' >";
					$strAttackerHTML .= "<div name='img_'><img src='" . $strIcon . "'></div>";
					if ($this->objSource->arrAttackers[$j]->strName) {
						$strAttackerHTML .= "<span class='name textBeefy'>";

						$strName = $this->objSource->arrAttackers[$j]->strName;
						$strCoordinate = GetCoordinatesForLog($this->objSource->arrAttackers[$j]->arrCoordinates, $this->objSource->blnHideCoord);
						$strTechnologies = GetTechnologiesForLog($this->objSource->arrAttackers[$j]->arrTechnologies, $this->objSource->blnHideTech, false);

						$strAttackerHTML .= "<font color='" . RED_COMMON . "'>".$this->objSource->arrAttackers[$j]->strName."</font>";
						if ((!$this->objSource->blnHideCoord) || (!$this->objSource->blnHideTech)) {
							$strAttackerHTML .=  "<br>";
						}
						if ((!$this->objSource->blnHideCoord) && ($strCoordinate != ""))  {
							$strAttackerHTML .=  " "."<font color='" . WHITE_DARK . "'>".$strCoordinate."</font>"." ";
						}
						if ((!$this->objSource->blnHideTech) && ($strTechnologies != "")) {
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
			for ($j = 0; $j < count($this->objSource->arrAttackers); $j++) {
				$strAttackerHTML = "<td class='newBack' align='center' valign='top'>";
				$arrRoundFleet = $this->objSource->arrAttackers[$j]->arrRoundFleet;
				$strAttackerHTML .= $this->GetFleetStatisticsHTML($arrRoundFleet, $this->objSource->intRoundsCount);
				$strAttackerHTML .= "</td>";
				$strAllAttackersFleetStatisticsHTML = $strAllAttackersFleetStatisticsHTML.$strAttackerHTML."\n";
                foreach ($arrRoundFleet[0]['fleet'] as $key => $value){
                    if ($value["count"]) {
                        if ($value["name"] == $key) $CountFleetAttackers[$key] += $value["count"];
                    }
                }
			}

            $urlWebSim = "http://websim.speedsim.net/index.php?lang=" . $_COOKIE["lang"];

            if ($CountFleetAttackers["SmallTransporter"])    $urlWebSim .= "&ship_a0_0_b=" . $CountFleetAttackers["SmallTransporter"];
            if ($CountFleetAttackers["BigTransporter"])      $urlWebSim .= "&ship_a0_1_b=" . $CountFleetAttackers["BigTransporter"];
            if ($CountFleetAttackers["LightFighter"])        $urlWebSim .= "&ship_a0_2_b=" . $CountFleetAttackers["LightFighter"];
            if ($CountFleetAttackers["HeavyFighter"])        $urlWebSim .= "&ship_a0_3_b=" . $CountFleetAttackers["HeavyFighter"];
            if ($CountFleetAttackers["Cruiser"])             $urlWebSim .= "&ship_a0_4_b=" . $CountFleetAttackers["Cruiser"];
            if ($CountFleetAttackers["Battleship"])          $urlWebSim .= "&ship_a0_5_b=" . $CountFleetAttackers["Battleship"];
            if ($CountFleetAttackers["Colony"])              $urlWebSim .= "&ship_a0_6_b=" . $CountFleetAttackers["Colony"];
            if ($CountFleetAttackers["Recycler"])            $urlWebSim .= "&ship_a0_7_b=" . $CountFleetAttackers["Recycler"];
            if ($CountFleetAttackers["Spy"])                 $urlWebSim .= "&ship_a0_8_b=" . $CountFleetAttackers["Spy"];
            if ($CountFleetAttackers["Bomber"])              $urlWebSim .= "&ship_a0_9_b=" . $CountFleetAttackers["Bomber"];
            //if ($CountFleetAttackers["SunSat"])            $urlWebSim .= "&ship_a0_10_b=" . $CountFleetAttackers["SunSat"];
            if ($CountFleetAttackers["Destroyer"])           $urlWebSim .= "&ship_a0_11_b=" . $CountFleetAttackers["Destroyer"];
            if ($CountFleetAttackers["Deathstar"])           $urlWebSim .= "&ship_a0_12_b=" . $CountFleetAttackers["Deathstar"];
            if ($CountFleetAttackers["Battlecruiser"])       $urlWebSim .= "&ship_a0_13_b=" . $CountFleetAttackers["Battlecruiser"];

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
				for ($j = 0; $j < count($this->objSource->arrDefenders); $j++) {
					$intEndFleetStructure = GetSumFleetStructure($this->objSource->arrDefenders[$j]->arrRoundFleet[$this->objSource->intRoundsCount - 1]);
					($intEndFleetStructure > 0) ? ($strIcon = VICTORY_ICON) : ($strIcon = DEFETED_ICON);
					$strDefenderHTML = "<td class='newBack' align='center' valign='top' >";
					$strDefenderHTML .= "<div name='img_'><img src='" . $strIcon . "'></div>";
					if ($this->objSource->arrDefenders[$j]->strName) {
						$strDefenderHTML .= "<span class='name textBeefy'>";

						$strName = $this->objSource->arrDefenders[$j]->strName;
						$strCoordinate = GetCoordinatesForLog($this->objSource->arrDefenders[$j]->arrCoordinates, $this->objSource->blnHideCoord);
						$strTechnologies = GetTechnologiesForLog($this->objSource->arrDefenders[$j]->arrTechnologies, $this->objSource->blnHideTech, false);

						$strDefenderHTML .= "<font color='" . GREEN_COMMON . "'>".$this->objSource->arrDefenders[$j]->strName."</font>";
						if ((!$this->objSource->blnHideCoord) || (!$this->objSource->blnHideTech)) {
							$strDefenderHTML .=  "<br>";
						}
						if ((!$this->objSource->blnHideCoord) && ($strCoordinate != "")) {
							$strDefenderHTML .=  " "."<font color='" . WHITE_DARK . "'>".$strCoordinate."</font>"." ";
						}
						if ((!$this->objSource->blnHideTech) && ($strTechnologies != "")) {
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
			for ($j = 0; $j < count($this->objSource->arrDefenders); $j++) {
				$strDefenderHTML = "<td class='newBack' align='center' valign='top'>";
				$arrRoundFleet = $this->objSource->arrDefenders[$j]->arrRoundFleet;
				$strDefenderHTML .= $this->GetFleetStatisticsHTML($arrRoundFleet, $this->objSource->intRoundsCount);
				$strDefenderHTML .= "</td>";
				$strAllDefendersFleetStatisticsHTML = $strAllDefendersFleetStatisticsHTML.$strDefenderHTML."\n";
                foreach ($arrRoundFleet[0]['fleet'] as $key => $value){
                    if ($value["count"]) {
                        if ($value["name"] == $key) $CountFleetDefenders[$key] += $value["count"];
                    }
                }
			}

            if ($CountFleetDefenders["SmallTransporter"])    $urlWebSim .= "&ship_d0_0_b=" . $CountFleetDefenders["SmallTransporter"];
            if ($CountFleetDefenders["BigTransporter"])      $urlWebSim .= "&ship_d0_1_b=" . $CountFleetDefenders["BigTransporter"];
            if ($CountFleetDefenders["LightFighter"])        $urlWebSim .= "&ship_d0_2_b=" . $CountFleetDefenders["LightFighter"];
            if ($CountFleetDefenders["HeavyFighter"])        $urlWebSim .= "&ship_d0_3_b=" . $CountFleetDefenders["HeavyFighter"];
            if ($CountFleetDefenders["Cruiser"])             $urlWebSim .= "&ship_d0_4_b=" . $CountFleetDefenders["Cruiser"];
            if ($CountFleetDefenders["Battleship"])          $urlWebSim .= "&ship_d0_5_b=" . $CountFleetDefenders["Battleship"];
            if ($CountFleetDefenders["Colony"])              $urlWebSim .= "&ship_d0_6_b=" . $CountFleetDefenders["Colony"];
            if ($CountFleetDefenders["Recycler"])            $urlWebSim .= "&ship_d0_7_b=" . $CountFleetDefenders["Recycler"];
            if ($CountFleetDefenders["Spy"])                 $urlWebSim .= "&ship_d0_8_b=" . $CountFleetDefenders["Spy"];
            if ($CountFleetDefenders["Bomber"])              $urlWebSim .= "&ship_d0_9_b=" . $CountFleetDefenders["Bomber"];
            if ($CountFleetDefenders["SunSat"])              $urlWebSim .= "&ship_d0_10_b=" . $CountFleetDefenders["SunSat"];
            if ($CountFleetDefenders["Destroyer"])           $urlWebSim .= "&ship_d0_11_b=" . $CountFleetDefenders["Destroyer"];
            if ($CountFleetDefenders["Deathstar"])           $urlWebSim .= "&ship_d0_12_b=" . $CountFleetDefenders["Deathstar"];
            if ($CountFleetDefenders["Battlecruiser"])       $urlWebSim .= "&ship_d0_13_b=" . $CountFleetDefenders["Battlecruiser"];

            if ($CountFleetDefenders["RocketLauncher"])     $urlWebSim .= "&ship_d0_14_b=" . $CountFleetDefenders["RocketLauncher"];
            if ($CountFleetDefenders["LightLaser"])         $urlWebSim .= "&ship_d0_15_b=" . $CountFleetDefenders["LightLaser"];
            if ($CountFleetDefenders["HeavyLaser"])         $urlWebSim .= "&ship_d0_16_b=" . $CountFleetDefenders["HeavyLaser"];
            if ($CountFleetDefenders["GaussCannon"])        $urlWebSim .= "&ship_d0_17_b=" . $CountFleetDefenders["GaussCannon"];
            if ($CountFleetDefenders["IonCannon"])          $urlWebSim .= "&ship_d0_18_b=" . $CountFleetDefenders["IonCannon"];
            if ($CountFleetDefenders["PlasmaCannon"])       $urlWebSim .= "&ship_d0_19_b=" . $CountFleetDefenders["PlasmaCannon"];
            if ($CountFleetDefenders["SmallShieldDome"])       $urlWebSim .= "&ship_d0_20_b=1";
            if ($CountFleetDefenders["LargeShieldDome"])       $urlWebSim .= "&ship_d0_21_b=1";

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
			$arrKeys = array('M', 'C', 'D');
			$arrexplode = array();

			// Merge players


			foreach ($arrPlayers["players_begin"] as $arrPlayer) {

				if (isset($arrexplode[$arrPlayer["name"]]["compounds"])) {
					foreach ($arrKeys as $strKey)
						$arrexplode[$arrPlayer["name"]]["compounds"][$strKey] += $arrPlayer["resources"][$strKey];
				}
				else {
					foreach ($arrKeys as $strKey)
						$arrexplode[$arrPlayer["name"]]["compounds"][$strKey] = $arrPlayer["resources"][$strKey];
				}
					if (!$arrPlayers["resources_begin"]['SUM']) {
						$arrexplode[$arrPlayer["name"]]["compounds"]["part"] = 1;
					}
					else {
						if (isset($arrexplode[$arrPlayer["name"]]["compounds"]["part"]))
							$arrexplode[$arrPlayer["name"]]["compounds"]["part"] += $arrPlayer["resources"]['SUM'] / $arrPlayers["resources_begin"]['SUM'];
						else
							$arrexplode[$arrPlayer["name"]]["compounds"]["part"] = $arrPlayer["resources"]['SUM'] / $arrPlayers["resources_begin"]['SUM'];
					}
			}

			foreach ($arrPlayers["players_end"] as $arrPlayer) {
				if (isset($arrexplode[$arrPlayer["name"]]["end"])) {
					foreach ($arrKeys as $strKey)
						$arrexplode[$arrPlayer["name"]]["end"][$strKey] += $arrPlayer["resources"][$strKey];
				}
				else {
					foreach ($arrKeys as $strKey)
						$arrexplode[$arrPlayer["name"]]["end"][$strKey] = $arrPlayer["resources"][$strKey];
				}
			}

			foreach ($arrexplode as $key => $value) {
				foreach ($arrKeys as $strKey) {
					$arrexplode[$key]["losses"][$strKey]  = $arrexplode[$key]["compounds"][$strKey] - $arrexplode[$key]["end"][$strKey];
				}
			}

			if (!$arrPlayers["resources_begin"]['SUM']) {
				$arrexplode[$arrPlayer["name"]]["losses"]["part"] = 1;
			}
			else {
				foreach ($arrexplode as $key => $value) {
					if (($value["compounds"]['M']+$value["compounds"]['C']+$value["compounds"]['D']) == 0)
						$arrexplode[$key]["losses"]["part"] = 0;
					else
						$arrexplode[$key]["losses"]["part"] = ($value["losses"]['M']+$value["losses"]['C']+$value["losses"]['D'])/($value["compounds"]['M']+$value["compounds"]['C']+$value["compounds"]['D']);
				}
			}

			return $arrexplode;
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
			$this->arrCache['battle coordinates'] = NULL;
			foreach ($this->objSource->arrDefenders as $key => $value) {
				$arrRoundFleet = $value->Get('roundfleet');
				if (!$this->arrCache['battle coordinates']) {
					$this->arrCache['battle coordinates'] = $value->Get('coordinates');
				}
				if ($value->Get('coordinates') == "undefined") {
					return array('attacker' => 0, 'defender' => 0);
				}
				$intConsumption = GetSumFleetConsumption($value->Get('coordinates'), $this->arrCache['battle coordinates'], $arrRoundFleet[0], $this->objSource->blnPFuel);
				$arrDefender[] = $intConsumption;
				$arrDefender['SUM'] += 	$intConsumption;
			}

			foreach ($this->objSource->arrAttackers as $key => $value) {
				$arrRoundFleet = $value->Get('roundfleet');
				if ($value->Get('coordinates') == "undefined") {
					return array('attacker' => 0, 'defender' => 0);
				}
				$intConsumption = GetSumFleetConsumption($value->Get('coordinates'), $this->arrCache['battle coordinates'], $arrRoundFleet[0], $this->objSource->blnPFuel);
				$arrAttacker[] = $intConsumption;
				$arrAttacker['SUM'] += 	$intConsumption;
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
			foreach ($this->objSource->arrAttackers as $key => $value) {
				$arrRoundFleet = $value->Get('roundfleet');
				$arrTmp['resources'] = GetSumFleetResources($arrRoundFleet[0],false);
				$arrTmp['structure'] = GetSumFleetStructure($arrRoundFleet[0]);
				$arrTmp['name'] = $value->Get('name');
				$arrAttacker['players_begin'][] = $arrTmp;
				$arrAttacker['resources_begin']['SUM'] += $arrTmp['resources']['SUM'];
				$arrAttacker['resources_begin']['M'] += $arrTmp['resources']['M'];
				$arrAttacker['resources_begin']['C'] += $arrTmp['resources']['C'];
				$arrAttacker['resources_begin']['D'] += $arrTmp['resources']['D'];
				$arrAttacker['sum_begin'] += GetSumFleetStructure($arrRoundFleet[0]);
			}

			foreach ($this->objSource->arrAttackers as $key => $value) {
				$arrRoundFleet = $value->Get('roundfleet');
				$intRoundsCount = $value->Get('roundscount');
				$arrTmp['structure'] = GetSumFleetStructure($arrRoundFleet[$intRoundsCount - 1]);
				$arrTmp['resources'] = GetSumFleetResources($arrRoundFleet[$intRoundsCount - 1],false);
				$arrTmp['name'] = $value->Get('name');
				$arrAttacker['players_end'][] = $arrTmp;
				$arrAttacker['resources_end']['SUM'] += $arrTmp['resources']['SUM'];
				$arrAttacker['resources_end']['M'] += $arrTmp['resources']['M'];
				$arrAttacker['resources_end']['C'] += $arrTmp['resources']['C'];
				$arrAttacker['resources_end']['D'] += $arrTmp['resources']['D'];
				$arrAttacker['sum_end'] += GetSumFleetStructure($arrRoundFleet[$intRoundsCount - 1]);
			}

			$this->arrCache['battle coordinates'] = NULL;
			foreach ($this->objSource->arrDefenders as $key => $value) {
				$arrRoundFleet = $value->Get('roundfleet');
				$arrTmp['structure'] = GetSumFleetStructure($arrRoundFleet[0]);
				$arrTmp['resources'] = GetSumFleetResources($arrRoundFleet[0],false);
				$arrTmp['resources_in_defense'] = GetSumFleetResources($arrRoundFleet[0],true);
				if (!$this->arrCache['battle coordinates']) {
					$this->arrCache['battle coordinates'] = $value->Get('coordinates');
				}
				$arrTmp['name'] = $value->Get('name');
				$arrDefender['players_begin'][] = $arrTmp;
				$arrDefender['resources_begin']['SUM'] += $arrTmp['resources']['SUM'];
				$arrDefender['resources_in_defense_begin']['SUM'] += $arrTmp['resources_in_defense']['SUM'];
				$arrDefender['resources_begin']['M'] += $arrTmp['resources']['M'];
				$arrDefender['resources_begin']['C'] += $arrTmp['resources']['C'];
				$arrDefender['resources_begin']['D'] += $arrTmp['resources']['D'];
				$arrDefender['sum_begin'] += GetSumFleetStructure($arrRoundFleet[0]);
			}

			foreach ($this->objSource->arrDefenders as $key => $value) {
				$arrRoundFleet = $value->Get('roundfleet');
				$intRoundsCount = $value->Get('roundscount');
				$arrTmp['structure'] = GetSumFleetStructure($arrRoundFleet[$intRoundsCount - 1]);
				$arrTmp['resources'] = GetSumFleetResources($arrRoundFleet[$intRoundsCount - 1],false);
				$arrTmp['resources_in_defense'] = GetSumFleetResources($arrRoundFleet[$intRoundsCount - 1],true);
				$arrTmp['name'] = $value->Get('name');
				$arrDefender['players_end'][] = $arrTmp;
				$arrDefender['resources_end']['SUM'] += $arrTmp['resources']['SUM'];
				$arrDefender['resources_in_defense_end']['SUM'] += $arrTmp['resources_in_defense']['SUM'];
				$arrDefender['resources_end']['M'] += $arrTmp['resources']['M'];
				$arrDefender['resources_end']['C'] += $arrTmp['resources']['C'];
				$arrDefender['resources_end']['D'] += $arrTmp['resources']['D'];
				$arrDefender['sum_end'] += GetSumFleetStructure($arrRoundFleet[$intRoundsCount - 1]);
			}

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
								<tr class='" . TD_BG_2 . "'>
									<td align='left' valign='top' colspan='2' style='padding:10; padding-left:20'>
										$strRecyclerReport
									</td>
								</tr>";
					}
					if (($strTotalRecycled != "") || ($strRecyclerReport != "")) {
						$strResult_ .= "
							<table width='800' border='1' style='border-collapse: collapse' bordercolor='" . "#222222" . "'>
								<tr class='" . TD_BG_1 . "'>
									<td align='left' colspan='2' style='padding:10'>
										<font color='" . WHITE_DARK . "'><b>" . Dictionary("recycler_report_title") . "</b></font>
									</td>
								</tr>
								$strTotalRecycled
								$strRecyclerReport
							</table>";
					}
				}
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
//made by Zmei Комменты
				if (!$this->objSource->bln_post ) {

$strTdTagBB = "align='center' valign='center' height='28' style='padding-left: 2; padding-right: 2;' onmouseover='this.setAttribute(\"background\", \"" . VISTA_PANEL_A_BB_CODE . "\");' onmouseout='this.setAttribute(\"background\", \"\");'";
$strResult_ .= "<br>
<table width='800' border='1' style='border-collapse: collapse' bordercolor='" . "#222222" . "'>
								<tr id='user_comments' class='" . TD_BG_1 . "'>
									<td align='left' colspan='2' style='padding:10' width='800'>
										<font color='" . WHITE_DARK . "'><b>" . Dictionary("user_comments_title") . "</b></font>
									</td>
								</tr>
								<tr class='" . TD_BG_2 . " user_comments'>
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
            $strAttacker = "<b><font color='" . WHITE_DARK . "'>" . Dictionary("att_gains_2") . ": </font><font color='" . WHITE_DARK . "'>" . PrepareNumber($intTotalGains) . "</font></b>";
			$this->arrCache['Profit']['Attacker'] = $intTotalGains;

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
            $strDefender  = "<b><font color='" . WHITE_DARK . "'>" . Dictionary("def_gains_2") . ": </font><font color='" . WHITE_DARK . "'>" . PrepareNumber($intTotalGains) . "</font></b>";
			$this->arrCache['Profit']['Defender'] = $intTotalGains;

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
			$intSumLosses = ($arrAttacker['resources_end']['SUM'] - $arrAttacker['resources_begin']['SUM'])*(-1) + ($arrDefender['resources_end']['SUM'] - $arrDefender['resources_begin']['SUM'])*(-1);
			$intSumLossesM = ($arrAttacker['resources_begin']['M'] - $arrAttacker['resources_end']['M']) + ($arrDefender['resources_begin']['M'] - $arrDefender['resources_end']['M']);
			$intSumLossesC = ($arrAttacker['resources_begin']['C'] - $arrAttacker['resources_end']['C']) + ($arrDefender['resources_begin']['C'] - $arrDefender['resources_end']['C']);
			$intSumLossesD = ($arrAttacker['resources_begin']['D'] - $arrAttacker['resources_end']['D']) + ($arrDefender['resources_begin']['D'] - $arrDefender['resources_end']['D']);

			$this->arrCache['SumLossesMC'] = NumberToString($intSumLosses);
			$this->arrCache['SumLossesD'] = NumberToString($intSumLossesD);
			$this->arrCache['SumLossesMCD'] = NumberToString($intSumLossesM+$intSumLossesC+$intSumLossesD);

			$strTitle = "<font color='" . YELLOW_COMMON . "'><b>" . Dictionary("losses_title") . ": " . NumberToString($intSumLosses) . "</b></font>";

			if ($intSumLossesD == 0)
				$strTotalLosses = "<font color='" . WHITE_DARK . "'><b>" . Dictionary("summary") . ": " . "</b></font>" . "<b><font color='" . YELLOW_COMMON . "'>" . NumberToString($intSumLosses) . "</font>" . "</b></font>";
			else
				$strTotalLosses = "<font color='" . WHITE_DARK . "'><b>" . Dictionary("summary") . ": " . "</b></font>" . "<b><font color='" . RED_COMMON . "'>" . NumberToString($intSumLosses) . "</font>" . " <font color='" . WHITE_DARK . "'>(" . NumberToString(($arrAttacker['resources_end']['SUM'] - $arrAttacker['resources_begin']['SUM'])*(-1)) . " + " . NumberToString(($arrDefender['resources_end']['SUM'] - $arrDefender['resources_begin']['SUM'])*(-1)) . ")</font></b></font>";


			$strProgress = "";
			$strLoses = "";
			if ($intSumLosses != 0) {
				$strProgress = $this->GetStatistics_CreateProgress(round(($arrAttacker['resources_begin']['SUM'] - $arrAttacker['resources_end']['SUM']) / (($arrAttacker['resources_begin']['SUM'] - $arrAttacker['resources_end']['SUM']) + ($arrDefender['resources_begin']['SUM'] - $arrDefender['resources_end']['SUM'])) * 100));
				$strProgress = "
                                    <tr class='" . TD_BG_2 . " losses'>
										<td align='center' height='38' colspan='2' style='padding:10'>
											$strTotalLosses
										</td>
									</tr>
									<tr class='" . TD_BG_2 . " losses'>
										<td align='center' colspan='2' style='padding:2'>
											$strProgress
										</td>
									</tr>
								";

				$strAttacker = "<font color='" . WHITE_DARK . "'>" . "<b>" . Dictionary("att_total_losses") . ": " . "<font color='" . RED_COMMON . "'>" . NumberToString(($arrAttacker['resources_begin']['SUM'] - $arrAttacker['resources_end']['SUM'])) . "</font>" ." (" . round(($arrAttacker['resources_begin']['SUM'] - $arrAttacker['resources_end']['SUM']) / $arrAttacker['resources_begin']['SUM'] * 100, 2) . "%)" . "</font>" . "</b><br>";
				$arrexplode = $this->MergePlayers($arrAttacker);
				if (count($arrexplode) > 1)
					foreach ($arrexplode as $key => $value)
						$strAttacker .= "<font color='" . WHITE_DARK . "'>" . "- " . $key . ": " . NumberToString($value["losses"]['M']+$value["losses"]['C']+$value["losses"]['D']) . " (" . round($value["losses"]["part"]*100,2) . "%) " . "</font>" . "<br>";

				$strDefender = "<font color='" . WHITE_DARK . "'>" . "<b>" . Dictionary("def_total_losses") . ": " . "<font color='" . RED_COMMON . "'>" . NumberToString(($arrDefender['resources_begin']['SUM'] - $arrDefender['resources_end']['SUM'])). "</font>" . " (" . round(($arrDefender['resources_begin']['SUM'] - $arrDefender['resources_end']['SUM']) / $arrDefender['resources_begin']['SUM'] * 100, 2) . "%)" . "</font>" . "</b><br>";
				$arrexplode = $this->MergePlayers($arrDefender);
				if (count($arrexplode) > 1)
					foreach ($arrexplode as $key => $value)
						$strDefender .= "<font color='" . WHITE_DARK . "'>" . "- " . $key . ": " . NumberToString($value["losses"]['M']+$value["losses"]['C']+$value["losses"]['D']) . " (" . round($value["losses"]["part"]*100,2) . "%) " . "</font>" . "<br>";


				//<def stat>
				$strDefenderEx = "";
				if ($arrDefender['resources_begin']['SUM'] > 0) {
					/*$strDefProgress = $this->GetStatistics_CreateProgress(round(($arrDefender['resources_in_defense_begin']['SUM']) / ($arrDefender['resources_begin']['SUM'])* 100));
					$strDefenderEx = "
						<tr class='" . TD_BG_2 . "'>
							<td width='50%'></td>
							<td width='50%' align='left' valign='top' border='1' style='padding:10;'>
								<div name='full_stat' style='display: block'>
									<center>
									<table  border='0' style='border-collapse: collapse' bordercolor='" . "#222222" . "'>
										<tr >
											<td align='center' valign='top' colspan='2' style='padding:10;'>
												<font color='" . WHITE_DARK . "'>" . "Defense / Fleet ratio" . "</font>
												<br>
												$strDefProgress
												<br>
												<font color='" . WHITE_DARK . "'><b>" . "Expected real losses: " . PrepareNumber(round($arrDefender['resources_end']['SUM'] - $arrDefender['resources_begin']['SUM'] + (-$arrDefender['resources_in_defense_end']['SUM'] + $arrDefender['resources_in_defense_begin']['SUM'])*0.7)) . "</b></font>
											</td>
										</tr>
									</table>
									</center>
								<div>
							</td>

						</tr>";
					*/
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
									<tr id='losses' class='" . TD_BG_1 . "'>
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
			$strTitle = "<font color='" . YELLOW_COMMON . "'><b>" . Dictionary("compounds_title") . "</b></font>";
			$strProgress = $this->GetStatistics_CreateProgress(round($arrAttacker['resources_begin']['SUM'] / ($arrAttacker['resources_begin']['SUM'] + $arrDefender['resources_begin']['SUM']) * 100));

			$strAttacker = "<font color='" . WHITE_DARK . "'>" . "<b>" . Dictionary("att_total_comp") . ": "  . " <font color='" . YELLOW_COMMON . "'>" . NumberToString($arrAttacker['resources_begin']['SUM']) . "</font>" . "</font>" . " (100%)" . "</b><br>";
			$arrexplode = $this->MergePlayers($arrAttacker);
			if (count($arrexplode) > 1)
				foreach ($arrexplode as $key => $value)
					$strAttacker .= "<font color='" . WHITE_DARK . "'>" . "- " . $key . ": " . NumberToString($value["compounds"]['M']+$value["compounds"]['C']+$value["compounds"]['D']) . " (" . round($value["compounds"]["part"]*100,2) . "%) " . "</font>" . "<br>";

			$strDefender = "<font color='" . WHITE_DARK . "'>" . "<b>" . Dictionary("def_total_comp") . ": "  . " <font color='" . YELLOW_COMMON . "'>" . NumberToString($arrDefender['resources_begin']['SUM']) . "</font>" . "</font>" . " (100%)" . "</b><br>";
			$arrexplode = $this->MergePlayers($arrDefender);
			if (count($arrexplode) > 1)
				foreach ($arrexplode as $key => $value)
					$strDefender .= "<font color='" . WHITE_DARK . "'>" . "- " . $key . ": " . NumberToString($value["compounds"]['M']+$value["compounds"]['C']+$value["compounds"]['D']) . " (" . round($value["compounds"]["part"]*100,2) . "%) " . "</font>" . "<br>";

			$strResult .= 	"<br>";

			$strResult .= 	"
								<table width='800' border='1' style='border-collapse: collapse' bordercolor='" . "#222222" . "'>
									<tr id='compounds' class='" . TD_BG_1 . "'>
										<td align='center' height='38' colspan='2' style='padding:10'>
											$strTitle
										</td>
									</tr>
									<tr class='" . TD_BG_2 . " compounds'>
										<td align='center' colspan='2' style='padding:2'>
											$strProgress
										</td>
									</tr>
									<tr class='" . TD_BG_2 . " compounds'>
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

			//<explode-up>
				$strResult .= $this->CreateexplodeUpDiv($arrAttacker, $arrDefender, true);
				$strResult .= $this->CreateexplodeUpDiv($arrAttacker, $arrDefender, false);
			//</explode-up>

			//<rank+url+bbcode>
				$strResult .= $this->CreateRankAndLinksDiv($arrAttacker, $arrDefender);
			//</rank+url+bbcode>

			return $strResult;
		}

		private function GetStatistics_CreateProgress($intDelim) {
			$intWidth = 200;
			$strResult = "";
			//$strResult .= "<b><font color='" . WHITE_DARK . "'>" . Dictionary("attacker") . " " . $intDelim . "%</font> / <font color='" . WHITE_DARK . "'>" . Dictionary("defender") . " " . (100 - $intDelim) ."%</font><b>";
			//$strResult .= "<br>";
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
			//$strResult .= "<font color='" . WHITE_DARK . "'>" . $intDelim . "%  </font><img id='' src='" . PROGRESS_20X20_RED . "' border='0' width='" . ($intDelim * $intWidth / 100) . "' height='20'>"."<img id='' src='" . PROGRESS_20X20_GREEN . "' border='0' width='" . ((100 - $intDelim) * $intWidth / 100) . "' height='20'><font color='" . WHITE_DARK . "'>  " . (100-$intDelim) . "%</font>";
			return $strResult;
		}

		private function GetStatistics_GetLogRank($arrAttacker, $arrDefender) {
			/*
			0.2 * self_count
			+2 if win
			-2 if lose
			4 * enemy_structure / (self_structure + enemy_structure) if win
			1.5 * enemy_losses / (self_losses + enemy_losses)
			4 * enemy_losses / (self_structure + enemy_structure)
			4 * stolen / (self_structure + enemy_structure)
			*/

			$dblAttackerRank = 0.0;
			$dblDefenderRank = 0.0;

			$dblAttackerRank += 0.2 * count($arrAttacker['players_begin']);
			$dblDefenderRank += 0.2 * count($arrDefender['players_begin']);

			if ($arrDefender['sum_end'] == 0) {
				$dblAttackerRank += 2;
				$dblDefenderRank -= 2;
			}
			if ($arrAttacker['sum_end'] == 0) {
				$dblAttackerRank -= 2;
				$dblDefenderRank += 2;
			}

			if ($arrDefender['sum_end'] == 0) {
				$dblAttackerRank += 4 * ($arrDefender['sum_begin'] / ($arrAttacker['sum_begin'] + $arrDefender['sum_begin']));
			}
			if ($arrAttacker['sum_end'] == 0) {
				$dblDefenderRank += 4 * ($arrAttacker['sum_begin'] / ($arrAttacker['sum_begin'] + $arrDefender['sum_begin']));
			}

			if (($arrAttacker['sum_begin'] - $arrAttacker['sum_end']) + ($arrDefender['sum_begin'] - $arrDefender['sum_end']) > 0) {
				$dblAttackerRank += 1.5 * (($arrDefender['sum_begin'] - $arrDefender['sum_end']) / (($arrAttacker['sum_begin'] - $arrAttacker['sum_end']) + ($arrDefender['sum_begin'] - $arrDefender['sum_end'])));
				$dblDefenderRank += 1.5 * (($arrAttacker['sum_begin'] - $arrAttacker['sum_end']) / (($arrAttacker['sum_begin'] - $arrAttacker['sum_end']) + ($arrDefender['sum_begin'] - $arrDefender['sum_end'])));
			}

			$dblAttackerRank += 4 * (($arrDefender['sum_begin'] - $arrDefender['sum_end']) / ($arrAttacker['sum_begin'] + $arrDefender['sum_begin']));
			$dblDefenderRank += 4 * (($arrAttacker['sum_begin'] - $arrAttacker['sum_end']) / ($arrAttacker['sum_begin'] + $arrDefender['sum_begin']));

			// TODO: 4 * stolen / (self_structure + enemy_structure)

			/*
			$dblLogRank -= 8 * ($arrAttacker['sum_begin'] - $arrAttacker['sum_end']) / (($arrAttacker['sum_begin'] - $arrAttacker['sum_end']) + ($arrDefender['sum_begin'] - $arrDefender['sum_end']));
			$dblLogRank += 8 * ($arrDefender['sum_begin'] - $arrDefender['sum_end']) / (($arrAttacker['sum_begin'] - $arrAttacker['sum_end']) + ($arrDefender['sum_begin'] - $arrDefender['sum_end']));

			$dblLogRank += 2 * ($arrDefender['sum_begin'] / ($arrAttacker['sum_begin'] + $arrDefender['sum_begin']));
			*/

			if ($dblAttackerRank < 0) $dblAttackerRank = 0;
			if ($dblDefenderRank < 0) $dblDefenderRank = 0;

			$arrResult[] = $dblAttackerRank;
			$arrResult[] = $dblDefenderRank;

			return $arrResult;
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

            if ($this->arrCache['Profit']['Attacker'] > $this->arrCache['Profit']['Defender']) $strProfit = $this->arrCache['Profit']['Attacker'];
            else $strProfit = $this->arrCache['Profit']['Defender'];

            if ($strProfit > 0) $strProfit = "[color=#009900]+".NumberS($strProfit, true)."[/color]";
            else $strProfit = "[color=#ff0000]-".NumberS($strProfit, true)."[/color]";

            $strLongTitle = "[".$varShortNameUni."] ".$strTitle." (". $this->arrCache["SumLossesMC"] .", ".ucfirst(strtolower($varUni)).".".strtolower($this->objSource->strDomain).", ".$this->objSource->strDate.")";
			$strBBUrl = "[url=".$SERVERURL."?id=".$this->objSource->strId."]";
			$strBBUrl .= $strLongTitle;
			$strBBUrl .= "[".$strProfit."]";
			$strBBUrl .= "[/url]";
            if ($SERVERURL == LOGSERVERURL){
			    $this->arrCache['bburl'] = $strBBUrl;
            }
            if ($SERVERURL == ALTLOGSERVERURL){
			    $this->arrCache['bburl2'] = $strBBUrl;
            }
			$this->arrCache['longtitle'] = $strLongTitle;
			return $strBBUrl;
		}

		private function GetBBUrl2($SERVERURL) {
		$strTitle = $this->Get("title");
		$strDomain = strtolower($this->objSource->strDomain);
		$strUni = strtolower('uni' . $this->objSource->intUni);
			$varUni = $this->objSource->intUni;
			$varShortNameUni = ShortNameUni($varUni,true);
			$varUni = NameUni($varUni);

            if ($this->arrCache['Profit']['Attacker'] > $this->arrCache['Profit']['Defender']) $strProfit = $this->arrCache['Profit']['Attacker'];
            else $strProfit = $this->arrCache['Profit']['Defender'];

            if ($strProfit > 0) $strProfit = "[color=#009900]+".NumberS($strProfit, true)."[/color]";
            else $strProfit = "[color=#ff0000]-".NumberS($strProfit, true)."[/color]";

            $strLongTitle = "[".$varShortNameUni."] ".$strTitle." (". $this->arrCache["SumLossesMC"] .", ".ucfirst(strtolower($varUni)).".".strtolower($this->objSource->strDomain).", ".$this->objSource->strDate.") [".$strProfit."]";
			$strBBUrl = "[url=".$SERVERURL."?id=".$this->objSource->strId."]";
			$strBBUrl .= $strLongTitle;
			$strBBUrl .= "[/url]";
            if ($SERVERURL == LOGSERVERURL){
			    $this->arrCache['bburl'] = $strBBUrl;
            }
            if ($SERVERURL == ALTLOGSERVERURL){
			    $this->arrCache['bburl2'] = $strBBUrl;
            }
			$this->arrCache['longtitle'] = $strLongTitle;
			return $strBBUrl;
		}

		private function CreateBBCode($arrAttacker, $arrDefender) {
			$arrCombatResult = $this->arrCache['combat_result'];
			$arrRanks = $this->GetStatistics_GetLogRank($arrAttacker, $arrDefender);

			$strBBCode = "";
			$strBBCode .= $this->GetBBUrl(LOGSERVERURL);
			$strBBCode .= "[quote=\"LogServer\"]";
			$strBBCode .= "[color=" . RED_COMMON . "][b]ATTACKER:[/b][/color]"."\n";
			for ($j = 0; $j < count($this->objSource->arrAttackers); $j++) {

				$strName = $this->objSource->arrAttackers[$j]->strName;
				$strCoordinate = GetCoordinatesForLog($this->objSource->arrAttackers[$j]->arrCoordinates, $this->objSource->blnHideCoord);
				$strTechnologies = GetTechnologiesForLog($this->objSource->arrAttackers[$j]->arrTechnologies, $this->objSource->blnHideTech, false);

				$strBBCode .= "[size=12][color=" . RED_COMMON . "]".$strName." ".$strCoordinate." "." ".$strTechnologies." "."[/color][/size]"."\n";
				$arrRoundFleet = $this->objSource->arrAttackers[$j]->arrRoundFleet;
				$strBBCode .= $this->GetFleetStatisticsBBcode($arrRoundFleet, $this->objSource->intRoundsCount);
			}
			$strBBCode .= "\n";
			$strBBCode .= "[color=" . GREEN_COMMON . "][b]DEFENDER:[/b][/color]"."\n";
			for ($j = 0; $j < count($this->objSource->arrDefenders); $j++) {

				$strName = $this->objSource->arrDefenders[$j]->strName;
				$strCoordinate = GetCoordinatesForLog($this->objSource->arrDefenders[$j]->arrCoordinates, $this->objSource->blnHideCoord);
				$strTechnologies = GetTechnologiesForLog($this->objSource->arrDefenders[$j]->arrTechnologies, $this->objSource->blnHideTech, false);

				$strBBCode .= "[size=12][color=" . GREEN_COMMON . "]".$strName." ".$strCoordinate." "." ".$strTechnologies." "."[/color][/size]"."\n";

				$arrRoundFleet = $this->objSource->arrDefenders[$j]->arrRoundFleet;
				$strBBCode .= $this->GetFleetStatisticsBBcode($arrRoundFleet, $this->objSource->intRoundsCount);
			}
			$strBBCode .= "\n";
			$strBBCode .= "[color=" . WHITE_DARK . "][b]COMBAT RESULT:[/b][/color]"."\n";
			$strBBCode .= "[size=10][color=" . WHITE_DARK . "]";
			$strBBCode .= $this->UpdateCombatResult($this->objSource->arrCombatResult->all);
			$strBBCode .= "[/color][/size]";

			//<statistics>
				$strBBCode .= "\n";
				$strBBCode .= "[b][color=" . RED_COMMON . "]TOTAL LOSSES: [/color][color=" . RED_COMMON . "]" . $this->arrCache['SumLossesMCD'] . "[/color]"."[color=" . RED_COMMON  . "] (" . $this->arrCache['SumLossesMC'] . " + " . $this->arrCache['SumLossesD'] . ") [/color][/b]\n";
				$strBBCode .= "[size=10][color=" . RED_COMMON . "]Attacker's total losses: " . NumberToString(($arrAttacker['resources_begin']['SUM'] - $arrAttacker['resources_end']['SUM'])) . "[/color][/size]";
				$strBBCode .= "\n";
				$strBBCode .= "[size=10][color=" . RED_COMMON . "]Defender's total losses: " . NumberToString(($arrDefender['resources_begin']['SUM'] - $arrDefender['resources_end']['SUM'])) . "[/color][/size]";
				$strBBCode .= "\n";
				$strBBCode .= "\n";
				$strBBCode .= "[b][color=" . GREEN_COMMON . "]PROFIT[/color][/b]\n";
				$intTotalGains = $this->arrCache['Profit']['Attacker'];
				if ($intTotalGains > 0) $strColor = "" . GREEN_COMMON . ""; else $strColor = "" . RED_COMMON . "";
				$strBBCode .= "[size=10][color=" . GREEN_COMMON . "]Attacker total gains (+wreckage): [/color][color=" . $strColor . "]" . NumberToString($intTotalGains) . "[/color][/size]"."\n";
				$intTotalGains = $this->arrCache['Profit']['Defender'];
				if ($intTotalGains > 0) $strColor = "" . GREEN_COMMON . ""; else $strColor = "" . RED_COMMON . "";
				$strBBCode .= "[size=10][color=" . GREEN_COMMON . "]Defender total gains (+wreckage): [/color][color=" . $strColor . "]" . NumberToString($intTotalGains) . "[/color][/size]"."\n";
			//</statistics>

			$strBBCode .= "\n";
			$strBBCode .= "[color=" . WHITE_DARK . "][b]LOG RANK: " . round(abs($arrRanks[0] - $arrRanks[1]), 1) . "[/b][/color]"."\n";

			$strBBCode .= "[/quote]";

			$this->arrCache['bbcode'] = $strBBCode;

			return $strBBCode;
		}
		private function CreateBBCode2($arrAttacker, $arrDefender) {
			$arrCombatResult = $this->arrCache['combat_result'];
			$arrRanks = $this->GetStatistics_GetLogRank($arrAttacker, $arrDefender);

			$strBBCode2 = "";
			$strBBCode2 .= $this->GetBBUrl(ALTLOGSERVERURL);
			$strBBCode2 .= "[quote=\"LogServer\"]";
			$strBBCode2 .= "[color=" . RED_COMMON . "][b]ATTACKER:[/b][/color]"."\n";
			for ($j = 0; $j < count($this->objSource->arrAttackers); $j++) {

				$strName = $this->objSource->arrAttackers[$j]->strName;
				$strCoordinate = GetCoordinatesForLog($this->objSource->arrAttackers[$j]->arrCoordinates, $this->objSource->blnHideCoord);
				$strTechnologies = GetTechnologiesForLog($this->objSource->arrAttackers[$j]->arrTechnologies, $this->objSource->blnHideTech, false);

				$strBBCode2 .= "[size=12][color=" . RED_COMMON . "]".$strName." ".$strCoordinate." "." ".$strTechnologies." "."[/color][/size]"."\n";
				$arrRoundFleet = $this->objSource->arrAttackers[$j]->arrRoundFleet;
				$strBBCode2 .= $this->GetFleetStatisticsBBcode($arrRoundFleet, $this->objSource->intRoundsCount);
			}
			$strBBCode2 .= "\n";
			$strBBCode2 .= "[color=" . GREEN_COMMON . "][b]DEFENDER:[/b][/color]"."\n";
			for ($j = 0; $j < count($this->objSource->arrDefenders); $j++) {

				$strName = $this->objSource->arrDefenders[$j]->strName;
				$strCoordinate = GetCoordinatesForLog($this->objSource->arrDefenders[$j]->arrCoordinates, $this->objSource->blnHideCoord);
				$strTechnologies = GetTechnologiesForLog($this->objSource->arrDefenders[$j]->arrTechnologies, $this->objSource->blnHideTech, false);

				$strBBCode2 .= "[size=12][color=" . GREEN_COMMON . "]".$strName." ".$strCoordinate." "." ".$strTechnologies." "."[/color][/size]"."\n";

				$arrRoundFleet = $this->objSource->arrDefenders[$j]->arrRoundFleet;
				$strBBCode2 .= $this->GetFleetStatisticsBBcode($arrRoundFleet, $this->objSource->intRoundsCount);
			}
			$strBBCode2 .= "\n";
			$strBBCode2 .= "[color=" . WHITE_DARK . "][b]COMBAT RESULT:[/b][/color]"."\n";
			$strBBCode2 .= "[size=10][color=" . WHITE_DARK . "]";
			$strBBCode2 .= $this->UpdateCombatResult($this->objSource->arrCombatResult->all);
			$strBBCode2 .= "[/color][/size]";

			//<statistics>
				$strBBCode2 .= "\n";
				$strBBCode2 .= "[b][color=" . RED_COMMON . "]TOTAL LOSSES: [/color][color=" . RED_COMMON . "]" . $this->arrCache['SumLossesMCD'] . "[/color]"."[color=" . RED_COMMON  . "] (" . $this->arrCache['SumLossesMC'] . " + " . $this->arrCache['SumLossesD'] . ") [/color][/b]\n";
				$strBBCode2 .= "[size=10][color=" . RED_COMMON . "]Attacker's total losses: " . NumberToString(($arrAttacker['resources_begin']['SUM'] - $arrAttacker['resources_end']['SUM'])) . "[/color][/size]";
				$strBBCode2 .= "\n";
				$strBBCode2 .= "[size=10][color=" . RED_COMMON . "]Defender's total losses: " . NumberToString(($arrDefender['resources_begin']['SUM'] - $arrDefender['resources_end']['SUM'])) . "[/color][/size]";
				$strBBCode2 .= "\n";
				$strBBCode2 .= "\n";
				$strBBCode2 .= "[b][color=" . GREEN_COMMON . "]PROFIT[/color][/b]\n";
				$intTotalGains = $this->arrCache['Profit']['Attacker'];
				if ($intTotalGains > 0) $strColor = "" . GREEN_COMMON . ""; else $strColor = "" . RED_COMMON . "";
				$strBBCode2 .= "[size=10][color=" . GREEN_COMMON . "]Attacker total gains (+wreckage): [/color][color=" . $strColor . "]" . NumberToString($intTotalGains) . "[/color][/size]"."\n";
				$intTotalGains = $this->arrCache['Profit']['Defender'];
				if ($intTotalGains > 0) $strColor = "" . GREEN_COMMON . ""; else $strColor = "" . RED_COMMON . "";
				$strBBCode2 .= "[size=10][color=" . GREEN_COMMON . "]Defender total gains (+wreckage): [/color][color=" . $strColor . "]" . NumberToString($intTotalGains) . "[/color][/size]"."\n";
			//</statistics>

			$strBBCode2 .= "\n";
			$strBBCode2 .= "[color=" . WHITE_DARK . "][b]LOG RANK: " . round(abs($arrRanks[0] - $arrRanks[1]), 1) . "[/b][/color]"."\n";

			$strBBCode2 .= "[/quote]";

			$this->arrCache['bbcode2'] = $strBBCode2;

			return $strBBCode2;
		}
		private function CreateRankAndLinksDiv($arrAttacker, $arrDefender) {
			$strTable = "
				<table>
					<tr>
						<td valign='top'>
							URL:&nbsp;
						</td>
						<td>
							<input type='text' name='' size='100' value='" . LOGSERVERURL . "?id=" . $this->objSource->strId . "' style='border:1px solid #888888; color: #888888; font-family: Arial; font-size: 12px; background-color: #000000' onclick='this.select();'>
						</td>
					</tr>
					<tr>
						<td valign='top'>
							BB-URL:&nbsp;
						</td>
						<td>
							<textarea rows='2' name='' cols='120' style='font-size: 12px; font-family: Arial; color:#888888; background-color:#000000; border-style:solid; border: 1px solid #888888;' onclick='this.select();'>".$this->GetBBUrl2(LOGSERVERURL)."</textarea>
						</td>
					</tr>
					<tr>
						<td valign='top'>
							BB-code:&nbsp"."\n"."&nbsp;
						</td>
						<td>
							<textarea rows='4' name='' cols='120' style='font-size: 12px; font-family: Arial; color:#888888; background-color:#000000; border-style:solid; border: 1px solid #888888;' onclick='this.select();'>".$this->CreateBBCode($arrAttacker, $arrDefender)."</textarea>
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
							<input type='text' name='' size='100' value='" . URL_BACKUP_SERVER . "?id=" . $this->objSource->strId . "' style='border:1px solid #888888; color: #888888; font-family: Arial; font-size: 12px; background-color: #000000' onclick='this.select();'>
						</td>
					</tr>
					<tr>
						<td valign='top'>
							ALT. BB-URL:&nbsp;
						</td>
						<td>
							<textarea rows='2' name='' cols='120' style='font-size: 12px; font-family: Arial; color:#888888; background-color:#000000; border-style:solid; border: 1px solid #888888;' onclick='this.select();'>".$this->GetBBUrl2(ALTLOGSERVERURL)."</textarea>
						</td>
					</tr>
					<tr>
						<td valign='top'>
							ALT. BB-code:&nbsp"."\n"."&nbsp;
						</td>
						<td>
							<textarea rows='4' name='' cols='120' style='font-size: 12px; font-family: Arial; color:#888888; background-color:#000000; border-style:solid; border: 1px solid #888888;' onclick='this.select();'>".$this->CreateBBCode2($arrAttacker, $arrDefender)."</textarea>
						</td>
					</tr>
				</table>";

			$arrRanks = $this->GetStatistics_GetLogRank($arrAttacker, $arrDefender);

            $strResult = "
				<div id=\"combat_result\">
					<center>
					<table width='800' border='1' style='border-collapse: collapse' bordercolor='" . "#222222" . "'>
						<tr id='log_rank' class='" . TD_BG_1 . "'>
							<td align='center' height='38' colspan='2' style='padding:10'>
								<font color='" . YELLOW_COMMON . "'><b>" . Dictionary("log_rank_title") . ": " . round(abs($arrRanks[0] - $arrRanks[1]), 1) . "</b></font>
							</td>
						</tr>
						<tr class='" . TD_BG_2 . " log_rank'>
							<td align='center' valign='top' colspan='2' style='padding:10;'>
								<font color='" . WHITE_DARK . "'><b>" . Dictionary("attacker") . " " . round($arrRanks[0], 1) . "</font> / <font color='" . WHITE_DARK . "'>" . Dictionary("defender") . " " . round($arrRanks[1], 1) ."<b></font>
							</td>
						</tr>
					</table>
					<br>
					<table width='800' border='1' style='border-collapse: collapse' bordercolor='" . "#222222" . "'>
						<tr id='url_title' class='" . TD_BG_1 . "'>
							<td align='center' height='38' colspan='2' style='padding:10'>
								<font color='" . YELLOW_COMMON . "'><b>" . "URL / BB-code" . "</b></font>
							</td>
						</tr>
						<tr class='" . TD_BG_2 . " url_title'>
							<td align='center' valign='top' colspan='2' style='padding:10;'>
								$strTable
							</td>
						</tr>
					</table>
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

		private function CreateexplodeUpDiv($arrAttacker, $arrDefender, $blnAttackerOrDefender) {
			$arrCombatResult = $this->arrCache['combat_result'];
			$arrRecyclerReport = $this->arrCache['recycler_report'];

			// I don't know what does it mean
			if (($arrAttacker['resources_end']['SUM'] == 0) && ($arrDefender['resources_end']['SUM'] == 0))
				return "";

			/*
			if (($arrAttacker['resources_end']['SUM'] > 0) && ($arrDefender['resources_end']['SUM'] == 0)) { // Attacker won
				$arrPlayers = $arrAttacker;
				$strColor = RED_COMMON;
			}
			else {
				if (($arrAttacker['resources_end']['SUM'] == 0) && ($arrDefender['resources_end']['SUM'] > 0)) { // Defender won
					$arrPlayers = $arrDefender;
					$strColor = GREEN_COMMON;
				}
				else
					return "";
			}
			*/

			if ($blnAttackerOrDefender) {
				$arrPlayers = $arrAttacker;
				$strColor = RED_COMMON;
			}
			else {
				$arrPlayers = $arrDefender;
				$strColor = GREEN_COMMON;
			}

			if ($arrPlayers["resources_begin"]['SUM'] == 0)
				return "";

			$arrKeys = array('M', 'C', 'D');

			// Get +
			foreach ($arrKeys as $strKey) {
				if ($blnAttackerOrDefender) {
					$intGained[$strKey] = $arrCombatResult[0][$strKey] + $arrRecyclerReport[$strKey];
				}
				else {
					$intGained[$strKey] = $arrRecyclerReport[$strKey];
				}
				$intSum[$strKey] = $intGained[$strKey] - $arrPlayers["resources_begin"][$strKey] + $arrPlayers["resources_end"][$strKey];
			}

			$arrexplode = array();

			// Merge players
			foreach ($arrPlayers["players_begin"] as $arrPlayer) {
				if (isset($arrexplode[$arrPlayer["name"]])) {
					foreach ($arrKeys as $strKey)
						$arrexplode[$arrPlayer["name"]][$strKey] += $arrPlayer["resources"][$strKey];
				}
				else {
					foreach ($arrKeys as $strKey)
						$arrexplode[$arrPlayer["name"]][$strKey] = $arrPlayer["resources"][$strKey];
				}
				if (isset($arrexplode[$arrPlayer["name"]]["part"]))
					$arrexplode[$arrPlayer["name"]]["part"] += $arrPlayer["resources"]['SUM'] / $arrPlayers["resources_begin"]['SUM'];
				else
					$arrexplode[$arrPlayer["name"]]["part"] = $arrPlayer["resources"]['SUM'] / $arrPlayers["resources_begin"]['SUM'];

				//$arrexplode[$arrPlayer["name"]][$strKey] = ($arrPlayer["structure"] / $arrPlayers["resources_begin"]['SUM']);
				/*if (isset($arrexplode[$arrPlayer["name"]]['PART_STRUCT']))
					$arrexplode[$arrPlayer["name"]]['PART_STRUCT'] += $dblPercent;
				else
					$arrexplode[$arrPlayer["name"]]['PART_STRUCT'] = $dblPercent;*/
			}

			foreach ($arrPlayers["players_end"] as $arrPlayer) {
				if (isset($arrexplode[$arrPlayer["name"]])) {
					foreach ($arrKeys as $strKey)
						$arrexplode[$arrPlayer["name"]][$strKey] -= $arrPlayer["resources"][$strKey];
				}
				else {
					foreach ($arrKeys as $strKey)
						$arrexplode[$arrPlayer["name"]][$strKey] = $arrPlayer["resources"][$strKey];
				}
			}

			//print_r($intSum); exit;

			$arrexplodeEqually = $arrexplode;

			foreach ($arrexplode as $key => $value) {
				foreach ($arrKeys as $strKey) {
					if ($intSum[$strKey] >= 0) {
						$arrexplode[$key][$strKey] += $intSum[$strKey] * $arrexplode[$key]["part"];
						$arrexplodeEqually[$key][$strKey] += $intSum[$strKey] / count($arrexplodeEqually);
					}
					else {
						$dblPercentEx[$strKey] = $arrexplode[$key][$strKey] / ($arrPlayers["resources_begin"][$strKey] - $arrPlayers["resources_end"][$strKey]);
						$arrexplode[$key][$strKey] = $intGained[$strKey] * $dblPercentEx[$strKey];
						$arrexplodeEqually[$key][$strKey] = $intGained[$strKey] * $dblPercentEx[$strKey];
					}
				}
			}

			foreach ($arrexplode as $key => $value) {
				$arrexplode[$key]["SUM"] = 0;
				$arrexplodeEqually[$key]["SUM"] = 0;
				foreach ($arrKeys as $strKey) {
					$arrexplode[$key][$strKey] = round($arrexplode[$key][$strKey]);
					$arrexplode[$key]["SUM"] += $arrexplode[$key][$strKey];
					$arrexplodeEqually[$key][$strKey] = round($arrexplodeEqually[$key][$strKey]);
					$arrexplodeEqually[$key]["SUM"] += $arrexplodeEqually[$key][$strKey];
				}
			}

			//print_r($arrexplode);
			//exit;

			if (count($arrexplode) == 1)
				return "";

			$strTable = "";
			$strBG = TD_BG_4;
			$strTable .= "<table border='1' style='border-collapse: collapse' bordercolor='" . "#222222" . "'>
							<tr>
								<td class='" . TD_BG_3 . "' style='padding:4' align='center'><b><font color='" . WHITE_DARK . "'></td>
								<td width='4' rowspan='" . (count($arrexplode) + 3) . "'></td>
								<td class='" . TD_BG_3 . "' style='padding:4' align='center' colspan='4'><b><font color='" . WHITE_DARK . "'>" . Dictionary("proportional") . "</td>
								<td width='4' rowspan='" . (count($arrexplode) + 3) . "'></td>
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
			foreach ($arrexplode as $key => $value) {
			($strBG == TD_BG_3) ? ($strBG = TD_BG_4) : ($strBG = TD_BG_3);
			$strTable .= "	<tr class='" . $strBG . "'>
								<td style='padding:4' align='left'><b><font color='" . $strColor . "'>" . $key . "</td>
								<td style='padding:4' align='right'><b>" . PrepareNumber($value['M']) . "</td>
								<td style='padding:4' align='right'><b>" . PrepareNumber($value['C']) . "</td>
								<td style='padding:4' align='right'><b>" . PrepareNumber($value['D']) . "</td>
								<td style='padding:4' align='right'><b>" . PrepareNumber($value['SUM']) . "</td>

								<td style='padding:4' align='right'><b>" . PrepareNumber($arrexplodeEqually[$key]['M']) . "</td>
								<td style='padding:4' align='right'><b>" . PrepareNumber($arrexplodeEqually[$key]['C']) . "</td>
								<td style='padding:4' align='right'><b>" . PrepareNumber($arrexplodeEqually[$key]['D']) . "</td>
								<td style='padding:4' align='right'><b>" . PrepareNumber($arrexplodeEqually[$key]['SUM']) . "</td>
							</tr>";
			}

			$strTable .= "</table>";

			$strResult = "
				<div id=\"combat_result\">
					<center>
					<table width='800' border='1' style='border-collapse: collapse' bordercolor='" . "#222222" . "'>
						<tr id='explodeup_title' class='" . TD_BG_1 . "'>
							<td align='center' height='38' colspan='2' style='padding:10'>
								<font color='" . YELLOW_COMMON . "'><b>" . Dictionary("explodeup_title") . "</b></font>
							</td>
						</tr>
						<tr class='" . TD_BG_2 . " explodeup_title'>
							<td align='center' valign='top' colspan='2' style='padding:10;'>
								$strTable
							</td>
						</tr>
					</table>
				</div>";

			return $strResult;
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
			$arrCombatResult = $this->arrCache['combat_result'];
			$arrRecyclerReport = $this->arrCache['recycler_report'];
			$arrIPMs = $this->arrCache['ipms'];
			$arrPlanetCleanUpReport = $this->ProcessPlanetCleanUpReport();
			$arrConsumption = $this->ProcessConsumption();

			$intRowSpan = 9;

			$intProfit[0]['M'] = $arrAttacker['resources_end']['M'] - $arrAttacker['resources_begin']['M'];
			$intProfit[0]['C'] = $arrAttacker['resources_end']['C'] - $arrAttacker['resources_begin']['C'];
			$intProfit[0]['D'] = $arrAttacker['resources_end']['D'] - $arrAttacker['resources_begin']['D'];
			$intProfit[0]['SUM'] = $arrAttacker['resources_end']['SUM'] - $arrAttacker['resources_begin']['SUM'];
			$intProfit[1]['M'] = $arrDefender['resources_end']['M'] - $arrDefender['resources_begin']['M'];
			$intProfit[1]['C'] = $arrDefender['resources_end']['C'] - $arrDefender['resources_begin']['C'];
			$intProfit[1]['D'] = $arrDefender['resources_end']['D'] - $arrDefender['resources_begin']['D'];
			$intProfit[1]['SUM'] = $arrDefender['resources_end']['SUM'] - $arrDefender['resources_begin']['SUM'];

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
			$strProfitNotPO = '';
			if ($arrRecyclerReport['SUM']) {
				$intRowSpan++;
				$strRecycled .= "
                <tr>
					<td class='" . $this->GetRSBG(0) . "' style='padding:4'><font color='" . GREEN_COMMON . "'><b>" . Dictionary("profit_not_po") . "</td>
					<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b><u>" . PrepareNumber($intProfitNotPO[0]['M']) . "</td>
					<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b><u>" . PrepareNumber($intProfitNotPO[0]['C']) . "</td>
					<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b><u>" . PrepareNumber($intProfitNotPO[0]['D']) . "</td>
					<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b><u>" . PrepareNumber($intProfitNotPO[0]['SUM']) . "</td>
					<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b><u>" . PrepareNumber($intProfitNotPO[1]['M']) . "</td>
					<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b><u>" . PrepareNumber($intProfitNotPO[1]['C']) . "</td>
					<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b><u>" . PrepareNumber($intProfitNotPO[1]['D']) . "</td>
					<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b><u>" . PrepareNumber($intProfitNotPO[1]['SUM']) . "</td>
				</tr>";
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
						<td class='" . $this->GetRSBG(0) . "' style='padding:4'><font color='" . RED_COMMON . "'><b>" . "Deut. consumption" . "</td>
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
					<td class='" . $this->GetRSBG(1) . "' style='padding:4'><font color='" . WHITE_DARK . "'><b>" . Dictionary("fleet_t") . "</td>
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
					<td class='" . $this->GetRSBG(1) . "' style='padding:4' align='right'><b>" . PrepareNumber($arrAttacker['resources_end']['M'] - $arrAttacker['resources_begin']['M']) . "</td>
					<td class='" . $this->GetRSBG(1) . "' style='padding:4' align='right'><b>" . PrepareNumber($arrAttacker['resources_end']['C'] - $arrAttacker['resources_begin']['C']) . "</td>
					<td class='" . $this->GetRSBG(1) . "' style='padding:4' align='right'><b>" . PrepareNumber($arrAttacker['resources_end']['D'] - $arrAttacker['resources_begin']['D']) . "</td>
					<td class='" . $this->GetRSBG(1) . "' style='padding:4' align='right'><b>" . PrepareNumber($arrAttacker['resources_end']['SUM'] - $arrAttacker['resources_begin']['SUM']) . "</td>
					<td class='" . $this->GetRSBG(1) . "' style='padding:4' align='right'><b>" . PrepareNumber($arrDefender['resources_end']['M'] - $arrDefender['resources_begin']['M']) . "</td>
					<td class='" . $this->GetRSBG(1) . "' style='padding:4' align='right'><b>" . PrepareNumber($arrDefender['resources_end']['C'] - $arrDefender['resources_begin']['C']) . "</td>
					<td class='" . $this->GetRSBG(1) . "' style='padding:4' align='right'><b>" . PrepareNumber($arrDefender['resources_end']['D'] - $arrDefender['resources_begin']['D']) . "</td>
					<td class='" . $this->GetRSBG(1) . "' style='padding:4' align='right'><b>" . PrepareNumber($arrDefender['resources_end']['SUM'] - $arrDefender['resources_begin']['SUM']) . "</td>
				</tr>
				$strCaptured
				$strRecycled
				$strCleaned
				$strIPMs
				$strConsumption
                $strProfitNotPO
                <tr>
					<td class='" . $this->GetRSBG(0) . "' style='padding:4'><font color='" . GREEN_COMMON . "'><b><u>" . Dictionary("profit_1_t") . "</td>
					<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b><u>" . PrepareNumber($intProfit[0]['M']) . "</td>
					<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b><u>" . PrepareNumber($intProfit[0]['C']) . "</td>
					<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b><u>" . PrepareNumber($intProfit[0]['D']) . "</td>
					<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b><u>" . PrepareNumber($intProfit[0]['SUM']) . "</td>
					<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b><u>" . PrepareNumber($intProfit[1]['M']) . "</td>
					<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b><u>" . PrepareNumber($intProfit[1]['C']) . "</td>
					<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b><u>" . PrepareNumber($intProfit[1]['D']) . "</td>
					<td class='" . $this->GetRSBG(0) . "' style='padding:4' align='right'><b><u>" . PrepareNumber($intProfit[1]['SUM']) . "</td>
				</tr>";

			$strTable .= "</table>";

			$strResult = "
				<div id=\"combat_result\">
					<center>
					<table width='800' border='1' style='border-collapse: collapse' bordercolor='" . "#222222" . "'>
						<tr id='rs_title' class='" . TD_BG_1 . "'>
							<td align='center' height='38' colspan='2' style='padding:10'>
								<font color='" . YELLOW_COMMON . "'><b>" . Dictionary("rs_title") . "</b></font>
							</td>
						</tr>
						<tr class='" . TD_BG_2 . " rs_title'>
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
				$arrReturn[0]['M'] = 0;
				$arrReturn[0]['C'] = 0;
				$arrReturn[0]['D'] = 0;
				$arrReturn[0]['SUM'] = 0;
				$arrReturn[1]['M'] = 0;
				$arrReturn[1]['C'] = 0;
				$arrReturn[1]['D'] = 0;
				$arrReturn[1]['SUM'] = 0;

			$strTmp = "";
			$arrTmp = NULL;

			$strTmp = "";
			if (key_exists("0",$this->objSource->arrCombatResult->part)) {
				$strTmp = $this->objSource->arrCombatResult->part[0];
			}

			if(preg_match_all('/[\-0-9]+[\.\,0-9]*/', $strTmp, $arrMatches)) {
				$arrReturn[0]['M'] = (float) str_replace(",", "", str_replace(".", "", $arrMatches[0][0]));
				$arrReturn[0]['C'] = (float) str_replace(",", "", str_replace(".", "", $arrMatches[0][1]));
				$arrReturn[0]['D'] = (float) str_replace(",", "", str_replace(".", "", $arrMatches[0][2]));
				$arrReturn[0]['SUM'] = $arrReturn[0]['M'] + $arrReturn[0]['C'] + $arrReturn[0]['D'];
			}

			$strTmp = "";
			if (key_exists("1",$this->objSource->arrCombatResult->part)) {
				$strTmp = $this->objSource->arrCombatResult->part[1];
			}

			if(preg_match_all('/[0-9]+[\.\,0-9]*/', $strTmp, $arrMatches)) {
				//print_r($arrMatches);
				$arrReturn[1]['M'] = (float) str_replace(",", "", str_replace(".", "", $arrMatches[0][2]));
				$arrReturn[1]['C'] = (float) str_replace(",", "", str_replace(".", "", $arrMatches[0][3]));
				$arrReturn[1]['D'] = 0;
				$arrReturn[1]['SUM'] = $arrReturn[1]['M'] + $arrReturn[1]['C'] + $arrReturn[1]['D'];
			}

			//<CHECK MOON>
				$strTmp = preg_replace("'<br.*?>'is","<br>",$strTmp);
				$search=array("\n", "\r");
				$strTmp=str_replace($search,"", $strTmp);

				$arrTemp = explode("<br>",$strTmp);

				$bPercentLine = false;
				$arrReturn[2] = false;
				foreach ($arrTemp as $strValue) {
					if ($strValue != "") {
						if ($bPercentLine) {
							if (preg_match('/^[^0-9]+$/', $strValue, $arrMatches)) {
								$arrReturn[2] = true;
							}
							break;
						}
						if (strpos($strValue, "%")) {
							$bPercentLine = true;
						}
					}
				}
			//<\CHECK MOON>

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
				$strPattern = "/(:\s*[0-9]+[^0-9:]+[0-9]+[^0-9:]+[0-9]+)|(![^0-9]+[0-9]+[^0-9]+[0-9]+[^0-9]+[0-9]+)/";
				if (preg_match_all($strPattern, $strPlanetCleanUpReport, $arrMatches)) {
					$arrReturn = array();
					$arrReturn['M'] = 0;
					$arrReturn['C'] = 0;
					$arrReturn['D'] = 0;
					$arrReturn['SUM'] = 0;
					foreach ($arrMatches[0] as $strPlanetCleanUpReport) {
						$strPattern = "/[0-9]+/";
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
			$this->strHTMLLogNew = $strHTML;
		}
	}
?>