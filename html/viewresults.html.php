<?php
?>
<!-- div to go back -->
<div id="div_go_back" class="divGoBack">
<form action ="./index.php">
<input type="Submit" value="Go Back" />
</form>
</div>
<!-- MAIN DIV: RESULTS FROM SERACH PARAMETERS -->         
<div id="div_result_main" class="divResultMainCls">
<fieldset><legend>Results for searchtype: <b><i><?php echo $htype; ?></i></b> and item: <b><i><?php echo $value; ?></i></b> and input language: <b></i><?php echo $lang?></i></b></legend>
<?php
echo "Found ".count($res)." synsets containing $value </br>";
?>
<!-- each synset has its div: different id but same class to be furtherly styled -->

<?php
for ($i=0; $i<count($res); $i++){
    $id=$res[$i]['synsetid'];
    $definition=$res[$i]['definition'];
    $definition = addslashes($definition);
    $pos=$res[$i]['pos'];
    ?>
    
    <div id="div_result_main_<?php echo $id; ?>" class = "divResultMainCls_sub">
        <a href="javascript:showResBySynsetId('<?php echo $id;?>','<?php echo $value;?>','<?php echo $definition;?>','<?php echo $lang;?>',0);"><?php echo $id; ?></a>&nbsp; <?php echo "(".$pos.")"; ?>
        <?php echo $definition; ?></br>
    </div>
    <?php
}
?>
</fieldset>
</div> <!-- END MAIN DIV -->
<p></p>
<!-- DIV FOR WORDS AND RELATIONS -->
<div id="div_result_sub" class="divResultSubCls">
<!-- SHOULD CONTAIN OTHER DIV FOR STYLING-->

</div>
<!-- END DIV FOR WORDS AND RELATIONS -->

    <!-- DIV FOR OUTPUT LANGUAGES INPUT AND OUTPUT -->
    <?php
for ($i=0; $i<count($tlangs);$i++){
    ?>
    <div id="div_result_sub_<?php echo $tlangs[$i]; ?>" class="divResultSubClsTgt">
   
    </div>
    
    
     <div id="div_result_sub_<?php echo $tlangs[$i]; ?>_showRes" class="divResultSubClsTgt">

    </div>
    
    <?php
    } 
    ?>
    <!-- END DIV FOR OUTPUT LANGUAGES INPUT AND OUTPUT-->

<script>

/*
Draw the div_result_sub
*/        
function showResBySynsetId(id, item, def,lang,type)     { 
 if (type==0){
		 var rsdiv=document.getElementById("div_result_sub");
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
 }


/*
Draw the div_result_sub
*/        
function showWordsBySynsetId(id, lang,relid,type)     { 
 if (type==0){
		 var rsdiv=document.getElementById("div_result_sub_lor_"+id+"_"+lang+"_"+relid);
        var text='<fieldset><legend>Results for SynsetId <b><i>'+id+'</i></b></legend>';
        var jsonData1 = $.ajax({
          url: "getResBySynsetId.php",
		  type: "POST",
		  data:"id="+id+"&lang="+lang,
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
 }
 </script>  