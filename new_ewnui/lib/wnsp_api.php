<?php

require_once("./utils/tablelist.php");
require_once("./utils/vars.php");
/*
returns the details of a user
table: USER_TAB
*/
function getUserDetails($connection, $table,$user, $passwd){
        global $debug;
        $lists = array();
      
        $myquery = "SELECT nickname as name FROM $table WHERE username='$user' and md5(password)='$passwd';";
        //$myquery = "SELECT distinct synsetid, definition, pos FROM " . $table . " where wordidmd5 = md5('$word') ;";
          //$myquery = "SELECT distinct synsetid, definition, pos FROM " . $table . " LIMIT 10;";
		mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $connection);

        if( $debug){
                echo "\tgetUserDetails($connection , $table, $word)</br>";
                echo "\tExecuting ". $myquery. "<br/> ";
        }
        $result = mysql_query($myquery);
        if ($result==FALSE) {
            if ($debug)
                echo 'Invalid query: '.mysql_error();
             $ret ="SQL Error";
             //$ret =-1;
             return $ret;
            }
else {
// Mysql_num_row is counting table row
$count=mysql_num_rows($result);

// If result matched $myusername and $mypassword, table row must be 1 row

if($count==1){
    
     while($row=mysql_fetch_array($result)){
		$info_obj = array();
		$info_obj['name'] = $row['name'];
		$lists[] = $info_obj;
}
    $ret=$lists[0]['name'];

}
else {
 $ret ="Wrong Username or Password";
}
	return $ret;
}
}
/*
returns the list of languages a user can manage
table: USER_GRANT_TAB
*/
function getTargetlanguagesFromUser($connection, $table,$name,$username){
        global $debug;
        $lists = array();
        $myquery = "SELECT distinct target_language as t_lang FROM " . $table . " where name = '$name'  and username='$username';";
        //$myquery = "SELECT distinct synsetid, definition, pos FROM " . $table . " where wordidmd5 = md5('$word') ;";
          //$myquery = "SELECT distinct synsetid, definition, pos FROM " . $table . " LIMIT 10;";
		mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $connection);

        if( $debug){
                echo "\tgetTargetlanguagesFromUser($connection, $table,$name,$username)</br>";
                echo "\tExecuting ". $myquery. "<br/> ";
        }
        $result = mysql_query($myquery);
        if ($result==FALSE) {
            echo 'Invalid query: '.mysql_error();
            }
	
	
	
      while($row=mysql_fetch_array($result)){
		$info_obj = array();
		$info_obj['t_lang'] = $row['t_lang'];
		$lists[] = $info_obj;
	}

	//echo "XXXXXXXXXXXXXXXXXXXXX ".$res."</br>";
	return $lists;
}

/*
returns the list of languages a user can manage
table: USER_GRANT_TAB
*/
function getWordsList($connection, $table,$word){
        global $debug;
        $lists = array();
        $myquery = "SELECT distinct lemma as word FROM " . $table . " where lemma like  '$word%' order by 1 asc;";
        //$myquery = "SELECT distinct synsetid, definition, pos FROM " . $table . " where wordidmd5 = md5('$word') ;";
          //$myquery = "SELECT distinct synsetid, definition, pos FROM " . $table . " LIMIT 10;";
		mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $connection);

        if( $debug){
                echo "\tgetWordsList($connection, $table,$word)</br>";
                echo "\tExecuting ". $myquery. "<br/> ";
        }
        $result = mysql_query($myquery);
        if ($result==FALSE) {
            echo 'Invalid query: '.mysql_error();
            }
	
	
	
      while($row=mysql_fetch_array($result)){
		$info_obj = array();
		$info_obj['word'] = $row['word'];
		$lists[] = $info_obj;
	}

	//echo "XXXXXXXXXXXXXXXXXXXXX ".$res."</br>";
	return $lists;
}

/*
returns the list of relation
table: REL_TAB
*/
function getRelsList($connection, $table,$word){
        global $debug;
        $lists = array();
        $myquery = "SELECT distinct link as rel FROM " . $table . " where link like  '$word%' order by 1 asc;";
        //$myquery = "SELECT distinct synsetid, definition, pos FROM " . $table . " where wordidmd5 = md5('$word') ;";
          //$myquery = "SELECT distinct synsetid, definition, pos FROM " . $table . " LIMIT 10;";
		mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $connection);

        if( $debug){
                echo "\tgetWordsList($connection, $table,$word)</br>";
                echo "\tExecuting ". $myquery. "<br/> ";
        }
        $result = mysql_query($myquery);
        if ($result==FALSE) {
            echo 'Invalid query: '.mysql_error();
            }
	
	
	
      while($row=mysql_fetch_array($result)){
		$info_obj = array();
		$info_obj['rel'] = $row['rel'];
		$lists[] = $info_obj;
	}

	//echo "XXXXXXXXXXXXXXXXXXXXX ".$res."</br>";
	return $lists;
}

/*
returns the list of relation
table: POS_TAB
*/
function getPosList($connection, $table,$word){
        global $debug;
        $lists = array();
        $myquery = "SELECT distinct pos as pos FROM " . $table . " where  pos  like '$word%' order by 1 asc;";
        //$myquery = "SELECT distinct synsetid, definition, pos FROM " . $table . " where wordidmd5 = md5('$word') ;";
          //$myquery = "SELECT distinct synsetid, definition, pos FROM " . $table . " LIMIT 10;";
		mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $connection);

        if( $debug){
                echo "\tgetWordsList($connection, $table,$word)</br>";
                echo "\tExecuting ". $myquery. "<br/> ";
        }
        $result = mysql_query($myquery);
        if ($result==FALSE) {
            echo 'Invalid query: '.mysql_error();
            }
	
	
	
      while($row=mysql_fetch_array($result)){
		$info_obj = array();
		$info_obj['pos'] = $row['pos'];
		$lists[] = $info_obj;
	}

	//echo "XXXXXXXXXXXXXXXXXXXXX ".$res."</br>";
	return $lists;
}

function getSynsetListByDefinition($connection, $table,$word){
        global $debug;
        $lists = array();
        $myquery = "SELECT distinct synsetid as id, definition as def, pos as pos  FROM " . $table . " where  definition  like '$word%' order by 1 asc;";
        //$myquery = "SELECT distinct synsetid, definition, pos FROM " . $table . " where wordidmd5 = md5('$word') ;";
          //$myquery = "SELECT distinct synsetid, definition, pos FROM " . $table . " LIMIT 10;";
		mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $connection);

        if( $debug){
                echo "\tgetWordsList($connection, $table,$word)</br>";
                echo "\tExecuting ". $myquery. "<br/> ";
        }
        $result = mysql_query($myquery);
        if ($result==FALSE) {
            echo 'Invalid query: '.mysql_error();
            }
	
	
	
      while($row=mysql_fetch_array($result)){
		$info_obj = array();
		$info_obj['id'] = $row['id'];
		$info_obj['def'] = $row['def'];
		$info_obj['pos'] = $row['pos'];
		$lists[] = $info_obj;
	}

	//echo "XXXXXXXXXXXXXXXXXXXXX ".$res."</br>";
	return $lists;
}

function getSynsetListById($connection, $table,$word){
        global $debug;
        $lists = array();
        $myquery = "SELECT distinct synsetid as id, definition as def, pos as pos  FROM " . $table . " where  synsetid  like '$word%' order by 1 asc;";
        //$myquery = "SELECT distinct synsetid, definition, pos FROM " . $table . " where wordidmd5 = md5('$word') ;";
          //$myquery = "SELECT distinct synsetid, definition, pos FROM " . $table . " LIMIT 10;";
		mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $connection);

        if( $debug){
                echo "\tgetWordsList($connection, $table,$word)</br>";
                echo "\tExecuting ". $myquery. "<br/> ";
        }
        $result = mysql_query($myquery);
        if ($result==FALSE) {
            echo 'Invalid query: '.mysql_error();
            }
	
	
	
      while($row=mysql_fetch_array($result)){
		$info_obj = array();
		$info_obj['id'] = $row['id'];
		$info_obj['def'] = $row['def'];
		$info_obj['pos'] = $row['pos'];
		$lists[] = $info_obj;
	}

	//echo "XXXXXXXXXXXXXXXXXXXXX ".$res."</br>";
	return $lists;
}

/*
Insert records the list of languages a user can manage
table: USER_GRANT_TAB
*/
function doInsertRecordsIntoActivity($connection, $myquery){
        global $debug;
        $lists = array();
        //$myquery = "SELECT distinct synsetid, definition, pos FROM " . $table . " where wordidmd5 = md5('$word') ;";
          //$myquery = "SELECT distinct synsetid, definition, pos FROM " . $table . " LIMIT 10;";
		mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $connection);

        if( $debug){
                echo "\tdoInsertRecordsIntoActivity($connection, $myquery)</br>";
                echo "\tExecuting ". $myquery. "<br/> ";
        }
        $result = mysql_query($myquery);
        if ($result==FALSE) {
            echo 'Invalid query: '.mysql_error()."</br>";
            return -1;
            }
	    return 0;
	
}

function doInsertRecordsIntoActivitySrel($connection, $myquery){
        global $debug;
        $lists = array();
        //$myquery = "SELECT distinct synsetid, definition, pos FROM " . $table . " where wordidmd5 = md5('$word') ;";
          //$myquery = "SELECT distinct synsetid, definition, pos FROM " . $table . " LIMIT 10;";
		mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $connection);

        if( !$debug){
                echo "\tdoInsertRecordsIntoActivitySRel($connection, $myquery)</br>";
                echo "\tExecuting ". $myquery. "<br/> ";
        }
        $result = mysql_query($myquery);
        if ($result==FALSE) {
            echo 'Invalid query: '.mysql_error()."</br>";
            return -1;
            }
	    return 0;
	
}

function doInsertRecordsIntoActivityTrel($connection, $myquery){
        global $debug;
        $lists = array();
        //$myquery = "SELECT distinct synsetid, definition, pos FROM " . $table . " where wordidmd5 = md5('$word') ;";
          //$myquery = "SELECT distinct synsetid, definition, pos FROM " . $table . " LIMIT 10;";
		mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $connection);

        if( !$debug){
                echo "\tdoInsertRecordsIntoActivityTRel($connection, $myquery)</br>";
                echo "\tExecuting ". $myquery. "<br/> ";
        }
        $result = mysql_query($myquery);
        if ($result==FALSE) {
            echo 'Invalid query: '.mysql_error()."</br>";
            return -1;
            }
	    return 0;
	
}

/*
Insert user activity on synset, pos and gloss
table: $USER_SYN_TAB
*/
function doSaveChangesForGlossAndPosInSynset($connection, $myquery){
        global $debug;
        $lists = array();
        //$myquery = "SELECT distinct synsetid, definition, pos FROM " . $table . " where wordidmd5 = md5('$word') ;";
          //$myquery = "SELECT distinct synsetid, definition, pos FROM " . $table . " LIMIT 10;";
		mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $connection);

        if( $debug){
                echo "\tdoSaveChangesForGlossAndPosInSynset($connection, $myquery)</br>";
                echo "\tExecuting ". $myquery. "<br/> ";
        }
        $result = mysql_query($myquery);
        if ($result==FALSE) {
            echo 'Invalid query: '.mysql_error()."</br>";
            return "";
            }
	    return mysql_affected_rows();
	
}
/**
Insert a user in the logged table
**/
function doInsertUserIntoLogged($connection, $myquery){
        global $debug;
        $lists = array();
        //$myquery = "SELECT distinct synsetid, definition, pos FROM " . $table . " where wordidmd5 = md5('$word') ;";
          //$myquery = "SELECT distinct synsetid, definition, pos FROM " . $table . " LIMIT 10;";
		mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $connection);

        if( $debug){
                echo "\tdoInsertUserIntoLogged($connection, $myquery)</br>";
                echo "\tExecuting ". $myquery. "<br/> ";
        }
        $result = mysql_query($myquery);
        if ($result==FALSE) {
            echo 'Invalid query: '.mysql_error()."</br>";
            return -1;
            }
	    return 0;
	
}

/**
Deletes a user from the logged table
**/
function doDeleteUserFromLogged($connection, $myquery){
        global $debug;
        $lists = array();
        //$myquery = "SELECT distinct synsetid, definition, pos FROM " . $table . " where wordidmd5 = md5('$word') ;";
          //$myquery = "SELECT distinct synsetid, definition, pos FROM " . $table . " LIMIT 10;";
		mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $connection);

        if( $debug){
                echo "\tdoDeleteUserFromLogged($connection, $myquery)</br>";
                echo "\tExecuting ". $myquery. "<br/> ";
        }
        $result = mysql_query($myquery);
        if ($result==FALSE) {
            echo 'Invalid query: '.mysql_error()."</br>";
            return -1;
            }
	    return mysql_affected_rows();
	
}

/**
Deletes a user from the logged table
**/
function doIsUserLogged($connection, $myquery){
        global $debug;
        $lists = array();
        //$myquery = "SELECT distinct synsetid, definition, pos FROM " . $table . " where wordidmd5 = md5('$word') ;";
          //$myquery = "SELECT distinct synsetid, definition, pos FROM " . $table . " LIMIT 10;";
		mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $connection);

        if( $debug){
                echo "\tdoIsUserLogged($connection, $myquery)</br>";
                echo "\tExecuting ". $myquery. "<br/> ";
        }
        $result = mysql_query($myquery);
        if ($result==FALSE) {
            echo 'Invalid query: '.mysql_error()."</br>";
            return -1;
            }
	    return mysql_num_rows($result);
	
}

?>
