<?php
session_name('CyberWorks');
session_set_cookie_params(1209600);
session_start();

if (isset($_SESSION['permissions']['edit']['player'])) {
    if ($_SESSION['permissions']['edit']['player'] && isset($_POST['vehID']) && isset($_POST['type'])) {
        include ('../gfunctions.php');
        $db_link = serverConnect($_SESSION['dbid']);

        if ($_POST['type'] == 'fuel') {
            $sql = "UPDATE `vehicle` SET `fuel`=1 WHERE `id` = '" . $_POST['vehID'] . "';";
            $db_link->query($sql);
        } elseif ($_POST['type'] == 'repair') {
            $sql = "UPDATE `vehicle` SET `damage`=0 WHERE `id` = '" . $_POST['vehID'] . "';";
            $db_link->query($sql);
        }
    }
}