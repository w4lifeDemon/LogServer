<?php
	class cUser
	{
		var $intId;
		var $strLogin;
		var $strPassword;
		var $strMail;
		var $intRole;
		var $strRegDate;
		var $blnConfirm;
		
		var $varSettingsBox;
		var $varLogBox;
		
		function CreateTempUser($strLogin, $strPassword, $strMail) {
			if (preg_match("/^[A-Za-z0-9_ ]{3,12}$/", $strLogin) == 0) {
				LogError("cUser::CreateTempUser", "Login does not match the requirements: " . $strLogin);
				return false;
			}
			if (preg_match("/^[A-Za-z0-9_ ]{3,20}$/", $strPassword) == 0) {
				LogError("cUser::CreateTempUser", "Password does not match the requirements");
				return false;
			}
			if ((preg_match("/^.+@.+\..{2,3}$/", $strMail) == 0) || (strlen($strMail) > 40)){
				LogError("cUser::CreateTempUser", "E-mail does not match the requirements");
				return false;
			}
			if (!$this->IsLoginExists($strLogin)) {
				LogError("cUser::CreateTempUser", "User with the same login exists");
				return false;
			}
			if (!$this->IsMailExists($strMail)) {
				LogError("cUser::CreateTempUser", "User with the same e-mail exists");
				return false;
			}
			$this->strLogin = $strLogin;
			$this->strPassword = $strPassword;
			$this->strMail = $strMail;
			return true;
		}
		
		function SaveTempUser() {
			if (!cDB::SaveTempUser($this)) {
				LogError("cUser::SaveTempUser", "cDB::SaveTempUser failed");
				return false;
			}
			return true;
		}
		
		function SaveTempUser2() {
			if (!cDB::SaveUser($this)) {
				LogError("cUser::SaveTempUser", "cDB::SaveUser failed");
				return false;
			}
			return true;
		}
		
		function ActivateUser() {
			if (!cDB::ActivateUser($this->strLogin)) {
				LogError("cUser::ActivateUser", "cDB::ActivateUser failed");
				return false;
			}
			return true;
		}
		
		function LoadTempUser() {
			$objTempUser = cDB::LoadTempUser($this->strLogin);
			if (!$objTempUser) {
				LogError("cUser::LoadTempUser", "cDB::LoadTempUser failed: " . $this->strLogin);
				return false;
			}
			$this->strPassword = $objTempUser->strPassword;
			$this->strMail = $objTempUser->strMail;
			$this->strRegDate = $objTempUser->strRegDate;
			return true;
		}
		
		function DeleteTempUser() {
			if (!cDB::DeleteTempUser($this->strLogin)) {
				LogError("cUser::DeleteTempUser", "cDB::DeleteTempUser failed");
				return false;
			}
			return true;
		}
		
		function SaveUser() {
			$this->intId = cDB::GetUserId();
			if (IsErrors()) {
				LogError("cUser::SaveUser", "objUser->GetId failed");
				return false;
			}
			$this->intRole = 1;
			if (!cDB::SaveUser($this->strLogin)) {
				LogError("cUser::SaveTempUser", "cDB::SaveTempUser failed");
				return false;
			}
			return true;
		}
		
		function LoadUser() {
			$objUser = cDB::LoadUser($this->strLogin);
			if (!$objUser) {
				LogError("cUser::LoadUser", "cDB::LoadUser failed: " . $this->strLogin);
				return false;
			}
			$this->intId = $objUser->intId;
			$this->strPassword = $objUser->strPassword;
			$this->strMail = $objUser->strMail;
			$this->intRole = $objUser->intRole;
			$this->strRegDate = $objUser->strRegDate;
			return true;
		}
		
		function LoadUser2() {
			$objUser = cDB::LoadUser2($this->strLogin, $this->strPassword);
			if (!$objUser) {
				LogError("cUser::LoadUser2", "cDB::LoadUser2 failed; Login: " . $this->strLogin . " Password: " . $this->strPassword);
				return false;
			}
			$this->intId = $objUser->intId;
			$this->strMail = $objUser->strMail;
			$this->intRole = $objUser->intRole;
			$this->strRegDate = $objUser->strRegDate;
			return true;
		}

		function LoadLostpw() {
            $varResult = cDB::LoadUrlLostpw($this->strMail);
			if (!$varResult) {
		    	return "ERR_LOSTPSW_MAIL_DB";
			} else {
			    $strUser64 = base64_encode(base64_encode($varResult.date("dmyH")));
			    $strURL = "https://logserver.net/index.php?lostpw=" . $strUser64 . "&mail=" . $this->strMail;

    			$strSubject = "Подтверждение смены пароля";
                                
    			$strMessage = "Здравствуйте! Мы получили запрос на сброс пароля для Вашей учетной записи.<br><br>";
                $strMessage .= "<hr><br>";
                $strMessage .= "Для сброса пароля, перейдите по указанной ниже ссылке или скопируйте её и вставьте в адресную строку Вашего браузера:<br><br>";
                $strMessage .= "$strURL<br><br>";
                $strMessage .= "Вы получили данное письмо, поскольку кто-то запросил сброс пароля. Если Вы не делали такого запроса, проигнорируйте данное письмо.<br><br>";
                $strMessage .= "<hr><br>";
                $strMessage .= "Если Вы получите его повторно, свяжитесь с нами и сообщите об этом.<br>";

    			if(!cMail::SendMail($this->strMail, $strSubject, $strMessage)) {
    				LogError("LoadUrlLostpw", "Can't send mail to: " . $strDestination);
    				return false;
    			}

                return 'TRUE_URL_LOSTPSW';
            }
		}

		function HidePassword() {
			$this->strPassword = md5(str_pad($this->strPassword, 40, '0'));
		}
		
		private function IsLoginExists($strLogin) {
			if (!cDB::IsLoginExists($strLogin)) {
				LogError("cUser::IsLoginExists", "cDB::IsLoginExists failed");
				return false;
			}
			return true;
		}
		
		private function IsMailExists($strMail) {
			if (!cDB::IsMailExists($strMail)) {
				LogError("cUser::IsMailExists", "cDB::IsMailExists failed");
				return false;
			}
			return true;
		}

		function ProcessChangtpw($arrInput) {
			if (!$arrInput["old_pass"]) {
				return ERR_CHANGT_OLDPSW;
			}
			if (!$arrInput["new_pass"] || strlen($arrInput["new_pass"]) < 5) {
			    return ERR_CHANGT_NEWPSW;
			}
			if (!$arrInput["new_pass2"] || $arrInput["new_pass"] != $arrInput["new_pass2"]) {
			    return ERR_CHANGT_NEWPSW2;
			}
			$strOldPass = md5(str_pad($arrInput["old_pass"], 40, '0'));
			if (!cDB::IsPassExists($_SESSION['account']['id'], $strOldPass)) {
			    return ERR_CHANGT_ISPSW;
			}
			$strNewPass = md5(str_pad($arrInput["new_pass"], 40, '0'));
			if (!cDB::LoadChangtpw($_SESSION['account']['id'], $strNewPass)) {
			    return ERR_CHANGT_LOADPSW;
			} else {
			    return INFO_CHANGT_FINISH;
			}
        }

		/*
		class cDB {
	     function TempLoginExists(strLogin)
	     function TempMailExists(strLogin)
	     function LoginExists(strLogin)
	     function MailExists(strLogin)
	     function SaveTempUser(objUser)
	     function ActivateTempUser(strLogin) // &Iuml;&aring;&eth;&aring;&iacute;&aring;&ntilde;&ograve;&egrave; &egrave;&ccedil; &acirc;&eth;&aring;&igrave;&aring;&iacute;&iacute;&icirc;&eacute; &ograve;&agrave;&aacute;&euml;&egrave;&ouml;&ucirc; &acirc; &icirc;&ntilde;&iacute;&icirc;&acirc;&iacute;&oacute;&thorn;
	     function DeleteTempUser(strLogin)
	     function DeleteTempFakes() // &Oacute;&auml;&agrave;&euml;&egrave;&ograve;&uuml; &acirc;&ntilde;&aring;&otilde; &ograve;&aring;&igrave;&iuml;&icirc;&acirc;&ucirc;&otilde; &thorn;&ccedil;&aring;&eth;&icirc;&acirc;, &ccedil;&agrave;&eth;&aring;&atilde;&agrave;&iacute;&iacute;&ucirc;&otilde; &aacute;&icirc;&euml;&uuml;&oslash;&aring; &iacute;&aring;&auml;&aring;&euml;&egrave; &iacute;&agrave;&ccedil;&agrave;&auml;
	     function LoadTempUser(strLogin)
	     function LoadUser(strLogin)
		}
		*/
	}

	class cRegSrv {
		static function ProcessRegistration($arrInput) {
			$objUser = new cUser();
			if (!$objUser->CreateTempUser($arrInput["account_login"], $arrInput['account_pswd'], $arrInput['account_mail'])) {
				LogError("cRegSrv::ProcessRegistration", "cUser::CreateTempUser failed");
				return false;
			}
			$objUser->HidePassword();
			if (!$objUser->SaveTempUser2()) {
				LogError("cRegSrv::ProcessRegistration", "cUser::SaveTempUser failed");
				return false;
			}
			if (!cMail::SendConfirmMail($objUser)) {
				LogError("cRegSrv::ProcessRegistration", "cMail::SendConfirmMail failed");
				return false;
			}
			return true;
		}

		function ConfirmLostpw($strLogin64, $strMail) {
            $acceptedChars = 'abcdefghijklmnopqrstuvwxyzl234567890';
            for  ($i=0;  $i<=7;  $i++) {
                $randomNumber = rand(0, (strlen($acceptedChars)-1));
                $varPassword .= $acceptedChars[$randomNumber];
            }
			$strLogin = str_replace(date("dmyH"), "", base64_decode(base64_decode($strLogin64)));
            $varResult = cDB::LoadLostpw($strLogin, $strMail, $varPassword);
			if (!$varResult) {
		    	return 'ERR_LOSTPSW_MAIL_DB';
			} else {
    			$strSubject = "Новый пароль";

    			$strMessage = "$strLogin!<br><br>";                                
                $strMessage .= "<hr><br>";
                $strMessage .= "Новый пароль: $varPassword<br><br>";
                $strMessage .= "<hr><br>";

    			if(!cMail::SendMail($strMail, $strSubject, $strMessage)) {
    				LogError("LoadUrlLostpw", "Can't send mail to: " . $strDestination);
    				return false;
    			}

                return 'TRUE_CONFIRM_LOSTPSW';
            }
		}

		static function ConfirmRegistration($strLogin64) {
			$objUser = new cUser();
			$objUser->strLogin = base64_decode(base64_decode($strLogin64));
			/*
			if (!$objUser->LoadTempUser()) {
				LogError("cRegSrv::ConfirmRegistration", "cUser::LoadTempUser failed");
				return false;
			}
			if (!$objUser->DeleteTempUser()) {
				LogError("cRegSrv::ConfirmRegistration", "cUser::DeleteTempUser failed");
				return false;
			}
			if (!$objUser->SaveUser()) {
				LogError("cRegSrv::ConfirmRegistration", "cUser::SaveUser failed");
				return false;
			}
			if (!$objUser->LoadUser()) {
				LogError("cRegSrv::ConfirmRegistration", "cUser::LoadUser failed");
				return false;
			}
			*/
			if (!$objUser->ActivateUser()) {
				LogError("cRegSrv::ConfirmRegistration", "cUser::ActivateUser failed");
				return false;
			}
			if (!$objUser->LoadUser()) {
				LogError("cRegSrv::ConfirmRegistration", "cUser::LoadUser failed");
				return false;
			}
			$_SESSION['account']['login'] = $objUser->strLogin;
			$_SESSION['account']['id'] = $objUser->intId;

			return true;
		}

		static function ProcessLogin($varInput) {
			if (!array_key_exists("account_login", $varInput) || !array_key_exists("account_pswd", $varInput)) {
				LogError("cRegSrv::ProcessLogin", "Wrong input");
				return false;
			}
			$objUser = new cUser();
			$objUser->strLogin = $varInput['account_login'];
			$objUser->strPassword = $varInput['account_pswd'];
			$objUser->HidePassword();
			if (!$objUser->LoadUser2()) {
				LogError("cRegSrv::ProcessLogin", "cUser::LoadUser2 failed");
				return false;
			}
			$_SESSION['account']['login'] = $objUser->strLogin;
			$_SESSION['account']['id'] = $objUser->intId;
			if ($varInput['account_remember']) {
				$strData = base64_encode(serialize(array('account_login' => $varInput['account_login'], 'account_pswd' => $varInput['account_pswd'])));
				setcookie('autologin', $strData, time() + 60 * 60 * 24 * 30);
			}

			return true;
		}

		static function ProcessLostpw($varInput) {
			if (!$varInput['account_mail'] || !$varInput['account_code']) {
				return 'ERR_LOSTPSW';
			}
            else if (!preg_match("/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/", $varInput['account_mail'])) {
				return 'ERR_LOSTPSW_MAIL';
            }
            else if ($_SESSION['secpic'] != $varInput['account_code']) {
				return 'ERR_LOSTPSW_CODE';
            }
            else {
    			$objUser = new cUser();
    			$objUser->strMail = $varInput['account_mail'];
    			$objUser->strCode = $varInput['account_code'];

    			return $objUser->LoadLostpw();
            }
		}

		static function AutoLoginFromCookie() {
			//print_r($_COOKIE);
			if (isset($_COOKIE['autologin'])) {
				$arrData = unserialize(base64_decode($_COOKIE['autologin']));
				$objUser = new cUser();
				$objUser->strLogin = $arrData['account_login'];
				$objUser->strPassword = $arrData['account_pswd'];
				$objUser->HidePassword();
				if (!$objUser->LoadUser2()) {
					return false;
				}
				$_SESSION['account']['login'] = $objUser->strLogin;
				$_SESSION['account']['id'] = $objUser->intId;
			}
		}

		static function ProcessLogout() {
			unset($_SESSION['account']);
			setcookie('autologin', '');
            session_unset();
			return true;
		}
	}
?>
