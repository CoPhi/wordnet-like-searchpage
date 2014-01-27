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
$field_=$_POST['field'];
$term=$_POST['term'];
$ilang=$_POST['ilang'];
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

function getRels($conn, $REL_TAB,$term){
    $result=getRelsList($conn, $REL_TAB,$term);
    $temp= json_encode($result);
    $str='{"lor": ';
    $str=$str.$temp."}";
echo $str;
//echo $result;
}

function getPoses($conn, $POS_TAB,$term){
    $result=getPosList($conn, $POS_TAB,$term);
    $temp= json_encode($result);
    $str='{"pos": ';
    $str=$str.$temp."}";
echo $str;
//echo $result;
}

function getSynsetByDefinition($conn, $SYNSETS_TAB,$term){
    $result=getSynsetListByDefinition($conn, $SYNSETS_TAB,$term);
    $temp= json_encode($result);
    $str='{"syn": ';
    $str=$str.$temp."}";
echo $str;
//echo $result;
}

function getSynsetById($conn, $SYNSETS_TAB,$term){
    $result=getSynsetListById($conn, $SYNSETS_TAB,$term);
    $temp= json_encode($result);
    $str='{"syn": ';
    $str=$str.$temp."}";
echo $str;
//echo $result;
}
switch($field_){
	case 'searchTxt':	
		$result=getWords($conn, $WORDS_TAB,$term);
		$column="word";
	break;
    
    case 'searchRel':	
		$result=getRels($conn, $REL_TAB,$term);
		$column="rel";
	break;
    
    case 'searchPos':	
		$result=getPoses($conn, $POS_TAB,$term);
		$column="pos";
	break;
    
     case 'searchDef':	
		$result=getSynsetByDefinition($conn, $SYNSETS_TAB,$term);
		$column="syn";
	break;
    case 'searchId':	
		$result=getSynsetById($conn, $SYNSETS_TAB,$term);
		$column="syn";
	break;

    }
?>