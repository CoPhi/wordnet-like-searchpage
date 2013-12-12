<?php
?>
<!-- div to go back -->
<!-- MAIN DIV: RESULTS FROM SERACH PARAMETERS -->  
<input type='hidden' value='<?php echo $lang; ?>' id='id_ilang'>
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
		  data:"id="+id+"&value="+item+"&def="+def+"&lang="+lang+"&type="+type+"&pos="+pos+"&ilang="+lang,
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
                    data:"id="+id+"&lang="+tlang+"&ilang="+lang+"&pos="+pos,
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
                            myStr="Found "+num+ " mapped synset(s)</br>"
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
     var ilang=document.getElementById("id_ilang").value;
     if (user.trim()==""){
         mydata="id="+id+"&value="+item+"&def="+def+"&lang="+lang+"&nickname=<?php echo $user; ?>&username=<?php echo $username; ?>&pos="+pos+"&ilang="+ilang;
     }
     else {
         mydata="id="+id+"&value="+item+"&def="+def+"&lang="+lang+"&nickname="+user+"&username="+username+"&pos="+pos+"&ilang="+ilang;
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
    var hasNewRule=false;
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
   
   
   // MANAGING ADDITIONAL WORDS
    var newwords = document[name][ 'newwords[]' ];
    var newsynsets = document[name][ 'newsynsets[]' ];
    var newsenses = document[name][ 'newsenses[]' ];
    var newposes = document[name][ 'newposes[]' ];
    var newacts = document[name][ 'newacts[]' ];
    var newrules = document[name][ 'newrules[]' ];
    var nwArr;
    var nwl=0;
    var myStr="-1";
    var addMe=false;
    for (k=0; k<newwords.length; k++){
        var divid="div_new_words_"+lang+"_"+k
        var  cdiv=document.getElementById(divid);
        var cdisplay=cdiv.style.display
        var nwTxtId="nw_"+lang+"_"+k
        //alert (nwTxtId);
        // add 1 to the true dimension by visibility
        var cvalue=document.getElementById(nwTxtId).value;
        if (cdisplay=="block" && cvalue!=""){
            nwl=nwl+1;
            myStr =myStr+"#"+k;
            }
        // alert (cdisplay);
        }
    // there is something visible
    
    //
  if(nwl>0){
   addMe=true;
   //alert (myStr.split("#")+ " -# "+nwl+ " #- ");
   var nwArr = new Array()
   var nsyArr = new Array()
   var nseArr = new Array()
   var naArr = new Array()
   var npArr = new Array()
   var nrArr = new Array()
   var nvsArr = new Array()
   
   var idx=myStr.split("#");
   var idxl=idx.length;
   
  //  alert ("dim nwl"+nwl)
   for (k=0; k<idxl; k++){
       var getme=idx[k];
      
       if (getme>=0){
       //     alert (getme + " " +k)
            //nwArr[k]=newwords[getme].value;
            nwArr.push(newwords[getme].value);
            nsyArr.push(newsynsets[getme].value);
            nseArr.push(newsenses[getme].value);
            naArr.push(newacts[getme].value);
            npArr.push(newposes[getme].value);
            
            if (newrules !=null)
                hasNewRule=true;
            if (hasNewRule){
                nrArr.push(newrules[getme].value);
              //  alert (idx[l]+" "+newrules[getme].value)
        }
           // managing validation value for new words
            var vName="nv_"+getme;
            
            var radios = document[name][vName];
          //  alert (vName+ ", "+radios.length);
            for( j = 0; j < radios.length; j++ ) {
               // alert (radios[j].value)
                if( radios[j].checked ) {
                //   alert( "checked "+radios[j].value+ ", for "+getme );
                   nvsArr.push(radios[j].value);
                }
            }
    }
   
    
    }
   
   }
   //alert ("dim nw "+nwArr.length)
     for (m =0 ; m<   nwl; m++){
        // alert (m)
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
     if (hasRule){
         if (addMe){
            if (hasNewRule)
                mydata={ words : wArr  , synsets : syArr, poses: pArr, senses: seArr, lang:lang, ilang:ilang, synsetid:synsetid, user:myuser, acts: aArr, rules: rArr, vs: vsArr, newrules: nrArr,  newwords : nwArr  , newsynsets : nsyArr, newposes: npArr, newsenses: nseArr,newacts: naArr , newvs: nvsArr}
            else 
                mydata={ words : wArr  , synsets : syArr, poses: pArr, senses: seArr, lang:lang, ilang:ilang, synsetid:synsetid, user:myuser, acts: aArr, rules: rArr, vs: vsArr,  newwords : nwArr  , newsynsets : nsyArr, newposes: npArr, newsenses: nseArr,newacts: naArr, newvs: nvsArr}
       } else {
       mydata={ words : wArr  , synsets : syArr, poses: pArr, senses: seArr, lang:lang, ilang:ilang, synsetid:synsetid, user:myuser, acts: aArr, rules: rArr, vs: vsArr}
        }
    }
    else {
        if (addMe){
            if (hasNewRule)
                mydata={ words : wArr  , synsets : syArr, poses: pArr, senses: seArr, lang:lang, ilang:ilang, synsetid:synsetid, user:myuser, acts: aArr, vs: vsArr, newrules: nrArr,  newwords : nwArr  , newsynsets : nsyArr, newposes: npArr, newsenses: nseArr,newacts: naArr, newvs: nvsArr}
            else 
                 mydata={ words : wArr  , synsets : syArr, poses: pArr, senses: seArr, lang:lang, ilang:ilang, synsetid:synsetid, user:myuser, acts: aArr, vs: vsArr, newwords : nwArr  , newsynsets : nsyArr, newposes: npArr, newsenses: nseArr,newacts: naArr, newvs: nvsArr}
       } else {
            mydata={ words : wArr  , synsets : syArr, poses: pArr, senses: seArr, lang:lang, ilang:ilang, synsetid:synsetid, user:myuser, acts: aArr,vs: vsArr,newacts: naArr}
        }
    }
     var jsonData1 = $.ajax({
          url: "updateresults.php",
		  type: "POST",
		  data: mydata,
          async: false
          }).responseText;
          rsdiv.innerHTML=jsonData1;
}


    
    
}
var maxNewWord=3;
function AddMoreLessWord(comm,lang) {
    var strDivId="id_new_word_"+lang;
    var strNewWordId="nw_"+lang+"_";
    var last=document.getElementById(strDivId);
    var lastId=last.value;
    var lastId4jq=last.value;
    var IntlastId=parseInt(lastId);
    var div="div_new_words_"+lang+"_";
	  if ((comm == "add") && (IntlastId < maxNewWord)) {
        //alert ("adding " +lastId);
	     div = div + lastId;
	     which = document.getElementById(div);
	     which.style.display="block";
         IntlastId=IntlastId+1;
         last.value=IntlastId;
	  }
	  if ((comm == "rm") && (IntlastId > 0)) {
           IntlastId=IntlastId - 1;
           lastId=IntlastId.toString();
            strNewWordId=strNewWordId+lastId;
            //alert (strNewWordId+ " "+lastId)
            div = div + IntlastId;
         // set the value to ""
         var nw=document.getElementById(strNewWordId);
         nw.value="";
	     which = document.getElementById(div);
	    which.style.display="none";
         last.value=IntlastId;
   }
   
}

// various jqueries    
    </script>  