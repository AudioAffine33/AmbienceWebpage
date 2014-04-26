<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Unbenanntes Dokument</title>

	<?php
		include('php/include.php');
		
		require_once('php/recaptcha/recaptchalib.php');
		$publickey = "6Ld2fPISAAAAAJ_gnn-6FYe3xrndT79zYts3-XH2";
		$privatekey = "6Ld2fPISAAAAAJrM7PcZsHwqb8mvPcrAggomSnuu";
		
		$errorLog;
		$errorReg;
		
		if (isset($_POST['regName'])){
			$resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);
			
			
				if (!$resp->is_valid) {
					echo "The reCAPTCHA wasn't entered correctly. Go back and try it again." .
						 "(reCAPTCHA said: " . $resp->error . ")";
				} else {
					$errorReg = addUser($_POST);
					if ($errorReg['new']){
				?>
                	<script type="text/javascript">
						parent.$.fancybox.close();
					</script>
                <?php
					}
				}
		}
	?>
</head>

<body>
  	<form method="POST">
    	<table>
        	<tr>
            	<td>Name:</td>
            	<td><input type="text" name="regName" <?php if(isset($errorReg['name'])){ ?> style="background-color:#F00" <?php } ?> /></td>
                <td><?php if(isset($errorReg['name'])){ echo $errorReg['name']; } ?></td>
        	</tr>
            <tr>
            	<td>Passwort:</td>
            	<td><input type="password" name="regPass1"<?php if(isset($errorReg['mail'])){ ?> style='background-color:#F00'<?php } ?> /></td>
                <td><?php if(isset($errorReg['pass'])){ echo $errorReg['pass']; } ?></td>
        	</tr>
            <tr>
            	<td>Passwort wdh.:</td>
            	<td><input type="password" name="regPass2"<?php if(isset($errorReg['mail'])){ ?> style='background-color:#F00'<?php } ?> /></td>
        	</tr>
            <tr>
            	<td>Email:</td>
            	<td><input type="text" name="regMail"<?php if(isset($errorReg['mail'])){ ?> style='background-color:#F00'<?php } ?> /></td>
                <td><?php if(isset($errorReg['mail'])){ echo $errorReg['mail']; } ?></td>
        	</tr>
            <tr>
            	<td colspan="2">
                	<?php
					  echo recaptcha_get_html($publickey);
					?>
                </td>
            </tr>
            <tr>
            	<td colspan="2"><input type="submit" value="Registrieren" /></td>
        	</tr>
        </table>
	</form>   	
</body>
</html>