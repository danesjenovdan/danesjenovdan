<?php
require_once ("../lib/user.php");

header('Access-Control-Allow-Origin: *');
session_start();

echo User::jsonEncodeUser();

?>
