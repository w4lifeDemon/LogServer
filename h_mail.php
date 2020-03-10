<?php
	class cMail {
		function SendConfirmMail($objInput) {
			$strUser64 = base64_encode(base64_encode($objInput->strLogin));
			$strDestination = $objInput->strMail;
			$strSource = "LogServer.org";
			$strSubject = "$strSource - confirm registration";
			$strURL = "https://logserver.net/index.php?reg=" . $strUser64;
			$strMessage = "This e-mail was used for registration on $strSource.\n
							Confirm your registration using this link: $strURL\n\n
							Best regards, $strSource administration.";
			if(!mail($strDestination, $strSubject, $strMessage, "From: $strSource")) {
				LogError("SendConfirmMail", "Can't send mail to: " . $strDestination);
				return false;
			}
			return true;
		}
		function SendMail($strDestination, $strSubject, $strMessage) {
    			$strSource = "LogServer.org";
                $strHeader = "From: $strSource <webmaster@logserver.org>\r\n";
                $strHeader .= "Content-type: text/html; charset=\"windows-1251\"\r\n";

    			$strSubject = "$strSource - $strSubject";
                $strMessage .= "С уважением, администрация $strSource.";
                
    			if(!mail($strDestination, $strSubject, $strMessage, $strHeader)) {
    				LogError("SendMail", "Can't send mail to: " . $strDestination);
    				return false;
    			}
			return true;
		}        
	}
?>