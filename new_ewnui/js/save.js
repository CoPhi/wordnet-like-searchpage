/**
saves source rels and new source rels
added check on 0 rels and 1 rels
separated the behaviour between rels and newrels, since newrels always exixt. 
**/

function saveMeSr(name, synsetid, lang,user,mapped_lang,mapped_synset,mapped_pos){
    var ilang=mapped_lang;
    var lsyn=synsetid;
    var synsetid=mapped_synset;
    //var myuser="riccardo.delgratta@gmail.com";
    var myuser=user;
    var pos,gloss, log,posIn;
    var div_save=name+"_save";
    var mydata="";
    //alert (name);
    var hasRels=true;
    var mydata=""
    var isRelArray=false;
    var rsdiv=document.getElementById(div_save);
    posIn=document[name][ 'lpos' ];
     if (posIn == null)
        pos=document[name][ 'lpos_1' ].value;
    else 
        pos=posIn.value;
    //alert (pos);
    var rels = document[name][ 'rels[]' ];
    var targets = document[name][ 'targets[]' ]; 
    var tposes = document[name][ 'tposes[]' ];
    var targetsIds = document[name][ 'targetsIds[]' ];
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
    
    // is rels defined?
    if (rels == undefined)
        hasRels=false;

    // is rels an array >1 rel?
    if (hasRels){
        wl=rels.length;
        if ( wl  != undefined) {
            isRelArray=true;
            //alert('value is Array with size: '+wl+ " "+isRelArray);
            // setting length
            wl=rels.length;
            syl=targets.length;
            pl=tposes.length;
            sel=targetsIds.length;
            al=acts.length;
            if (hasRule)
                rl=rules.length;
        } 
        else {
            // in this case we have strings
            //alert('Is a string with value: '+rels.value);
            // setting length==1
            wl=1;
            syl=1;
            pl=1;
            sel=1;
            al=1;

            // getting string values
            var myrel=rels.value;
            var mytarget=targets.value;
            var mytpos=tposes.value;
            var mytargetId=targetsIds.value;
            var myact=acts.value;
            if (hasRule)
                var myrule=rules.value;
            
            // create array
            var rels=new Array(wl); //rels
            var targets=new Array(syl); // targets
            var tposes=new Array(pl); // tposes
            var targetsIds=new Array(syl); // targetsIds
            var acts=new Array(syl); // activities
            
            // fill the value
            rels[0]=myrel;
            targets[0]=mytarget;
            tposes[0]=mytpos;
            targetsIds[0]=mytargetId;
            acts[0]=myact;
           

            if (hasRule){
                rl=1;
                var rules=new Array(rl);
                rules[0]=myrule;
            }
        }
        
        // MANAGING rels
        var wArr=new Array(wl); //rels
        var syArr=new Array(syl); // targets
        var pArr=new Array(pl); // tposes
        var seArr=new Array(syl); // targetsIds
        var aArr=new Array(syl); // activities
        var vsArr=new Array(wl); // validate same as rels
       // alert ("created arrays for mydata "+wArr.length);
        
        if (wl==1){
            wArr[0]= myrel;
            syArr[0]=mytarget;
            pArr[0]=mytpos;
            seArr[0]=mytargetId;
            aArr[0]=myact;
             if (hasRule){
                rArr=new Array(rl);
                rArr[i]=myrule;
            }
        } 
        else {
            rArr=new Array(rl);
            for( i = 0; i < wl; i++ ) {
                wArr[i]= rels[i].value;
                syArr[i]=targets[i].value;
                pArr[i]=tposes[i].value;
                seArr[i]=targetsIds[i].value;
                aArr[i]=acts[i].value;
                if (hasRule){
                    rArr[i]=rules[i].value;
                    //alert (rArr[i]);
                }
                //alert ("created arrays: "+rels[i]+ " - "+rArr.length);
               
                
                // managing validation value
                var vName="srv_"+i;
            
                var radios = document[name][vName];
                //alert (vName+ ", "+radios.length);
                for( j = 0; j < radios.length; j++ ) {
                    // alert (radios[j].value)
                    if( radios[j].checked ) {
                        // alert( radios[j].value+ ", "+i );
                        vsArr[i]=radios[j].value;
                    }
                }
            }// end for on rels
        }
        //alert ("created arrays: "+rels[0]+ " - "+wArr[0]);
    } // end hasRels=true

    // MANAGING ADDITIONAL rels
    var newrels = document[name][ 'newrels[]' ];
    var newtargets = document[name][ 'newtargets[]' ];
    var newtargetsIds = document[name][ 'newtargetsIds[]' ];
    var newtposes = document[name][ 'newtposes[]' ];
    var newacts = document[name][ 'newacts[]' ];
    var newrules = document[name][ 'newrules[]' ];
    var nwArr;
    var nwl=0;
    var myStr="-1";
    var addMe=false;
    
    for (k=0; k<newrels.length; k++){
        var divid="div_manage_new_srel_"+lsyn+"_"+lang+"_"+k
       // alert (divid);
        var  cdiv=document.getElementById(divid);
        var cdisplay=cdiv.style.display
        var nwTxtId="nsr_"+lsyn+"_"+lang+"_"+k
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
                //nwArr[k]=newrels[getme].value;
                nwArr.push(newrels[getme].value);
                nsyArr.push(newtargets[getme].value);
                nseArr.push(newtargetsIds[getme].value);
                naArr.push(newacts[getme].value);
                npArr.push(newtposes[getme].value);
            
                if (newrules !=null)
                    hasNewRule=true;
                if (hasNewRule){
                    nrArr.push(newrules[getme].value);
                    //  alert (idx[l]+" "+newrules[getme].value)
                }
                // managing validation value for new rels
                var vName="nsrv_"+getme;
            
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
   
    } // end managing newrels
    
    // CREATE THE mydata 
    if (hasRule){
         if (addMe){
            if (hasNewRule)
                mydata={type:"msr", rels : wArr  , targets : syArr, tposes: pArr, targetsIds: seArr, lang:lang, ilang:ilang, synsetid:synsetid, user:myuser, acts: aArr, rules: rArr, vs: vsArr, newrules: nrArr,  newrels : nwArr  , newtargets : nsyArr, newtposes: npArr, newtargetsIds: nseArr,newacts: naArr , newvs: nvsArr,mpos:mapped_pos, lsyn:lsyn,lpos:pos}
            else 
                mydata={type:"msr", rels : wArr  , targets : syArr, tposes: pArr, targetsIds: seArr, lang:lang, ilang:ilang, synsetid:synsetid, user:myuser, acts: aArr, rules: rArr, vs: vsArr,  newrels : nwArr  , newtargets : nsyArr, newtposes: npArr, newtargetsIds: nseArr,newacts: naArr, newvs: nvsArr,mpos:mapped_pos,lsyn:lsyn,lpos:pos}
        } else {
                mydata={type:"msr", rels : wArr  , targets : syArr, tposes: pArr, targetsIds: seArr, lang:lang, ilang:ilang, synsetid:synsetid, user:myuser, acts: aArr, rules: rArr, vs: vsArr,mpos:mapped_pos,lsyn:lsyn,lpos:pos}
            }
    }
    else {
        if (addMe){
            if (hasNewRule)
                mydata={type:"msr", rels : wArr  , targets : syArr, tposes: pArr, targetsIds: seArr, lang:lang, ilang:ilang, synsetid:synsetid, user:myuser, acts: aArr, vs: vsArr, newrules: nrArr,  newrels : nwArr  , newtargets : nsyArr, newtposes: npArr, newtargetsIds: nseArr,newacts: naArr, newvs: nvsArr,mpos:mapped_pos,lsyn:lsyn}
            else 
                 mydata={type:"msr", rels : wArr  , targets : syArr, tposes: pArr, targetsIds: seArr, lang:lang, ilang:ilang, synsetid:synsetid, user:myuser, acts: aArr, vs: vsArr, newrels : nwArr  , newtargets : nsyArr, newtposes: npArr, newtargetsIds: nseArr,newacts: naArr, newvs: nvsArr,mpos:mapped_pos,lsyn:lsyn,lpos:pos}
       } else {
                mydata={type:"msr", rels : wArr  , targets : syArr, tposes: pArr, targetsIds: seArr, lang:lang, ilang:ilang, synsetid:synsetid, user:myuser, acts: aArr,vs: vsArr,newacts: naArr,mpos:mapped_pos,lsyn:lsyn,lpos:pos}
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


/**
saves target rels and new target rels
added check on 0 rels and 1 rels
separated the behaviour between rels and newrels, since newrels always exixt. 
**/

function saveMeTr(name, synsetid, lang,user,mapped_lang,mapped_synset,mapped_pos){
    var ilang=mapped_lang;
    var lsyn=synsetid;
    var synsetid=mapped_synset;
    //var myuser="riccardo.delgratta@gmail.com";
    var myuser=user;
    var pos,gloss, log,posIn;
    var div_save=name+"_save";
    var mydata="";
    //alert (name);
    var hasRels=true;
    var mydata=""
    var isRelArray=false;
    var rsdiv=document.getElementById(div_save);
    posIn=document[name][ 'lpos' ];
    if (posIn == null)
        pos=document[name][ 'lpos_1' ].value;
    else 
        pos=posIn.value;
    //alert (pos);
    var rels = document[name][ 'rels[]' ];
    var targets = document[name][ 'targets[]' ]; 
    var tposes = document[name][ 'tposes[]' ];
    var targetsIds = document[name][ 'targetsIds[]' ];
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
    
    // is rels defined?
    if (rels == undefined)
        hasRels=false;

    // is rels an array >1 rel?
    if (hasRels){
        wl=rels.length;
        if ( wl  != undefined) {
            isRelArray=true;
            //alert('value is Array with size: '+wl+ " "+isRelArray);
            // setting length
            wl=rels.length;
            syl=targets.length;
            pl=tposes.length;
            sel=targetsIds.length;
            al=acts.length;
            if (hasRule)
                rl=rules.length;
        } 
        else {
            // in this case we have strings
            //alert('Is a string with value: '+rels.value);
            // setting length==1
            wl=1;
            syl=1;
            pl=1;
            sel=1;
            al=1;

            // getting string values
            var myrel=rels.value;
            var mytarget=targets.value;
            var mytpos=tposes.value;
            var mytargetId=targetsIds.value;
            var myact=acts.value;
            if (hasRule)
                var myrule=rules.value;
            
            // create array
            var rels=new Array(wl); //rels
            var targets=new Array(syl); // targets
            var tposes=new Array(pl); // tposes
            var targetsIds=new Array(syl); // targetsIds
            var acts=new Array(syl); // activities
            
            // fill the value
            rels[0]=myrel;
            targets[0]=mytarget;
            tposes[0]=mytpos;
            targetsIds[0]=mytargetId;
            acts[0]=myact;
           

            if (hasRule){
                rl=1;
                var rules=new Array(rl);
                rules[0]=myrule;
            }
        }
        
        // MANAGING rels
        var wArr=new Array(wl); //rels
        var syArr=new Array(syl); // targets
        var pArr=new Array(pl); // tposes
        var seArr=new Array(syl); // targetsIds
        var aArr=new Array(syl); // activities
        var vsArr=new Array(wl); // validate same as rels
       // alert ("created arrays for mydata "+wArr.length);
        
        if (wl==1){
            wArr[0]= myrel;
            syArr[0]=mytarget;
            pArr[0]=mytpos;
            seArr[0]=mytargetId;
            aArr[0]=myact;
             if (hasRule){
                rArr=new Array(rl);
                rArr[i]=myrule;
            }
        } 
        else {
            rArr=new Array(rl);
            for( i = 0; i < wl; i++ ) {
                wArr[i]= rels[i].value;
                syArr[i]=targets[i].value;
                pArr[i]=tposes[i].value;
                seArr[i]=targetsIds[i].value;
                aArr[i]=acts[i].value;
                if (hasRule){
                    rArr[i]=rules[i].value;
                    //alert (rArr[i]);
                }
                //alert ("created arrays: "+rels[i]+ " - "+rArr.length);
               
                
                // managing validation value
                var vName="trv_"+i;
            
                var radios = document[name][vName];
                //alert (vName+ ", "+radios.length);
                for( j = 0; j < radios.length; j++ ) {
                    // alert (radios[j].value)
                    if( radios[j].checked ) {
                        // alert( radios[j].value+ ", "+i );
                        vsArr[i]=radios[j].value;
                    }
                }
            }// end for on rels
        }
        //alert ("created arrays: "+rels[0]+ " - "+wArr[0]);
    } // end hasRels=true

    // MANAGING ADDITIONAL rels
    var newrels = document[name][ 'newrels[]' ];
    var newtargets = document[name][ 'newtargets[]' ];
    var newtargetsIds = document[name][ 'newtargetsIds[]' ];
    var newtposes = document[name][ 'newtposes[]' ];
    var newacts = document[name][ 'newacts[]' ];
    var newrules = document[name][ 'newrules[]' ];
    var nwArr;
    var nwl=0;
    var myStr="-1";
    var addMe=false;
    
    for (k=0; k<newrels.length; k++){
        var divid="div_manage_new_trel_"+lsyn+"_"+lang+"_"+k
        //alert (divid);
        var  cdiv=document.getElementById(divid);
        var cdisplay=cdiv.style.display
        var nwTxtId="ntr_"+lsyn+"_"+lang+"_"+k
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
                //nwArr[k]=newrels[getme].value;
                nwArr.push(newrels[getme].value);
                nsyArr.push(newtargets[getme].value);
                nseArr.push(newtargetsIds[getme].value);
                naArr.push(newacts[getme].value);
                npArr.push(newtposes[getme].value);
            
                if (newrules !=null)
                    hasNewRule=true;
                if (hasNewRule){
                    nrArr.push(newrules[getme].value);
                    //  alert (idx[l]+" "+newrules[getme].value)
                }
                // managing validation value for new rels
                var vName="ntrv_"+getme;
            
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
   
    } // end managing newrels
    
    // CREATE THE mydata 
    if (hasRule){
         if (addMe){
            if (hasNewRule)
                mydata={type:"mtr", rels : wArr  , targets : syArr, tposes: pArr, targetsIds: seArr, lang:lang, ilang:ilang, synsetid:synsetid, user:myuser, acts: aArr, rules: rArr, vs: vsArr, newrules: nrArr,  newrels : nwArr  , newtargets : nsyArr, newtposes: npArr, newtargetsIds: nseArr,newacts: naArr , newvs: nvsArr,mpos:mapped_pos, lsyn:lsyn,lpos:pos}
            else 
                mydata={type:"mtr", rels : wArr  , targets : syArr, tposes: pArr, targetsIds: seArr, lang:lang, ilang:ilang, synsetid:synsetid, user:myuser, acts: aArr, rules: rArr, vs: vsArr,  newrels : nwArr  , newtargets : nsyArr, newtposes: npArr, newtargetsIds: nseArr,newacts: naArr, newvs: nvsArr,mpos:mapped_pos,lsyn:lsyn,lpos:pos}
        } else {
                mydata={type:"mtr", rels : wArr  , targets : syArr, tposes: pArr, targetsIds: seArr, lang:lang, ilang:ilang, synsetid:synsetid, user:myuser, acts: aArr, rules: rArr, vs: vsArr,mpos:mapped_pos,lsyn:lsyn,lpos:pos}
            }
    }
    else {
        if (addMe){
            if (hasNewRule)
                mydata={type:"mtr", rels : wArr  , targets : syArr, tposes: pArr, targetsIds: seArr, lang:lang, ilang:ilang, synsetid:synsetid, user:myuser, acts: aArr, vs: vsArr, newrules: nrArr,  newrels : nwArr  , newtargets : nsyArr, newtposes: npArr, newtargetsIds: nseArr,newacts: naArr, newvs: nvsArr,mpos:mapped_pos,lsyn:lsyn}
            else 
                 mydata={type:"mtr", rels : wArr  , targets : syArr, tposes: pArr, targetsIds: seArr, lang:lang, ilang:ilang, synsetid:synsetid, user:myuser, acts: aArr, vs: vsArr, newrels : nwArr  , newtargets : nsyArr, newtposes: npArr, newtargetsIds: nseArr,newacts: naArr, newvs: nvsArr,mpos:mapped_pos,lsyn:lsyn,lpos:pos}
       } else {
                mydata={type:"mtr", rels : wArr  , targets : syArr, tposes: pArr, targetsIds: seArr, lang:lang, ilang:ilang, synsetid:synsetid, user:myuser, acts: aArr,vs: vsArr,newacts: naArr,mpos:mapped_pos,lsyn:lsyn,lpos:pos}
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
