<?php
?>
<!-- div to go back -->
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
        <a href="javascript:showResBySynsetId('<?php echo $id;?>','<?php echo $value;?>','<?php echo $definition;?>','<?php echo $lang;?>','<?php echo $pos;?>',0);"><?php echo $id; ?></a>&nbsp; <?php echo "(".$pos.")"; ?>
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
    <!-- DIV TO SAVE AND OTHER STUFF -->
    <div id="div_save"  class="divSaveCls">
    saved stuff will be here
    </div>

<script language="Javascript">

/*
Draw the div_result_sub
*/        
function showResBySynsetId(id, item, def,lang,pos,type)     {
   
 if (type==0){
      clearInnerText();
		 var rsdiv=document.getElementById("div_result_sub");
        var text='<fieldset><legend>Results for SynsetId <b><i>'+id+'</i></b></legend>';
        var jsonData1 = $.ajax({
          url: "getResBySynsetId.php",
		  type: "POST",
		  data:"id="+id+"&value="+item+"&def="+def+"&lang="+lang+"&type="+type+"&pos="+pos,
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
    // var nickname=<?php echo  preg_replace('!\s+!', '#%#', $user); ?>;
     //   var username=<?php echo substr_replace($username,'.','#%#'); ?>;
     var user=document.getElementById("hiddennickname").value;
     var username=document.getElementById("hiddenusername").value;
     var mydata="";
     if (user.trim()==""){
         mydata="id="+id+"&value="+item+"&def="+def+"&lang="+lang+"&nickname=<?php echo $user; ?>&username=<?php echo $username; ?>&pos="+pos;
     }
     else {
         mydata="id="+id+"&value="+item+"&def="+def+"&lang="+lang+"&nickname="+user+"&username="+username+"&pos="+pos;
     }
     mydata=mydata+"&type="+type;
        
        var text='<fieldset><legend>Results for SynsetId <b><i>'+id+'</i></b></legend>';
        var jsonData1 = $.ajax({
          url: "getResBySynsetId.php",
		  type: "POST",
		  //data:"id="+id+"&value="+item+"&def="+def+"&lang="+lang+"&nickname=<?php echo $user; ?>&username=<?php echo $username; ?>",
		  data:mydata,
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
           rsdiv.innerHTML="";
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
               rsdiv.innerHTML="";
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
      rsdiv.innerHTML="";
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
/**
clear all divs for results
*/
function clearInnerText(){
    var rsdiv=document.getElementById("div_result_sub");
    rsdiv.innerHTML="";
     var tlangs = <?php echo json_encode($tlangs); ?>;  
          
        
          for (i=0;i<tlangs.length;i++){
               var tlang=tlangs[i];
           //    alert (tlang);
               var rsdiv=document.getElementById("div_result_sub_"+tlang+"_showRes");
               rsdiv.innerHTML="";
           }
      rsdiv=document.getElementById("div_save");
      rsdiv.innerHTML="";
      
}

function saveMe(lang, synsetid, ilang){
    var name="form_"+lang;
    var rsdiv=document.getElementById("div_save");
     rsdiv.innerHTML="";
    
    //var words = document.getElementsByName( 'words[]' );
    var words = document[name][ 'words[]' ];
    var synsets = document[name][ 'synsets[]' ]; 
    var poses = document[name][ 'poses[]' ];
    var senses = document[name][ 'senses[]' ];
    var rules = document[name][ 'rules[]' ];
    var acts = document[name][ 'acts[]' ];
    var hasRule=false;
    var rl;
    var rArr;
    var myuser = document.getElementById("user_text_"+lang).value;
   // alert (myuser);
    
    var wl=words.length;
    var syl=synsets.length;
    var pl=poses.length;
    var sel=senses.length;
    var al=acts.length;
    if (rules != null){
           rl=rules.length;
            hasRule=true;
        }
   
    if (wl==syl && syl==pl && pl==sel && sel==al){
        var wArr=new Array(wl); //words
        var syArr=new Array(syl); // synsets
        var pArr=new Array(pl); // poses
        var seArr=new Array(syl); // senses
        var aArr=new Array(syl); // activities
        var vsArr=new Array(wl); // validate same as words
        if (hasRule){
            rArr=new Array(rl);
            }
        for( i = 0; i < words.length; i++ ) {
            wArr[i]= words[i].value;
            syArr[i]=synsets[i].value;
            pArr[i]=poses[i].value;
            seArr[i]=senses[i].value;
            aArr[i]=acts[i].value;
            
            if(hasRule)
                rArr[i]=rules[i].value;
                
            // managing validation value
            var vName="v_"+i;
            
            var radios = document[name][vName];
            //alert (vName+ ", "+radios.length);
            for( j = 0; j < radios.length; j++ ) {
               // alert (radios[j].value)
                if( radios[j].checked ) {
                   // alert( radios[j].value+ ", "+i );
                   vsArr[i]=radios[j].value;
        }
        
          
        }
          
            
        }
     var mydata="";
     if (hasRule)
        mydata={ words : wArr  , synsets : syArr, poses: pArr, senses: seArr, lang:lang, ilang:ilang, synsetid:synsetid, user:myuser, acts: aArr, rules: rArr, vs: vsArr}
    else 
        mydata={ words : wArr  , synsets : syArr, poses: pArr, senses: seArr, lang:lang, ilang:ilang, synsetid:synsetid, user:myuser, acts: aArr,vs: vsArr}
     var jsonData1 = $.ajax({
          url: "updateresults.php",
		  type: "POST",
		  data: mydata,
          async: false
          }).responseText;
          rsdiv.innerHTML=jsonData1;
}


    
    
    }

 </script>  