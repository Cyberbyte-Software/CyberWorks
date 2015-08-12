<?php
require "../classes/rcon.php";
require "../gfunctions.php";

if (isset($_POST['sid']) && isset($_POST['command'])) {
    $db_connection = masterConnect();
    $sid = clean($_POST['sid'], "int");
    $rid = clean($_POST['id'], "int");
    $cmd = clean($_POST['command'], "string");

    $sql = "SELECT * FROM `servers` WHERE `use_sq` = 1 AND `sid` = " . $sid . ";";
    $result_of_query = $db_connection->query($sql);
    if ($result_of_query->num_rows == 1) {
        $server = $result_of_query->fetch_object();
        try
        {
            $answer = rcon(decrypt($server->sq_ip), decrypt($server->sq_port), decrypt($server->rcon_pass), $cmd);
        }
        catch (Exception $e)
        {
            echo $e->getMessage( );
        }
    }
}
