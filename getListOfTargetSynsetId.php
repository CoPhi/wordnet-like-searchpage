<?php
/*
adding inner divs
*/
require_once("./utils/tablelist.php");
require_once("./utils/vars.php");
require_once("./lib/DB_api.php");
$param=$_POST['id'];
$lang=$_POST['lang'];
$link=GetMyConnection();
$table=$lang."Map";
$table_1=$lang."SynsetXsynsetMap";
$side_conn=SwitchConnection($lang, $link, 1);
$result=getListOfTargetSynsetId($side_conn,$table,$table_1,$param);
$main_conn=SwitchConnection($lang, $link, 0);

$str="";
for ($i=0; $i<count($result); $i++){
    $mapped=$result[$i]['mapped'];
    $temp= getDefPosFromSynsetId($main_conn, $SYNSETS_TAB,$mapped);
    for ($j=0; $j<count($temp); $j++){
        $pos=$temp[$j]['pos'];
        $definition=$temp[$j]['definition'];
        $definition=addslashes($definition);
        $myStr="<a href=\"javascript:showResBySynsetId('$mapped','','$definition','$lang',1);\">".$mapped."</a>&nbsp; (".$pos.") ".$definition;
        $mapped=$myStr;
        }
    if ($i==0)
        $str=$mapped;
    else 
        $str=$str."#".$mapped;
}
echo $str;
?>
