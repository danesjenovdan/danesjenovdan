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
	echo json_encode ($returnArr);
	exit();
}
$user_id = (int)$_SESSION['uid'];

if (!empty($_FILES)) {
	$tempFile = $_FILES['documentfile']['tmp_name'];
	$targetPath = '../documents/';
	$targetFile =  $_FILES['documentfile']['name'];


	$fileParts  = pathinfo($_FILES['documentfile']['name']);

		if(move_uploaded_file($tempFile,$targetFile)){
			
			
			$user_id = (int)$_SESSION['uid'];

			$right_id	= (int)$_POST['right_id'];
			$proposal_id		= (string)$_POST['proposal_id'];
			$documentname	= (string)$_POST['documentname'];
			$file_name_file = str_replace($_SERVER['DOCUMENT_ROOT'],'',$targetFile);

			$returnArr	= array ();

			if (empty ($returnArr) && empty ($documentname)) {
				$returnArr = array (
					'success'		=> 0,
					'description'	=> 'Prosimo, vnesite naziv imena datoteke.'
				);
			}

			if (empty ($returnArr)) {

				$sql = "
					INSERT INTO
						document
					(`id_proposal`, `id_user`, `title`, 
						`path`, 
						`type`, 
						`size`, 
						`approved`,
						`timestamp`)
					VALUES
					('" . $proposal_id . "', '" . $user_id . "', '" . mysqli_real_escape_string ($db, $documentname) . "', '" 
						. mysqli_real_escape_string ($db, $file_name_file) . "', '"
						. mysqli_real_escape_string ($db, $fileParts['extension']) . "', '"
						. mysqli_real_escape_string ($db, $_FILES['documentfile']['size']) . "', '0', NOW())
				";
				
				mysqli_query ($db, $sql);
				if (mysqli_affected_rows ($db) <= 0) {
					$returnArr = array (
						'success'		=> 0,
						'description'	=> 'napaka'
					);
				} else {
					$returnArr = array (
						'success'		=> 1,
						'description'	=> 'Hvala, vaš dokument je uspešno dodan in čaka na potrditev objave.'
					);
				}

			}

			//echo json_encode ($returnArr);
			echo ($returnArr['description']);


		}else{
			print('napaka');
		}
}