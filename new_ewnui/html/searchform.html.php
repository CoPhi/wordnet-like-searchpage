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
 $il_6  ="";
 
 // disabled output languages
  $old_grc  ="";
 $old_lat ="";
 $old_ita  ="";
 $old_ara  ="";
 $old_eng  ="";
 $old_hrv  ="";
 
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

if ($lang=="hrv"){
    $il_6="checked";
    $old_hrv ="disabled";
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
$ol_hrv="";



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
        if ($tlangs[$i]=="hrv")
            $ol_hrv="checked";     
        if ($tlangs[$i]=="eng")
            $ol_eng="checked";    

}

if ($num==0)
     $ol_grc="checked";

?>
<div id="searchDiv" class="searchDivCls">
<p class="searchP">Specify the input language,  type the word or the synset to search and at least one output language</p>
<form id="searchFrm" method="POST" name="searchFrm">
<table class="searchTabCls" border="0">
<tr>
 <td>
        <fieldset class="ui-widget ui-widget-content">
        <legend>Select the input language</legend>
        English <input type="radio" name="lang" value="eng" <?php echo $il_1; ?> id="leng" onchange="disableAccordingTgtlang('leng'); getInputlanguage();"/></br>
        Greek  <input type="radio" name="lang" value="grc"  <?php echo $il_2; ?> id="lgrc" onchange="disableAccordingTgtlang('lgrc'); getInputlanguage();"/></br>
        Italian <input type="radio" name="lang" value="ita" <?php echo $il_3; ?> id="lita" onchange="disableAccordingTgtlang('lita'); getInputlanguage();"/></br>
        Latin <input type="radio" name="lang" value="lat" <?php echo $il_4; ?> id="llat" onchange="disableAccordingTgtlang('llat'); getInputlanguage();"/></br>
        Arabic <input type="radio" name="lang" value="ara" <?php echo $il_5; ?> id="lara" onchange="disableAccordingTgtlang('lara'); getInputlanguage();"/></br>
        Croatian <input type="radio" name="lang" value="hrv" <?php echo $il_6; ?> id="lhrv" onchange="disableAccordingTgtlang('lhrv'); getInputlanguage();"/></br>
    </fieldset>
    </td>
    <td height="100%">
    <fieldset class="ui-widget ui-widget-content">
        <legend>Type the word or the synsetid</legend>
        <input type="text" id="searchTxt" class="searchTxtCls" name="elem" value="<?php echo $word; ?>" placeholder='Type a word'>
         Word<input type="radio" name="typeofsearch" value="0"  <?php echo  $sbyw; ?> id="sbyw"/>&nbsp;&nbsp;
         Synsetid<input type="radio" name="typeofsearch" value="1"  <?php echo $sbys; ?> id="sbys"/>
        </fieldset>
        </br>
    </td>
    <td>
        <fieldset class="ui-widget ui-widget-content">
        <legend>Select the target languages</legend>
        Greek <input type="checkBox" name="tlang[]" value="grc"  <?php echo $ol_grc. " ".$old_grc; ?> id="tlgrc"/></br>
        Latin <input type="checkBox" name="tlang[]" value="lat"  <?php echo $ol_lat. " ".$old_lat; ?> id="tllat"/></br>
        Italian <input type="checkBox" name="tlang[]" value="ita" <?php echo $ol_ita. " ".$old_ita; ?> id="tlita"/></br>
        Arabic <input type="checkBox" name="tlang[]" value="ara" <?php echo $ol_ara. " ".$old_ara; ?> id="tlara" /></br>
        Croatian <input type="checkBox" name="tlang[]" value="hrv" <?php echo $ol_ara. " ".$old_ara; ?> id="tlhrv" /></br>
        English <input type="checkBox" name="tlang[]" value="eng" <?php echo $ol_eng. " ".$old_eng; ?> id="tleng"/></br>
    </fieldset>
    </td>
    <td>
        <fieldset class="ui-widget ui-widget-content">
        <legend>Select Domain Resources</legend>
        Italian Geodomain<input type="checkBox" name="dr[]" value="igd"   id="dr_igd"/></br>
        English Geodomain<input type="checkBox" name="dr[]" value="egd"   id="dr_egd"/></br>
        Mariterm<input type="checkBox" name="dr[]" value="mt"   id="dr_mt"/></br>
    </fieldset>
    </td>
 </tr>
  </table>
  <input type="hidden" name="myusername" value="<?php echo $username; ?>" id="hiddenusername"><!-- email -->
  <input type="hidden" name="mynickname" value="<?php echo $nickname; ?>" id="hiddennickname"> 
  <input type="hidden" name="myilang" value="eng" id="hiddenilang"> 
  <input type="hidden" name="mycursyn" value="" id="mycursyn"> 
  <input type="hidden" name="mycurpos" value="" id="mycurpos"> 
</form>
</div>
<div>
    <button id="btnSubmit">View Results</button>
    <button id="btnReset">Reset Form</button>
    <button id="btnNewSearch">New Search</button>
</div>

<script language="Javascript">
 $(function() {
     $( "#searchTxt" ).autocomplete({
         source: function( request, response ) {
        $.ajax({
            url: "ajaxautocomplete.php?",
            type: "POST",
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
        /*alert( ui.item ?
        "Selected: " + ui.item.label :
        "Nothing selected, input was " + this.value);
        */
        },
    });
});
           




$('#btnSubmit').click( function() {
    validateFormAndAddPanelWithResults();
});

$("#btnReset" ).click(function() {
  resetForm();
});

$("#btnNewSearch" ).click(function() {
  resetForm();
  resetTabs();
});

</script>
