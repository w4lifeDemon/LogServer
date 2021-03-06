<?
/*
# File: logout.php
# Script Name: vAuthenticate 3.0
# Author: Vincent Ryan Ong
# Email: support@beanbug.net
#
# Description:
# vAuthenticate is a revolutionary authentication script which uses
# PHP and MySQL for lightning fast processing. vAuthenticate comes 
# with an admin interface where webmasters and administrators can
# create new user accounts, new user groups, activate/inactivate 
# groups or individual accounts, set user level, etc. This may be
# used to protect files for member-only areas. vAuthenticate 
# uses a custom class to handle the bulk of insertion, updates, and
# deletion of data. This class can also be used for other applications
# which needs user authentication.
#
# This script is a freeware but if you want to give donations,
# please send your checks (coz cash will probably be stolen in the
# post office) them to:
#
# Vincent Ryan Ong
# Rm. 440 Wellington Bldg.
# 655 Condesa St. Binondo, Manila
# Philippines, 1006
*/
	// Destroy Sessions
	setcookie ("USERNAME", "");
	setcookie ("PASSWORD", "");	
	include_once ("authconfig.php");
?>

<html>
<head>
<title>Member's Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<p><font face="Arial, Helvetica, sans-serif" size="5"><b>You have successfully logged off.</b></font></p>
<p><font face="Arial, Helvetica, sans-serif" size="2"><b>Click <a href="<? echo $login; ?>">here</a> to re-login.</b></font></p>
