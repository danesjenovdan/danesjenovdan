<?php
require_once("../config/config.php");
require_once("../lib/user.php");
//session_start();

if(!isset($_REQUEST["code"])){ 
    $_SESSION['state'] = md5(uniqid(rand(), TRUE)); // CSRF protection
    
        $_SESSION['ref'] = $_SERVER['HTTP_REFERER'];
        
    $dialog_url = "https://www.facebook.com/dialog/oauth?client_id=" 
       . $app_id . "&redirect_uri=" . urlencode($my_url . "?ref=" . $_SESSION['ref']) . "&state="
       . $_SESSION['state']."&scope=email";
     echo("<script> top.location.href='" . $dialog_url . "'</script>");
}
else
{
$code = $_REQUEST["code"];

if($_SESSION['state'] && ($_SESSION['state'] === $_REQUEST['state'])) { 
    $token_url = "https://graph.facebook.com/oauth/access_token?"
       . "client_id=" . $app_id . "&redirect_uri=" . urlencode($my_url . "?ref=" . $_SESSION['ref'])
       . "&client_secret=" . $app_secret . "&code=" . urlencode($code);

     //$response = file_get_contents($token_url);
     
       $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, $token_url); 
            curl_setopt($ch, CURLOPT_HEADER, TRUE); 
            curl_setopt($ch, CURLOPT_NOBODY, TRUE); // remove body 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
            $response = curl_exec($ch); 
     
     $params = null;
     parse_str($response, $params);
     $_SESSION['access_token'] = $params['access_token'];
     $graph_url = "https://graph.facebook.com/me?access_token=" 
       . $params['access_token'];
       
       
       $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, $graph_url); 
            curl_setopt($ch, CURLOPT_HEADER, TRUE); 
            curl_setopt($ch, CURLOPT_NOBODY, TRUE); // remove body 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
            $response = curl_exec($ch);
    $user = json_decode($response);
            //$namesurname=explode(" ",$user->name);
			$data = array(
				'name' => $user->first_name,
				'surname' => $user->last_name ,
				'email' => $user->email,
                'fbid' => $user->id
			);
		if ($user = User::getUserByArray (array ('fbid' => $user->id))) {
				User::login ($user['id_user']);
			} else {
				$user_id = User::addUser ($data);
				User::login ($user_id);
			}

            header("Location: " . $_SESSION['ref']);

    }
   else {
    // echo("Login Failed...");
   }
}
?>
