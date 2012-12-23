<?php
// upload.php
// Processes file being uploaded
// Note: allowed file extensions, max file upload size, and other settings are found in g.php
$pagetitle = 'Upload';
require_once 'g.php';
require_once 'inc/header.php';

if(!is_dir(UPLOAD_DIR)) // Make sure upload directory exists and is writeable, otherwise create it
	mkdir(UPLOAD_DIR, 0500);
elseif(substr(sprintf("%o",fileperms(UPLOAD_DIR)),-4) != 0500)
	chmod(UPLOAD_DIR, 0500);

if($_POST['submitupload'] == 'Submit')
{
	$ext = explode(".", strtolower($_FILES["file"]["name"]));
	$ext = strtolower(end($ext));

	if((($_FILES["file"]["type"] == "image/gif")  /** These are allowed MIME types. Full list to add your own: http://www.iana.org/assignments/media-types/index.html **/
	|| ($_FILES["file"]["type"] == "image/jpeg")
	|| ($_FILES['file']['type'] == "text/plain")
	|| ($_FILES["file"]["type"] == "image/png")
	|| ($_FILES["file"]["type"] == "image/jpg"))
	&& ($_FILES["file"]["size"] < MAX_FILESIZE) // Make sure file is smaller than max allowed
	&& in_array($ext, $allowedExtensions)) // Make sure file has an allowed extension
	{
		if($_FILES['file']['error'] > 0) // Make sure there's no errors uploading
		{
			echo "Error: ". $_FILES['file']['error'];
			exit;
		}
		else // No errors, process file
		{
			echo "Upload: " . $_FILES["file"]["name"] . "<br>";
			echo "Type: " . $_FILES["file"]["type"] . "<br>";
			echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
			echo "Stored in: " . $_FILES["file"]["tmp_name"] . '<br />';
			$realname = $_FILES['file']['name']; // Store the actual user entered title of the file
			$newname = GenerateFilename() . ".$ext"; // Generate a new filename for this file and store it in this var
			// Now store file
			if(!file_exists(UPLOAD_DIR . DIRECTORY_SEPARATOR . $newname))
			{
				$newloc = dirname(__FILE__) . DIRECTORY_SEPARATOR . UPLOAD_DIR . DIRECTORY_SEPARATOR . $newname;
				if(@move_uploaded_file($_FILES['file']['tmp_name'], $newloc))
				{
					if(chmod($newloc, 0500))
						echo "File uploaded to: ".URL.'/'.UPLOAD_DIR."/$newname";
				}
				else
				{
					echo "Error uploading file.";
					if(file_put_contents("logs/".UPLOAD_LOGFILE.".txt", 'Failed to move uploaded file to uploads directory. File name: '. $_FILES['file']['name'] .' '. date("M-d-Y H:i:s") . PHP_EOL, FILE_APPEND))
						echo "Successfully logged error.";
					else
						echo "Failed to write error to log.";
				}
			}
			else
				echo "File already exists.";
		}
	}
	elseif (!in_array($ext, $allowedExtensions)) {
		echo "File type not allowed!";
	}
	elseif ($_FILES['file']['size'] > MAX_FILESIZE) {
		echo "File too big!";
	}
	else
		echo "File is broken!";
}
else
{
	echo 'No file submitted!';
}
require_once 'inc/footer.php';

/* TO DO:
* File upload progress bar!!!
* Make files go to uploads/userid/file.ext once user is logged in (if not logged in stay in uploads/file.ext)
* Membership system
* Viewing and downloading files
* Admin panel to view/delete files, view/modify/delete users
* Statistics to monitor how much people download, bandwidth usage, etc.
*/
?>