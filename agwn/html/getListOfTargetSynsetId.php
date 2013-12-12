<?php
require_once("./utils/tablelist.php");
require_once("./utils/vars.php");
require_once("./lib/DB_api.php");
$param=$_POST['id'];
$lang=$_POST['lang'];
$link=GetMyConnection();
$table=$lang."Map";
$main_conn=SwitchConnection($lang, $link, 1);
$result=getListOfTargetSynsetId($main_conn,$table,$param);
/*echo "List of Mapped synsets</br>";
for ($i=0; $i<count($result); $i++){
    $theword=$result[$i]['mapped'];
    $thepos=$result[$i]['pos'];
    $thesense=$result[$i]['sensenum'];
    if ($theword===$word)
       $str=$theword="<b><i>".$theword." </i></b> (".$thesense.")  (".$thepos.")";
       
    else{
        $str =$theword."  (".$thesense.")  (".$thepos.")";
        }
    echo $str."</br>";
    }
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
