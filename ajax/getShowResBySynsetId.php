<?php
/*require_once("../utils/tablelist.php");
require_once("../utils/vars.php");
require_once("../lib/DB_api.php");
*/
global $hostname;
global $username;
global $password;
global $database;
global $debug;
$param=$_POST['id'];
echo "XXXXXX ".$param ;
$j=0;
$k=1;
$l=0;

$cols = '{"cols":[{"id":"'.$j.'","label":"Couple","type":"string"},{"id":"'.$k.'","label":"Resource per Langue Couple","type":"number"}],';
$sersult=getWordsFromSynsetId($link,$WSS_VIEW,$param);
echo "TOT ".count($result);
/*$mods=array();
$vals=array();
for ($i = 0; $i < count($result); $i++){
	$rows[] = '{"c":[{"v":'.'"'. $result[$i]['Couple'].'"},{"v":'. $result[$i]['tot'].'}]}';
	$l++;
}
$google_JSON_row =implode(",",$rows);
echo $cols . '"rows":[',$google_JSON_row ."]}";
*/
?>
