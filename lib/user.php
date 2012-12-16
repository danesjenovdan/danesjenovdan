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
				id = '" . (int)$user_id . "'
		";
		$result = mysqli_query ($db, $sql);
		if (mysqli_num_rows ($result > 0)) {
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
				email = '" . (int)$email . "'
		";
		$result = mysqli_query ($db, $sql);
		if (mysqli_num_rows ($result > 0)) {
			return mysqli_fetch_assoc ($result);
		}
		return false;
	}

	public static function addUser ($data)
	{
		global $db;

		$vars = array (
			'name'				=> $data['name'],
			'surname'			=> $data['surname'],
			'email'				=> $data['email']
		);
		$sql = "
			INSERT INTO user
				(". implode(', ', array_keys ($vars)) .", timestamp)
			VALUES
				(:". implode(', :', array_keys ($vars)) .", NOW())
		";
		$result = mysqli_query ($db, $sql);
		if (mysqli_affected_rows ($result > 0)) {
			return mysqli_insert_id ($db);
		}
		return false;
	}

}
