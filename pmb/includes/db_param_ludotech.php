<?php

if(preg_match('/db_param\.inc\.php/', $_SERVER['REQUEST_URI'])) {
	include('./forbidden.inc.php'); forbidden();
	}

function db_ludotech_connect($database,$user,$pass,$sql_server, &$connection = null){
    mysql_close();
    $conn = mysql_connect($sql_server,$user,$pass);
    $res = 0;
    if($conn !== FALSE) {
    	$dbSelected = mysql_select_db($database);
    	if ($dbSelected !== FALSE) $res = 1;
    }
        
    if ($connection != null) $connection = $conn;
    return $res;
};

function db_ludotech_close(){
    mysql_close();
};

?>
