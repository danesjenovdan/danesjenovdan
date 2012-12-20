<?php

require_once ('../config/config.php');
require_once ("../lib/user.php");

if (isset($_POST['email']) && isset($_POST['name']) && isset($_POST['surname'])) {
	if(!User::getUserByArray(array ('email' => mysqli_real_escape_string($_POST['email']))) {
	    $userID = User::addUser(array(
	        "email" => mysqli_real_escape_string($db, $_POST['email']),
	        "name" => mysqli_real_escape_string($db, $_POST['name']),
	        "surname" => mysqli_real_escape_string($db, $_POST['surname']),
	    ));
	}
    
    User::login($userID);
}

echo User::jsonEncodeUser();

?>