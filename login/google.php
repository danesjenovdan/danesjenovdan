<?php

require_once ('../config/config.php');
require_once ("../lib/user.php");
require_once ('../lib/openid.php');
# Logging in with Google accounts requires setting special identity, so this example shows how to do it.

//if(!isset($_SESSION['googleref'])) {
//	$_SESSION['googleref'] = $_GET['ref'];
//}

//try {
	# Change 'localhost' to your domain name.
//	$openid = new LightOpenID($_SERVER['HTTP_HOST']);
	$openid = new LightOpenID('danesjenovdan.si');
//	if (!empty ($_SESSION['uid'])) {
//		header ("Location: ../");
//	} else
	if(!$openid->mode) {
// uncomment to set manual login
//		if(isset($_GET['login'])) {
			$openid->identity = 'https://www.google.com/accounts/o8/id';
			$openid->required = array('contact/email' , 'namePerson/first' , 'namePerson/last');
            $openid->returnUrl = $google_return_url;
			header('Location: ' . $openid->authUrl());
//		}
/*		?>
	<form action="?login" method="post">
		<button>Login with Google</button>
	</form>
	<?php*/
	} elseif($openid->mode == 'cancel') {
		echo 'User has canceled authentication!';
	} else {
		if($openid->validate())
		{
			//User logged in
			$d = $openid->getAttributes();

			$first_name = $d['namePerson/first'];
			$last_name = $d['namePerson/last'];
			$email = $d['contact/email'];

//			$data = array(
//				'name' => $first_name ,
//				'surname' => $last_name ,
//				'email' => $email
//			);

			if
				($user = User::getUserByArray
					(array 
						('email' => mysqli_real_escape_string
							($db, $email)
					)
				)
			) {

				echo $user['id_user'];
	    		User::login($user['id_user']);
//	    		echo User::jsonEncodeUser();
				echo "<script>document.location.href = 'http://www.danesjenovdan.si/ajax/isAuthorized.php';</script>";
			}else{
	   			$userID = User::addUser(array(
	        		"email" => mysqli_real_escape_string($db, $email),
	        		"name" => mysqli_real_escape_string($db, $first_name),
	        		"surname" => mysqli_real_escape_string($db, $last_name),
	    		));
	    		User::login($userID);
//	    		echo User::jsonEncodeUser();
				header('Location: ../');
			}
			
		} else {
			echo ("NOT");
			//user is not logged in
		}
	}
//}
