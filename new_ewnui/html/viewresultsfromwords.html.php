<?php

global $tlangs;
?>


<!-- div to go back -->
<!-- MAIN DIV: RESULTS FROM SERACH PARAMETERS -->  
<input type='hidden' value='<?php echo $lang; ?>' id='id_ilang'>
<div id="div_result_main" class="divResultMainCls">

<!--Results for searchtype: <b><i><?php echo $htype; ?></i></b> and item: <b><i><?php echo $value; ?></i></b> and input language: <b></i><?php echo $lang?></i></b></br> -->
<?php
echo "Found ".count($res)." synsets containing <b><i>$value </i></b> and input language: <b></i> $lang </i></b> </br>";
?>
</br>
<!-- each synset has its div: different id but same class to be furtherly styled -->
<?php
for ($i=0; $i<count($res); $i++){

    $id=$res[$i]['synsetid'];
    $definition=$res[$i]['definition'];
    $definition_1=$res[$i]['definition'];
    $definition = addslashes($definition);
    $pos=$res[$i]['pos'];
    $sw=$res[$i]['sw'];
    ?>
    <div id="div_result_main_<?php echo $id; ?>" class = "divResultMainCls_sub">
    <a href="javascript:showResBySynsetId('<?php echo $id;?>','<?php echo $value;?>','<?php echo $definition;?>','<?php echo $lang;?>','<?php echo $pos;?>',0);"><?php echo $id; ?></a>&nbsp; <?php echo "(".$pos.")"; ?>
        <?php echo $definition_1."       [".$sw. "]"; ?>
           <!-- <div id="div_result_main_<?php echo $id; ?>_js" class = "divResultMainCls_sub">
                <a href="javascript:showAll('<?php echo $id;?>','<?php echo $value;?>','<?php echo $definition;?>','<?php echo $lang;?>','<?php echo $pos;?>',0);">Show All</a>&nbsp; 
                <a href="javascript:showWords('<?php echo $id;?>','<?php echo $value;?>','<?php echo $definition;?>','<?php echo $lang;?>','<?php echo $pos;?>',0);">Show Contained Word</a>&nbsp;
                <a href="javascript:showRelations('<?php echo $id;?>','<?php echo $value;?>','<?php echo $definition;?>','<?php echo $lang;?>','<?php echo $pos;?>',0);">Show Relations</a>
            </div>
            -->
         </div>
        <?php
    }
?>



</div> <!-- END MAIN DIV -->
