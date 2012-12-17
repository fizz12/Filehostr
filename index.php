<?php
require_once 'g.php';
$pagetitle = 'Uploader Index';
require_once 'inc/header.php';
?>
<div id="uploadform">
	<form id="upload" action="upload.php" method="post" enctype="multipart/form-data">
		Upload: <input type="file" name="file" id="file"><br />
		<input type="submit" name="submitupload" value="Submit" />
	</form>
</div>

<?php require_once 'inc/footer.php';?>