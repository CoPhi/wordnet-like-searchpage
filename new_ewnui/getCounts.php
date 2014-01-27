<?php
require("./utils/vars.php");
include("./lib/DB_api.php");
global $debug;
global $database;
global $wn_database;
global $ita_database;
global $side_database;
global $gd;
global $gds;
global $gds_eng;
global$gds_ita;
$lang="";
$tlangs="";
$type="";
$htype="word";
$value = "";
$nickname="";
$username="";
$passwd="";
$include_html=true;

$username = (isset($_POST['myusername']) ? $_POST['myusername'] : $_GET['myusername']); 
$lang = (isset($_POST['lang']) ? $_POST['lang'] : $_GET['lang']); 
$tlangs = (isset($_POST['tlang']) ? $_POST['tlang'] : $_GET['tlang']); 
$drs = (isset($_POST['dr']) ? $_POST['dr'] : $_GET['dr']); 
$nickname = (isset($_POST['mynickname']) ? $_POST['mynickname'] : $_GET['mynickname']); 
$passwd = (isset($_POST['mypassword']) ? $_POST['mypassword'] : $_GET['mypassword']); 
$type = (isset($_POST['typeofsearch']) ? $_POST['typeofsearch'] : $_GET['typeofsearch']); 
$value = (isset($_POST['elem']) ? $_POST['elem'] : $_GET['elem']); 



if ($type==1){
    $htype="synsetid";
}


$link=GetMyConnection();
$main_conn=SwitchConnection($lang, $link, 0);
if ($link && $main_conn){
    if($type==0){
        $res=getCountSynsetIdsFromWord($main_conn,$WSS_VIEW,$value);
        } else {
        $res=getCountWordsFromSynsetId($main_conn,$WSS_VIEW,$value);
        }

}

echo $res[0]['tot'];
 
?>