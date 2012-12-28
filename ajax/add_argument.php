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
$user_id = (int)$_POST['uid'];

$proposal_id	= (int)$_POST['proposal_id'];
$type			= (int)$_POST['type'];
//$title			= (string)$_POST['title'];
$content		= (string)$_POST['content'];

$returnArr	= array ();
$success = 0;

//if (empty ($returnArr) && empty ($title)) {
//	$returnArr = array (
//		'success'		=> 0,
//		'description'	=> 'Prosimo, vnesite naziv argumenta.'
//	);
//}

if (empty ($returnArr) && empty ($content)) {
	$returnArr = array (
		'success'		=> 0,
		'description'	=> 'Prosimo, vnesite vsebino argumenta.'
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
			argument
		(`id_proposal`, `id_user`, `type`, `text`, `timestamp`)
		VALUES
		('" . $proposal_id . "', '" . $user_id . "', '" . $type . "', '" . mysqli_real_escape_string ($db, $content) . "', NOW())
	";
	mysqli_query ($db, $sql);
	if (mysqli_affected_rows ($db) <= 0) {
		$returnArr = array (
			'success'		=> 0,
			'description'	=> 'Pri dodajanju argumenta je prišlo do napake. Prosimo, poskusite ponovno ali o tem obvestite urednika.'
		);
	} else {
		$returnArr = array (
			'success'		=> 1,
			'description'	=> 'Hvala, vaš argument je uspešno dodan in čaka na potrditev objave.'
		);
		$success = 1;
	}

}

/* close connection */
$db->close();

echo $success;
