<?php
require("../config/config.php");
require_once ("../lib/user.php");

if(!isset($_REQUEST["code"])){
    $_SESSION['state'] = md5(uniqid(rand(), TRUE)); // CSRF protection
    $dialog_url = "https://www.facebook.com/dialog/oauth?client_id="
       . $app_id . "&redirect_uri=" . urlencode($my_url) . "&state="
       . $_SESSION['state']."&scope=email";
     echo("<script> top.location.href='" . $dialog_url . "'</script>");
}
else
{
$code = $_REQUEST["code"];

if($_SESSION['state'] && ($_SESSION['state'] === $_REQUEST['state'])) {
    $token_url = "https://graph.facebook.com/oauth/access_token?"
       . "client_id=" . $app_id . "&redirect_uri=" . urlencode($my_url)
       . "&client_secret=" . $app_secret . "&code=" . $code;

     $response = file_get_contents($token_url);
     $params = null;
     parse_str($response, $params);
     $_SESSION['access_token'] = $params['access_token'];
     $graph_url = "https://graph.facebook.com/me?access_token="
       . $params['access_token'];

    $user = json_decode(file_get_contents($graph_url));
    $_SESSION["username"]=$user->name;
    $_SESSION["email"]=$user->email;
    $_SESSION["authorized"]=true;
    $_SESSION["authority"]="facebook";
    $_SESSION["uid"]=$user->id;
    //Check if we have user in database. If we do, we do nothing. If it is his
    //firsttime login, we insert him into database.
    $query="select id_user from user where fbid=".$user->id.";";
    $result=$db->query($query);
    $line=$result->fetch_object();
    $result->close();
    if($line){
           // echo "User found!";
            $_SESSION["userid"]=$line->id_user;
           // echo "User id=".$line->id_user;
    }
    else {
        // echo "User is not in database. Inserting.\n";
        $sql="insert into user(email, name, fbid) values ('".$user->email."','".$user->name."',".$user->id.");";
        //echo $sql;
        $db->query($sql);

        $query="select id_user from user where fbid=".$user->id.";";
        if($result=$db->query($query)){
            $line=$result->fetch_object();
            $result->close();
            if($line){
                $_SESSION["userid"]=$line->id_user;
            }
            else {
                echo "Something went wrong!\n";
            }
        }
    }
    }
   else {
    // echo("Login Failed...");
    $_SESSION["username"]=NULL;
    $_SESSION["authorized"]=false;
    $_SESSION["email"]=NULL;
    $_SESSION["authority"]=NULL;
    $_SESSION["uid"]=NULL;
   }
}
?>
