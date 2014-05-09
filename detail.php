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
            $user = get_user_by_ID($amb['user_id']);
            header('title: '.htmlentities($amb['name']).'');
        } else {
            header('Location: overview.php');
            exit;
        }


    ?>
</head>

<body>

<div id="Content">
  <?php include ("header.php"); ?>

    <div id="BkwdtoHauptseitebtn"><a href="overview.php?<?php echo http_build_query($_SESSION['query']) ?>"><< zurück zur Übersicht </a></div>


    <div id="Detailansicht">
  	
    <div id="AmbienceBildBut">

        <?php
            if ($_GET['id'] != 0){
        ?>
        <a href="detail.php?id=<?php echo htmlentities($_GET['id'])-1;?>"><div id="BkwButton">Vorheriges</div></a>
  	    <?php
            }
            if ($_GET['id'] != count($_SESSION['query_Array'])-1){
        ?>
        <a href="detail.php?id=<?php echo htmlentities($_GET['id'])+1;?>"><div id="FwdButton">Nächstes</div></a>
        <?php
            }
        ?>
    </div>
    
    

        <img src="media/pics_ambiences/<?php echo $amb['picture']; ?>" id="AmbienceBildGroß"></img>

  	
    
    <div id="AmbienceDescript">
    		<h1><?php echo htmlentities($amb['name']); ?></h1>
            <a href="""><?php echo htmlentities($user['name']); ?></a> (<?php echo date("d.m.y", strtotime(htmlentities($amb['date_added']))); ?>)<br />

            <table>
                <tr>
                    <td>Dauer:</td>
                    <td><?php echo gmdate("i:s", htmlentities($amb['length'])); ?> Min</td>
                </tr>
                <tr>
                    <td>Aufgenommen:</td>
                    <td><b><?php echo date("d.m.y", strtotime(htmlentities($amb['date']))); ?></b> um <b><?php echo date("H:i", strtotime(htmlentities($amb['time']))); ?></b> Uhr</td>
                </tr>
                <tr>
                    <td>Kategorie:</td>
                    <td><?php echo htmlentities($cat['name']); ?></td>
                </tr>
                <tr>
                    <td>Beschreibung:</td>
                    <td><?php echo htmlentities($amb['description']); ?></td>
                </tr>
                <tr>
                    <td>Ort:</td>
                    <td><?php echo htmlentities($loc['name']); ?>, <?php echo htmlentities($loc['land']); ?></td>
                </tr>
            </table>
    </div>
	
    <div id="AmbiencePlayer">
        <audio src="media/audio/<?php echo htmlentities($amb['filename']); ?>" preload="none"></audio>
  	</div>
    
  	

    <?php if (isset($_SESSION['name'])){ ?>
        <div id="downloadarea">
        	<div id="DownloadButton">
                <form method="POST" action="php/download.php">
                    <input type="hidden" name="filename" value="<?php echo htmlentities($amb['filename']); ?>" />
                    <input  type="submit" value="Download" />
                </form>
            </div>
            <div id="fileinfos">
                <table>
                    <tr>
                        <td>Format:</td>
                        <td><?php echo htmlentities($format['codec']); ?></td>
                    </tr>

                    <tr>
                        <td>Qualität:</td>
                        <td><?php echo htmlentities($format['samplerate']/1000); ?> Khz, <?php echo htmlentities($format['bitdepth']); ?> bit</td>
                    </tr>
                    <tr>
                        <td>Dateigröße:</td>
                        <td><?php echo round(htmlentities($amb['size'])/1024/1024, 1); ?> MB</td>
                    </tr>
                </table>
            </div>
            
        </div>
    <?php } ?>


  	<div id="Useranzeige">
    	<div id="UserBild">Bild</div>
        <div id="Username">UserHeinz</div>
    </div>
  </div>


</div>
</body>
</html>