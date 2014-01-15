<?php
ob_start();
require_once("include/entryPoint.php");

session_destroy();

redirect('../home.php');
ob_flush();
?>
