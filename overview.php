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

        $_SESSION['query_Array'] = array();
        $_SESSION['query'] = array();
        parse_str($_SERVER['QUERY_STRING'], $_SESSION['query']);

        include('php/filters.php');

        if (isset($_POST['cont'])){
            header('Location: overview.php?'.http_build_query(createContinentFilter($_POST)));
            exit;
        }

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
      <img src="media/Design_Vorlagen/Hauptseite/02c_entdecke_login.png" />
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
        <div id="SuchBut"></div>
        <div id="SortBut">
        	<div id="SortBut1"></div>        
        	<div id="SortBut2"></div> 
        	<div id="SortBut3"></div> 
        
        </div>
   	</div>
  
  <div id="LinkeNavigation">
    <div id="LinkeNavKat">
      <ul>
        <li class="OberKat">
        	<a href="#">Orte</a>
            <ul>
            	<li class="Unterpunkt">
                    <form method="POST">
                        <input type="checkbox" name="cont" value="eu"
                                onchange="this.form.submit();"
                                <?php if (isset($_GET['cont']) && checkContinentFilter('eu')){ echo ' checked=/"checked/"';} ?>>
                        Europa
                    </form></li>
            	<li class="Unterpunkt">
                    <form method="POST">
                        <input type="checkbox" name="cont" value="as"
                               onchange="this.form.submit();"
                                <?php if (isset($_GET['cont']) && checkContinentFilter('as')){ echo ' checked=/"checked/"';} ?>>
                        Asien
                    </form>
                </li>
            	<li class="Unterpunkt">
                    <form method="POST">
                        <input type="checkbox" name="cont" value="af"
                               onchange="this.form.submit();"
                                <?php if (isset($_GET['cont']) && checkContinentFilter('af')){ echo ' checked=/"checked/"';} ?>>
                        Afrika
                    </form>
                </li>
            	<li class="Unterpunkt">
                    <form method="POST"><input type="checkbox" name="cont" value="am"
                                               onchange="this.form.submit();"
                                <?php if (isset($_GET['cont']) && checkContinentFilter('am')){ echo ' checked=/"checked/"';} ?>>
                        Amerika
                    </form>
                </li>
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
  		<div class="AnzeigeBut">
            <?php
                if (getNumElements($_GET) > $_GET['limit']){
                    createSiteNav($_GET);
                }
            ?>
      	</div>
      <?php
		$query = createSearch($_GET);
        $query->execute();

        unset($_SESSION['query_Array']);
        $index = 0;
		while ($row = $query->fetch()){
            $_SESSION['query_Array'][$index] = $row['id'];
			$locationArray = getLocation_by_ID($row['location_id']);
			$format_act = getFormat_by_ID($row['format_id']);
				?>
      <div class="Ambiences">
       		<a href="detail.php?id=<?php echo $index; ?>">
                <img src="media/pics_ambiences/thumb/<?php echo htmlentities($row['picture']);?>"  class="AmbiencePic" />
            </a>
      		<div class="AmbienceDescription">
                <a href="detail.php?id=<?php echo $index; ?>">
                    <h1><?php echo htmlentities($row['name']); ?></h1>
                </a>
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
            $index++;
		}

		?>
 		</div>
  	</div>
	<div class="AnzeigeBut">
        <?php
            if (getNumElements($_GET) > $_GET['limit']){
                createSiteNav($_GET);
            }
        ?>
    </div>	

</div>
</body>
</html>
