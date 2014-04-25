<?php	
	//MySQL
	$connection = 	mysql_connect("localhost","root","")
					or die ("keine Verbindung möglich. Benutzername oder Passwort sind falsch");

	mysql_select_db("ambienceproj")
					or die ("Die Datenbank existiert nicht.");
					
	mysql_query("SET NAMES 'utf8'");
	
	//Get ID3
	require('getid3/getid3.php');
	
	//GoogleMaps API
	?>
	<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBeZ-iwEnYMBb5cke9oBkkYf-5WqCGhxf8&sensor=false&libraries=places"></script>
    <script type="text/javascript" src="JS/functions.js"></script>
	<?php
	
	//Verbindung zu PHP-Skripten
	require_once("sql.php");
	require_once("upload.php");
	
	//$getID3->setOption(array('encoding' => $PageEncoding));
	
	//Session starten
	session_start();
	
	//Audio auf Server laden und in DB eintragen
	function upload_audio_inDB_onServer ($file, $userid){
		$getID3 = new getID3;
		$info = $getID3->analyze($file['tmp_name']);
		if (!isset ($info['error']) && isset($info['audio'])){
			$infoArray = get_audio_info($info, $file);
			$filename_new = add_amb($infoArray, $userid);
			upload_audio($file, $filename_new);
		
			$amb_id_act = get_ambience_id($filename_new);
		
			return $amb_id_act;
		} else {
			throw new Exception('Illegal File Format');
		}
	}
	
	function delete_audio_fromDB_andServer($id){
		$infoArray = get_ambience_by_ID($id);
		
		delete_ambience_fromDB($id);
		delete_ambience_from_Server($infoArray);
	}
	
	//Pfadvariablen
	function getRoot(){
		$rootPath = str_replace("\\php", "", dirname(__FILE__));
		return $rootPath;
	}
	
	function createSiteNav($array){
		$gesamt = getNumElements($_GET);
		
		$sites = ceil($gesamt / $array['limit']);
		//if ($sites > 10){
			$actDez = floor($array['page']/10);
			$dezCount = $sites - $actDez * 10;
			if ($dezCount > 10) {$dezCount = 10;}
			if ($actDez != 0){
				if (isset($array['query'])){
					echo "<a href='overview.php?limit=".$array['limit']."&page=1&query=".$array['query']."'>Erste</a>";
				} else {
					echo "<a href='overview.php?limit=".$array['limit']."&page=1'>Erste</a>";
				}
			}
			if ($_GET['page'] != 1){
				if (isset($array['query'])){
					echo "<a href='overview.php?limit=".$array['limit']."&page=".($_GET['page']-1)."&query=".$array['query']."'><</a>";
				} else {
					echo "<a href='overview.php?limit=".$array['limit']."&page=".($_GET['page']-1)."'><</a>";
				}
			}
			for ($i = 1; $i<=$dezCount; $i++){
				if (isset($array['query'])){
					echo "<a href='overview.php?limit=".$array['limit']."&page=".($actDez+$i)."&query=".$array['query']."'>".($actDez+$i)."</a> ";
				} else {
					echo "<a href='overview.php?limit=".$array['limit']."&page=".($actDez+$i)."'>".($actDez+$i)."</a> ";
				}
			}
			if ($_GET['page'] != $sites){
				if (isset($array['query'])){
					echo "<a href='overview.php?limit=".$array['limit']."&page=".($_GET['page']+1)."&query=".$array['query']."'>></a>";
				} else {
					echo "<a href='overview.php?limit=".$array['limit']."&page=".($_GET['page']+1)."'>></a>";
				}
			}
			if ($actDez != floor($sites/10)){
				if (isset($array['query'])){
					echo "<a href='overview.php?limit=".$array['limit']."&page=".$sites."&query=".$array['query']."'><</a>";
				} else {
					echo "<a href='overview.php?limit=".$array['limit']."&page=".$sites."'><</a>";
				}
			}
		//}
	}
?>