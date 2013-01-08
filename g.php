<?php
// Global file
// contains database info, constants, and necessary global functions
error_reporting(E_ALL ^ E_NOTICE); // Show all errors except notices

/** Constant Defines **/
define('ROOT_DIR', 'uploadsite'); // IMPORTANT! Change this to the root foldername of your Uploader script
define('URL', 'http://localhost/uploadsite'); // Root url of your site with no trailing slash, ex: http://www.mysite.com
define('NAME', 'Filehostr'); // Name of your site

/** Uploaded File Settings **/
define('UPLOAD_DIR', 'uploads'); // Directory where files are uploaded to, no slashes. **You must manually change this in the .htaccess file as well!**
define('UPLOAD_LOGFILE', 'upload_error_log'); // Filename of log to write uploading errors to
$allowedExtensions = array('png', 'jpg', 'bmp', 'jpeg', 'gif', 'txt'); // Allowed file extensions, DO NOT INCLUDE THE DOT (.)

define('MAX_FILESIZE', 5242880); // Max filesize limit in bytes, default is 5MB. You will need to change the upload_max_filesize setting in php.ini to match this at least
if(ini_get('upload_max_filesize') != '10M') // Change '10M' to the exact value you enter in ini_set below to save resources
	ini_set('upload_max_filesize', "10M"); // Sets upload_max_filesize in php.ini. Must be greater than or equal to MAX_FILESIZE constant above. M stands for megabytes, K for kilo, and G for gigabytes

define('MAX_INPUT_TIME', 60); // Time upload is allowed to take to complete, needs to be less than or equal to the setting in php.ini
if(intval(ini_get('max_input_time')) < 60) // Change 60 to the exact value of ini_set below to save resources
	ini_set('max_input_time', '60'); // Sets the max input time in seconds in php.ini. Must be greater than or equal to MAX_INPUT_TIME constant

define('POST_MAX_SIZE', 6291456); // This needs to be greater than or equal to MAX_FILESIZE, in bytes
if(ini_get('post_max_size') != '10M') // Change '10M' to the exact value in ini_set below to save resources
	ini_set('post_max_size', '10M'); // Needs to be greater than or equal to upload_max_filesize setting in php.ini

/** Database Info and Connection (uses MySQL with PDO) **/
$host = 'localhost'; // Your MySQL host
$dbname = 'uploader'; // Your MySQL database name
$dbuser = 'upladmin'; // Your MySQL database username
$dbpass = 'bPMLzq8uhn5bph8B'; // Your MySQL user password
$dblogfile = 'PDO_error_log'; // Filename that PDO errors are written to (.txt automatically appended)

// Don't mess with anything below this line unless you know what you're doing ---
try{
	$db = new PDO("mysql:host=$host;dbname=$dbname", $dbuser, $dbpass);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	if(!is_dir("logs")) // make logs directory if it doesn't exist already
		@mkdir("logs", 0500);
}
catch(PDOexception $e){
	echo "Database connection error.<br />";
	if(!is_dir("logs")) // make logs directory if it doesn't exist already
		@mkdir("logs", 0500);

	if(file_put_contents("logs/$dblogfile.txt", $e->getMessage() .' '. date("M-d-Y H:i:s") . PHP_EOL, FILE_APPEND))
		echo "Successfully logged error."
	else
		echo "Failed to write error to log.";
}


/** Globally Accessible Functions **/

/**
 * Generate a new filename for a user-submitted file
 *
 * Int $len: character length of generated filename
 * @return String the file name
 * @author fizz12
 **/
function GenerateFilename($len=8)
{
	$chars = '0987654321poiuytrewqlkjhgfdsamnbvcxz_QWERTYUIOPASDFGHJKLZXCVBNM';
	$return = '';

	for($i=0;$i<$len;$i++)
	{
		$return .= $chars[mt_rand(0, strlen($chars)-1)];
	}
	return $return;
}

/**
 * chmod all the files within a directory and the directory itself
 *
 * String $dir: name of directory to chmod (include slashes ONLY if further than one directory from root)
 * Int $perms: file permissions to change to (ex: 0600, 0777), default 0600 (owner read/write only, denied for everyone else)
 * @return 1 on success, 0 on failure
 * @author fizz12
 **/
function ChmodDirectory($dir, $perms=0600)
{
	$logfile = 'ChmodDirectory_error_log'; // Filename of error log for this function only
	#$dir = dirname(__FILE__).DIRECTORY_SEPARATOR.$dir;
	if(!is_dir($dir) || !is_numeric($perms)) // Make sure $dir is actually a directory and $perms is at least numeric
		return 0;
	else
	{
		if(!chmod($dir, $perms)) // chmod the directory itself
		{
			file_put_contents("logs/$logfile.txt", "Failed to chmod directory [$dir] to [$perms].". date("M-d-Y H:i:s") . PHP_EOL, FILE_APPEND);
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
	else
	{
		file_put_contents("logs/$logfile.txt", "Failed to open directory [Given: $dir].". date("M-d-Y H:i:s") . PHP_EOL, FILE_APPEND);
		return 0;
	}
}

/**
 * Get Username from given UID
 *
 * Integer $uid: The User ID we want to find the associated username of
 * @return String Username
 * @author fizz12
 **/
function UidToName($uid){}
?>