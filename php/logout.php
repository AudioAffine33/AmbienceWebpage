<?php
	session_start();
	session_destroy();
	
	header('Location: ../overview.php'); 
	exit;
?>