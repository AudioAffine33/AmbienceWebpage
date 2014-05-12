<?php
header("Content-disposition: attachement; filename=".$_GET['filename']."");
header("Content-type: ".trim('file -ib ../media/audio/'.$_GET['filename'].'')."");
readfile("../media/audio/".$_GET['filename']."");
?>