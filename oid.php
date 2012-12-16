<?php

# Logging in with Google accounts requires setting special identity, so this example shows how to do it.
require 'lib/openid.php';
//try {
	# Change 'localhost' to your domain name.
	$openid = new LightOpenID($_SERVER['HTTP_HOST']);
	if(!$openid->mode) {
		if(isset($_GET['login'])) {
			$openid->identity = 'https://www.google.com/accounts/o8/id';
			$openid->required = array('contact/email' , 'namePerson/first' , 'namePerson/last' , 'pref/language' , 'contact/country/home');
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

			$first_name = $d['namePerson/first'];
			$last_name = $d['namePerson/last'];
			$email = $d['contact/email'];

			$data = array(
				'name' => $first_name ,
				'surname' => $last_name ,
				'email' => $email ,
			);

			//	TODO: Write to DB
			if ($user_id = User::getUserByEmail($email)) {
				//	TODO: Login
				$_SESSION['uid'] = $user_id;
			} else {
				$user_id = User::addUser ($data);
				$_SESSION['uid'] = $user_id;
			}
		}
		else
		{
			die ("NOT");
			//user is not logged in
		}
	}
//}
