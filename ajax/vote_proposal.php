<?php

/*
 * Author: Marko Bratkovič (marko@lunca.com)
 */

include_once ("../config/config.php");

//	TODO: User check
//	if (empty ($_SESSION['user_id'])) die ('gtfo');
$user_id = 0;

$proposal_id= (int)$_POST['proposal_id'];
$type		= (int)$_POST['type'];

$returnArr	= array ();

if (empty ($returnArr) && empty ($proposal_id)) {
	$returnArr = array (
		'success'		=> 0,
		'description'	=> 'Prišlo je do napake.'
	);
}

if (empty ($returnArr) && empty ($type)) {
	$returnArr = array (
		'success'		=> 0,
		'description'	=> 'Prišlo je do napake.'
	);
}

if (empty ($returnArr)) {

	$mysqli = new mysqli ($dbhost, $dbuser, $dbpassword, $dbname);

	/* check connection */
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

	$vp = ($type == 1) ? 1 : 0;
	$vm = ($type == -1) ? 1 : 0;
	$sql = "
		INSERT INTO
			vote
		(`id_proposal`, `id_user`, `vote_plus`, `vote_minus`, `timestamp`)
		VALUES
		('" . $proposal_id . "', '" . $user_id . "', '" . $vp . "', '" . $vm . "', NOW())
	";
	mysqli_query ($mysqli, $sql);
	if (mysqli_affected_rows ($mysqli) <= 0) {
		$returnArr = array (
			'success'		=> 0,
			'description'	=> 'Pri glasovanju je prišlo do napake.'
		);
	} else {
		$returnArr = array (
			'success'		=> 1,
			'description'	=> 'OK'
		);
	}

	/* close connection */
	$mysqli->close();
}

echo json_encode ($returnArr);
