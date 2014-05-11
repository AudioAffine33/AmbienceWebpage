<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Upload</title>
<link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="css/foundation.css" />
<link rel="stylesheet" href="css/Haupseite.css" type="text/css" />
<script src="js/vendor/modernizr.js"></script>
    <?php
    include('php/include.php');

    $errorFile = false;

    if (isset($_FILES['userfile'])){
        try {
            $amb_id_act = upload_audio_inDB_onServer($_FILES['userfile'], $_SESSION['id']);
            $errorFile = false;
            header("Location: uploadDetails.php?id=".$amb_id_act);
        } catch (Exception $e){
            print_r($e);
            $errorFile = true;
        }
    }

    ?>
</head>
<div id="Content">
  	<?php include("header.php"); ?>

	<div id="Upload">
    
    	<div id="Dateiupload">
        <h1>Datei zum Upload:</h1>
        <form enctype="multipart/form-data" method="POST">
            <input type="hidden" name="MAX_FILE_SIZE" value="1000000000" />
            <input name="userfile" type="file" <?php if ($errorFile){ echo "style='background-color:#F00'";} ?> />
            <input type="submit" value="Weiter und Details angeben" />
        </form>
        <?php if ($errorFile){ echo "<br>Ungültige Datei";} ?>
        </div>
    
    </div>
    
</div>


<body>
</body>
</html>
