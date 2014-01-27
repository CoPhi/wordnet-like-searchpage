<?php

$myusername=$_POST['user'];
$mysalt=$_POST['mysalt'];
$ret=-1;
require_once("./utils/tablelist.php");
require_once("./utils/vars.php");
require_once("./lib/DB_api.php");
require_once("./lib/wnsp_api.php");

$u=11;
// Connect to server and select databse.
$link=GetMyConnection();
$conn=SwitchConnection('', $link, 2);
$myusername = stripslashes($myusername);

$myusername = mysql_real_escape_string($myusername);
$myusername_1 = $myusername;
$u_l=strlen($myusername);
$l_sub=abs($u_l-$u);
$myusername=substr($myusername,0,$l_sub-1).$mysalt.substr($myusername,$l_sub,$u_l);
$myusername=md5($myusername);
$str="DELETE FROM $USER_LOG_TAB where tokenId='".$myusername."' and mysalt='$mysalt';";
//$str="DELETE FROM $USER_LOG_TAB ;";//where tokenId='".$myusername."' and mysalt='$mysalt';";
$val=doDeleteUserFromLogged($conn,$str);
echo $val;

?>