<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Detailansicht</title>
<link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="css/foundation.css" />
<script src="js/vendor/modernizr.js"></script>
<script src="js/audiojs/audio.min.js"></script>
<link rel="stylesheet" href="css/Haupseite.css" type="text/css" />
<script type="text/javascript">
    var a = audiojs;
    a.events.ready(function() {
        var a1 = a.createAll();
    });
</script>

    <?php
        include('php/include.php');
        $id;
        $amb;

        if (isset($_GET['id']) && is_numeric($_GET['id'])){

            $id = $_SESSION['query_Array'][$_GET['id']];
            $amb = get_ambience_by_ID($id);
            $format = getFormat_by_ID($amb['format_id']);
            $loc = getLocation_by_ID($amb['location_id']);
            $cat = get_category_by_ID($amb['category_id']);
        } else {
            header('Location: overview.php');
            exit;
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
       <div id="BkwdtoHauptseitebtn">
    
    		<a href="overview.php?<?php echo http_build_query($_SESSION['query']) ?>"><< zurück zur Übersicht </a>
    
    </div>
  		<div id="SucheDetail">
    	<form method="GET">
        	<input type="hidden" name="limit" value="<?php echo $_GET['limit']; ?>" />
    		<input type="text" name="name" />
        </form>
        </div>
        <div id="SuchBut"></div>
   	</div>
  <div id="Detailansicht">
  	
    <div id="AmbienceBildBut">

        <?php
            if ($_GET['id'] != 0){
        ?>
        <a href="detail.php?id=<?php echo $_GET['id']-1;?>"><div id="BkwButton">Vorheriges</div></a>
  	    <?php
            }
            if ($_GET['id'] != count($_SESSION['query_Array'])-1){
        ?>
        <a href="detail.php?id=<?php echo $_GET['id']+1;?>"><div id="FwdButton">Nächstes</div></a>
        <?php
            }
        ?>
    </div>
    
    

        <img src="media/pics_ambiences/<?php echo $amb['picture']; ?>" id="AmbienceBildGroß"></img>

  	
    
    <div id="AmbienceDescript">
    		<h1><?php echo $amb['name']; ?></h1>
    		<ul>
            	<li><?php echo $loc['land']; ?></li>
            	<li><?php echo $format['samplerate']/1000; ?> Khz</li>
            	<li><?php echo $format['codec']; ?></li>
            </ul>
    </div>
    
  	<div id="AmbiencePlayer">
        <audio src="media/audio/<?php echo $amb['filename']; ?>" preload="none"></audio>
  	</div>
    
  	
  
  
  
  
  
  
  
  
  
  </div>


</div>
</body>
</html>