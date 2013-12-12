<?php
// logged user. Loaded when the user paramter is set

?>
<div id="div_credits">
<table>
<tr><td>Powered by <a href="mailto:riccardo.delgratta@il.cnr.ir">Riccardo Del Gratta</a> </td><td>&nbsp;&nbsp;&nbsp;&nbsp;View <a href="gnu_lic.html" target="_blank">License</a></td></tr>
</table>
</div>
</br>
</br>
<div id="div_main_login" class="divMainLogin">
Logged as <?php echo $nickname ;?> <a href="javascript:logout();"> Logout </a>
<div id ="div_login" class="divLoginCls"></div>
</div>
<div id="div_validate_form" class="divValidateFormCls">
</div>
<script language="Javascript">
/* get values for user and password
    if fine, then tries to connect
    */
 
function validateAndSubmitForm() {
        var goahead=true;
//String.prototype.trim=function(){return this.replace(/^\s+|\s+$/g, '');};   
		 var rsdiv=document.getElementById("div_validate_form");
         var maindiv=document.getElementById("div_main_login");
         var user=document.getElementById("myusername").value.trim();
         var passwd=document.getElementById("mypassword").value.trim();
         var text="";
         var chk="Wrong Username or Password";
      
          if (user=="" && passwd==""){
             text="<i>Please supply username and password</i>";
             //alert (" BB user "+user+ " passwd "+passwd+ " "+text);
             goahead=false;
        } else {
             if (user==""){
                 goahead=false;
             text="<i>Username cannot be blank</i>";
           //alert (" UB user "+user+ " passwd "+passwd+ " "+text);
         }
          if (passwd==""){
              goahead=false;
             text="<i>Password cannot be blank</i>";
             //alert (" PB user "+user+ " passwd "+passwd+ " "+text);
             
         }
        }
        if (!goahead){
         rsdiv.innerHTML="<i>"+text+"</i>";
        } else {
           
            passwd=MD5(passwd);
              //alert ("user "+user+ " passwd "+passwd);
             var jsonData1 = $.ajax({
          url: "getUserToken.php",
		  type: "POST",
		  data:"user="+user+"&passwd="+passwd,
          dataType:"json",
          async: false
          }).responseText;
       
        if (jsonData1==chk){
            rsdiv.innerHTML="<i>"+jsonData1+"</i>";
        } else {
            document.getElementById("hiddennickname").value=jsonData1;
         jsonData1="Logged as "+jsonData1 +" <a href=\"javascript:logout();\"> Logout </a>";
          maindiv.innerHTML="<i>"+jsonData1+"</i>";
          
          document.getElementById("hiddenusername").value=user;
           //document.getElementById("hiddenpasswd").value=passwd;
       }
        }
}

function logout(){
     var maindiv=document.getElementById("div_main_login");
     text='<div id ="div_login" class="divLoginCls"></br>';
text=text+'<table  class="tabLoginClass"><caption>Log to access editing stuff</caption>';
text=text+'<tr><td colspan="2">User Login</td></tr><tr>';
text=text+'<td class="labelTdClass">Username:</td><td class="fieldTdClass"><input name="myusername" type="text" id="myusername"></td>';
text=text+'</tr><Tr><td class="labelTdClass">Password:</td><td class="fieldTdClass"><input name="mypassword" type="password" id="mypassword"></td>';
text=text+'</tr><Tr><td class="labelTdClass">Password:</td><td class="fieldTdClass"></td>';
text=text+'</tr><td><input type="button" name="Submit" value="Login"  onclick="validateAndSubmitForm()"></td><td>&nbsp;</td>';
text=text+'<td>&nbsp;</td><td>&nbsp;</td></tr></table></br>';
text=text+'</div><div id="div_validate_form" class="divValidateFormCls"></div>';
     maindiv.innerHTML=text;
      document.getElementById("hiddenusername").value="";
           document.getElementById("hiddennickname").value="";
            var tlangs = <?php echo json_encode($tlangs); ?>;  
             if (tlangs != null){
            for (i=0;i<tlangs.length;i++){
              var lang=tlangs[i];
              var id="button_update_"+lang;
              var idTxt="user_text_"+lang;
              
              var elem=document.getElementById(id);
             // alert ("-"+id+"-");
              if (elem !=null){
                elem.disabled=true;
            }
            
             var elemTxt=document.getElementById(idTxt);
             // alert ("-"+id+"-");
              if (elemTxt !=null){
                elemTxt.value="";
                }
              }
        }
    }
</script>