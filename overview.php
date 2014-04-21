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
            	<tr>
            		<td>
                    	<img src="Testbilder/533046l.jpg" class="AmbiencePic" />
            		</td>
                    <td>
                    	<h1>Blumen und Gras</h1>
                    	<ul>
                        	<li>Ostsee,DE</li>
                            <li>12:00Uhr</li>
                            <li>16-bit, 44.1Khz</li>
                    	</ul>
                    </td>
                    <td>
                    	<img src="Testbilder/dsc_9869axhaf.jpg" class="AmbiencePic"  />
                    </td>
                    <td>
                    	<h1>Fluss</h1>
                        <ul>
                        	<li>Ostsee,DE</li>
                            <li>15:00Uhr</li>
                            <li>4-bit, 8Khz</li>
                    	</ul>
                    </td>
           		</tr>
                
                <tr>
                	<td>
                    	<img src="Testbilder/DSC00904.jpg" class="AmbiencePic" />
                  	</td>
                    <td>
                    	<h1>Alpenpanorama</h1>
                        <ul>
                        	<li>Alpen,AUS</li>
                            <li>16:00 Uhr</li>
                            <li>8-bit, 16Khz</li>
                       	</ul>
                  	</td>
                    
                    <td>
                    	<img src="Testbilder/dscn1776.jpg" class="AmbiencePic" />
                    </td>
                    <td>
                    	<h1>Palmenallee</h1>
                        <ul>
                        	<li>San Francisco,USA</li>
                            <li>1PM</li>
                            <li>8-bit,44.1Khz</li>
                       	</ul>
                  	</td>
               	</tr>
                  
                <tr>
                	<td>
                    	<img src="Testbilder/hollywood-walk-of-fame-100.jpg" class="AmbiencePic" />
                  	</td>
                    <td>
                    	<h1>Walk of Fame</h1>
                        <ul>
                        	<li>Los Angeles,USA</li>
                            <li>10AM</li>
                            <li>16-bit,48Khz</li>
                       	</ul>
                  	</td>
                    
                    <td>
                    	<img src="Testbilder/lanschaftsbilder004.jpg" class="AmbiencePic" />
                    </td>
                    <td>
                    	<h1>Grasberg</h1>
                        <ul>
                        	<li>Hobbingen,Mittelerde</li>
                            <li>14:00 Uhr</li>
                            <li>16-bit, 48Khz</li>
                       	</ul>
                  	</td>
               	</tr>
            </table>
            
        </div>
            
    </div>
</body>
</html>
