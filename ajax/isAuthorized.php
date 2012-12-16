<?php

session_start();

if(isset($_SESSION['uid']) && $_SESSION['uid']>0){
    $var=array('uid'=>$_SESSION['uid'],'name'=>$_SESSION['username'], 'email'=>$_SESSION['email']);
} else {
    $var=array('uid'=>-1);
}
   echo(json_encode($var));

?>
