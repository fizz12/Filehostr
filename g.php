<?php
// Global file
// contains database info, constants, and necessary global functions

/** Constant Defines **/
define('ROOTDIR', 'uploadsite'); // IMPORTANT! Change this to the root foldername of your Uploader script
define('NAME', 'Upl0ader'); // Name of your site

/** Database Info and Connection (uses MySQL with PDO) **/
$host = 'localhost'; // Your MySQL host
$dbname = 'uploader'; // Your MySQL database name
$dbuser = 'upladmin'; // Your MySQL database username
$dbpass = 'YLeUXwhQjT5HYJbG'; // Your MySQL user password
$dblogfile = 'PDO_error_log'; // Filename that PDO errors are written to (.txt automatically appended)
/*if(is_dir('logs'))
	chmodDirectory('logs', 0600);
elseif(substr(sprintf("%o",fileperms("logs")),-4) != '0600')
	chmod('logs', 0600);*/
// Don't mess with anything below this line unless you know what you're doing ---
try{
	$db = new PDO("mysql:host=$host;dbname=$dbname", $dbuser, $dbpass);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOexception $e){
	echo "Database connection error.<br />";
	if(!is_dir("logs")) // make logs directory if it doesn't exist already
		mkdir("logs", 0600);

	if(file_put_contents("logs/$dblogfile.txt", $e->getMessage() .' '. date("M-d-Y H:i:s") . PHP_EOL, FILE_APPEND))
		echo "Successfully logged error. Error is: ".$e->getMessage(); // *** REMOVE THE ERROR IS: PART AND DELETE THIS COMMENT FOR RELEASE ***
	else
		echo "Failed to write error to log.";
}


/** Globally Accessible Functions **/

/**
 * chmod all the files within a directory and the directory itself
 *
 * String $dir: name of directory to chmod (include slashes if further than one directory from root)
 * Int $perms: file permissions to change to (ex: 0600, 0777), default 0600 (owner read/write only, denied for everyone else)
 * @return 1 on success, 0 on failure
 * @author fizz12
 **/
function chmodDirectory($dir, $perms=0600)
{
	$logfile = 'chmodDirectory_error_log'; // Filename of error log for this function only
	#$dir = dirname(__FILE__).DIRECTORY_SEPARATOR.$dir;
	if(!is_dir($dir) || !is_numeric($perms)) // Make sure $dir is actually a directory and $perms is at least numeric
		return;
	else
	{
		if(!chmod($dir, $perms)) // chmod the directory itself
		{
			file_put_contents("logs/$logfile.txt", "Failed to chmod directory: $dir ". date("M-d-Y H:i:s") . PHP_EOL, FILE_APPEND);
			return 0;
		}
	}

	if($h = opendir($dir))
	{
		while(false !== ($entry = readdir($h))) // Now iterate through each file in $dir and chmod them
		{
			if($entry != '.' && $entry != '..' && $entry != basename($_SERVER['PHP_SELF']))
			{
				#chmod($entry, $perms);
				if(!chmod($dir.DIRECTORY_SEPARATOR.$entry, $perms))
				{
					file_put_contents("logs/$logfile.txt", "Failed to chmod: [$entry] to [$perms].". date("M-d-Y H:i:s") . PHP_EOL, FILE_APPEND);
					return 0;
				}
			}
		}

		closedir($h);
		echo "Success!";
		return 1;
	}
}
?>