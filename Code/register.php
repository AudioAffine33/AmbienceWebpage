<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/Registrierung.css" />
<link rel="stylesheet" href="css/foundation.css" />
<title>Registrierung</title>

	<?php
		include('php/include.php');

		$errorLog;
		$errorReg;
        $errorCap;

		if (isset($_POST['regName'])){
                if(isset($_SESSION['captcha_spam']) &&  $_POST["captcha"] == $_SESSION['captcha_spam']){
                    unset($_SESSION['captcha_spam']);
                    $errorReg = addUser($_POST);
                    if ($errorReg['new']){
                        ?>
                            <script type="text/javascript">
                                //parent.$.fancybox.close();
                            </script>
                        <?php
                        header("Location: success.php?name=".$_POST['regName']);
                    }
				} else {
                    $errorCap ="The reCAPTCHA wasn't entered correctly.";
				}
		}
	?>
</head>

<body>
	<div id="registrierung" class="row">
    <div class="column text-center"><h1>Registrierung</h1></div>
  	<div class="row">
    <form method="POST">
		<div class="row">
            	<div id="RegInput1" class="two columns small-push-1 RegInput"><input type="text" placeholder="Name" name="regName" <?php if(isset($errorReg['name'])){ ?> style="background-color:#F00" <?php } ?> value="<?php if(isset($_POST['regName']) && !isset($errorReg['name'])){ echo $_POST['regName'];} ?>" /></div>

                    <?php if(isset($errorReg['name'])){ ?><div id="ErrorRegN" class="column alert-box alert small-push-1"><?php echo $errorReg['name'];?></div><?php } ?>
                </div>
                <div class="row">
            	<div id="RegInput2" class="two columns small-push-1 RegInput"><input type="password" placeholder="Passwort" name="regPass1"<?php if(isset($errorReg['pass'])){ ?> style='background-color:#F00'<?php } ?> />
                </div>
                </div>
                <div class="row">
            	<div id="RegInput3" class="two columns small-push-1 RegInput"><input type="password" placeholder="Passwort wiederholen" name="regPass2"<?php if(isset($errorReg['pass'])){ ?> style='background-color:#F00'<?php } ?> />	</div>
    			</div>

                    <?php if(isset($errorReg['pass'])){?><div class="small-push-1 column alert-box alert"><?php echo $errorReg['pass']; ?></div><?php } ?>
                <div class="row">
            	<div id="RegInput4" class="two columns small-push-1 RegInput"><input type="text" placeholder="E-Mail" name="regMail"<?php if(isset($errorReg['mail'])){ ?> style='background-color:#F00'<?php } ?> value="<?php if(isset($_POST['regMail']) && !isset($errorReg['mail'])){ echo $_POST['regMail'];} ?>" />
                </div>
        		</div>
                <?php if(isset($errorReg['mail'])){?><div class=" column alert-box alert small-push-1"><?php echo $errorReg['mail']; ?></div><?php } ?>

                <?php if (isset($errorCap)){?><div class="small-push-1 column alert-box alert"><?php echo $errorCap; ?></div><?php }?>
                <div id="Eingabe" class="three columns text-center">
                    <img src="php/captcha/captcha.php" title="Sicherheitscode">
                    <input type="text" name="captcha">
                </div>
                <div id="row">
            	<div id="regbut" class="two columns small-push-1"><input type="submit" value="Registrieren" /></div>
                <div id="abbut"  class="two columns right small-pull-1" onclick="parent.jQuery.fancybox.close();"><img src="media/Design_Vorlagen/Registrierung/03_registrierung_button_abbrechen.png" /></div>
                </div>
   	</form>
    </div>
    </div>
</body>
</html>