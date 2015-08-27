<?php include("views/templates/head.php"); ?>
<body>
<header class="header" style="background: <?php if (isset($settings['colour'])) echo $settings['colour']; else echo '#5FBFFF'; ?>;">
    <div class="sidebar-toggle-box">
        <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
    </div>

    <a href="<?php echo $settings['url'] ?>dashboard" class="logo"><b>Cyber Works
            <?php if ($debug) {
    echo '- Debug Mode';
}
?>
    </b></a>
    <?php if (isset($settings['colour'])) {
        echo '<style>
        ul.sidebar-menu li a.active,ul.sidebar-menu li a:hover,ul.sidebar-menu li a:focus {background: '.$settings['colour'].';}
        .modal-header {background: '.$settings['colour'].';}
            </style>';
    } ?>
    <a class="logosmall pull-right hidden-xs">
        <b>Copyright &copy; 2015 Cyber Works <?php if (isset($settings['version'])) {
    echo $settings['version'];
}
?> by Cyberbyte Studios</b></a>
</header>

<aside>
    <div id="sidebar" class="nav-collapse ">
        <ul class="sidebar-menu" id="nav-accordion">
            <p class="centered">
                <?php if (!isset($_SESSION['profile_link'])) {
                    if (isset($_SESSION['user_email']) && $settings['gravatar']) {
                        echo '<a href="' . $settings['url'] . 'profile">';
                        echo '<img src="' . get_gravatar($_SESSION['user_email'],64,'retro') . '" class="img-circle" width="60" height="60"></a></p>';
                    } else {
                        echo '<a href="' . $settings['url'] . 'profile">';
                        echo '<img src="' . $settings['url'] . 'assets/img/profile/' . $_SESSION['user_profile'] . '.jpg"';
                        echo 'class="img-circle" width="60" height="60"></a></p>';
                    }
                } else {
                    echo '<a href="' . $_SESSION['profile_link'] . '" target="_blank">';
                    echo '<img src="' . $_SESSION['user_profile'] . '"';
                    echo 'class="img-circle" width="64" height="64"></a></p>';
                }
                ?>
            <h5 class="centered">
                <?php
                if ($_SESSION['steamsignon']) echo '<i class="fa fa-steam-square"></i>';
                echo $_SESSION['user_name']; ?>
            </h5>

            <li>
                <a href="<?php echo $settings['url'] ?>dashboard">
                    <i class="fa fa-dashboard"></i>
                    <span><?php echo $lang['navDashboard']; ?></span>
                </a>
            </li>

			<?php
                if(isset($_SESSION['server_type']))
                {
                    switch($_SESSION['server_type'])
                    {
                        case 'life':
                            include("views/life/nav.php");
                            break;
                        case 'exile':
                            include("views/exile/nav.php");
                            break;
                    }
                }

                foreach ($settings['plugins'] as &$plugin) {
                    if (file_exists("plugins/". $plugin. "/nav.php")) {
                        include("plugins/". $plugin."/nav.php");
                    }
                }

                $sql = "SELECT `sid`,`name` FROM `servers` WHERE `use_sq` = 1;";
                $result = $db_connection->query($sql);
                if ($result->num_rows >= 1) {
                ?>
					<li class="dropdown">
						<a data-toggle="dropdown" class="dropdown-toggle" href="#">
							<i class="fa fa-server"></i>
							<span><?php echo $lang['gameServers']; ?></span>
						</a>
						<ul class="dropdown-menu extended tasks-bar">
							<?php while ($row = mysqli_fetch_assoc($result)) {	?>
								<li style="colour:green;">
									<a href="<?php echo $settings['url'] ?>curplayers/<?php echo $row['sid'] ?>">
										<i class="fa fa-cog"></i>
										<span><?php echo $row['name']; ?></span>
									</a>
								</li>
							<?php } ?>
						</ul>
					</li>
				<?php } if (isset($_SESSION['user_email'])) { ?>
            <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="fa fa-user"></i>
                        <span><?php echo $lang['navProfile']; ?></span>
                    </a>
                    <ul class="dropdown-menu extended tasks-bar">
                        <li>
                            <a href="<?php echo $settings['url'] ?>profile">
                                <i class="fa fa-fw fa-user"></i>
                                <span><?php echo $lang['navProfile']; ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo $settings['url'] ?>2factor">
                                <i class="fa fa-fw fa-mobile"></i>
                                <span><?php echo $lang['2factor']; ?></span>
                            </a>
                        </li>
                    </ul>
                </li>
            <?php } else { ?>
            <li>
                <a href="<?php echo $settings['url'] ?>register">
                    <i class="fa fa-fw fa-user"></i>
                    <span> Register</span>
                </a>
            </li>
            <?php } if ($_SESSION['permissions']['edit']['staff']) { ?>
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="fa fa-users"></i>
                        <span><?php echo $lang['users']; ?></span>
                    </a>
                    <ul class="dropdown-menu extended tasks-bar">
                        <li>
                            <a href="<?php echo $settings['url'] ?>staff">
                                <i class="fa fa-fw fa-users"></i>
                                <span><?php echo $lang['staff']; ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo $settings['url'] ?>newUser">
                                <i class="fa fa-fw fa-user-plus"></i>
                                <span><?php echo $lang['navNewUser']; ?></span>
                            </a>
                        </li>
                    </ul>
                </li>
            <?php } elseif ($_SESSION['permissions']['view']['staff']) { ?>
                <li>
                    <a href="<?php echo $settings['url'] ?>staff">
                        <i class="fa fa-fw fa-user"></i>
                        <span><?php echo $lang['staff']; ?></span>
                    </a>
                </li>
            <?php } if ($_SESSION['permissions']['super_admin']) { ?>
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="fa fa-cogs"></i>
                        <span><?php echo $lang['options']; ?></span>
                    </a>
                    <ul class="dropdown-menu extended tasks-bar">
                        <li>
                            <a href="<?php echo $settings['url'] ?>newDB">
                                <i class="fa fa-fw fa-plus"></i>
                                <span><?php echo $lang['new'] . ' ' . $lang['database'] ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo $settings['url'] ?>newServer">
                                <i class="fa fa-fw fa-plus"></i>
                                <span><?php echo $lang['new'] . ' ' . $lang['server'] ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo $settings['url'] ?>servers">
                                <i class="fa fa-fw fa-cogs"></i>
                                <span><?php echo $lang['edit'] . ' ' . $lang['databases'] ?></span>
                            </a>
                        </li>
                        <?php if ($settings['logging']) { ?>
                        <li>
                            <a href="<?php echo $settings['url'] ?>logs">
                                <i class="fa fa-fw fa-th-list"></i>
                                <span><?php echo $lang['logs'] ?></span>
                            </a>
                        </li>
                        <?php } ?>
                        <li>
                            <a href="<?php echo $settings['url'] ?>settings">
                                <i class="fa fa-fw fa-wrench"></i>
                                <span><?php echo $lang['settings'] ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo $settings['url'] ?>pluginstore">
                                <i class="fa fa-fw fa-shopping-cart"></i>
                                <span><?php echo $lang['pluginstore'] ?></span>
                            </a>
                        </li>
                    </ul>
                </li>
            <?php } if ($_SESSION['multiDB']) { ?>
            <li>
                <a data-toggle="modal" href="#changeDB">
                    <i class="fa fa-fw fa-cogs"></i>
                    <span><?php echo $lang['database'] . 's' ?></span>
                </a>
            </li>
            <?php } ?>
            <li>
                <a href="<?php echo $settings['url'] ?>index?logout"><i class="fa fa-fw fa-power-off"></i> <?php echo $lang['navLogOut']; ?></a>
            </li>
            <?php if ($debug) {
                include("views/debug/nav.php");
            }
            ?>
        </ul>
    </div>
</aside>

<section id="main-content">
    <section class="wrapper">
        <?php
        if (isset($error)) {
            echo '<div style="margin-top: 120px;" class="alert alert-danger animated infinite bounce" role="alert">' . $error . '</div>';
        }
        if (isset($message)) {
            echo '<div style="margin-top: 120px;" class="alert alert-info animated infinite bounce" role="alert">' . $message . '</div>';
        }
        if (isset($page)) {
            include($page);
        }
        ?>
    </section>
</section>
<?php include("views/templates/scripts.php"); ?>
</body>
</html>
