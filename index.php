<?php
session_start();
    date_default_timezone_set('Europe/Moscow');
	set_time_limit(20);
	error_reporting (0);
	/*
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	*/
/*
	function auth_send(){
	    header('WWW-Authenticate: Basic realm="Service BD"');
	    header('HTTP/1.0 401 Unauthorized');
	    echo "<html><body bgcolor=white link=blue vlink=blue alink=red>"
	    ,"<h1>Ошибка аутентификации</h1>"
	    ,"<h1>Обслуживание базы данных</h1>"
	    ,"<p>Обратитесь к администратору для получения логина и пароля.</p>"
	    ,"</body></html>";	    
	    exit;
	};

	$login = "admin";
	$password = "admin";

	if (!isset($_SERVER['PHP_AUTH_USER'])) {
		auth_send();
	} else {
		$auth_user = $_SERVER['PHP_AUTH_USER'];
		$auth_pass = $_SERVER['PHP_AUTH_PW'];
		
		if (($auth_user != $login) || ($auth_pass != $password)) {
			auth_send();
		};
	};
*/
    if ($_COOKIE["del_logserver"] == 1) exit("Логсервер удален!");
	require 'h_abox.php';
	require 'h_battle.php';
	require 'h_constants.php';
	require 'h_db.php';
	require 'h_dlgwnd.php';
	require 'h_user.php';
	require 'h_files.php';
	require 'h_ftp.php';
	require 'h_api.php';
	require 'h_functions.php';
	require 'h_html.php';
	require 'h_html_constructor.php';
	require 'h_html_constructor_6x.php';
	require 'h_html_constructor_7x.php';
	require 'h_http.php';
	require 'h_localizations.php';
	require 'h_log.php';
	require 'h_mail.php';
	require 'h_parser.php';
	require 'h_parser_0x.php';
	require 'h_parser_1x.php';
	require 'h_parser_7x.php';
	require 'h_player.php';
	require 'h_system.php';

	cRegSrv::AutoLoginFromCookie();
	
	SetLang();
	
	Main();
?>