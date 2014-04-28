<?php

	function add_amb($infoArray, $userid){
        global $db;
		$format_id = get_format_id($infoArray);
		
		if (is_null($format_id)){
			$query = $db->prepare("INSERT INTO format (codec, bitdepth, samplerate, bitrate, channels) VALUES (:format, :bitdepth, :samplerate, :bitrate, :channels);");
			$query->bindValue(':format', $infoArray['format'], PDO::PARAM_STR);
			$query->bindValue(':samplerate', $infoArray['samplerate'], PDO::PARAM_INT);
			$query->bindValue(':bitrate', $infoArray['bitrate'], PDO::PARAM_INT);
			$query->bindValue(':channels', $infoArray['channels'], PDO::PARAM_INT);
			if (isset($infoArray['bits_per_sample'])){
				$query->bindValue(':bitdepth', $infoArray['formbitdepthat'], PDO::PARAM_INT);
			} else {
				$query->bindValue(':bitdepth', NULL, PDO::PARAM_NULL);
			}
			
			$query->execute();
			
			$format_id = get_format_id($infoArray);
		}
		
		$ext = pathinfo($infoArray['filename'], PATHINFO_EXTENSION);	
		$ersetzen = array( 'ä' => 'ae', 'ö' => 'oe', 'ü' => 'ue', 'ß' => 'ss', ' ' => '_', '\\' => '-', '/' => '-', '|' => '-', '*' => '-' , ',' => '_');
		$nameNeu = strtr( strtolower( pathinfo($infoArray['filename'], PATHINFO_FILENAME) ), $ersetzen );
		$nameNeu = substr($nameNeu, 0, 40);
		$filename_neu = $nameNeu.".".$ext;
		
		$query = $db->prepare("INSERT INTO ambience (format_id, filename, size, length, user_id, date_added, name) VALUES (:format_id, :filename, :size, :length, :user_id, :date_added, :nam);");
		$query->bindValue(':format_id', $format_id, PDO::PARAM_INT);
		$query->bindValue(':filename', $filename_neu, PDO::PARAM_STR);
		$query->bindValue(':size', $infoArray['filesize'], PDO::PARAM_INT);
		$query->bindValue(':length', $infoArray['length'], PDO::PARAM_INT);
		$query->bindValue(':user_id', $userid, PDO::PARAM_INT);
		$query->bindValue(':date_added', "CURDATE()", PDO::PARAM_STR);
		$query->bindValue(':nam', (substr($infoArray['filename'], 0, strrpos($infoArray['filename'], "."))));
		
		$query->execute();
		
		$amb_id = get_ambience_id($filename_neu);
		
		//Description
		$query = $db->prepare("IUPDATE ambience SET description=:descr, date=:date, time=:time, originator=:orig WHERE id=:id;");
		$query->bindValue(':id', $amb_id, PDO::PARAM_INT);
		
		if (isset($infoArray['riffDescr'])){
			$query->bindValue(':descr', $infoArray['riffDescr'], PDO::PARAM_STR);
		} else if (isset($infoArray['id3title'])){
			$query->bindValue(':descr', $infoArray['id3title'], PDO::PARAM_STR);
		} else {
			$query->bindValue(':descr', NULL, PDO::PARAM_NULL);
		}
		
		//Date, Time
		if (isset($infoArray['date'])){
			$query->bindValue(':date', $infoArray['date'], PDO::PARAM_STR);
		} else {
			$query->bindValue(':date', NULL, PDO::PARAM_NULL);
		}
		if (isset($infoArray['time'])){
			$query->bindValue(':time', $infoArray['time'], PDO::PARAM_STR);
		} else {
			$query->bindValue(':time', NULL, PDO::PARAM_NULL);
		}
		
		//Originator
		if (isset($infoArray['orig'])){
			$query->bindValue(':orig', $infoArray['orig'], PDO::PARAM_STR);
		} else {
			$query->bindValue(':orig', NULL, PDO::PARAM_NULL);
		}
		
		$query->execute();

		$filename_neu = $amb_id."_".$filename_neu;
		$query = $db->prepare("UPDATE ambience SET filename=:filename_neu WHERE id=:id;");
		$query->bindValue(':filname_neu', $filename_neu, PDO::PARAM_STR);
		$query->bindValue(':id', $amb_id, PDO::PARAM_INT);
		
		$query->execute();
		
		
		return $filename_neu;
	}
	
	function addUser ($userArray){
        global $db;
		$array = checkUser($userArray);
		if ($array['new']){
			$query = $db->prepare("INSERT INTO user (name, pass, email, rights) VALUES (:regName, :regPass, :regMail, 'user');");
			$query->bindValue(':regName', $userArray['regName'], PDO::PARAM_STR);
			$query->bindValue(':regPass', md5($userArray['regPass1']), PDO::PARAM_STR);
			$query->bindValue(':regMail', $userArray['regMail'], PDO::PARAM_STR);
			
			$query->execute();
			return $array;
		}
		else {
			return $array;
		}
	}
	
	function checkUser ($userArray){
        global $db;
		$error = array ('new' => true);
		
		$query = $db->prepare("SELECT * FROM user WHERE name=:regName;");
		$query->bindValue(':regName', $userArray['regName'], PDO::PARAM_STR);
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
        global $db;
		$error = array();
		$error['correct'] = false;
		
		$query = $db->prepare("SELECT * FROM user WHERE name=:loginName;");
		$query->bindValue(':loginName', $loginArray['loginName'], PDO::PARAM_STR);
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
        global $db;
		$ret = array();
		
		$query = $db->query("SELECT * FROM category;");		
		$query->execute();		
		while($row = $query->fetch()){
			$ret[$row['id']] = $row['name'];
		}
		
		return $ret;
	}
	
	function get_category_by_ID($id){
        global $db;
		$query = $db->prepare("SELECT * FROM category WHERE id=:id;");
		$query->bindValue(":id", $id, PDO::PARAM_INT);
		$query->execute();
		
		return $query->fetch();
	}
	
	function get_format_id($infoArray){
        global $db;
		$ret = NULL;
		
		$query  = $db->prepare("SELECT id FROM format WHERE codec=:format AND bitdepth=:bitdepth AND samplerate=:samplerate AND bitrate=:bitrate AND channels=:channels;");
		$query->bindValue(':format', $infoArray['format'], PDO::PARAM_STR);
		$query->bindValue(':samplerate', $infoArray['samplerate'], PDO::PARAM_INT);
		$query->bindValue(':bitrate', $infoArray['bitrate'], PDO::PARAM_INT);
		$query->bindValue(':channels', $infoArray['channels'], PDO::PARAM_INT);
		
		if (isset ($infoArray['bits_per_sample'])){
			$query->bindValue(':bitdepth', $infoArray['bits_per_sample'], PDO::PARAM_INT);
		} else {$query->bindValue(':bitdepth', NULL);}
		
		$query->execute();
		$result = $query->fetch();
		
		if (isset($result)){
   			$ret = $result['id'];
		}
		
		return $ret;
	}
	
	function get_location_id($infoArray){
        global $db;
		$ret = NULL;
		
		$query = $db->prepare('SELECT id FROM location WHERE name=:locName AND land=:locLand AND latitude=:locLat AND longitude=:locLat;');
		$query->bindValue(':locName', $infoArray['locName'], PDO::PARAM_STR);
		$query->bindValue(':locLand', $infoArray['locLand'], PDO::PARAM_STR);
		$query->bindValue(':locLat', $infoArray['locLat'], PDO::PARAM_STR);
		$query->bindValue(':locLat', $infoArray['locLat'], PDO::PARAM_STR);
		
		$query->execute();
		$result = $query->fetch();
		
		if (isset($result)){
   			$ret = $result['id'];
		}
		
		return $ret;
	}
	
	function getRandAmb ($number){
        global $db;
		
		$query = $db->prepare('SELECT * FROM ambience ORDER BY RAND() LIMIT :number;');
		$query->bindValue(':number', $number, PDO::PARAM_INT);
		$query->execute();
		
		return $query->fetchAll();
	}

	
	function get_ambience_id($filename){
        global $db;
		$ret = NULL;

        $query = $db->prepare("SELECT id FROM ambience WHERE filename=:filename;");
        $query->bindValue(':filename', $filename, PDO::PARAM_STR);
		$query->execute();
		
		while($row = $query->fetch()){
   				$ret = $row['id'];
  		}
		
		return $ret;
	}
	
	function get_ambience_by_ID($id){
        global $db;

        $query = $db->prepare("SELECT * FROM ambience WHERE id =:id;");
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();

        $result = $query->fetch();
		
		return $result;
	}
	
	function getUser_by_ID ($id){
        global $db;

        $query = $db->prepare("SELECT * FROM user WHERE id =:id;");
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
		
		$result = $query->fetch();;
		
		return $result;
	}
	
	function getFormat_by_ID ($id){
        global $db;

        $query = $db->prepare("SELECT * FROM format WHERE id =:id;");
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();

        $result = $query->fetch();;

        return $result;
	}
	
	function getLocation_by_ID ($id){
        global $db;

        $query = $db->prepare("SELECT * FROM location WHERE id =:id;");
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();

        $result = $query->fetch();;

        return $result;
	}
	
	function setPic ($file, $amb_id){
        global $db;
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

            $query = $db->prepare("UPDATE ambience SET picture=:pic WHERE id=:id;");
            $query->bindValue(':id', $amb_id, PDO::PARAM_INT);
            $query->bindValue(':pic', $filename_neu, PDO::PARAM_STR);
            $query->execute();

			upload_pic_amb($file, $filename_neu);
		}
	}
	
	function set_ambience_details($details_array, $amb_id){
        global $db;

        $query = $db->prepare("UPDATE ambience SET name=:name, location_id=:locID, date=:date, time=:time, description=:descr, category_id=:cat_id WHERE id=:amb_id;");
        $query->bindValue(':name', $details_array['name'], PDO::PARAM_STR);
        $query->bindValue(':id', $amb_id, PDO::PARAM_INT);
		
		if ($details_array['locName'] != NULL && $details_array['locName'] != ""){
			$locID = get_location_id($details_array);
			if (!isset($locID)){
                $query2 = $db->prepare("INSERT INTO location (name, land, latitude, longitude) VALUES (:locName, :locLand, :locLat, :locLng);");
                $query2->bindValue(':locName', $details_array['locName'], PDO::PARAM_STR);
                $query2->bindValue(':locLand', $details_array['locLand'], PDO::PARAM_STR);
                $query2->bindValue(':locLat', $details_array['locLat']);
                $query2->bindValue(':locLng', $details_array['locLng']);

                $query2->execute();
				
				$locID = get_location_id($details_array);
			}
            $query->bindValue(':locID', $locID, PDO::PARAM_INT);
		} else {
            $query->bindValue(':locID', NULL, PDO::PARAM_NULL);
        }
		
		if ($details_array['date'] != NULL && $details_array['date'] != ""){
            $query->bindValue(':date', $details_array['date'], PDO::PARAM_STR);
        } else {
            $query->bindValue(':date', NULL, PDO::PARAM_NULL);
		}
		
		if ($details_array['time'] != NULL && $details_array['time'] != ""){
            $query->bindValue(':time', $details_array['time'], PDO::PARAM_STR);
		} else {
            $query->bindValue(':time', NULL, PDO::PARAM_NULL);
        }
		
		if ($details_array['description'] != NULL && $details_array['description'] != ""){
            $query->bindValue(':descr', $details_array['description'], PDO::PARAM_STR);
		} else {
            $query->bindValue(':descr', NULL, PDO::PARAM_NULL);
        }
		
		if ($details_array['category'] != NULL && $details_array['category'] != ""){
            $query->bindValue(':cat_id', $details_array['category'], PDO::PARAM_INT);
		} else {
            $query->bindValue(':cat_id', NULL, PDO::PARAM_NULL);
        }

        $query->execute();
	}
	
	function update_amb($ambID, $updateArray){
        global $db;
		$ambArray = get_ambience_by_ID($ambID);
		if ($updateArray != ""){
			$locID = get_location_id($updateArray);
		}
		if (check_detail_Input($updateArray)){
			if ($updateArray['name'] != $ambArray['name']){
                $query = $db->prepare("UPDATE ambience SET name=:name WHERE id=:id;");
				$query->bindValue(":name", $updateArray['name'], PDO::PARAM_STR);
                $query->bindValue(":id", $ambID, PDO::PARAM_INT);
                $query->execute();
			}
			if ($updateArray['category'] != $ambArray['category_id']){
                $query = $db->prepare("UPDATE ambience SET category_id=:cat WHERE id=:id;");
                $query->bindValue(":cat", $updateArray['category'], PDO::PARAM_INT);
                $query->bindValue(":id", $ambID, PDO::PARAM_INT);
                $query->execute();
			}
			if (!isset($locID) || $locID != $ambArray['location_id']){
				if (!isset($locID)){
                    $query = $db->prepare("INSERT INTO location (name, land, latitude, longitude) VALUES (:locName, :locLand, :locLat, :locLng);");
                    $query->bindValue(':locName', $updateArray['locName'], PDO::PARAM_STR);
                    $query->bindValue(':locLand', $updateArray['locLand'], PDO::PARAM_STR);
                    $query->bindValue(':locLat', $updateArray['locLat']);
                    $query->bindValue(':locLng', $updateArray['locLng']);
					
					$locID = get_location_id($updateArray);
				}
                $query = $db->prepare("UPDATE ambience SET location_id=:loc WHERE id=:id;");
                $query->bindValue(":loc", $locID, PDO::PARAM_INT);
                $query->bindValue(":id", $ambID, PDO::PARAM_INT);
                $query->execute();
			}
			if ($updateArray['date'] != $ambArray['date']){
                $query = $db->prepare("UPDATE ambience SET date=:date WHERE id=:id;");
                $query->bindValue(":date", $updateArray['date'], PDO::PARAM_STR);
                $query->bindValue(":id", $ambID, PDO::PARAM_INT);
                $query->execute();
			}
			if ($updateArray['time'] != $ambArray['time']){
                $query = $db->prepare("UPDATE ambience SET time=:time WHERE id=:id;");
                $query->bindValue(":time", $updateArray['time'], PDO::PARAM_STR);
                $query->bindValue(":id", $ambID, PDO::PARAM_INT);
                $query->execute();
			}
			if ($updateArray['description'] != $ambArray['description']){
                $query = $db->prepare("UPDATE ambience SET description=:descr WHERE id=:id;");
                $query->bindValue(":descr", $updateArray['description'], PDO::PARAM_STR);
                $query->bindValue(":id", $ambID, PDO::PARAM_INT);
                $query->execute();
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
        global $db;

        $query = $db->prepare("DELETE FROM ambience WHERE id =:id;");
        $query->bindValue(":id", $id, PDO::PARAM_INT);
        $query->execute();

        $query = $db->prepare("DELETE FROM rating WHERE ambience_id =:id;");
        $query->bindValue(":id", $id, PDO::PARAM_INT);
        $query->execute();

        $query = $db->prepare("DELETE FROM comment WHERE ambience_id =:id;");
        $query->bindValue(":id", $id, PDO::PARAM_INT);
        $query->execute();

        $query = $db->prepare("DELETE FROM report WHERE gemeldet_ambience_id =:id;");
        $query->bindValue(":id", $id, PDO::PARAM_INT);
        $query->execute();
	}
	
	function createSearch ($array){
        global $db;
		
		if (isset($array['limit'])){
			$limit = $array['limit'];
			if (isset($array['page'])){
				$page = $array['page'];
                $start = ($limit*($page-1));
			} else {
                $start = 0;
			}
		} else {
            $start = 0;
            $limit = 10;
		}

        $qr_string = "SELECT * FROM ambience WHERE name LIKE :name LIMIT ".$start.",".$limit.";";

        $query = $db->prepare($qr_string);

        if (isset($array['name'])){
            $query->bindValue(':name', '%'.$array['name'].'%', PDO::PARAM_STR);
        } else {
            $query->bindValue(':name', "%%", PDO::PARAM_STR);
        }

        return $query;
	}
	
	function getNumElements ($array){
        global $db;
        $ret=0;
        $query = $db->prepare("SELECT COUNT(*) AS 'count' FROM ambience WHERE name LIKE :name ");
		
		if (isset($array['name'])){
            $query->bindValue(':name', '%'.$array['name'].'%', PDO::PARAM_STR);
		} else {
            $query->bindValue(':name', '%%', PDO::PARAM_STR);
        }
		$query->execute();
		
		while ($row = $query->fetch()){
			$ret = $row['count'];
		}
			
		return $ret;
	}
?>