<?php	
	//MySQL
	$connection = 	mysql_connect("localhost","root","")
					or die ("keine Verbindung möglich. Benutzername oder Passwort sind falsch");

	mysql_select_db("ambienceproj")
					or die ("Die Datenbank existiert nicht.");
	
	//Get ID3
	require('getid3/getid3.php');
	
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
			echo $filename_new;
		
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
?>