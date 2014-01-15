<?php 
ob_start();
session_start();
require_once('account/include/entryPoint.php');
ini_set('date.timezone', 'Europe/London');
echo '<title>DEPOSIT MONEY</title>';

$merchant_reference = 'rpm-'.time();
echo '<iframe src="http://www.eurivex.com/depositmoney_test.php?ref='.$merchant_reference.'" width="100%" height="100%" seamless="seamless"><iframe>';
ob_flush();
?>