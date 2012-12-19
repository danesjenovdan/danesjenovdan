<?php
header('Access-Control-Allow-Origin: *');
session_start();

if(isset($_SESSION['user'])){
    $var=array('uid'=>$_SESSION['uid'],'name'=>$_SESSION['user']['name']." ".$_SESSION['user']['surname'], 'email'=>$_SESSION['user']['email']);
} else {
    $var=array('uid'=>-1);
}
   echo(json_encode($var));

?>
