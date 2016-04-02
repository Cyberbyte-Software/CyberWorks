<?php
if (!file_exists('config/settings.php')) {

function rand_sha1($length)
{
    $max = ceil($length / 40);
    $random = '';
    for ($i = 0; $i < $max; $i++) {
        $random .= sha1(microtime(true) . mt_rand(10000, 90000));
    }
    return substr($random, 0, $length);
}

function encrypt($text, $salt)
{
    return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
}

function decrypt($text, $salt)
{
    return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $salt, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
}

if (isset($_POST['user_name'])) {
    $last = str_replace(strrchr($_SERVER['REQUEST_URI'], '/'), '', $_SERVER['REQUEST_URI']) . '/';
    $settings['url'] = 'http://' . $_SERVER['HTTP_HOST'] . $last;
    $base = substr($last, 1);
    $settings['base'] = substr_count($settings['url'], "/") - 2;

    $hta = 'RewriteEngine On
RewriteBase /'.$base . '
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule . /'.$base . 'index.php [L]';
    file_put_contents('.htaccess',$hta);

    $settings['id'] = 1001;
    $settings['community'] = $_POST['community_name'];

    $user_name = $_POST['user_name'];
    $user_email = $_POST['user_email'];
    $user_password = $_POST['user_password'];
    $user_pic = $_POST['user_pic'];

    $server_name = $_POST['server_name'];
    $server_type = $_POST['server_type'];

    $sql_type = $_POST['SQL_type'];
    $settings['key'] = rand_sha1(16);

    $server_use_SQ = $_POST['server_use_SQ'];
    if ($server_use_SQ == '1') {
        $server_IP = encrypt($_POST['server_IP'], $settings['key']);
        $server_PORT = encrypt($_POST['server_port'], $settings['key']);
        $server_RCON = encrypt($_POST['server_RCON_pass'], $settings['key']);
    }

    $server_SQL_host = $_POST['server_SQL_host'];
    if (strpos($server_SQL_host, ":")) {
        $SQL_ip = explode(":", $server_SQL_host);
        $settings['db']['host'] = encrypt($SQL_ip['0'], $settings['key']);
        $settings['db']['port'] = encrypt($SQL_ip['1'], $settings['key']);
    } else {
        $settings['db']['host'] = encrypt($server_SQL_host, $settings['key']);
    }

    $server_SQL_user = $_POST['server_SQL_user'];
    $server_SQL_pass = $_POST['server_SQL_pass'];
    $server_SQL_name = $_POST['server_SQL_name'];

    $encrypted_SQL_host = encrypt($server_SQL_host, $settings['key']);
    $encrypted_SQL_user = encrypt($server_SQL_user, $settings['key']);
    $encrypted_SQL_pass = encrypt($server_SQL_pass, $settings['key']);
    $encrypted_SQL_name = encrypt($server_SQL_name, $settings['key']);

    $settings['db']['user'] = $encrypted_SQL_user;
    $settings['db']['pass'] = $encrypted_SQL_pass;
    $settings['db']['name'] = $encrypted_SQL_name;

    $settings['maxLevels']['cop'] = 7;
    $settings['maxLevels']['medic'] = 5;
    $settings['maxLevels']['admin'] = 5;
    $settings['maxLevels']['donator'] = 5;

    $settings['items'] = 15;
    $settings['notifications'] = true;
    $settings['news'] = true;
    $settings['sql_phone'] = false;
    $settings['language'] = 'en';
    $settings['allowLang'] = true;
    $settings['lifeVersion'] = 4;
    $settings['wanted'] = false;
    $settings['version'] = '0.5';
    $settings['staffRanks'] = 5;
    $settings['logging'] = true;
    $settings['2factor'] = false;
    $settings['gravatar'] = false;
    $settings['force2factor'] = 'none';

    $settings['steamAPI'] = '';
    $settings['vacTest'] = false;
    $settings['steamdomain'] = '';
    $settings['steamlogin'] = false;
    $settings['plugins'] = array();
    $settings['performance'] = false;
    $settings['annonlogin'] = false;
    $settings['performance'] = false;
    $settings['register'] = false;
    $settings['passreset'] = false;
    $settings['performance'] = false;
    $settings['refresh'] = 30;
    $settings['communityBansTest'] = false;
    $settings['communityBansAPI'] = '';

    $settings['item'] = array(5,10,15,25,50);

    $settings['installedLanguage']=array();
    $langEn = array('English','en');
    $langDe = array('German','de');
    array_push($settings['installedLanguage'], $langEn);
    array_push($settings['installedLanguage'], $langDe);

    $settings['names'] = array('', 'Dave', 'Sam', 'Joe', 'Kerry', 'Connie', 'Jess');
    $settings['ranks'] = array('Banned','Player','Member','Moderator','Server Admin','Super Admin');

    $permissions = include 'config/permissions.php';
    $userPerms = json_encode($permissions['5']);

    $link = mysqli_connect($server_SQL_host,$server_SQL_user,$server_SQL_pass,$server_SQL_name);
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }

    $user_password_hash = password_hash($user_password, PASSWORD_DEFAULT);

    mysqli_query($link, "USE `" . $server_SQL_name . "`;") or die('LINK: ' . mysqli_error($link));

    $query = mysqli_query($link, "SHOW TABLES LIKE 'users'") or die('TEST 1: ' . mysqli_error($link));
    if (mysqli_num_rows($query) == 1) {
        mysqli_query($link, "DROP TABLE `users`") or die('DROP 1: ' . mysqli_error($link));
    }

    $query = mysqli_query($link, "SHOW TABLES LIKE 'notes'") or die('TEST 2: ' . mysqli_error($link));
    if (mysqli_num_rows($query) == 1) {
        mysqli_query($link, "DROP TABLE `notes`") or die('DROP 2: ' . mysqli_error($link));
    }

    $query = mysqli_query($link, "SHOW TABLES LIKE 'db'") or die('TEST 3: ' . mysqli_error($link));
    if (mysqli_num_rows($query) == 1) {
        mysqli_query($link, "DROP TABLE `db`") or die('DROP 3: ' . mysqli_error($link));
    }

    $query = mysqli_query($link, "SHOW TABLES LIKE 'servers'") or die('TEST 4: ' . mysqli_error($link));
    if (mysqli_num_rows($query) == 1) {
        mysqli_query($link, "DROP TABLE `servers`") or die('DROP 4: ' . mysqli_error($link));
    }

    $query = mysqli_query($link, "SHOW TABLES LIKE 'logs'") or die('TEST 5: ' . mysqli_error($link));
    if (mysqli_num_rows($query) == 1) {
        mysqli_query($link, "DROP TABLE `logs`") or die('DROP 5: ' . mysqli_error($link));
    }

    mysqli_query($link, "CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL primary key,
  `user_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_email` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `playerid` varchar(17) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_level` int(1) NOT NULL DEFAULT '1',
  `permissions` text COLLATE utf8_unicode_ci NOT NULL,
  `user_profile` varchar(255) NOT NULL,
  `items` int(2) NULL,
  `twoFactor` VARCHAR(25) NULL,
  `backup` VARCHAR(255) NULL,
  `token` VARCHAR(64) NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='user data';") or die('1: ' . mysqli_error($link));

    if (isset($_POST['user_pid'])) {
        $user_pid = $_POST['user_pid'];
        mysqli_query($link, "INSERT INTO `users` (`user_id`, `user_name`, `user_password_hash`, `user_email`, `playerid`, `user_level`, `permissions`,

`user_profile`) VALUES
    (1, '" . $user_name . "', '" . $user_password_hash . "', '" . $user_email . "', '" . $user_pid . "', 5, '" . $userPerms . "', '" . $user_pic . "');") or die('2: ' . mysqli_error($link));
    } else { mysqli_query($link, "INSERT INTO `users` (`user_id`, `user_name`, `user_password_hash`, `user_email`, `user_level`, `permissions`, `user_profile`) VALUES
    (1, '" . $user_name . "', '" . $user_password_hash . "', '" . $user_email . "', 5, '" . $userPerms . "', '" . $user_pic . "');") or die('2: ' . mysqli_error($link)); }

    mysqli_query($link, "ALTER TABLE `users`
    MODIFY `user_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'auto incrementing user_id of each user, unique index',AUTO_INCREMENT=2;") or die('3: ' . mysqli_error($link));

    mysqli_query($link, "CREATE TABLE IF NOT EXISTS `notes` (
	  `note_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'auto incrementing note_id of each user, unique index',
	  `uid` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	  `staff_name` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	  `note_text` VARCHAR(255) NOT NULL,
	  `note_updated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	  PRIMARY KEY (`note_id`),
	  UNIQUE KEY `note_id` (`note_id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;") or die('4: ' . mysqli_error($link));

    mysqli_query($link, "CREATE TABLE IF NOT EXISTS `db` (
    `dbid` INT(11) NOT NULL AUTO_INCREMENT,
    `type` VARCHAR(64) NOT NULL,
    `sql_host` VARCHAR(64) NOT NULL,
    `sql_user` VARCHAR(64) NOT NULL,
    `sql_pass` VARCHAR(255) NOT NULL,
    `sql_name` VARCHAR(64) NOT NULL,
	PRIMARY KEY (dbid),
	UNIQUE KEY `dbid` (`dbid`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;") or die('5: ' . mysqli_error($link));

    mysqli_query($link, "INSERT INTO `db` (`type`, `sql_host`, `sql_user`, `sql_pass`, `sql_name`) VALUES
    ('" . $sql_type . "', '" . $encrypted_SQL_host . "', '" . $encrypted_SQL_user . "', '" . $encrypted_SQL_pass . "', '" . $encrypted_SQL_name . "');") or die ('6: ' . mysqli_error($link));

    mysqli_query($link, "CREATE TABLE IF NOT EXISTS `servers` (
    `sid` INT(2) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(64) NOT NULL,
    `dbid` INT(2) NOT NULL,
    `type` VARCHAR(64) NOT NULL,
    `use_sq` INT(2) NOT NULL,
    `sq_port` VARCHAR(255) NULL,
    `sq_ip` VARCHAR(255) NULL,
    `rcon_pass` VARCHAR(255) NULL,
	PRIMARY KEY (`sid`),
	UNIQUE KEY `sid` (`sid`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;") or die('7: ' . mysqli_error($link));

    if ($server_use_SQ == '1') {
        mysqli_query($link, "INSERT INTO `servers` (`name`, `dbid`, `type`, `use_sq`, `sq_port`, `sq_ip`,`rcon_pass`) VALUES
    ('" . $server_name . "', '1', '" . $server_type . "', '" . $server_use_SQ . "', '" . $server_PORT . "', '" . $server_IP . "', '" . $server_RCON . "');") or die

('8: ' . mysqli_error($link));
    } else {
                mysqli_query($link, "INSERT INTO `servers` (`name`, `dbid`, `type`, `use_sq`) VALUES
    ('" . $server_name . "', '1', '" . $server_type . "', '" . $server_use_SQ . "');") or die('8: ' . mysqli_error($link));
    }

    mysqli_query($link, "CREATE TABLE IF NOT EXISTS `logs` (
    `logid` int(11) NOT NULL AUTO_INCREMENT,
    `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `user` varchar(64) DEFAULT NULL,
    `action` varchar(255) DEFAULT NULL,
    `level` int(11) NOT NULL,
    PRIMARY KEY (`logid`),
    UNIQUE KEY `logid` (`logid`),
    KEY `logid_2` (`logid`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;") or die('9: ' . mysqli_error($link));

    mysqli_close($link);

    file_put_contents('config/settings.php', '<?php return ' . var_export($settings, true) . ';');
    $settings = include 'config/settings.php';

    header("Location: index?setup=1");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CyberWorks Server Admin Panel needs to be installed">
    <meta name="keyword" content="CyberWorks, Server, Admin Panel">

    <title>Cyber Works Installer</title>

    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Ruda:400,700,900">
    <link rel="stylesheet" type="text/css" href="assets/css/main.css">
    <!--Copyright CyberByte 2015 http://cyberbyte.org.uk/-->
</head>

<body>
<section id="container">
    <header class="header black-bg">
        <a href="http://cyberworks.org.uk" class="logo"><b>Cyber Works installer</b></a>
    </header>
        <section class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            <center>Cyber Works Installer</center>
                        </h1>
                    </div>
                </div>

                <div class="col-sm-4" style="float: none; margin: 0 auto;">
                    <form method="post" action="index.php" name="setupform">
                        <div class="form-group">
                            <p style="text-align: center;">Use this installer to
                            setup the Cyber Works server admin panel. If you need
                            any help feel free to contact support at
                            <a href="http://cyberbyte.org.uk">http://cyberbyte.org.uk</a>.</p>
                            <p style="text-align: center;"> If you just need to fix you can use our
                            <a href="gensettings.php">RECOVERY INSTALLER</a>.</p>
                            <br>
                            <label for="community_name">Community Name: </label>
                            <input placeholder="Community Name" id="community_name"
                                   class="form-control login_input" type="text" name="community_name" <?php if (isset($_POST['community_name'])) echo 'value="' . $_POST['community_name'] . '"'?>>
                            <br><h4>User Setup</h4>
                            <label for="user_name">Username: </label>
                            <input placeholder="Username" id="user_name"
                                   class="form-control login_input" type="text" name="user_name" <?php if (isset($_POST['user_name'])) echo 'value="' . $_POST['user_name'] . '"'?>>

                            <label for="user_email">Email: </label>
                            <input placeholder="Email" id="user_email" class="form-control login_input"
                                   type="email" name="user_email" required <?php if (isset($_POST['user_email'])) echo 'value="' . $_POST['user_email'] . '"'?>>

                            <label for="user_password">Password: </label>
                            <input placeholder="Password" id="user_password"
                                   class="form-control login_input" type="password" name="user_password"
                                   autocomplete="off" required <?php if (isset($_POST['user_password'])) echo 'value="' . $_POST['user_password'] . '"'?>>

                            <label for="user_pid">Player ID: </label>
                            <input placeholder="Player ID" id="user_pid"
                                   class="form-control login_input" type="number" name="user_pid" <?php if (isset($_POST['user_pid'])) echo 'value="' . $_POST['user_pid'] . '"'?>>
                            <label for="user_pic">Picture: </label>

                            <select id='user_pic' name='user_pic' class=" form-control login_input">
                            <?php
                            for ($icon = 1; $icon < 7; $icon++) {
                                echo '<option value="' . $icon . '"';
                                if (isset($_POST['user_pic'])) {
                                    if ($icon == $_POST['user_pic']) echo ' selected';
                                }
                                echo '>' . $icon . '</option>';
                            } ?>
                            </select>
                            <br>

                            <h4>SQL Setup</h4>
                            <label for="SQL_type">Server type: </label>
                            <select id="SQL_type" class=" form-control login_input" name="SQL_type">
                                <option value="life">Altis Life</option>
                                <!--<option value="wasteland">Wasteland</option>-->
                            </select>

                            <label for="server_SQL_host">SQL Host: </label>
                            <input placeholder="SQL Host" id="server_SQL_host"
                                   class="form-control login_input" type="text" name="server_SQL_host"
                                <?php if (isset($_POST['server_SQL_host'])) echo 'value="' . $_POST['server_SQL_host'] . '"'?>>

                            <label for="server_SQL_user">SQL User: </label>
                            <input placeholder="SQL User" id="server_SQL_user"
                                   class="form-control login_input" type="text" name="server_SQL_user"
                                <?php if (isset($_POST['server_SQL_user'])) echo 'value="' . $_POST['server_SQL_user'] . '"'?>>

                            <label for="server_SQL_pass">SQL Password: </label>
                            <input placeholder="SQL Password" id="server_SQL_pass"
                                   class="form-control login_input" type="password" name="server_SQL_pass"
                                <?php if (isset($_POST['server_SQL_pass'])) echo 'value="' . $_POST['server_SQL_pass'] . '"'?>>

                            <label for="server_SQL_name">SQL Database: </label>
                            <input placeholder="SQL Database Name" id="server_SQL_name"
                                   class="form-control login_input" type="text" name="server_SQL_name"
                                <?php if (isset($_POST['server_SQL_name'])) echo 'value="' . $_POST['server_SQL_name'] . '"'?>>
                            <br><br>

                            <h4>Server Setup</h4>
                            <label for="server_name">Server name: </label>
                            <input placeholder="Server name" id="server_name"
                                   class="form-control login_input" type="text" name="server_name"
                                <?php if (isset($_POST['server_name'])) echo 'value="' . $_POST['server_name'] . '"'?>>

                            <label for="server_type">Server type: </label>
                            <select id="server_name" class=" form-control login_input" name="server_type">
                                <option value="life">Altis Life</option>
                                <!--<option value="wasteland">Wasteland</option>-->
                            </select>

                            <br><label for="server_use_SQ">Use SourceQuery: </label>
                            <select class="form-control login_input" name="server_use_SQ" id="server_use_SQ">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select><br>

                            <label for="server_port">Server Query Port: </label>
                            <input placeholder="Server Query Port (Default: 2302)" id="server_port"
                                   class="form-control login_input" type="text" name="server_port"
                                <?php if (isset($_POST['server_port'])) echo 'value="' . $_POST['server_port'] . '"'?>>

                            <label for="server_IP">Server Query IP: </label>
                            <input placeholder="Server Query IP" id="server_IP"
                                   class="form-control login_input" type="text" name="server_IP"
                                <?php if (isset($_POST['server_IP'])) echo 'value="' . $_POST['server_IP'] . '"'?>>

                            <label for="server_SQL_pass">RCON Password: </label>
                            <input placeholder="RCON Password" id="server_SQL_pass"
                                   class="form-control login_input" type="password" name="server_RCON_pass"
                                <?php if (isset($_POST['server_SQL_pass'])) echo 'value="' . $_POST['server_SQL_pass'] . '"'?>>
                            <br>
                            <input class="btn btn-lg btn-primary" style="float:right;" type="submit" name="setup"
                                   value="Setup">
                        </div>
                    </form>
                </div>
        </section>
</section>

</body>
</html>
<?php }
