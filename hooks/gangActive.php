<?php
session_name('CyberWorks');
session_set_cookie_params(1209600);
session_start();

if (isset($_SESSION['permissions']['edit']['gangs'])) {
    if ($_SESSION['permissions']['edit']['gangs'] && isset($_POST['gang'])) {
        require('../gfunctions.php');
        $settings = require('../config/settings.php');
        
        if (isset($_SESSION['dbid'])) {
            $db_link = serverConnect($_SESSION['dbid']);

            $sql = "SELECT `active` FROM `gangs` WHERE `id` = '" . $_POST['gang'] . "';";
            $result = $db_link->query($sql);
            if ($result->num_rows > 0) {
                $active = $result->fetch_object();
                if ($active == '1') {
                    $active = '0';
                    if ($settings['logging']) {
                        logAction($_SESSION['user_name'], $lang['edited'] . ' ' . uIDname($_POST['player'], $db_link) . ' ' . $lang['gang'] . ' ' . $lang['deactive'], 1);
                    }
                } elseif ($active == '0') {
                    $active = '1';
                    if ($settings['logging']) {
                        logAction($_SESSION['user_name'], $lang['edited'] . ' ' . uIDname($_POST['player'], $db_link) . ' ' . $lang['gang'] . ' ' . $lang['active'], 1);
                    }
                } else {
                    $active = '0';
                }
                $sql = "UPDATE `gangs` SET `active`='$active' WHERE `id` = '" . $_POST['gang'] . "';";
                $db_link->query($sql);
            }
        }
    }
}