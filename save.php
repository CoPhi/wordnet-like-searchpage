<?php
?>
<html>
<head>
<link href="./css/global.css" rel="stylesheet" type="text/css">
<meta HTTP-EQUIV="content-type" CONTENT="text/html; charset=UTF-8">
<title>Search page for wordnets (save results)</title>
</head>
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
?>
<html>
<head>
<title>View</title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
</head>
<body>
<?php 
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

// is everything fine
$nw=count($words);
$np=count($poses);
$nsy=count($synsets);
$nse=count($senses);
$nrules=count($rules);
$nacts=count($acts);

if ($nw==$np AND $np==$nsy AND $nsy==$nse AND $nse=$rules AND $nrules==$nacts){
    for($i=0; $i<$nw; $i++){
        $v= isset($_POST['v_'.$i])?$_POST['v_'.$i]:$_GET['v_'.$i];
        //echo $v.", ";
        array_push($vs,$v);
        }
    
    } else {
        $go_ahead=false;
        }
 if ($go_ahead){
     
     /*
     `lang`  CHAR(3) NOT NULL,
  `word` varchar(255) NOT NULL,
  `pos` varchar(3) NOT NULL,
  `sense` int(11) NOT NULL,
  `rule` int(11) NOT NULL,
  `synsetid` bigint(20) NOT NULL,
  `mapping_lang` Char(3) NOT NULL,
  `mapping_synsetid` bigint(20) NOT NULL,
     */
     $resStr="";
     $insStr="Insert into $USER_ACT_TAB (lang,word,pos,sense,rule,synsetid,activity,value,mapping_lang,mapping_synsetid,username) VALUES (";
     $link=GetMyConnection();
     $conn=SwitchConnection('', $link, 2);
     for($i=0; $i<$nw; $i++){
         $mystr=$insStr. "'$lang', '$words[$i]', '$poses[$i]','$senses[$i]', '$rules[$i]','$synsets[$i]', '$acts[$i]','$vs[$i]','$ilang','$synsetid','$user');";
         $int=doInsertRecordsIntoActivity($link,$mystr);
         
         $resStr=$resStr. "Inserted: '$lang', '$words[$i]', '$poses[$i]','$senses[$i]', '$rules[$i]','$synsets[$i]', '$acts[$i]','$vs[$i]','$ilang','$synsetid','$user', with ret code: $int;</br>";
     }
      echo $resStr;
     }
?>
</body>
</html>
