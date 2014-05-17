<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>AmbienceWebsite_Startseite</title>
	<link rel="stylesheet" href="css/foundation.css" />
    <script src="js/vendor/modernizr.js"></script>
	<link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="css/Startseite_style.css" type="text/css" />

    <?php
        include('php/include.php');
    ?>
    <script type="text/javascript" src="js/fancyBox/source/jquery.fancybox.pack.js"></script>
    <link rel="stylesheet" href="js/fancyBox/source/jquery.fancybox.css" media="screen" />
    <script type="text/javascript" src="js/Soundmanager2/script/soundmanager2.js"></script>
    <script type="text/javascript" src="js/spin.js"></script>

    <?php

    $errorLog;

    if (isset($_SESSION['name'])){
        header("Location: overview.php");
    }

    if (isset($_POST['loginName'])){
        $errorLog = login($_POST);


        if($errorLog['correct']){
            header('Location: overview.php');
            exit;
        }
    }
		
		$randPics = getRandAmb(8);
	?>
    <script type="text/javascript">
        soundManager.url="js/Soundmanager2/swf/soundmanager2.swf";
        var randPicsJSON = '<?php echo json_encode($randPics); ?>';
        var randPics = JSON.parse(randPicsJSON);
        var playing = false;
        var playingID;

        var opts = {
            lines: 17, // The number of lines to draw
            length: 0, // The length of each line
            width: 9, // The line thickness
            radius: 30, // The radius of the inner circle
            corners: 1, // Corner roundness (0..1)
            rotate: 48, // The rotation offset
            direction: 1, // 1: clockwise, -1: counterclockwise
            color: '#000', // #rgb or #rrggbb or array of colors
            speed: 1, // Rounds per second
            trail: 36, // Afterglow percentage
            shadow: false, // Whether to render a shadow
            hwaccel: false, // Whether to use hardware acceleration
            className: 'spinner', // The CSS class to assign to the spinner
            zIndex: 2e9, // The z-index (defaults to 2000000000)
            top: '50%', // Top position relative to parent
            left: '50%' // Left position relative to parent
        };

        var spinner;

        soundManager.onload = function() {
            for (var i = 0; i< 8; i++){
                soundManager.createSound({
                    id: randPics[i].id,
                    url: 'media/audio/'+randPics[i].filename,
                    autoload: true,
                    whileplaying: function() {
                        spinner.stop();
                        $(".buttonPlay").css('display', 'block');
                    },
                    onfinish:function()  {
                        window.location.href='index.php'
                    }});
            }
        };


        function buttonClick(soundID){
            if (!playing){
                soundManager.play(soundID);
                playing = true;
                playingID = soundID;
                $(".buttonPlay").css('display', 'none');
                spinner = new Spinner(opts).spin(document.getElementById(soundID));

                $("#"+soundID).children(".buttonPlay").children("img").css('display', 'block');

                $("#"+soundID).hover(function(){
                    $(this).children(".buttonPlay").children("img").css('display', 'none');
                    $(this).children(".buttonPause").children("img").css('display', 'block');
                }, function() {
                    $(this).children(".buttonPlay").children("img").css('display', 'block');
                    $(this).children(".buttonPause").children("img").css('display', 'none');
                });

            } else {
                //Stoppen (auf selbiges Bild klicken)
                if (playingID == soundID){
                    soundManager.stopAll();
                    spinner.stop();
                    playing=false;
                    playingID = null;

                    $("#"+soundID).children(".buttonPlay").children("img").css('display', 'none');

                    $("#"+soundID).hover(function(){
                        $(this).children(".buttonPlay").children("img").css('display', 'block');
                        $(this).children(".buttonPause").children("img").css('display', 'none');
                    }, function() {
                        $(this).children(".buttonPlay").children("img").css('display', 'none');
                        $(this).children(".buttonPause").children("img").css('display', 'none');
                    });

                } else {

                    $("#"+playingID).children(".buttonPlay").children("img").css('display', 'none');

                    $("#"+playingID).hover(function(){
                        $(this).children(".buttonPlay").children("img").css('display', 'block');
                        $(this).children(".buttonPause").children("img").css('display', 'none');
                    }, function() {
                        $(this).children(".buttonPlay").children("img").css('display', 'none');
                        $(this).children(".buttonPause").children("img").css('display', 'none');
                    });

                    spinner.stop();
                    soundManager.stopAll();
                    soundManager.play(soundID);
                    playingID = soundID;
                    $(".buttonPlay").css('display', 'none');
                    spinner = new Spinner(opts).spin(document.getElementById(soundID));

                    $("#"+soundID).children(".buttonPlay").children("img").css('display', 'none');

                    $("#"+soundID).hover(function(){
                        $(this).children(".buttonPlay").children("img").css('display', 'none');
                        $(this).children(".buttonPause").children("img").css('display', 'block');
                    }, function() {
                        $(this).children(".buttonPlay").children("img").css('display', 'block');
                        $(this).children(".buttonPause").children("img").css('display', 'none');
                    });

                }
            }
        }

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

            $(".picframe").hover(function(){
                $(this).children(".buttonPlay").children("img").css('display', 'block');
            }, function() {
                $(this).children(".buttonPlay").children("img").css('display', 'none');
            });
		});

	</script>
</head>

<body>
<div id="Content">

	<div class="row abstand">

        <div id ="TopLeft" class="medium-pull-1 column">
            <div class="picframe" id="<?php echo $randPics[0]['id'] ?>" onclick="buttonClick('<?php echo $randPics[0]['id'] ?>')">
                <div class="buttonPlay"><img src="media/Design_Vorlagen/Startseite/01b_startseite_play.png" /></div>
                <div class="buttonPause"><img src="media/Design_Vorlagen/Startseite/01b_startseite_pause.png" /></div>
                <img src="media/pics_ambiences/<?php echo $randPics[0]['picture'] ?>" class="AmbienceFotoFade AmbienceFoto desaturate grey" />
            </div>
        </div>
    
    	<div id="TopCenter" class="medium column">
            <div class="picframe" id="<?php echo $randPics[1]['id'] ?>"  onclick="buttonClick('<?php echo $randPics[1]['id'] ?>')">
                <div class="buttonPlay"><img src="media/Design_Vorlagen/Startseite/01b_startseite_play.png" /></div>
                <div class="buttonPause"><img src="media/Design_Vorlagen/Startseite/01b_startseite_pause.png" /></div>
                <img src="media/pics_ambiences/<?php echo $randPics[1]['picture'] ?>" class="AmbienceFoto desaturate grey"/>
            </div>
        </div>
    
    	<div id="TopRight" class="medium-push-1 column">
            <div class="picframe" id="<?php echo $randPics[2]['id'] ?>"  onclick="buttonClick('<?php echo $randPics[2]['id'] ?>')">
                <div class="buttonPlay"><img src="media/Design_Vorlagen/Startseite/01b_startseite_play.png" /></div>
                <div class="buttonPause"><img src="media/Design_Vorlagen/Startseite/01b_startseite_pause.png" /></div>
                <img src="media/pics_ambiences/<?php echo $randPics[2]['picture'] ?>" class="AmbienceFoto desaturate grey"/>
            </div>
        </div>
    
    </div>

	<div class="row abstand" id="Center">
  
    	<div id="MidLeft" class="medium-pull-1 column">
            <div class="picframe" id="<?php echo $randPics[3]['id'] ?>"  onclick="buttonClick('<?php echo $randPics[3]['id'] ?>')">
                <div class="buttonPlay"><img src="media/Design_Vorlagen/Startseite/01b_startseite_play.png" /></div>
                <div class="buttonPause"><img src="media/Design_Vorlagen/Startseite/01b_startseite_pause.png" /></div>
                <img src="media/pics_ambiences/<?php echo $randPics[3]['picture'] ?>" class="AmbienceFoto desaturate grey"/>
            </div>
        </div>
    
   		<div id="LoginBox" class="column">
        	<div id="LoginTop" class="medium row text-center">
        
       			<h1>Login</h1>
   				<div class="small column"><form method="POST">
                	<input id="logName" type="text" name="loginName" <?php if(isset($errorLog['name'])){ ?> style="background-color:#F00" <?php } ?> value="<?php if(isset($_POST['loginName']) && isset($errorLog['pass'])){ echo $_POST['loginName'];} ?>"/>
                    <input type="password" name="loginPass" <?php if(isset($errorLog['pass'])){ ?> style="background-color:#F00" <?php } ?>/>
                    <?php
                        if (isset($errorLog['name'])){
                            ?><div class="alert-box alert"><?php
                            echo $errorLog['name'];
                            ?></div><?php
                        } elseif (isset($errorLog['pass'])){
                            ?><div class="alert-box alert"><?php
                            echo $errorLog['pass'];
                            ?></div><?php
                        }
                    ?>
                 <div id="LoginBut" class="small-push-1 column"><input type="submit" value="Einloggen"/></div>
                </form>
              	 <div id="Reg" class="small-push-2 column"><a id="regFrame" data-fancybox-type="iframe" href="register.php" target="_blank" title="Registrieren"><img src="media/Design_Vorlagen/Startseite/01b_startseite_registrieren.png" /></a> </div>
                </div>
          	</div>
        
        	<a id="LoginBottom" href="overview.php" class="small column text-center">
             
          	<h1>Entdecke</h1>
            
            </a>
	</div>
    
   	 	<div id="MidRight" class="medium-push-1 column">
            <div class="picframe" id="<?php echo $randPics[4]['id'] ?>" onclick="buttonClick('<?php echo $randPics[4]['id'] ?>')">
                <div class="buttonPlay"><img src="media/Design_Vorlagen/Startseite/01b_startseite_play.png" /></div>
                <div class="buttonPause"><img src="media/Design_Vorlagen/Startseite/01b_startseite_pause.png" /></div>
                <img src="media/pics_ambiences/<?php echo $randPics[4]['picture'] ?>" class="AmbienceFoto desaturate grey"/>
            </div>
        </div>
   </div>

   	<div class="row abstand">
    
  		<div id="BotLeft" class="medium-pull-1 column">
            <div class="picframe" id="<?php echo $randPics[5]['id'] ?>"  onclick="buttonClick('<?php echo $randPics[5]['id'] ?>')">
                <div class="buttonPlay"><img src="media/Design_Vorlagen/Startseite/01b_startseite_play.png" /></div>
                <div class="buttonPause"><img src="media/Design_Vorlagen/Startseite/01b_startseite_pause.png" /></div>
                <img src="media/pics_ambiences/<?php echo $randPics[5]['picture'] ?>" class="AmbienceFoto desaturate grey"/>
            </div>
        </div>
    
    	<div id="BotCenter" class="medium column">
            <div class="picframe"  id="<?php echo $randPics[6]['id'] ?>" onclick="buttonClick('<?php echo $randPics[6]['id'] ?>')">
                <div class="buttonPlay"><img src="media/Design_Vorlagen/Startseite/01b_startseite_play.png" /></div>
                <div class="buttonPause"><img src="media/Design_Vorlagen/Startseite/01b_startseite_pause.png" /></div>
                <img src="media/pics_ambiences/<?php echo $randPics[6]['picture'] ?>" class="AmbienceFoto desaturate grey"/>
            </div>
        </div>
    
    	<div id="BotRight" class="medium-push-1 column">
            <div class="picframe" id="<?php echo $randPics[7]['id'] ?>" onclick="buttonClick('<?php echo $randPics[7]['id'] ?>')">
                <div class="buttonPlay"><img src="media/Design_Vorlagen/Startseite/01b_startseite_play.png" /></div>
                <div class="buttonPause"><img src="media/Design_Vorlagen/Startseite/01b_startseite_pause.png" /></div>
                <img src="media/pics_ambiences/<?php echo $randPics[7]['picture'] ?>" class="AmbienceFoto desaturate grey"/>
            </div>
        </div>
	</div>

</div>
</body>

</html>
