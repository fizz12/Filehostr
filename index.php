<?php
$pagetitle = 'Index';
require_once 'g.php';
require_once 'inc/header.php';
?>
<style type="text/css">
.upload {
	max-width: 400px;
	background-color: #fff;
	padding: 20px 0px 15px 0px;
	border: 1px solid #e5e5e5;
	margin: 30px auto 20px;
	text-align: center;

	-webkit-border-radius: 10px;
       -moz-border-radius: 10px;
            border-radius: 10px;
    -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
       -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
            box-shadow: 0 1px 2px rgba(0,0,0,.05);
}
.upload-form input[type="text"] {
        font-size: 14px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
}
.upload-heading {
	text-align: center;
}
.upload-browse {
	margin-bottom: 17px;
	margin-top: 2px;
	margin-left: 3px;
	padding: 5px;
}
</style>

<!-- Basic jQuery input checking... -->
<script type="text/javascript">
$("#upload-form").submit(function() {
	if($("#file").val().length<4) {
		alert("Please enter a file to be submitted.");
		return false;
	}else{
		return true;
	}
});
</script>
<div class="container">
	<div class="upload">
		<form class="upload-form" id="upload-form" action="upload.php" method="post" enctype="multipart/form-data">
			<h2 class="upload-heading">Upload to <?php echo NAME;?>!</h2><br />
			<input type="file" name="file" id="file" style="display:none">
			<input type="text" id="pretty-input" class="input-large"  placeholder="Upload a file..." onclick="$('input[id=file]').click();"><a class="btn upload-browse" onclick="$('input[id=file]').click();">Browse</a><br />
			<input type="submit" name="submitupload" value="Submit" class="btn btn-primary" />
		</form>
		<div id="login">
			<a href="#">Login</a> | <a href="#">Sign up</a>
		</div>
	</div>
</div>

<!-- Workaround for Boostrap's lack of a file input button (thanks http://duckranger.com/2012/06/pretty-file-input-field-in-bootstrap/) -->
<script type="text/javascript">
$('input[id=file]').change(function(){
	$('#pretty-input').val($(this).val());
});
</script>

<?php require_once 'inc/footer.php';?>