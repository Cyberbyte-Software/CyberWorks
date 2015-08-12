<?php
require("../classes/session.php");
SessionManager::sessionStart('CyberWorks');

if(isset($_SESSION['permissions']['edit']['player'])){
    if ($_SESSION['permissions']['edit']['player'] && isset($_POST['player']) && isset($_POST['id'])) {
        require('../gfunctions.php');
        require('../config/license.php');
        $settings = require('../config/settings.php');
        $db_link = serverConnect($_SESSION['dbid']);

        if($settings['logging']) logAction($_SESSION['user_name'],$lang['edited'] . ' ' . uIDname($_POST['player'],$db_link) . ' '. $lang['licenses'],1);

        if ($_POST['id'] == 'arrested') {
            $sql = "SELECT `arrested` FROM `players` WHERE `uid` = '" . $_POST['player'] . "';";
            $result = $db_link->query($sql);
            if ($result->num_rows > 0) {
                $switch = $result->fetch_object();
                if ($switch == '1') $sql = "UPDATE `players` SET `arrested`='0' WHERE `uid` = '" . $_POST['player'] . "';";
                elseif ($switch == '0') $sql = "UPDATE `players` SET `arrested`='1' WHERE `uid` = '" . $_POST['player'] . "';";
                $db_link->query($sql);
            }
        } elseif ($_POST['id'] == 'blacklist') {
            $sql = "SELECT `arrested` FROM `players` WHERE `uid` = '" . $_POST['player'] . "';";
            $result = $db_link->query($sql);
            if ($result->num_rows > 0) {
                $switch = $result->fetch_object();
                if ($switch == '1') $sql = "UPDATE `players` SET `blacklist`='0' WHERE `uid` = '" . $_POST['player'] . "';";
                elseif ($switch == '0') $sql = "UPDATE `players` SET `blacklist`='1' WHERE `uid` = '" . $_POST['player'] . "';";
                $db_link->query($sql);
            }
        }
    }
}