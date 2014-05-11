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

    $amb = get_ambience_by_ID($_GET['id']);
    $cats = get_categories();

    if (isset($_FILES['pic'])){
        $amb_id_act = $_POST['amb_id'];
        if ($_FILES['pic'] != ""){
            try {
                setPic($_FILES['pic'], $_POST['amb_id']);
                $errorPic = false;
            } catch (Exception $e){
                $errorPic = true;
            }

            $_POST['locLat'] = round($_POST['locLat'], 14);
            $_POST['locLng'] = round($_POST['locLng'], 14);

            if (check_detail_input($_POST)){
                set_ambience_details($_POST, $_POST['amb_id']);
            }
        }
        header('Location: overview.php');
        exit;

    }
    ?>
<script type="text/javascript" src="js/googleMapsUpload.js"></script>
</head>
<div id="Content">
  	<?php include("header.php"); ?>


	<div id="Upload">
        <form enctype="multipart/form-data" method="POST">
            <div id="UploadBild">
                Wählen Sie ein Bild aus
                <table>
                    <tr>
                        <input type="hidden" name="amb_id" value="<?php echo $_GET['id']; ?>" />
                        <input type="hidden" name="MAX_FILE_SIZE" value="1000000000" />
                        <td><input name="pic" type="file" <?php if ($errorPic){ echo "style='background-color:#F00'";} ?> /></td>
                    </tr>
                    <tr>
                        <td><?php if ($errorPic){ echo "Angegebene Datei hat das falsche Format";} ?></td>
                    </tr>
                </table>
            </div>

            <div id="UploadDaten">
                <table>
                    <tr>
                        <td>Name:</td>
                        <td><input type="text" name="name" value="<?php echo $amb['name']; ?>"/></td>
                    </tr>
                    <tr>
                        <td>Datum:</td>
                        <td><input type="date" name="date" value="<?php echo $amb['date']; ?>"/></td>
                    </tr>
                    <tr>
                        <td>Uhrzeit:</td>
                        <td><input type="Time" name="time" value="<?php echo $amb['time']; ?>"/></td>
                    </tr>
                    <tr>
                        <td>Ort:</td>
                        <td><input id="placeSearch" type="text" name="location" /></td>
                        <input id="locName" type="hidden" name="locName" value="" />
                        <input id="land" type="hidden" name="locLand" value="" />
                        <input id="countryCode" type="hidden" name="countryCode" value="" />
                        <input id="lat" type="hidden" name="locLat" value="" />
                        <input id="lng" type="hidden" name="locLng" value="" />
                    </tr>
                    <tr>
                    <td>Kategorie</td>
                    <td>
                        <select name="category">
                            <?php
                            foreach ($cats as $catid=>$category){
                                if ($category != ""){
                                    echo "<option value='".$catid."'>".$category."</option>";
                                }
                            }
                            ?>
                        </select>
                    </td>
                    </tr>
                    <tr>
                        <td>Beschreibung</td>
                        <td><textarea name="description" rows="5"><?php echo $amb['description']; ?></textarea></td>
                    </tr>
                </table>

                <div id="UploadButton">
                    <input type="submit" value="Send File" />
                </div>
            </div>
        </form>
    
    
    
    
    </div>

</div>

<body>
</body>
</html>
