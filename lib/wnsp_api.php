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
      
        $myquery = "SELECT nickname as name FROM $table WHERE username='$user' and password='$passwd';";
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
?>
