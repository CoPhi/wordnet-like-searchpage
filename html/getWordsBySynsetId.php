<?php
/*
ADDING THE INNER DIVS
*/
require_once("./utils/tablelist.php");
require_once("./utils/vars.php");
require_once("./lib/DB_api.php");
$param=$_POST['id'];
$lang=$_POST['lang'];
$link=GetMyConnection();
$main_conn=SwitchConnection($lang, $link, 0);
$result=getWordsFromSynsetId($main_conn,$WSS_VIEW,$param);
$myDefDiv='<div id="div_result_sub_res_def_'.$param.'_'.$lang.'" class="divResultSubDef">';
$myDefDiv=$myDefDiv."Definition: $definition </br></div>";
$myLowDiv='<form><div id="div_result_sub_res_low_'.$param.'_'.$lang.'" class="divResultSubWords">';
$myLowDiv=$myLowDiv. "List of words</br>";
for ($i=0; $i<count($result); $i++){
    $theword=$result[$i]['lemma'];
    $theword4id=$result[$i]['lemma'];;
    $thepos=$result[$i]['pos'];
    $thesense=$result[$i]['sensenum'];
    $str =$theword."  (".$thesense.")  (".$thepos.") XXX";

    $str='<div id="div_result_sub_low_'.$param.'_'.$lang.'_'.$theword4id.'" class="divResultSubLow">'.$str.'</div>' ;
    $myLowDiv=$myLowDiv." ".$str."</div><input type='button' value=CC/></form>";
}
$myStr=$myDefDiv."<p></p>".$myLowDiv;
echo $myStr."<p></p>";
$result=getRelsFromSynsetId($main_conn,$SEMLINK_TAB,$REL_TAB,$SYNSETS_TAB,$param);
$myRelDiv='<div id="div_result_sub_res_rel_'.$param.'_'.$lang.'" class="divResultSubRel">';
$myRelDiv=$myRelDiv."List of Relations</br> Source: ".$param."</br>";
for ($i=0; $i<count($result); $i++){
    $target=$result[$i]['target'];
    $target_1=$result[$i]['target'];
   
    $rel=$result[$i]['relation'];
     $relid=$result[$i]['linkid'];
    $tdef=$result[$i]['tdefinition'];
    $tdef=addslashes($tdef);
    $tpos=$result[$i]['tpos'];
     $target= "<a href=\"javascript:showWordsBySynsetId('$target','$lang','$relid',0);\">".$target."</a>";
    $str=" &nbsp; &nbsp; &nbsp; &nbsp; ".$rel." ".$target. " (".$tpos. ")  ".$tdef;
    $str='<div id="div_result_sub_res_lor_'.$target_1.'_'.$lang.'_'.$relid.'" class="divResultSubLor">'.$str.'</div>' ;
    $myRelDiv=$myRelDiv." ".$str."</div>";
    echo $str;
    }
?>
