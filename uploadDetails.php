<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Upload_Details</title>
<link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="css/foundation.css" />
<link rel="stylesheet" href="css/Haupseite.css" type="text/css" />
<script src="js/vendor/modernizr.js"></script>
    <?php
    include('php/include.php');

    $errorPic = false;
    $errorDetail = array();

    $amb = get_ambience_by_ID($_GET['id']);
    $cats = get_categories();

    if (isset($_FILES['pic'])){
        $amb_id_act = $_POST['amb_id'];
        print_r($_FILES);
        if ($_FILES['pic']['size'] != 0){
            try {
                setPic($_FILES['pic'], $_POST['amb_id']);
                $errorPic = false;
            } catch (Exception $e){
                $errorPic = true;
            }
        }
        $_POST['locLat'] = round($_POST['locLat'], 14);
        $_POST['locLng'] = round($_POST['locLng'], 14);

        $errorDetail = check_detail_input($_POST);

        if ($errorDetail['correct']){
            set_ambience_details($_POST, $_POST['amb_id']);
            header('Location: overview.php');
            exit;
        }
    }
    ?>
<script type="text/javascript" src="js/googleMapsUpload.js"></script>
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


	<div id="Upload" class="column">
        <form enctype="multipart/form-data" method="POST">
            <div id="UploadBild" class="columns large-12 medium-12">
                <table>
                    <tr>
                        <input type="hidden" name="amb_id" value="<?php echo $_GET['id']; ?>" />
                        <input type="hidden" name="MAX_FILE_SIZE" value="1000000000" />
                        <td id="AmbPicUpl"><input name="pic" type="file" <?php if ($errorPic){ echo "style='background-color:#F00'";} ?> /></td>
                        <td id="BiUpTe"> <h2>Bild auswählen</h2></td>

                    </tr>
                    <tr>
                        <td><?php if ($errorPic){ echo "Angegebene Datei hat das falsche Format";} ?></td>
                    </tr>
                </table>
            </div>

            <div id="UploadDaten" class="columns large-12 medium-12">
                <table>
                    <tr>
                        <td>Name:</td>
                        <td><input type="text" name="name" <?php if(isset($errorDetail['name'])){ ?> style="background-color:#F00" <?php } ?>  value="<?php if(isset($_POST['name']) && !isset($errorReg['name'])){ echo $_POST['name'];}  else {echo $amb['name'];} ?>"/></td>
                        <td><?php if (isset($errorDetail['name'])){ echo $errorDetail['name'];} ?></td>
                    </tr>
                    <tr>
                        <td>Datum:</td>
                        <td><input type="date" name="date" value="<?php if(isset($_POST['date'])){ echo $_POST['date'];}  else {echo $amb['date'];} ?>"/></td>
                    </tr>
                    <tr>
                        <td>Uhrzeit:</td>
                        <td><input type="Time" name="time" value="<?php if(isset($_POST['time'])){ echo $_POST['time'];}  else {echo $amb['time'];} ?>"/></td>
                    </tr>
                    <tr>
                        <td>Ort:</td>
                        <script> document.write(''
                        +'<td><input id="placeSearch" type="text" name="location" value="<?php if(isset($_POST['location'])){ echo $_POST['location'];}  ?>" /></td>'
                        +'<input id="locName" type="hidden" name="locName" value="<?php if(isset($_POST['locName'])){ echo $_POST['locName'];}  ?>" />'
                        +'<input id="land" type="hidden" name="locLand" value="<?php if(isset($_POST['locLand'])){ echo $_POST['locLand'];}  ?>" />'
                        +'<input id="countryCode" type="hidden" name="countryCode" value="<?php if(isset($_POST['countryCode'])){ echo $_POST['countryCode'];}  ?>" />'
                        +'<input id="lat" type="hidden" name="locLat" value="<?php if(isset($_POST['locLat'])){ echo $_POST['locLat'];}  ?>" />'
                        +'<input id="lng" type="hidden" name="locLng" value="<?php if(isset($_POST['locLng'])){ echo $_POST['locLng'];}  ?>" />)')
                        </script>
                        <noscript><td>Für die Ortsauswahl wird JavaScript benötigt</td></rd></noscript>
                    </tr>
                    <tr>
                    <td>Kategorie</td>
                    <td>
                        <select name="category">
                            <?php
                            foreach ($cats as $catid=>$category){
                                if ($category != ""){
                                    echo "<option value='".$catid."' ";
                                    if (isset($_POST['category']) && $_POST['category'] == $catid){
                                        echo "selected='selected'";
                                    }
                                    echo ">".$category."</option>";
                                }
                            }
                            ?>
                        </select>
                    </td>
                    </tr>
                    <tr>
                        <td>Beschreibung</td>
                        <td><textarea name="description" rows="5" <?php if(isset($errorDetail['descr'])){ ?> style="background-color:#F00" <?php } ?>><?php  if(isset($_POST['description']) && !isset($errorReg['descr'])){ echo $_POST['description'];}  else {echo $amb['description'];} ?></textarea></td>
                        <td><?php if (isset($errorDetail['descr'])){ echo $errorDetail['descr'];} ?></td>
                    </tr>
                </table>

                
            </div>
            <div id="UploadButton" class="columns large-12 medium-12 left">
                    <input type="submit" value="Send File" />
                </div>
        </form>
    
    
    
    
    </div>

</div>

<body>
</body>
</html>
