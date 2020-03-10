<?php
	function SendHTTPRequest($arrInput) { //sample: SendHTTPRequest(array('host' => 'logserver.net', 'url' => 'http://logserver.net'));
		if (!CheckHTTPCOnnection($arrInput)) return;
		$strResponse = "";
		$fp = fsockopen($arrInput['host'], 80, $errno, $errstr, 30);
		if (!$fp) {
		    //echo "$errstr ($errno)<br />\n";
		}
		else {
		    $out = "GET " . $arrInput['url'] . " HTTP/1.0\r\n\r\n ";
		    //$out .= "Host: " . $strHost . "\r\n";
		    $out .= "Connection: Close\r\n\r\n";
		    fwrite($fp, $out);
		    while (!feof($fp)) {
		        $strResponse .= fgets($fp, 128);
		    }
		    fclose($fp);
		}
		return $strResponse;
	}
	
	function CheckHTTPCOnnection($arrInput) {
		error_reporting(0);
		$fp = fsockopen($arrInput['host'], 80, $errno, $errstr, 30);
		error_reporting(E_ALL);
		if (!$fp) return;
		else fclose($fp);
		return true;
	}
?>