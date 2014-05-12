<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/Registrierung.css" />
<title>Registrierung</title>

	<?php
		include('php/include.php');

		require_once('php/recaptcha/recaptchalib.php');
		$publickey = "6Ld2fPISAAAAAJ_gnn-6FYe3xrndT79zYts3-XH2";
		$privatekey = "6Ld2fPISAAAAAJrM7PcZsHwqb8mvPcrAggomSnuu";

		$errorLog;
		$errorReg;
        $errorCap;

		if (isset($_POST['regName'])){
			$resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);


				if (!$resp->is_valid) {
                    $errorCap ="The reCAPTCHA wasn't entered correctly.";
				} else {
					$errorReg = addUser($_POST);
					if ($errorReg['new']){
				?>
                	<script type="text/javascript">
						//parent.$.fancybox.close();
					</script>
                <?php
                        header("Location: success.php?name=".$_POST['regName']);
					}
				}
		}
	?>
</head>

<body>
	<div id="registrierung">
    <h1>Registrierung</h1>
  	<div id="felder">
    <form method="POST">
        <table>
        	<tr>
            	<td class="eingabe">Name:</td>
            	<td><input type="text" name="regName" <?php if(isset($errorReg['name'])){ ?> style="background-color:#F00" <?php } ?> value="<?php if(isset($_POST['regName']) && !isset($errorReg['name'])){ echo $_POST['regName'];} ?>" /></td>
        	</tr>
            <tr>
                <td colspan="2" id="errorName">
                    <?php if(isset($errorReg['name'])){ echo $errorReg['name']; } ?>
                </td>
            </tr>
            <tr>
            	<td class="eingabe">Passwort:</td>
            	<td><input type="password" name="regPass1"<?php if(isset($errorReg['pass'])){ ?> style='background-color:#F00'<?php } ?> /></td>
        	</tr>
            <tr>
            	<td class="eingabe">Passwort wdh.:</td>
            	<td><input type="password" name="regPass2"<?php if(isset($errorReg['pass'])){ ?> style='background-color:#F00'<?php } ?> /></td>
        	</tr>
            <tr>
                <td colspan="2" id="errorPass">
                    <?php if(isset($errorReg['pass'])){ echo $errorReg['pass']; } ?>
                </td>
            </tr>
            <tr>
            	<td class="eingabe">Email:</td>
            	<td><input type="text" name="regMail"<?php if(isset($errorReg['mail'])){ ?> style='background-color:#F00'<?php } ?> value="<?php if(isset($_POST['regMail']) && !isset($errorReg['mail'])){ echo $_POST['regMail'];} ?>" /></td>
        	</tr>
            <tr>
                <td colspan="2" id="errorMail">
                    <?php if(isset($errorReg['mail'])){ echo $errorReg['mail']; } ?>
                </td>
            </tr>
            <tr>
                <td colspan="2" id="errorCaptcha">
                    <?php if (isset($errorCap)){ echo $errorCap;}?>
                </td>
            </tr>
            <tr>
            	<td colspan="2" class="eingabe">
                	<?php
					  echo recaptcha_get_html($publickey);
					?>
                </td>
            </tr>
            <tr>
            	<td id="regbut"><input type="submit" value="Registrieren" /></td>
                <td id="abbut" onclick="parent.jQuery.fancybox.close();"><img src="media/Design_Vorlagen/Registrierung/03_registrierung_button_abbrechen.png" /></td>
        	</tr>
        </table>
   	</form>
    </div>
    </div>
</body>
</html>