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

        if (isset($_POST['name'])){
            $_SESSION['query']['name'] = $_POST['name'];

            header('Location: overview.php?'.http_build_query($_SESSION['query']));
            exit;
        }

        if (isset($_POST['minLgt'])){
            $_SESSION['query']['minLgt'] = $_POST['minLgt'];
            if ($_POST['maxLgt'] != ""){
                $_SESSION['query']['maxLgt'] = $_POST['maxLgt'];
            } else {
                unset($_SESSION['query']['maxLgt']);
            }

            header('Location: overview.php?'.http_build_query($_SESSION['query']));
            exit;
        }

        if (isset($_POST['cont'])){
            header('Location: overview.php?'.http_build_query(createContinentFilter($_POST)));
            exit;
        }
        if (isset($_POST['cdc'])){
            header('Location: overview.php?'.http_build_query(createFormatFilter($_POST)));
            exit;
        }
        if (isset($_POST['bd'])){
            header('Location: overview.php?'.http_build_query(createDepthFilter($_POST)));
            exit;
        }
        if (isset($_POST['sf'])){
            header('Location: overview.php?'.http_build_query(createFreqFilter($_POST)));
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
    	<form method="POST">
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
        	<a>Orte</a>
            <ul>
                <li class="Unterpunkt">
                    <form method="POST">
                        <?php if (isset($_GET['cont']) && checkContinentFilter('eu')){ echo 'x';} ?>
                        <input type="hidden" name="cont" value="eu" >
                        <a onclick="$(this).closest('form').submit()">Europa</a>
                    </form>
                </li>
            	<li class="Unterpunkt">
                    <form method="POST">
                        <?php if (isset($_GET['cont']) && checkContinentFilter('as')){ echo 'x';} ?>
                        <input type="hidden" name="cont" value="as" >
                        <a onclick="$(this).closest('form').submit()">Asien</a>
                    </form>
                </li>
            	<li class="Unterpunkt">
                    <form method="POST">
                        <?php if (isset($_GET['cont']) && checkContinentFilter('af')){ echo 'x';} ?>
                        <input type="hidden" name="cont" value="af" >
                        <a onclick="$(this).closest('form').submit()">Afrika</a>
                    </form>
                </li>
            	<li class="Unterpunkt">
                    <form method="POST">
                        <?php if (isset($_GET['cont']) && checkContinentFilter('am')){ echo 'x';} ?>
                        <input type="hidden" name="cont" value="am" >
                        <a onclick="$(this).closest('form').submit()">Amerika</a>
                    </form>
                </li>
        	</ul>
        </li>
        <li class="OberKat">
        	<a>Dauer</a>
        	<ul>
                <form method="POST">
                    <li class="Unterpunkt">
                        Min:
                        <input onchange="$(this).closest('form').submit()" type="text" name="minLgt" value="<?php if (isset($_GET['minLgt'])) { echo $_GET['minLgt'];} else { echo 0;} ?>" />
                        s
                    </li>
                    <li>
                        Max:
                        <input onchange="$(this).closest('form').submit()" type="text" name="maxLgt" value="<?php if (isset($_GET['maxLgt'])) { echo $_GET['maxLgt'];}?>" />
                        s
                    </li>
                </form>
            </ul>
       	</li>
        <li class="OberKat">
            <a>Format</a>
            <ul>
                <li class="Unterpunkt">
                    <form method="POST">
                        <?php if (isset($_GET['cdc']) && checkFormatFilter('riff')){ echo 'x';} ?>
                        <input type="hidden" name="cdc" value="riff" >
                        <a onclick="$(this).closest('form').submit()">WAV</a>
                    </form>
                </li>
                <li class="Unterpunkt">
                    <form method="POST">
                        <?php if (isset($_GET['cdc']) && checkFormatFilter('mp3')){ echo 'x';} ?>
                        <input type="hidden" name="cdc" value="mp3" >
                        <a onclick="$(this).closest('form').submit()">mp3</a>
                    </form>
                </li>
                <li class="Unterpunkt">
                    <form method="POST">
                        <?php if (isset($_GET['cdc']) && checkFormatFilter('mp4')){ echo 'x';} ?>
                        <input type="hidden" name="cdc" value="mp4" >
                        <a onclick="$(this).closest('form').submit()">mp4</a>
                    </form>
                </li>
            </ul>
        </li>
        <li class="OberKat">
        	<a>Auflösung</a>
                <ul>
                    <li class="Unterpunkt">
                        <form method="POST">
                            <?php if (isset($_GET['bd']) && checkDepthFilter('16')){ echo 'x';} ?>
                            <input type="hidden" name="bd" value="16" >
                            <a onclick="$(this).closest('form').submit()">16 bit</a>
                        </form>
                    </li>
                    <li class="Unterpunkt">
                        <form method="POST">
                            <?php if (isset($_GET['bd']) && checkDepthFilter('24')){ echo 'x';} ?>
                            <input type="hidden" name="bd" value="24" >
                            <a onclick="$(this).closest('form').submit()">24 bit</a>
                        </form>
                    </li>
                    <li class="Unterpunkt">
                        <form method="POST">
                            <?php if (isset($_GET['bd']) && checkDepthFilter('32')){ echo 'x';} ?>
                            <input type="hidden" name="bd" value="32" >
                            <a onclick="$(this).closest('form').submit()">32 bit</a>
                        </form>
                    </li>
                </ul>
        </li>
        <li class="OberKat">
              <a href="#">Abstastrate</a>
        		<ul>
                    <li class="Unterpunkt">
                        <form method="POST">
                            <?php if (isset($_GET['sf']) && checkFreqFilter('44100')){ echo 'x';} ?>
                            <input type="hidden" name="sf" value="44100" >
                            <a onclick="$(this).closest('form').submit()">44.1 kHz</a>
                        </form>
                    </li>
                    <li class="Unterpunkt">
                        <form method="POST">
                            <?php if (isset($_GET['sf']) && checkFreqFilter('48000')){ echo 'x';} ?>
                            <input type="hidden" name="sf" value="48000" >
                            <a onclick="$(this).closest('form').submit()">48 kHz</a>
                        </form>
                    </li>
                    <li class="Unterpunkt">
                        <form method="POST">
                            <?php if (isset($_GET['sf']) && checkFreqFilter('96000')){ echo 'x';} ?>
                            <input type="hidden" name="sf" value="96000" >
                            <a onclick="$(this).closest('form').submit()">96 kHz</a>
                        </form>
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
