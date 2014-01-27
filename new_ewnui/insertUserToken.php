<?php 

require_once("./utils/tablelist.php");
require_once("./utils/vars.php");
require_once("./lib/DB_api.php");
require_once("./lib/wnsp_api.php");

// Connect to server and select databse.
$link=GetMyConnection();
$conn=SwitchConnection('', $link, 2);
// username and password sent from form
$myusername=$_POST['user'];
$mysalt=$_POST['mysalt'];
$mypassword=$_POST['passwd'];
$u=11;
$p=9;


// To protect MySQL injection (more detail about MySQL injection)
$myusername = stripslashes($myusername);
$mypassword = stripslashes($mypassword);
$myusername = mysql_real_escape_string($myusername);
$myusername_1 = $myusername;

$mypassword = mysql_real_escape_string($mypassword);



$username_chk="Wrong Username or Password";
$username_sql_err="SQL Error";

$username=getUserDetails($conn, $USER_TAB,$myusername,md5($mypassword));
$ret="";
if ($username==$username_sql_err){
    $ret=$username_sql_err;
}
else{
    if ($username==$username_chk){
        $ret=$username_chk;
    } else {
        $u_l=strlen($myusername);
        $l_sub=abs($u_l-$u);
        $myusername=substr($myusername,0,$l_sub-1).$mysalt.substr($myusername,$l_sub,$u_l);

        $p_l=strlen($mypassword);
        $p_sub=abs($p_l-$p);
        $mypassword=substr($mypassword,0,$p_sub-1).$mysalt.substr($mypassword,$p_sub,$p_l);
        $user=md5($myusername);
         $str="Insert into $USER_LOG_TAB (username,tokenid,mysalt) VALUES ('".$myusername_1."','".$user."',$mysalt);";
        $val=doInsertUserIntoLogged($conn,$str);
        if ($val==0){
            $ret=$username.'%'.$user.'%'.$mysalt;
        } else{
            $ret=$username_sql_err;
            }
    }
}
echo $ret;
?>