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
        $user = get_user_by_ID($_GET['id']);
        $ambArray = get_ambience_by_user($_GET);
        $ambArrayForLocs = get_ambience_by_user_noLim($_GET);
        $allLocs = array();
        $index = 0;
            foreach($ambArrayForLocs as $amb){
                $locationArray = getLocation_by_ID($amb['location_id']);
                $allLocs[$index]['location'] = $locationArray;
                $allLocs[$index]['ambience'] = $amb;
                $index++;
            }
    } else {
        header("Location: overview.php");
    }

    if (isset($_POST['showMail'])){
        if($user['emailShown']){
            setUserShowMail(false);
            header("Location: user.php?id=".$_SESSION['id']);
        } else {
            setUserShowMail(true);
            header("Location: user.php?id=".$_SESSION['id']);
        }
    }

?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#changeFrame").fancybox({
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

        $(".picframe").hover(function(){
            $(this).children(".buttonPlay").children("img").css('display', 'block');
        }, function() {
            $(this).children(".buttonPlay").children("img").css('display', 'none');
        });
    });

    var locsJSON = '<?php echo json_encode($allLocs); ?>';
    var locs = JSON.parse(locsJSON);
</script>
<script type="text/javascript" src="js/googleMapsUser.js"></script>
</head>

<body>
<div id="Content">
  	<?php include("header.php"); ?>

	<div id="Benutzerprofil">
    	<h1><?php echo htmlentities($user['name']); ?></h1>
		<div id="Benutzerbild">
            <?php if (isset($user['picture']) && $user['picture'] != ""){ ?>
                <img src="media/pics_user/<?php echo htmlentities($user['picture']); ?>" />
            <?php } else { ?>
            <img src="media/Design_Vorlagen/Userseite/standardUser.jpg" />
            <?php } ?>
            <?php if(isset($_SESSION['id']) && $user['id'] == $_SESSION['id']){ ?>
                <a id="changeFrame" data-fancybox-type="iframe" href="changeUser.php?ch=pic" target="_blank" title="Bild">Bild ändern</a>
            <?php } ?>
        </div>
		<div id="Benutzerdetails">
            <table>
                <?php if(isset($_SESSION['id']) && $user['id'] == $_SESSION['id']){ ?>
                <tr>
                    <td class="Datenname">E-Mail:</td>
                    <td>
                        <div id="mail">
                            <?php echo htmlentities($user['email']); ?>
                        </div>
                    </td>
                    <td><a id="changeFrame" data-fancybox-type="iframe" href="changeUser.php?ch=mail" target="_blank" title="E-Mail">Ändern</a></td>
                <tr>
                <?php }
                elseif($user['emailShown'] || (isset($_SESSION['rights']) && $_SESSION['rights'] == 'admin')){ ?>
                    <tr>
                        <td class="Datenname">E-Mail:</td>
                        <td>
                            <div id="mail">
                                <a href="mailto:<?php echo htmlentities($user['email']); ?>"><?php echo htmlentities($user['email']); ?></a>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
                </tr>
                <tr>
                    <td class="Datenname">Über mich:</td>
                    <td>
                        <div id="about">
                            <?php echo htmlentities($user['about']); ?>
                        </div>
                    </td>
                    <?php if(isset($_SESSION['id']) && $user['id'] == $_SESSION['id']){ ?>
                    <td><a id="changeFrame" data-fancybox-type="iframe" href="changeUser.php?ch=about" target="_blank" title="Über mich">Ändern</a></td>
                    <?php }?>
                </tr>
            </table>
        </div>
        <?php if(isset($_SESSION['id']) && $user['id'] == $_SESSION['id']){ ?>
        <div id="pwChangeBut">
            <a id="changeFrame" data-fancybox-type="iframe" href="changeUser.php?ch=pw" target="_blank" title="Über mich">Passwort ändern</a>
        </div>
        <div id="showEmail">
            <form method="POST">
                <input type="hidden" name="showMail">
                <a onclick="$(this).closest('form').submit()">E-Mail-Addresse anzeigen</a>
                <?php if($user['emailShown']){ echo "X";} ?>
            </form>
        </div>
        
        <?php } ?>
	</div>
    <div id="Uploadanzeige">
    	<h1>Uploads</h1>
        <div id="UploadALinks">
    		
            <div id="GoogleMaps"><div id="map-canvas"></div></div>

    		<div id="Uploaddetails">
                <div id="countUploads">
                    Uploads:<br />
                    <?php echo get_numElements_by_user($_GET); ?>
                </div>
                <div id="avgRating">
                    Durchschnittliche Bewertung:<br />
                    <?php echo round(getAverageUserRating($user), 2); ?>
                </div>
                <div id="countDownloads">
                    Gedownloaded:<br />
                    <?php echo htmlentities(getDownloadCountByUser($user)); ?>
                </div>
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
    <div id="deleteUserBut">
            <td><a id="changeFrame" data-fancybox-type="iframe" href="changeUser.php?ch=del" target="_blank" title="Account wirklich löschen?"></a></td>
        </div>
</div>
</body>
</html>
