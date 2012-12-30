<?php

session_start();

include('config/config.php');
include('lib/class.template.php');

class RewriteUrl {
	public function rru($i){
		$m = explode('/',@strtolower($_GET['m']));
		return (isset($i)) ? @$m[$i] : @$m[0];
	}
}

$rights = array();

$rights['pravica-do-dobrega-zivljenja-in-solidarne-druzbe']=1;
$rights['pravica-do-skupnega']=2;
$rights['pravica-do-dobre-politicne-oblasti']=3;
$rights['pravica-do-narave']=4;
$rights['pravica-do-cloveku-prijazne-ekonomije']=5;
$rights['pravica-do-vkljucenosti']=6;
$id_right = @$rights[RewriteUrl::rru(0)];



switch (strtolower(RewriteUrl::rru(0)))
{
	case 'predlog':
	//	include('php/proposal.php');
	break;

	default:
		include('php/index.php');
	break;
}
die();

