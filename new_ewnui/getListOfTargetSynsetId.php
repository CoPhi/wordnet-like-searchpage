<?php
/*
adding inner divs
*/
require_once("./utils/tablelist.php");
require_once("./utils/vars.php");
require_once("./lib/DB_api.php");
$param=$_POST['id'];
$lang=$_POST['lang'];
$ilang=$_POST['ilang'];
$pos=$_POST['pos'];
$link=GetMyConnection();
$table=$lang."Map";
$table_1=$lang."SynsetXsynsetMap";
$result=Array();
$dir="ltr";
if ($ilang=="ara")
    $dir="rtl";
if ($ilang=='eng') {
    $side_conn=SwitchConnection($lang, $link, 1);
    $result=getListOfTargetSynsetId($side_conn,$table,$table_1,$param);
}

elseif ($ilang=='ita')   {
    $side_conn=SwitchConnection($ilang, $link, 3); 
    $resultP=getListOfPivotSynsetId($side_conn, $IWNWN30_ILI_TAB,$param);
    
     $side_conn=SwitchConnection($lang, $link, 1);
     if (count($resultP)==0)
        $paramP="(-1)";
      else {   
     $paramP=" (";
      for ($j=0; $j<count($resultP); $j++){
          if ($j<count($resultP) -1 )
                $paramP=$paramP.$resultP[$j]['pivot'].", ";
           else  
                $paramP=$paramP.$resultP[$j]['pivot'];
      //    array_push($result,$app);
      }
           $paramP=$paramP.")";
    }
       if ($lang!='eng'){
                $result=getListOfTargetSynsetIdWithPivot($side_conn,$table,$table_1,$paramP);
            }
       else {
           $l=0;
           foreach ($resultP as $row){
               //echo $row['pivot'];
                $result[$l]['mapped']=$row['pivot'];
                $l++;
            }
        }
            
}
//print_r($result);
//print_r($resultP);
  $main_conn=SwitchConnection($lang, $link, 0);
$str="";
 // eng is the pivot

    for ($i=0; $i<count($result); $i++){
        $mapped=$result[$i]['mapped'];
        $temp= getDefPosFromSynsetId($main_conn, $SYNSETS_TAB,$mapped);
        for ($j=0; $j<count($temp); $j++){
            $pos=$temp[$j]['pos'];
            $sw=getSerializedWordsFromSynsetId($main_conn, $WSS_VIEW,$mapped, '');
            $definition=$temp[$j]['definition'];
            $definition=addslashes($definition);
            $myStr="<a href=\"javascript:showResBySynsetId('$mapped','','$definition','$lang','$pos',1);\">".$mapped."</a>&nbsp; (".$pos.") ".$definition."      <bdo dir='$dir'> [".$sw. "]</bdo>";
            $mapped=$myStr;
            }
        if ($i==0)
            $str=$mapped;
        else 
            $str=$str."#".$mapped;
    }
echo $str;

?>
