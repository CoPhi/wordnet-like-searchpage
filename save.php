<?php
?>
<html>
<head>
<link href="./css/global.css" rel="stylesheet" type="text/css">
<meta HTTP-EQUIV="content-type" CONTENT="text/html; charset=UTF-8">
<title>Search page for wordnets (view results)</title>
</head>
<?php 
require("./utils/vars.php");
include("./lib/DB_api.php");
global $debug;
global $database;
global $wn_database;
global $ita_database;
global $side_database;
$words="";
$lang="";
$senses="";
$poses="";
$vs=array();
$synsetid="";
$words = $_POST['words'];
$poses=$_POST['poses'];
$synsets=$_POST['synsets'];
$senses=$_POST['senses'];
$lang=$_POST['lang'];
$synsetid=$_POST['synsetid'];
$user=$_POST['user'];
$go_ahead=true;
$include_html=false;
?>
<html>
<head>
<title>View</title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
</head>
<body>
<?php 
/*
typeof search
0 -> word
1 -> synset
*/

// Are GET parameters
// $lang
if (trim($lang)===""){
    $lang = $_GET['lang'];
 }
 
 // $synsetid
 if (trim($synsetid)===""){
    $synsetid = $_GET['synsetid'];
}
//$user
 if (trim($user)===""){
    $user = $_GET['user'];
 }
 
// $poses
 if (count($poses)==0){
    $poses = $_GET['poses'];
}

// $words
 if (count($words)==0){
    $words = $_GET['words'];
}

// $synsets
 if (count($synsets)==0){
    $synsets = $_GET['synsets'];
}

// $senses
 if (count($senses)==0){
    $senses = $_GET['senses'];
}
// is everything fine
$nw=count($words);
$np=count($poses);
$nsy=count($synsets);
$nse=count($senses);

if ($nw==$np AND $np==$nsy AND $nsy==$nse){
    for($i=0; $i<$nw; $i++){
        $v= isset($_POST['v_'.$i])?$_POST['v_'.$i]:$_GET['v_'.$i];
        echo $v.", ";
        array_push($vs,$v);
        }
    
    } else {
        $go_ahead=false;
        }
 if ($go_ahead){
     echo "Saving: </br>";
     for($i=0; $i<$nw; $i++){
         echo "Line $i: $words[$i], $senses[$i],$poses[$i], $synsets[$i], $vs[$i],$lang,$synsetid,$user</br>";
         }
     }
 // check target tanguages at least one is needed
if (empty($tlangs)){
    
}
for ($i=0; $i<count($tlangs); $i++){
   // echo "Tlang $i=$tlangs[$i]";
}

if ($debug==1){
    echo "Searching for type: $htype and language: $lang and value $value</br>";
}
$link=GetMyConnection();
$main_conn=SwitchConnection($lang, $link, 0);
if ($link && $main_conn){
    $res=getSynsetIdsFromWord($main_conn,$WSS_VIEW,$value);
     include('./html/viewresults.html.php');
     }
?>
</body>
</html>
