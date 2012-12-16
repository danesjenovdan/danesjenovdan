<?php

/* In this file there are some settings for the global site behavior */

/* *********************************************
   ************ Database settings **************
   ********************************************* */

$dbhost="kreatorij.com";
$dbname="danesjenovdan";
$dbuser="danesjenovdan";
$dbpassword="danesjenovdan";

$db = new mysqli($dbhost, $dbuser, $dbpassword, $dbpassword);
if ($db->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    exit(1);
}



?>
