<?php
require_once("../config/config.php");
require_once("../lib/user.php");
//session_start();

if (isset($_SESSION['ref']) && $_SESSION['state'] && ($_SESSION['state'] === $_REQUEST['state'])) {} 
else if(!isset($_REQUEST["code"])){
    $_SESSION['state'] = md5(uniqid(rand(), TRUE)); // CSRF protection
    
        $_SESSION['ref'] = $_SERVER['HTTP_REFERER'];
        
    $dialog_url = "https://www.facebook.com/dialog/oauth?client_id=" 
       . $app_id . "&redirect_uri=" . urlencode($my_url) . "&state="
       . $_SESSION['state']."&scope=email";
     //header("Location: " . $dialog_url);
     echo("<script> top.location.href='" . $dialog_url . "'</script>");
}
else
{
$code = $_REQUEST["code"];

if($_SESSION['state'] && ($_SESSION['state'] === $_REQUEST['state'])) { 
    $token_url = "https://graph.facebook.com/oauth/access_token?"
       . "client_id=" . $app_id . "&redirect_uri=" . urlencode($my_url)
       . "&client_secret=" . $app_secret . "&code=" . urlencode($code);

     $response = file_get_contents($token_url);
     
     $params = null;
     parse_str($response, $params);
     $_SESSION['access_token'] = $params['access_token'];
     $graph_url = "https://graph.facebook.com/me?" . $response;
       
     $response = file_get_contents($graph_url);

    $user = json_decode($response);
            //$namesurname=explode(" ",$user->name);
			$data = array(
				'name' => $user->first_name,
				'surname' => $user->last_name ,
				'email' => $user->email,
                'fbid' => $user->id
			);
		if ($user = User::getUserByArray (array ('email' => $user->email))) {
				User::updateUser(array ('fbid' => $user->id, "email" => $user->email), "email");
				User::login ($user['id_user']);
			} else if (!empty($data['name'])) {
				$user_id = User::addUser ($data);
				User::login ($user_id);
			} else {
                var_dump($_SESSION);
			    echo "Login Failed";
			}
            $ref = $_SESSION['ref'];
            unset($_SESSION['ref']);
            header("Location: http://sect.io/" . $ref);

    }
   else {
     //echo("Login Failed...");
   }
}
?>
