<?php
function encrypt($text)
{
    $settings = require('config/settings.php');
    return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $settings['key'], $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
}

function decrypt($text)
{
    $settings = require('config/settings.php');
    return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $settings['key'], base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
}

function masterConnect()
{
    $settings = require('config/settings.php');
    if (isset($settings['db']['port'])) {
        $db_connection = new mysqli(decrypt($settings['db']['host']), decrypt($settings['db']['user']), decrypt($settings['db']['pass']), decrypt($settings['db']['name']), decrypt($settings['db']['port']));
    } else {
        $db_connection = new mysqli(decrypt($settings['db']['host']), decrypt($settings['db']['user']), decrypt($settings['db']['pass']), decrypt($settings['db']['name']));
    }
    if (!$db_connection->set_charset("utf8")) {
        $db_connection->errors[] = $db_connection->error;
    }
    return $db_connection;
}

function serverConnect($dbid = NULL)
{
    if (isset($_SESSION['dbid']) && empty($dbid)) {
        $dbid = $_SESSION['dbid'];
    }
    $settings = require('config/settings.php');

    if (isset($settings['db']['port'])) {
        $db_connection = new mysqli(decrypt($settings['db']['host']), decrypt($settings['db']['user']), decrypt($settings['db']['pass']), decrypt($settings['db']['name']), decrypt($settings['db']['port']));
    } else {
        $db_connection = new mysqli(decrypt($settings['db']['host']), decrypt($settings['db']['user']), decrypt($settings['db']['pass']), decrypt($settings['db']['name']));
    }

    $sql = "SELECT `sql_host`,`sql_name`,`sql_pass`,`sql_user` FROM `db` WHERE `dbid` = '" . $dbid . "';";
    $server = $db_connection->query($sql);

    if ($server->num_rows === 1) {
        $server = $server->fetch_object();
        $host = decrypt($server->sql_host);

        if (strpos($host, ":")) {
            $SQL_ip = explode(":", $host);
            $host = $SQL_ip['0'];
            $port = $SQL_ip['1'];
        }

        if (isset($port)) {
            $db_link = new mysqli($host, decrypt($server->sql_user), decrypt($server->sql_pass), decrypt($server->sql_name), $port);
        } else {
            $db_link = new mysqli($host, decrypt($server->sql_user), decrypt($server->sql_pass), decrypt($server->sql_name));
        }

        if (!$db_link->set_charset("utf8")) {
            $db_link->errors[] = $db_link->error;
        }

        return $db_link;
    } else {
        return false;
    }

}

function carType($car, $lang)
{
    switch ($car) {
        case 'Car':
            return $lang['car'];
            break;
        case 'Air':
            return $lang['air'];
            break;
        case 'Ship':
            return $lang['ship'];
            break;
    }
}

function yesNo($input, $lang)
{
    if ($input == 1) {
        return $lang['yes'];
    } else if ($input == 0) {
        return $lang['no'];
    } else {
        return $lang['error'];
    }
    }

function select($val, $row)
{
    if ($row == $val) {
        return 'selected';
    }
    }

function nameID($pId, $db_link)
{
    $sql = "SELECT `name` FROM `players` WHERE `playerid` LIKE '" . $pId . "' ";
    $result_of_query = $db_link->query($sql);

    if ($result_of_query->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result_of_query)) {
            return $row['name'];
        }
    } else {
        return $pId;
    }
    }

function uID($pId, $db_link)
{
    $sql = "SELECT `uid` FROM `players` WHERE `playerid` = '" . $pId . "' ";
    $result_of_query = $db_link->query($sql);
    if ($result_of_query->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result_of_query)) {
            return $row['uid'];
        }
    } else {
        return $pId;
    }
    }

function uIDname($uID, $db_link)
{
    $sql = "SELECT `name` FROM `players` WHERE `uid` = '" . $uID . "' ";
    $result_of_query = $db_link->query($sql);
    if ($result_of_query->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result_of_query)) {
            return $row['name'];
        }
    } else {
        return $uID;
    }
    }

function IDname($name, $db_link)
{
    $sql = "SELECT `name`,`playerid` FROM `players` WHERE `name` LIKE '%" . $name . "%' ";
    $result_of_query = $db_link->query($sql);

    if ($result_of_query->num_rows > 0) {
        while ($row = mysqli_fetch_array($result_of_query)) {
        }
    } else {
        return $name;
    }
    }

/**
 * @param string $action
 * @param integer $level
 */
function logAction($user, $action, $level)
{
    $settings = require('config/settings.php');

    if ($settings['logging']) {
        $db_connection = masterConnect();
        $sql = "INSERT INTO `logs` (`user`, `action`, `level`) VALUES ('" . $user . "', '" . $action . "', '" . $level . "');";
        $db_connection->query($sql);
    }
}

function message($text)
{
    echo "<br><div class='row'><div class='col-lg-12'>";
    echo "<div class='alert alert-danger alert-dismissable'>";
    echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
    echo "<i class='fa fa-info-circle'></i> " . $text . "</div></div></div>";
}

function error($errno, $errstr, $errfile, $errline)
{
    echo '<h4><b>PHP ERROR ' . $errno . '</b> ' . $errstr . ' - ' . $errfile . ':' . $errline . '</h4>';
}

/**
 * @param integer $code
 */
function errorMessage($code, $lang)
{
    switch ($code)
    {
        case 1:
            return $lang['lowVersion']; //Version too low
        case 2:
            return $lang['dbConnect']; //Db Connection
        case 3:
            return $lang['noRes']; //No Results
        case 4:
            return $lang['404']; //404 Not Found
        case 5:
            return $lang['noPerm']; //No Permissions
        case 6:
            return $lang['banned']; //User Banned
        case 7:
            return $lang['pluginNF']; //User Banned
        case 8:
            return $lang['noID']; //No ID
        case 9:
            return $lang['noPlayers']; // RCON no players online
        case 10:
            return $lang['selDB']; // Select A DB
        case 11:
            return $lang['noServer']; // Select A DB
        case 31:
            return $lang['noHouse']; //No House
        case 32:
            return $lang['noVeh']; //No Vehicle
        case 33:
            return $lang['noGang']; //No Gang
        case 34:
            return $lang['noCrimes']; //No Crimes
        case 35:
            return $lang['noCrimes']; //No Crimes
        case 36:
            return $lang['noPlayer']; //No Player
        case 37:
            return $lang['noLic']; //No License
        case 371:
            return $lang['no'] . ' ' . $lang['civil'] . ' ' . $lang['licenses']; //No Civillian Licenses
        case 372:
            return $lang['no'] . ' ' . $lang['medic'] . ' ' . $lang['licenses']; //No Medic Licenses
        case 373:
            return $lang['no'] . ' ' . $lang['police'] . ' ' . $lang['licenses']; //No Police Licenses
        case 38:
            return $lang['no'] . ' ' . $lang['gear']; //No License
        case 381:
            return $lang['no'] . ' ' . $lang['civil'] . ' ' . $lang['gear']; //No Civillian Licenses
        case 382:
            return $lang['no'] . ' ' . $lang['medic'] . ' ' . $lang['gear']; //No Medic Licenses
        case 383:
            return $lang['no'] . ' ' . $lang['police'] . ' ' . $lang['gear']; //No Police Licenses
    }
}

function random($length)
{
    $max = ceil($length / 40);
    $random = '';
    for ($i = 0; $i < $max; $i++) {
        $random .= sha1(microtime(true) . mt_rand(10000, 90000));
    }
    return substr($random, 0, $length);
}

function steamBanned($PID)
{
    $settings = require('config/settings.php');
    if (!empty($settings['steamAPI'])) {
        $api = "http://api.steampowered.com/ISteamUser/GetPlayerBans/v1/?key=" . $settings['steamAPI'] . "&steamids=" . $PID;
        $bans = json_decode(file_get_contents($api), true);
        if ($bans['players']['0']['VACBanned']) {
            return '<h4><span class="label label-danger" style="margin-left:3px; line-height:2;">VAC BANNED</span></h4>';
        }
        //todo:formatting
    }
}

function multiDB()
{
    $settings = require('config/settings.php');
    if (isset($settings['db']['port'])) {
        $db_connection = new mysqli(decrypt($settings['db']['host']), decrypt($settings['db']['user']), decrypt($settings['db']['pass']), decrypt($settings['db']['name']), decrypt($settings['db']['port']));
    } else {
        $db_connection = new mysqli(decrypt($settings['db']['host']), decrypt($settings['db']['user']), decrypt($settings['db']['pass']), decrypt($settings['db']['name']));
    }

    $sql = "SELECT `sid`,`dbid`,`type`,`name` FROM `servers`;";
    $db = $db_connection->query($sql)->fetch_object(); ;

    if ($db->num_rows <= 1) {
        $_SESSION['multiDB'] = false;
        $_SESSION['server_type'] = $db->type;
        $_SESSION['dbid'] = $db->dbid;
    } else {
        $_SESSION['multiDB'] = true;
    }
}

function tokenGen($length)
{
    return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, $length);
}

function stripArray($input, $type)
{
    $array = array();

    switch ($type) {
        case 0:
            $array = explode("],[", $input);
            $array = str_replace('"[[', "", $array);
            $array = str_replace(']]"', "", $array);
            $array = str_replace('`', "", $array);
            break;
        case 1:
            $array = explode(",", $input);
            $array = str_replace('"[', "", $array);
            $array = str_replace(']"', "", $array);
            $array = str_replace('`', "", $array);
            break;
        case 2:
            $array = explode(",", $input);
            $array = str_replace('"[', "", $array);
            $array = str_replace(']"', "", $array);
            $array = str_replace('`', "", $array);
            break;
        case 3:
            $input = str_replace('[`', "", $input);
            $input = str_replace('`]', "", $input);
            $array = explode("`,`", $input);
            break;
    }

    return $array;
}

function clean($input, $type)
{
    if ($type == 'string') {
        return filter_var(htmlspecialchars(trim($input)), FILTER_SANITIZE_STRING);
    } elseif ($type == 'int') {$input = filter_var(htmlspecialchars(trim($input)), FILTER_SANITIZE_NUMBER_INT); if ($input < 0) {
        return 0;
    } else {
        return $input;
    }
    } elseif ($type == 'url') {
        return filter_var(htmlspecialchars(trim($input)), FILTER_SANITIZE_URL);
    } elseif ($type == 'email') {
        return filter_var(htmlspecialchars(trim($input)), FILTER_SANITIZE_EMAIL);
    } elseif ($type == 'boolean') {
        return ($input === 'true');
    } elseif ($type == 'intbool') {
        if ($input == 1 || $input == 0) return $input;
    } else {
        return 0;
    }
    }

/**
 * @param string $this
 * @param string|null $inthat
 */
function before($this, $inthat)
{
    return substr($inthat, 0, strpos($inthat, $this));
}


/**
 * @param string $this
 */
function after($this, $inthat)
{
    if (!is_bool(strpos($inthat, $this))) {
            return substr($inthat, strpos($inthat, $this) + strlen($this));
    }
    }

function communityBanned($GUID)
{
    $settings = require('config/settings.php');
    $data = json_decode(file_get_contents("http://bans.itsyuka.tk/api/bans/player/id/" . $GUID . "/format/json/key/" . $settings['communityBansAPI']), true);

    if ($data['level'] >= 1) {
        return true;
    } else {
        return false;
    }
}
