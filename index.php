<?php
?>
<html>
<head>
<link href="./css/global.css" rel="stylesheet" type="text/css">
<meta HTTP-EQUIV="content-type" CONTENT="text/html; charset=UTF-8">
<title>Search page for wordnets</title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
<script src="./js/jquery.treeview.js" type="text/javascript"></script>
<script type="text/javascript" src="./js/md5.js"></script>
</head>
<body>


<?php 
require("./utils/vars.php");
require("./lib/DB_api.php");
global $debug;
$word="";
$lang="";
$tlang="";
$word = $_POST['word'];
$lang=$_POST['lang'];
$word = $_POST['word'];
$user=$_POST['user'];
$passwd=$_POST['passwd'];
$include_html=false;

if (trim($user)===""){
    $user = $_GET['user'];
}

if (trim($passwd)===""){
    $passwd = $_GET['passwd'];
}

if (trim($word)===""){
    $word = $_GET['word'];
 }
 
 if (trim($lang)===""){
    $lang = $_GET['lang'];
     }
 if (trim($tlang)===""){
    $lang = $_GET['tlang'];
     }
//<-- login -->

include('./html/loginform.html.php');
//<!-- end login -->
//<!-- search -->
include('./html/searchform.html.php');
// <!-- end search -->

?>
</body>
</html>