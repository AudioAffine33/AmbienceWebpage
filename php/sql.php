<?php
	function add_amb($infoArray, $userid){
		$format_id = get_format_id($infoArray);
		
		if (is_null($format_id)){
			if (isset($infoArray['bits_per_sample'])){
				$abfrage  = "INSERT INTO format (codec, bitdepth, samplerate, bitrate, channels) VALUES ('";
				$abfrage .= $infoArray['format']."', ".$infoArray['bits_per_sample'].", ".$infoArray['samplerate'].", ".$infoArray['bitrate'].", ".$infoArray['channels'].");";
			} else {
				$abfrage  = "INSERT INTO format (codec, samplerate, bitrate, channels) VALUES ('";
				$abfrage .= $infoArray['format']."', ".$infoArray['samplerate'].", ".$infoArray['bitrate'].", ".$infoArray['channels'].");";
			}
			
			mysql_query($abfrage);
			
			$format_id = get_format_id($infoArray);
		}
		
		$ext = pathinfo($infoArray['filename'], PATHINFO_EXTENSION);	
		$ersetzen = array( 'ä' => 'ae', 'ö' => 'oe', 'ü' => 'ue', 'ß' => 'ss', ' ' => '_', '\\' => '-', '/' => '-', '|' => '-', '*' => '-' , ',' => '_');
		$nameNeu = strtr( strtolower( pathinfo($infoArray['filename'], PATHINFO_FILENAME) ), $ersetzen );
		$nameNeu = substr($nameNeu, 0, 40);
		$filename_neu = $nameNeu.".".$ext;
		
		$abfrage  = "INSERT INTO ambience (format_id, filename, size, length, user_id, date_added, name) VALUES ";
		$abfrage .= "(".$format_id.", '".$filename_neu."', ".$infoArray['filesize'].", ".$infoArray['length'].", ";
		$abfrage .= $userid.", CURDATE(),'".substr($infoArray['filename'], 0, strrpos($infoArray['filename'], '.'))."')";
		
		mysql_query($abfrage);
		
		$amb_id = get_ambience_id($filename_neu);
		
		//Description
		if (isset($infoArray['riffDescr'])){
			$abfrage = "UPDATE ambience SET description='".$infoArray['riffDescr']."' WHERE id=".$amb_id.";";

			mysql_query($abfrage);
		}
		if (isset($infoArray['id3title'])){
			$abfrage = "UPDATE ambience SET description='".$infoArray['id3title']."' WHERE id=".$amb_id.";";

			mysql_query($abfrage);
		}
		
		//Date, Time
		if (isset($infoArray['date'])){
			$abfrage = "UPDATE ambience SET date='".$infoArray['date']."' WHERE id=".$amb_id.";";

			mysql_query($abfrage);
		}
		if (isset($infoArray['time'])){
			$abfrage = "UPDATE ambience SET time='".$infoArray['time']."' WHERE id=".$amb_id.";";

			mysql_query($abfrage);
		}
		
		//Originator
		if (isset($infoArray['orig'])){
			$abfrage = "UPDATE ambience SET originator='".$infoArray['orig']."' WHERE id=".$amb_id.";";

			mysql_query($abfrage);
		}
		
		$ext = pathinfo($filename_neu, PATHINFO_EXTENSION);	
		$filename_neu = $amb_id."_".$filename_neu;
		$abfrage = "UPDATE ambience SET filename='".$filename_neu."' WHERE id=".$amb_id.";";
		
		
		mysql_query($abfrage);
		
		
		return $filename_neu;
	}
	
	function addUser ($userArray){
		$array = checkUser($userArray);
		if ($array['new']){
			$abfrage = "INSERT INTO user (name, pass, email, rights) VALUES ('".$userArray['regName']."', '".md5($userArray['regPass1'])."', '".$userArray['regMail']."', 'user');";
			mysql_query($abfrage);
			return $array;
		}
		else {
			return $array;
		}
	}
	
	function checkUser ($userArray){
		$error = array ('new' => true);
		
		$abfrage = "SELECT * FROM user WHERE name='".$userArray['regName']."'";
		$ergebnis = mysql_query($abfrage);
		$numRows = mysql_num_rows($ergebnis);
		if ($numRows > 0){
			$error['name'] ="Der Benutzername \"".$userArray['regName']."\" ist bereits vergeben<br />";
			$error['new'] = false;	
		}
		if ($userArray['regName'] == ""){
			$error['name'] = "Bitte geben Sie einen Namen an!<br /> ";	
			$error['new'] = false;
		}
		if ($userArray['regPass1'] != $userArray['regPass2']){
			$error['pass'] = "Passwörter stimmen nicht überein<br /> ";	
			$error['new'] = false;
		}
		if ($userArray['regPass1'] == "" || $userArray['regPass2'] == ""){
			$error['pass'] = "Bitte geben Sie ein Passwort an!<br /> ";	
			$error['new'] = false;
		}
		if ($userArray['regMail'] == ""){
			$error['mail'] = "E-Mail-Addresse nicht korrekt<br />";
			$error['new'] = false;
		}
		
		return $error;
	}
	
	function login($loginArray){
		$error;
		$error['correct'] = false;
		
		$abfrage = "SELECT * FROM user WHERE name='".$loginArray['loginName']."';";
		$ergebnis = mysql_query($abfrage);
		
		$numrows = mysql_num_rows($ergebnis);
		if ($numrows < 1){
			$error['name'] = "Der Benutzername existiert nicht!";	
		}
		
		while ($row = mysql_fetch_object($ergebnis)){
			if (md5($loginArray['loginPass']) == $row->pass){
				//session_start();
				$_SESSION['name'] = $row->name;
				$_SESSION['id'] = $row->id;
				$error['correct'] = true;
			} else {
				$error['pass'] = "Das angegebene Passwort ist nicht korrekt";
			}
		}
		return $error;
	}
	
	function get_categories(){
		$ret = array();
		
		$abfrage = "SELECT * FROM category;";		
		$result = mysql_query($abfrage);		
		while($row = mysql_fetch_object($result)){
			$ret[$row->id] = $row->name;
		}
		
		return $ret;
	}
	
	function get_category_by_ID($id){
		$ret = array();
		
		$abfrage = "SELECT * FROM category WHERE id=".$id;
		$result = mysql_query($abfrage);
		while ($row = mysql_fetch_object($result)){
			$ret['id'] = $row->id;
			$ret['name'] = $row->name;
		}
		
		return $ret;
	}
	
	function get_format_id($infoArray){
		$ret = NULL;
		
		$abfrage  = "SELECT id FROM format " ;
		$abfrage .= "WHERE codec='".$infoArray['format']."' ";
		
		if (isset ($infoArray['bits_per_sample'])){
			$abfrage .= "AND bitdepth=".$infoArray['bits_per_sample']." ";
		}
		$abfrage .= "AND samplerate=".$infoArray['samplerate']." ";
		$abfrage .= "AND bitrate=".round($infoArray['bitrate'], 0)." ";
		$abfrage .= "AND channels=".$infoArray['channels'].";";
		
		$ergebnis = mysql_query($abfrage);
		
		if (isset($ergebnis)){
			while($row = mysql_fetch_object($ergebnis)){
   					$ret = $row->id;
  			}
		}
		
		return $ret;
	}
	
	function get_location_id($infoArray){
		$ret = NULL;
		
		$abfrage  = "SELECT id FROM location " ;
		$abfrage .= "WHERE name='".$infoArray['locName']."' ";
		$abfrage .= "AND land='".$infoArray['locLand']."' ";
		$abfrage .= "AND latitude=".$infoArray['locLat']." ";
		$abfrage .= "AND longitude=".$infoArray['locLng']." ";
		
		print_r($abfrage);
		$ergebnis = mysql_query($abfrage);
		
		if (isset($ergebnis)){
			while($row = mysql_fetch_object($ergebnis)){
   					$ret = $row->id;
  			}
		}
		
		return $ret;
	}
	
		function getRandAmb ($number){
		$ret = array();
		
		$abfrage= "SELECT * FROM ambience ORDER BY RAND() LIMIT ".$number;
		$result = mysql_query($abfrage);
		$index=0;
		while($row = mysql_fetch_object($result)){
			$ret[$index]["id"] = $row->id;
			$ret[$index]["format_id"] = $row->format_id;
			$ret[$index]["filename"] = $row->filename;
			$ret[$index]["size"] = $row->size;
			$ret[$index]["length"] = $row->length;
			$ret[$index]["name"] = $row->name;
			$ret[$index]["user_id"] = $row->user_id;
			$ret[$index]["location_id"] = $row->location_id;
			$ret[$index]["date"] = $row->date;
			$ret[$index]["time"] = $row->time;
			$ret[$index]["description"] = $row->description;
			$ret[$index]["category_id"] = $row->category_id;
			$ret[$index]["picture"] = $row->picture;
			$ret[$index]["rating"] = $row->rating;
			$ret[$index]["date_added"] = $row->date_added;
			$ret[$index]["orig"] = $row->originator;
			$index++;
		}
		
		return $ret;
	}

	
	function get_ambience_id($filename){
		$ret = NULL;
		
		$abfrage = "SELECT id FROM ambience WHERE filename='".$filename."'";
		
		$ergebnis = mysql_query($abfrage);
		
		while($row = mysql_fetch_object($ergebnis)){
   				$ret = $row->id;
  		}
		
		return $ret;
	}
	
	function get_ambience_by_ID($id){
		$ret = array();
		
		$abfrage = "SELECT * FROM ambience WHERE id =".$id;
		
		$ergebnis = mysql_query($abfrage);
		
		while($row = mysql_fetch_object($ergebnis)){
			$ret["id"] = $row->id;
			$ret["format_id"] = $row->format_id;
			$ret["filename"] = $row->filename;
			$ret["size"] = $row->size;
			$ret["length"] = $row->length;
			$ret["name"] = $row->name;
			$ret["user_id"] = $row->user_id;
			$ret["location_id"] = $row->location_id;
			$ret["date"] = $row->date;
			$ret["time"] = $row->time;
			$ret["description"] = $row->description;
			$ret["category_id"] = $row->category_id;
			$ret["picture"] = $row->picture;
			$ret["rating"] = $row->rating;
			$ret["date_added"] = $row->date_added;
			$ret["orig"] = $row->originator;
		}
		
		return $ret;
	}
	
	function getUser_by_ID ($id){
		$ret = array();	
		
		$abfrage = "SELECT * FROM user WHERE id =".$id;
		
		$ergebnis = mysql_query($abfrage);
		
		while ($row = mysql_fetch_object($ergebnis)){
			$ret['id'] = $row->id;
			$ret['name'] = $row->name;
			$ret['about'] = $row->about;
			$ret['email'] = $row->email;
			$ret['pic'] = $row->picture;
		}
		
		return $ret;
	}
	
	function getFormat_by_ID ($id){
		$ret = array();	
		
		$abfrage = "SELECT * FROM format WHERE id =".$id;
		
		$ergebnis = mysql_query($abfrage);
		
		while ($row = mysql_fetch_object($ergebnis)){
			$ret['id'] = $row->id;
			$ret['format'] = $row->codec;
			$ret['bitdepth'] = $row->bitdepth;
			$ret['samplerate'] = $row->samplerate;
			$ret['bitrate'] = $row->bitrate;
			$ret['channels'] = $row->channels;
		}
		
		return $ret;
	}
	
	function getLocation_by_ID ($id){
		$ret = array();	
		
		$abfrage = "SELECT * FROM location WHERE id =".$id;
		
		$ergebnis = mysql_query($abfrage);
		
		while ($row = mysql_fetch_object($ergebnis)){
			$ret['id'] = $row->id;
			$ret['name'] = $row->name;
			$ret['land'] = $row->land;
			$ret['lat'] = $row->latitude;
			$ret['lng'] = $row->longitude;
		}
		
		return $ret;
	}
	
	function setPic ($file, $amb_id){
		$check = getimagesize($file['tmp_name']);
		if (!$check){
			throw new Exception('Kein gültiges Bild');
		} else {
			$amb = get_ambience_by_ID($amb_id);
			if ($amb['picture'] != ""){
				unlink(dirname(__FILE__)."\\pics_ambiences\\".$amb['picture']);
			}
			$filename_audio = $amb['filename'];
			$arr_audio = explode(".", $filename_audio);
			$filename_pic = basename($file['name']);
			$arr_pic = explode(".", $filename_pic);
			$filename_neu = $arr_audio[0].".".$arr_pic[1];
			
			$abfrage = "UPDATE ambience SET picture='".$filename_neu."' WHERE id=".$amb_id;
			mysql_query($abfrage);
			upload_pic_amb($file, $filename_neu);
		}
	}
	
	function set_ambience_details($details_array, $amb_id){
		$abfrage = "UPDATE ambience SET name='".$details_array['name']."' WHERE id=".$amb_id;
		mysql_query($abfrage);
		
		if ($details_array['locName'] != NULL && $details_array['locName'] != ""){
			$locID = get_location_id($details_array);
			echo $locID;
			if (!isset($locID)){
				$abfrage = "INSERT INTO location (name, land, latitude, longitude) ";
				$abfrage.= "VALUES ('".$details_array['locName']."', '".$details_array['locLand']."', ".$details_array['locLat'].", ".$details_array['locLng'].")";
				mysql_query($abfrage);
				
				echo $abfrage;
				
				$locID = get_location_id($details_array);
			}
			
			$abfrage = "UPDATE ambience SET location_id='".$locID."' WHERE id=".$amb_id;
			mysql_query($abfrage);
		}
		
		if ($details_array['date'] != NULL && $details_array['date'] != ""){
			$abfrage = "UPDATE ambience SET date='".$details_array['date']."' WHERE id=".$amb_id;
			mysql_query($abfrage);
		}
		
		if ($details_array['time'] != NULL && $details_array['time'] != ""){
			$abfrage = "UPDATE ambience SET time='".$details_array['time']."' WHERE id=".$amb_id;
			mysql_query($abfrage);
		}
		
		if ($details_array['description'] != NULL && $details_array['description'] != ""){
			$abfrage = "UPDATE ambience SET description='".$details_array['description']."' WHERE id=".$amb_id;
			mysql_query($abfrage);
		}
		
		if ($details_array['category'] != NULL && $details_array['category'] != ""){
			$abfrage = "UPDATE ambience SET category_id='".$details_array['category']."' WHERE id=".$amb_id;
			mysql_query($abfrage);
		}
	}
	
	function update_amb($ambID, $updateArray){
		$ambArray = get_ambience_by_ID($ambID);
		if ($updateArray != ""){
			$locID = get_location_id($updateArray);
		}
		
		if (check_detail_Input($updateArray)){
			if ($updateArray['name'] != $ambArray['name']){
				$abfrage = "UPDATE ambience SET name='".$updateArray['name']."' WHERE id=".$ambID;
				mysql_query($abfrage);
			}
			if ($updateArray['category'] != $ambArray['category_id']){
				$abfrage = "UPDATE ambience SET category_id='".$updateArray['category']."' WHERE id=".$ambID;
				mysql_query($abfrage);
			}
			if (!isset($locID) || $locID != $ambArray['location_id']){
				if (!isset($locID)){
					$abfrage = "INSERT INTO location (name, land, latitude, longitude) ";
					$abfrage.= "VALUES ('".$updateArray['locName']."', '".$updateArray['locLand']."', ".$updateArray['locLat'].", ".$updateArray['locLng'].")";
					mysql_query($abfrage);
					
					$locID = get_location_id($updateArray);
				}
				$abfrage = "UPDATE ambience SET location_id='".$locID."' WHERE id=".$ambID;
				mysql_query($abfrage);
			}
			if ($updateArray['date'] != $ambArray['date']){
				$abfrage = "UPDATE ambience SET date='".$updateArray['date']."' WHERE id=".$ambID;
				mysql_query($abfrage);
			}
			if ($updateArray['time'] != $ambArray['time']){
				$abfrage = "UPDATE ambience SET time='".$updateArray['time']."' WHERE id=".$ambID;
				mysql_query($abfrage);
			}
			if ($updateArray['description'] != $ambArray['description']){
				$abfrage = "UPDATE ambience SET description='".$updateArray['description']."' WHERE id=".$ambID;
				mysql_query($abfrage);
			}
		} else {
			throw new Exception ("Es muss ein Name angegeben werden");
		}
	}
	
	//check Inputs
	function check_detail_Input($detail_array){
		
		if ($detail_array['name'] == NULL || $detail_array['name'] == ""){
			return false;	
		}
		else { return true;}
	}
	
	function delete_ambience_fromDB($id){
		$abfrage = "DELETE FROM ambience WHERE id =".$id;
		mysql_query($abfrage);
		
		$abfrage = "DELETE FROM rating WHERE ambience_id =".$id;
		mysql_query($abfrage);
		
		$abfrage = "DELETE FROM comment WHERE ambience_id =".$id;
		mysql_query($abfrage);
		
		$abfrage = "DELETE FROM report WHERE gemeldet_ambience_id =".$id;
		mysql_query($abfrage);
	}
	
	function createSearch ($array){
		$ret  = "SELECT * FROM ambience ";
		$limit;
		
		if (isset($array['query'])){
			$query = $array['query'];
			$ret .= "WHERE name LIKE '%".$query."%' ";
		}
		
		if (isset($array['limit'])){
			$limit = $array['limit'];
			if (isset($array['page'])){
				$page = $array['page'];
				$ret .= "LIMIT ".($limit*($page-1)).", ".$limit;
			} else {
				$ret .= "LIMIT 0, ".$limit;
			}
		} else {
			$ret .= "LIMIT 0, 10";
		}
		
		return $ret;
	}
	
	function getNumElements ($array){
		$ret = 0;
		$string  = "SELECT COUNT(*) AS 'count' FROM ambience ";
		
		if (isset($array['query'])){
			$query = $array['query'];
			$ret .= "WHERE name LIKE '%".$query."%' ";
		}
		$result = mysql_query($string);
		
		while ($row = mysql_fetch_object($result)){
			$ret = $row->count;
		}
			
		return $ret;
	}
?>