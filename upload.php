<?php
// In PHP versions earlier than 4.1.0, $HTTP_POST_FILES should be used instead
// of $_FILES.
if (!empty($_POST)) {
$uploaddir = realpath(dirname(__FILE__)) . '/files/';
$uploadconfig = realpath(dirname(__FILE__)) .  '/config';
$uploadfile = $uploaddir . basename($_FILES['file']['name']);

echo '<pre>';
if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
	echo "File is valid, and was successfully uploaded.\n";
	echo exec("cd {$uploadconfig} && swish-e -c swish.conf", $return);
} else {
	echo "Possible file upload attack!\n";
}

echo 'Here is some more debugging info:';
print_r($_FILES);

print "</pre>";
}

?>

<!DOCTYPE html>
<html>
  <head>
    <title>Swish-E Example</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
  </head>
  <body>
        <div class="container-fluid">
                <div class="row-fluid">
                        <div class="span2">

                        </div>
                        <div class="span10">
                                <h1>Swish-E /</strong> <small><a href="index.php">Search file base</a></small></h1>
								<form action="upload.php" method="post" enctype="multipart/form-data">
                                        <fieldset>
                                                <legend>Upload a file</legend>
                                                <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
                                                <input type="file" name="file" id="file">
                                                <span class="help-block">Supported formats: pdf, doc, docx, txt, rtf</span>
                                                <button type="submit" class="btn">Submit</button>
                                        </fieldset>
                                </form>
                        </div>
                </div>
        </div>
        <script src="http://code.jquery.com/jquery.js"></script>
        <script src="js/bootstrap.min.js"></script>
  </body>
</html>
