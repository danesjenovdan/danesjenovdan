<?php
require_once("../config/config.php");
require_once("../lib/user.php");
//session_start();

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
            $namesurname=explode(" ",$user->name);
			$data = array(
				'name' => $namesurname[0] ,
				'surname' => $namesurname[1] ,
				'email' => $user->email,
                'fbid' => $user->id
			);
		if ($user = User::getUserByArray (array ('fbid' => $user->id))) {
				User::login ($user['id_user']);
			} else {
				$user_id = User::addUser ($data);
				User::login ($user_id);
			}
    }
   else {
    // echo("Login Failed...");
   }
}
?>
