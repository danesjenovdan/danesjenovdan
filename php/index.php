<?php

// proposal -> arguments
if(RewriteUrl::rru(1)!=''){

	$tpl = new template();
	$w = $tpl->fetch('static/header.html');
	$tpl->set("header", $w);

	$w = $tpl->fetch('static/footer.html');
	$tpl->set("footer", $w);

	$id_proposal = RewriteUrl::rru(1);

	//seznam
	// proposal + votes
	$query="select 
		title,
		text,
		timestamp,
		id_user,
		id_proposal as id,
			(select count(*) from vote where id_proposal=id and vote_plus>0) as vote_plus, 
			(select count(*) from vote where id_proposal=id and vote_minus>0) as vote_minus
			from proposal where id_proposal=$id_proposal and approved=1 ;";
	if ($result = $db->query($query)) {

		$predlog = $result->fetch_object();
		$tpl->set("predlog", $predlog);

		// user for proposal
		$query = "SELECT * FROM  `user` where id_user=$predlog->id_user";
		if ($result = $db->query($query)) {
			$tpl->set("user", $result->fetch_object());
		}

$query = "
SELECT argument.*, user.*
FROM argument
LEFT JOIN user
ON argument.id_user = user.id_user
where argument.id_proposal=$id_proposal and argument.approved=1 and argument.type=1
";

		// argument for
//		$query = "SELECT * FROM  `argument` where id_proposal=$id_proposal and approved=1 and type=1";
$query = "
SELECT argument.*, user.*
FROM argument
LEFT JOIN user
ON argument.id_user = user.id_user
where argument.id_proposal=$id_proposal and argument.approved=1 and argument.type=1
";
		if ($result = $db->query($query)) {
			$data = array();
		    while ($obj = $result->fetch_object()) {
		        $data[] = $obj;
		    }
			$tpl->set("argplus", $data);
		}
		// argument against
//		$query = "SELECT * FROM  `argument` where id_proposal=$id_proposal and approved=1 and type=-1";
$query = "
SELECT argument.*, user.*
FROM argument
LEFT JOIN user
ON argument.id_user = user.id_user
where argument.id_proposal=$id_proposal and argument.approved=1 and argument.type=-1
";		
		if ($result = $db->query($query)) {
			$data = array();
		    while ($obj = $result->fetch_object()) {
		    	$data[] = $obj;
		    }
			$tpl->set("argminus", $data);
		}



		$query = "SELECT * FROM  `document` where id_proposal=$id_proposal and approved=1";
		if ($result = $db->query($query)) {
			$data = array();
		    while ($obj = $result->fetch_object()) {
		        $data[] = $obj;
		    }
			$tpl->set("document", $data);
		}


	}

print	$tpl->fetch('static/predlog.php');
die();
}

// right -> proposals
if(RewriteUrl::rru(0)!=''){

	$tpl = new template();

	$w = $tpl->fetch('static/header.html');
	$tpl->set("header", $w);

	$w = $tpl->fetch('static/footer.html');
	$tpl->set("footer", $w);
	$tpl->set("id", $id_right);


	//print '<h1>pravica</h1>';
	$query = "SELECT * FROM  `right` where id_right = $id_right";

	if ($result = $db->query($query)) {

		$tpl->set("pravica", $result->fetch_object());

		// proposals + votes
		$query="select title,timestamp,id_proposal as id, (select count(*) from vote where
		id_proposal=id and vote_plus>0) as vote_plus, (select count(*) from vote where
		id_proposal=id and vote_minus>0) as vote_minus from proposal where id_right=".$id_right." order by timestamp desc limit 10;";

		if ($result = $db->query($query)) {
			$data = array();
		    while ($obj = $result->fetch_object()) {
		    	$data[] = $obj;
		    }

			$tpl->set("predlogi", $data);

		
		}



		$result->close();
	}

	print $tpl->fetch('static/pravica.php');



}else{
	// index page
	$tpl = new template();
/*
	$w = $tpl->fetch('static/header.html');
	$tpl->set("header", $w);

	$w = $tpl->fetch('static/footer.html');
	$tpl->set("footer", $w);
*/
	print $tpl->fetch('static/landing.html');

}