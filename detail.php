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

        if(isset($_POST['rate'])){
            rate(get_user_by_ID($_SESSION['id']), $amb, $_POST['rate']);
        }

        if (isset($_SESSION['id'])){
            $rating = get_rating(get_user_by_ID($_SESSION['id']), $amb);
        }

        if(isset($_POST['filename'])){
            addDownload($amb);
            header("Location: php/download.php?filename=".$_POST['filename']);
        }
    ?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#changeAmbFrame").fancybox({
            'type' : 'iframe',
            'titlePosition' : 'over',
            'padding' : 0,
            'margin' : 0,
            'width' : 500,
            'height': 300,
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
    
    

        <img src="media/pics_ambiences/<?php echo $amb['picture']; ?>" id="AmbienceBildGroß" />
        <?php if(isset($_SESSION['id']) && ($amb['user_id'] == $_SESSION['id'] || $_SESSION['rights'] == 'admin' )){ ?>
            <td><a id="changeAmbFrame" data-fancybox-type="iframe" href="changeAmb.php?ch=pic&id=<?php echo htmlentities($amb['id']); ?>" target="_blank" title="Bild">Bild ändern</a></td>
        <?php } ?>

  	
    
    <div id="AmbienceDescript">
    		<h1><?php echo htmlentities($amb['name']); ?></h1>
            <?php if(isset($_SESSION['id']) && ($amb['user_id'] == $_SESSION['id'] || $_SESSION['rights'] == 'admin' )){ ?>
                <td><a id="changeAmbFrame" data-fancybox-type="iframe" href="changeAmb.php?ch=name&id=<?php echo htmlentities($amb['id']); ?>" target="_blank" title="Name">Ändern</a></td>
            <?php } ?>
            (<?php echo date("d.m.y", strtotime(htmlentities($amb['date_added']))); ?>)<br />

            <?php if (isset($_SESSION['id'])){ ?>
            <div id="rating">
                <?php
                for($i = 0; $i < $rating; $i++){
                ?>
                    <form class="rateBut" method="POST">
                        <input type="hidden" name="rate" value="<?php echo $i+1; ?>">
                        <img onclick="$(this).closest('form').submit()" src="media/Design_Vorlagen/Detailansicht/bewertung_gruen_true.png" />
                    </form>
                <?php
                }
                if ($rating < 5){
                    for ($i = 0; $i < 5-$rating; $i++){
                        ?>
                        <form class="rateBut" method="POST">
                            <input type="hidden" name="rate" value="<?php echo $rating+$i+1; ?>">
                            <img onclick="$(this).closest('form').submit()" src="media/Design_Vorlagen/Detailansicht/bewertung_khaki_false.png" />
                        </form>
                <?php
                    }
                }
                ?>
            </div>
            <?php } ?>

            <table>
                <tr>
                    <td>Durchschnittsbewertung:</td>
                    <td><?php echo round(getAverageRating($amb), 2); ?></td>
                </tr>
                <tr>
                    <td>Heruntergeladen:</td>
                    <td><?php echo $amb['downloaded']; ?></td>
                </tr>
                <tr>
                    <td>Dauer:</td>
                    <td><?php echo gmdate("i:s", htmlentities($amb['length'])); ?> Min</td>
                </tr>
                <tr>
                    <td>Aufgenommen:</td>
                    <td><b><?php echo date("d.m.y", strtotime(htmlentities($amb['date']))); ?></b> um <b><?php echo date("H:i", strtotime(htmlentities($amb['time']))); ?></b> Uhr</td>
                    <?php if(isset($_SESSION['id']) && ($amb['user_id'] == $_SESSION['id'] || $_SESSION['rights'] == 'admin' )){ ?>
                        <td><a id="changeAmbFrame" data-fancybox-type="iframe" href="changeAmb.php?ch=datetime&id=<?php echo htmlentities($amb['id']); ?>" target="_blank" title="Datum/Uhrzeit">Ändern</a></td>
                    <?php } ?>
                </tr>
                <tr>
                    <td>Kategorie:</td>
                    <td><?php echo htmlentities($cat['name']); ?></td>
                    <?php if(isset($_SESSION['id']) && ($amb['user_id'] == $_SESSION['id'] || $_SESSION['rights'] == 'admin' )){ ?>
                        <td><a id="changeAmbFrame" data-fancybox-type="iframe" href="changeAmb.php?ch=cat&id=<?php echo htmlentities($amb['id']); ?>" target="_blank" title="Kategorie">Ändern</a></td>
                    <?php } ?>
                </tr>
                <tr>
                    <td>Beschreibung:</td>
                    <td><?php echo htmlentities($amb['description']); ?></td>
                    <?php if(isset($_SESSION['id']) && ($amb['user_id'] == $_SESSION['id'] || $_SESSION['rights'] == 'admin' )){ ?>
                        <td><a id="changeAmbFrame" data-fancybox-type="iframe" href="changeAmb.php?ch=descr&id=<?php echo htmlentities($amb['id']); ?>" target="_blank" title="Beschreibung">Ändern</a></td>
                    <?php } ?>
                </tr>
                <tr>
                    <td>Ort:</td>
                    <td>
                        <?php
                        if ($loc['name'] != "dummy"){
                            echo htmlentities($loc['name']); ?>, <?php echo htmlentities($loc['land']);
                        }
                        ?>
                    </td>
                    <?php if(isset($_SESSION['id']) && ($amb['user_id'] == $_SESSION['id'] || $_SESSION['rights'] == 'admin' )){ ?>
                        <td><a id="changeAmbFrame" data-fancybox-type="iframe" href="changeAmb.php?ch=loc&id=<?php echo htmlentities($amb['id']); ?>" target="_blank" title="Ort">Ändern</a></td>
                    <?php } ?>
                </tr>
            </table>
    </div>

    <div id="delAmbBut">
        <?php if(isset($_SESSION['id']) && ($amb['user_id'] == $_SESSION['id'] || $_SESSION['rights'] == 'admin' )){ ?>
            <td><a id="changeAmbFrame" data-fancybox-type="iframe" href="changeAmb.php?ch=del&id=<?php echo htmlentities($amb['id']); ?>" target="_blank" title="Ambience wirklich löschen?">Ambience löschen</a></td>
        <?php } ?>
    </div>
	
    <div id="AmbiencePlayer">
        <audio src="media/audio/<?php echo htmlentities($amb['filename']); ?>" preload="none"></audio>
  	</div>
    
  	<div id="Useranzeige">
    	<div id="UserBild">
            <?php if (isset($user['picture']) && $user['picture'] != ""){   ?>
                <a href="user.php?id=<?php echo htmlentities($user['id']); ?>"><img src="media/pics_user/<?php echo htmlentities($user['picture']); ?>" width="100px" height="80px" /></a>
            <?php } ?>
        </div>
        <div id="Username"><a href="user.php?id=<?php echo htmlentities($user['id']); ?>"><?php echo htmlentities($user['name']); ?></a></div>
    </div>

    <?php if (isset($_SESSION['name'])){ ?>
        <div id="downloadarea">
        	<div id="DownloadButton">
                <form method="POST" >
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


  	
  </div>


</div>
</body>
</html>