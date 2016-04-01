<?php
session_name('CyberWorks');
session_set_cookie_params(1209600);
session_start();

if (isset($_SESSION['permissions']['edit']['player'])) {
    if ($_SESSION['permissions']['edit']['player'] && isset($_POST['player']) && isset($_POST['id'])) {
        require('../gfunctions.php');        
        $settings = require('../config/settings.php');
        if (isset($_SESSION['dbid'])) {
            $db_link = serverConnect($_SESSION['dbid']);

            if ($_POST['id'] == 'arrested') {
                $sql = "SELECT `arrested` FROM `players` WHERE `uid` = '" . $_POST['player'] . "';";
                $result = $db_link->query($sql);
                if ($result->num_rows > 0) {
                    $switch = $result->fetch_object();
                    if ($switch->arrested == '1') {
                        $sql = "UPDATE `players` SET `arrested`='0' WHERE `uid` = '" . $_POST['player'] . "';";
                    } elseif ($switch->arrested == '0') {
                        $sql = "UPDATE `players` SET `arrested`='1' WHERE `uid` = '" . $_POST['player'] . "';";
                    }
                    $db_link->query($sql);
                }
            } elseif ($_POST['id'] == 'blacklist') {
                $sql = "SELECT `blacklist` FROM `players` WHERE `uid` = '" . $_POST['player'] . "';";
                $result = $db_link->query($sql);
                if ($result->num_rows > 0) {
                    $switch = $result->fetch_object();
                    if ($switch->blacklist == '1') {
                        $sql = "UPDATE `players` SET `blacklist`='0' WHERE `uid` = '" . $_POST['player'] . "';";
                    } elseif ($switch->blacklist == '0') {
                        $sql = "UPDATE `players` SET `blacklist`='1' WHERE `uid` = '" . $_POST['player'] . "';";
                    }
                    $db_link->query($sql);
                }
            }
        }
    }
}
?>
