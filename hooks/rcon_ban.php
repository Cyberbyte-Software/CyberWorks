<?php
require "../classes/rcon.php";
require "../gfunctions.php";

//if (isset($_POST['ID']) && isset($_POST['GSID']) && isset($_POST['REASON']) && isset($_POST['TIME'])) {
if (false) {
    $db_connection = masterConnect();
    $sid = clean($_POST['GSID'], "int");
    $rid = clean($_POST['ID'], "int");
    $time = clean($_POST['TIME'], "int");
    $reason = clean($_POST['REASON'], "string");

    $sql = "SELECT * FROM `servers` WHERE `use_sq` = 1 AND `sid` = " . $sid . ";";
    $result_of_query = $db_connection->query($sql);
    if ($result_of_query->num_rows == 1) {
        $server = $result_of_query->fetch_object();
        try
        {
            $cmd = 'ban ' . $rid . ' ' . $time . ' ' . $reason;
            $answer = rcon(decrypt($server->sq_ip), decrypt($server->sq_port), decrypt($server->rcon_pass), $cmd);
        }
        catch (Exception $e)
        {
            echo $e->getMessage( );
        }
    }
}
