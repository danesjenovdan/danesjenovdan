<?php

require_once ('../config/config.php');
require_once ("../lib/user.php");

if (isset($_POST['email']) && isset($_POST['name']) && isset($_POST['surname'])) {
    $userID = User::addUser(array(
        "email" => mysqli_real_escape_string($_POST['email']),
        "name" => mysqli_real_escape_string($_POST['name']),
        "surname" => mysqli_real_escape_string($_POST['surname']),
    ));
    
    User::login($userID);
}

echo User::jsonEncodeUser();

?>