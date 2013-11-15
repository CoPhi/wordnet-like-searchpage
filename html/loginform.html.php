<?php
?>
<div id ="div_login" class="divLoginCls">
<form name="formlogin" method="post" action="login.php">
<table  class="tabLoginClass">
<caption>Log to access editing stuff</caption>
<tr>
<td colspan="2">User Login</td>
</tr>
<tr>
<td class="labelTdClass">Username:</td>
<td class="fieldTdClass"><input name="myusername" type="text" id="myusername"></td>
</tr>
<Tr>
<td class="labelTdClass">Password:</td>
<td class="fieldTdClass"><input name="mypassword" type="password" id="mypassword"></td>
</tr>
<td><input type="button" name="Submit" value="Login"  onclick="validateAndSubmitForm()"></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
</table>
</form>
</div>
<div id="div_validate_form" class="divValidateFormCls">
bla bla bla
</div>



<script language="Javascript">
/* get values for user and password
    if fine, then tries to connect
    */
 
function validateAndSubmitForm() {
        var goahead=true;
//String.prototype.trim=function(){return this.replace(/^\s+|\s+$/g, '');};   
		 var rsdiv=document.getElementById("div_validate_form");
         var user=document.getElementById("myusername").value;
         var passwd=document.getElementById("mypassword").value;
         var text="";
       
        
          if (user.trim()=="" && passwd.trim()==""){
             text="<i>Please supply username and password</i>";
             //   alert ("user "+user+ " passwd "+passwd+ " "+text);
             goahead=false;
        } else {
             if (user.trim()==""){
                 goahead=false;
             text="<i>Username cannot be blank</i>";
          //     alert ("user "+user+ " passwd "+passwd+ " "+text);
         }
          if (passwd.trim()==""){
              goahead=false;
             text="<i>Password cannot be blank</i>";
               // alert ("user "+user+ " passwd "+passwd+ " "+text);
         }
        }
        if (!goahead){
         rsdiv.innerHTML=text;
        } else {
            alert ("OK");
            }
    /*
    // Connect to server and select databse.
mysql_connect("$host", "$username", "$password")or die("cannot connect");
mysql_select_db("$db_name")or die("cannot select DB");

// username and password sent from form
$myusername=$_POST['myusername'];
$mypassword=$_POST['mypassword'];

// To protect MySQL injection (more detail about MySQL injection)
$myusername = stripslashes($myusername);
$mypassword = stripslashes($mypassword);
$myusername = mysql_real_escape_string($myusername);
$mypassword = mysql_real_escape_string($mypassword);

$sql="SELECT * FROM $tbl_name WHERE username='$myusername' and password='$mypassword'";
$result=mysql_query($sql);

// Mysql_num_row is counting table row
$count=mysql_num_rows($result);

// If result matched $myusername and $mypassword, table row must be 1 row

if($count==1){

// Register $myusername, $mypassword and redirect to file "login_success.php"
session_register("myusername");
session_register("mypassword");
header("location:login_success.php");
}
else {
echo "Wrong Username or Password";
}
    */
     
/*

        var jsonData1 = $.ajax({
          url: "getResBySynsetId.php",
		  type: "POST",
		  data:"id="+id+"&value="+item+"&def="+def+"&lang="+lang,
          dataType:"json",
          async: false
          }).responseText;
          text=text+jsonData1+'</fieldset>';
          rsdiv.innerHTML=text;
          var tlangs = <?php echo json_encode($tlangs); ?>;  
          for (i=0;i<tlangs.length;i++){
               var tlang=tlangs[i];
           //    alert (tlang);
               var rsdiv=document.getElementById("div_result_sub_"+tlang);
               var text='<fieldset><legend>Results for Target Language <b><i>'+tlang+'</i></b></legend>';
                var targetSynIds=$.ajax({
                    url: "getListOfTargetSynsetId.php",
                    type: "POST",
                    data:"id="+id+"&lang="+tlang,
                    dataType:"json",
                    async: false
                    }).responseText;
                    var str="";
                    var syns = [];
                    syns=targetSynIds.split("#");
                    lstr="";
                     var num=syns.length;
                    var myStr="";
                     if (num==1 && syns[0]==""){
                            myStr="No mapped synsets found";
                        } else {
                            myStr="Found "+num+ " mapped synset(s)"
                        }
                    for (j=0; j<syns.length; j++){
                        var divStr='<div id="div_result_sub_'+tlang+'_'+j+'" class="divResultMainCls_sub">'
                        divStr=divStr+syns[j]+"</div>"
                        lstr=lstr+divStr+"</div>";
                      // alert (syns[j]);
                        str=str+syns[j]+"</br>"
                }
                text=text+myStr;
               text=text+lstr+'</fieldset>';
               //  text=text+'</fieldset>';
                 rsdiv.innerHTML=text;
               }
 }
 if (type==1){
     //alert ( "div_result_sub_"+lang+"_showRes");
     var rsdiv=document.getElementById("div_result_sub_"+lang+"_showRes");
        var text='<fieldset><legend>Results for SynsetId <b><i>'+id+'</i></b></legend>';
        var jsonData1 = $.ajax({
          url: "getResBySynsetId.php",
		  type: "POST",
		  data:"id="+id+"&value="+item+"&def="+def+"&lang="+lang,
          dataType:"json",
          async: false
          }).responseText;
          text=text+jsonData1+'</fieldset>';
          rsdiv.innerHTML=text;
 }
 */
}
</script>