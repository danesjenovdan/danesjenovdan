<?php 
	setcookie("voted", "1", time()+1);
	header("Location: http://danesjenovdan.si/mobile");
?>