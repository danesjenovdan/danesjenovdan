<?php

/* Connects to database and returns list of proposals in ajax string.
 *
 * usage:
 * list_proposals.php?no=number_of_proposals&sortby=date|importance
 *
 * if no arguments are given defaults are taken. no=10, sortby=date
 *
 * returns: ajax string with fields:
 * id: id number in the database
 * title: title of the proposal
 * vote_plus: number of positive votes
 * vote_minus: number of negative votes
 *
 *
 * Written by: Samo Penic <samo.penic@gmail.com>
 */

require("../config/config.php");

// Checking and setting defaults if necessary.
if(isset($_REQUEST['id'])){
    $id=intval($_REQUEST['id']);
}
else {
    die();
}
if(isset($_REQUEST['no'])){
    $number=intval($_REQUEST['no']);
}
else {
    $number=10;
}

if(!isset($_REQUEST['sortby']) || $_REQUEST['sortby']=="timestamp"){
    $sortby="timestamp";
}
else {
    $sortby="importance";
}

if(!isset($_REQUEST['order']) || $_REQUEST['order']=="desc"){
    $order="desc";
}
else {
    $order="asc";
}
$query="select title,timestamp,id_proposal as id, (select count(*) from vote where
id_proposal=id and vote_plus>0) as vote_plus, (select count(*) from vote where
id_proposal=id and vote_minus>0) as vote_minus from proposal where id_right=".$id." order by ".$sortby ." ".$order." limit ".$number.";";

//echo $query;
$array=array();;
if ($result = $db->query($query)) {
    while($obj=$result->fetch_object()){
    array_push($array,$obj);
}
    echo json_encode($array);    
    $result->close();
}

$db->close();
?>
