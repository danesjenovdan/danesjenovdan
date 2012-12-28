<?php
header('Access-Control-Allow-Origin: *');
/*
 * Author: Marko Bratkovič (marko@lunca.com)
 */

include_once ("../config/config.php");

//if (empty ($_SESSION['uid'])) {
//	$returnArr = array (
//		'success'		=> 0,
//		'description'	=> 'Uporabnik ni prijavljen.'
//	);
//	echo json_encode ($returnArr);
//	exit();
//}
$user_id = (int)$_SESSION['uid'];

$right_id	= (int)$_POST['right_id'];
$title		= (string)$_POST['title'];
$content	= (string)$_POST['content'];

$returnArr	= array ();
$success = 0;
if (empty ($returnArr) && empty ($title)) {
	$returnArr = array (
		'success'		=> 0,
		'description'	=> 'Prosimo, vnesite naziv predloga.'
	);
}

if (empty ($returnArr) && empty ($content)) {
	$returnArr = array (
		'success'		=> 0,
		'description'	=> 'Prosimo, vnesite vsebino predloga.'
	);
}

if (empty ($returnArr)) {

	/* check connection */
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

	$sql = "
		INSERT INTO
			proposal
		(`id_right`, `id_user`, `title`, `text`, `timestamp`)
		VALUES
		('" . $right_id . "', '" . $user_id . "', '" . mysqli_real_escape_string ($db, $title) . "', '" . mysqli_real_escape_string ($db, $content) . "', NOW())
	";
	mysqli_query ($db, $sql);
	if (mysqli_affected_rows ($db) <= 0) {
		$returnArr = array (
			'success'		=> 0,
			'description'	=> 'Pri dodajanju predloga je prišlo do napake. Prosimo, poskusite ponovno ali o tem obvestite urednika.'
		);
	} else {
		$returnArr = array (
			'success'		=> 1,
			'description'	=> 'Hvala, vaš predlog je uspešno dodan in čaka na potrditev objave.'
		);
		$success = 1;
	}

}

/* close connection */
$db->close();

echo $success;
