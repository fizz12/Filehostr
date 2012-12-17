<?php
// Global file
// contains database info, constants, and necessary global functions

// Database Info and Connection (uses MySQL with PDO)
$host = 'localhost'; // Your MySQL host
$dbname = 'uploader'; // Your MySQL database name
$dbuser = 'upladmin'; // Your MySQL database username
$dbpass = 'YLeUXwhQjT5HYJbG'; // Your MySQL user password
$logfile = 'PDO_error_log'; // Filename that PDO errors are written to (.txt automatically appended)
// Don't mess with anything below this line unless you know what you're doing ---
try{
	$db = new PDO("mysql:host=$host;dbname=$dbname", $dbuser, $dbpass);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOexception $e){
	echo "Database connection error.<br />";
	if(file_put_contents("logs/$logfile.txt", $e->getMessage() .' '. date("M-d-Y H:i:s") . PHP_EOL, FILE_APPEND))
		echo "Successfully logged error. Error is: ".$e->getMessage(); // *** REMOVE THE ERROR IS: PART AND DELETE THIS COMMENT FOR RELEASE ***
	else
		echo "Failed to write error to log.";
}
?>