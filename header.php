<nav class="top-bar show-for-small-only" data-topbar>
    <ul class="title-area">
        <li class="name">
            <h1><a href="overview.php">My Site</a></h1>
        </li>

        <!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
        <li class="toggle-topbar menu-icon"><a href="#"><span>
                 <?php
                 if (isset($_SESSION['name'])){
                     if(isset(get_user_by_ID($_SESSION['id'])['picture']) && get_user_by_ID($_SESSION['id'])['picture'] != ""){
                         ?>
                         <img src="media/pics_user/<?php echo get_user_by_ID($_SESSION['id'])['picture']; ?>" width="30px" height="30px" />
                     <?php
                     }else {
                         ?>
                         <img src="media/Design_Vorlagen/Userseite/standardUser.jpg" width="30px" height="30px" />
                     <?php
                     }
                     echo $_SESSION['name']."</a>";
                 } else {
                     echo "Menu";
                 }
                 ?>
        </span></a></li>
    </ul>

    <section class="top-bar-section">
        <!-- Right Nav Section -->

        <ul class="right">
            <form method="POST"
                <li class="has-form">
                    <div class="row collapse">
                        <div class="large-8 small-9 columns">
                            <input type="text" name="name" placeholder="Suchbegriff" <?php if (isset($_GET['name'])){ echo "value='".htmlentities($_GET['name'])."'";} ?> />
                        </div>
                        <div class="large-4 small-3 columns">
                            <a onclick="$(this).closest('form').submit()" class="alert button expand">Suche</a>
                        </div>
                    </div>
                </li>
            </form>
            <?php
            if (!isset($_SESSION['name'])){
            ?>
            <li class="has-dropdown">
                <a href="#">Login</a>
                <ul class="dropdown">
                    <form method="POST">
                        <li class="has-form">
                            <div class="row collapse">
                                <div class="small-5 columns">
                                    <input type="text" placeholder="Name" id="logName" placeholder="Benutzername" class="loginText" name="loginName" <?php if(isset($errorLog['name'])){ ?> style="background-color:#F00" <?php } ?> value="<?php if(isset($_POST['loginName']) && !isset($errorLog['name'])){ echo $_POST['loginName'];} ?>"/>
                                </div>
                                <div class="small-4 columns">
                                    <input type="password" placeholder="Passwort" <class="loginText" name="loginPass" <?php if(isset($errorLog['pass'])){ ?> style="background-color:#F00" <?php } ?> />
                                </div>
                                <div class="large-4 small-3 columns">
                                    <div id="LoginButton" ><input class="loginButton" type="submit" value="Login" /></div>
                                </div>
                            </div>
                        </li>
                    </form>
                    <li>
                        <a lass="loginButton" id="regFrame" data-fancybox-type="iframe" href="register.php" target="_blank">Registrieren</a>
                    </li>
                </ul>
            </li>
            <?php
            }else {
            ?>
                <li>
                    <a href="user.php?id=<?php echo $_SESSION['id']?>"><img src="media/Design_Vorlagen/Hauptseite/05_header_profil.png" /></a>
                </li>
                <li>
                    <div id="LogoutHeader" class="two columns"><form method="POST" action="php/logout.php"><input type="submit" name="logout" value="Logout" /></form></div>
                </li>
            <?php
            }
            ?>
        </ul>
    </section>
</nav>
<div id="Header" class="row hide-for-small-only">
    <div id="LogoHeader" class="medium-2 columns hide-for-small-only">Logo</div>
    <div id="LoginHeader" class="right three columns hide-for-small-only"> <a href="">
            <?php
            if (!isset($_SESSION['name'])){
                ?>
                <a onclick="displayLoginMenu()"><img src="media/Design_Vorlagen/Hauptseite/02c_entdecke_login.png" /></a>
                <noscript>
                    <div id="LoginMenu" class="row">
                        <form method="POST">
                            
                          <div class="two columns"><input id="logName" type="text" placeholder="Benutzername" class="loginText" name="loginName" <?php if(isset($errorLog['name'])){ ?> style="background-color:#F00" <?php } ?> value="<?php if(isset($_POST['loginName']) && !isset($errorLog['name'])){ echo $_POST['loginName'];} ?>"/></div>
                                
                                <div class="two columns"><input type="password" placeholder="Passwort" class="loginText" name="loginPass" <?php if(isset($errorLog['pass'])){ ?> style="background-color:#F00" <?php } ?> /></div>
                            
                            <div id="LoginButton" class="two columns"><input class="loginButton" type="submit" value="Login" /></div>
                            <div id="RegButton" class="two columns"><button class="loginButton" id="regFrame" data-fancybox-type="iframe" href="register.php" target="_blank" title="Registrieren"></button></div>
                        </form>
                    </div>
                </noscript>
            <?php
            } else {
                echo "<a onclick='displayUserMenu()'>";
                if(isset(get_user_by_ID($_SESSION['id'])['picture']) && get_user_by_ID($_SESSION['id'])['picture'] != ""){
                ?>
                        <img src="media/pics_user/<?php echo get_user_by_ID($_SESSION['id'])['picture']; ?>" width="30px" height="30px" />
                <?php
                }else {
                    ?>
                    <img src="media/Design_Vorlagen/Userseite/standardUser.jpg" width="30px" height="30px" />
                    <noscript>
                        <div id="UserMenu" class="row">
                            <div id="ProfilButton" class="two columns"><a href="user.php?id=<?php echo $_SESSION['id']?>"><img src="media/Design_Vorlagen/Hauptseite/05_header_profil.png" /></a></div>
                            <div id="LogoutHeader" class="two columns"><form method="POST" action="php/logout.php"><input type="submit" name="logout" value="Logout" /></form></div>
                        </div>
                    </noscript>
                <?php
                }
                echo $_SESSION['name']."</a>";
            }
            ?>
        </a> </div>
</div>

 
<div id="LoginMenu" class="row">
    <form method="POST">
        
        <div class="two columns"><input id="logName" type="text" placeholder="Benutzername" class="loginText" name="loginName" <?php if(isset($errorLog['name'])){ ?> style="background-color:#F00" <?php } ?> value="<?php if(isset($_POST['loginName']) && !isset($errorLog['name'])){ echo $_POST['loginName'];} ?>"/></div>
            <div class="two columns"><input type="password" placeholder="Passwort" class="loginText" name="loginPass" <?php if(isset($errorLog['pass'])){ ?> style="background-color:#F00" <?php } ?> /></div>
        <div id="LoginButton" class="column"><input class="loginButton" type="submit" value="Login" /></div>
       	<div id="RegButton" class="column"><button class="loginButton" id="regFrame" data-fancybox-type="iframe" href="register.php" target="_blank" title="Registrieren"></button></div>
    </form>
</div>

<div id="UserMenu" class="row">
      <div id="ProfilButton" class="two columns"><a href="user.php?id=<?php echo $_SESSION['id']?>"><img src="media/Design_Vorlagen/Hauptseite/05_header_profil.png" /></a></div>
    <div id="LogoutHeader" class="two columns"><form method="POST" action="php/logout.php"><input type="submit" name="logout" value="Logout" /></form></div>
</div>

<div id="ObereNavigation" class="row hide-for-small-only">
    <div id="AmbBut" class="two columns small-push-1"><a href="overview.php">|Ambiences</a></div>
    <div id="ButTopF" class="two columns small-push-1"><a href="">|Top Files</a></div>
    <div id="ButKont" class="two columns small-push-1"><a href="">|Kontakt</a></div>
    <div id="ButFaq" class="two columns small-push-1"><a href="">|FAQ</a></div>
    <?php if (isset($_SESSION['name'])){ ?>
        <div id="ButUpl" class="two columns right small-push-1 text-center"><a href="uploadData.php">|Upload</a></div>
    <?php } ?>
</div>

<div id="SucheHeader" class="row hide-for-small-only">

    <div id="Suche" class="four columns small-push-3">
        <form method="POST">
            <input type="text" name="name" placeholder="Suchbegriff" <?php if (isset($_GET['name'])){ echo "value='".htmlentities($_GET['name'])."'";} ?>/>
        </form>
    </div>
    <div id="SortBut" class="six columns right">
        <div id="SortBut1" class="column small-pull-4"></div>
        <div id="SortBut2" class="column small-pull-3"></div>
        <div id="SortBut3" class="column small-pull-2"></div>

    </div>
</div>