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
				$query->bindValue(':bitdepth', $infoArray['bitdepth'], PDO::PARAM_INT);
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
		
		$query = $db->prepare("INSERT INTO ambience (format_id, filename, size, length, user_id, date_added, name) VALUES (:format_id, :filename, :size, :length, :user_id, CURDATE(), :nam);");
		$query->bindValue(':format_id', $format_id, PDO::PARAM_INT);
		$query->bindValue(':filename', $filename_neu, PDO::PARAM_STR);
		$query->bindValue(':size', $infoArray['filesize'], PDO::PARAM_INT);
		$query->bindValue(':length', $infoArray['length'], PDO::PARAM_INT);
		$query->bindValue(':user_id', $userid, PDO::PARAM_INT);
		$query->bindValue(':nam', (substr($infoArray['filename'], 0, strrpos($infoArray['filename'], "."))));
		
		$query->execute();
		
		$amb_id = get_ambience_id($filename_neu);

		//Description
		$query = $db->prepare("UPDATE ambience SET description=:descr, date=:date, time=:time, originator=:orig WHERE id=:id;");
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
			$query->bindValue(':date', $infoArray['date']);
		} else {
			$query->bindValue(':date', NULL, PDO::PARAM_NULL);
		}
		if (isset($infoArray['time'])){
			$query->bindValue(':time', $infoArray['time']);
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

        //Loc-Dummy
        $query = $db->prepare("UPDATE ambience SET location_id=1 WHERE id=:id;");
        $query->bindValue(':id', $amb_id, PDO::PARAM_INT);
        $query->execute();

        //Cat-Dummy
        $query = $db->prepare("UPDATE ambience SET category_id=0 WHERE id=:id;");
        $query->bindValue(':id', $amb_id, PDO::PARAM_INT);
        $query->execute();

		$filename_neu = $amb_id."_".$filename_neu;
		$query = $db->prepare("UPDATE ambience SET filename=:filename_neu WHERE id=:id;");
		$query->bindValue(':filename_neu', $filename_neu, PDO::PARAM_STR);
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
		if (strlen($userArray['regName']) < 2){
			$error['name'] = "Der Benutzername muss mindestens 2 Zeichen lang sein.";
			$error['new'] = false;
		}
        if (strlen($userArray['regName']) > 20){
            $error['name'] = "Der Benutzername darf maximal 20 Zeichen lang sein.";
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
        if (strlen($userArray['regPass1']) < 5 || strlen($userArray['regPass2']) < 5){
            $error['pass'] = "Das Passwort muss mindestens 5 Zeichen lang sein";
            $error['new'] = false;
        }
        if (strlen($userArray['regPass1']) > 20 || strlen($userArray['regPass2']) > 20){
            $error['pass'] = "Das Passwort darf höchstens 20 Zeichen lang sein";
            $error['new'] = false;
        }
        if (is_numeric(strpos($userArray['regPass1'], "1234")) || is_numeric(strpos($userArray['regPass2'], "1234"))){
            $error['pass'] = "Das Passwort darf die Zeichenkette '1234' nicht enthalten";
            $error['new'] = false;
        }
        if (is_numeric(strpos($userArray['regPass1'], "passwort")) || is_numeric(strpos($userArray['regPass2'], "passwort"))){
            $error['pass'] = "Das Passwort darf die Zeichenkette 'passwort' nicht enthalten";
            $error['new'] = false;
        }
		if ($userArray['regMail'] == ""){
			$error['mail'] = "E-Mail-Addresse nicht korrekt<br />";
			$error['new'] = false;
		}
		
		return $error;
	}



    function checkPWChange ($pwArray){
        global $db;

        $error=array();

        $query = $db->prepare("SELECT * FROM user WHERE id=:id;");
        $query->bindValue(':id', $_SESSION['id'], PDO::PARAM_STR);
        $query->execute();

        $result = $query->fetch();


        if (md5($pwArray['oldPass']) != $result['pass']){
            $error['passOld'] = "Passwort ist nicht korrekt<br /> ";
        }

        if ($pwArray['newPass1'] != $pwArray['newPass2']){
            $error['passNew'] = "Passwörter stimmen nicht überein<br /> ";
        }
        if ($pwArray['newPass1'] == "" || $pwArray['newPass2'] == ""){
            $error['passNew'] = "Bitte geben Sie ein Passwort an!<br /> ";
        }
        if (strlen($pwArray['newPass1']) < 5 || strlen($pwArray['newPass2']) < 5){
            $error['passNew'] = "Das Passwort muss mindestens 5 Zeichen lang sein";
            $error['new'] = false;
        }
        if (strlen($pwArray['newPass1']) > 20 || strlen($pwArray['newPass2']) > 20){
            $error['passNew'] = "Das Passwort darf höchstens 20 Zeichen lang sein";
            $error['new'] = false;
        }
        if (is_numeric(strpos($pwArray['newPass1'], "1234")) || is_numeric(strpos($pwArray['newPass2'], "1234"))){
            $error['passNew'] = "Das Passwort darf die Zeichenkette '1234' nicht enthalten";
            $error['new'] = false;
        }
        if (is_numeric(strpos($pwArray['newPass1'], "passwort")) || is_numeric(strpos($pwArray['newPass2'], "passwort"))){
            $error['passNew'] = "Das Passwort darf die Zeichenkette 'passwort' nicht enthalten";
            $error['new'] = false;
        }

        return $error;
    }

    function checkPW ($pw){
        global $db;

        $error=array();

        $query = $db->prepare("SELECT * FROM user WHERE id=:id;");
        $query->bindValue(':id', $_SESSION['id'], PDO::PARAM_STR);
        $query->execute();

        $result = $query->fetch();


        if (md5($pw) != $result['pass']){
            $error['pass'] = "Passwort ist nicht korrekt<br /> ";
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
                    $_SESSION['rights'] = $row['rights'];
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
            if ($row['name'] != ""){
			    $ret[$row['id']] = $row['name'];
            }
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
		
		$query = $db->prepare('SELECT id FROM location WHERE name=:locName AND land=:locLand AND latitude=:locLat AND longitude=:locLng;');
		$query->bindValue(':locName', $infoArray['locName'], PDO::PARAM_STR);
		$query->bindValue(':locLand', $infoArray['locLand'], PDO::PARAM_STR);
		$query->bindValue(':locLat', $infoArray['locLat']);
		$query->bindValue(':locLng', $infoArray['locLng']);

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

    function get_ambience_by_user($array){
        global $db;

        $limit = 6;
        if (isset($array['page'])){
            $page = $array['page'];
            $start = ($limit*($page-1));
        } else {
            $start = 0;
        }

        $string = "SELECT * FROM ambience WHERE user_id = :user_id ORDER BY date_added DESC LIMIT ".$start.", 6;";

        $query = $db->prepare($string);
        $query->bindValue(':user_id', $array['id'], PDO::PARAM_INT);
        $query->execute();

        $result = $query->fetchAll();

        return $result;
    }

    function get_ambience_by_user_noLim($array){
            global $db;

            $limit = 5;
            if (isset($array['page'])){
                $page = $array['page'];
                $start = ($limit*($page-1));
            } else {
                $start = 0;
            }

            $string = "SELECT * FROM ambience WHERE user_id = :user_id ORDER BY date_added DESC";

            $query = $db->prepare($string);
            $query->bindValue(':user_id', $array['id'], PDO::PARAM_INT);
            $query->execute();

            $result = $query->fetchAll();

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

    function get_user_by_ID($id){
        global $db;

        $query = $db->prepare("SELECT * FROM user WHERE id =:id;");
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();

        $result = $query->fetch();;

        return $result;
    }

    function get_rating($user, $ambience){
        global $db;

        $query = $db->prepare("SELECT * FROM rating WHERE user_id =:user_id AND ambience_id = :amb_id;");
        $query->bindValue(':user_id', $user['id'], PDO::PARAM_INT);
        $query->bindValue(':amb_id', $ambience['id'], PDO::PARAM_INT);
        $query->execute();

        $result = $query->fetch();;

        return $result['rating'];
    }

    function getAverageRating($ambience){
        global $db;

        $query = $db->prepare("SELECT AVG(rating) AS 'average' FROM rating WHERE ambience_id = :amb_id;");
        $query->bindValue(':amb_id', $ambience['id'], PDO::PARAM_INT);
        $query->execute();

        $result = $query->fetch();;

        return $result['average'];
    }

    function getAverageUserRating($user){
        global $db;

        $query = $db->prepare("SELECT AVG(rating.rating) AS 'average' FROM rating JOIN ambience ON rating.ambience_id = ambience.id WHERE ambience.user_id=:user_id;");
        $query->bindValue(':user_id', $user['id'], PDO::PARAM_INT);
        $query->execute();

        $result = $query->fetch();;

        return $result['average'];
    }
	
	function setPic ($file, $amb_id){
    global $db;
    $check = getimagesize($file['tmp_name']);
    if (!$check){
        throw new Exception('Kein gültiges Bild');
    } else {
        $amb = get_ambience_by_ID($amb_id);
        if ($amb['picture'] != ""){
            unlink(getRoot()."\\media\\pics_ambiences\\".$amb['picture']);
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

function setUserPic ($file, $user_id){
    global $db;
    $check = getimagesize($file['tmp_name']);
    if (!$check){
        throw new Exception('Kein gültiges Bild');
    } else {
        $user = get_user_by_ID($user_id);
        if ($user['picture'] != ""){
            unlink(getRoot()."\\media\\pics_user\\".$user['picture']);
        }
        $userName = $_SESSION['name'];
        $filename_pic = basename($file['name']);
        $arr_pic = explode(".", $filename_pic);
        $filename_neu = $_SESSION['id']."_".$userName.".".$arr_pic[1];

        $query = $db->prepare("UPDATE user SET picture=:pic WHERE id=:id;");
        $query->bindValue(':id', $user_id, PDO::PARAM_INT);
        $query->bindValue(':pic', $filename_neu, PDO::PARAM_STR);
        $query->execute();

        upload_pic_user($file, $filename_neu);
    }
}

    function setUserPass($pass){
        global $db;

        $pass_new = md5($pass);

        $query = $db->prepare("UPDATE user SET pass = :pass WHERE id = :id;");
        $query->bindValue(":pass", $pass_new, PDO::PARAM_STR);
        $query->bindValue(":id", $_SESSION['id'], PDO::PARAM_INT);
        $query->execute();
    }

    function setUserMail($mail){
        global $db;

        $query = $db->prepare("UPDATE user SET email = :mail WHERE id = :id;");
        $query->bindValue(":mail", $mail, PDO::PARAM_STR);
        $query->bindValue(":id", $_SESSION['id'], PDO::PARAM_INT);
        $query->execute();
    }

    function setUserAbout($about){
        global $db;

        $query = $db->prepare("UPDATE user SET about = :about WHERE id = :id;");
        $query->bindValue(":about", $about, PDO::PARAM_STR);
        $query->bindValue(":id", $_SESSION['id'], PDO::PARAM_INT);
        $query->execute();
    }

    function setUserShowMail($bool){
        global $db;

        $query = $db->prepare("UPDATE user SET emailShown = :bool WHERE id = :id;");
        $query->bindValue(":bool", $bool, PDO::PARAM_BOOL);
        $query->bindValue(":id", $_SESSION['id'], PDO::PARAM_INT);
        $query->execute();
    }
	
	function set_ambience_details($details_array, $amb_id){
        global $db;

        if ($details_array['name'] != NULL && $details_array['name'] != ""){
            $query = $db->prepare("UPDATE ambience SET name=:nam WHERE id=:amb_id;");
            $query->bindValue(':nam', $details_array['name'], PDO::PARAM_STR);
            $query->bindValue(':amb_id', $amb_id, PDO::PARAM_INT);
            $query->execute();
        }

        if ($details_array['locName'] != NULL && $details_array['locName'] != ""){
            //print_r( $details_array);
            $locID = get_location_id($details_array);
            if (!isset($locID)){
                global $CONTINENTS;
                global $COUNTRY_CONTINENTS;

                $continent = $CONTINENTS[$COUNTRY_CONTINENTS[$details_array['countryCode']]];

                $query2 = $db->prepare("INSERT INTO location (name, land, latitude, longitude, countrycode, continent) VALUES (:locName, :locLand, :locLat, :locLng, :cc, :continent);");
                $query2->bindValue(':locName', $details_array['locName'], PDO::PARAM_STR);
                $query2->bindValue(':locLand', $details_array['locLand'], PDO::PARAM_STR);
                $query2->bindValue(':cc', $details_array['countryCode'], PDO::PARAM_STR);
                $query2->bindValue(':continent', $continent, PDO::PARAM_STR);
                $query2->bindValue(':locLat', $details_array['locLat']);
                $query2->bindValue(':locLng', $details_array['locLng']);
                $query2->execute();

                $locID = get_location_id($details_array);
            }
            echo $locID;
            $query = $db->prepare("UPDATE ambience SET location_id=:locID WHERE id=:amb_id;");
            $query->bindValue(':locID', $locID, PDO::PARAM_INT);
            $query->bindValue(':amb_id', $amb_id, PDO::PARAM_INT);
            $query->execute();
        }

        if ($details_array['date'] != NULL && $details_array['date'] != ""){
            $query = $db->prepare("UPDATE ambience SET date=:date WHERE id=:amb_id;");
            $query->bindValue(':date', $details_array['date'], PDO::PARAM_STR);
            $query->bindValue(':amb_id', $amb_id, PDO::PARAM_INT);
            $query->execute();
        }

        if ($details_array['time'] != NULL && $details_array['time'] != ""){
            $query = $db->prepare("UPDATE ambience SET time=:time WHERE id=:amb_id;");
            $query->bindValue(':time', $details_array['time'], PDO::PARAM_STR);
            $query->bindValue(':amb_id', $amb_id, PDO::PARAM_INT);
            $query->execute();

        }

        if ($details_array['description'] != NULL && $details_array['description'] != ""){
            $query = $db->prepare("UPDATE ambience SET description=:descr WHERE id=:amb_id;");
            $query->bindValue(':descr', $details_array['description'], PDO::PARAM_STR);
            $query->bindValue(':amb_id', $amb_id, PDO::PARAM_INT);
            $query->execute();
        }

        if ($details_array['category'] != NULL && $details_array['category'] != ""){
            $query = $db->prepare("UPDATE ambience SET category_id=:cat_id WHERE id=:amb_id;");
            $query->bindValue(':cat_id', $details_array['category'], PDO::PARAM_STR);
            $query->bindValue(':amb_id', $amb_id, PDO::PARAM_INT);
            $query->execute();
        }
	}
	
	function update_amb($ambID, $updateArray){
        global $db;
		$ambArray = get_ambience_by_ID($ambID);
		if (isset($updateArray["locName"]) && $updateArray["locName"] != ""){
			$locID = get_location_id($updateArray);
		}
        if (isset($updateArray["name"]) && $updateArray['name'] != $ambArray['name']){
            $query = $db->prepare("UPDATE ambience SET name=:name WHERE id=:id;");
            $query->bindValue(":name", $updateArray['name'], PDO::PARAM_STR);
            $query->bindValue(":id", $ambID, PDO::PARAM_INT);
            $query->execute();
        }
        if (isset($updateArray["category"]) && $updateArray['category'] != $ambArray['category_id']){
            $query = $db->prepare("UPDATE ambience SET category_id=:cat WHERE id=:id;");
            $query->bindValue(":cat", $updateArray['category'], PDO::PARAM_INT);
            $query->bindValue(":id", $ambID, PDO::PARAM_INT);
            $query->execute();
        }
        if (isset($updateArray["locName"]) && (!isset($locID) || $locID != $ambArray['location_id'])){
            if (!isset($locID)){
                $updateArray['locLat'] = round($updateArray['locLat'], 14);
                $updateArray['locLng'] = round($updateArray['locLng'], 14);

                global $CONTINENTS;
                global $COUNTRY_CONTINENTS;

                $continent = $CONTINENTS[$COUNTRY_CONTINENTS[$updateArray['countryCode']]];

                $query = $db->prepare("INSERT INTO location (name, land, latitude, longitude, countrycode, continent) VALUES (:locName, :locLand, :locLat, :locLng, :cc, :continent);");
                $query->bindValue(':locName', $updateArray['locName'], PDO::PARAM_STR);
                $query->bindValue(':locLand', $updateArray['locLand'], PDO::PARAM_STR);
                $query->bindValue(':cc', $updateArray['countryCode'], PDO::PARAM_STR);
                $query->bindValue(':continent', $continent, PDO::PARAM_STR);
                $query->bindValue(':locLat', $updateArray['locLat']);
                $query->bindValue(':locLng', $updateArray['locLng']);
                $query->execute();

                $locID = get_location_id($updateArray);
            }
            $query = $db->prepare("UPDATE ambience SET location_id=:loc WHERE id=:id;");
            $query->bindValue(":loc", $locID, PDO::PARAM_INT);
            $query->bindValue(":id", $ambID, PDO::PARAM_INT);
            $query->execute();
        }
        if (isset($updateArray["date"]) && $updateArray['date'] != $ambArray['date']){
            $query = $db->prepare("UPDATE ambience SET date=:date WHERE id=:id;");
            $query->bindValue(":date", $updateArray['date']);
            $query->bindValue(":id", $ambID, PDO::PARAM_INT);
            $query->execute();
        }
        if (isset($updateArray["time"]) &&$updateArray['time'] != $ambArray['time']){
            $query = $db->prepare("UPDATE ambience SET time=:time WHERE id=:id;");
            $query->bindValue(":time", $updateArray['time']);
            $query->bindValue(":id", $ambID, PDO::PARAM_INT);
            $query->execute();
        }
        if (isset($updateArray["description"]) &&$updateArray['description'] != $ambArray['description']){
            $query = $db->prepare("UPDATE ambience SET description=:descr WHERE id=:id;");
            $query->bindValue(":descr", $updateArray['description'], PDO::PARAM_STR);
            $query->bindValue(":id", $ambID, PDO::PARAM_INT);
            $query->execute();
        }
	}

    function rate($user, $amb, $rating){
        global $db;

        if(get_rating($user, $amb) != "" && get_rating($user, $amb) != NULL){
            $query = $db->prepare("UPDATE rating SET rating=:rating WHERE user_id=:user_id AND ambience_id = :amb_id;");
            $query->bindValue(":rating", $rating, PDO::PARAM_INT);
            $query->bindValue(":user_id", $user['id'], PDO::PARAM_INT);
            $query->bindValue(":amb_id", $amb['id'], PDO::PARAM_INT);
            $query->execute();
        } else {
            $query = $db->prepare("INSERT INTO rating (user_id, ambience_id, rating) VALUES (:user_id, :amb_id, :rating);");
            $query->bindValue(":rating", $rating, PDO::PARAM_INT);
            $query->bindValue(":user_id", $user['id'], PDO::PARAM_INT);
            $query->bindValue(":amb_id", $amb['id'], PDO::PARAM_INT);
            $query->execute();
        }

        $avgRating = round(getAverageRating($amb), 10);

        $query = $db->prepare("UPDATE ambience SET rating=:avgRating WHERE id=:amb_id;");
        $query->bindValue(":avgRating", $avgRating, PDO::PARAM_STR);
        $query->bindValue(":amb_id", $amb['id'], PDO::PARAM_INT);
        $query->execute();
    }
	
	//check Inputs
	function check_detail_Input($detail_array){
        $error = array();
        $error['correct'] = true;
		
		if ($detail_array['name'] == NULL || $detail_array['name'] == ""){
            $error['name'] = "Es muss ein Name angegeben werden.";
            $error['correct'] = false;
		}
        if (strlen($detail_array['name']) > 80){
            $error['name'] = "Der Name darf nicht länger als 80 Zeichen sein.";
            $error['correct'] = false;
        }
        if (strlen($detail_array['description']) > 500){
            $error['descr'] = "Die Beschreibung darf nicht länger als 500 Zeichen sein.";
            $error['correct'] = false;
        }

		return $error;
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

    function deleteUserFromDB($id){
        global $db;

        $query = $db->prepare("DELETE FROM user WHERE id =:id;");
        $query->bindValue(":id", $id, PDO::PARAM_INT);
        $query->execute();

        $query = $db->prepare("DELETE FROM rating WHERE user_id =:id;");
        $query->bindValue(":id", $id, PDO::PARAM_INT);
        $query->execute();

        $query = $db->prepare("DELETE FROM comment WHERE user_id =:id;");
        $query->bindValue(":id", $id, PDO::PARAM_INT);
        $query->execute();

        $query = $db->prepare("DELETE FROM report WHERE gemeldet_user_id =:id;");
        $query->bindValue(":id", $id, PDO::PARAM_INT);
        $query->execute();

        $query = $db->prepare("DELETE FROM report WHERE melder_id =:id;");
        $query->bindValue(":id", $id, PDO::PARAM_INT);
        $query->execute();
    }
	
	function createSearch ($array){
        global $db;
        global $CONTINENTS;

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

        //Abfrage nach gesetztem Kategorie-Filter und String-Explode
        if (isset($array['cat']) && $array['cat'] != ""){
            $strCat = $array['cat'];
            $arrayCat = explode('-', $strCat);
        }

        //Abfrage nach gesetztem Kontinent-Filter und String-Explode
        if (isset($array['cont']) && $array['cont'] != ""){
            $strCont = $array['cont'];
            $arrayContCode = explode('-', $strCont);
            $arrayCont = array();
            $index = 0;
            foreach ($arrayContCode as $contCode){
                $arrayCont[$index] = $CONTINENTS[$contCode];
                $index ++;
            }
        }
        //Abfrage nach gesetztem Format-Filter und String-Explode
        if (isset($array['cdc']) && $array['cdc'] != ""){
            $strCdc = $array['cdc'];
            $arrayCdc = explode('-', $strCdc);
        }
        //Abfrage nach gesetztem Auflösungs-Filter und String-Explode
        if (isset($array['bd']) && $array['bd'] != ""){
            $strBd = $array['bd'];
            $arrayBd = explode('-', $strBd);
        }
        //Abfrage nach gesetztem Abstast-Filter und String-Explode
        if (isset($array['sf']) && $array['sf'] != ""){
            $strFreq = $array['sf'];
            $arrayFreq = explode('-', $strFreq);
        }

        //Abfrage aufbauen und durchführen
        $qr_string = "SELECT ".globalAliasString()." FROM ambience ";
        $qr_string.= "JOIN location ON ambience.location_id = location.id ";
        $qr_string.= "JOIN format  ON ambience.format_id = format.id ";
        $qr_string.= "JOIN category  ON ambience.category_id = category.id ";
        $qr_string.= "WHERE ambience.name LIKE :name ";

        $qr_string.= "AND (category.name LIKE :cat1 ";
        if (isset ($arrayCat)){
            for ($i = 1; $i < count($arrayCat); $i++){
                $qr_string.= "OR  category.name LIKE :cat".($i+1)." ";
            }
        }
        $qr_string.= ") ";

        $qr_string.= "AND (continent LIKE :loc1 ";
        if (isset ($arrayCont)){
            for ($i = 1; $i < count($arrayCont); $i++){
                $qr_string.= "OR  continent LIKE :loc".($i+1)." ";
            }
        }
        $qr_string.= ") ";

        $qr_string.= "AND (codec LIKE :fm1 ";
        if (isset ($arrayCdc)){
            for ($i = 1; $i < count($arrayCdc); $i++){
                $qr_string.= "OR  codec LIKE :fm".($i+1)." ";
            }
        }
        $qr_string.= ") ";

        $qr_string.= "AND (bitdepth LIKE :bd1 ";
        if (isset ($arrayBd)){
            for ($i = 1; $i < count($arrayBd); $i++){
                $qr_string.= "OR  bitdepth LIKE :bd".($i+1)." ";
            }
        }
        $qr_string.= "OR 1 = :bdh) ";

        $qr_string.= "AND (samplerate LIKE :freq1 ";
        if (isset ($arrayFreq)){
            for ($i = 1; $i < count($arrayFreq); $i++){
                $qr_string.= "OR  samplerate LIKE :freq".($i+1)." ";
            }
        }
        $qr_string.= "OR 1 = :freqh) ";

        $qr_string.= "AND (ambience.length BETWEEN :minLgt AND :maxLgt) ";
        $qr_string.= "LIMIT ".$start.",".$limit.";";

        $query = $db->prepare($qr_string);

        //Name
        if (isset($array['name'])){
            $query->bindValue(':name', '%'.$array['name'].'%', PDO::PARAM_STR);
        } else {
            $query->bindValue(':name', "%%", PDO::PARAM_STR);
        }

        //Kategorie
        if (isset($arrayCat[0])){
            $query->bindValue(':cat1', '%'.$arrayCat[0].'%', PDO::PARAM_STR);
        } else {
            $query->bindValue(':cat1', "%%", PDO::PARAM_STR);
        }
        if (isset($arrayCat)){
            for ($i = 1; $i < count($arrayCat); $i++){
                $query->bindValue(':cat'.($i+1), '%'.$arrayCat[$i].'%', PDO::PARAM_STR);
            }
        }

        //Kontinent
        if (isset($arrayCont[0])){
            $query->bindValue(':loc1', '%'.$arrayCont[0].'%', PDO::PARAM_STR);
        } else {
            $query->bindValue(':loc1', "%%", PDO::PARAM_STR);
        }
        if (isset($arrayCont)){
            for ($i = 1; $i < count($arrayCont); $i++){
                $query->bindValue(':loc'.($i+1), '%'.$arrayCont[$i].'%', PDO::PARAM_STR);
            }
        }

        //Format
        if (isset($arrayCdc[0])){
            $query->bindValue(':fm1', '%'.$arrayCdc[0].'%', PDO::PARAM_STR);
        } else {
            $query->bindValue(':fm1', "%%", PDO::PARAM_STR);
        }
        if (isset($arrayCdc)){
            for ($i = 1; $i < count($arrayCdc); $i++){
                $query->bindValue(':loc'.($i+1), '%'.$arrayCdc[$i].'%', PDO::PARAM_STR);
            }
        }

        //Auflösung
        if (isset($arrayBd[0])){
            $query->bindValue(':bd1', $arrayBd[0], PDO::PARAM_INT);
            $query->bindValue(':bdh', 2, PDO::PARAM_INT);
        } else {
            $query->bindValue(':bd1', 0, PDO::PARAM_INT);
            $query->bindValue(':bdh', 1, PDO::PARAM_INT);
        }
        if (isset($arrayBd)){
            for ($i = 1; $i < count($arrayBd); $i++){
                $query->bindValue(':bd'.($i+1), $arrayBd[$i], PDO::PARAM_INT);
            }
        }

        //Samplerate
        if (isset($arrayFreq[0])){
            $query->bindValue(':freq1', $arrayFreq[0], PDO::PARAM_INT);
            $query->bindValue(':freqh', 2, PDO::PARAM_INT);
        } else {
            $query->bindValue(':freq1', 0, PDO::PARAM_INT);
            $query->bindValue(':freqh', 1, PDO::PARAM_INT);
        }
        if (isset($arrayFreq)){
            for ($i = 1; $i < count($arrayFreq); $i++){
                $query->bindValue(':freq'.($i+1), $arrayFreq[$i], PDO::PARAM_INT);
            }
        }

        //Dauer
        if (isset($array['minLgt'])&& $array['minLgt'] != ""){
            $query->bindValue(':minLgt', $array['minLgt'], PDO::PARAM_INT);
        } else {
            $query->bindValue(':minLgt', 0, PDO::PARAM_INT);
        }
        if (isset($array['maxLgt']) && is_numeric($array['maxLgt'])){
            $query->bindValue(':maxLgt', $array['maxLgt'], PDO::PARAM_INT);
        } else {
            $query->bindValue(':maxLgt', 2147483, PDO::PARAM_INT);
        }

        return $query;
	}

	function getNumElements ($array){
        global $db;
        global $CONTINENTS;

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

        //Abfrage nach gesetztem Kategorie-Filter und String-Explode
        if (isset($array['cat']) && $array['cat'] != ""){
            $strCat = $array['cat'];
            $arrayCat = explode('-', $strCat);
        }

        //Abfrage nach gesetztem Kontinent-Filter und String-Explode
        if (isset($array['cont']) && $array['cont'] != ""){
            $strCont = $array['cont'];
            $arrayContCode = explode('-', $strCont);
            $arrayCont = array();
            $index = 0;
            foreach ($arrayContCode as $contCode){
                $arrayCont[$index] = $CONTINENTS[$contCode];
                $index ++;
            }
        }
        //Abfrage nach gesetztem Format-Filter und String-Explode
        if (isset($array['cdc']) && $array['cdc'] != ""){
            $strCdc = $array['cdc'];
            $arrayCdc = explode('-', $strCdc);
        }
        //Abfrage nach gesetztem Auflösungs-Filter und String-Explode
        if (isset($array['bd']) && $array['bd'] != ""){
            $strBd = $array['bd'];
            $arrayBd = explode('-', $strBd);
        }
        //Abfrage nach gesetztem Abstast-Filter und String-Explode
        if (isset($array['sf']) && $array['sf'] != ""){
            $strFreq = $array['sf'];
            $arrayFreq = explode('-', $strFreq);
        }

        //Abfrage aufbauen und durchführen
        $qr_string = "SELECT COUNT(*) AS 'count' FROM ambience ";
        $qr_string.= "JOIN location ON ambience.location_id = location.id ";
        $qr_string.= "JOIN format  ON ambience.format_id = format.id ";
        $qr_string.= "JOIN category  ON ambience.category_id = category.id ";
        $qr_string.= "WHERE ambience.name LIKE :name ";

        $qr_string.= "AND (category.name LIKE :cat1 ";
        if (isset ($arrayCat)){
            for ($i = 1; $i < count($arrayCat); $i++){
                $qr_string.= "OR  category.name LIKE :cat".($i+1)." ";
            }
        }
        $qr_string.= ") ";

        $qr_string.= "AND (continent LIKE :loc1 ";
        if (isset ($arrayCont)){
            for ($i = 1; $i < count($arrayCont); $i++){
                $qr_string.= "OR  continent LIKE :loc".($i+1)." ";
            }
        }
        $qr_string.= ") ";

        $qr_string.= "AND (codec LIKE :fm1 ";
        if (isset ($arrayCdc)){
            for ($i = 1; $i < count($arrayCdc); $i++){
                $qr_string.= "OR  codec LIKE :fm".($i+1)." ";
            }
        }
        $qr_string.= ") ";

        $qr_string.= "AND (bitdepth LIKE :bd1 ";
        if (isset ($arrayBd)){
            for ($i = 1; $i < count($arrayBd); $i++){
                $qr_string.= "OR  bitdepth LIKE :bd".($i+1)." ";
            }
        }
        $qr_string.= "OR 1 = :bdh) ";

        $qr_string.= "AND (samplerate LIKE :freq1 ";
        if (isset ($arrayFreq)){
            for ($i = 1; $i < count($arrayFreq); $i++){
                $qr_string.= "OR  samplerate LIKE :freq".($i+1)." ";
            }
        }
        $qr_string.= "OR 1 = :freqh) ";

        $qr_string.= "AND (ambience.length BETWEEN :minLgt AND :maxLgt); ";

        $query = $db->prepare($qr_string);

        //Name
        if (isset($array['name'])){
            $query->bindValue(':name', '%'.$array['name'].'%', PDO::PARAM_STR);
        } else {
            $query->bindValue(':name', "%%", PDO::PARAM_STR);
        }

        //Kategorie
        if (isset($arrayCat[0])){
            $query->bindValue(':cat1', '%'.$arrayCat[0].'%', PDO::PARAM_STR);
        } else {
            $query->bindValue(':cat1', "%%", PDO::PARAM_STR);
        }
        if (isset($arrayCat)){
            for ($i = 1; $i < count($arrayCat); $i++){
                $query->bindValue(':cat'.($i+1), '%'.$arrayCat[$i].'%', PDO::PARAM_STR);
            }
        }

        //Kontinent
        if (isset($arrayCont[0])){
            $query->bindValue(':loc1', '%'.$arrayCont[0].'%', PDO::PARAM_STR);
        } else {
            $query->bindValue(':loc1', "%%", PDO::PARAM_STR);
        }
        if (isset($arrayCont)){
            for ($i = 1; $i < count($arrayCont); $i++){
                $query->bindValue(':loc'.($i+1), '%'.$arrayCont[$i].'%', PDO::PARAM_STR);
            }
        }

        //Format
        if (isset($arrayCdc[0])){
            $query->bindValue(':fm1', '%'.$arrayCdc[0].'%', PDO::PARAM_STR);
        } else {
            $query->bindValue(':fm1', "%%", PDO::PARAM_STR);
        }
        if (isset($arrayCdc)){
            for ($i = 1; $i < count($arrayCdc); $i++){
                $query->bindValue(':loc'.($i+1), '%'.$arrayCdc[$i].'%', PDO::PARAM_STR);
            }
        }

        //Auflösung
        if (isset($arrayBd[0])){
            $query->bindValue(':bd1', $arrayBd[0], PDO::PARAM_INT);
            $query->bindValue(':bdh', 2, PDO::PARAM_INT);
        } else {
            $query->bindValue(':bd1', 0, PDO::PARAM_INT);
            $query->bindValue(':bdh', 1, PDO::PARAM_INT);
        }
        if (isset($arrayBd)){
            for ($i = 1; $i < count($arrayBd); $i++){
                $query->bindValue(':bd'.($i+1), $arrayBd[$i], PDO::PARAM_INT);
            }
        }

        //Samplerate
        if (isset($arrayFreq[0])){
            $query->bindValue(':freq1', $arrayFreq[0], PDO::PARAM_INT);
            $query->bindValue(':freqh', 2, PDO::PARAM_INT);
        } else {
            $query->bindValue(':freq1', 0, PDO::PARAM_INT);
            $query->bindValue(':freqh', 1, PDO::PARAM_INT);
        }
        if (isset($arrayFreq)){
            for ($i = 1; $i < count($arrayFreq); $i++){
                $query->bindValue(':freq'.($i+1), $arrayFreq[$i], PDO::PARAM_INT);
            }
        }

        //Dauer
        if (isset($array['minLgt'])&& $array['minLgt'] != ""){
            $query->bindValue(':minLgt', $array['minLgt'], PDO::PARAM_INT);
        } else {
            $query->bindValue(':minLgt', 0, PDO::PARAM_INT);
        }
        if (isset($array['maxLgt']) && is_numeric($array['maxLgt'])){
            $query->bindValue(':maxLgt', $array['maxLgt'], PDO::PARAM_INT);
        } else {
            $query->bindValue(':maxLgt', 2147483, PDO::PARAM_INT);
        }

		$query->execute();

		while ($row = $query->fetch()){
			$ret = $row['count'];
		}

		return $ret;
	}

    function get_numElements_by_user($array){
        global $db;
        $id = $array['id'];

        $string = "SELECT COUNT(*) AS 'count' FROM ambience WHERE user_id = :user_id;";

        $query = $db->prepare($string);
        $query->bindValue(':user_id', $id, PDO::PARAM_INT);
        $query->execute();

        while ($row = $query->fetch()){
            $ret = $row['count'];
        }

        return $ret;
    }

    function globalAliasString(){

        $ret = "";
        $ret.= "ambience.id AS 'id', ";
        $ret.= "ambience.format_id AS 'format_id', ";
        $ret.= "ambience.filename AS 'filename', ";
        $ret.= "ambience.size AS 'size', ";
        $ret.= "ambience.length AS 'length', ";
        $ret.= "ambience.name AS 'name', ";
        $ret.= "ambience.user_id AS 'user_id', ";
        $ret.= "ambience.location_id AS 'location_id', ";
        $ret.= "ambience.date AS 'date', ";
        $ret.= "ambience.time AS 'time', ";
        $ret.= "ambience.description AS 'description', ";
        $ret.= "ambience.category_id AS 'category_id', ";
        $ret.= "ambience.picture AS 'picture', ";
        $ret.= "ambience.rating AS 'rating', ";
        $ret.= "ambience.date_added AS 'date_added', ";
        $ret.= "ambience.originator AS 'originator'";

        return $ret;
    }

    function getAllContinentsGerman(){
        global $db;
        global $COUNTRY_CONTINENTS;
        global $CONTINENTS_GERMAN;

        $ret = array();

        $query = $db->query("SELECT * FROM location GROUP BY continent;");
        $query->execute();

        $index=0;
        while($row = $query->fetch()){
            if ($row['countrycode'] !="dummy"){
            $continentCode = $COUNTRY_CONTINENTS[$row['countrycode']];
            $continentGerman = $CONTINENTS_GERMAN[$continentCode];
            $ret[$index]['code'] = $continentCode;
            $ret[$index]['german'] = $continentGerman;
            $index ++;
            }
        }

        return $ret;
    }

    function getAllFormats(){
        global $db;

        $query = $db->query("SELECT * FROM format GROUP BY codec;");
        $query->execute();

        return $query->fetchAll();
    }

    function getAllBitdepths(){
        global $db;

        $query = $db->query("SELECT * FROM format GROUP BY bitdepth;");
        $query->execute();

        return $query->fetchAll();
    }

    function getAllFreqs(){
        global $db;

        $query = $db->query("SELECT * FROM format GROUP BY samplerate;");
        $query->execute();

        return $query->fetchAll();
    }

    function addDownload($amb){
        global $db;

        $query = $db->prepare("UPDATE ambience SET downloaded=:down WHERE id=:amb_id;");
        $query->bindValue(":down", $amb['downloaded']+1, PDO::PARAM_INT);
        $query->bindValue(":amb_id", $amb['id'], PDO::PARAM_INT);
        $query->execute();
    }

function getDownloadCountByUser($user){
    global $db;

    $query = $db->prepare("SELECT SUM(downloaded) AS 'sum' FROM ambience WHERE user_id = :user_id");
    $query->bindValue(":user_id", $user['id'], PDO::PARAM_INT);
    $query->execute();

    $result = $query->fetch();
    return $result['sum'];
}
?>