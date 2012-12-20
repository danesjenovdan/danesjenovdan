<?php
header('Access-Control-Allow-Origin: *');
/*
 * Author: Marko Bratkovič (marko@lunca.com)
 */

include_once ("../config/config.php");

if (empty ($_SESSION['uid'])) {
	$returnArr = array (
		'success'		=> 0,
		'description'	=> 'Uporabnik ni prijavljen.'
	);
	echo json_encode ($returnArr);
	exit();
}
$user_id = (int)mysqli_real_escape_string($db, $_SESSION['uid']);

$argument_id	= (int)mysqli_real_escape_string($db, $_POST['argument_id']);
$type		= (int)mysqli_real_escape_string($db, $_POST['type']);

$returnArr	= array ();

if (empty ($returnArr) && (empty ($argument_id))) {
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

	/* check connection */
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

	$vp = ($type == 1) ? 1 : 0;
	$vm = ($type == -1) ? 1 : 0;
    
    // user can vote once
    $sqlCheck = "SELECT id" .
        " FROM vote" .
        " WHERE id_argument = " . $argument_id .
        " AND id_user = " . $user_id .
        " LIMIT 1";
    mysql_query($db, $sqlCheck);
    if (mysqli_affected_rows($db) > 0) {
        $returnArr = array (
            'success'       => -1,
            'description'   => 'Uporabnik je že glasoval.'
        );
    }

	$sql = "
		INSERT IGNORE INTO
			vote
		(`id_argument`, `id_user`, `vote_plus`, `vote_minus`, `timestamp`)
		VALUES
		('" . $argument_id . "', '" . $user_id . "', '" . $vp . "', '" . $vm . "', NOW())
	";
	mysqli_query ($db, $sql);
	if (mysqli_affected_rows ($db) <= 0) {
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

}

/* close connection */
$db->close();

echo json_encode ($returnArr);
