<?php
/*
ADDING THE INNER DIVS
*/
require_once("./utils/tablelist.php");
require_once("./utils/vars.php");
require_once("./lib/DB_api.php");
$param=$_POST['id'];
$word=$_POST['value'];
$definition=$_POST['def'];
$lang=$_POST['lang'];
$user="riccardo.delgratta@gmail.com";
$dir="";
if ($lang=="ara")
    $dir="rtl";
$link=GetMyConnection();
$main_conn=SwitchConnection($lang, $link, 0);
$result=getWordsFromSynsetId($main_conn,$WSS_VIEW,$param);
$myDefDiv='<div id="div_result_sub_def_'.$param.'_'.$lang.'" class="divResultSubDef">';
$myDefDiv=$myDefDiv."Definition: $definition </br></div>";
$myLowDiv='<div id="div_result_sub_low_'.$param.'_'.$lang.'" class="divResultSubWords" >';
//$myLowDiv=$myLowDiv. "List of words</br>";
for ($i=0; $i<count($result); $i++){
    $theword=$result[$i]['lemma'];
    $theword4id=$result[$i]['lemma'];
    $thepos=$result[$i]['pos'];
    $thesense=$result[$i]['sensenum'];
    if ($theword===$word)
       $str=$theword="<div dir='$dir'><b><i>".$theword." </i></b> (".$thesense.")  (".$thepos.")</div>";
       
    else{
        $str ="<div dir='$dir'>".$theword."  (".$thesense.")  (".$thepos.")</div> ";
}
   //$strInput="<input type='text' value='$theword4id' /><input type='text' value='$thesense' /><input type='text' value='$thepos' /> ";
   $strInput=""; //<input type='text' value='$theword4id' /><input type='text' value='$thesense' /><input type='text' value='$thepos' /> ";
    $str='<div id="div_result_sub_low_'.$param.'_'.$lang.'_'.$theword4id.'" class="divResultSubLow" >'.$str.' '.$strInput.'</div>' ;
    $myLowDiv=$myLowDiv." ".$str."</div>";
}
$myStr=$myDefDiv."<p></p>".$myLowDiv;
echo $myStr."<p></p>";
$result=getRelsFromSynsetId($main_conn,$SEMLINK_TAB,$REL_TAB,$SYNSETS_TAB,$param);
$myRelDiv='<div id="div_result_sub_rel_'.$param.'_'.$lang.'" class="divResultSubRel" >';
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
    $str='<div id="div_result_sub_lor_'.$target_1.'_'.$lang.'_'.$relid.'" class="divResultSubLor">'.$str.'</div>' ;
    $myRelDiv=$myRelDiv." ".$str."</div>";
    echo $str;
}
if ($lang=="grc"){
$result=getWordsFromSynsetId($main_conn,$WSS_VIEW,$param);
$myDefDiv='<div id="div_result_sub_form_'.$param.'_'.$lang.'" class="divResultSubDef">';
$myDefDiv=$myDefDiv."Manage Results </br></div>";
$strForm0="<form method='get' action='save.php'>";
$strForm1="<input type='Submit' value='Update'></form>";
$myLowDiv='<div id="div_result_sub_form_low_'.$param.'_'.$lang.'" class="divResultSubWords">';
 
//$myLowDiv=$myLowDiv. "List of words</br>";
// creating a different connection
$side_conn=SwitchConnection($lang, $link, 1);
for ($i=0; $i<count($result); $i++){
      $chk0="";
      $chk1="";
      $chk2="";
    $theword=$result[$i]['lemma'];
    $theword4id=$result[$i]['lemma'];
    $thepos=$result[$i]['pos'];
    $thesense=$result[$i]['sensenum'];
    $validate=getFeatsFromSynsetId($side_conn,$GRCWS_TAB,$GRCMAP_TAB,'in',$param,$theword4id);
    if (count($validate) == 0 OR count($validate) >1){
        $value=-1;
} else {
    $value=$validate[0]['feat_val'];
    $synsetid=$validate[0]['synsetid'];
    if ($value==0){
        $chk0="checked";
          $chk1="";
      $chk2="";
    }
    if ($value==1){
        $chk1="checked";
          $chk0="";
      $chk2="";
    }
    if ($value==2){
        $chk2="checked";
          $chk1="";
      $chk0="";
        }
    }

    $str =$theword."  (".$thesense.")  (".$thepos.") ";
 $strInput="<input type='hidden' value='$theword4id' name='words[]' /><input type='hidden' value='$thesense'  name='senses[]'/><input type='hidden' value='$thepos' name='poses[]'/> 
   <input type='hidden' value='$param' name='synsets[]'/>";
   $strRadio="&nbsp;Validate: <input type='radio' name='v_".$i."' value='0'  id='radio_result_sub_form_low_$param"."_".$lang."_".$theword4id."_0' $chk0/>&nbsp;0</input>";
   $strRadio=$strRadio."&nbsp;&nbsp;<input type='radio' name='v_".$i."' value='1'  id='radio_result_sub_form_low_$param"."_".$lang."_".$theword4id."_1' $chk1/>&nbsp;1</input>";
    $strRadio=$strRadio."&nbsp;&nbsp;<input type='radio' name='v_".$i."' value='2'  id='radio_result_sub_form_low_$param"."_".$lang."_".$theword4id."_2' $chk2/>&nbsp;2</input></br>";
  /* $strInput="<input type='text' value='$theword4id' name='TXT_result_sub_form_low_name_$param"."_".$lang."_".$theword4id."' /><input type='text' value='$thesense'  name='TXT_result_sub_form_low_sense_$param"."_".$lang."_".$theword4id."'/><input type='text' value='$thepos' name='TXT_result_sub_form_low_pos_$param"."_".$lang."_".$theword4id."'/> 
   <input type='text' value='$param' name='TXT_result_sub_form_low_synset_$param"."_".$lang."_".$theword4id."'/>";
   $strRadio="&nbsp;Validate: <input type='radio' name='IN_result_sub_form_low_$param"."_".$lang."_".$theword4id."_0' value='0'  id='radio_result_sub_form_low_$param"."_".$lang."_".$theword4id."_0' $chk0/>&nbsp;0</input>";
   $strRadio=$strRadio."&nbsp;&nbsp;<input type='radio' name='radio_result_sub_form_low_$param"."_".$lang."_".$theword4id."_1' value='1'  id='radio_result_sub_form_low_$param"."_".$lang."_".$theword4id."_1' $chk1/>&nbsp;1</input>";
    $strRadio=$strRadio."&nbsp;&nbsp;<input type='radio' name='radio_result_sub_form_low_$param"."_".$lang."_".$theword4id."_2' value='2'  id='radio_result_sub_form_low_$param"."_".$lang."_".$theword4id."_2' $chk2/>&nbsp;2</input></br>";
  */
    $strInput=$strInput. " ".$strRadio;
    $str='<div id="div_result_sub_form_low_'.$param.'_'.$lang.'_'.$theword4id.'" class="divResultSubLow">'.$str.' '.$strInput.'</div>' ;
    $myLowDiv=$myLowDiv." ".$str."</div>";
}
$myStr=$myDefDiv."<p></p>".$myLowDiv;
echo "<p></p>".$strForm0.$myStr."<input type = 'hidden' name = 'synsetid' value='$synsetid'><input type = 'hidden' name = 'lang' value='$lang'><input type = 'hidden' name = 'user' value='$user'>".$strForm1."<p></p>";
}
?>
