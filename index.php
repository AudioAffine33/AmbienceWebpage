<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>AmbienceWebsite_Startseite</title>
	
	<link rel="stylesheet" href="Startseite_style.css" type="text/css" />
    
    <script type="text/javascript" src="js/jquery-1.11.0.js"></script>
    <script type="text/javascript" src="js/fancyBox/source/jquery.fancybox.pack.js"></script>
    <link rel="stylesheet" href="js/fancyBox/source/jquery.fancybox.css" media="screen" />
    
    <?php
		include('php/include.php');
		
		$errorLog;
		
		if (isset($_POST['loginName'])){
			$errorLog = login($_POST);
			
			
			if($errorLog['correct']){
				header('Location: overview.php'); 
				exit;
			}
		}
	?>	
    
    <script type="text/javascript">
		$(document).ready(function() {
			$("#regFrame").fancybox();
		});
	</script>
</head>

<body>
<div id="Content">

	<div id="Top">

		<div id="TopLeft"><img src="Testbilder/533046l.jpg" class="AmbienceFoto desaturate grey"/></div>
    
    	<div id="TopCenter"><img src="Testbilder/dsc_9869axhaf.jpg" class="AmbienceFoto desaturate grey"/></div>
    
    	<div id="TopRight"><img src="Testbilder/DSC00904.jpg" class="AmbienceFoto desaturate grey" /></div>
    
    </div>

	<div id="Center">
  
    	<div id="MidLeft"><img src="Testbilder/dscn1776.jpg" class="AmbienceFoto desaturate grey" /></div>
    
   		<div id="LoginBox">
        	<div id="LoginTop"> 
        
       			<h1>Login</h1> 
   				<form method="POST">
                	<input type="text" name="loginName" <?php if(isset($errorLog['name'])){ ?> style="background-color:#F00" <?php } ?>/>
                    <input type="password" name="loginPass" <?php if(isset($errorLog['pass'])){ ?> style="background-color:#F00" <?php } ?>/>
                    <input type="submit" value="Einloggen"/><br />
                </form>
              	 <a id="regFrame" data-fancybox-type="iframe" href="register.php" title="Registrieren">Neuer User</a> 
          	</div>
        
        	<div id="LoginBottom" href="overview.php">
             
            	<h1><a href="overview.php">Entdecke</a></h1>
            
            </div>
	</div>
    
   	 	<div id="MidRight"><img src="Testbilder/hollywood-walk-of-fame-100.jpg" class="AmbienceFoto desaturate grey" /></div>
   </div>
 

   	<div id="Bot">
    
  		<div id="BotLeft"><img src="Testbilder/lanschaftsbilder004.jpg" class="AmbienceFoto desaturate grey" /></div>
    
    	<div id="BotCenter" ><img src="Testbilder/New_york_times_square-terabass.jpg" class="AmbienceFoto desaturate grey"/></div>
    
    	<div id="BotRight" ><img src="Testbilder/Tokyo_street.jpg" class="AmbienceFoto desaturate grey" /></div>
	</div>

</div>
</body>

</html>
