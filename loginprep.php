<?php
require_once ("../lib/user.php");

header('Access-Control-Allow-Origin: *');
session_start();

echo User::jsonEncodeUser();

echo "<script>document.location.href = '" . $_GET['ref'] . "</script>"

?>
