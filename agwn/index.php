<?php
?>
<html>
<head>
<link href="./css/global.css" rel="stylesheet" type="text/css">
<meta HTTP-EQUIV="content-type" CONTENT="text/html; charset=UTF-8">
<title>Search page for wordnets</title>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
<script type="text/javascript" src="./js/md5.js"></script>
</head>
<body>


<?php 
require("./utils/vars.php");
require("./lib/DB_api.php");
global $debug;
$word="";
$lang="";
$tlangs="";
$user="";
$nickname="";
$passwd="";
$type=0;

$include_html=false;

/**
Managing GET and POST variables
The first time the index is executed all variables are empty
*/

$username = (isset($_POST['username']) ? $_POST['username'] : $_GET['username']); 
$word = (isset($_POST['elem']) ? $_POST['elem'] : $_GET['elem']); 
$lang = (isset($_POST['lang']) ? $_POST['lang'] : $_GET['lang']); 
$tlangs = (isset($_POST['tlang']) ? $_POST['tlang'] : $_GET['tlang']); 
$nickname = (isset($_POST['nickname']) ? $_POST['nickname'] : $_GET['nickname']); 
//$passwd = (isset($_POST['passwd']) ? $_POST['passwd'] : $_GET['passwd']); 
$type = (isset($_POST['type']) ? $_POST['type'] : $_GET['type']); 



//<-- login -->

if($username=="" AND $nickname==""){
    include('./html/loginform.html.php');
} else {
    include('./html/loggeduser.html.php');
}

//<!-- end login -->
//<!-- search -->
include('./html/searchform.html.php');
// <!-- end search -->

?>
</body>
</html>