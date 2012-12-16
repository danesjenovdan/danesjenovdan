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

/* *********************************************
   ************ Facebook settings **************
   ********************************************* */

   $app_id = "212117025591104";
   $app_secret = "23f0eb6371165818dabb378b7fabbf7a";
   $my_url = "http://localhost/~samo/danesjenovdan/login/processFBlogin.php";


/* *********************************************
   ************  Google settings  **************
   ********************************************* */

    $GClientID="813022248366.apps.googleusercontent.com";
    $GClientSecret="Y5uUj1pPp9umsunxuRlZtkg6";
    $GRedirectURL="http://localhost/~samo/danesjenovdan/login/GLogin.php";
    $GDeveloperKey="AI39si47CSsbhEM8fAwNal-ch3NyBweCXtBV2rG9pKormHGw5rjzHDvdm2TwAvK9Aq_VS_00-7xGpKMQ02tx0fOi18U6cU9nMw";




?>
