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
            //print_r($e);
            $errorFile = true;
        }
    }

    ?>
<script type="text/javascript">
    $(document).ready( function(){
        $('input[type="file"]').change(function () {

            var file = $(this).val();
            var fileArray = file.split("\\");
            $("#Dateipfad").html(fileArray[fileArray.length-1]);
        });
    });

</script>
</head>
<div id="Content" class="row">
  	<?php include("header.php"); ?>

	<div id="Upload" class="columns">
        <h1>Datei zum Upload:</h1>
        <form enctype="multipart/form-data" method="POST">
            <input type="hidden" name="MAX_FILE_SIZE" value="1000000000" />
            <div id="userfile" class="columns large-3 medium-12">
                <input name="userfile" type="file" <?php if ($errorFile){ echo "style='background-color:#F00'";} ?> />
            </div>
          	<div class="columns large-4 medium-12"><h3>Audiodatei auswählen</h3></div>
            
            <div id="weiterbutton" class="columns large-3 medium-12 small-push-1"><input type="submit" value="Weiter und Details angeben" /></div>
            <div class="columns large-4 medium-12"><h3>Weiter und Details angeben</h3></div>
        </form>
        
        <?php if ($errorFile){ echo "<div id='ErrorFile' class='alert-box alert'>Ungültige Datei</div>";} ?>
        </div>
    
</div>


<body>
</body>
</html>
