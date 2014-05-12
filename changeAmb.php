<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>User bearbeiten</title>

    <?php
    include('php/include.php');

    $errorPic = false;
    $errorReg = array();

    $amb = get_ambience_by_ID($_GET['id']);
    $cats = get_categories();

    if (isset($_FILES['pic'])){
        try {
            setPic($_FILES['pic'], $_GET['id']);
            $errorPic = false;
            ?>
            <script type="text/javascript">
                parent.$.fancybox.close();
                parent.location.reload(true);
            </script>
        <?php
        } catch (Exception $e){
            $errorPic = true;
        }
    }

    if (isset($_POST['date'])){
        update_amb($_GET['id'], $_POST);
        ?>
        <script type="text/javascript">
            parent.$.fancybox.close();
            parent.location.reload(true);
        </script>
        <?php
    }

    if (isset($_POST['date']) || isset($_POST['category']) || isset($_POST['locName'])){
        update_amb($_GET['id'], $_POST);
        ?>
        <script type="text/javascript">
            parent.$.fancybox.close();
            parent.location.reload(true);
        </script>
    <?php
    }

    if (isset($_POST['description'])){
        if (strlen($_POST['description']) < 500){
            update_amb($_GET['id'], $_POST);
            ?>
            <script type="text/javascript">
                parent.$.fancybox.close();
                parent.location.reload(true);
            </script>
        <?php
        } else {
            $errorDescr = "Die Beschreibung darf höchstens 500 Zeichen lang sein.";
        }

    }

    if (isset($_POST['passDel'])){
        $errorReg = array();
        $errorReg = checkPW($_POST['passDel']);
        if (!isset($errorReg['pass'])){
            delete_audio_fromDB_andServer($_GET['id']);
            ?>
            <script type="text/javascript">
                parent.$.fancybox.close();
                parent.window.location.href = "overview.php";
            </script>
        <?php
        }
    }

    ?>

<script type="text/javascript" src="js/googleMapsUpload.js"></script>
</head>

<body>
<div id="aenderung">
    <?php if($_GET['ch']=="pic"){ ?>
        <h1>Bild ändern</h1>
        <form enctype="multipart/form-data" method="POST">
            <table>
                <input type="hidden" name="MAX_FILE_SIZE" value="1000000000" />
                <tr>
                    <td>Bild auswählen</td>
                    <td><input name="pic" type="file" <?php if ($errorPic){ echo "style='background-color:#F00'";} ?> /></td>
                </tr>
                <tr>
                    <td><input id="submitButton" type="submit" value="Ändern" /></td>
                    <td><a id="abbut" onclick="parent.$.fancybox.close();">Abbrechen</a></td>
                </tr>
            </table>
        </form>
    <?php } ?>
    <?php if($_GET['ch']=="datetime"){ ?>
        <h1>Datum/Uhrzeit ändern</h1>
        <form method="POST">
            <table>
                <tr>
                    <td class="eingabe">Datum:</td>
                    <td><input type="date" name="date" value="<?php echo $amb['date']; ?>"/></td>
                </tr>
                <tr>
                    <td class="eingabe">Uhrzeit:</td>
                    <td><input type="time" name="time" value="<?php echo $amb['time']; ?>"/></td>
                </tr>
                <tr>
                    <td><input id="submitButton" type="submit" value="Ändern" /></td>
                    <td><a id="abbut" onclick="parent.$.fancybox.close();">Abbrechen</a></td>
                </tr>
            </table>
        </form>
    <?php } ?>
    <?php if($_GET['ch']=="cat"){ ?>
        <h1>Kategorie ändern</h1>
        <form method="POST">
            <table>
                <tr>
                    <td class="eingabe">Kategorie:</td>
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
                    <td><input id="submitButton" type="submit" value="Ändern" /></td>
                    <td><a id="abbut" onclick="parent.$.fancybox.close();">Abbrechen</a></td>
                </tr>
            </table>

        </form>
    <?php } ?>

    <?php if($_GET['ch']=="descr"){ ?>
        <h1>Beschreibung ändern</h1>
        <form method="POST">
            <table>
                <tr>
                    <td class="eingabe">Beschreibung:</td>
                    <td><textarea name="description" rows="5" <?php if(isset($errorDescr)){ ?> style='background-color:#F00'<?php } ?>><?php echo $amb['description']; ?></textarea></td>
                    <td><?php if(isset($errorDescr)){ echo $errorDescr; } ?></td>
                </tr>
                <tr>
                    <td><input id="submitButton" type="submit" value="Ändern" /></td>
                    <td><a id="abbut" onclick="parent.$.fancybox.close();">Abbrechen</a></td>
                </tr>
            </table>

        </form>
    <?php } ?>

    <?php if($_GET['ch']=="loc"){ ?>
        <h1>Ort ändern</h1>
        <form method="POST">
            <table>
                <tr>
                    <td class="eingabe">Ort:</td>
                    <td><input id="placeSearch" type="text" name="location" /></td>
                    <input id="locName" type="hidden" name="locName" value="" />
                    <input id="land" type="hidden" name="locLand" value="" />
                    <input id="countryCode" type="hidden" name="countryCode" value="" />
                    <input id="lat" type="hidden" name="locLat" value="" />
                    <input id="lng" type="hidden" name="locLng" value="" />
                </tr>
                <tr>
                    <td><input id="submitButton" type="submit" value="Ändern" /></td>
                    <td><a id="abbut" onclick="parent.$.fancybox.close();">Abbrechen</a></td>
                </tr>
            </table>

        </form>
    <?php } ?>

    <?php if($_GET['ch']=="del"){ ?>
        <h1>Ambience löschen</h1>
        <form method="POST">
            Bitte gib dein Passwort ein, um das Löschen der Ambience zu bestätigen.
            <table>
                <tr>
                    <td class="eingabe">Passwort:</td>
                    <td><input type="password" name="passDel"<?php if(isset($errorReg['pass'])){ ?> style='background-color:#F00'<?php } ?> /></td>
                    <td><?php if(isset($errorReg['pass'])){ echo $errorReg['pass']; } ?></td>
                </tr>
                <tr>
                    <td><input id="submitButton" type="submit" value="Löschen" /></td>
                    <td><a id="abbut" onclick="parent.$.fancybox.close();">Abbrechen</a></td>
                </tr>
            </table>
        </form>
    <?php } ?>
</div>
</body>
</html>