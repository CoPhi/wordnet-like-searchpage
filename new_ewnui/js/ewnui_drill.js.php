<?php
/**
contains functions to browsing the resources
**/
?>
 
 
function showWords(id, item, def,lang,pos,type){
  
}

function showRelations(id, item, def,lang,pos,type){
   
}


/**
Adds a tab that contains a new tabbed panel
1) tab 0 for browsing
2) tab 1... as many as target languages 
**/
function showResBySynsetId(id, item, def,lang,pos,type){
  var user=document.getElementById("myusername").value;
  var mysaltObj=document.getElementById("mysalt");
  var mysalt=0;
  if (mysaltObj!=null)
    mysalt=mysaltObj.value;
    
  var a_length = Object.keys(a).length;
  var t_length = Object.keys(t).length;
  last_viewed=id; // this is the current viewed synsetid
  var test=false;
  var obj,pos_1;
  var lid=id+"_"+pos+"_"+lang;
  var ltab;
  var btab;
  var brw;
  var ulCls,liCls,divCls, aCls;   
  var divname,div_vr_accordion,subdivname,header,theader,rsdiv,panel,iText, divTab, divItab, divUl, divIul;
  var gloss="",words="",sRels="",tRels="";
  var num_tabs;
  var posId="",formStr="", saveBtn="", endStr="",xposId="",xglossId="",xformStr="", xsaveBtn="", xendStr="",xsrelId="";
 
  def=stripslashes(def);
    // moving iText to a tab panel  
    
  liCls="  class='ui-state-default ui-corner-top' role='tab'";
  ulCls="  class='ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all' role='tablist'";
  divCls="  class='ui-tabs-panel ui-widget-content ui-corner-bottom' role='tabpanel'";
  aCls="  class='ui-tabs-anchor' role='presentation'";
   
  /* managing type
  0) from input
  1) from target
  */
  if(type==0){
    obj="#tabs";
    divname="div_result_input_sub_"+lid;
    subdivname="div_result_input_sub_view_"+lid;
    header="Input Section (Input language "+lang+"): browsing information for synset "+id + " ("+pos+") "+"\""+def + "\"";
    ltab="#i_tabs_"+lid;
    btab="#b_tabs_"+lid;
    divTab=".tabs";
    divItab=".i_tabs";
    divItabCls="i_tabs";
    divBtabCls="b_tabs";
    divBtab=".b_tabs";
    divUl=".ulCls";
    divIul=".iUlCls";
    divBul=".bUlCls";
    var div_lid="div_"+lid;
    brw="brw_"+lid;
    num_tabs = $(".tabs >ul >li").size();
    posId='edit_pos_sub_brw_'; 
    glossId='edit_gloss_sub_brw_';
    sRelId='edit_srel_sub_brw_';
    formId='edit_form_sub_brw_';
    btnId='edit_btn_sub_brw_';
    document.getElementById("mycursyn").value=id;
    document.getElementById("mycurpos").value=pos;
    pos_1=pos;
  }
  if(type==1){
    obj="#t_tabs";
    divname="div_result_target_view_"+lid;
    subdivname="div_result_target_sub_view_"+lid;
    header="Target Section (Target language "+lang+"): browsing information for synset "+id + " ("+pos+") "+"\""+def + "\"";
    ltab="#i_t_tabs_"+lid;
    btab="#b_t_tabs_"+lid;
    divTab=".t_tabs";
    divItab=".i_t_tabs"
    divItabCls="i_t_tabs";
    divBtabCls="b_t_tabs";
    divBtab=".b_t_tabs";
    divUl=".t_ulCls";
    divIul=".i_t_UlCls";
    divBul=".b_t_UlCls";
    var div_lid="div_t_"+lid;
    brw="brw_t_"+lid;
    num_tabs = $(".t_tabs >ul >li").size();
    posId='edit_pos_sub_brw_t_';   
    glossId='edit_gloss_sub_brw_t_';
    sRelId='edit_srel_sub_brw_t_';
    formId='edit_form_sub_brw_t_';
    btnId='edit_btn_sub_brw_t_';
    pos_1=document.getElementById("mycurpos").value;
  }
  
  
    
  div_vr_accordion=document.getElementById(divname);
  var iconStr="<span class='ui-icon ui-icon-close' role='presentation' style='display:block;'>Remove Tab</span> <span class='ui-icon  ui-icon-pencil' role='presentation' style='display:none;'>Edit</span>";
  
  var rsdiv=document.getElementById(div_lid);
  var sessionId = $.ajax({
        url: "getUserToken.php",
        type: "POST",
        data:"user="+user+"&mysalt="+mysalt,
        dataType:"json",
        async: false
        }).responseText;
        //alert (sessionId)
        if(sessionId==1){
            iconStr="<span class='ui-icon ui-icon-close' role='presentation' style='display:block;'>Remove Tab</span> <span class='ui-icon  ui-icon-pencil' role='presentation' style='display:block;'>Edit</span>";
        }
 
 // managing gloss
    posId=posId+id+"_"+pos+"_"+lang+"_";   
    glossId=glossId+id+"_"+pos+"_"+lang+"_";   
    formId=formId+id+"_"+pos+"_"+lang+"_";   
    btnId=btnId+id+"_"+pos+"_"+lang+"_";
    
    xposId=posId+"gloss";
    xglossId=glossId+"gloss";
    xformId=formId+"gloss";
    xbtnId=btnId+"gloss";

  iText="";//Synset Id: "+id + "</br>Part Of Speech: <span class=posCls>"+pos +"</span></br>Gloss: "+def + "</br>Input language "+lang+"&nbsp;&nbsp;<img src='./img/"+lang+".png' ></br></br></br>";
  // add a form 
  formStr="<form id='"+xformId+"' name='"+xformId+"'>"
  saveBtn="<input type='button' value='Save Changes' onclick='javascript:saveMe(\""+xformId+"\", \""+id+"\", \""+lang+"\",0);' style='display:none;' id='"+xbtnId+"'>";
  endStr="</form><div id='"+xformId+"_save' style='display:none'></div>";
  gloss="Synset Id: "+id + "</br>Part Of Speech: <input type='text' id='"+xposId+"' value='"+pos +"' disabled maxlength='3' size='3' name='pos'></br><label for='"+xglossId+"'>Gloss: </label><textarea rows='2' cols='50' id='"+xglossId+"' disabled name='gloss'>"+def+"</textarea></br>Input language "+lang+"&nbsp;&nbsp;<img src='./img/"+lang+".png' ></br></br></br>";
  gloss=formStr+gloss+saveBtn+endStr;
  if (div_vr_accordion ==null ){       
    var li ="<li><a href='#"+divname+"'><img src='./img/source-dict-icon.png' >&nbsp;<img src='./img/"+lang+".png' >&nbsp;&nbsp;<b>"+id+" ("+pos+")</b></a>"+iconStr+"</li>";
    var viewDiv = "<div id='"+divname+"'><div id='"+subdivname+"'><div id='"+div_lid+"'></div></div></div>";
    $(divUl).append(li);
    $(divTab).append(viewDiv)
  } else{
      if (type==0)
            num_tabs = $('.tabs a[href="#'+divname+'"]').parent().index();
        if (type==1)
            num_tabs = $('.t_tabs a[href="#'+divname+'"]').parent().index();     
     //alert ("index: "+num_tabs);
   // alert(rsdiv.innerHTML);//="";
    rsdiv.innerHTML="";
   // alert(rsdiv.innerHTML);
  }
  var rsdiv=document.getElementById(div_lid);
  //defition
  /*var jsonData1 = $.ajax({
        url: "getAllBySynsetId.php",
        type: "POST",
        data:"id="+id+"&value="+item+"&def="+def+"&lang="+lang+"&type="+type+"&pos="+pos+"&ilang="+lang+"&field=df&username="+user,
        dataType:"json",
        async: false
      }).responseText;
      gloss=gloss+jsonData1+"</br>";   */
  //words
   
    xposId=posId+"ws";
    xglossId=glossId+"ws";
    xformId=formId+"ws";
    xbtnId=btnId+"ws"; 
  //  alert (xbtnId);
    var jsonData1 = $.ajax({
        url: "getAllBySynsetId.php",
        type: "POST",
        data:"id="+id+"&value="+item+"&def="+def+"&lang="+lang+"&type="+type+"&pos="+pos+"&ilang="+lang+"&field=sw&username="+user+"&pos_1="+pos_1,
        dataType:"json",
        async: false
      }).responseText;
  
  formStr="<form id='"+xformId+"' name='"+xformId+"'>"
  saveBtn="<input type='button' value='Save Changes' onclick='javascript:saveMe(\""+xformId+"\", \""+id+"\", \""+lang+"\",1);' style='display:none;' id='"+xbtnId+"'>";
  endStr="</form><div id='"+xformId+"_save' style='display:none'></div>";
  
   words=words+jsonData1+"</br>";
   words=formStr+words+saveBtn+endStr;    
      
  // Source Relations
   // managing gloss
    xposId=posId+"sr";
    xsrelId=sRelId+"sr";
    xformId=formId+"sr";
    xbtnId=btnId+"sr"; 
  
  var jsonData1 = $.ajax({
        url: "getAllBySynsetId.php",
        type: "POST",
        data:"id="+id+"&value="+item+"&def="+def+"&lang="+lang+"&type="+type+"&pos="+pos+"&ilang="+lang+"&field=sr&username="+user,
        dataType:"json",
        async: false
      }).responseText;
      
  formStr="<form id='"+xformId+"' name='"+xformId+"'>"
  saveBtn="<input type='button' value='Save Changes' onclick='javascript:saveMe(\""+xformId+"\", \""+id+"\", \""+lang+"\",2);' style='display:none;' id='"+xbtnId+"'>";
  endStr="</form><div id='"+xformId+"_save' style='display:none'></div>";
  sRels=sRels+jsonData1+"</br>";
  sRels=formStr+sRels+saveBtn+endStr;    
      
        // target Relations  
        // Source Relations
   // managing gloss
    xposId=posId+"tr";
    xsrelId=sRelId+"tr";
    xformId=formId+"tr";
    xbtnId=btnId+"tr"; 
  var jsonData1 = $.ajax({
        url: "getAllBySynsetId.php",
        type: "POST",
        data:"id="+id+"&value="+item+"&def="+def+"&lang="+lang+"&type="+type+"&pos="+pos+"&ilang="+lang+"&field=tr&username="+user,
        dataType:"json",
        async: false
      }).responseText;
      
      formStr="<form id='"+xformId+"' name='"+xformId+"'>"
      saveBtn="<input type='button' value='Save Changes' onclick='javascript:saveMe(\""+xformId+"\", \""+id+"\", \""+lang+"\",3);' style='display:none;' id='"+xbtnId+"'>";
      endStr="</form><div id='"+xformId+"_save' style='display:none'></div>";
      tRels=tRels+jsonData1+"</br>";
      tRels=formStr+tRels+saveBtn+endStr;   
  
  // inner tab (i_tabs)    
  var viewDiv='<div id="'+ltab+'" class="ui-tabs ui-widget ui-widget-content ui-corner-all ui-tabs-collapsible '+divItabCls+' "><ul '+ulCls+ ' >';
  // adding the browsing into the resource
  viewDiv=viewDiv+'<li '+liCls+' ><a '+aCls+ ' href="#'+brw+'"><img src="./img/database-icon.png" >&nbsp;&nbsp;&nbsp; Browse '+id+'('+pos+')</a>'+iconStr+'</li>';
  
  // loop over tlangs
  var tli="",tdiv="",tref;
  if(type==0){
    
    if (gtlangs.length>0){
      for (i=0;i<gtlangs.length;i++){
        var tlang=gtlangs[i];
        tref=tlang+"_"+lid
        var lTxt=listMappedSynsets(id, item, def,tlang,pos,lang);
        //alert ("tlang loop: "+tlang);
        var myTxt=lTxt.split("%");
        var num=myTxt[0];
        var txt= myTxt[1];
        
        tli=tli+'<li '+liCls+' ><a '+aCls+ ' href="#'+tref+'"><img src="./img/'+tlang+'.png" >&nbsp;<img src="./img/view-list-icon.png">&nbsp;Found <b>'+num +' ('+tlang+') </b> Mapped Synset(s) for <b>'+id+'</b></a>'+iconStr+'</li>';
        tdiv=tdiv+'<div id="'+tref+'"' +divCls+' >'+txt+'</div>';
      } // end loop over tlangs
    } // end num tlangs >0
    } // end type==0  
      tli=tli+"</ul>";
      tdiv=tdiv+"</div>";
   
        
  viewDiv=viewDiv+tli;
  viewDiv=viewDiv+'<div id="'+brw+'"' +divCls+' ><div id="sub_'+brw+'"></div></div>';
  
  var viewBDiv='<div id="'+btab+'" class="ui-tabs ui-widget ui-widget-content ui-corner-all ui-tabs-collapsible '+divBtabCls+' "><ul '+ulCls+ ' >';
  // adding the browsing into the resource
  viewBDiv=viewBDiv+'<li '+liCls+' ><a '+aCls+ ' href="#sub_'+brw+'_gloss"><img src="./img/gloss.png" >&nbsp;&nbsp;&nbsp; Gloss </a>'+iconStr+'</li>';
  viewBDiv=viewBDiv+'<li '+liCls+' ><a '+aCls+ ' href="#sub_'+brw+'_ws"><img src="./img/ws.png" >&nbsp;&nbsp;&nbsp; List Of Words</a>'+iconStr+'</li>';
  viewBDiv=viewBDiv+'<li '+liCls+' ><a '+aCls+ ' href="#sub_'+brw+'_sr"><img src="./img/rels.png" >&nbsp;&nbsp;&nbsp; Relations as Source</a>'+iconStr+'</li>';
  viewBDiv=viewBDiv+'<li '+liCls+' ><a '+aCls+ ' href="#sub_'+brw+'_tr"><img src="./img/trels.png" >&nbsp;&nbsp;&nbsp; Relations as Target</a>'+iconStr+'</li>';
  viewBDiv=viewBDiv+'</ul>';
  
  viewBDiv = viewBDiv+"<div id='sub_"+brw+"_gloss'>"+gloss+"</div>";
  viewBDiv = viewBDiv+"<div id='sub_"+brw+"_ws' >"+words+"</div>";
  viewBDiv = viewBDiv+"<div id='sub_"+brw+"_sr' >"+sRels+"</div>";
  viewBDiv = viewBDiv+"<div id='sub_"+brw+"_tr' >"+tRels+"</div>";
  viewBDiv = viewBDiv+"</div>";
 
  
  viewDiv=viewDiv+tdiv; 
  //alert ('sub_'+brw) 
        //
  // adding innerHTML to browse
  //alert(brw)                   
  iText=iText+viewDiv;
  rsdiv.innerHTML=iText;
  $(obj).tabs( "option", "heightStyle", "content" );
  $(obj).tabs("refresh"); 
  $( divItab ).tabs({
      activate: function( event, ui ) {}
  });
  $(divItab).tabs("refresh");
  
  
  var bDiv=  document.getElementById('sub_'+brw);
  //alert (viewBDiv);
  bDiv.innerHTML=viewBDiv;

  $( divBtab ).tabs({
      activate: function( event, ui ) {}
  });
  $(divBtab).tabs("refresh"); 
  $(obj).tabs("option","active",num_tabs);   
    
}

function listMappedSynsets(id, item, def,tlang,pos,lang){
  var text='';
  var test=false;
  var t_length = Object.keys(t).length;
  var divname="div_result_target_"+id+"_"+pos+"_"+tlang;
  var subdivname="div_result_sub_target_"+id+"_"+pos+"_"+tlang;
  var chk="t_"+id+"_"+pos+"_"+tlang;
  var div_vr_accordion=document.getElementById(divname);
  var targetSynIds=$.ajax({
        url: "getListOfTargetSynsetId.php",
        type: "POST",
        data:"id="+id+"&lang="+tlang+"&ilang="+lang+"&pos="+pos,
        dataType:"json",
        async: false
        }).responseText;
        /*
  if(t.hasOwnProperty(chk)){ // check if main list of target languages already exists
    indexToActivate =  getIndexOf(t,chk);
    test=true;
    alert ("Found "+chk+ " "+indexToActivate+ " - " +t_length);
  } 
  else {
    
    test=false;
    //inserting the value
    t[chk]=t_length;
       var targetSynIds=$.ajax({
        url: "getListOfTargetSynsetId.php",
        type: "POST",
        data:"id="+id+"&lang="+tlang+"&ilang="+lang+"&pos="+pos,
        dataType:"json",
        async: false
        }).responseText;
        */
    var str="";
    var syns = [];
    syns=targetSynIds.split("#");
    lstr="";
    var num=syns.length;
    var myStr="";
    if (num==1 && syns[0]==""){
        num=0;
        myStr="No mapped synsets found";
    } else {
        myStr="Found "+num+ " mapped synset(s)</br>"
    }
    for (j=0; j<syns.length; j++){
        var divStr='<div id="div_result_sub_'+tlang+'_'+j+'_'+pos+'" class="divResultMainCls_sub">'
        divStr=divStr+syns[j]+"</div>"
        lstr=lstr+divStr;
    }
    text=text+myStr;
    text=text+lstr;
    /*
  } // end if check
  */
  
  return num+"%"+text;
 
}
function showResBySynsetIdInTarget(id, item, def,tlang,pos,lang){
  var text='';
  var indexToActivate;
  var panel = '#targets';
  var test=false;
  var t_length = Object.keys(t).length;
  var theader="Results for Target Language "+tlang  + " Information from synset "+id + " ("+pos+") "+"\""+def + "\"";
  var divname="div_result_target_"+id+"_"+pos+"_"+tlang;
  var subdivname="div_result_sub_target_"+id+"_"+pos+"_"+tlang;
  var chk="t_"+id+"_"+pos+"_"+tlang;
  var div_vr_accordion=document.getElementById(divname);
  if(t.hasOwnProperty(chk)){ // check if main list of target languages already exists
    indexToActivate =  getIndexOf(t,chk);
    test=true;
    //alert ("Found "+chk+ " "+indexToActivate+ " - " +t_length);
  } 
  else {
    test=false;
    //inserting the value
    t[chk]=t_length;
    var viewDiv = "<div id='"+divname+"'><h3><span class='accordion-header'></span></h3><div id='"+subdivname+"'></div></div>";
    $(panel).append(viewDiv)
    var  rsdiv=document.getElementById(subdivname);   
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
        var divStr='<div id="div_result_sub_'+tlang+'_'+j+'_'+pos+'" class="divResultMainCls_sub">'
        divStr=divStr+syns[j]+"</div>"
        lstr=lstr+divStr+"</div>";
    }
    text=text+myStr;
    text=text+lstr;
    rsdiv.innerHTML=text;
  } // end if check
  
  
  var idx=0;
  
  if (!test){
      var processingHeaders = $(panel+' h3');
      var last=processingHeaders.length;
      idx=last-1;
      alert ("Active NEW "+ (idx)+ " "+panel);
      
    //
    }
  else {
      idx=indexToActivate+1;
      alert ("Active EXISTING "+(idx)+ " "+panel);
  }
   $(panel).accordion("refresh");   
   $(panel).accordion("option","active",idx); 
   $( panel ).find($( panel ).accordion( "option", "header" )).eq(idx).find($("span.accordion-header")).text(theader);
   $( panel ).accordion( "option", "heightStyle", "content" );
  
  
 
}

function showResBySynsetIdInBrowse(id, item, def,lang,pos,type){
  var user=document.getElementById("myusername").value;
  var a_length = Object.keys(a).length;
  var t_length = Object.keys(t).length;
  //var i_length = Object.keys(i).length; 
  last_viewed=id; // this is the current viewed synsetid
  var test=false;
  var divname,div_vr_accordion,subdivname,header,theader,rsdiv,panel;
  def=stripslashes(def);
  /* managing type
  0) from input
  1) from target
  */
  if(type==0){
    divname="div_result_input_sub_nav_";
    subdivname="div_result_input_sub_nav_view";
    header="Input Section (Input language "+lang+"): browsing information for synset "+id + " ("+pos+") "+"\""+def + "\"";
    panel="#browses";
    panel_idx="browses h3";
    
  }
  if(type==1){
    divname="div_result_target_view_"+id+"_"+pos+"_"+lang;
    subdivname="div_result_target_sub_view_"+id+"_"+pos+"_"+lang;
    header="Target Section (Target language "+lang+"): browsing information for synset "+id + " ("+pos+") "+"\""+def + "\"";
    panel="#targets";
    panel_idx="#targets h3";
  }
  div_vr_accordion=document.getElementById(divname);
  if (div_vr_accordion ==null ){
    //alert(subdivname)
    var viewDiv = "<div id='"+divname+"'><h3><span class='accordion-header'></span></h3><div id='"+subdivname+"'></div></div>";
      $(panel).append(viewDiv)
     }
  
  rsdiv=document.getElementById(subdivname);
  var text='';
  var jsonData1 = $.ajax({
    url: "getAllBySynsetId.php",
    type: "POST",
    data:"id="+id+"&value="+item+"&def="+def+"&lang="+lang+"&type="+type+"&pos="+pos+"&ilang="+lang+"&field=sw&username="+user,
    dataType:"json",
    async: false
    }).responseText;
  text=text+jsonData1;
  text=text+"</br>";
  
  var jsonData1 = $.ajax({
    url: "getAllBySynsetId.php",
    type: "POST",
    data:"id="+id+"&value="+item+"&def="+def+"&lang="+lang+"&type="+type+"&pos="+pos+"&ilang="+lang+"&field=sr&username="+user,
    dataType:"json",
    async: false
    }).responseText;
    text=text+jsonData1;
    rsdiv.innerHTML=text;
  
  
  
  // refreshing
  var processingHeaders = $(panel + " h3");
  var last=processingHeaders.length;
  //alert (panel_idx+ " "+last);
  $( panel).find($( panel ).accordion( "option", "header" )).eq(last-1).find($("span.accordion-header")).text(header);
  $( panel ).accordion( "option", "heightStyle", "content" );
  $( panel ).accordion("option","disabled",false);
  $( panel ).accordion("refresh");
  $( panel ).accordion("option","active",last-1);   
  
  // target language panel(s)
        
  if(type==0){
    if (gtlangs.length>0){
      $('#targets').accordion("option","disabled",false); 
      $('#targets').accordion("refresh");   
      $( panel ).accordion("option","active",false);
      for (i=0;i<gtlangs.length;i++){
        var tlang=gtlangs[i];
        showResBySynsetIdInTarget(id, item, def,tlang,pos,lang)
      }
      
    }
   
  }

} // end function

function stripslashes(str) {
    str=str.replace(/\\'/g,'\'');
    str=str.replace(/\\"/g,'"');
    str=str.replace(/\\0/g,'\0');
    str=str.replace(/\\\\/g,'\\');
    return str;
}

function Popup(url){
    var style = "top=50, left=50, width=800, height=600, status=no, menubar=no, toolbar=no scrollbars=yes";
    window.open(url, "", style);
}

function getIndexOf(obj, val){
    var i;
    for (i=0; i<Object.keys(obj).length; i++){
        var key=Object.keys(obj)[i];
        if (key==val){
            //alert (key)
            break;
        }
    }
    return i;
 }
 
var a = {} // global object for indexing
var t = {} // global object for indexing target languages list
var i = {} // global object for  list of synset in target languages (similar to a)
var last_viewed=""; // contains the last viewed id
a["user"]=0;
a["search"]=1;
//t["target"]=0;

//global variable for target languages
 var gtlangs = new Array();

$('.edit_pos_brw').bind('dblclick', function() {
 alert ('qui');
 $(this).attr('contentEditable', true);
    }).blur(
 function() {
     $(this).attr('contentEditable', false);
 });
 
 $('.edit_pos_brw_t').bind('dblclick', function() {
 alert ('qui');
 $(this).attr('contentEditable', true);
    }).blur(
 function() {
     $(this).attr('contentEditable', false);
 });
 
 


