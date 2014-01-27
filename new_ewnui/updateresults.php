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

 
// field to switch on 
 $field_ = (isset($_POST['type']) ? $_POST['type'] : $_GET['type']); 
 

function saveChangesForWordsSynset(){
    $link=GetMyConnection();
    $conn=SwitchConnection('', $link, 2);

    global $USER_ACT_TAB;    
    // input language
    $ilang = (isset($_POST['ilang']) ? $_POST['ilang'] : $_GET['ilang']); 
    
     // input pos
    $mpos = (isset($_POST['mpos']) ? $_POST['mpos'] : $_GET['mpos']); 

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
    $insStr="Insert into $USER_ACT_TAB (lang,word,pos,sense,rule,synsetid,activity,value,mapping_lang,mapping_pos,mapping_synsetid,username) VALUES (";
    //$insStr="Insert into $USER_ACT_TAB (lang,word,pos,sense,rule,synsetid,activity,value,mapping_lang,mapping_synsetid,username) VALUES (";
    for($i=0; $i<$nw; $i++){
        $mystr=$insStr. "'$lang', '$words[$i]', '$poses[$i]','$senses[$i]', '$rules[$i]','$synsets[$i]', '$acts[$i]','$vs[$i]','$ilang','$mpos','$synsetid','$user');";
        //$mystr=$insStr. "'$lang', '$words[$i]', '$poses[$i]','$senses[$i]', '$rules[$i]','$synsets[$i]', '$acts[$i]','$vs[$i]','$ilang','$synsetid','$user');";
        $int=doInsertRecordsIntoActivity($link,$mystr);
        $resStr=$resStr. "Inserted into $USER_ACT_TAB: '$lang', '$words[$i]', '$poses[$i]','$senses[$i]', '$rules[$i]','$synsets[$i]', '$acts[$i]','$vs[$i]','$ilang','$mpos','$synsetid','$user', with ret code: $int;</br>";
        //$resStr=$resStr. "Inserted into table $table: '$lang', '$words[$i]', '$poses[$i]','$senses[$i]', '$rules[$i]','$synsets[$i]', '$acts[$i]','$vs[$i]','$ilang','$synsetid','$user', with ret code: $int;</br>";
     }
    echo $resStr."</br></hline></br>";
      
    $resStr="";
    $insStr="Insert into $USER_ACT_TAB (lang,word,pos,sense,rule,synsetid,activity,value,mapping_lang,mapping_pos,mapping_synsetid,username) VALUES (";
    //$insStr="Insert into $table (lang,word,pos,sense,rule,synsetid,activity,value,mapping_lang,mapping_synsetid,username) VALUES (";
for($i=0; $i<$neww; $i++){
        $mystr=$insStr. "'$lang', '$newwords[$i]', '$newposes[$i]','$newsenses[$i]', '$newrules[$i]','$newsynsets[$i]', '$newacts[$i]','$newvs[$i]','$ilang','$mpos','$synsetid','$user');";
         //$mystr=$insStr. "'$lang', '$newwords[$i]', '$newposes[$i]','$newsenses[$i]', '$newrules[$i]','$newsynsets[$i]', '$newacts[$i]','$newvs[$i]','$ilang','$synsetid','$user');";
         $int=doInsertRecordsIntoActivity($link,$mystr);
         $resStr=$resStr. "Inserted into $USER_ACT_TAB: '$lang', '$newwords[$i]', '$newposes[$i]','$newsenses[$i]', '$newrules[$i]','$newsynsets[$i]', '$newacts[$i]','$newvs[$i]','$ilang','$mpos','$synsetid','$user', with ret code: $int;</br>";
         //$resStr=$resStr. "Inserted: '$lang', '$newwords[$i]', '$newposes[$i]','$newsenses[$i]', '$newrules[$i]','$newsynsets[$i]', '$newacts[$i]','$newvs[$i]','$ilang','$synsetid','$user', with ret code: $int;</br>";
     }
     
      $strRecap="Recap on new words added</br>";
      foreach ($newwords as $nw){
          $strRecap=$strRecap. "* word: $nw </br>";
      }
      $strRecap=$strRecap. "</br><hr/></br>";
      foreach ($newsenses as $ns){
          $strRecap=$strRecap.  "* sense: $ns </br>";
    }
     echo $resStr."</br><hr/></br>.$strRecap";
}

function saveChangesForSourceRel(){
    $link=GetMyConnection();
    $conn=SwitchConnection('', $link, 2);

    global $USER_ACT_SREL_TAB ;    
    // input language
    $ilang = (isset($_POST['ilang']) ? $_POST['ilang'] : $_GET['ilang']); 

    // input pos
    $lpos = (isset($_POST['lpos']) ? $_POST['lpos'] : $_GET['lpos']);
    
    // input synset
    $lsyn = (isset($_POST['lsyn']) ? $_POST['lsyn'] : $_GET['lsyn']); 	
    
     // mapping pos
    $mpos = (isset($_POST['mpos']) ? $_POST['mpos'] : $_GET['mpos']); 

    // $lang
    $lang = (isset($_POST['lang']) ? $_POST['lang'] : $_GET['lang']); 
 
    // $synsetid
    $synsetid = (isset($_POST['synsetid']) ? $_POST['synsetid'] : $_GET['synsetid']); 
 
    //$user
    $user = (isset($_POST['user']) ? $_POST['user'] : $_GET['user']); 

    // $tposes
    $tposes = (isset($_POST['tposes']) ? $_POST['tposes'] : $_GET['tposes']); 

    // $rels
    $rels = (isset($_POST['rels']) ? $_POST['rels'] : $_GET['rels']); 

    // $targets
    $targets = (isset($_POST['targets']) ? $_POST['targets'] : $_GET['targets']); 

    // $targetsIds
    $targetsIds = (isset($_POST['targetsIds']) ? $_POST['targetsIds'] : $_GET['targetsIds']); 

    // rules
    $rules = (isset($_POST['rules']) ? $_POST['rules'] : $_GET['rules']); 

    //activities
    $acts = (isset($_POST['acts']) ? $_POST['acts'] : $_GET['acts']); 

    //values
    $vs = (isset($_POST['vs']) ? $_POST['vs'] : $_GET['vs']); 

    // $newtposes
    $newtposes = (isset($_POST['newtposes']) ? $_POST['newtposes'] : $_GET['newtposes']); 

    // $newrels
    $newrels = (isset($_POST['newrels']) ? $_POST['newrels'] : $_GET['newrels']); 

    // $targets
    $newtargets = (isset($_POST['newtargets']) ? $_POST['newtargets'] : $_GET['newtargets']); 

    // $newtargetsIds
    $newtargetsIds = (isset($_POST['newtargetsIds']) ? $_POST['newtargetsIds'] : $_GET['newtargetsIds']); 

    // newrules
    $newrules = (isset($_POST['newrules']) ? $_POST['newrules'] : $_GET['newrules']); 

    //newactivities
    $newacts = (isset($_POST['newacts']) ? $_POST['newacts'] : $_GET['newacts']); 

    //newvalues
    $newvs = (isset($_POST['newvs']) ? $_POST['newvs'] : $_GET['newvs']); 

    // is everything fine
    $nw=count($rels);
    $neww=count($newrels);
    $np=count($tposes);
    $nsy=count($targets);
    $nse=count($targetsIds);
    $nrules=count($rules);
    $nacts=count($acts);
    $resStr="";
 
    $insStr="Insert into $USER_ACT_SREL_TAB (lang,rel,pos,rule,synsetid,activity,value,mapping_lang,mapping_pos,mapping_synsetid,target_pos,target_synsetid,target_def,username) VALUES (";
    for($i=0; $i<$nw; $i++){
        $mystr=$insStr. "'$lang', '$rels[$i]', '$lpos', '$rules[$i]','$lsyn','$acts[$i]', '$vs[$i]','$ilang','$mpos','$synsetid','$tposes[$i]','$targetsIds[$i]','$targets[$i]', '$user');"; 
        $int=doInsertRecordsIntoActivitySrel($link,$mystr);
        $resStr=$resStr. "Inserted into $USER_ACT_SREL_TAB: '$lang', '$rels[$i]', '$lpos', '$rules[$i]','$lsyn','$acts[$i]', '$vs[$i]','$ilang','$mpos','$synsetid','$tposes[$i]','$targetsIds[$i]','$targets[$i]', '$user', with ret code: $int;</br>";
        
     }
    echo $resStr."</br></hline></br>";
      
    $resStr="";
    $insStr="Insert into $USER_ACT_SREL_TAB (lang,rel,pos,rule,synsetid,activity,value,mapping_lang,mapping_pos,mapping_synsetid,target_pos,target_synsetid,target_def,username) VALUES (";
for($i=0; $i<$neww; $i++){
        $mystr=$insStr. "'$lang', '$newrels[$i]', '$lpos', '$newrules[$i]','$lsyn', '$newacts[$i]','$newvs[$i]','$ilang','$mpos','$synsetid','$newtposes[$i]','$newtargetsIds[$i]','$newtargets[$i]','$user');";
         
         $int=doInsertRecordsIntoActivitySrel($link,$mystr);
         $resStr=$resStr. "Inserted into $USER_ACT_SREL_TAB: '$lang', '$newrels[$i]', $lpos', '$newrules[$i]','$lsyn', '$newacts[$i]','$newvs[$i]','$ilang','$mpos','$synsetid','$newtposes[$i]','$newtargetsIds[$i]','$newtargets[$i]','$user', with ret code: $int;</br>";
     }
     
      $strRecap= "Recap on new source relations added</br>";
      foreach ($newrels as $nw){
          $strRecap=$strRecap.  "* relation: $nw </br>";
      }
      $strRecap=$strRecap. "</br><hr/></br>";
      foreach ($newtargetsIds as $ns){
          echo "* target id: $ns </br>";
    }
	
    echo "</br><hr/></br>";
      foreach ($newtargets as $ns){
          $strRecap=$strRecap.  "* target definition: $ns </br>";
    }
     echo $resStr."</br><hr/></br>".$strRecap;
}

function saveChangesForTargetRel(){
    $link=GetMyConnection();
    $conn=SwitchConnection('', $link, 2);

    global $USER_ACT_TREL_TAB ;    
    // input language
    $ilang = (isset($_POST['ilang']) ? $_POST['ilang'] : $_GET['ilang']); 

    // input pos
    $lpos = (isset($_POST['lpos']) ? $_POST['lpos'] : $_GET['lpos']);
    
    // input synset
    $lsyn = (isset($_POST['lsyn']) ? $_POST['lsyn'] : $_GET['lsyn']); 	
    
     // mapping pos
    $mpos = (isset($_POST['mpos']) ? $_POST['mpos'] : $_GET['mpos']); 

    // $lang
    $lang = (isset($_POST['lang']) ? $_POST['lang'] : $_GET['lang']); 
 
    // $synsetid
    $synsetid = (isset($_POST['synsetid']) ? $_POST['synsetid'] : $_GET['synsetid']); 
 
    //$user
    $user = (isset($_POST['user']) ? $_POST['user'] : $_GET['user']); 

    // $tposes
    $tposes = (isset($_POST['tposes']) ? $_POST['tposes'] : $_GET['tposes']); 

    // $rels
    $rels = (isset($_POST['rels']) ? $_POST['rels'] : $_GET['rels']); 

    // $targets
    $targets = (isset($_POST['targets']) ? $_POST['targets'] : $_GET['targets']); 

    // $targetsIds
    $targetsIds = (isset($_POST['targetsIds']) ? $_POST['targetsIds'] : $_GET['targetsIds']); 

    // rules
    $rules = (isset($_POST['rules']) ? $_POST['rules'] : $_GET['rules']); 

    //activities
    $acts = (isset($_POST['acts']) ? $_POST['acts'] : $_GET['acts']); 

    //values
    $vs = (isset($_POST['vs']) ? $_POST['vs'] : $_GET['vs']); 

    // $newtposes
    $newtposes = (isset($_POST['newtposes']) ? $_POST['newtposes'] : $_GET['newtposes']); 

    // $newrels
    $newrels = (isset($_POST['newrels']) ? $_POST['newrels'] : $_GET['newrels']); 

    // $targets
    $newtargets = (isset($_POST['newtargets']) ? $_POST['newtargets'] : $_GET['newtargets']); 

    // $newtargetsIds
    $newtargetsIds = (isset($_POST['newtargetsIds']) ? $_POST['newtargetsIds'] : $_GET['newtargetsIds']); 

    // newrules
    $newrules = (isset($_POST['newrules']) ? $_POST['newrules'] : $_GET['newrules']); 

    //newactivities
    $newacts = (isset($_POST['newacts']) ? $_POST['newacts'] : $_GET['newacts']); 

    //newvalues
    $newvs = (isset($_POST['newvs']) ? $_POST['newvs'] : $_GET['newvs']); 

    // is everything fine
    $nw=count($rels);
    $neww=count($newrels);
    $np=count($tposes);
    $nsy=count($targets);
    $nse=count($targetsIds);
    $nrules=count($rules);
    $nacts=count($acts);
    $resStr="";
 
    $insStr="Insert into $USER_ACT_TREL_TAB (lang,rel,pos,rule,synsetid,activity,value,mapping_lang,mapping_pos,mapping_synsetid,source_pos,source_synsetid,source_def,username) VALUES (";
    for($i=0; $i<$nw; $i++){
        $mystr=$insStr. "'$lang', '$rels[$i]', '$lpos', '$rules[$i]','$lsyn','$acts[$i]', '$vs[$i]','$ilang','$mpos','$synsetid','$tposes[$i]','$targetsIds[$i]','$targets[$i]', '$user');"; 
        $int=doInsertRecordsIntoActivityTrel($link,$mystr);
        $resStr=$resStr. "Inserted into $USER_ACT_TREL_TAB: '$lang', '$rels[$i]', '$lpos', '$rules[$i]','$lsyn','$acts[$i]', '$vs[$i]','$ilang','$mpos','$synsetid','$tposes[$i]','$targetsIds[$i]','$targets[$i]', '$user', with ret code: $int;</br>";
        
     }
    echo $resStr."</br></hline></br>";
      
    $resStr="";
    $insStr="Insert into $USER_ACT_TREL_TAB (lang,rel,pos,rule,synsetid,activity,value,mapping_lang,mapping_pos,mapping_synsetid,source_pos,source_synsetid,source_def,username) VALUES (";
for($i=0; $i<$neww; $i++){
        $mystr=$insStr. "'$lang', '$newrels[$i]', '$lpos', '$newrules[$i]','$lsyn', '$newacts[$i]','$newvs[$i]','$ilang','$mpos','$synsetid','$newtposes[$i]','$newtargetsIds[$i]','$newtargets[$i]','$user');";
         
         $int=doInsertRecordsIntoActivityTrel($link,$mystr);
         $resStr=$resStr. "Inserted into $USER_ACT_TREL_TAB: '$lang', '$newrels[$i]', $lpos', '$newrules[$i]','$lsyn', '$newacts[$i]','$newvs[$i]','$ilang','$mpos','$synsetid','$newtposes[$i]','$newtargetsIds[$i]','$newtargets[$i]','$user', with ret code: $int;</br>";
 }
 
       $strRecap= "Recap on new target relations added</br>";
      foreach ($newrels as $nw){
          $strRecap=$strRecap.  "* relation: $nw </br>";
      }
      $strRecap=$strRecap. "</br><hr/></br>";
      foreach ($newtargetsIds as $ns){
          echo "* target id: $ns </br>";
    }
	
    echo "</br><hr/></br>";
      foreach ($newtargets as $ns){
          $strRecap=$strRecap.  "* target definition: $ns </br>";
    }
     echo $resStr."</br><hr/></br>".$strRecap;
}
 /* needed
    1) user
    2) mapped language
    3) mapped synset
    4) current synset
    5) current lang
    6) current definition (gloss)
    7) current pos
    8) activity
    Saves in $USER_SYN_TAB
    */
function  saveChangesForGlossAndPosInSynset(){
    
    $link=GetMyConnection();
    $conn=SwitchConnection('', $link, 2);

    global $USER_SYN_TAB;    
    //$user
    $user = (isset($_POST['user']) ? $_POST['user'] : $_GET['user']); 
    
    // input language
    $ilang = (isset($_POST['ilang']) ? $_POST['ilang'] : $_GET['ilang']); 
    
    // input pos
    $mpos = (isset($_POST['mpos']) ? $_POST['mpos'] : $_GET['mpos']); 
    
    // $synsetid
    $synsetid = (isset($_POST['synsetid']) ? $_POST['synsetid'] : $_GET['synsetid']); 
    
    // $synsets
    $synset = (isset($_POST['synset']) ? $_POST['synset'] : $_GET['synset']); 
    
    // $lang
    $lang = (isset($_POST['lang']) ? $_POST['lang'] : $_GET['lang']); 
    
     // def
    $def = (isset($_POST['def']) ? $_POST['def'] : $_GET['def']); 
    
     // pos
    $pos = (isset($_POST['pos']) ? $_POST['pos'] : $_GET['pos']);
    
     // act
    $act = (isset($_POST['act']) ? $_POST['act'] : $_GET['act']);
    
    // compose string
    $insStr="INSERT INTO $USER_SYN_TAB (synsetid, pos, lang, definition, mapped_synset, mapped_lang, mapped_pos,user,activity ) VALUES ('$synset','$pos','$lang','$def','$synsetid','$ilang','$mpos','$user','$act')";
    $int=doSaveChangesForGlossAndPosInSynset($link,$insStr);
    
    echo $int;
 
    }

switch($field_){
    case 'mgp':	
        $result=saveChangesForGlossAndPosInSynset();
        break;
    case 'mws':
        $result=saveChangesForWordsSynset();
        break;
    case 'msr':
        $result=saveChangesForSourceRel(); 
         break;
    case 'mtr':
        $result=saveChangesForTargetRel(); 
         break;
        
}
      
?>