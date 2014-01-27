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

//$value=utf8_decode($value);
 
 // check target tanguages at least one is needed
if (empty($tlangs)){
    echo "You must select at least one target language to view additional results. So far only the basic navigation is available</br>";
   // $include_html=false;
}

$strInput="";
   for ($i=0; $i<count($tlangs);$i++){
      
          $str='<input type="hidden" value = "'.$tlangs[$i].'" name="tlang[]">';
          $strInput=$strInput.$str;
     
}

   for ($i=0; $i<count($drs);$i++){
      // create a list of hyperlink
      if ($drs[$i]=="igd"){
            $url=$gd.$gds_ita.$gds;
            $res="Italian Geodomain";
            echo $lurl;
    }
        if ($drs[$i]=="egd"){
            $url=$gd.$gds_eng.$gds;
            $res="English Geodomain";
    }
    
    /*   else{
            $url=$drs[$i];
            $res="Mariterm";
            }
      */      
          $str='<a href="'.$url.'" target="_blank">View '.$res.' in LOD</a>&nbsp;' ;
          $strInput=$strInput.$str;
     
}
echo $strInput;
if($include_html){
    $link=GetMyConnection();
    $main_conn=SwitchConnection($lang, $link, 0);
    if ($link && $main_conn){
        if($type==0){
            $res=getSynsetIdsFromWord($main_conn,$WSS_VIEW,$value);
            include('./html/viewresultsfromwords.html.php');
        } else {
            $res=getWordsFromSynsetId($main_conn,$WSS_VIEW,$value);
            include('./html/viewresultsfromsynsetid.html.php');
        }

    }
}
 
?>

 <script>
$(function() {
        $( "#i_tabs_102737660_n_eng").tabs();
        });

</script>