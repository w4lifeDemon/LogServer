<?php
error_reporting (0);
sleep (1);
	if ($_POST['md5'])
		echo md5($_GET['md5'] . "logserver");
	if ($_GET['md5'])
		echo md5(str_pad($_GET['md5'], 40, '0'));
	if ($_GET['login'])
        echo str_replace(date("dmyH"), "", base64_decode(base64_decode(($_GET['login']))));
?>