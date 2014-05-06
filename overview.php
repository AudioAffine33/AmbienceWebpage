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

        if (isset($_POST['lgt'])){
            if (!isset($_GET['lgt'])){
                $_SESSION['query']['lgt'] = $_POST['lgt'];
                header('Location: overview.php?'.http_build_query($_SESSION['query']));
                exit;
            } else {
                unset ($_SESSION['query']['lgt']);
                header('Location: overview.php?'.http_build_query($_SESSION['query']));
                exit;
            }
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

        if (isset($_POST['cat'])){
            if (!isset($_GET['cat']) || $_POST['cat'] != ""){
                header('Location: overview.php?'.http_build_query(createCatFilter($_POST)));
                exit;
            } else {
                unset($_SESSION['query']['cat']);
                header('Location: overview.php?'.http_build_query($_SESSION['query']));
                exit;
            }
        }

        if (isset($_POST['cont'])){
            if (!isset($_GET['cont']) || $_POST['cont'] != ""){
                header('Location: overview.php?'.http_build_query(createContinentFilter($_POST)));
                exit;
            } else {
                unset($_SESSION['query']['cont']);
                header('Location: overview.php?'.http_build_query($_SESSION['query']));
                exit;
            }
        }
        if (isset($_POST['cdc'])){
            if (!isset($_GET['cdc']) || $_POST['cdc'] != ""){
                header('Location: overview.php?'.http_build_query(createFormatFilter($_POST)));
                exit;
            } else {
                unset($_SESSION['query']['cdc']);
                header('Location: overview.php?'.http_build_query($_SESSION['query']));
                exit;
            }
        }
        if (isset($_POST['bd'])){
            if (!isset($_GET['bd']) || $_POST['bd'] != ""){
                header('Location: overview.php?'.http_build_query(createDepthFilter($_POST)));
                exit;
            } else {
                unset($_SESSION['query']['bd']);
                header('Location: overview.php?'.http_build_query($_SESSION['query']));
                exit;
            }
        }
        if (isset($_POST['sf'])){
            if (!isset($_GET['sf']) || $_POST['sf'] != ""){
                header('Location: overview.php?'.http_build_query(createFreqFilter($_POST)));
                exit;
            } else {
                unset($_SESSION['query']['sf']);
                header('Location: overview.php?'.http_build_query($_SESSION['query']));
                exit;
            }
        }

		if (!isset($_GET['limit']) || !is_numeric($_GET['limit'])){
			$_GET['limit']=10;
            $_GET['page']=1;
		}
		if (!isset($_GET['page']) || !is_numeric($_GET['page'])){
			$_GET['page']=1;
		}
	?>

    <script type="text/javascript">
        $(document).ready(function(){
            $(".OberKat").click(function(){
                //if ($(this).children("ul").css('display') != 'block'){
                   // $(this).children("ul").css('display', 'block').css('padding', '10%');
                //} else {
                   // $(this).children("ul").css('display', 'none').css('padding', '');
                //}
            });
        });
    </script>
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
              <form method="POST"><input type="hidden" name="cat" value="" /><a onclick="$(this).closest('form').submit()">Kategorien</a></form>
              <ul>
                  <?php
                  if (isset($_GET['cat'])){
                      $catArray = get_categories();

                      foreach ($catArray as $x){
                          ?>
                          <li class="Unterpunkt">
                              <form method="POST">
                                  <?php if (isset($_GET['cat']) && checkCatFilter($x)){ echo 'x';} ?>
                                  <input type="hidden" name="cat" value="<?php echo $x; ?>" >
                                  <a onclick="$(this).closest('form').submit()"><?php echo $x; ?></a>
                              </form>
                          </li>
                      <?php
                      }
                  }
                  ?>
              </ul>
          </li>
        <li class="OberKat">
        	<form method="POST"><input type="hidden" name="cont" value="" /><a onclick="$(this).closest('form').submit()">Orte</a></form>
            <ul>
                <?php
                    if (isset($_GET['cont'])){
                        $contArray = getAllContinentsGerman();

                        foreach ($contArray as $x){
                            ?>
                            <li class="Unterpunkt">
                                <form method="POST">
                                    <?php if (isset($_GET['cont']) && checkContinentFilter($x['code'])){ echo 'x';} ?>
                                    <input type="hidden" name="cont" value="<?php echo $x['code']; ?>" >
                                    <a onclick="$(this).closest('form').submit()"><?php echo $x['german']; ?></a>
                                </form>
                            </li>
                            <?php
                        }
                    }
                ?>
        	</ul>
        </li>
        <li class="OberKat">
            <form method="POST"><input type="hidden" name="lgt" value="" /><a onclick="$(this).closest('form').submit()">Dauer</a></form>
        	<ul>
                <?php if(isset($_GET['lgt'])){ ?>
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
                <?php } ?>
            </ul>
       	</li>
        <li class="OberKat">
            <form method="POST"><input type="hidden" name="cdc" value="" /><a onclick="$(this).closest('form').submit()">Format</a></form>
            <ul>
                <?php
                if (isset($_GET['cdc'])){
                    $formArray = getAllFormats();

                    foreach ($formArray as $x){
                        ?>
                        <li class="Unterpunkt">
                            <form method="POST">
                                <?php if (isset($_GET['cdc']) && checkFormatFilter($x['codec'])){ echo 'x';} ?>
                                <input type="hidden" name="cdc" value="<?php echo $x['codec']; ?>" >
                                <a onclick="$(this).closest('form').submit()"><?php
                                    if ($x['codec'] != "riff"){
                                        echo $x['codec'];
                                    } else {
                                        echo "WAV";
                                    }
                                    ?>
                                </a>
                            </form>
                        </li>
                    <?php
                    }
                }
                ?>
            </ul>
        </li>
        <li class="OberKat">
            <form method="POST"><input type="hidden" name="bd" value="" /><a onclick="$(this).closest('form').submit()">Auflösung</a></form>
            <ul>
                <?php
                if (isset($_GET['bd'])){
                    $bdArray = getAllBitdepths();

                    foreach ($bdArray as $x){
                        if (isset($x['bitdepth']) && $x['bitdepth'] != NULL && $x['bitdepth'] != ""){
                        ?>
                        <li class="Unterpunkt">
                            <form method="POST">
                                <?php if (isset($_GET['bd']) && checkDepthFilter($x['bitdepth'])){ echo 'x';} ?>
                                <input type="hidden" name="bd" value="<?php echo $x['bitdepth']; ?>" >
                                <a onclick="$(this).closest('form').submit()"><?php echo $x['bitdepth']." bit";?>
                                </a>
                            </form>
                        </li>
                    <?php
                        }
                    }
                }
                ?>
            </ul>
        </li>
        <li class="OberKat">
            <form method="POST"><input type="hidden" name="sf" value="" /><a onclick="$(this).closest('form').submit()">Abtastrate</a></form>
            <ul>
                <?php
                if (isset($_GET['sf'])){
                    $bdArray = getAllFreqs();

                    foreach ($bdArray as $x){
                            ?>
                            <li class="Unterpunkt">
                                <form method="POST">
                                    <?php if (isset($_GET['sf']) && checkFreqFilter($x['samplerate'])){ echo 'x';} ?>
                                    <input type="hidden" name="sf" value="<?php echo $x['samplerate']; ?>" >
                                    <a onclick="$(this).closest('form').submit()"><?php echo ($x['samplerate']/1000)." kHz";?>
                                    </a>
                                </form>
                            </li>
                    <?php
                    }
                }
                ?>
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
