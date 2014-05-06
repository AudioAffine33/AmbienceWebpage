<?php
header("Content-disposition: attachement; filename=".$_POST['filename']."");
header("Content-type: ".trim('file -ib ../media/audio/'.$_POST['filename'].'')."");
readfile("../media/audio/".$_POST['filename']."");
?>