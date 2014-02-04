
/**
main JS functions
**/

/*
disables the target languages according to the input language
*/
function disableAccordingTgtlang(id){
    var tgt=["leng","lita","lgrc","llat","lara","lhrv"]
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
/*
gets the input language. Used for autocompletion
*/
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

/*
Guess if a mobile is querying
27/12/2013 return FALSE ANY CASE
*/
function isMobile(){
    var isMobile = {
        Android: function() {
            return navigator.userAgent.match(/Android/i);
        },
        BlackBerry: function() {
            return navigator.userAgent.match(/BlackBerry/i);
        },
        iOS: function() {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        },
        Opera: function() {
            return navigator.userAgent.match(/Opera Mini/i);
        },
        Windows: function() {
            return navigator.userAgent.match(/IEMobile/i);
        },
        any: function() {
            return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
        }
    };

    if(isMobile.any()){
        //return true;
        return false;
    } else {
        return false;
    }
}

/*
switch to mobile
TBD
*/
function go2Mobile(){
    var ldir="m";
    var location = window.location.host;
    var pathname =  window.location.pathname;
    var protocol =  window.location.protocol;
    var page=pathname.substring(pathname.lastIndexOf("/") + 1);
    pathname=pathname.replace(page,"");
    var destination=protocol+"//"+location+"/"+pathname+"/"+ldir+"/"+page
    
    if(isMobile()) {
       // alert (destination)
        window.location = destination;
    }
        
}

/**
Login and validate
**/
//login
function validateAndSubmitForm() {

   
  var goahead=true;
	var rsdiv=document.getElementById("div_validate_form");
	var ulog=document.getElementById("userloginpanel");
   var maindiv=document.getElementById("div_main_login");
   var capdiv=document.getElementById("div_caption");
  
  var user=document.getElementById("myusername").value.trim();
  var passwd=document.getElementById("mypassword").value.trim();
  //var mysalt=document.getElementById("mysalt").value.trim();
  var mysalt = Math.round(new Date().getTime() / 1000);
  //alert (mysalt);
  var text="";
  var chk="Wrong Username or Password";
  var err="SQL Error";
      
  if (user=="" && passwd==""){
     text="<i>Please supply username and password</i>";
     //alert (" BB user "+user+ " passwd "+passwd+ " "+text);
     goahead=false;
  }
  else {
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
  } 
  else {
    passwd=MD5(passwd);
    var jsonData1 = $.ajax({
        url: "insertUserToken.php",
        type: "POST",
        data:"user="+user+"&passwd="+passwd+"&mysalt="+mysalt,
        dataType:"json",
        async: false
    }).responseText;
  
    if (jsonData1==chk || jsonData1==err){
        rsdiv.innerHTML="<i>"+jsonData1+"</i>";
    } else {
        var test=jsonData1.split("%");
        var myname=test[0];
        var log_1=test[1];
        var log=test[2];
        jsonData1="Logged as "+myname +". TokenId: "+log_1+"&nbsp;&nbsp;<img src='./img/logout.png' alt='Logout' onclick=\"javascript:logout();\" />";
        maindiv.innerHTML="<a href=\"javascript:showMyActivity('"+user+"');\">My Activity</a><input name=\"myusername\" type=\"hidden\" id=\"myusername\"  value=\""+user+"\"><input name=\"mysalt\" type=\"hidden\" id=\"mysalt\"  value=\""+log+"\">";
        capdiv.innerHTML="";
        ulog.innerHTML="<i>"+jsonData1+"</i>";
    }
  }
    // Logged then enable edit functionalities
    $('span.ui-icon-pencil').each(function() {
        main="#div_search";
        var panelId = $( this ).closest( "li" ).attr( "aria-controls" );
        //alert (panelId);
        
        $(this).show();
        
    });
    $( "#panels" ).accordion( "option", "icons", {"header": "ui-icon-triangle-1-e", "activeHeader": "ui-icon-triangle-1-s"} );
    
    
}

function saveMe(name, synsetid, lang, s) {
    var user=document.getElementById("myusername").value;
    var mapped_lang=document.getElementById("id_ilang").value;
    var mapped_synset=document.getElementById("mycursyn").value;
    var mapped_pos=document.getElementById("mycurpos").value;
    switch(s){
        case 0:
            //alert (s)
            saveMeGloss(name, synsetid, lang,user,mapped_lang,mapped_synset,mapped_pos)
            break;
        case 1:
            saveMeWs(name, synsetid,lang,user,mapped_lang,mapped_synset,mapped_pos)
            break;
        case 2:
            saveMeSr(name, synsetid,lang,user,mapped_lang,mapped_synset,mapped_pos)
            break;
        case 3:
            alert (s)
            saveMeTr(name, synsetid,lang,user,mapped_lang,mapped_synset,mapped_pos)
            break;
        default:
    }

    

}

function saveMeGloss(name, synsetid, lang,user,mapped_lang,mapped_synset,mapped_pos){
    var pos,gloss, log;
   // name=name+"gloss";
    //alert (name);
    var div_save=name+"_save";
    var act="mgp";
    var mydata="";
    var rsdiv=document.getElementById(div_save);
    
    pos = document[name][ 'pos' ].value;
    gloss = document[name][ 'gloss' ].value;
    
    // saving 
    mydata={lang:lang,def:gloss,pos:pos,synset:synsetid,ilang:mapped_lang,type:"mgp",synsetid:mapped_synset, act:act,user:user,mpos:mapped_pos};
    var jsonData1 = $.ajax({
        url: "updateresults.php",
		  type: "POST",
		  data: mydata,
        async: false
        }).responseText;
    
    log="Successfully inserted </br> ";user+", "+pos+", "+gloss+", "+synsetid+", "+lang;
    log=log+" user:" +user+"</br>";
    log=log+" mapped language: "+mapped_lang+"</br>";
    log=log+" mapped pos: "+mapped_pos+"</br>";
    log=log+" mapped synset:" +mapped_synset+"</br>";
    log=log+" current synset: "+synsetid+"</br>"; 
    log=log+" current language: "+lang+"</br>";
    log=log+" current definition: "+gloss+"</br>";
    log=log+" current pos:" +pos+"</br>";
    log=log+" activity: "+act+"</br>"; 


    if (jsonData1==1)
        rsdiv.innerHTML=log;
    else 
        rsdiv.innerHTML=jsonData1;
    
    $("#"+div_save).show();
   

}
/**
saves words and new words
added check on 0 words and 1 words
separated the behaviour between words and newwords, since newwords always exixt. 
**/

function saveMeWs(name, synsetid, lang,user,mapped_lang,mapped_synset,mapped_pos){
    var ilang=mapped_lang;
    var lsyn=synsetid;
    var synsetid=mapped_synset;
    //var myuser="riccardo.delgratta@gmail.com";
    var myuser=user;
    var pos,gloss, log;
    var div_save=name+"_save";
    var mydata="";
    //alert (name);
    var hasWords=true;
    var mydata=""
    var isWordArray=false;
    var rsdiv=document.getElementById(div_save);
    
    var words = document[name][ 'words[]' ];
    var synsets = document[name][ 'synsets[]' ]; 
    var poses = document[name][ 'poses[]' ];
    var senses = document[name][ 'senses[]' ];
    var rules = document[name][ 'rules[]' ];
    var acts = document[name][ 'acts[]' ];
    var hasRule=false;
    var hasNewRule=false;
    var wl;
    var syl;
    var pl;
    var sel;
    var al;
    var rl;
    var rArr;
    
    if (rules != null){
        hasRule=true;
    }
    
    // is words defined?
    if (words == undefined)
        hasWords=false;

    // is words an array >1 word?
    if (hasWords){
        wl=words.length;
        if ( wl  != undefined) {
            isWordArray=true;
            //alert('value is Array with size: '+wl+ " "+isWordArray);
            // setting length
            wl=words.length;
            syl=synsets.length;
            pl=poses.length;
            sel=senses.length;
            al=acts.length;
            if (hasRule)
                rl=rules.length;
        } 
        else {
            // in this case we have strings
            //alert('Is a string with value: '+words.value);
            // setting length==1
            wl=1;
            syl=1;
            pl=1;
            sel=1;
            al=1;

            // getting string values
            var myword=words.value;
            var mysynset=synsets.value;
            var mypose=poses.value;
            var mysense=senses.value;
            var myact=acts.value;
            if (hasRule)
                var myrule=rules.value;
            
            // create array
            var words=new Array(wl); //words
            var synsets=new Array(syl); // synsets
            var poses=new Array(pl); // poses
            var senses=new Array(syl); // senses
            var acts=new Array(syl); // activities
            
            // fill the value
            words[0]=myword;
            synsets[0]=mysynset;
            poses[0]=mypose;
            senses[0]=mysense;
            acts[0]=myact;
           

            if (hasRule){
                rl=1;
                var rules=new Array(rl);
                rules[0]=myrule;
            }
        }
        
        // MANAGING WORDS
        var wArr=new Array(wl); //words
        var syArr=new Array(syl); // synsets
        var pArr=new Array(pl); // poses
        var seArr=new Array(syl); // senses
        var aArr=new Array(syl); // activities
        var vsArr=new Array(wl); // validate same as words
       // alert ("created arrays for mydata "+wArr.length);
        
        if (wl==1){
            wArr[0]= myword;
            syArr[0]=mysynset;
            pArr[0]=mypose;
            seArr[0]=mysense;
            aArr[0]=myact;
             if (hasRule){
                rArr=new Array(rl);
                rArr[i]=myrule;
            }
        } 
        else {
            rArr=new Array(rl);
            for( i = 0; i < wl; i++ ) {
                wArr[i]= words[i].value;
                syArr[i]=synsets[i].value;
                pArr[i]=poses[i].value;
                seArr[i]=senses[i].value;
                aArr[i]=acts[i].value;
                if (hasRule){
                    rArr[i]=rules[i].value;
                    //alert (rArr[i]);
                }
                //alert ("created arrays: "+words[i]+ " - "+rArr.length);
               
                
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
            }// end for on words
        }
        //alert ("created arrays: "+words[0]+ " - "+wArr[0]);
    } // end hasWords=true

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
        var divid="div_manage_new_word_"+lsyn+"_"+lang+"_"+k
        //alert (divid);
        var  cdiv=document.getElementById(divid);
        var cdisplay=cdiv.style.display
        var nwTxtId="nw_"+lsyn+"_"+lang+"_"+k
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
   
    } // end managing newwords
    
    // CREATE THE mydata 
    if (hasRule){
         if (addMe){
            if (hasNewRule)
                mydata={type:"mws", words : wArr  , synsets : syArr, poses: pArr, senses: seArr, lang:lang, ilang:ilang, synsetid:synsetid, user:myuser, acts: aArr, rules: rArr, vs: vsArr, newrules: nrArr,  newwords : nwArr  , newsynsets : nsyArr, newposes: npArr, newsenses: nseArr,newacts: naArr , newvs: nvsArr,mpos:mapped_pos}
            else 
                mydata={type:"mws", words : wArr  , synsets : syArr, poses: pArr, senses: seArr, lang:lang, ilang:ilang, synsetid:synsetid, user:myuser, acts: aArr, rules: rArr, vs: vsArr,  newwords : nwArr  , newsynsets : nsyArr, newposes: npArr, newsenses: nseArr,newacts: naArr, newvs: nvsArr,mpos:mapped_pos}
        } else {
                mydata={type:"mws", words : wArr  , synsets : syArr, poses: pArr, senses: seArr, lang:lang, ilang:ilang, synsetid:synsetid, user:myuser, acts: aArr, rules: rArr, vs: vsArr,mpos:mapped_pos}
            }
    }
    else {
        if (addMe){
            if (hasNewRule)
                mydata={type:"mws", words : wArr  , synsets : syArr, poses: pArr, senses: seArr, lang:lang, ilang:ilang, synsetid:synsetid, user:myuser, acts: aArr, vs: vsArr, newrules: nrArr,  newwords : nwArr  , newsynsets : nsyArr, newposes: npArr, newsenses: nseArr,newacts: naArr, newvs: nvsArr,mpos:mapped_pos}
            else 
                 mydata={type:"mws", words : wArr  , synsets : syArr, poses: pArr, senses: seArr, lang:lang, ilang:ilang, synsetid:synsetid, user:myuser, acts: aArr, vs: vsArr, newwords : nwArr  , newsynsets : nsyArr, newposes: npArr, newsenses: nseArr,newacts: naArr, newvs: nvsArr,mpos:mapped_pos}
       } else {
                mydata={type:"mws", words : wArr  , synsets : syArr, poses: pArr, senses: seArr, lang:lang, ilang:ilang, synsetid:synsetid, user:myuser, acts: aArr,vs: vsArr,newacts: naArr,mpos:mapped_pos}
        }
    }
    
    // SAVING RESULTS
    var jsonData1 = $.ajax({
          url: "updateresults.php",
		  type: "POST",
		  data: mydata,
          async: false
          }).responseText;
          rsdiv.innerHTML=jsonData1;
    
    rsdiv.innerHTML=jsonData1;
    
    $("#"+div_save).show();
    
    
} // end function 




function autocompleteme(id,lang) {
    // alert (myid);
    $(function() {
        $( "#"+id ).autocomplete({
        source: function( request, response ) {
            $.ajax({
                url: "ajaxautocomplete.php?",
                type: "POST",
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

function autocompletemeRel(id,lang) {
    var field="searchRel",ml=3;
    // alert (myid);
    $(function() {
        $( "#"+id ).autocomplete({
        source: function( request, response ) {
            $.ajax({
                url: "ajaxautocomplete.php?",
                type: "POST",
                dataType: "json",
                data: {
                ilang: lang,
                field: field,
                maxRows: 12,
                term: request.term
            },
        success: function( data ) {
            response( $.map( data.lor, function( item ) {
                return {
                    //label: item.name + (item.adminName1 ? ", " + item.adminName1 : "") + ", " + item.countryName,
                    value: item.rel
                }
            }));
            }
        });
    },
    minLength: ml,
    select: function( event, ui ) {
        //alert( ui.item ?
        //"Selected: " + ui.item.label :
        //"Nothing selected, input was " + this.value);
            },
        });
    });
}

function autocompletemePos(id,lang) {
    var field="searchPos",ml=1;
    // alert (myid);
    $(function() {
        $( "#"+id ).autocomplete({
        source: function( request, response ) {
            $.ajax({
                url: "ajaxautocomplete.php?",
                type: "POST",
                dataType: "json",
                data: {
                ilang: lang,
                field: field,
                maxRows: 12,
                term: request.term
            },
        success: function( data ) {
            response( $.map( data.pos, function( item ) {
                return {
                    //label: item.name + (item.adminName1 ? ", " + item.adminName1 : "") + ", " + item.countryName,
                    value: item.pos
                }
            }));
            }
        });
    },
    minLength: ml,
    select: function( event, ui ) {
        //alert( ui.item ?
        //"Selected: " + ui.item.label :
        //"Nothing selected, input was " + this.value);
            },
        });
    });
}

function autocompletemeTarget(id,lang, type, baseid, idx ){
    var field="",ml=6;
    // alert (myid);
    switch(type){
        case 0:
            field="searchDef";
            ml=3;
            break;
        case 1:
            field="searchId";
    }
    $(function() {
        $( "#"+id ).autocomplete({
        source: function( request, response ) {
            $.ajax({
                url: "ajaxautocomplete.php?",
                type: "POST",
                dataType: "json",
                data: {
                ilang: lang,
                field: field,
                maxRows: 12,
                term: request.term
            },
        success: function( data ) {
            response( $.map( data.syn, function( item ) {
                return {
                    //label: item.name + (item.adminName1 ? ", " + item.adminName1 : "") + ", " + item.countryName,
                    value: item.id+ "#-#"+item.def+"#-#"+item.pos
                }
                document.getElementById(id).value="";
            }));
            }
        });
    },
    minLength: ml,
    select: function( event, ui ) {
        event.preventDefault();
        var txtId=baseid+"tgt_id_"+idx;
        var txtPos=baseid+"pos_"+idx;
        var txtTa=baseid+"tgt_"+idx;
        //alert (ui.item.value.id);
        var test="#-#", lid,lpos,ldef;
        if (ui.item.label.indexOf(test) != -1){
            var res = ui.item.label.split("#-#");
            lid=res[0];
            ldef=res[1];
            lpos=res[2];
        }
        
        
        document.getElementById(id).value="";
        document.getElementById(id).value=ldef; 
        document.getElementById(txtPos).value=lpos; 
        document.getElementById(txtId).value=lid;
      
        
        //alert( ui.item ?
        //"Selected: " + ui.item.label :
        //"Nothing selected, input was " + this.value);
            },
        });
    });
}

var maxNewWord=5;
var maxNewSRel=5;
var maxNewTRel=5;
function AddMoreLessWord(comm,id,lang) {
    var strTrId="id_new_word_"+id+"_"+lang;
    var last=document.getElementById(strTrId);
    var lastId=last.value;
    var lastId4jq=last.value;
    var IntlastId=parseInt(lastId);
    var trId="div_manage_new_word_"+id+"_"+lang+"_";
    var strNewWordId="nw_"+id+"_"+lang+"_";
	  if ((comm == "add") && (IntlastId < maxNewWord)) {
        //alert ("adding " +lastId);
	     trId = trId + lastId;
	     which = document.getElementById(trId);
	     which.style.display="block";
         IntlastId=IntlastId+1;
         last.value=IntlastId;
	  }
	  if ((comm == "rm") && (IntlastId > 0)) {
           IntlastId=IntlastId - 1;
           lastId=IntlastId.toString();
            strNewWordId=strNewWordId+lastId;
            //alert (strNewWordId+ " "+lastId)
            trId = trId + IntlastId;
         // set the value to ""
         var nw=document.getElementById(strNewWordId);
         nw.value="";
	     which = document.getElementById(trId);
	    which.style.display="none";
         last.value=IntlastId;
   }
   
}

function AddMoreLessSRel(comm,id,lang) {
    var strTrId="id_new_srel_"+id+"_"+lang;
    var last=document.getElementById(strTrId);
    var lastId=last.value;
    var lastId4jq=last.value;
    var IntlastId=parseInt(lastId);
    var trId="div_manage_new_srel_"+id+"_"+lang+"_";
    var strNewWordId="nsr_"+id+"_"+lang+"_";
	  if ((comm == "add") && (IntlastId < maxNewWord)) {
        //alert ("adding " +lastId);
	     trId = trId + lastId;
	     which = document.getElementById(trId);
	     which.style.display="block";
         IntlastId=IntlastId+1;
         last.value=IntlastId;
	  }
	  if ((comm == "rm") && (IntlastId > 0)) {
           IntlastId=IntlastId - 1;
           lastId=IntlastId.toString();
            strNewWordId=strNewWordId+lastId;
            //alert (strNewWordId+ " "+lastId)
            trId = trId + IntlastId;
         // set the value to ""
         var nw=document.getElementById(strNewWordId);
         nw.value="";
	     which = document.getElementById(trId);
	    which.style.display="none";
         last.value=IntlastId;
   }
   
}

function AddMoreLessTRel(comm,id,lang) {
    var strTrId="id_new_trel_"+id+"_"+lang;
    var last=document.getElementById(strTrId);
    var lastId=last.value;
    var lastId4jq=last.value;
    var IntlastId=parseInt(lastId);
    var trId="div_manage_new_trel_"+id+"_"+lang+"_";
    var strNewWordId="ntr_"+id+"_"+lang+"_";
	  if ((comm == "add") && (IntlastId < maxNewWord)) {
        //alert ("adding " +lastId);
	     trId = trId + lastId;
	     which = document.getElementById(trId);
	     which.style.display="block";
         IntlastId=IntlastId+1;
         last.value=IntlastId;
	  }
	  if ((comm == "rm") && (IntlastId > 0)) {
           IntlastId=IntlastId - 1;
           lastId=IntlastId.toString();
            strNewWordId=strNewWordId+lastId;
            //alert (strNewWordId+ " "+lastId)
            trId = trId + IntlastId;
         // set the value to ""
         var nw=document.getElementById(strNewWordId);
         nw.value="";
	     which = document.getElementById(trId);
	    which.style.display="none";
         last.value=IntlastId;
   }
   
}
