<?php
	define("IMG_DLGWND_0", "index_files/dlgwnd/dlgwnd_0.png");
	define("IMG_DLGWND_2", "index_files/dlgwnd/dlgwnd_2.png");
	define("IMG_DLGWND_4", "index_files/dlgwnd/dlgwnd_4.png");
	define("IMG_DLGWND_5", "index_files/dlgwnd/dlgwnd_5.png");
	define("IMG_DLGWND_6", "index_files/dlgwnd/dlgwnd_6.png");
	define("IMG_DLGWND_8", "index_files/dlgwnd/dlgwnd_8.png");
	define("IMG_DLGWND_A", "index_files/dlgwnd/dlgwnd_a.png");
	define("IMG_DLGWND_E", "index_files/dlgwnd/dlgwnd_e.png");
	
	class cDlgWnd {
		var $blnScriptSent = false;
		function CreateDlgHTML($strId, $strTitle, $strMsg) {
			$strPreload = "
				<img style='display: none' src='" . IMG_DLGWND_0 . "'>
				<img style='display: none' src='" . IMG_DLGWND_2 . "'>
				<img style='display: none' src='" . IMG_DLGWND_4 . "'>
				<img style='display: none' src='" . IMG_DLGWND_5 . "'>
				<img style='display: none' src='" . IMG_DLGWND_6 . "'>
				<img style='display: none' src='" . IMG_DLGWND_8 . "'>
				<img style='display: none' src='" . IMG_DLGWND_A . "'>
				<img style='display: none' src='" . IMG_DLGWND_E . "'>
				";
			$strTable = "
				<table border='0' style='border-collapse: collapse' cellpadding='0'>
					<tr>
						<td>
							<table width='100%' border='0' style='border-collapse: collapse' cellpadding='0'>
								<tr>
									<td width='8' height='28' style='background: transparent url(" . IMG_DLGWND_0 . "); background-position: left top'></td>
									<td height='28' style='background: transparent url(" . IMG_DLGWND_2 . ")'><font face='Arial' size='2' color='#FFFFFF'>$strTitle</font></td>
									<td id='dlgwnd_close_$strId' width='60' height='28' style='background: transparent url(" . IMG_DLGWND_0 . "); background-position: right top'>
										<table width='100%' height='100%' border='0' style='border-collapse: collapse' cellpadding='0'>
											<tr>
												<td width='10' height='18'></td>
												<td><img src='" . IMG_DLGWND_E . "' onmouseover='dlgwnd_activate_close(\"dlgwnd_close_$strId\")' onmouseout='dlgwnd_deactivate_close(\"dlgwnd_close_$strId\")' onclick='dlgwnd_hide(\"$strId\")'></td>
												<td width='5'></td>
											</tr>
											<tr>
												<td></td>
												<td></td>
												<td></td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
							<table width='100%' border='0' style='border-collapse: collapse' cellpadding='0'>
								<tr>
									<td height='100%'><img src='" . IMG_DLGWND_4 . "' width='8' height='100%'></td>
									<td colspan='2' style='padding: 10; background: transparent url(" . IMG_DLGWND_5 . ")'><font face='Arial' size='2' color='#444444'>$strMsg</font></td>
									<td height='100%'><img src='" . IMG_DLGWND_6 . "' width='8' height='100%'></td>
								</tr>
							</table>
							<table width='100%' border='0' style='border-collapse: collapse' cellpadding='0'>
								<tr>
									<td width='8' height='8' style='background: transparent url(" . IMG_DLGWND_0 . "); background-position: left bottom'></td>
									<td><img src='" . IMG_DLGWND_8 . "' width='100%' height='8'></td>
									<td width='55' height='8' style='background: transparent url(" . IMG_DLGWND_0 . "); background-position: right bottom'></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				";
			
			$strScript = "
				<script>
					function dlgwnd_activate_close(strId) {
						document.getElementById(strId).style.background = 'transparent url(" . IMG_DLGWND_A . ")';
						document.getElementById(strId).style.backgroundPosition = 'right top';
					}
					function dlgwnd_deactivate_close(strId) {
						document.getElementById(strId).style.background = 'transparent url(" . IMG_DLGWND_0 . ")';
						document.getElementById(strId).style.backgroundPosition = 'right top';
					}
					function dlgwnd_hide(strId) {
						document.getElementById(strId).style.display = 'none';
					}
				</script>
				";
			
			$strHTML = "
				<div id='$strId' style='display: none; position: absolute; z-index: 1; width: 100%; height: 100%; left: 0px; top: 0px'>
				<table border='0' width='100%' height='100%' background='" . TABLE_BACKGROUND . "'>
					<tr>
						<td align='center' valign='center' height='50%'>
							<div>
								$strPreload
								$strTable
							</div>
						</td>
					</tr>
					<tr><td height='50%'></td></tr>
				</table>
				</div>
			";
			
			if (!$this->blnScriptSent) {
				$strHTML .= $strPreload;
				$strHTML .= $strScript;
				$this->blnScriptSent = true;
			}
			
			return $strHTML;
		}
	}
?>