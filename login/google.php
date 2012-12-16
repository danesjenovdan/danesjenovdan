<?php

include_once ('../config/config.php');

//var_dump($_SESSION);exit();
# Logging in with Google accounts requires setting special identity, so this example shows how to do it.
require '../lib/openid.php';
//try {
	# Change 'localhost' to your domain name.
	$openid = new LightOpenID($_SERVER['HTTP_HOST']);
	if (!empty ($_SESSION['uid'])) {
		echo "Prijavljen!";
	} elseif(!$openid->mode) {
		if(isset($_GET['login'])) {
			$openid->identity = 'https://www.google.com/accounts/o8/id';
			$openid->required = array('contact/email' , 'namePerson/first' , 'namePerson/last');
			header('Location: ' . $openid->authUrl());
		}
		?>
	<form action="?login" method="post">
		<button>Login with Google</button>
	</form>
	<?php
	} elseif($openid->mode == 'cancel') {
		echo 'User has canceled authentication!';
	} else {
		if($openid->validate())
		{
			//User logged in
			$d = $openid->getAttributes();
var_dump ($d);Exit();
			$first_name = $d['namePerson/first'];
			$last_name = $d['namePerson/last'];
			$email = $d['contact/email'];

			$data = array(
				'name' => $first_name ,
				'surname' => $last_name ,
				'email' => $email
			);

			if ($user = User::getUserByArray (array ('email' => $email))) {
				User::login ($user['id_user']);

			} else {
				$user_id = User::addUser ($data);
				User::login ($user_id);
			}
			echo "Prijavljen!";
		} else {
			echo ("NOT");
			//user is not logged in
		}
	}
//}
