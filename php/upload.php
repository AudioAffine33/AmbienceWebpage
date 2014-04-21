<?php

	//Basic Upload
	function upload_audio ($file, $filename_neu){
		$uploadfile = getRoot().'\\media\\audio\\'. $filename_neu;
		
		if (move_uploaded_file($file['tmp_name'], $uploadfile)){}
	}
	
	function upload_pic_amb ($file, $filename_neu){
		$uploadfile = getRoot().'\\media\\pics_ambiences\\'.$filename_neu;
		
		if (move_uploaded_file($file['tmp_name'], $uploadfile)){
			img_createThumb($uploadfile);
			img_createSmallerVers($uploadfile);
		}
	}
	
	//Audio-Info
	
	function get_audio_info($info, $file){
		$name = basename($file['name']);
		//print_r( $info );
		//echo "<br>";
		
		$ret =	array(
						"filename" => basename($name),
						"format" => $info['fileformat'],
						"length" => $info['playtime_seconds'],
						"bitrate" => $info['audio']['bitrate'],
						"filesize" => $info['filesize'],
						"samplerate" => $info['audio']['sample_rate'],
						"channels" => $info['audio']['channels']);
		
		//MP3 (mit ID3)				
		if (isset ($info['id3v2'])){
			$ret["id3title"] = $info['id3v2']['comments']['title'][0];
			$ret["orig"] = $info['id3v2']['comments']['artist'][0];
			$ret["id3album"] = $info['id3v2']['comments']['album'][0];
		}
		
		//BWF
		if (isset ($info['riff']['WAVE']['bext'])){
			$ret["date"] = date('Y-m-d', strtotime($info['riff']['WAVE']['bext'][0]['origin_date']));
			$ret["time"] = date('h:i:s', strtotime($info['riff']['WAVE']['bext'][0]['origin_time']));
			$ret["orig"] = $info['riff']['WAVE']['bext'][0]['author'];
			$ret["riffDescr"] = $info['riff']['WAVE']['bext'][0]['title'];
		}
		
		//WAV
		if (isset ($info['audio']['bits_per_sample'])){
			$ret["bits_per_sample"] = $info['audio']['bits_per_sample'];
		}
		
		//iPhone
		if (isset ($info['quicktime']['moov']['subatoms'][0])){
			$ret["date"] = gmdate("Y-m-d", $info['quicktime']['moov']['subatoms'][0]['creation_time_unix']);
			$ret["time"] = gmdate('h:i:s', $info['quicktime']['moov']['subatoms'][0]['creation_time_unix']);
		}
		if (isset ($info['quicktime']['moov']['subatoms'][2])){
			$ret["orig"] = $info['quicktime']['moov']['subatoms'][2]['subatoms'][0]['subatoms'][1]['subatoms'][2]['data'];
		}
		
		return $ret;
	}
	
	//Delete Ambience
	function delete_ambience_from_Server($fileArray){
		unlink(getRoot()."\\media\\audio\\".$fileArray['filename']);
		unlink(getRoot()."\\media\\pics_ambiences\\".$fileArray['picture']);
		unlink(getRoot()."\\media\\pics_ambiences\\thumb\\".$fileArray['picture']);
	}
	
	function img_createThumb($uploadfile){
		$filetype = getimagesize($uploadfile);
			
			if ($filetype['mime'] == 'image/jpg' || $filetype['mime'] == 'image/jpeg') {
				$image = imagecreatefromjpeg($uploadfile);
			}
			else if ($filetype['mime'] == 'image/png') {
				$image = imagecreatefrompng($uploadfile);
			}
			else if ($filetype['mime'] == 'image/gif') {
				$image = imagecreatefromgif($uploadfile);
			}
			else if ($filetype['mime'] == 'image/wbmp') {
				$image = imagecreatefromwbmp($uploadfile);
			}
			$pathparts = pathinfo ($uploadfile);
			$filename = $pathparts['dirname']."/thumb/".$pathparts['filename'].".".$pathparts['extension'];

			$thumb_width = 200;
			$thumb_height = 200;

			$width = imagesx($image);
			$height = imagesy($image);
			
			$original_aspect = $width / $height;
			$thumb_aspect = $thumb_width / $thumb_height;
			
			if ( $original_aspect >= $thumb_aspect )
			{
			   // If image is wider than thumbnail (in aspect ratio sense)
			   $new_height = $thumb_height;
			   $new_width = $width / ($height / $thumb_height);
			}
			else
			{
			   // If the thumbnail is wider than the image
			   $new_width = $thumb_width;
			   $new_height = $height / ($width / $thumb_width);
			}
			
			$thumb = imagecreatetruecolor( $thumb_width, $thumb_height );
			
			// Resize and crop
			imagecopyresampled($thumb,
							   $image,
							   0 - ($new_width - $thumb_width) / 2, // Center the image horizontally
							   0 - ($new_height - $thumb_height) / 2, // Center the image vertically
							   0, 0,
							   $new_width, $new_height,
							   $width, $height);
			if ($filetype['mime'] == 'image/jpg' || $filetype['mime'] == 'image/jpeg') {
				imagejpeg($thumb, $filename, 80);
			}
			else if ($filetype['mime'] == 'image/png') {
				imagepng($thumb, $filename);
			}
			else if ($filetype['mime'] == 'image/gif') {
				imagegif($thumb, $filename);
			}
			else if ($filetype['mime'] == 'image/wbmp') {
				imagewbmp($thumb, $filename);
			}
	}
	
	function img_createSmallerVers($uploadfile){
		$filetype = getimagesize($uploadfile);
			
			if ($filetype['mime'] == 'image/jpg' || $filetype['mime'] == 'image/jpeg') {
				$image = imagecreatefromjpeg($uploadfile);
			}
			else if ($filetype['mime'] == 'image/png') {
				$image = imagecreatefrompng($uploadfile);
			}
			else if ($filetype['mime'] == 'image/gif') {
				$image = imagecreatefromgif($uploadfile);
			}
			else if ($filetype['mime'] == 'image/wbmp') {
				$image = imagecreatefromwbmp($uploadfile);
			}
			$pathparts = pathinfo ($uploadfile);
			$filename = $pathparts['dirname']."/".$pathparts['filename'].".".$pathparts['extension'];

			$thumb_height = 500;

			$width = imagesx($image);
			$height = imagesy($image);
			
			$original_aspect = $width / $height;
			
			// If image is wider than thumbnail (in aspect ratio sense)
			$new_height = $thumb_height;
			$new_width = $thumb_height*$original_aspect;
			
			
			$thumb = imagecreatetruecolor( $new_width, $thumb_height );
			
			// Resize and crop
			imagecopyresampled($thumb,
							   $image,
							   0, // Center the image horizontally
							   0, // Center the image vertically
							   0, 0,
							   $new_width, $new_height,
							   $width, $height);
			if ($filetype['mime'] == 'image/jpg' || $filetype['mime'] == 'image/jpeg') {
				imagejpeg($thumb, $filename, 80);
			}
			else if ($filetype['mime'] == 'image/png') {
				imagepng($thumb, $filename);
			}
			else if ($filetype['mime'] == 'image/gif') {
				imagegif($thumb, $filename);
			}
			else if ($filetype['mime'] == 'image/wbmp') {
				imagewbmp($thumb, $filename);
			}
	}
?>