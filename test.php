<?php
require("./utils/vars.php");
include("./lib/DB_api.php");
include("./lib/wnsp_api.php");
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
// input language
 $ilang = (isset($_POST['ilang']) ? $_POST['ilang'] : $_GET['ilang']); 
// $lang
 $lang = (isset($_POST['lang']) ? $_POST['lang'] : $_GET['lang']); 
 
 // $synsetid
 $synsetid = (isset($_POST['synsetid']) ? $_POST['synsetid'] : $_GET['synsetid']); 
 
//$user
 $user = (isset($_POST['user']) ? $_POST['user'] : $_GET['user']); 

// $poses
$poses = (isset($_POST['poses']) ? $_POST['poses'] : $_GET['poses']); 

// $words
$words = (isset($_POST['words']) ? $_POST['words'] : $_GET['words']); 

// $synsets
 $synsets = (isset($_POST['synsets']) ? $_POST['synsets'] : $_GET['synsets']); 

// $senses
 $senses = (isset($_POST['senses']) ? $_POST['senses'] : $_GET['senses']); 

// rules
$rules = (isset($_POST['rules']) ? $_POST['rules'] : $_GET['rules']); 

//activities
$acts = (isset($_POST['acts']) ? $_POST['acts'] : $_GET['acts']); 

//values
$vs = (isset($_POST['vs']) ? $_POST['vs'] : $_GET['vs']); 
// is everything fine
$nw=count($words);
$np=count($poses);
$nsy=count($synsets);
$nse=count($senses);
$nrules=count($rules);
$nacts=count($acts);

for($i=0; $i<$nw; $i++){
         $mystr=$insStr. "'$lang', '$words[$i]', '$poses[$i]','$senses[$i]', '$rules[$i]','$synsets[$i]', '$acts[$i]','$vs[$i]','$ilang','$synsetid','$user');";
         $int=doInsertRecordsIntoActivity($link,$mystr);
         $resStr=$resStr. "Inserted: '$lang', '$words[$i]', '$poses[$i]','$senses[$i]', '$rules[$i]','$synsets[$i]', '$acts[$i]','$vs[$i]','$ilang','$synsetid','$user', with ret code: $int;</br>";
     }
      echo $resStr;
?>