<?php

    
?>

<div id="div_main_login" class="divMainLogin" style="align:center;">
<div id ="div_login" class="divLoginCls">
<div id="div_caption">
You must Login to access editing features. </br></br>
</div>
<table  class="tabLoginClass" border="0">
<tr>
<td colspan="2">Provide username and password </td>
</tr>
<tr>
<td class="labelTdClass">Username:</td>
<td class="fieldTdClass"><input name="myusername" type="text" id="myusername"  value="<?php echo $user;?>"></td>
</tr>
<Tr>
<td class="labelTdClass">Password:</td>
<td class="fieldTdClass"><input name="mypassword" type="password" id="mypassword" value="<?php echo $passwd;?>"></td>
</tr>
<tr>
<td><button id="btnLogin">Login</button></td>
<td>&nbsp;</td>
</tr>
</table>
</div>
<div id="div_validate_form" class="divValidateFormCls" style="align:center;">
</div>
</div>



<script language="javascript">

$( "#btnLogin" ).click(function() {
  validateAndSubmitForm();
});



</script>