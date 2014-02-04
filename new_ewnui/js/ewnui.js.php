<?php
?>



// logout 
function logout(){
  var user=document.getElementById("myusername").value;
  var ulog=document.getElementById("userloginpanel");
  var mysalt=document.getElementById("mysalt").value;
  var maindiv=document.getElementById("div_main_login");
  var maindiv=document.getElementById("div_main_login");
  var jsonData1 = $.ajax({
      url: "resetSession.php",
		  type: "POST",
		  data:"user="+user+"&mysalt="+mysalt,
      dataType:"json",
      async: false
      }).responseText;
    if (jsonData1==1){
        var text='<div id ="div_login" class="divLoginCls"><div id="div_caption">You must Login to access editing features. </br></br></div>';
        //text=text+'<input type="text" value ="'+user+'">'
        var textTab='<table  class="tabLoginClass" border="0"><tr><td colspan="2">Provide username and password</td></tr>';
        textTab=textTab+'<tr><td class="labelTdClass">Username:</td><td class="fieldTdClass"><input name="myusername" type="text" id="myusername"  value=""></td></tr>';
        textTab=textTab+'<tr><td class="labelTdClass">Password:</td><td class="fieldTdClass"><input name="mypassword" type="password" id="mypassword" value=""></td></tr>';
        textTab=textTab+'<tr><td><button id="btnLogin">Login</button></td><td>&nbsp;</td></tr></table>';
        var textVal='</div><div id="div_validate_form" class="divValidateFormCls" style="align:center;"></div>';
    
        text=text+textTab+textVal;
        //alert (textTab);  
        maindiv.innerHTML=text;
        ulog.innerHTML="<a href=\"#div_lp_accodion\"><img src='./img/login.png' >&nbsp;&nbsp;&nbsp;User Login</a>";
        $( "#panels" ).accordion( "option", "icons", {"header": "ui-icon-triangle-1-e", "activeHeader": "ui-icon-triangle-1-s"}, "active: true" );
 
        $( "#panels" ).accordion( "option", "active", 1 );
        $( "#panels" ).accordion( "refresh" );
 
        $( "#btnLogin" ).click(function() {
            validateAndSubmitForm();
        });
        
        // hide icons
        $('span.ui-icon-pencil').each(function() {
            main="#div_search";
            var panelId = $( this ).closest( "li" ).attr( "aria-controls" );
            //alert ("icon "+panelId);
            $(this).hide();
            //hide words
            var pos_in="edit_pos_"+panelId;
            var gloss_in="edit_gloss_"+panelId;
            $("#"+pos_in).attr("disabled",true);    
            $("#"+gloss_in).attr("disabled",true);    
    });
}
 else{
     alert("Something went wrong. You are still logged with "+user+ " and "+mysalt); 
     }
}
    
    
  function resetForm(){
    /*checkdate fields and decode*/
    document.getElementById("searchTxt").value="";
    document.getElementById("mycursyn").value="";
    document.getElementById("mycurpos").value="";
    document.getElementById("sbyw").checked=true;
    document.getElementById("tlgrc").checked=true;
    document.getElementById("tlita").checked=false;
    document.getElementById("tllat").checked=false;
    document.getElementById("tlara").checked=false;
    document.getElementById("tlhrv").checked=false;
    document.getElementById("tleng").checked=false;
    document.getElementById("leng").checked=true;
    
    document.getElementById("tleng").disabled=true;
    document.getElementById("tlita").disabled=false;
    document.getElementById("tllat").disabled=false;
    document.getElementById("tlara").disabled=false;
    document.getElementById("tlgrc").disabled=false;
    document.getElementById("tlhrv").disabled=false;
    gtlangs=new Array();
    //$("#targets").accordion("destroy");

}

/**
Destroy the tabs of the results and purge the main one
**/
function resetTabs(){
    var tabs = $( ".tabs" ).tabs();
    /*tabs.delegate( "span.ui-icon-close", "click", function() {
        var panelId = $( this ).closest( "li" ).remove().attr( "aria-controls" );
        alert ("delegate "+panelId);
        $( "#" + panelId ).remove();
        tabs.tabs( "refresh" );
        });

*/
    $('.tabs .ui-tabs-nav a').each(function() {
        main="#div_search";
         var id = $(this).attr('href');
       // alert (id+ " - "+main);
        if(id!=main){
            var panelId = $( this ).closest( "li" ).remove().attr( "aria-controls" );
            //alert ("delegate "+panelId);
            $( id ).remove();
             tabs.tabs( "refresh" );
            
           }
    });
    
    $('.t_tabs .ui-tabs-nav a').each(function() {
        main="#div_search";
         var id = $(this).attr('href');
        //alert (id+ " - "+main);
        if(id!=main){
            var panelId = $( this ).closest( "li" ).remove().attr( "aria-controls" );
            //alert ("delegate "+panelId);
            $( id ).remove();
             tabs.tabs( "refresh" );
            
           }
    });
    
    
}  

function validateFormAndAddPanelWithResults(){
    var obj="#tabs";
    var  name="searchFrm";
    var ilang,typeofsearch,elem,username;
    var tlangs = new Array();
    var olangs = new Array();
    var odrs = new Array();
    var value=document.getElementById("searchTxt").value;
    var value1=value;
    var disp="Synsets";
    username=document.getElementById("myusername").value;
    
    if (value1.trim()==""){
      alert ("There is no item to search for");
      document.getElementById("searchTxt").value=value;
      } 
    else {
      /* here we have to search according to
      1) input language
      2) search type
      3) elem to search
      4) target languages
      5) domain resources
      */
 
      //1 )
      var radios = document[name]['lang']; 
      for( j = 0; j < radios.length; j++ ) {
        // alert (radios[j].value)
        if( radios[j].checked ) {
          ilang=radios[j].value;
          //   alert( "checked "++ ", for "+getme );
          //  alert ('lang='+ilang);
          //nvsArr.push(radios[j].value);
        }
      }
 
      //2
      var radios = document[name]['typeofsearch']; 
      for( j = 0; j < radios.length; j++ ) {
        // alert (radios[j].value)
        if( radios[j].checked ) {
          typeofsearch=radios[j].value;
          //   alert( "checked "++ ", for "+getme );
          //alert ('typeofsearch='+typeofsearch);
          //nvsArr.push(radios[j].value);
        }
      }
       //addendum
       if (typeofsearch==1)
            disp="Words"
      //3
      elem=document.getElementById("searchTxt").value;
 
      //4
      tlangs = document[name][ 'tlang[]' ];
      for( j = 0; j < tlangs.length; j++ ) {
        if(tlangs[j].checked){
            var gtl=gtlangs.length;
            var val=tlangs[j].value;
            var idx=$.inArray(val, gtlangs);
            olangs.push(val)
            // init
            if(gtl==0){
                
                gtlangs.push(val)
            } else {
                if (idx==-1 ){
                    gtlangs.push(val)
                }
            }
        }
      }

      //5
      drs = document[name][ 'dr[]' ];
      for( j = 0; j < drs.length; j++ ) {
        if(drs[j].checked){
          //  alert (drs[j].value)
          odrs.push(drs[j].value)
        }
      }
      var mydata="";
      
      
      mydata={lang: ilang, typeofsearch:typeofsearch,tlang:olangs,elem:elem,myusername:username,dr:odrs}
      var jsonCount = $.ajax({
      url: "getCounts.php",
		    type: "POST",
		    data: mydata,
            async: false
        }).responseText;
      var  div_vr_accordion=document.getElementById("div_vr_accordion");
      if (div_vr_accordion ==null ){
            var li ="<li><a href='#div_vr_accordion'><img src='./img/view-list-icon.png' >&nbsp;&nbsp;&nbsp;Listing <b>"+jsonCount+ "</b> " +disp+ "</a><span class='ui-icon ui-icon-close' role='presentation' style='display:block;'>Remove Tab</span></li>";
            //var li ="<li><a href='#div_vr_accordion'><img src='./img/view-list-icon.png' >&nbsp;&nbsp;&nbsp;Listing <b>"+jsonCount+ "</b> " +disp+ " for Input Language: "+ilang+"; item: "+elem+") </a><span></span></li>";
            var viewDiv = "<div id='div_vr_accordion'><div id='top_view'></div></div>";
            $("div#tabs ul").append(li);
            $("div#tabs").append(viewDiv)
      }
      var rsdiv=document.getElementById("top_view");
      var jsonData1 = $.ajax({
      url: "proxme.php",
		    type: "POST",
		    data: mydata,
        async: false
      }).responseText;
      rsdiv.innerHTML=jsonData1;
      }
    $( obj).tabs( "option", "heightStyle", "content" );
    $(obj).tabs("refresh");   
    $(obj).tabs("option","active",1);      
    
}
 
 




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
   // var idx=-1;
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

$('#def_grc').bind('dblclick', function() {
 alert ('qui');
 $(this).attr('contentEditable', true);
    }).blur(
 function() {
     $(this).attr('contentEditable', false);
 });
 


