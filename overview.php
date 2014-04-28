<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Hauptseite_uneingeloggt</title>
<link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="css/foundation.css" />
<script src="js/vendor/modernizr.js"></script>
<link rel="stylesheet" href="css/Haupseite.css" type="text/css" />
<?php
		include('php/include.php');
		

		if (!isset($_GET['limit']) || !is_numeric($_GET['limit'])){
			$_GET['limit']=10;
            $_GET['page']=1;
		}
		if (!isset($_GET['page']) || !is_numeric($_GET['page'])){
			$_GET['page']=1;
		}
	?>
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
  </div>
  
  <div id="ObereNavigation">
    <div id="Button1" class="ButtonNavigation">Ambiences</div>
    <div id="Button2" class="ButtonNavigation">FAQ</div>
    <div id="Button3" class="ButtonNavigation">Kontakt</div>
  </div>
  
  <div id="SucheHeader">
  
  		<div id="Suche">
    	<form method="GET">
        	<input type="hidden" name="limit" value="<?php echo $_GET['limit']; ?>" />
    		<input type="text" name="name" />
        </form>
        </div>
   	</div>
  
  <div id="LinkeNavigation">
    <div id="LinkeNavKat">
      <ul>
        <li class="OberKat">
        	<a href="#">Orte</a>
            <ul>
            	<li class="Unterpunkt"><a href="">Europa</a></li>
                <li class="Unterpunkt"><a href="">Asien</a></li>
                <li class="Unterpunkt"><a href="">Afrika</a></li>
                <li class="Unterpunkt"><a href="">Amerika</a></li>
        
        	</ul>
        </li>
        <li class="OberKat">
        	<a href="#">Dauer</a>
        	<ul>
        		<li class="Unterpunkt"><a href=""> < 0:30 Min </a></li>
        		<li class="Unterpunkt"><a href=""> 30sec - 1 Min</a></li>
        		<li class="Unterpunkt"><a href=""> 1min - 1:30 Min</a></li>
                <li class="Unterpunkt"><a href=""> 1:30 Min - 2 Min</a></li>
            </ul>
       	</li>
        
        <li class="OberKat">
        	<a href="#">Qualität</a>
            <ul>
            	<li class="OberKat"><a href="#">bit</a>
                	<ul>
                    	<li class="Unterpunkt"><a href="">4-8 bit</a></li>
                    	<li class="Unterpunkt"><a href="">8-16 bit</a></li>
                    </ul>
                </li>
               
               	<li class="OberKat"><a href="#">Kilohertz</a>
        			<ul>
                		<li class="Unterpunkt"><a href="">8Khz</a></li>
                    	<li class="Unterpunkt"><a href="">16Khz</a></li>
                		<li class="Unterpunkt"><a href="">44.1Khz</a></li>
                    	<li class="Unterpunkt"><a href="">48Khz</a></li>
                        <li class="Unterpunkt"><a href="">96Khz</a></li>
                	</ul>
                </li>
        	</ul>
      	</li>

      </ul>
    </div>
  </div>
  <div id="AmbiencesAnzeige">
      <?php
		$query = createSearch($_GET);
        $query->execute();
		//$found = $query->rowCount();
		//$index = 0;
		while ($row = $query->fetch()){
			$locationArray = getLocation_by_ID($row['location_id']);
			$format_act = getFormat_by_ID($row['format_id']);
				?>
      <div class="Ambiences">
       		<img src="media/pics_ambiences/thumb/<?php echo htmlentities($row['picture']);  ?>" class="AmbiencePic" />
      	<div class="AmbienceDescription">
        	<h1><?php echo htmlentities($row['name']); ?></h1>
          		<ul>
            		<li>
           	  			<?php if (isset($locationArray['name'])){echo $locationArray['name']; } ?>
            		</li>
            <li><?php echo date("G:i", strtotime($row['time'])) ?></li>
            <li><?php echo htmlentities($format_act['bitdepth'])." bit , ".htmlentities($format_act['samplerate'])." kHz"; ?></li>
          </ul>
       	</div>
    <div id="SeitenNav">
      	<?php
		}
			if (getNumElements($_GET) > $_GET['limit']){
				createSiteNav($_GET);
			} 
		?>
 	</div>
  </div>
</div>
</body>
</html>
