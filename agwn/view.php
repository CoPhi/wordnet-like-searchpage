<?php
?>
<html>
<head>
<link href="./css/global.css" rel="stylesheet" type="text/css">
<meta HTTP-EQUIV="content-type" CONTENT="text/html; charset=UTF-8">
<title>Search page for wordnets (view results)</title>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
</head>
<?php 
require("./utils/vars.php");
include("./lib/DB_api.php");
global $debug;
global $database;
global $wn_database;
global $ita_database;
global $side_database;
$lang="";
$tlangs="";
$type="";
$htype="word";
$value = "";
$nickname="";
$username="";
$passwd="";
$include_html=true;
?>
<html>
<head>
<title>View</title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
<script src="./js/jquery.treeview.js" type="text/javascript"></script>
<script type="text/javascript" src="./js/md5.js"></script>
</head>
<body>
<?php 
/*
typeof search
0 -> word
1 -> synset
*/
$username = (isset($_POST['myusername']) ? $_POST['myusername'] : $_GET['myusername']); 
$lang = (isset($_POST['lang']) ? $_POST['lang'] : $_GET['lang']); 
$tlangs = (isset($_POST['tlang']) ? $_POST['tlang'] : $_GET['tlang']); 
$nickname = (isset($_POST['mynickname']) ? $_POST['mynickname'] : $_GET['mynickname']); 
$passwd = (isset($_POST['mypassword']) ? $_POST['mypassword'] : $_GET['mypassword']); 
$type = (isset($_POST['typeofsearch']) ? $_POST['typeofsearch'] : $_GET['typeofsearch']); 
$value = (isset($_POST['elem']) ? $_POST['elem'] : $_GET['elem']); 



if ($type==1){
    $htype="synsetid";
}

if($nickname==""){
    include('./html/loginform.html.php');
} else {
    include('./html/loggeduser.html.php');
    }
//$value=utf8_decode($value);
 
 // check target tanguages at least one is needed
if (empty($tlangs)){
    echo "You should select at least one target language. </br>";
    $include_html=false;
}

$strInput="";
   for ($i=0; $i<count($tlangs);$i++){
      
          $str='<input type="hidden" value = "'.$tlangs[$i].'" name="tlang[]">';
          $strInput=$strInput.$str;
     
 }
 
?>
<div id="div_go_back" class="divGoBack">
<form action ="./index.php" method="POST">
<input type="hidden" value = "<?php echo $nickname; ?>" name="nickname" id="hiddennickname">
<input type="hidden" value = "<?php echo $username; ?>" name="username" id="hiddenusername"> <!-- the email -->
<input type="hidden" value = "<?php echo $value; ?>" name="elem">
<input type="hidden" value = "<?php echo $type; ?>" name="type">
<input type="hidden" value = "<?php echo $lang; ?>" name="lang">

<?php 
echo $strInput;
?>
<input type="Submit" value="Back to Search Page" />
</form>
</div>
<?php
if ($debug==1){
    echo "Searching for type: $htype and language: $lang and value $value</br>";
}
if($include_html){
$link=GetMyConnection();
$main_conn=SwitchConnection($lang, $link, 0);
if ($link && $main_conn){
    $res=getSynsetIdsFromWord($main_conn,$WSS_VIEW,$value);
     include('./html/viewresults.html.php');
 }
 }
?>
<script language="Javascript">
   /* $(function() {
    $( "#nw_grc_0" ).autocomplete({
        source: function( request, response ) {
        $.ajax({
        url: "ajaxautocomplete1.php?",
        type: "GET",
        dataType: "json",
        data: {
        ilang: $('#hiddenlang').val(),
        field: "newWord",
        maxRows: 12,
        term: request.term
    },
    success: function( data ) {
        response( $.map( data.low, function( item ) {
            return {
                //label: item.name + (item.adminName1 ? ", " + item.adminName1 : "") + ", " + item.countryName,
                value: item.word
            }
        }));
        }
    });
    },
minLength: 3,
select: function( event, ui ) {
//alert( ui.item ?
//"Selected: " + ui.item.label :
//"Nothing selected, input was " + this.value);
},

});
});
*/

    
    $(document).ready(function(){
    $("#nw_grc_0").click(function(){
        alert("Ciao!");
    });
});

function autocompleteme(id,lang) {
   // alert (myid);
 $(function() {
    $( "#"+id ).autocomplete({
        source: function( request, response ) {
        $.ajax({
        url: "ajaxautocomplete.php?",
        type: "GET",
        dataType: "json",
        data: {
        ilang: lang,
        field: "searchTxt",
        maxRows: 12,
        term: request.term
    },
    success: function( data ) {
        response( $.map( data.low, function( item ) {
            return {
                //label: item.name + (item.adminName1 ? ", " + item.adminName1 : "") + ", " + item.countryName,
                value: item.word
            }
        }));
        }
    });
    },
minLength: 3,
select: function( event, ui ) {
//alert( ui.item ?
//"Selected: " + ui.item.label :
//"Nothing selected, input was " + this.value);
},

});
});
    }
</script>
</body>
</html>
