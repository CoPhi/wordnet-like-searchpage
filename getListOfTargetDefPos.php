<?php
require_once("./utils/tablelist.php");
require_once("./utils/vars.php");
require_once("./lib/DB_api.php");
$param=$_POST['id'];
$lang=$_POST['lang'];
$link=GetMyConnection();
//$table=$lang."Map";
$main_conn=SwitchConnection($lang, $link, 0);
$result=getDefPosFromSynsetId($main_conn,$SYNSETS_TAB,$param);
$str="";
for ($i=0; $i<count($result); $i++){
    $definition=$result[$i]['definition'];
    $pos=$result[$i]['pos'];
    $str="<a href=\"javascript:showResBySynsetId('<?php echo $param;?>','','<?php echo $definition;?>','<?php echo $lang;?>');\">".$param." </a>&nbsp;(".$pos.")";
    echo $str."</br>";
}
/*
echo "<p></p>";
$result=getRelsFromSynsetId($main_conn,$SEMLINK_TAB,$REL_TAB,$SYNSETS_TAB,$param);
echo "List of Relations</br>";
echo "Source: ".$param."</br>";
for ($i=0; $i<count($result); $i++){
    $target=$result[$i]['target'];
    $target= "<a href=\"javascript:showSearchBySynsetId('<?php echo $target;?>');\">".$target."</a>";
    $rel=$result[$i]['relation'];
    $tdef=$result[$i]['tdefinition'];
    $tdef=addslashes($tdef);
    $tpos=$result[$i]['tpos'];
    $str=" &nbsp; &nbsp; &nbsp; &nbsp; ".$rel." ".$target. " (".$tpos. ")  ".$tdef;
    
    echo $str."</br>";
}
*/

?>
