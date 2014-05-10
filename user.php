<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Userseite</title>
<link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="css/foundation.css" />
<link rel="stylesheet" href="css/Haupseite.css" type="text/css" />
<script src="js/vendor/modernizr.js"></script>
<?php
    include('php/include.php');
    if (isset($_GET['id']) and is_numeric($_GET['id'])){
        $user = getUser_by_ID($_GET['id']);

    } else {
        header("Location: overview.php");
    }
?>
</head>

<body>
<div id="Content">
  	<?php include("header.php"); ?>

	<div id="Benutzerprofil">
    	<h1><?php echo htmlentities($user['name']); ?><h1>
		<div id="Benutzerbild"><img src="media/Design_Vorlagen/Userseite/Userbild.png" /></div>
		<div id="Benutzerdetails"><?php echo htmlentities($user['about']); ?></div>

	</div>
    <div id="Uploadanzeige">
    	<h1>Uploads</h1>
        <div id="UploadALinks">
    		
            <div id="GoogleMaps"></div>
    		<div id="Uploaddetails"></div>
    	
        </div>
        
        <div id="UploadARechts">
        	<div id="AmbiencesUserAnzeige">

       		</div>
            <div id="NavigationUploads"></div>
    	</div>
    </div>
    
</div>
</body>
</html>
