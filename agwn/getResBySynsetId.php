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
$type=$_POST['type'];
$lang=$_POST['lang'];
$ilang=$_POST['ilang'];
$user=$_POST['username'];
$nick=$_POST['nickname'];
$pos=$_POST['pos'];
$table=$lang."Map";
$dir="";
if ($lang=="ara")
    $dir="rtl";
// $ilang='eng' ;

// play with features
$link=GetMyConnection();

$maxnumofnewwords=3;

/*
getting xPosRule for type=1
*/
if($type==1){
    $myconn=SwitchConnection($lang, $link, 1);
    if ($lang !='eng'){
        $xres=getMappedSynsetFromTargetSynset($myconn,$table,$param, $pos);
        $syn1=$xres[0]['synsetid'];
    } else {
        $syn1=$param;
        }
    
    $myconn=SwitchConnection($ilang, $link, 0);
    $xres=getDefPosFromSynsetId($myconn,$SYNSETS_TAB,$syn1);
    $pos1=$xres[0]['pos'];
   // echo "$param, $pos, $syn1, $pos1";
    // get the rule
    $myconn=SwitchConnection($lang, $link, 1);
    $xres=getXPosGenerationRule($myconn,$XPOSRULE_TAB,$lang,$pos1, $pos);
    $selstr="";
    $strOption="";
    if (count($xres)>0){
        $selstr="<select name = 'rules[]'><option value='No Rule'>Please choose a rule</option>";
        $newSelstr="<select name = 'newrules[]'><option value='No Rule'>Please choose a rule</option>";
        for ($i=0; $i<count($xres); $i++){
            $rule= $xres[$i]['rule'];
            $strOption=$strOption."<option value = '$rule'>".$rule."</option>";
        }
        $selstr=$selstr.$strOption. "</select>";
        $newSelstr=$newSelstr.$strOption. "</select>";
        }
//echo $selstr;
     
}
$main_conn=SwitchConnection($lang, $link, 0);
$result=getWordsFromSynsetId($main_conn,$WSS_VIEW,$param);
$myDefDiv='<div id="div_result_sub_def_'.$param.'_'.$lang.'" class="divResultSubDef">';
$myDefDiv=$myDefDiv."Definition: $definition </br></div>";
$myLowDiv='<div id="div_result_sub_low_'.$param.'_'.$lang.'" class="divResultSubWords" >';
$disabled="disabled";
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
   // add the validation of relation 
   $strRelRadio="";
   if ($type==1){
        $strRelRadio="&nbsp;<input type='radio' name='rv_$param"."_".$lang."_".$i."' value='0'  id='radio_result_sub_form_v_rel_$param"."_".$lang."_".$i."_0' />&nbsp;0</input>";
        $strRelRadio=$strRelRadio."&nbsp;&nbsp;<input type='radio' name='rv_$param"."_".$lang."_".$i."' value='1'  id='radio_result_sub_form_v_rel_$param"."_".$lang."_".$i."_1' />&nbsp;1</input>";
        $strRelRadio=$strRelRadio."&nbsp;&nbsp;<input type='radio' name='rv_$param"."_".$lang."_".$i."'  value='2'  id='radio_result_sub_form_v_rel_$param"."_".$lang."_".$i."_2'  checked />&nbsp;2</input>";
    }
    $rel=$result[$i]['relation'];
     $relid=$result[$i]['linkid'];
    $tdef=$result[$i]['tdefinition'];
    $tdef=addslashes($tdef);
    $tpos=$result[$i]['tpos'];
     $target= "<a href=\"javascript:showWordsBySynsetId('$target','$lang','$relid',0);\">".$target."</a>";
    $str=" &nbsp; &nbsp;" .$strRelRadio. " &nbsp; &nbsp; ".$rel." ".$target. " (".$tpos. ")  ".$tdef;
    $str='<div id="div_result_sub_lor_'.$target_1.'_'.$lang.'_'.$relid.'" class="divResultSubLor">'.$str.'</div>' ;
    $myRelDiv=$myRelDiv." ".$str."</div>";
    echo $str;
}
if ($type==1){
    if ($nick!="")
        $disabled="";
$result=getWordsFromSynsetId($main_conn,$WSS_VIEW,$param);
$myDefDiv='<div id="div_result_sub_form_'.$param.'_'.$lang.'" class="divResultSubDef">';
$myDefDiv=$myDefDiv."Manage Results </br></div>";
$strForm0="<form method='POST' action='save.php' id='form_$lang' name='form_$lang'>";

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
    if ($lang != 'eng')
        $validate=getFeatsFromSynsetId($side_conn,$GRCWS_TAB,$GRCMAP_TAB,'in',$param,$theword4id);
  /*  if (count($validate) == 0 OR count($validate) >1){
        $value=2;
} else {
    */
    if (count($validate) == 0 OR count($validate) >1){
       $chk0="checked";
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
   <input type='hidden' value='$param' name='synsets[]'/><input type='hidden' value='Validate' name='acts[]'/>";
   $strRadio="&nbsp;<input type='radio' name='v_".$i."' value='0'  id='radio_result_sub_form_low_$param"."_".$lang."_".$theword4id."_0' $chk0/>&nbsp;0</input>";
   $strRadio=$strRadio."&nbsp;&nbsp;<input type='radio' name='v_".$i."' value='1'  id='radio_result_sub_form_low_$param"."_".$lang."_".$theword4id."_1' $chk1/>&nbsp;1</input>";
    $strRadio=$strRadio."&nbsp;&nbsp;<input type='radio' name='v_".$i."' value='2'  id='radio_result_sub_form_low_$param"."_".$lang."_".$theword4id."_2' $chk2/>&nbsp;2</input>";
  /* $strInput="<input type='text' value='$theword4id' name='TXT_result_sub_form_low_name_$param"."_".$lang."_".$theword4id."' /><input type='text' value='$thesense'  name='TXT_result_sub_form_low_sense_$param"."_".$lang."_".$theword4id."'/><input type='text' value='$thepos' name='TXT_result_sub_form_low_pos_$param"."_".$lang."_".$theword4id."'/> 
   <input type='text' value='$param' name='TXT_result_sub_form_low_synset_$param"."_".$lang."_".$theword4id."'/>";
   $strRadio="&nbsp;Validate: <input type='radio' name='IN_result_sub_form_low_$param"."_".$lang."_".$theword4id."_0' value='0'  id='radio_result_sub_form_low_$param"."_".$lang."_".$theword4id."_0' $chk0/>&nbsp;0</input>";
   $strRadio=$strRadio."&nbsp;&nbsp;<input type='radio' name='radio_result_sub_form_low_$param"."_".$lang."_".$theword4id."_1' value='1'  id='radio_result_sub_form_low_$param"."_".$lang."_".$theword4id."_1' $chk1/>&nbsp;1</input>";
    $strRadio=$strRadio."&nbsp;&nbsp;<input type='radio' name='radio_result_sub_form_low_$param"."_".$lang."_".$theword4id."_2' value='2'  id='radio_result_sub_form_low_$param"."_".$lang."_".$theword4id."_2' $chk2/>&nbsp;2</input></br>";
  */
  
    $strInput="&nbsp;&nbsp;".$strRadio." ".$str."&nbsp;".$strInput. "  ".$selstr."</br>";
    $str='<div id="div_result_sub_form_low_'.$param.'_'.$lang.'_'.$theword4id.'" class="divResultSubLow">'.' '.$strInput. '</div>' ;
    $myLowDiv=$myLowDiv." ".$str."</div>";
}
$strForm1="<input type='Button' value='Save Changes' id =\"button_update_$lang\" $disabled onclick='saveMe(\"$lang\",\"$syn1\",\"$ilang\");'></form>";
$strNewWord="</br></br>";
$strNewWord=$strNewWord."<div id='div_manage_new_word_$lang' class='divNewWordCls'>
    <div id='div_add_new_words_$lang' class='divNewWordCls' style='display:block'>
        <a href='javascript:AddMoreLessWord(\"add\",\"$lang\");'>Add New Word(s) to Synset $param </a>&nbsp;&nbsp;
        <a href='javascript:AddMoreLessWord(\"rm\",\"$lang\");'>Remove New Word(s) From Synset $param </a>&nbsp;&nbsp;
        <input type='hidden' value='0' name='nwId' id='id_new_word_$lang' >";
        $div="";
for ($n=0; $n<$maxnumofnewwords; $n++){
      $strNewRadio="&nbsp;<input type='radio' name='nv_".$n."' value='0'  id='radio_result_sub_form_low_$param"."_".$lang."_".$n."_0' />&nbsp;0</input>";
   $strNewRadio=$strNewRadio."&nbsp;&nbsp;<input type='radio' name='nv_".$n."' value='1'  id='radio_result_sub_form_low_$param"."_".$lang."_".$n."_1' />&nbsp;1</input>";
    $strNewRadio=$strNewRadio."&nbsp;&nbsp;<input type='radio' name='nv_".$n."' value='2'  id='radio_result_sub_form_low_$param"."_".$lang."_".$n."_2' checked/>&nbsp;2</input>";
    $id="nw_".$lang."_".$n;
    $newwordStr="&nbsp;".$strNewRadio."&nbsp; &nbsp;<input type='text' value='' name='newwords[]' id='$id' class='newWordCls' placeholder='Type a new word  ($lang)' onclick='javascript:autocompleteme(\"$id\",\"$lang\");'/><input type='hidden' value=".time()."  name='newsenses[]'/><input type='text' value='$thepos' name='newposes[]' placeholder='Type a new pos'/><input type='hidden' value='$param' name='newsynsets[]'/>";
    $div=$div."<div id='div_new_words_".$lang."_".$n."' style='display:none;'><input type='hidden' value='Add' name='newacts[]'/>&nbsp; &nbsp;".$newwordStr."  ".$newSelstr."</div>";
    }
$strNewWord=$strNewWord.$div;
$myStr=$myDefDiv."<p></p>".$myLowDiv;
$myjquery="<script></script>";
echo "<p></p>".$strForm0.$myStr."<input type = 'hidden' name = 'synsetid' value='$syn1'><input type = 'hidden' name = 'ilang' value='$ilang'><input type = 'hidden' name = 'lang' value='$lang' id='hiddenlang'><input type = 'hidden' name = 'user' value='$user' id ='user_text_$lang'>".$strAddNewWord.$strNewWord.$strForm1."<p></p>".$myjquery;
}
?>



