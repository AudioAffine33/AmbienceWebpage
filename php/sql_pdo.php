<?php
	function add_amb($infoArray, $userid){
		$format_id = get_format_id($infoArray);
		
		if (is_null($format_id)){
			$query = $db->prepare("INSERT INTO format (codec, bitdepth, samplerate, bitrate, channels) VALUES (:format, :bitdepth, :samplerate, :bitrate, :channels);");
			$query->bindValue(':format', $infoArray['format']);
			$query->bindValue(':samplerate', $infoArray['samplerate']);
			$query->bindValue(':bitrate', $infoArray['bitrate']);
			$query->bindValue(':channels', $infoArray['channels']);
			if (isset($infoArray['bits_per_sample'])){
				$query->bindValue(':bitdepth', $infoArray['formbitdepthat']);
			} else {
				$query->bindValue(':bitdepth', NULL);
			}
			
			$query->execute();
			
			$format_id = get_format_id($infoArray);
		}
		
		$ext = pathinfo($infoArray['filename'], PATHINFO_EXTENSION);	
		$ersetzen = array( 'ä' => 'ae', 'ö' => 'oe', 'ü' => 'ue', 'ß' => 'ss', ' ' => '_', '\\' => '-', '/' => '-', '|' => '-', '*' => '-' , ',' => '_');
		$nameNeu = strtr( strtolower( pathinfo($infoArray['filename'], PATHINFO_FILENAME) ), $ersetzen );
		$nameNeu = substr($nameNeu, 0, 40);
		$filename_neu = $nameNeu.".".$ext;
		
		$query = $db->prepare("INSERT INTO ambience (format_id, filename, size, length, user_id, date_added, name) VALUES (:format_id, :filename, :size, :length, :user_id, :date_added, :name);");
		$query->bindValue(':format_id', $format_id);
		$query->bindValue(':filename', $filename_neu);
		$query->bindValue(':size', $infoArray['filesize']);
		$query->bindValue(':length', $infoArray['length']);
		$query->bindValue(':user_id', $userid);
		$query->bindValue(':date_added', "CURDATE()");
		$query->bindValue(':name', substr($infoArray['filename'], 0, strrpos($infoArray['filename'])));		
		
		$query->execute();
		
		$amb_id = get_ambience_id($filename_neu);
		
		//Description
		$query = $db->prepare("IUPDATE ambience SET description=:descr, date=:date, time=:time, originator=:orig WHERE id=:id;");
		$query->bindValue(':id', $amb_id);
		
		if (isset($infoArray['riffDescr'])){
			$query->bindValue(':descr', $infoArray['riffDescr']);
		} else if (isset($infoArray['id3title'])){
			$query->bindValue(':descr', $infoArray['id3title']);
		} else {
			$query->bindValue(':descr', NULL);
		}
		
		//Date, Time
		if (isset($infoArray['date'])){
			$query->bindValue(':date', $infoArray['date']);
		} else {
			$query->bindValue(':date', NULL);
		}
		if (isset($infoArray['time'])){
			$query->bindValue(':time', $infoArray['time']);
		} else {
			$query->bindValue(':time', NULL);
		}
		
		//Originator
		if (isset($infoArray['orig'])){
			$query->bindValue(':orig', $infoArray['orig']);
		} else {
			$query->bindValue(':orig', NULL);
		}
		
		$query->execute();
		
		$ext = pathinfo($filename_neu, PATHINFO_EXTENSION);	
		$filename_neu = $amb_id."_".$filename_neu;
		$query = $db->prepare("UPDATE ambience SET filename=:filename_neu WHERE id=:id;");
		$query->bindValue(':filname_neu', $filename_neu);
		$query->bindValue(':id', $amb_id);
		
		$query->execute();
		
		
		return $filename_neu;
	}
	
	function addUser ($userArray){
		$array = checkUser($userArray);
		if ($array['new']){
			$query = $db->prepare("INSERT INTO user (name, pass, email, rights) VALUES (:regName, :regPass, :regMail, 'user');");
			$query->bindValue(':regName', $userArray['regName']);
			$query->bindValue(':regPass', md5($userArray['regPass1']));
			$query->bindValue(':regMail', $userArray['regMail']);
			
			$query->execute();
			return $array;
		}
		else {
			return $array;
		}
	}
	
	function checkUser ($userArray){
		$error = array ('new' => true);
		
		$query = $db->prepare("SELECT * FROM user WHERE name=:regName;");
		$query->bindValue(':regName'. $userArray['regName']);
		$query->execute();
		
		$numRows = $query->rowCount();
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
		
		$query = $db->prepare("SELECT * FROM user WHERE name=:loginName;");
		$query->bindValue(':loginName', $loginArray['loginName']);
		$query->execute();
		
		$numrows = $query->rowCount();
		if ($numrows < 1){
			$error['name'] = "Der Benutzername existiert nicht!";	
		} else {		
			while ($row = $query->fetch()){
				if (md5($loginArray['loginPass']) == $row['pass']){
					//session_start();
					$_SESSION['name'] = $row['name'];
					$_SESSION['id'] = $row['id'];
					$error['correct'] = true;
				} else {
					$error['pass'] = "Das angegebene Passwort ist nicht korrekt";
				}
			}
		}
		return $error;
	}
	
	function get_categories(){
		$ret = array();
		
		$query = $db->query("SELECT * FROM category;");		
		$query->execute();		
		while($row = $query->fetch()){
			$ret[$row['id']] = $row['name'];
		}
		
		return $ret;
	}
	
	function get_category_by_ID($id){
		
		$query = $db->prepare("SELECT * FROM category WHERE id=:id;");
		$query->bindValue(":id", $id);
		$query->execute();
		
		return $query->fetch();
	}
	
	function get_format_id($infoArray){
		$ret = NULL;
		
		$query  = $db->prepare("SELECT id FROM format WHERE codec=:format AND bitdepth=:bitdepth AND samplerate=:samplerate AND bitrate=:bitrate AND channels=:channels;");
		$query->bindValue(':format', $infoArray['format']);
		$query->bindValue(':samplerate', $infoArray['samplerate']);
		$query->bindValue(':bitrate', $infoArray['bitrate']);
		$query->bindValue(':channels', $infoArray['channels']);
		
		if (isset ($infoArray['bits_per_sample'])){
			$query->bindValue(':bitdepth', $infoArray['bits_per_sample']);
		} else {$query->bindValue(':bitdepth', NULL);}
		
		$query->execute();
		$result = $query->fetch();
		
		if (isset($result)){
   			$ret = $result['id'];
		}
		
		return $ret;
	}
	
	function get_location_id($infoArray){
		$ret = NULL;
		
		$query = $db->prepare('SELECT id FROM location WHERE name=:locName AND land=:locLand AND latitude=:locLat AND longitude=:locLat;');
		$query->bindValue(':locName', $infoArray['locName']);
		$query->bindValue(':locLand', $infoArray['locLand']);
		$query->bindValue(':locLat', $infoArray['locLat']);
		$query->bindValue(':locLat', $infoArray['locLat']);
		
		$query->execute();
		$result = $query->fetch();
		
		if (isset($result)){
   			$ret = $result['id'];
		}
		
		return $ret;
	}
	
		function getRandAmb ($number){
		$ret = array();
		
		$query = $db->prepare('SELECT * FROM ambience ORDER BY RAND() LIMIT :number;');
		$query->bindValue(':number', $number);
		$query->execute();
		
		return $query->fetchAll();
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
		
		if (isset($array['name'])){
			$name = $array['name'];
			$ret .= "WHERE name LIKE '%".$name."%' ";
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
		
		if (isset($array['name'])){
			$name = $array['name'];
			$string .= "WHERE name LIKE '%".$name."%' ";
		}
		$result = mysql_query($string);
		
		while ($row = mysql_fetch_object($result)){
			$ret = $row->count;
		}
			
		return $ret;
	}
?>