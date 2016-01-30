<?php
session_name('CyberWorks');
session_set_cookie_params(1209600);
session_start();

include_once('config/english.php');
foreach ($settings['plugins'] as &$plugin) {
    if (file_exists("plugins/" . $plugin . "/lang/lang.php")) {
        include("plugins/" . $plugin . "/lang/lang.php");
    }
}

if (isset($_SESSION['permissions']['edit']['licences'])) {
    if ($_SESSION['permissions']['edit']['licences'] && isset($_POST['player']) && isset($_POST['id'])) {
        require('../gfunctions.php');
        require('../config/license.php');
        $settings = require('../config/settings.php');
        if (isset($_SESSION['dbid'])) {
            $db_link = serverConnect($_SESSION['dbid']);
    
            $change = explode("_", $_POST['id']);
            $col = $change['1'] . "_licenses";
            $sql = "SELECT `" . $col . "` FROM `players` WHERE `uid` = '" . $_POST['player'] . "';"; //todo: innerjoin
            $result = $db_link->query($sql);
            if ($result->num_rows > 0) {
                $lic = $result->fetch_object()->$col;
                $num = strpos($lic, $change['2']) + strlen($change['2']) + 2;
                if ($lic[$num] == '1') {
                    $lic[$num] = '0';
                    if ($settings['logging']) {
                        logAction($_SESSION['user_name'], $lang['removed'] . ' ' . uIDname($_POST['player'], $db_link) . ' ' . $lang['licenses'] . ' ( has removed' . licName($_POST['id'], $license) . ')', 2);
                    }
                } elseif ($lic[$num] == '0') {
                    $lic[$num] = '1';
                    if ($settings['logging']) {
                        logAction($_SESSION['user_name'], $lang['added'] . ' ' . uIDname($_POST['player'], $db_link) . ' ' . $lang['licenses'] . ' (has added ' . licName($_POST['id'], $license) . ')', 2);
                    }
                }
                $sql = "UPDATE `players` SET `" . $col . "`='$lic' WHERE `uid` = '" . $_POST['player'] . "';";
                $db_link->query($sql);
            }
        }
    }
}
