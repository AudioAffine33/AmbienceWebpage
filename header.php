<div id="Header">
    <div id="LogoHeader">Logo</div>
    <div id="LoginHeader"> <a href="">
            <?php
            if (!isset($_SESSION['name'])){
                ?>
                <img src="media/Design_Vorlagen/Hauptseite/02c_entdecke_login.png" />
            <?php
            } else {
                echo $_SESSION['name'];
            }
            ?>
        </a> </div>
    <br>
</div>

<div id="ObereNavigation">
    <div id="Button1" class="ButtonNavigation"><a href="overview.php">Ambiences</a></div>
    <div id="Button2" class="ButtonNavigation"><a href="">FAQ</a></div>
    <div id="Button3" class="ButtonNavigation"><a href="">Kontakt</a></div>
    <?php if (isset($_SESSION['name'])){ ?>
        <div id="Button4" class="ButtonNavigation"><a href="">Upload</a></div>
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