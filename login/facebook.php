<?php
require_once("../config/config.php");
require_once("../lib/user.php");
//session_start();

if(!isset($_SESSION['trajlala'])) {
$_SESSION['trajlala'] = $_GET['ref'];
}

if (isset($_SESSION['ref']) && $_SESSION['state'] && ($_SESSION['state'] === $_REQUEST['state'])) {
	            $ref = $_SESSION['trajlala'];
	            unset($_SESSION['trajlala']);
	            header("Location: " . $ref);
	//			echo($ref);
	//			echo("<script> top.location.href='" . $ref . "'</script>");
	
} 

//else
 if(!isset($_REQUEST["code"])){
    $_SESSION['state'] = md5(uniqid(rand(), TRUE)); // CSRF protection
    
        $_SESSION['ref'] = $_SERVER['HTTP_REFERER'];
        
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
				'name' => mysqli_real_escape_string($db, $user->first_name),
				'surname' => mysqli_real_escape_string($db, $user->last_name),
				'email' => mysqli_real_escape_string($db, $user->email),
                'fbid' => mysqli_real_escape_string($db, $user->id)
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
//            $ref = $_SESSION['trajlala'];
//            unset($_SESSION['trajlala']);
//            header("Location: http://sect.io/" . $ref);
//			echo($ref);
//			echo("<script> top.location.href='" . $ref . "'</script>");

    }
   else {
     //echo("Login Failed...");
   }
}
?>
