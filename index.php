<?php
?>
<html>
<head>
<link href="./css/global.css" rel="stylesheet" type="text/css">
<meta HTTP-EQUIV="content-type" CONTENT="text/html; charset=UTF-8">
<title>Search page for wordnets</title>
</head>
<body>
<!-- login -->
<?php
include('./html/loginform.html.php');

?>
<!-- end login -->
<?php 
require("./utils/vars.php");
require("./lib/DB_api.php");
global $debug;
$word="";
$lang="";
$tlang="";
$word = $_POST['word'];
$lang=$_POST['lang'];
$tlang=$_POST['tlang'];
$include_html=false;

if (trim($word)===""){
    $word = $_GET['word'];
 }
 
 if (trim($lang)===""){
    $lang = $_GET['lang'];
     }
 if (trim($tlang)===""){
    $lang = $_GET['tlang'];
     }

include('./html/searchform.html.php');

?>
</body>
</html>