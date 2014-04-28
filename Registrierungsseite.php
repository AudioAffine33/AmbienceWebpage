<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Registrierung</title>
<link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="css/Haupseite.css" type="text/css" />
<?php
		include('php/include.php');
		
		if (!isset($_GET['limit'])){
			$_GET['limit']=10;
		}
		if (!isset($_GET['page'])){
			$_GET['page']=1;
		}
	?>
</head>

<body>

<div id="Content">
  <div id="Header">
    <div id="LogoHeader">Logo</div>
	</div>
    
   <div id="Registrierung">
   	<h1>Werde Teil von AmbienceWorld</h1>
    <div id="Userbild">
    
    </div>	
    <div id="Registrierungsformular">
    Benutzername:
    <br />
    Passwort:
    <br />
    Passwort bestätigen:
    <br />
    Email:
    
    
    </div>
 
   </div>

</div>

</body>
</html>