<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Hauptseite_uneingeloggt</title>
<link rel="stylesheet" href="Haupseite.css" type="text/css" />

	<?php
		include('php/include.php');
	?>

</head>

<body>
	<div id="Content">
		<div id="Header">
        	
            <div id="LogoHeader">Logo</div>
            <div id="LoginHeader">
            	<a href="">
					<?php
                        if (!isset($_SESSION['name'])){
                    ?>
                        Login
                    <?php
                        } else {
                            echo $_SESSION['name'];
                        }
                    ?>
                </a>
            </div>
        	<br>
            <div id="SucheHeader">Suche</div>
            
        </div>
        
        <div id="ObereNavigation">
        
        	<div id="Button1" class="ButtonNavigation">Ambiences</div>
            
            <div id="Button2" class="ButtonNavigation">FAQ</div>
            
            <div id="Button3" class="ButtonNavigation">Kontakt</div>
        
        </div>
        
        <div id="LinkeNavigation"> 
        
       		<div id="LinkeNavKat">
            	<ul>
            	 <li>Ort</li>
                 <li>Tageszeit</li>
                 <li>Qualität</li>
            	</ul>
            </div>
                   
        </div>
        
        <div id="AmbiencesAnzeige">
        	
            <table id="Ambiences">
            	<?php
					$abfrage = "SELECT * FROM ambience;";
					$result = mysql_query($abfrage);
					$found = mysql_num_rows($result);
					$index = 0;
					while ($row = mysql_fetch_object($result)){
						if ($index%2 == 0){
							$format_act = getFormat_by_ID($row->format_id);
				?>
            	<tr>
            		<td>
                    	<img src="media/pics_ambiences/thumb/<?php echo $row->picture;  ?>" class="AmbiencePic" />
                  	</td>
                    <td>
                    	<h1><?php echo $row->name; ?></h1>
                        <ul>
                        	<li><?php echo $row->location; ?></li>
                            <li><?php echo date("G:i", strtotime($row->time)) ?></li>
                            <li><?php echo $format_act['bitdepth']." bit , ".$format_act['samplerate']." kHz"; ?></li>
                       	</ul>
                  	</td>
                <?php
							if ($result == ($index -1)) {
								?></tr><?php
							}
							$index ++;
						} else {
				?>
                 	<td>
                    	<img src="media/pics_ambiences/thumb/<?php echo $row->picture;  ?>" class="AmbiencePic" />
                  	</td>
                    <td>
                    	<h1><?php echo $row->name; ?></h1>
                        <ul>
                        	<li><?php echo $row->location; ?></li>
                            <li><?php echo date("G:i", strtotime($row->time)); ?></li>
                            <li><?php echo $format_act['bitdepth']." bit , ".$format_act['samplerate']." kHz"; ?></li>
                       	</ul>
                  	</td>
            		
               	</tr>
                <?php
						$index++;
						}
					}
				?>
            </table>
            
        </div>
            
    </div>
</body>
</html>
