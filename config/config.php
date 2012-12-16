<?php

ini_set ("display_errors", 1);
ini_set ("display_startup_errors", 1);
ini_set('error_reporting', E_ALL);

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
} else {
	mysqli_query ($db, "SET NAMES UTF8");
}

session_start();

/* *********************************************
   ************ Facebook settings **************
   ********************************************* */

   $app_id = "212117025591104";
   $app_secret = "23f0eb6371165818dabb378b7fabbf7a";
   $my_url = "http://sect.io/login/facebook.php";

