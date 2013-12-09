<?php
require("./utils/vars.php");
include("./lib/DB_api.php");
include("./lib/wnsp_api.php");
//echo $_SERVER['DOCUMENT_ROOT'];
global $debug;
global $database;
global $wn_database;
global $ita_database;
global $side_database;
$words="";
$lang="";
$ilang="";
$senses="";
$poses="";
$vs=array();
$synsetid="";
$synsets=""; 
$rules="";
$user=""; 
$acts=""; 
$go_ahead=true;
$include_html=false;
$field_=$_GET['field'];
$term=$_GET['term'];
$ilang=$_GET['ilang'];
 $link=GetMyConnection();
$conn=SwitchConnection($ilang, $link, 0);

function getWords($conn, $WORDS_TAB,$term){
    $result=getWordsList($conn, $WORDS_TAB,$term);
    $temp= json_encode($result);
    $str='{"low": ';
    $str=$str.$temp."}";
echo $str;
//echo $result;
}

switch($field_){
	case 'searchTxt':	
		$result=getWords($conn, $WORDS_TAB,$term);
		$column="word";
	break;
    }
?>