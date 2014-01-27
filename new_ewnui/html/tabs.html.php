<?php
?>
<style>
#panels .ui-icon { display: none; }
.tabs li .ui-icon-close { float: left; margin: 0.4em 0.2em 0 0; cursor: pointer; }
.i_tabs li .ui-icon-close { float: left; margin: 0.4em 0.2em 0 0; cursor: pointer; }
.b_tabs li .ui-icon-close { float: left; margin: 0.4em 0.2em 0 0; cursor: pointer; }

.t_tabs li .ui-icon-close { float: left; margin: 0.4em 0.2em 0 0; cursor: pointer; }
.i_t_tabs li .ui-icon-close { float: left; margin: 0.4em 0.2em 0 0; cursor: pointer; }
.b_t_tabs li .ui-icon-close { float: left; margin: 0.4em 0.2em 0 0; cursor: pointer; }

.tabs li  .ui-icon-pencil { float: left; margin: 0.4em 0.2em 0 0; cursor: pointer; }
.i_tabs li .ui-icon-pencil { float: left; margin: 0.4em 0.2em 0 0; cursor: pointer; }
.b_tabs li .ui-icon-pencil { float: left; margin: 0.4em 0.2em 0 0; cursor: pointer; }

.t_tabs li  .ui-icon-pencil { float: left; margin: 0.4em 0.2em 0 0; cursor: pointer; }
.i_t_tabs li .ui-icon-pencil { float: left; margin: 0.4em 0.2em 0 0; cursor: pointer; }
.b_t_tabs li .ui-icon-pencil { float: left; margin: 0.4em 0.2em 0 0; cursor: pointer; }


</style>
<div class="panels" id="panels">
    <div>
        <h3 id ="userloginpanel" style="text-align:top;"><a href="#div_lp_accodion"><img src='./img/login.png' >&nbsp;&nbsp;&nbsp;User Login</a></h3>
        <div id="div_lp_accodion">
            <?php 
                if($username=="" AND $nickname==""){
                    include('./html/loginform.html.php');
                } else {
                    include('./html/loggeduser.html.php');
                }
            ?>
        </div>
    </div>
<div id="tabs" class="tabs">
    <ul class="ulCls">
        <li><a href="#div_search"><img src='./img/search-icon-scaled.png' >&nbsp;&nbsp;&nbsp;Search Panel</a><span></span></li>

    </ul>
    <div id="div_search">
        <?php
            include('./html/searchform.html.php');
        ?>
    </div>
</div> <!-- end main tab -->

<div id="t_tabs" class="t_tabs">
    <ul class="t_ulCls">
    </ul>
</div> <!-- end target tab -->

<script>
$( "#tabs" ).tabs({
        collapsible: true,
        active: true,
        autoHeight: true,
        heightStyle: "content",
        active:0,
    });

 $( ".i_tabs" ).tabs({
        collapsible: true,
        active: true,
        autoHeight: true,
        heightStyle: "content",
        active:0
    });
    
 $( ".b_tabs" ).tabs({
        collapsible: true,
        active: true,
        autoHeight: true,
        heightStyle: "content",
        active:0
    });   

$( "#t_tabs" ).tabs({
        collapsible: true,
        active: true,
        autoHeight: true,
        heightStyle: "content",
    });
    
   $( ".i_t_tabs" ).tabs({
        collapsible: true,
        active: true,
        autoHeight: true,
        heightStyle: "content",
        active:0
    });
    
 $( ".b_t_tabs" ).tabs({
        collapsible: true,
        active: true,
        autoHeight: true,
        heightStyle: "content",
        active:0
    });    
    
 $(document).ready(function() {
    $( "> div", "#panelDispos" ).draggable({
        helper: "clone",
        revert: "invalid",
        cursor: "move",
        handle: "h3",
        connectToSortable: ".panels"
    });
    
    $( "#panels" ).accordion({
        header: "> div > h3",
        collapsible: true,
        active: false,
        autoHeight: false,
        autoActivate: true,
        icons: {},
        active: 1,
    });
    $("#panels").click(function() {
        var indexToActivate = $('#panels:contains("Browse the Resource")').index();
        var active = $( "#panels" ).accordion( "option", "active" ); //getter
        var processingHeaders = $('#panels h3');
        var last=processingHeaders.length
        if (active==1 && last-1>active){
            // click on search page disable 
            //$( "#panels" ).accordion( "destroy" );
            $('#panels h3').find(last).remove();
            }
        //alert('Current New Index is ' + active +" and last "+last);
    //alert(current);
});
  
    
    $( "#panelDispos" ).accordion({
        header: "> div > h3",
        collapsible: true,
        active: false,
        autoHeight: true,
        heightStyle: "content",
    });
    $( "button" ).button();
});   

// removing the tab
 $(function() {
     var tabs = $( ".tabs" ).tabs();
     // close icon: removing the tab on click
     tabs.delegate( "span.ui-icon-close", "click", function() {
         var panelId = $( this ).closest( "li" ).remove().attr( "aria-controls" );
         var div=document.getElementById(panelId);
         $( "#" + panelId ).remove();
         tabs.tabs( "refresh" );
    });
        
    var tabs = $( ".t_tabs" ).tabs();
    // close icon: removing the tab on click
    tabs.delegate( "span.ui-icon-close", "click", function() {
        var panelId = $( this ).closest( "li" ).remove().attr( "aria-controls" );
        var div=document.getElementById(panelId);
        $( "#" + panelId ).remove();
        tabs.tabs( "refresh" );
    });
    
    var tabs = $( ".tabs" ).tabs();
   
    tabs.delegate( "span.ui-icon-pencil", "click", function() {
        var ilangElem=document.getElementById("id_ilang"),ilang;
        if (ilangElem ==null)
            ilang="eng"
        else
            ilang=ilangElem.value;
        
        var panelId = $( this ).closest( "li" ).attr( "aria-controls" );
        if (ilang=='eng' || ilang=='ita')
            alert ("You are not granted to edit English and Italian Reasources. Input language: "+ilang);
        else {
            //alert (ilang);
            var pos_in="edit_pos_"+panelId;
            var gloss_in="edit_gloss_"+panelId;
            var btn_in="edit_btn_"+panelId;
            panelId=".edit_"+panelId;
            //alert ("delegate "+panelId);
        
            $(panelId).toggle();
       
            //alert ("btn "+btn_in);
            $("#"+pos_in).attr("disabled",false);    
            $("#"+gloss_in).attr("disabled",false);    
            //$("#"+btn_in).attr("disabled",false);    
            $("#"+btn_in).show();   
            
            }
    });
    
    var tabs = $( ".t_tabs" ).tabs();
        tabs.delegate( "span.ui-icon-pencil", "click", function() {
        var panelId = $( this ).closest( "li" ).attr( "aria-controls" );
        var ilang = "eng";
            //alert(panelId.indexOf(l1) != -1);
         if (panelId.indexOf(ilang) != -1)
            alert ("You are not granted to edit English and Italian Reasources. Input language: "+ilang);
        else {
            var pos_in="edit_pos_"+panelId;
            var gloss_in="edit_gloss_"+panelId;
            var btn_in="edit_btn_"+panelId;
            panelId=".edit_"+panelId;
            //alert ("delegate "+panelId);
            //alert ("delegate "+panelId);
        
            $(panelId).toggle();
       
            //alert ("btn "+btn_in);
            $("#"+pos_in).attr("disabled",false);    
            $("#"+gloss_in).attr("disabled",false);    
            //$("#"+btn_in).attr("disabled",false);    
            $("#"+btn_in).show();    
        }
          
    });
        
        tabs.bind( "keyup", function( event ) {
           
            if ( event.altKey && event.keyCode === $.ui.keyCode.BACKSPACE ) {
                var panelId = tabs.find( ".ui-tabs-active" ).remove().attr( "aria-controls" );
                alert ("bind "+panelId);
                $( "#" + panelId ).remove();
                tabs.tabs( "refresh" );
            }
        });
    });
</script>