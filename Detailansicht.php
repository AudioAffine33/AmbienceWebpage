<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Detailansicht</title>
<link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="css/foundation.css" />
<script src="js/vendor/modernizr.js"></script>
<link rel="stylesheet" href="css/Haupseite.css" type="text/css" />
</head>

<body>

<div id="Content">
  <div id="Header">
    <div id="LogoHeader">Logo</div>
    <div id="LoginHeader"> <a href="">
      <?php
                        if (!isset($_SESSION['name'])){
                    ?>
      Login
      <?php
                        } else {
                            echo $_SESSION['name'];
                        }
                    ?>
      </a> </div>
    <br>
  	<div id="SucheHeader">
    	<form method="GET">
        	<input type="hidden" name="limit" value="<?php echo $_GET['limit']; ?>" />
    		<input type="text" name="name" />
        </form>
   	</div>
  </div>
  <div id="ObereNavigation">
    <div id="Button1" class="ButtonNavigation">Ambiences</div>
    <div id="Button2" class="ButtonNavigation">FAQ</div>
    <div id="Button3" class="ButtonNavigation">Kontakt</div>
  </div>
  <div id="Detailansicht">
  	
    <div id="AmbienceBildBut">
    	
        <div id="BkwButton">Vorheriges</div>
  	
    	<div id="FwdButton">Nächstes</div>
  	
    </div>
    
    
    <div id="AmbienceBildGroß">
    
   
    
    </div>
  	
    
    <div id="AmbienceDescript">
    		<h1>Ambience</h1>
    		<ul>
            	<li>IT</li>
            	<li>44 Khz</li>
            	<li>WAV</li>
            </ul>
    </div>
    
  	<div id="AmbiencePlayer"> PLAYER </div>
    
  	<div id="BkwdtoHauptseitebtn">
    
    		<a href="overview.php"> zurück zur Übersicht </a>
    
    </div>
  
  
  
  
  
  
  
  
  
  </div>


</div>
</body>
</html>