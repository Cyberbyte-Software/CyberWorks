<?php
session_name('CyberWorks');
session_set_cookie_params(1209600);
session_start();

if (isset($_SESSION['permissions']['edit']['staff']) && isset($_SESSION['user_login_status'])) {
    if ($_SESSION['permissions']['edit']['staff'] && $_SESSION['user_login_status'] && isset($_POST['type']) && isset($_POST['server_name'])) {
    require_once("../gfunctions.php");
}
    $db_connection = masterConnect();
    
    switch ($_POST['type']) {
        case 'username':
            $username = clean($_POST['server_name'], 'string');
            $sql = "SELECT `name` FROM `servers` WHERE `name` = '" . $username . "'";
            $result_of_query = $db_connection->query($sql);
            if (mysqli_num_rows($result_of_query) == 1) {
                $isAvailable = false;
            } else {
                $isAvailable = true;
            }
            break;
    }
    
    if (isset($isAvailable)) {
        echo json_encode(array(
        'valid' => $isAvailable,
    ));
    }
    }