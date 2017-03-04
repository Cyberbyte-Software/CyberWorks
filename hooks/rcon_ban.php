<?php
require "../classes/rcon.php";
require "../gfunctions.php";

//if (isset($_POST['ID']) && isset($_POST['GSID']) && isset($_POST['REASON']) && isset($_POST['TIME'])) {
if (false) {
    $db_connection = masterConnect();
    $sid = clean((isset($_POST['GSID'])) ? $_POST['GSID'] : 0, "int");
    $rid = clean((isset($_POST['ID'])) ? $_POST['id'] : 0, "int");
    $time = clean((isset($_POST['TIME'])) ? $_POST['TIME'] : 0, "int");
    $reason = clean((isset($_POST['REASON'])) ? $_POST['REASON'] : 0, "int");


    $sql = "SELECT * FROM `servers` WHERE `use_sq` = 1 AND `sid` = " . $sid . ";";
    $result_of_query = $db_connection->query($sql);
    if ($result_of_query->num_rows == 1) {
        $server = $result_of_query->fetch_object();
        try
        {
            $rcon = new \Nizarii\ARC(decrypt($server->sq_ip), decrypt($server->rcon_pass), (int)decrypt($server->sq_port));
            $answer = $rcon->banPlayer($rid, $reason, $time);
        }
        catch (Exception $e)
        {
            echo $e->getMessage( );
        }
    }
}
