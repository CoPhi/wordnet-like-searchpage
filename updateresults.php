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

// $newposes
$newposes = (isset($_POST['newposes']) ? $_POST['newposes'] : $_GET['newposes']); 

// $newwords
$newwords = (isset($_POST['newwords']) ? $_POST['newwords'] : $_GET['newwords']); 

// $synsets
 $newsynsets = (isset($_POST['newsynsets']) ? $_POST['newsynsets'] : $_GET['newsynsets']); 

// $newsenses
 $newsenses = (isset($_POST['newsenses']) ? $_POST['newsenses'] : $_GET['newsenses']); 

// newrules
$newrules = (isset($_POST['newrules']) ? $_POST['newrules'] : $_GET['newrules']); 

//newactivities
$newacts = (isset($_POST['newacts']) ? $_POST['newacts'] : $_GET['newacts']); 

//newvalues
$newvs = (isset($_POST['newvs']) ? $_POST['newvs'] : $_GET['newvs']); 

// is everything fine
$nw=count($words);
$neww=count($newwords);
$np=count($poses);
$nsy=count($synsets);
$nse=count($senses);
$nrules=count($rules);
$nacts=count($acts);
$resStr="";
     $insStr="Insert into $USER_ACT_TAB (lang,word,pos,sense,rule,synsetid,activity,value,mapping_lang,mapping_synsetid,username) VALUES (";
     $link=GetMyConnection();
     $conn=SwitchConnection('', $link, 2);
for($i=0; $i<$nw; $i++){
         $mystr=$insStr. "'$lang', '$words[$i]', '$poses[$i]','$senses[$i]', '$rules[$i]','$synsets[$i]', '$acts[$i]','$vs[$i]','$ilang','$synsetid','$user');";
         $int=doInsertRecordsIntoActivity($link,$mystr);
         $resStr=$resStr. "Inserted: '$lang', '$words[$i]', '$poses[$i]','$senses[$i]', '$rules[$i]','$synsets[$i]', '$acts[$i]','$vs[$i]','$ilang','$synsetid','$user', with ret code: $int;</br>";
     }
      echo $resStr."</br></hline></br>";
      
      $resStr="";
     $insStr="Insert into $USER_ACT_TAB (lang,word,pos,sense,rule,synsetid,activity,value,mapping_lang,mapping_synsetid,username) VALUES (";
for($i=0; $i<$neww; $i++){
         $mystr=$insStr. "'$lang', '$newwords[$i]', '$newposes[$i]','$newsenses[$i]', '$newrules[$i]','$newsynsets[$i]', '$newacts[$i]','$newvs[$i]','$ilang','$synsetid','$user');";
         $int=doInsertRecordsIntoActivity($link,$mystr);
         $resStr=$resStr. "Inserted: '$lang', '$newwords[$i]', '$newposes[$i]','$newsenses[$i]', '$newrules[$i]','$newsynsets[$i]', '$newacts[$i]','$newvs[$i]','$ilang','$synsetid','$user', with ret code: $int;</br>";
     }
      echo $resStr."</br></hline></br>";
      
      foreach ($newwords as $nw){
          echo "* $nw </br>";
      }
      
      foreach ($newsenses as $nw){
          echo "* $nw </br>";
          }
?>