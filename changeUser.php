<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>User bearbeiten</title>

    <?php
    include('php/include.php');

    $errorPic = false;
    $errorReg = array();
    $exceptionPic;

    $user = get_user_by_ID($_SESSION['id']);

    if (isset($_FILES['pic'])){
        try {
            setUserPic($_FILES['pic'], $_SESSION['id']);
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

    if (isset($_POST['mail'])){
        if ($_POST['mail'] != ""){
            setUserMail($_POST['mail']);
            ?>
                <script type="text/javascript">
                    parent.$.fancybox.close();
                    parent.location.reload(true);
                </script>
            <?php
        } else {
            $errorReg['mail'] = "E-Mail-Addresse nicht korrekt<br />";
        }
    }
    if (isset($_POST['about'])){
        if (strlen($_POST['about']) <= 1000){
            setUserAbout($_POST['about']);
            ?>
            <script type="text/javascript">
                parent.$.fancybox.close();
                parent.location.reload(true);
            </script>
        <?php
        } else {
            $errorAbout = "Der Text darf höchstens 1000 Wörter lang sein";
        }

    }

    if (isset($_POST['oldPass'])){
        $errorReg = array();
        $errorReg = checkPWChange($_POST);
        if (!isset($errorReg['passOld']) && !isset($errorReg['passNew'])){
            setUserPass($_POST['newPass1']);
            ?>
            <script type="text/javascript">
                parent.$.fancybox.close();
                parent.location.reload(true);
            </script>
        <?php
        }
    }

    if (isset($_POST['passDel'])){
        $errorReg = array();
        $errorReg = checkPW($_POST['passDel']);
        if (!isset($errorReg['pass'])){
            deleteUserFromDB($_SESSION['id']);
            ?>
            <script type="text/javascript">
                parent.$.fancybox.close();
                parent.window.location.href = "php/logout.php";
            </script>
        <?php
        }
    }

    ?>
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
                    <script>document.write('<td><a id="abbut" onclick="parent.$.fancybox.close();">Abbrechen</a></td>');</script>
                    <noscript><td><a id="abbut" href="user.php?id=<?php echo htmlentities($_SESSION['id']); ?>">Abbrechen</a></td></noscript>
                </tr>
            </table>
        </form>
    <?php } ?>
    <?php if($_GET['ch']=="mail"){ ?>
        <h1>E-Mail ändern</h1>
        <form method="POST">
            <table>
                <tr>
                    <td class="eingabe">Neue Email:</td>
                    <td><input type="text" name="mail"<?php if(isset($errorReg['mail'])){ ?> style='background-color:#F00'<?php } ?> value="<?php echo htmlentities($user['email']); ?>" /></td>
                    <td><?php if(isset($errorReg['mail'])){ echo $errorReg['mail']; } ?></td>
                </tr>
                <tr>
                    <td><input id="submitButton" type="submit" value="Ändern" /></td>
                    <script>document.write('<td><a id="abbut" onclick="parent.$.fancybox.close();">Abbrechen</a></td>');</script>
                    <noscript><td><a id="abbut" href="user.php?id=<?php echo htmlentities($_SESSION['id']); ?>">Abbrechen</a></td></noscript>
                </tr>
            </table>
        </form>
    <?php } ?>
    <?php if($_GET['ch']=="about"){ ?>
        <h1>E-Mail ändern</h1>
        <form method="POST">
            <table>
                <tr>
                    <td class="eingabe">Über mich:</td>
                    <td>
                        <textarea rows="7" cols="40" name="about" <?php if(isset($errorAbout)){ ?> style='background-color:#F00'<?php } ?>><?php echo htmlentities($user['about']); ?></textarea>
                    </td>
                    <td><?php if(isset($errorAbout)){ echo $errorAbout; } ?></td>
                </tr>
                <tr>
                    <td><input id="submitButton" type="submit" value="Ändern" /></td>
                    <script>document.write('<td><a id="abbut" onclick="parent.$.fancybox.close();">Abbrechen</a></td>');</script>
                    <noscript><td><a id="abbut" href="user.php?id=<?php echo htmlentities($_SESSION['id']); ?>">Abbrechen</a></td></noscript>
                </tr>
            </table>
        </form>
    <?php } ?>
    <?php if($_GET['ch']=="pw"){ ?>
        <h1>Passwort ändern</h1>
        <form method="POST">
            <table>
                <tr>
                    <td class="eingabe">Altes Passwort:</td>
                    <td><input type="password" name="oldPass"<?php if(isset($errorReg['passOld'])){ ?> style='background-color:#F00'<?php } ?> /></td>
                    <td><?php if(isset($errorReg['passOld'])){ echo $errorReg['passOld']; } ?></td>
                </tr>
                <tr>
                    <td class="eingabe">Neues Passwort:</td>
                    <td><input type="password" name="newPass1"<?php if(isset($errorReg['passNew'])){ ?> style='background-color:#F00'<?php } ?> /></td>
                    <td><?php if(isset($errorReg['passNew'])){ echo $errorReg['passNew']; } ?></td>
                </tr>
                <tr>
                    <td class="eingabe">Neues Passwort wdh.:</td>
                    <td><input type="password" name="newPass2"<?php if(isset($errorReg['passNew'])){ ?> style='background-color:#F00'<?php } ?> /></td>
                </tr>
                <tr>
                    <td><input id="submitButton" type="submit" value="Ändern" /></td>
                    <script>document.write('<td><a id="abbut" onclick="parent.$.fancybox.close();">Abbrechen</a></td>');</script>
                    <noscript><td><a id="abbut" href="user.php?id=<?php echo htmlentities($_SESSION['id']); ?>">Abbrechen</a></td></noscript>
                </tr>
            </table>
        </form>
    <?php } ?>
    <?php if($_GET['ch']=="del"){ ?>
        <h1>Account löschen</h1>
        <form method="POST">
            Bitte gib dein Passwort ein, um das Löschen des Accounts zu bestätigen.
            <table>
                <tr>
                    <td class="eingabe">Passwort:</td>
                    <td><input type="password" name="passDel"<?php if(isset($errorReg['pass'])){ ?> style='background-color:#F00'<?php } ?> /></td>
                    <td><?php if(isset($errorReg['pass'])){ echo $errorReg['pass']; } ?></td>
                </tr>
                <tr>
                    <td><input id="submitButton" type="submit" value="Löschen" /></td>
                    <script>document.write('<td><a id="abbut" onclick="parent.$.fancybox.close();">Abbrechen</a></td>');</script>
                    <noscript><td><a id="abbut" href="user.php?id=<?php echo htmlentities($_SESSION['id']); ?>">Abbrechen</a></td></noscript>
                </tr>
            </table>
        </form>
    <?php } ?>
</div>
</body>
</html>