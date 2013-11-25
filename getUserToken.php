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
$mypassword=$_POST['passwd'];

// To protect MySQL injection (more detail about MySQL injection)
$myusername = stripslashes($myusername);
$mypassword = stripslashes($mypassword);
$myusername = mysql_real_escape_string($myusername);
$mypassword = mysql_real_escape_string($mypassword);

$username_chk="Wrong Username or Password";
$username_sql_err="SQL Error";

$username=getUserDetails($conn, $USER_TAB,$myusername,$mypassword);

if ($username==$username_sql_err){
    $ret=$username_sql_err;
}
else{
    if ($username==$username_chk){
        $ret=$username_chk;
    } else {
        session_register("myusername");
        session_register("mypassword");
        $ret=$username;
        }
}

echo $ret;
?>