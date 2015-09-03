<?php
session_name('CyberWorks');
session_set_cookie_params(1209600);
session_start();
require_once("../gfunctions.php");

if (isset($_SESSION['user_name'])) {
    if (isset($_POST['current_password'])) {
        $db_connection = masterConnect();
        $isAvailable = false;
        $sql = "SELECT `user_password_hash` FROM `users` WHERE `user_name` = '" . $_SESSION['user_name'] . "' ";
        $user = $db_connection->query($sql)->fetch_object();
        if (password_verify($_POST['current_password'], $user->user_password_hash)) {
            $isAvailable = true;
        }
    }
    
    if (isset($isAvailable)) {
        echo json_encode(array(
        'valid' => $isAvailable,
    ));
    }
    };