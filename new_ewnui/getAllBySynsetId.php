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
$pos1=$_POST['pos_1']; // original pos of the mapped synset
$table=$lang."Map";
$field_=$_POST['field'];
$maxnumofnewwords=5;
$dir="ltr";
if ($lang=="ara")
    $dir="rtl";

$link=GetMyConnection();
$main_conn=SwitchConnection($lang, $link, 0);

function getDef(){
    global $definition;
    global $dir;
    $myDefDiv='<div id="div_result_sub_def_'.$param.'_'.$lang.'" class="divResultSubDef">';
    if ($user !="")
       $myDefDiv=$myDefDiv." <span id='def_$ilang'>$definition $user</span></br></div>";
    else 
        $myDefDiv=$myDefDiv." $definition </br></div>";
    echo $myDefDiv;    
    }

function getWords($main_conn,$WSS_VIEW,$param, $word){
    global $definition;
    global $ilang;
    global $lang;
    global $user;
    global $dir;
    global $pos;
    global $pos1;
    global $type;
    global $maxnumofnewwords;
    $WS_TAB=$lang."Ws";
    $MAP_TAB=$lang."Map";
    global $XPOSRULE_TAB;
    $result=getWordsFromSynsetId($main_conn,$WSS_VIEW,$param); 
    $selstr="";
    $newSelstr="";
    $strOption="";

    $myLowDiv='<div id="div_result_sub_low_'.$param.'_'.$lang.'" class="divResultSubWords" ></br>';
    $disabled="disabled";
    
    //$myLowDiv=$myLowDiv. "List of words</br>";
    // sel str
     $side_conn=SwitchConnection($lang, $link, 1);
     $xres=getXPosGenerationRule($side_conn,$XPOSRULE_TAB,$lang,$pos1, $pos);
     if (count($xres)>0){
         $selstr="<select name = 'rules[]'><option value='No Rule'>Please choose a rule</option>";
         $newSelstr="<select name = 'newrules[]'><option value='No Rule'>Please choose a rule</option>";
         for ($i=0; $i<count($xres); $i++){
             $rule= $xres[$i]['rule'];
             $strOption=$strOption."<option value = '$rule'>".$rule."</option>";
         }
        $selstr=$selstr.$strOption."</select>";
        $newSelstr=$newSelstr.$strOption."</select>";
    }
    $tab="<table border='0' cellpadding='2' cellspacing='2'>";
    for ($i=0; $i<count($result); $i++){
        $theword=$result[$i]['lemma'];
        $theword4id=$result[$i]['lemma'];
        if($ilang == 'grc'){
            $theword="<a href='javascript:Popup(\"http://lsj.translatum.gr/wiki/$theword\")'>".$theword."</a>";
            
    }
        
        
    $thepos=$result[$i]['pos'];
    $thesense=$result[$i]['sensenum'];
    if ($theword===$word)
        $str=$theword="<div ><bdo dir='$dir'><b><i>".$theword." </i></b> (".$thesense.")  (".$thepos.")</bdo></div>";
    else
        $str ="<div dir='$dir'>".$theword."  (".$thesense.")  (".$thepos.")</div> ";
        
    // getting features
    // creating a different connection
   
   
    if ($lang != 'eng'){
        $validate=getFeatsFromSynsetId($side_conn,$WS_TAB,$MAP_TAB,'in',$param,$theword4id);
       
        }
    if (count($validate) == 0 OR count($validate) >1){
        $chk0="checked";
    } 
    else {
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
        if($type==0)
            $divClass="edit_sub_brw_".$param."_".$thepos."_".$lang."_ws";
        if ($type==1)
            $divClass="edit_sub_brw_t_".$param."_".$thepos."_".$lang."_ws";
        $strRadio=$tab."<tr><td><div class='$divClass' style='display:none'><input type='radio' name='v_".$i."' value='0'  id='radio_result_sub_form_low_$param"."_".$lang."_".$theword4id."_0' $chk0/>&nbsp;0</input>";
        $strRadio=$strRadio."&nbsp;&nbsp;<input type='radio' name='v_".$i."' value='1'  id='radio_result_sub_form_low_$param"."_".$lang."_".$theword4id."_1' $chk1/>&nbsp;1</input>";
        $strRadio=$strRadio."&nbsp;&nbsp;<input type='radio' name='v_".$i."' value='2'  id='radio_result_sub_form_low_$param"."_".$lang."_".$theword4id."_2' $chk2/>&nbsp;2</input></div></td>";
        $strRadio=$strRadio."<td><div class='$divClass' style='display:none'>".$selstr."</div></td>";
        $strInput="<td><input type='hidden' value='$theword4id' name='words[]' /><input type='hidden' value='$thesense'  name='senses[]'/><input type='hidden' value='$thepos' name='poses[]'/>"; 
        $strInput=$strInput."<input type='hidden' value='$param' name='synsets[]'/><input type='hidden' value='Validate' name='acts[]'/></td>";
 
        $str='<td><div id="div_result_sub_low_'.$param.'_'.$lang.'_'.$theword4id.'" class="divResultSubLow" >'.$str.$strInput.'</div></td></tr></table>' ;
        $tabStr=$strRadio.$str;
        $myLowDiv=$myLowDiv.$tabStr;
        } // end for
        
        // start for new words
        $tabStr="";
        $tab="<table border='0' cellpadding='2' cellspacing='2'>";
        $tab_ar="<table border='0' cellpadding='2' cellspacing='2'><caption>You can add up to $maxnumofnewwords new words to synset $param</caption><tr>
                        <td>Add a word <a href='javascript:AddMoreLessWord(\"add\",\"$param\",\"$lang\");'>[+] </a></td>
                        <td>Remove a word <a href='javascript:AddMoreLessWord(\"rm\",\"$param\",\"$lang\");'>[-] </a></td>
                        <td><input type='hidden' value='0' name='nwId' id='id_new_word_$param"."_".$lang."' ></td></tr></table>";
        for ($n=0; $n<$maxnumofnewwords; $n++){
            $strNewRadio=$tab."<tr id='div_manage_new_word_$param"."_".$lang."_".$n."' style='display:none;'>";
            $strNewRadio=$strNewRadio."<td><input type='radio' name='nv_".$n."' value='0'  id='radio_result_sub_form_low_$param"."_".$lang."_".$n."_0' />&nbsp;0</input>";
            $strNewRadio=$strNewRadio."&nbsp;&nbsp;<input type='radio' name='nv_".$n."' value='1'  id='radio_result_sub_form_low_$param"."_".$lang."_".$n."_1' />&nbsp;1</input>";
            $strNewRadio=$strNewRadio."&nbsp;&nbsp;<input type='radio' name='nv_".$n."' value='2'  id='radio_result_sub_form_low_$param"."_".$lang."_".$n."_2' checked/>&nbsp;2</input></td>";
            $strNewRadio=$strNewRadio."<td>".$newSelstr."</td>";
            $id="nw_".$param."_".$lang."_".$n;
            $newwordStr="<td><input type='text' value='' name='newwords[]' id='$id' class='newWordCls' placeholder='Type a new word  ($lang)' onclick='javascript:autocompleteme(\"$id\",\"$lang\");'/></td>";
            $newwordStr=$newwordStr."<td><input type='hidden' value=".(time()+$n)."  name='newsenses[]'/></td>";
            $newwordStr=$newwordStr."<td>Part Of Speech: <input type='text' value='$thepos' name='newposes[]' placeholder='Type a new pos'/ maxlength='3' size='3'><input type='hidden' value='$param' name='newsynsets[]'/><input type='hidden' value='Add' name='newacts[]'/></td>";
            $tabStr=$tabStr.$strNewRadio.$newwordStr."</tr></table>";
            
            
    }
    //<div id='div_manage_new_word_$lang' class='divNewWordCls'>".$caption.$newStr."
        $newStr=$newStr."</br><div class='$divClass' style='display:none'>".$tab_ar."<br>".$tabStr."</div>";
        $myStr=$myLowDiv."</div>";
        echo $myStr."</br>".$newStr;
}

function getRelsAsSource($main_conn,$SEMLINK_TAB,$REL_TAB,$SYNSETS_TAB,$WSS_VIEW,$param,$word){
    global $ilang;
    global $lang;
    global $type;
    global $dir;
    global $pos;
    global $definition;
    $maxnumofnewrels=5;
    $result=getRelsFromSynsetId($main_conn,$SEMLINK_TAB,$REL_TAB,$SYNSETS_TAB,$WSS_VIEW,$param,$word);
    $myRelDiv='<div id="div_result_sub_rel_'.$param.'_'.$lang.'" class="divResultSubRel" >';
    
    if($type==0)
            $divClass="edit_sub_brw_".$param."_".$pos."_".$lang."_sr";
    if ($type==1)
            $divClass="edit_sub_brw_t_".$param."_".$pos."_".$lang."_sr";
    //$myRelDiv=$myRelDiv."<b><i>List of Relations</i></b></br> Source: ".$param."</br>";
    $msg="Synset ".$param. " ($definition)   plays as <i>source</i> in the following relations:</br></br></br>"; 
    $strTab="<table border='0' cellpadding='2' cellspacing='2'><caption>".$msg."</caption>";
    $strTab_1="";
    $str="";
    for ($i=0; $i<count($result); $i++){
        $target=$result[$i]['target'];
        $target_1=$result[$i]['target'];
        // add the validation of relation 

        $strRelRadio="";
        $strRelRadio="&nbsp;<input type='radio' name='srv_".$i."' value='0'  id='radio_result_sub_form_v_rel_$param"."_".$lang."_".$i."_0' />&nbsp;0</input>";
        $strRelRadio=$strRelRadio."&nbsp;&nbsp;<input type='radio' name='srv_".$i."' value='1'  id='radio_result_sub_form_v_rel_$param"."_".$lang."_".$i."_1' />&nbsp;1</input>";
        $strRelRadio=$strRelRadio."&nbsp;&nbsp;<input type='radio' name='srv_".$i."'  value='2'  id='radio_result_sub_form_v_rel_$param"."_".$lang."_".$i."_2'  checked />&nbsp;2</input>";

   
       
        $rel=$result[$i]['relation'];
        $relid=$result[$i]['linkid'];
        $sw=$result[$i]['sw'];
        $tdef=$result[$i]['tdefinition'];
        $tdef_1=$result[$i]['tdefinition'];
        $tdef=addslashes($tdef);
        $tpos=$result[$i]['tpos'];
        
        $target= "<a href=\"javascript:showResBySynsetId('$target','$word','$tdef','$ilang','$tpos',$type);\">".$target."</a>";
         if ($i==0)
            $strTab_1=$strTab."<tr><td rowspan='".count($result)."'><b><i>".$param."</i></b><input type='hidden' value='$pos' name='lpos'></td><td>".$rel."</td><td><div class='$divClass' style='display:none'>$strRelRadio</div></td><td>".$target. " (".$tpos. ")  ".$tdef_1."  <input type='hidden' value='$tdef_1' name='targets[]'> <input type='hidden' value='$target_1' name='targetsIds[]'> <input type='hidden' value='$tpos' name='tposes[]'> <input type='hidden' value='$rel' name='rels[]'>  <input type='hidden' value='Validate' name='acts[]'/> <bdo dir='$dir'> [".$sw. "]</bdo></td></tr>";
        else
            $strTab_1=$strTab_1."<tr><td>".$rel."</td><td><div class='$divClass' style='display:none'>$strRelRadio</div></td><td>".$target. " (".$tpos. ")  ".$tdef_1."   <input type='hidden' value='$tdef_1' name='targets[]'> <input type='hidden' value='$target_1' name='targetsIds[]'> <input type='hidden' value='$tpos' name='tposes[]'>  <input type='hidden' value='$rel' name='rels[]'><input type='hidden' value='Validate' name='acts[]'/>  <bdo dir='$dir'> [".$sw. "]</bdo></td></tr>";
        
       // $str=" &nbsp; &nbsp;" .$strRelRadio. " &nbsp; &nbsp; ".$rel." ".$target. " (".$tpos. ")  ".$tdef."      <bdo dir='$dir'> [".$sw. "]</bdo>";
       // $str=" &nbsp; &nbsp;" .$strRelRadio. " &nbsp; &nbsp; ".$rel." ".$target. " (".$tpos. ")  ".$tdef."     [".$sw. "]";
        //$str='<div id="div_result_sub_lor_'.$target_1.'_'.$lang.'_'.$relid.'" class="divResultSubLor">'.$msg.$str.'</div>' ;
        $str='<div id="div_result_sub_lor_'.$target_1.'_'.$lang.'_'.$relid.'" class="divResultSubLor">'.$strTab_1;
        //$myRelDiv=$myRelDiv." ".$str;
       
}
 // new relations
 // start for new words
        $tabStr="";
        $tab="<table border='0' cellpadding='2' cellspacing='2'>";
        $tab_ar="<table border='0' cellpadding='2' cellspacing='2'><caption>You can add up to $maxnumofnewrels new source relations to synset $param ($definition) <input type='hidden' value='$pos' name='lpos_1'></caption><tr>
                        <td>Add a relation <a href='javascript:AddMoreLessSRel(\"add\",\"$param\",\"$lang\");'>[+] </a></td>
                        <td>Remove a relation <a href='javascript:AddMoreLessSRel(\"rm\",\"$param\",\"$lang\");'>[-] </a></td>
                        <td><input type='hidden' value='0' name='nrId' id='id_new_srel_$param"."_".$lang."' ></td></tr></table>";
        for ($n=0; $n<$maxnumofnewrels; $n++){
            $strNewRadio=$tab."<tr id='div_manage_new_srel_$param"."_".$lang."_".$n."' style='display:none;'>";
            $strNewRadio=$strNewRadio."<td><input type='radio' name='nsrv_".$n."' value='0'  id='radio_result_sub_form_lor_$param"."_".$lang."_".$n."_0' />&nbsp;0</input>";
            $strNewRadio=$strNewRadio."&nbsp;&nbsp;<input type='radio' name='nsrv_".$n."' value='1'  id='radio_result_sub_form_lor_$param"."_".$lang."_".$n."_1' />&nbsp;1</input>";
            $strNewRadio=$strNewRadio."&nbsp;&nbsp;<input type='radio' name='nsrv_".$n."' value='2'  id='radio_result_sub_form_lor_$param"."_".$lang."_".$n."_2' checked/>&nbsp;2</input></td>";
            $strNewRadio=$strNewRadio."<td>".$newSelstr."</td>";
            $id="nsr_".$param."_".$lang."_".$n;
            $baseid="nsr_".$param."_".$lang."_";
            $tgtid="nsr_".$param."_".$lang."_tgt_".$n;
            $tid="nsr_".$param."_".$lang."_tgt_id_".$n;
            $pid="nsr_".$param."_".$lang."_pos_".$n;
            $newwordStr="<td>Relation&nbsp;<input type='text' value='' name='newrels[]' id='$id' class='newWordCls' placeholder='Type a new relation  ' onclick='javascript:autocompletemeRel(\"$id\",\"$lang\");'/></td>";
            //$newwordStr=$newwordStr."<td>Browse in Glosses&nbsp;<input type='text' value=''  id='$tgtid' placeholder='Type a Target Synset 'name='newtargets[]' onclick='javascript:autocompletemeTarget(\"$tgtid\",\"$lang\",0,\"$base\",$n);'/></td>";
            $newwordStr=$newwordStr."<td>Browse in Glosses&nbsp;</td><td><textarea id='$tgtid' placeholder='Type a definition 'name='newtargets[]' onclick='javascript:autocompletemeTarget(\"$tgtid\",\"$lang\",0,\"$baseid\",$n);'></textarea></td>";
            //$newwordStr=$newwordStr."<td>Browse in Synset Ids&nbsp;<input type='text' value=''  id='$tid' placeholder='Type a Target Synset 'name='newtargetsIds[]' onclick='javascript:autocompletemeTarget(\"$tid\",\"$lang\",1,\"$baseid\",$n);'/></td>";
            $newwordStr=$newwordStr."<td>Browse in Synset Ids&nbsp;<input type='text' value=''  id='$tid' placeholder='Type a Target Synset 'name='newtargetsIds[]' /></td>";
            $newwordStr=$newwordStr."<td>Part Of Speech: <input type='text' value='' id='$pid' name='newtposes[]' placeholder='Type a Target Pos' maxlength='3'onclick='javascript:autocompletemePos(\"$pid\",\"$lang\");'/><input type='hidden' value='AddSRel' name='newacts[]'/></td>";
            $tabStr=$tabStr.$strNewRadio.$newwordStr."</tr></table>";
    }

    $newStr=$newStr."</br><div class='$divClass' style='display:none'>".$tab_ar."<br>".$tabStr."</div>";
    if (count($result)==0)
        $str='<div id="div_result_sub_lor_'.$target_1.'_'.$lang.'_'.$relid.'" class="divResultSubLor"><table><tr><td></td></tr>';
    echo $myRelDiv.$str."</table></div></div>".$newStr;
}


function getRelsAsTarget($main_conn,$SEMLINK_TAB,$REL_TAB,$SYNSETS_TAB,$WSS_VIEW,$param,$word){
    global $ilang;
    global $lang;
    global $type;
    global $dir;
     global $pos;
    global $definition;
    $maxnumofnewrels=5;
    $result=getTgtRelsFromSynsetId($main_conn,$SEMLINK_TAB,$REL_TAB,$SYNSETS_TAB,$WSS_VIEW,$param,$word);
    $myRelDiv='<div id="div_result_sub_trel_'.$param.'_'.$lang.'" class="divResultSubRel" >';
    $msg="Synset ".$param." ($definition) plays as <i>target</i> in the following relations:</br></br></br>"; 
    $strTab="<table border='0' cellpadding='2' cellspacing='2'><caption>".$msg."</caption>";
    $strTab_1="";
    //$myRelDiv=$myRelDiv."<b><i>List of Relations</i></b></br> Source: ".$param."</br>";
    if($type==0)
            $divClass="edit_sub_brw_".$param."_".$pos."_".$lang."_tr";
    if ($type==1)
            $divClass="edit_sub_brw_t_".$param."_".$pos."_".$lang."_tr";
    for ($i=0; $i<count($result); $i++){
        $target=$result[$i]['source'];
        $target_1=$result[$i]['source'];
      
        $strRelRadio="";
        $strRelRadio="&nbsp;<input type='radio' name='trv_".$i."' value='0'  id='radio_result_sub_form_v_rel_$param"."_".$lang."_".$i."_0' />&nbsp;0</input>";
        $strRelRadio=$strRelRadio."&nbsp;&nbsp;<input type='radio' name='trv_".$i."' value='1'  id='radio_result_sub_form_v_rel_$param"."_".$lang."_".$i."_1' />&nbsp;1</input>";
        $strRelRadio=$strRelRadio."&nbsp;&nbsp;<input type='radio' name='trv_".$i."'  value='2'  id='radio_result_sub_form_v_rel_$param"."_".$lang."_".$i."_2'  checked />&nbsp;2</input>";
      
        $rel=$result[$i]['relation'];
        $relid=$result[$i]['linkid'];
        $sw=$result[$i]['sw'];
        $tdef=$result[$i]['tdefinition'];
        $tdef_1=$result[$i]['tdefinition'];
        $tdef=addslashes($tdef);
        $tpos=$result[$i]['tpos'];
        $target= "<a href=\"javascript:showResBySynsetId('$target','$word','$tdef','$ilang','$tpos',$type);\">".$target."</a>";
         if ($i==0)
            $strTab_1=$strTab."<tr><td>".$target. " (".$tpos. ")  ".$tdef_1."   <input type='hidden' value='$tdef_1' name='targets[]'> <input type='hidden' value='$target_1' name='targetsIds[]'> <input type='hidden' value='$tpos' name='tposes[]'>  <input type='hidden' value='$rel' name='rels[]'><input type='hidden' value='Validate' name='acts[]'/>   <bdo dir='$dir'> [".$sw. "]</bdo></td><td><div class='$divClass' style='display:none'>$strRelRadio</div></td><td>".$rel."</td><td rowspan='".count($result)."'><b><i>".$param."</i></b><input type='hidden' value='$pos' name='lpos'></td></tr>";
        else
            $strTab_1=$strTab_1."<tr><td>".$target. " (".$tpos. ")  ".$tdef_1."   <input type='hidden' value='$tdef_1' name='targets[]'> <input type='hidden' value='$target_1' name='targetsIds[]'> <input type='hidden' value='$tpos' name='tposes[]'>  <input type='hidden' value='$rel' name='rels[]'><input type='hidden' value='Validate' name='acts[]'/>   <bdo dir='$dir'> [".$sw. "]</bdo></td><td><div class='$divClass' style='display:none'>$strRelRadio</div></td><td>".$rel."</td></tr>";
        //$str=" &nbsp; &nbsp;" .$strRelRadio. " &nbsp; &nbsp; ".$rel." ".$target. " (".$tpos. ")  ".$tdef."       [".$sw. "]";
        $str='<div id="div_result_sub_lotr_'.$target_1.'_'.$lang.'_'.$relid.'" class="divResultSubLor">'.$strTab_1 ;
        //$myRelDiv=$myRelDiv." ".$str;
       
}

        $tabStr="";
        $tab="<table border='0' cellpadding='2' cellspacing='2'>";
        $tab_ar="<table border='0' cellpadding='2' cellspacing='2'><caption>You can add up to $maxnumofnewrels new target relations to synset $param ($definition) <input type='hidden' value='$pos' name='lpos_1'></caption><tr>
                        <td>Add a relation <a href='javascript:AddMoreLessTRel(\"add\",\"$param\",\"$lang\");'>[+] </a></td>
                        <td>Remove a relation <a href='javascript:AddMoreLessTRel(\"rm\",\"$param\",\"$lang\");'>[-] </a></td>
                        <td><input type='hidden' value='0' name='ntrId' id='id_new_trel_$param"."_".$lang."' ></td></tr></table>";
    for ($n=0; $n<$maxnumofnewrels; $n++){
            $strNewRadio=$tab."<tr id='div_manage_new_trel_$param"."_".$lang."_".$n."' style='display:none;'>";
            $strNewRadio=$strNewRadio."<td><input type='radio' name='ntrv_".$n."' value='0'  id='radio_result_sub_form_lor_$param"."_".$lang."_".$n."_0' />&nbsp;0</input>";
            $strNewRadio=$strNewRadio."&nbsp;&nbsp;<input type='radio' name='ntrv_".$n."' value='1'  id='radio_result_sub_form_lor_$param"."_".$lang."_".$n."_1' />&nbsp;1</input>";
            $strNewRadio=$strNewRadio."&nbsp;&nbsp;<input type='radio' name='ntrv_".$n."' value='2'  id='radio_result_sub_form_lor_$param"."_".$lang."_".$n."_2' checked/>&nbsp;2</input></td>";
            $strNewRadio=$strNewRadio."<td>".$newSelstr."</td>";
            $id="ntr_".$param."_".$lang."_".$n;
            $baseid="ntr_".$param."_".$lang."_";
            $tgtid="ntr_".$param."_".$lang."_tgt_".$n;
            $tid="ntr_".$param."_".$lang."_tgt_id_".$n;
            $pid="ntr_".$param."_".$lang."_pos_".$n;
            $newwordStr="<td>Relation&nbsp;<input type='text' value='' name='newrels[]' id='$id' class='newWordCls' placeholder='Type a new relation  ' onclick='javascript:autocompletemeRel(\"$id\",\"$lang\");'/></td>";
            //$newwordStr=$newwordStr."<td>Browse in Glosses&nbsp;<input type='text' value=''  id='$tgtid' placeholder='Type a Target Synset 'name='newtargets[]' onclick='javascript:autocompletemeTarget(\"$tgtid\",\"$lang\",0,\"$base\",$n);'/></td>";
            $newwordStr=$newwordStr."<td>Browse in Glosses&nbsp;</td><td><textarea id='$tgtid' placeholder='Type a definition 'name='newtargets[]' onclick='javascript:autocompletemeTarget(\"$tgtid\",\"$lang\",0,\"$baseid\",$n);'></textarea></td>";
            //$newwordStr=$newwordStr."<td>Browse in Synset Ids&nbsp;<input type='text' value=''  id='$tid' placeholder='Type a Target Synset 'name='newtargetsIds[]' onclick='javascript:autocompletemeTarget(\"$tid\",\"$lang\",1,\"$baseid\",$n);'/></td>";
            $newwordStr=$newwordStr."<td>Browse in Synset Ids&nbsp;<input type='text' value=''  id='$tid' placeholder='Type a Target Synset 'name='newtargetsIds[]' /></td>";
            $newwordStr=$newwordStr."<td>Part Of Speech: <input type='text' value='' id='$pid' name='newtposes[]' placeholder='Type a Target Pos' maxlength='3'onclick='javascript:autocompletemePos(\"$pid\",\"$lang\");'/><input type='hidden' value='AddTRel' name='newacts[]'/></td>";
            $tabStr=$tabStr.$strNewRadio.$newwordStr."</tr></table>";
    }

    $newStr=$newStr."</br><div class='$divClass' style='display:none'>".$tab_ar."<br>".$tabStr."</div>";
     if (count($result)==0)
        $str='<div id="div_result_sub_lotr_'.$target_1.'_'.$lang.'_'.$relid.'" class="divResultSubLor"><table><tr><td></td></tr>';
    echo $myRelDiv.$str."</table></div></div>".$newStr;
    //echo "SSS";
}


switch($field_){
	case 'sw':	
		getWords($main_conn,$WSS_VIEW,$param,$word);
        break;
    case 'sr':	
		getRelsAsSource($main_conn,$SEMLINK_TAB,$REL_TAB,$SYNSETS_TAB, $WSS_VIEW,$param,$word);
        break;
    case 'tr':	
		getRelsAsTarget($main_conn,$SEMLINK_TAB,$REL_TAB,$SYNSETS_TAB, $WSS_VIEW,$param,$word);
        break;
    case 'df':	
		getDef(); 
        break;
    //echo $result;
    }
?>