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
$word="";
$lang="";
$tlangs="";
$type="";
$htype="word";
$value = $_POST['elem'];
$lang=$_POST['lang'];
$type=$_POST['typeofsearch'];
$tlangs=$_POST['tlang'];
$user=$_POST['mynickname'];
$username=$_POST['myusername'];
$passwd=$_POST['mypassword'];
$include_html=false;
?>
<html>
<head>
<title>View</title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
<script src="./js/jquery.treeview.js" type="text/javascript"></script>
<script type="text/javascript" src="./js/md5.js"></script>
</head>
<body>
<?php 
/*
typeof search
0 -> word
1 -> synset
*/
if (trim($value)===""){
    $value = $_GET['elem'];
 }
 
 if (trim($type)===""){
    $type = $_GET['typeofsearch'];
}

 if (trim($lang)===""){
    $lang = $_GET['lang'];
 }
 
 if (trim($user)===""){
    $user = $_GET['mynickname'];
 }

 if (count($tlangs)==0){
    $tlangs = $_GET['tlang'];
}

if ($type==1){
    $htype="synsetid";
}

if($user==""){
    include('./html/loginform.html.php');
} else {
    include('./html/loggeduser.html.php');
    }
//$value=utf8_decode($value);
 
 // check target tanguages at least one is needed
if (empty($tlangs)){
    echo "U DID NOT SELECT</br>";
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
