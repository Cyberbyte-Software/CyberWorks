<?php
require("classes/session.php");
SessionManager::sessionStart('CyberWorks');
require_once("../gfunctions.php");
$settings = include '../config/settings.php';

if (isset($_POST['player_id'])) {
    if (file_exists('../config/settings.php')) {
        if ($settings['steamAPI'] && $_SESSION['permissions']['view']['steam'] && !$settings['performance']) {
            $api = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=" . $settings['steamAPI'] . "&steamids=" . $_POST['player_id'];
            $player = json_decode(file_get_contents($api), true);
            $player = $player['response'];
            if (empty($player['players'])) $isAvailable = false; else {
                $isAvailable = true;
                $player = $player['players']['0'];
            }

            if (isset($isAvailable) && isset($player['personaname'])) echo json_encode(array(
                'valid' => $isAvailable,
                'name' => $player['personaname'],
                'url' => $player['profileurl']
            ));
            elseif (isset($isAvailable)) echo json_encode(array(
                'valid' => $isAvailable
            ));
        }
    } else echo json_encode(array('valid' => false));
} else echo json_encode(array('valid' => false));