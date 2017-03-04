<?php
require "../classes/rcon.php";
require "../gfunctions.php";

if (isset($_GET['sid'])) {
    $sid = clean((isset($_GET['sid'])) ? $_GET['sid'] : 0, "int");
    $db_connection = masterConnect();
    $sql = "SELECT `sq_ip`,`sq_port`,`rcon_pass` FROM `servers` WHERE `use_sq` = 1 AND `sid` = " . $sid . ";";
    $result_of_query = $db_connection->query($sql);
    if ($result_of_query->num_rows == 1) {
        $server = $result_of_query->fetch_object();
        try
        {
            $rcon = new \Nizarii\ARC(decrypt($server->sq_ip), decrypt($server->rcon_pass), (int)decrypt($server->sq_port));
            $answer = $rcon->getPlayersArray();
//            var_dump($answer);
//            $k = strrpos($answer, "---");
//            $l = strrpos($answer, "(");
//            $out = substr($answer, $k + 4, $l - $k - 5);
//            $array = preg_split('/$\R?^/m', $out);
//            $playersr = array();
//
//            if ($array[0] == '(0 players in total')  $array = array();
//
//            for ($i = 0; $i < count($array); $i++)
//            {
//                $playersr[$i] = array_values(array_diff(explode(' ', $array[$i]), array(null)));
//            }

            echo json_encode($answer);

        }
        catch (Exception $e)
        {
            echo $e->getMessage( );
            var_dump($e);
        }
    }
}