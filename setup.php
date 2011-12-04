<?php
//set stats for queries
$CONFIG->mysql_queries_count = 0;
$CONFIG->mysql_queries_time = 0;
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;

require_once $CONFIG->homePath."lib/general.php";
require_once $CONFIG->homePath."data/define.php";
require_once $CONFIG->homePath."data/api.php";
require_once $CONFIG->homePath."lib/paramlib.php";
require_once $CONFIG->homePath."lib/accesslib.php";
require_once $CONFIG->homePath."lib/i8nlib.php";
require_once $CONFIG->homePath."lib/loglib.php";
require_once $CONFIG->homePath."lib/mdetect.php";

require_once $CONFIG->homePath."lib/user.class.php";

unset($API);
global $API;
$API = new API();

//start session
startSession();
    
unset($USER);
global $USER;

unset($HEADER);
global $HEADER;

unset($PAGE);
global $PAGE;

unset($MSG);
global $MSG;
$MSG = array();

if (isset($_SESSION["session_username"])){
	$USER = new User($_SESSION["session_username"]);
} else {
	$USER = new User("");
}

