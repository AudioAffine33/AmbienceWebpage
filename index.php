<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>AmbienceWebsite_Startseite</title>
	<link rel="stylesheet" href="css/foundation.css" />
    <script src="js/vendor/modernizr.js"></script>
	<link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="css/Startseite_style.css" type="text/css" />
    
    <script type="text/javascript" src="js/jquery-1.11.0.js"></script>
    <script type="text/javascript" src="js/fancyBox/source/jquery.fancybox.pack.js"></script>
    <link rel="stylesheet" href="js/fancyBox/source/jquery.fancybox.css" media="screen" />
    
    <?php
		include('php/include.php');
		
		$randPics = getRandAmb(8);
		
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

		<div id="TopLeft"><img src="media/pics_ambiences/<?php echo $randPics[0]['picture'] ?>" class="AmbienceFotoFade AmbienceFoto desaturate grey"/>
       
         </div>
    
    	<div id="TopCenter"><img src="media/pics_ambiences/<?php echo $randPics[1]['picture'] ?>" class="AmbienceFoto desaturate grey"/></div>
    
    	<div id="TopRight"><img src="media/pics_ambiences/<?php echo $randPics[2]['picture'] ?>" class="AmbienceFoto desaturate grey" /></div>
    
    </div>

	<div id="Center">
  
    	<div id="MidLeft"><img src="media/pics_ambiences/<?php echo $randPics[3]['picture'] ?>" class="AmbienceFoto desaturate grey" /></div>
    
   		<div id="LoginBox">
        	<div id="LoginTop"> 
        
       			<h1>Login</h1> 
   				<form method="POST">
                	<input type="text" name="loginName" <?php if(isset($errorLog['name'])){ ?> style="background-color:#F00" <?php } ?>/>
                    <input type="password" name="loginPass" <?php if(isset($errorLog['pass'])){ ?> style="background-color:#F00" <?php } ?>/>
                   <br /><div id="LoginBut"><input type="submit" value="Einloggen"/></div><br />
                </form>
              	 <div id="Reg"><a id="regFrame" data-fancybox-type="iframe" href="register.php" title="Registrieren">Neuer User</a> </div>
          	</div>
        
        	<div id="LoginBottom" href="overview.php">
             
          	<h1><a href="overview.php">Entdecke</a></h1>
            
            </div>
	</div>
    
   	 	<div id="MidRight"><img src="media/pics_ambiences/<?php echo $randPics[4]['picture'] ?>" class="AmbienceFoto desaturate grey" /></div>
   </div>

   	<div id="Bot">
    
  		<div id="BotLeft"><img src="media/pics_ambiences/<?php echo $randPics[5]['picture'] ?>" class="AmbienceFoto desaturate grey" /></div>
    
    	<div id="BotCenter" ><img src="media/pics_ambiences/<?php echo $randPics[6]['picture'] ?>" class="AmbienceFoto desaturate grey"/></div>
    
    	<div id="BotRight" ><img src="media/pics_ambiences/<?php echo $randPics[7]['picture'] ?>" class="AmbienceFoto desaturate grey" /></div>
	</div>

</div>
</body>

</html>
