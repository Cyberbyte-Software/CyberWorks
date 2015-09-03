<?php
session_name('CyberWorks');
session_set_cookie_params(1209600);
session_start();

if (isset($_SESSION['permissions']['edit']['staff']) && isset($_SESSION['user_login_status'])) {
    if ($_SESSION['permissions']['edit']['staff'] && $_SESSION['user_login_status'] && isset($_POST['type'])) {
    require_once("../gfunctions.php");
}
    $db_connection = masterConnect();
    
    switch ($_POST['type']) {
        case 'email':
            if (isset($_POST['user_email'])) {
                $email = $_POST['user_email'];
                $sql = "SELECT `user_id` FROM `users` WHERE `user_email` = '" . $email . "';";
                $result_of_query = $db_connection->query($sql);
                if (mysqli_num_rows($result_of_query) == 1) {
                    $isAvailable = false;
                } else {
                    $isAvailable = true;
                }
            } else {
                $isAvailable = false;
            }
            break;
        case 'username':
            if (isset($_POST['user_name'])) {
                $username = $_POST['user_name'];
                $sql = "SELECT `user_id` FROM `users` WHERE `user_name` = '" . $username . "';";
                $result_of_query = $db_connection->query($sql);
                if (mysqli_num_rows($result_of_query) == 1) {
                    $isAvailable = false;
                } else {
                    $isAvailable = true;
                }
            } else {
                $isAvailable = false;
            }
            break;
    }
    
    if (isset($isAvailable)) {
        echo json_encode(array(
        'valid' => $isAvailable,
    ));
    }
    }