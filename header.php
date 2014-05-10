<div id="Header">
    <div id="LogoHeader">Logo</div>
    <div id="LoginHeader"> <a href="">
            <?php
            if (!isset($_SESSION['name'])){
                ?>
                <a onclick="displayLoginMenu()"><img src="media/Design_Vorlagen/Hauptseite/02c_entdecke_login.png" /></a>
            <?php
            } else {
                echo "<a onclick='displayUserMenu()'>".$_SESSION['name']."</a>";
            }
            ?>
        </a> </div>
    <br>
</div>


<div id="LoginMenu">
    <form method="POST">
        <table>
            <tr>
                <td>Login:</td> <td><input type="text" class="loginText" name="loginName" /></td>
            </tr>
            <tr>
                <td>Pass:</td> <td><input type="password" class="loginText" name="loginPass" /></td>
            </tr>
        </table>
        <input class="loginButton" type="submit" value="Login" />
        <button class="loginButton" id="regFrame" data-fancybox-type="iframe" href="register.php" target="_blank" title="Registrieren">Neu?</button>
    </form>
</div>

<div id="UserMenu">
    <a href="">Profil</a><br />
    <form method="POST" action="php/logout.php"><input type="submit" name="logout" value="Logout" /></form>

</div>

<div id="ObereNavigation">
    <div class="ButtonNavigationNU"><a href="overview.php">|Ambiences</a></div>
    <div class="ButtonNavigationNU"><a href="">|Top Files</a></div>
    <div class="ButtonNavigationNU"><a href="">|FAQ</a></div>
    <div class="ButtonNavigationNU"><a href="">|Kontakt</a></div>
    <?php if (isset($_SESSION['name'])){ ?>
        <div class="ButtonNavigationU"><a href="Upload_Datei.php">|Upload</a></div>
        <div class="ButtonNavigationU"><a href="user.php?id=<?php echo $_SESSION['id']?>" >|Userseite</a></div>
    <?php } ?>
</div>

<div id="SucheHeader">

    <div id="Suche">
        <form method="POST">
            <input type="text" name="name" <?php if (isset($_GET['name'])){ echo "value='".htmlentities($_GET['name'])."'";} ?>/>
        </form>
    </div>
    <div id="SuchBut"></div>
    <div id="SortBut">
        <div id="SortBut1"></div>
        <div id="SortBut2"></div>
        <div id="SortBut3"></div>

    </div>
</div>