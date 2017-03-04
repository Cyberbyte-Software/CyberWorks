<?php
require "../classes/rcon.php";
require "../gfunctions.php";

session_name('CyberWorks');
session_set_cookie_params(1209600);
session_start();
if (isset($_POST['sid']) && isset($_POST['command']) && isset($_SESSION['user_level'])) {
    if ($_SESSION['user_level'] > 3) {
        $db_connection = masterConnect();
        $sid = clean((isset($_POST['sid'])) ? $_POST['sid'] : 0, "int");
        $rid = clean((isset($_POST['id'])) ? $_POST['id'] : 0, "int");
        $cmd = clean((isset($_POST['command'])) ? $_POST['command'] : 0, "string");

        $sql = "SELECT * FROM `servers` WHERE `use_sq` = 1 AND `sid` = " . $sid . ";";
        $result_of_query = $db_connection->query($sql);
        if ($result_of_query->num_rows == 1) {
            $server = $result_of_query->fetch_object();
            try
            {
                $rcon = new \Nizarii\ARC(decrypt($server->sq_ip), decrypt($server->rcon_pass), (int)decrypt($server->sq_port));
                $answer = $rcon->command($cmd);
            }
            catch (Exception $e)
            {
                echo $e->getMessage( );
            }
        }
    }
}
