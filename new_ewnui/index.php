<?php
?>
<html>
<head>
<link href="./css/global.css" rel="stylesheet" type="text/css">
<meta HTTP-EQUIV="content-type" CONTENT="text/html; charset=UTF-8">
<title>Search page for wordnets</title>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" id="jq_css">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js" id="jq_js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js" id="jq_min_js"></script>

<script type="text/javascript" src="./js/md5.js"></script>
<script type="text/javascript" src="./js/save.js"></script>
<script type="text/javascript" src="./js/main.js"></script>
<script type="text/javascript" src="./js/ewnui.js.php"></script>
<script type="text/javascript" src="./js/ewnui_drill.js.php"></script>


</head>
<body onload="javasctipt:go2Mobile();" class="bodyCls">
<style name="css">
body {
   // background-image:url('./img/background.png');
  //  opacity:0.7;
}

</style>

<?php 
/*
$uri=$_SERVER['REQUEST_URI'];
$page=substr(strrchr($uri, "/"), 1);
//echo str_replace($page,"",$uri);
*/
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

// start session with the username
//session_start(); //initiate / open session
//$_SESSION['user'] = ""; // store something in the session  

/** include credits **/
include('./html/credits.html.php');
/** include other panels **/
?>
<div id="login_start">
<?php
if(isset($_SESSION['user']) && $_SESSION['user'] !="" ) {
  echo "<li><a href='#' id='logOut' onclick='logOut();'>Log Out</a></li>";
}
include('./html/tabs.html.php');
?>
</div>

<script>
$(document).ready(function() {
    $('.bodyCls').keydown(function(event) {
        if (event.keyCode == 13) {
            //alert ('this.form.submit()');
            return false;
         }
    });
});

</script>
</body>
</html>