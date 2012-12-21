<?php
header('Access-Control-Allow-Origin: *');
/*
 * Author: (klemensek@gmail.com)
 */


include_once ("../config/config.php");

if (empty ($_SESSION['uid'])) {
	$returnArr = array (
		'success'		=> 0,
		'description'	=> 'Uporabnik ni prijavljen.'
	);
	echo ($returnArr['description']);
	exit();
}

$user_id = (int)mysqli_real_escape_string($db, $_SESSION['uid']);
$proposal_id    = (int)mysqli_real_escape_string($db, $_POST['proposal_id']);
$right_id       = (int)mysqli_real_escape_string($db, $_POST['right_id']);

$returnArr	= array ();

if (empty ($returnArr) && empty ($proposal_id)) {
	$returnArr = array (
		'success'		=> 0,
		'description'	=> 'Prišlo je do napake.'
	);
}

if (empty ($returnArr) && empty ($right_id)) {
	$returnArr = array (
		'success'		=> 0,
		'description'	=> 'Prišlo je do napake.'
	);
}

if (empty ($returnArr) && (empty ($user_id) || $user_id <= 0)) {
    $returnArr = array (
        'success'       => 0,
        'description'   => 'Uporabnik ni prijavljen.'
    );
}

if (empty ($returnArr)) {

	/* check connection */
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

    
    
    $sqlCheck = "SELECT * " .
        " FROM wg" .
        " WHERE id_proposal = " . $proposal_id .
        " AND id_user = " . $user_id .
        " AND id_right = " . $right_id .
        " LIMIT 1";
    mysqli_query($db, $sqlCheck);
    if (mysqli_affected_rows($db) > 0) {
        $returnArr = array (
            'success'       => -1,
            'description'   => 'Skupini si že pridružen.'
        );
    }
    
    if (empty($returnArr)) {
    	$sql = "
    		INSERT IGNORE INTO
    			wg
    		(`id_proposal`, `id_user`, `id_right`, `timestamp`)
    		VALUES
    		('" . $proposal_id . "', '" . $user_id . "', '" . $right_id .  "', NOW())
    	";
    	mysqli_query ($db, $sql);
    	if (mysqli_affected_rows ($db) <= 0) {
    		$returnArr = array (
    			'success'		=> 0,
    			'description'	=> 'Prišlo je do napake.'
    		);
    	} else {
    		$returnArr = array (
    			'success'		=> 1,
    			'description'	=> 'Prijavljen!'
    		);
    	}
    }

	/* close connection */
	$db->close();
}

echo ($returnArr['description']);
