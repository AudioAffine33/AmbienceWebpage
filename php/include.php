<?php	
	//MySQL
	try {
		$db = new PDO(
					'mysql:host=localhost; dbname=ambienceproj', 
					'root', 
					'', 
					array( 
						PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
						PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
						PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
					)
				);
	} catch (Exception $e){
		die ('Datenbankfehler!');
	}

    global $db;
	
	//Get ID3
	require('getid3/getid3.php');

    //Countrys&Continents
    require('CountryContinents.php');
	
	//GoogleMaps API
	?>
    <link rel="stylesheet" href="css/normalize/normalize.css" type="text/css" />
    <script type="text/javascript" src="js/jquery-1.11.0.js"></script>
    <script type="text/javascript" src="js/fancyBox/source/jquery.fancybox.pack.js"></script>
    <link rel="stylesheet" href="js/fancyBox/source/jquery.fancybox.css" media="screen" />
	<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBeZ-iwEnYMBb5cke9oBkkYf-5WqCGhxf8&sensor=false&libraries=places"></script>
    <script type="text/javascript" src="js/functions.js"></script>
	<?php
	
	//Verbindung zu PHP-Skripten
	require_once("sql.php");
	require_once("upload.php");
	
	//$getID3->setOption(array('encoding' => $PageEncoding));
	
	//Session starten
	session_start();

    //
    $query_Array = array();

    //ggf. Session löschen
    if (isset($_POST['logout'])){
        session_destroy();
    }

    //login überprüfen
    $errorLog;

    if (isset($_POST['loginName'])){
        $errorLog = login($_POST);


        if($errorLog['correct']){
            header('Location: overview.php');
            exit;
        }
    }
	
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
		$query_act = array();
		parse_str($_SERVER['QUERY_STRING'], $query_act);
		
		$gesamt = getNumElements($_GET);
		
		$sites = ceil($gesamt / $array['limit']);
		//if ($sites > 10){
			$actDez = floor($array['page']/10);
			$dezCount = $sites - $actDez * 10;
			if ($dezCount > 10) {$dezCount = 10;}
			if ($actDez != 0){
				$query_act['page']=1;
				echo "<a href='overview.php?".http_build_query($query_act)."'>Erste</a>";
			}
			if ($_GET['page'] != 1){
				$query_act['page']= $_GET['page']-1;
				echo "<a href='overview.php?".http_build_query($query_act)."'><div id='AButbkw''></div></a>";
			}
			for ($i = 1; $i<=$dezCount; $i++){
				$query_act['page']= ($actDez+$i);
				echo "<a href='overview.php?".http_build_query($query_act)."'>".($actDez+$i)."</a> ";
			}
			if ($_GET['page'] != $sites){
				$query_act['page']= $_GET['page']+1;
				echo "<a href='overview.php?".http_build_query($query_act)."'><div id='AButfwd'></div></a>";
			}
			if ($actDez != floor($sites/10)){
				$query_act['page']= $sites;
				echo "<a href='overview.php?".http_build_query($query_act)."'>Letzte</a>";
			}
		//}
	}
?>