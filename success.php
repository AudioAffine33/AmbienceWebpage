<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="css/foundation.css" />
    <link rel="stylesheet" href="css/Registrierung.css" />
    <title>Registrierung</title>

    <?php
    include('php/include.php');

    ?>
    <script type="text/javascript">
        $( document ).ready(function() {
            parent.window.$('#logName').val("<?php echo $_GET['name']; ?>");
        });
    </script>
</head>

<body>
<div id="success" class="row">
	<div class="column text-center">
    <h1>Glückwunsch, <?php echo htmlentities($_GET['name']); ?></h1>
    Du hast dich erfolgreich registriert.
	</div>
    <div id="Abschl" class="column text-center">
    <a onclick="parent.$.fancybox.close();">Abschließen</a>
    </div>
</div>
</body>
</html>