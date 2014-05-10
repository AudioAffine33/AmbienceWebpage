<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Userseite</title>
<link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="css/foundation.css" />
<link rel="stylesheet" href="css/Haupseite.css" type="text/css" />
<script src="js/vendor/modernizr.js"></script>
<?php
    include('php/include.php');

    if (!isset($_GET['page']) || !is_numeric($_GET['page'])){
        $_GET['page']=1;
    }

    if (isset($_GET['id']) and is_numeric($_GET['id'])){
        $user = getUser_by_ID($_GET['id']);
        $ambArray = get_ambience_by_user($_GET);
        $allLocs = array();
        $index = 0;
            foreach($ambArray as $amb){
                $locationArray = getLocation_by_ID($amb['location_id']);
                $allLocs[$index]['location'] = $locationArray;
                $allLocs[$index]['ambience'] = $amb;
                $index++;
            }
    } else {
        header("Location: overview.php");
    }
?>
<script type="text/javascript">
    var locsJSON = '<?php echo json_encode($allLocs); ?>';
    var locs = JSON.parse(locsJSON);
</script>
<script type="text/javascript" src="js/googleMapsUser.js"></script>
</head>

<body>
<div id="Content">
  	<?php include("header.php"); ?>

	<div id="Benutzerprofil">
    	<h1><?php echo htmlentities($user['name']); ?><h1>
		<div id="Benutzerbild">
            <?php if (isset($user['picture'])){ ?>
                <img src="media/pics_user/<?php echo htmlentities($user['picture']); ?>" />
            <?php } ?>
        </div>
		<div id="Benutzerdetails"><?php echo htmlentities($user['about']); ?></div>

	</div>
    <div id="Uploadanzeige">
    	<h1>Uploads</h1>
        <div id="UploadALinks">
    		
            <div id="GoogleMaps"><div id="map-canvas"></div></div>

    		<div id="Uploaddetails">
                Uploads:<br />
                <?php echo get_numElements_by_user($_GET); ?>
    		</div>
    	
        </div>
        
        <div id="UploadARechts">
        	<div id="AmbiencesUserAnzeige">
                <?php
                    $index=0;
                    foreach($ambArray as $amb){
                        $_SESSION['query_Array'][$index] = $amb['id'];
                        $locationArray = getLocation_by_ID($amb['location_id']);
                        $format_act = getFormat_by_ID($amb['format_id']);
                        $cat_act = get_category_by_ID($amb['category_id']);
                ?>
                    <div class = "AmbienceUser">
                                      
                            <a href="detail.php?id=<?php echo $index; ?>">
                                <img src="media/pics_ambiences/thumb/<?php echo htmlentities($amb['picture']); ?>" class="UserAmPic" />
                            </a>
      
                        <div class = "AmbienceUserDescr">
                            <a href="detail.php?id=<?php echo $index; ?>"><h3><?php echo htmlentities($amb['name']); ?></h3></a>
                            <table>
                                <tr>
                                    <td>Kategorie</td>
                                    <td><?php echo htmlentities($cat_act['name']); ?></td>
                                </tr>
                                <tr>
                                    <td>Region</td>
                                    <td><?php echo htmlentities($locationArray['name']).", ".htmlentities($locationArray['land']); ?></td>
                                </tr>
                                <tr>
                                    <td>Dauer</td>
                                    <td><?php echo gmdate("i:s", htmlentities($amb['length'])); ?> Min</td>
                                </tr>
                                <tr>
                                    <td>Qualität</td>
                                    <td><?php echo (htmlentities($format_act['samplerate'])/1000)." kHz, ".htmlentities($format_act['bitdepth'])." bit"; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                <?php
                        $index++;
                    }
                ?>
       		</div>
            <?php
            if (get_numElements_by_user($_GET) > 5){
                ?>
                <div class="NavigationUploads">
                    <?php
                        createUserSiteNav($_GET);
                    ?>
                </div> <?php
            }
            ?>
    	</div>
    </div>
    
</div>
</body>
</html>
</div>
</body>
</html>
