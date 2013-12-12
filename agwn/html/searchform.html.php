<?php

// type of search
$sbyw="";
$sbys="";
if($type==1)
    $sbys="checked";
if($type==0)
    $sbyw="checked";
    
   // input languages 
 $il_1  ="";
 $il_2 ="";
 $il_3  ="";
 $il_4  ="";
 $il_5  ="";
 
 // disabled output languages
  $old_grc  ="";
 $old_lat ="";
 $old_ita  ="";
 $old_ara  ="";
 $old_eng  ="";
 if ($lang=="eng"){
    $il_1="checked";
    $old_eng  ="disabled";
    }
if ($lang=="grc"){
    $il_2="checked";
    $old_grc ="disabled";
     //$il_1="";
    }
 if ($lang=="ita"){
    $il_3="checked";
    $old_ita ="disabled";
     //$il_1="";
    }
if ($lang=="lat"){
    $il_4="checked";
    $old_lat ="disabled";
     //$il_1="";
    }
if ($lang=="ara"){
    $il_5="checked";
    $old_ara ="disabled";
     //$il_1="";
}
if (empty($lang)){
     $il_1="checked";
     $old_eng="disabled";
     }

// output languages 
$num=count($tlangs);
$ol_grc="";
$ol_lat="";
$ol_ita="";
$ol_ara="";
$ol_eng="";



    // loop
for ($i=0; $i<$num; $i++){

        if ($tlangs[$i]=="grc")
            $ol_grc="checked";
        if ($tlangs[$i]=="lat")
            $ol_lat="checked";   
        if ($tlangs[$i]=="ita")
            $ol_ita="checked";    
        if ($tlangs[$i]=="ara")
            $ol_ara="checked"; 
        if ($tlangs[$i]=="eng")
            $ol_eng="checked";    

}

if ($num==0)
     $ol_grc="checked";

 
/*if (true)
    echo "USER: $user WORD: ".$word." TLANG: $tlang LANG $lang NICK: $nickname PASSWD $passwd $sbys $sbyw $lang $num";
    */
?>

<div id="searchDiv" class="searchDivCls">
<p class="searchP">Specify the input language,  type the word or the synset to search and at least one output language</p>
<form id="searchFrm" action="view.php" method="POST" name="searchFrm">
<table class="searchTabCls" border="0">
<tr>
 <td>
        <fieldset>
        <legend>Select the input language</legend>
        English <input type="radio" name="lang" value="eng" <?php echo $il_1; ?> id="leng" onchange="disableAccordingTgtlang('leng'); getInputlanguage();"/></br>
        Greek  <input type="radio" name="lang" value="grc"  <?php echo $il_2; ?> id="lgrc" onchange="disableAccordingTgtlang('lgrc'); getInputlanguage();"/></br>
        Italian <input type="radio" name="lang" value="ita" <?php echo $il_3; ?> id="lita" onchange="disableAccordingTgtlang('lita'); getInputlanguage();"/></br>
        Latin <input type="radio" name="lang" value="lat" <?php echo $il_4; ?> id="llat" onchange="disableAccordingTgtlang('llat'); getInputlanguage();"/></br>
        Arabic <input type="radio" name="lang" value="ara" <?php echo $il_5; ?> id="lara" onchange="disableAccordingTgtlang('lara'); getInputlanguage();"/></br>
    </fieldset>
    </td>
    <td height="100%">
    <fieldset>
        <legend>Type the word or the synsetid</legend>
        <input type="text" id="searchTxt" class="searchTxtCls" name="elem" value="<?php echo $word; ?>" placeholder='Type a word'>
         Word<input type="radio" name="typeofsearch" value="0"  <?php echo  $sbyw; ?> id="sbyw"/>&nbsp;&nbsp;
         Synsetid<input type="radio" name="typeofsearch" value="1"  <?php echo $sbys; ?> id="sbys"/>
        </fieldset>
        </br>
         <input type="button" value="Search" onclick="formSubmit();">
         <input type="button" value="Reset" onclick="resetForm();">
         </br></br></br>
    </td>
    <td>
        <fieldset>
        <legend>Select the target languages</legend>
        Greek <input type="checkBox" name="tlang[]" value="grc"  <?php echo $ol_grc. " ".$old_grc; ?> id="tlgrc"/></br>
        Latin <input type="checkBox" name="tlang[]" value="lat"  <?php echo $ol_lat. " ".$old_lat; ?> id="tllat"/></br>
        Italian <input type="checkBox" name="tlang[]" value="ita" <?php echo $ol_ita. " ".$old_ita; ?> id="tlita"/></br>
        Arabic <input type="checkBox" name="tlang[]" value="ara" <?php echo $ol_ara. " ".$old_ara; ?> id="tlara" /></br>
        English <input type="checkBox" name="tlang[]" value="eng" <?php echo $ol_eng. " ".$old_eng; ?> id="tleng"/></br>
    </fieldset>
    </td>
 </tr>
  </table>
  <input type="hidden" name="myusername" value="<?php echo $username; ?>" id="hiddenusername"><!-- email -->
  <input type="hidden" name="mynickname" value="<?php echo $nickname; ?>" id="hiddennickname"> 
  <input type="hidden" name="myilang" value="eng" id="hiddenilang"> 
</form>
</div>
<script language="Javascript">

$(function() {
   
    $( "#searchTxt1" ).autocomplete(
    {
 
      source: "ajaxautocomplete.php?field=searchTxt&ilang="+$('#hiddenilang').val(),
     // source: ["chingpo","flagpole","gpo","pingpong paddle","pingpong table",],
      minLength: 1,
    });
});

 $(function() {
    $( ".searchTxtCls" ).autocomplete({
        source: function( request, response ) {
        $.ajax({
        url: "ajaxautocomplete.php?",
        type: "GET",
        dataType: "json",
        data: {
        ilang: $('#hiddenilang').val(),
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
////////////////////////////

function formSubmit(){
    /*checkdate fields and decode*/
    var value=document.getElementById("searchTxt").value;
    var value1=value;
    
    if (value1.trim()==""){
        alert ("There is no item to search for");
        document.getElementById("searchTxt").value=value;
       
       
        } else {
             document.getElementById("searchFrm").submit();
            }
}

function resetForm(){
    /*checkdate fields and decode*/
    document.getElementById("searchTxt").value="";
    document.getElementById("sbyw").checked=true;
    document.getElementById("tlgrc").checked=true;
    document.getElementById("tlita").checked=false;
    document.getElementById("tllat").checked=false;
    document.getElementById("tlara").checked=false;
    document.getElementById("tleng").checked=false;
    document.getElementById("leng").checked=true;
}

function getInputlanguage(){
     var ilangs = document['searchFrm'][ 'lang' ];
     var ret="eng";
     var elem=document.getElementById("hiddenilang");
     for (i=0; i<ilangs.length; i++){
        
         if (ilangs[i].checked){
                ret=ilangs[i].value;
            }
     }
     elem.value=ret;
    }


function disableAccordingTgtlang(id){
    var tgt=["leng","lita","lgrc","llat","lara"]
    document.getElementById(id).checked=true;
    for (i=0; i<tgt.length ; i++){
        var current=tgt[i];
        var tcurrent="t"+current;
         document.getElementById(tcurrent).disabled=false;
         
        if (current==id){
            document.getElementById(tcurrent).disabled=true;
            document.getElementById(tcurrent).checked=false;
            }
    }
}


</script>