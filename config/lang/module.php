<?php
include_once('config/lang/lang.en.php');

if(isset($settings)) {
    foreach ($settings['plugins'] as &$plugin) {
        if (file_exists("plugins/". $plugin. "/lang/lang.php")) {
            include("plugins/". $plugin."/lang/lang.php");
        }
    }
}