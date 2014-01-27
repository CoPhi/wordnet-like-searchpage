<?php
$mode=$_POST['mode'];

function getSession(){
 session_start();
  echo $_SESSION['user'];
  //echo "stica";
}




switch($mode){
	case 'get':	
		getSession();
    break;
    }
?>