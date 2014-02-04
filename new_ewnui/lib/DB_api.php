<?php
#connection functions
require_once("./utils/vars.php");
require_once("./utils/tablelist.php");

# connection to databases
function GetMyConnection()
{
	global $db_hostname;
	global $db_username;
	global $db_password;
	global $debug;
    global $connection;
    
    $connection = mysql_connect($db_hostname, $db_username, $db_password)  or die("Could not connect to server. With parameters: $db_hostname, $db_username, $db_password");
    if ($debug==1){
    echo "Connection successfull. Parameters:</br>";
                echo "\thostname: $db_hostname</br>";
                echo "\tusername: $db_username</br>";
                echo "\tpasswd: ***</br>";
    }
    return $connection;
}

function CloseConnection()
{
	global $connection;
	if( $connection )
	{
		mysql_close();
		$connection = false;
	}
}

# switch connection according to language and target language
function SwitchConnection($lang, $connection, $type){
    global $grc_database;
    global $eng_database;
    global $ita_database;
    global $iwn_database;
    global $side_grc_database;
    global $lat_database;
    global $ara_database;
    global $hrv_database;
    global $mapiwn_database;
    global $mapiliwn_database;
    global $sidem_database;
     global $login_database;
    global $connection;
    global$debug;
    
    if ($type==0) {  //input language
     if ($lang==="eng"){
            $db=$eng_database;
            }
      if ($lang==="ita"){
            $db=$ita_database; 
        }
       if ($lang==="grc"){
            $db=$grc_database; 
            } 
         if ($lang==="lat"){
            $db=$lat_database; 
            } 
        if ($lang==="ara"){
            $db=$ara_database; 
            } 
         if ($lang==="hrv"){
            $db=$hrv_database; 
            }   
}
    if ($type==1) {  //output language
     if ($lang==="eng"){
            $db=$eng_database;
            }
      if ($lang==="ita"){
            $db=$side_grc_database; 
        }
       if ($lang==="grc" OR $lang==="lat" OR $lang=="ara" OR $lang=="eng" OR $lang=="hrv" ){
            $db=$side_grc_database; 
            }  
}
if ($type==2)
    $db=$login_database;
    
  if ($type==3) {  //input language <> eng
      if ($lang==="ita"){
            $db=$mapiwn_database; 
    }
     if ($lang==="hrv"){
            $db=$maphwn_database; 
        }
       if ($lang==="grc" OR $lang==="lat" OR $lang=="ara" OR $lang=="hrv" ){
            $db=$mapiliwn_database; 
            }  
}    
if ($debug==1)
    echo "\tSwitchConnection( $lang,$connection, $type) Connected to database $db</br>";
mysql_select_db($db, $connection) or die('Could not select database.  '.$db);
    return $connection;
}

/*
returns the list of synsetid definition and pos from the word
view: wordsXsensesXsynsets
*/
function getSynsetIdsFromWord($connection, $table,$word){
        global $debug;
        $lists = array();
        $myquery = "SELECT distinct synsetid, definition, pos FROM " . $table . " where lemma = '$word' ;";
        //$myquery = "SELECT distinct synsetid, definition, pos FROM " . $table . " where wordidmd5 = md5('$word') ;";
          //$myquery = "SELECT distinct synsetid, definition, pos FROM " . $table . " LIMIT 10;";
		mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $connection);

        if( $debug){
                echo "\tgetSynsetIdsFromWord($connection , $table, $word)</br>";
                echo "\tExecuting ". $myquery. "<br/> ";
        }
        $result = mysql_query($myquery);
        if ($result==FALSE) {
            echo 'Invalid query: '.mysql_error();
            }
	
	
	
      while($row=mysql_fetch_array($result)){
		$info_obj = array();
		$info_obj['synsetid'] = $row['synsetid'];
		$info_obj['definition'] = $row['definition'];
        $info_obj['pos'] = $row['pos'];
        $sw=getSerializedWordsFromSynsetId($connection, $table,$row['synsetid'],$word);
        $info_obj['sw'] = $sw;
		$lists[] = $info_obj;
	}

	//echo "XXXXXXXXXXXXXXXXXXXXX ".$res."</br>";
	return $lists;
}

/*
returns the list of synsetid definition and pos from the word
view: wordsXsensesXsynsets
*/
function getCountSynsetIdsFromWord($connection, $table,$word){
        global $debug;
        $lists = array();
        $myquery = "SELECT count(distinct synsetid) as tot FROM " . $table . " where lemma = '$word' ;";
        //$myquery = "SELECT distinct synsetid, definition, pos FROM " . $table . " where wordidmd5 = md5('$word') ;";
          //$myquery = "SELECT distinct synsetid, definition, pos FROM " . $table . " LIMIT 10;";
		mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $connection);

        if( $debug){
                echo "\tgetCountSynsetIdsFromWord($connection , $table, $word)</br>";
                echo "\tExecuting ". $myquery. "<br/> ";
        }
        $result = mysql_query($myquery);
        if ($result==FALSE) {
            echo 'Invalid query: '.mysql_error();
            }
	
	
	
      while($row=mysql_fetch_array($result)){
		$info_obj = array();
		$info_obj['tot'] = $row['tot'];
		$lists[] = $info_obj;
	}

	//echo "XXXXXXXXXXXXXXXXXXXXX ".$res."</br>";
	return $lists;
}


/*
returns the list of synsetid definition and pos from the word
table: synsets
*/
function getDefPosFromSynsetId($connection, $table,$id){
        global $debug;
        $lists = array();
        $myquery = "SELECT distinct synsetid, definition, pos FROM " . $table . " where synsetid = '$id' ;";
        //$myquery = "SELECT distinct synsetid, definition, pos FROM " . $table . " where wordidmd5 = md5('$word') ;";
          //$myquery = "SELECT distinct synsetid, definition, pos FROM " . $table . " LIMIT 10;";
		mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $connection);

        if( $debug){
                echo "\tgetDefPosFromSynsetId($connection , $table, $id)</br>";
                echo "\tExecuting ". $myquery. "<br/> ";
        }
        $result = mysql_query($myquery);
        if ($result==FALSE) {
            echo 'Invalid query: '.mysql_error();
            }
	
	
	
      while($row=mysql_fetch_array($result)){
		$info_obj = array();
		$info_obj['synsetid'] = $row['synsetid'];
		$info_obj['definition'] = $row['definition'];
        $info_obj['pos'] = $row['pos'];
		$lists[] = $info_obj;
	}

	//echo "XXXXXXXXXXXXXXXXXXXXX ".$res."</br>";
	return $lists;
}

/*
returns the list of synsetid definition and pos from the word
table: synsets
*/
function getXPosGenerationRule($connection, $table,$lang,$pos_1,$pos_2){
        global $debug;
        $lists = array();
        $myquery = "SELECT distinct rule FROM " . $table . " where pos_1 = '$pos_1'  and pos_2='$pos_2' and lang='$lang';";
        //$myquery = "SELECT distinct synsetid, definition, pos FROM " . $table . " where wordidmd5 = md5('$word') ;";
          //$myquery = "SELECT distinct synsetid, definition, pos FROM " . $table . " LIMIT 10;";
		mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $connection);

        if( $debug){
                echo "\tgetXPosGenerationRule($connection , $table, $pos_1,$pos_2)</br>";
                echo "\tExecuting ". $myquery. "<br/> ";
        }
        $result = mysql_query($myquery);
        if ($result==FALSE) {
            echo 'Invalid query: '.mysql_error();
            }
	
	
	
      while($row=mysql_fetch_array($result)){
		$info_obj = array();
		$info_obj['rule'] = $row['rule'];
		$lists[] = $info_obj;
	}

	//echo "XXXXXXXXXXXXXXXXXXXXX ".$res."</br>";
	return $lists;
}

/*
returns the mapped synset
table: <lang>Map
*/
function getMappedSynsetFromTargetSynset($connection, $table,$id, $pos){
        global $debug;
        $lists = array();
        $myquery = "SELECT distinct synsetid_1 FROM " . $table . " where synsetid_2 = '$id' and pos='$pos' ;";
        //$myquery = "SELECT distinct synsetid, definition, pos FROM " . $table . " where wordidmd5 = md5('$word') ;";
          //$myquery = "SELECT distinct synsetid, definition, pos FROM " . $table . " LIMIT 10;";
		mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $connection);

        if( $debug){
                echo "\tgetMappedSynsetFromTargetSynset($connection , $table, $id)</br>";
                echo "\tExecuting ". $myquery. "<br/> ";
        }
        $result = mysql_query($myquery);
        if ($result==FALSE) {
            echo 'Invalid query: '.mysql_error();
            }
	
	
	
      while($row=mysql_fetch_array($result)){
		$info_obj = array();
		$info_obj['synsetid'] = $row['synsetid_1'];
		$lists[] = $info_obj;
	}

	//echo "XXXXXXXXXXXXXXXXXXXXX ".$res."</br>";
	return $lists;
}
/*
returns the list of words from the synsetid
view: wordsXsensesXsynsets
*/
function getWordsFromSynsetId($connection, $table,$param){
        global $debug;
        $lists = array();
        $myquery = "SELECT distinct lemma, pos, sensenum FROM " . $table . " where synsetid = '$param' ;";
		mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $connection);

        if( $debug){
                echo "\tgetWordsFromSynsetId($connection , $table, $param)</br>";
                echo "\tExecuting ". $myquery. "<br/>";
        }
        $result = mysql_query($myquery);
        if ($result==FALSE) {
            echo 'Invalid query: '.mysql_error();
            }
	
	
	
      while($row=mysql_fetch_array($result)){
		$info_obj = array();
		$info_obj['lemma'] = $row['lemma'];
        $info_obj['pos'] = $row['pos'];
        $info_obj['sensenum'] = $row['sensenum'];
		$lists[] = $info_obj;
	}

	//echo "XXXXXXXXXXXXXXXXXXXXX ".$res."</br>";
	return $lists;
}



/*
returns the serialization of words from the synsetid
view: wordsXsensesXsynsets
*/
function getSerializedWordsFromSynsetId($connection, $table,$param, $word){
        global $debug;
        $lists = array();
        $limit=4;
        $myquery = "SELECT distinct lemma FROM " . $table . " where synsetid = '$param' LIMIT ".($limit+1).";";
		mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $connection);

        if( $debug){
                echo "\tgetSerializedWordsFromSynsetId($connection , $table, $param)</br>";
                echo "\tExecuting ". $myquery. "<br/>";
        }
        $result = mysql_query($myquery);
        if ($result==FALSE) {
            echo 'Invalid query: '.mysql_error();
            }
	
	
	 $str="";
     $i=0;
     $c=mysql_num_rows($result);
    // echo "-$c-";
      while($row=mysql_fetch_array($result)){
          //echo "OK $lim $c";
		$info_obj = array();
        $w=$row['lemma'];
        if ($w==$word)
            //$w="<b><i>".$w."</i></b>";
            $w=$w;
            //$w= "<font color='red'>".$w."</font>"; 
        if ($c<=$limit){
             if($i<$c-1)
                $str=$str.$w.", ";
            else
                 $str=$str.$w;
            }
        if ($c>$limit){
             if($i<$c-1){
                
                $str=$str." ".$w.", ";
                }
            else
                 $str=$str." ".$w.", ... ";
            }
        $i++;
	}
    //echo $str;
	return $str;
}


/*
returns the serialization of words with different colors
according to the language and sources
lat and  db=2,3 color=red
tab1: <lang>Ws
tab2: <lang>Map
tab3: <lang>SynsetXsynsetMap

*/
function getColoredSerializedXXXXWordsFromSources($connection, $tableMap, $tableWs, $table_3, $param, $ser_word,$lang){
        global $debug;
        $lists = array();
        $limit=4;
        $words = explode(",", unserialize($ser_word));
        $ret=$ser_word;
        $myquery="";
        $myquery_1="";
        $num=count($words);
        //echo "XXXXX $num";
        if ($num>0){
            if ($num>$limit)
                $num=$limit+1;
            for ($j=0; $j<$num; $j++){
                $words[$j]=str_replace("<font color='red'>","",$words[$j]);
                $words[$j]=str_replace("</font>","",$words[$j]);
                $w=trim($words[$j]);
                // create the query
                if ($j<$num-1){
                     $myquery = $myquery." SELECT distinct word as lemma, feat_val as val FROM " . $tableWs . " w , ".$tableMap ." m where m.synsetid_2= '$param' and w.synsetid=m.synsetid_1 and feat_att='db' and word='$w' UNION ";
                     $myquery = $myquery." SELECT distinct word as lemma, feat_val as val FROM " . $tableWs . " w , ".$table_3 ." m where m.synsetid_2= '$param' and w.synsetid=m.synsetid_1 and feat_att='db' and word='$w' UNION ";
                    }else{
                        $myquery = $myquery." SELECT distinct word as lemma , feat_val as val FROM " . $tableWs . " w , ".$tableMap ." m where m.synsetid_2= '$param' and w.synsetid=m.synsetid_1 and feat_att='db' and word='$w' UNION ";
                        $myquery = $myquery." SELECT distinct word as lemma, feat_val as val FROM " . $tableWs . " w , ".$table_3 ." m where m.synsetid_2= '$param' and w.synsetid=m.synsetid_1 and feat_att='db' and word='$w' ORDER BY val desc;";
                        }
        }
        
        // exec 
        	mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $connection);

            if($debug){
                echo "\tgetColoredSerializedWordsFromSources($connection , $table, $param,$ser_word,$lang)</br>";
                echo "\tExecuting ". $myquery. "<br/>";
            }
            $result = mysql_query($myquery);
            if ($result==FALSE) {
                echo 'Invalid query: '.mysql_error();
                }
	 $str="";
     $i=0;
     //$c=mysql_num_rows($result);
     $c=$num;
    // echo "-$c-";
      while($row=mysql_fetch_array($result)){
          //echo "OK $lim $c";
		$info_obj = array();
        $w=$row['lemma'];
        $val=$row['val'];
        
        if ($w==$word)
            //$w="<b><i>".$w."</i></b>";
            $w= "<font color='red'>".$w."</font>";   
        if ($val==2 || $val==3)
            //$w= "<font color='red'>".$w."</font>";    
            $w= "<b><i>".$w."</i></b>";    
        if ($c<=$limit){
             if($i<$c-1)
                $str=$str.$w.", ";
            else
                 $str=$str.$w;
            }
        if ($c>$limit){
             if($i<$c-1){
                
                $str=$str." ".$w.", ";
                }
            else
                 $str=$str." ".$w.", ... ";
            }
        $i++;
	}
    //echo $str;
	return $str;
            
        }
        if ($num==0 || mysql_num_rows($result)==0)  {
            return $ser_word;
            }
        
	
}

/*
returns the serialization of words with different colors
according to the language and sources
lat and  db=2,3 color=red
tab1: <lang>Ws
tab2: <lang>Map
tab3: <lang>SynsetXsynsetMap

*/
function getColoredSerializedWordsFromSources($connection, $tableMap, $tableWs, $table_3, $param, $ser_word,$lang){
    global $debug;
    $lists = array();
    $limit=4;
    $words = explode(",", unserialize($ser_word));
    $ret=$ser_word;
    $myquery="";
    $myquery_1="";
    $num=count($words);
    $info_obj = array();
    $list = array();
    $list_0 = array();
    $endStr="";
    //echo "XXXXX ".$num."</br>";
    if ($num>0){
            if ($num>$limit+1){
                $endStr="";
                
                }
            // create the array 
            for ($j=0; $j<$num; $j++){  
                $words[$j]=str_replace("<font color='red'>","",$words[$j]);
                $words[$j]=str_replace("</font>","",$words[$j]);
                $w=trim($words[$j]);
                $myquery = " SELECT distinct word as lemma, feat_val as val FROM " . $tableWs . " w , ".$tableMap ." m where m.synsetid_2= '$param' and w.synsetid=m.synsetid_1 and feat_att='db' and word='$w' UNION ";
                $myquery = $myquery." SELECT distinct word as lemma, feat_val as val FROM " . $tableWs . " w , ".$table_3 ." m where m.synsetid_2= '$param' and w.synsetid=m.synsetid_1 and feat_att='db' and word='$w' ORDER BY val desc;";
                
                // exec the query
                mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $connection);

                if($debug){
                    echo "\tgetColoredSerializedItaWordsFromSources($connection , $table, $param,$ser_word,$lang)</br>";
                    echo "\tExecuting ". $myquery. "<br/>";
                }
                $result = mysql_query($myquery);
                if ($result==FALSE) {
                    echo 'Invalid query: '.mysql_error();
            }
             // check number of rows 
            
            if (mysql_num_rows($result)==0){
                $info_obj['lemma']=$words[$j];
                $info_obj['val']=0;
                $list[]= $info_obj;
            } else {
                while($row=mysql_fetch_array($result)){   
                    //echo "XXXXX ".mysql_num_rows($result)."</br>";
                    $info_obj['lemma']=$row['lemma'];
                    $info_obj['val']=$row['val'];   
                    //$list[]=$info_obj;
                    $list[]=array_merge($list,$info_obj);
                } // end loop over results
                
        }
        //echo "XXXX ".count($list);
        //print_r($list);
        $str="";
            for($i=0; $i<count($list); $i++){
                $w=$list[$i]['lemma'];
                $val=$list[$i]['val'];
               //echo "XXXX ".$w;
               if ($val==2 || $val==3)
                    $w="<b><i>".$w."</i></b>";
               if ($i<count($list) -1){
                $str=$str.$w.", ";
               } else {
                   $str=$str.$w.$endStr;
                   }
            }
        } // end for 
    } else{
        return $ser_word;
    }
    return $str;
}
/*
returns the serialization of words with different colors
according to the language and sources
lat and  db=2,3 color=red
tab1: <lang>Ws
tab2: <lang>Map
tab3: <lang>SynsetXsynsetMap

*/
function getColoredSerializedXXXWordsFromSources($connection, $tableMap, $tableWs, $table_3, $param, $ser_word,$lang){
        global $debug;
        $lists = array();
        $limit=4;
        $words = explode(",", unserialize($ser_word));
        $ret=$ser_word;
        $myquery="";
        $myquery_1="";
        $num=count($words);
        $info_obj = array();
        if ($num>0){
            if ($num>$limit)
                $num=$limit+1;
            $str="";
            //echo "XXXXX $num";
            for ($j=0; $j<$num; $j++){  
                $words[$j]=str_replace("<font color='red'>","",$words[$j]);
                $words[$j]=str_replace("</font>","",$words[$j]);
                $w=trim($words[$j]);
                $myquery = " SELECT distinct word as lemma, feat_val as val FROM " . $tableWs . " w , ".$tableMap ." m where m.synsetid_2= '$param' and w.synsetid=m.synsetid_1 and feat_att='db' and word='$w' UNION ";
                $myquery = $myquery." SELECT distinct word as lemma, feat_val as val FROM " . $tableWs . " w , ".$table_3 ." m where m.synsetid_2= '$param' and w.synsetid=m.synsetid_1 and feat_att='db' and word='$w' ORDER BY val desc;";
                
                // exec the query
                mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $connection);

                if($debug){
                    echo "\tgetColoredSerializedItaWordsFromSources($connection , $table, $param,$ser_word,$lang)</br>";
                    echo "\tExecuting ". $myquery. "<br/>";
                }
                $result = mysql_query($myquery);
                if ($result==FALSE) {
                    echo 'Invalid query: '.mysql_error();
            }
            //echo "XXXXX ".mysql_num_rows($result)."</br>";
            if (mysql_num_rows($result)==0){
                echo "XXX VOID ".$words[$j]."</br>".$myquery."</br>";
                $info_obj['lemma']=$words[$j];
                $info_obj['val']=0;
                    $str=$str." ".$w.", ";
                    } else {
                        // loop over the results
           $c=$num;
    // echo "-$c-";
      while($row=mysql_fetch_array($result)){
          //echo "OK $lim $c";
        $w=$row['lemma'];
        $val=$row['val'];
        
         $info_obj['lemma']=$row['lemma'];
         $info_obj['val']=$row['val'];
    }
        for ($i=0; $i<count($info_obj); $i++){
        if ($w==$word)
            //$w="<b><i>".$w."</i></b>";
            $w= "<font color='red'>".$w."</font>";   
        if ($val==2 || $val==3)
            //$w= "<font color='red'>".$w."</font>";    
            $w= "<b><i>".$w."</i></b>";    
        if ($c<=$limit){
             if($j<$c-1)
                $str=$str.$w.", ";
            else
                 $str=$str.$w;
            }
        if ($c>$limit){
             if($j<$c-1){
                
                $str=$str." ".$w.", ";
                }
            else
                 $str=$str." ".$w.", ... ";
            }
	}
                }
              
        }
        
        // exec 
        	

            return $str;

        
	
} else {
    return $ser_word;
    }

}


/*
returns the list of relations from the synsetid
table s: semlinks
table l: linktypes
table sy: synsets
table: wordsXsensesXsynsets
*/
function getRelsFromSynsetId($connection, $table_s,$table_l, $table_sy,$table, $param,$word){
        global $debug;
        $lists = array();
        $myquery = "SELECT s.synset1id as source, l.link as relation, s.synset2id as target, sy.definition as tdefinition, sy.pos as tpos, l.linkid as linkid FROM ". $table_s." s, ".$table_l. " l, ".$table_sy . " sy 
        where s.linkid=l.linkid and s.synset2id=sy.synsetid and s.synset1id = '$param' order by l.linkid;";
		mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $connection);

        if( $debug){
                echo "\tgetRelsFromSynsetId($connection , $table_s, $table_l, $param)</br>";
                echo "\tExecuting ". $myquery. "<br/>";
        }
        $result = mysql_query($myquery);
        if ($result==FALSE) {
            echo 'Invalid query: '.mysql_error();
            }
	
	
	
      while($row=mysql_fetch_array($result)){
		$info_obj = array();
		$info_obj['source'] = $row['source'];
        $info_obj['relation'] = $row['relation'];
         $info_obj['target'] = $row['target'];
         $sw=getSerializedWordsFromSynsetId($connection, $table,$row['target'],$word);
        $info_obj['sw'] = $sw;
          $info_obj['tdefinition'] = $row['tdefinition'];
           $info_obj['tpos'] = $row['tpos'];
             $info_obj['linkid'] = $row['linkid'];
		$lists[] = $info_obj;
	}

	//echo "XXXXXXXXXXXXXXXXXXXXX ".$res."</br>";
	return $lists;
}

/*
returns the list of relations from the synsetid, when the synsetid IS the target
table s: semlinks
table l: linktypes
table sy: synsets
table: wordsXsensesXsynsets
*/
function getTgtRelsFromSynsetId($connection, $table_s,$table_l, $table_sy,$table, $param,$word){
        global $debug;
        $lists = array();
        $myquery = "SELECT s.synset1id as source, l.link as relation, s.synset2id as target, sy.definition as tdefinition, sy.pos as tpos, l.linkid as linkid FROM ". $table_s." s, ".$table_l. " l, ".$table_sy . " sy 
        where s.linkid=l.linkid and s.synset1id=sy.synsetid and s.synset2id = '$param' order by l.linkid;";
		mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $connection);

        if( $debug){
                echo "\tgetTgtRelsFromSynsetId($connection , $table_s, $table_l, $param)</br>";
                echo "\tExecuting ". $myquery. "<br/>";
        }
        $result = mysql_query($myquery);
        if ($result==FALSE) {
            echo 'Invalid query: '.mysql_error();
            }
	
	
	
      while($row=mysql_fetch_array($result)){
		$info_obj = array();
		$info_obj['source'] = $row['source'];
        $info_obj['relation'] = $row['relation'];
         $info_obj['target'] = $row['target'];
         $sw=getSerializedWordsFromSynsetId($connection, $table,$row['source'],$word);
        $info_obj['sw'] = $sw;
          $info_obj['tdefinition'] = $row['tdefinition'];
           $info_obj['tpos'] = $row['tpos'];
             $info_obj['linkid'] = $row['linkid'];
		$lists[] = $info_obj;
	}

	//echo "XXXXXXXXXXXXXXXXXXXXX ".$res."</br>";
	return $lists;
}

/*
returns the list of target synsets mapped on $param
table: <lang>MAP
table_1: <lang>SynsetXsynsetMap
*/
function getListOfTargetSynsetId($connection, $table,$table_1,$param){
        global $debug;
        $lists = array();
        $myquery = "SELECT distinct s.synsetid_2 as mapped FROM ". $table." s  where s.synsetid_1 = '$param' ";
        $myquery_1= "SELECT distinct s.synsetid_2 as mapped FROM ". $table_1." s  where s.synsetid_1 = '$param'; ";
        $myquery = $myquery. " UNION ".$myquery_1;
        
		mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $connection);

        if( $debug){
                echo "\tgetListOfTargetSynsetId($connection , $table,  $param)</br>";
                echo "\tExecuting ". $myquery. "<br/>";
        }
        $result = mysql_query($myquery);
        if ($result==FALSE) {
            echo 'Invalid query: '.mysql_error();
            }
	
	
	
      while($row=mysql_fetch_array($result)){
		$info_obj = array();
		$info_obj['mapped'] = $row['mapped'];
		$lists[] = $info_obj;
	}

	//echo "XXXXXXXXXXXXXXXXXXXXX ".$res."</br>";
	return $lists;
}

/*
returns the list of target synsets mapped on the list of $param
table: <lang>MAP
*/
function getListOfTargetSynsetIdWithPivot($connection, $table,$table_1,$param){
        global $debug;
        $lists = array();
        $myquery = "SELECT distinct s.synsetid_2 as mapped FROM ". $table." s  where s.synsetid_1 in  $param ";
        $myquery_1= "SELECT distinct s.synsetid_2 as mapped FROM ". $table_1." s  where s.synsetid_1 in  $param; ";
        $myquery = $myquery. " UNION ".$myquery_1;
        
		mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $connection);

        if( $debug){
                echo "\tgetListOfTargetSynsetIdWithPivot($connection , $table,  $param)</br>";
                echo "\tExecuting ". $myquery. "<br/>";
        }
        $result = mysql_query($myquery);
        if ($result==FALSE) {
            echo 'Invalid query: '.mysql_error();
            }
	
	
	
      while($row=mysql_fetch_array($result)){
		$info_obj = array();
		$info_obj['mapped'] = $row['mapped'];
		$lists[] = $info_obj;
	}

	//echo "XXXXXXXXXXXXXXXXXXXXX ".$res."</br>";
	return $lists;
}

/*
returns the list of pivot synsets mapped on $param
table: according to languages
*/
function getListOfPivotSynsetId($connection, $table,$param){
        global $debug;
        $lists = array();
        $myquery = "SELECT distinct s.synset2id as pivot FROM ". $table." s  where s.synset1id = '$param' ;";
      /*  $myquery_1= "SELECT distinct s.synsetid_2 as mapped FROM ". $table_1." s  where s.synsetid_1 = '$param'; ";
        $myquery = $myquery. " UNION ".$myquery_1;
        */
        
		mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $connection);

        if( $debug){
                echo "\tgetListOfPivotSynsetId($connection , $table,  $param)</br>";
                echo "\tExecuting ". $myquery. "<br/>";
        }
        $result = mysql_query($myquery);
        if ($result==FALSE) {
            echo 'Invalid query: '.mysql_error();
            }
	
	
	
      while($row=mysql_fetch_array($result)){
		$info_obj = array();
		$info_obj['pivot'] = $row['pivot'];
   //     $info_obj['mapped'] = $row['pivot'];
		$lists[] = $info_obj;
	}

	//echo "XXXXXXXXXXXXXXXXXXXXX ".$res."</br>";
	return $lists;
}


/*
returns the list of features from the synsetid 
table s: <lang>Ws
table sy <lang>Map
*/
function getFeatsFromSynsetId($connection, $table_s,$table_sy, $feat, $param,$word){
        global $debug;
        $lists = array();
        $myquery="SELECT distinct s.word, s.synsetid, s.feat_att, s.feat_val FROM ". $table_s. " s, ".$table_sy. " sy  where sy.synsetid_2='$param' and feat_att='$feat' and word='$word' and sy.synsetid_1=s.synsetid;";
        mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $connection);

        if( $debug){
                echo "\tgetFeatsFromSynsetId($connection , $table_s, $feat, $param)</br>";
                echo "\tExecuting ". $myquery. "<br/>";
        }
        $result = mysql_query($myquery);
        if ($result==FALSE) {
            echo 'Invalid query: '.mysql_error();
            }
	
	
	
      while($row=mysql_fetch_array($result)){
		$info_obj = array();
		$info_obj['word'] = $row['word'];
        $info_obj['synsetid'] = $row['synsetid'];
         $info_obj['feat_att'] = $row['feat_att'];
          $info_obj['feat_val'] = $row['feat_val'];
		$lists[] = $info_obj;
	}

	//echo "XXXXXXXXXXXXXXXXXXXXX ".$res."</br>";
	return $lists;
}


?>