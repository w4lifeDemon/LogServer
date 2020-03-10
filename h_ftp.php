<?php
	function BackupObject($strId) {
		$blnReturn = false;
		if ((substr($strId, 0, 4) != "f000") && (substr($strId, 0, 4) != "f100") && (substr($strId, 0, 4) != "f200") && (substr($strId, 0, 4) != "f300")) {
			LogError("BackupObject", "Wrong input: " . $strId);
		    return $blnReturn;
		}
		$blnReturn = false;
		$objConnection = ftp_connect(FTP_BACKUP_SERVER); 
		if (!$objConnection) { 
		    LogError("BackupObject", "ftp_connect failed");
		    return $blnReturn;
		}
		$blnResult = ftp_login($objConnection, FTP_BACKUP_LOGIN, FTP_BACKUP_PSWD); 
		if (!$blnResult) { 
		    LogError("BackupObject", "ftp_login failed");
		    return $blnReturn;
		}
		$strFile = $strId;
		$blnResult = ftp_pasv($objConnection, true);
		$blnResult = ftp_put($objConnection, FTP_BACKUP_STORAGE_PATH . "/" . $strFile, FOLDER_UPLOAD . "/" . $strFile, FTP_BINARY); 
		if (!$blnResult) { 
		    LogError("BackupObject", "ftp_put failed");
		}
		else {
			$blnReturn = true;
		}
		ftp_close($objConnection);
		return $blnReturn;
	}
?>
