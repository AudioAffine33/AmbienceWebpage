<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Hauptseite_uneingeloggt</title>
<link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="css/foundation.css" />
<script src="js/vendor/modernizr.js"></script>
<link rel="stylesheet" href="css/Haupseite.css" />
<?php
		include('php/include.php');

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
        $(document).ready(function() {
            $("#regFrame").fancybox({
                'type' : 'iframe',
                'titlePosition' : 'over',
                'padding' : 0,
                'margin' : 0,
                'width' : 310,
                'height': 523,
                'scrolling' : 'no',
                'fitToView' : false,
                'autoSize' : false,
                'closeBtn' : false
            });
            $(".fancybox-iframe").attr('scrolling', 'no');
            $(".fancybox-iframe").attr("src", $(".fancybox-iframe").attr("src"));
        });
    </script>
</head>

<body>
<div id="Content" class="row">
  <?php include("header.php"); ?>
  <div id="Haupt" class="row">
  <div id="LinkeNavigation" class="columns medium-2 hide-for-medium-down">
    <div id="LinkeNavKat" class="column">
      <ul class="side-nav text-center">
          <noscript><li class="noscript">Für die Filterung der Einträge wird Javascript benötigt</li></noscript>
          <li class="OberKat">
              <form method="POST"><input type="hidden" name="cat" value="" /><a class="CursorAen" onclick="$(this).closest('form').submit()"><h5>Kategorien</h5></a></form>
              <ul class="side-nav">
                  <?php
                  if (isset($_GET['cat'])){
                      $catArray = get_categories();

                      foreach ($catArray as $x){
                          ?>
                          <li class="Unterpunkt">
                              <form method="POST">
                                  <?php if (isset($_GET['cat']) && checkCatFilter($x)){ echo 'x';} ?>
                                  <input type="hidden" name="cat" value="<?php echo htmlentities($x); ?>" >
                                  <a onclick="$(this).closest('form').submit()"><?php echo htmlentities($x); ?></a>
                              </form>
                          </li>
                      <?php
                      }
                  }
                  ?>
              </ul>
          </li>
        <li class="OberKat">
        	<form method="POST"><input type="hidden" name="cont" value="" /><a class="CursorAen" onclick="$(this).closest('form').submit()"><h5>Orte</h5></a></form>
            <ul class="side-nav">
                <?php
                    if (isset($_GET['cont'])){
                        $contArray = getAllContinentsGerman();

                        foreach ($contArray as $x){
                            if ($x['code'] != "dummy"){
                                ?>
                                <li class="Unterpunkt">
                                    <form method="POST">
                                        <?php if (isset($_GET['cont']) && checkContinentFilter($x['code'])){ echo 'x';} ?>
                                        <input type="hidden" name="cont" value="<?php echo htmlentities($x['code']); ?>" >
                                        <a onclick="$(this).closest('form').submit()"><?php echo htmlentities($x['german']); ?></a>
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
            <form method="POST"><input type="hidden" name="lgt" value="" /><a class="CursorAen" onclick="$(this).closest('form').submit()"><h5>Dauer</h5></a></form>
        	<ul class="side-nav">
                <?php if(isset($_GET['lgt'])){ ?>
                <form method="POST">
                    <li class="Unterpunkt">
                        Min:
                        <input onchange="$(this).closest('form').submit()" type="text" name="minLgt" value="<?php if (isset($_GET['minLgt'])) { echo htmlentities($_GET['minLgt']);} else { echo 0;} ?>" />
                        s
                    </li>
                    <li>
                        Max:
                        <input onchange="$(this).closest('form').submit()" type="text" name="maxLgt" value="<?php if (isset($_GET['maxLgt'])) { echo htmlentities($_GET['maxLgt']);}?>" />
                        s
                    </li>
                </form>
                <?php } ?>
            </ul>
       	</li>
        <li class="OberKat">
            <form method="POST"><input type="hidden" name="cdc" value="" /><a class="CursorAen" onclick="$(this).closest('form').submit()"><h5>Format</h5></a></form>
            <ul class="side-nav">
                <?php
                if (isset($_GET['cdc'])){
                    $formArray = getAllFormats();

                    foreach ($formArray as $x){
                        ?>
                        <li class="Unterpunkt">
                            <form method="POST">
                                <?php if (isset($_GET['cdc']) && checkFormatFilter($x['codec'])){ echo 'x';} ?>
                                <input type="hidden" name="cdc" value="<?php echo htmlentities($x['codec']); ?>" >
                                <a onclick="$(this).closest('form').submit()"><?php
                                    if ($x['codec'] != "riff"){
                                        echo htmlentities($x['codec']);
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
            <form method="POST"><input type="hidden" name="bd" value="" /><a class="CursorAen" onclick="$(this).closest('form').submit()"><h5>Auflösung</h5></a></form>
            <ul class="side-nav">
                <?php
                if (isset($_GET['bd'])){
                    $bdArray = getAllBitdepths();

                    foreach ($bdArray as $x){
                        if (isset($x['bitdepth']) && $x['bitdepth'] != NULL && $x['bitdepth'] != ""){
                        ?>
                        <li class="Unterpunkt">
                            <form method="POST">
                                <?php if (isset($_GET['bd']) && checkDepthFilter($x['bitdepth'])){ echo 'x';} ?>
                                <input type="hidden" name="bd" value="<?php echo htmlentities($x['bitdepth']); ?>" >
                                <a onclick="$(this).closest('form').submit()"><?php echo htmlentities($x['bitdepth'])." bit";?>
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
            <form method="POST"><input type="hidden" name="sf" value="" /><a class="CursorAen" onclick="$(this).closest('form').submit()"><h5>Abtastrate</h5></a></form>
            <ul class="side-nav">
                <?php
                if (isset($_GET['sf'])){
                    $bdArray = getAllFreqs();

                    foreach ($bdArray as $x){
                            ?>
                            <li class="Unterpunkt">
                                <form method="POST">
                                    <?php if (isset($_GET['sf']) && checkFreqFilter($x['samplerate'])){ echo 'x';} ?>
                                    <input type="hidden" name="sf" value="<?php echo htmlentities($x['samplerate']); ?>" >
                                    <a onclick="$(this).closest('form').submit()"><?php echo (htmlentities($x['samplerate']/1000))." kHz";?>
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
  <div id="AmbiencesAnzeige" class="columns large-10 medium-12">
           <?php
                if (getNumElements($_GET) > $_GET['limit']){
                    ?> <div class="AnzeigeBut row"><?php
                        createSiteNav($_GET);
                        ?> </div> <?php
                }
            ?>
      <?php
		$query = createSearch($_GET);
        $query->execute();

        unset($_SESSION['query_Array']);
        $index = 0;
		while ($row = $query->fetch()){
			$locationArray = getLocation_by_ID($row['location_id']);
			$format_act = getFormat_by_ID($row['format_id']);
				?>
          <div class="Ambience columns medium-6 small-12">
       		<div class="columns small-8 small-8"><a href="detail.php?id=<?php echo $row['id'] ?>">
                <img src="media/pics_ambiences/thumb/<?php echo htmlentities($row['picture']);?>"  class="AmbiencePic" />
            </a></div>
      		<div class="AmbienceDescription medium-4 small-4 columns">
                <a href="detail.php?id=<?php echo $row['id'] ?>">
                    <h1><?php echo htmlentities($row['name']); ?></h1>
                </a>
          		<ul>
                    <?php if ($locationArray['name'] != "dummy"){ ?>
            		    <li><?php if (isset($locationArray['name'])){echo $locationArray['name']; } ?></li>
                    <?php } ?>
                    <li><?php echo date("G:i", strtotime($row['time'])) ?></li>
                    <li><?php echo htmlentities($format_act['bitdepth'])." bit , ".(htmlentities($format_act['samplerate'])/1000)." kHz"; ?></li>
                </ul>
       		</div>
        </div>
      	<?php
            $index++;
		}
		?>

        <?php
            if (getNumElements($_GET) > $_GET['limit']){
                ?>
                <div class="NavField column" >
                <div class="AnzeigeBut column"><?php
                createSiteNav($_GET);
                ?>
                </div>
                </div> <?php
            }
        ?>
    </div>
</div>
</div>
</body>
</html>
