<?php

Class User {

	public static function getUserById ($user_id)
	{
		global $db;
		$sql = "
			SELECT
				*
			FROM
				user
			WHERE
				id_user = '" . (int)$user_id . "'
		";
		$result = mysqli_query ($db, $sql);
		if (mysqli_num_rows ($result) > 0) {
			return mysqli_fetch_assoc ($result);
		}
		return false;
	}

	public static function getUserByEmail ($email)
	{
		global $db;

		$sql = "
			SELECT
				*
			FROM
				user
			WHERE
				email = '" . (string)$email . "'
		";
		$result = mysqli_query ($db, $sql);
		if (mysqli_num_rows ($result) > 0) {
			return mysqli_fetch_assoc ($result);
		}
		return false;
	}

	public static function addUser ($vars)
	{
		global $db;

		$sql = "
			INSERT IGNORE INTO user
				(". implode(', ', array_keys ($vars)) .", timestamp)
			VALUES
				('". implode('\', \'', array_values ($vars)) ."', NOW())
		";
		mysqli_query ($db, $sql);
		if (mysqli_affected_rows ($db) > 0) {
			return mysqli_insert_id ($db);
		}
		return false;
	}

	public static function login ($user_id)
	{
		$user = User::getUserById ($user_id);
		$_SESSION['uid'] = $user_id;
		$_SESSION['user'] = $user;
	}

	public static function logout ()
	{
		unset ($_SESSION['uid']);
		unset ($_SESSION['user']);
	}

}
