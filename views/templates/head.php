<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CyberWorks Server Admin Panel">
    <meta name="keyword" content="CyberWorks, Server, Admin Panel">

    <title>Cyber Works</title>

    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Ruda:400,700,900">
    <link rel="stylesheet" type="text/css" href="<?php echo $settings['url'] ?>assets/css/main.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    
    <script type="text/javascript" src="<?php echo $settings['url'] ?>assets/js/jquery-1.11.3.min.js"></script> 
    
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <?php
    if (isset($settings)) {
        foreach ($settings['plugins'] as &$plugin) {
            if (file_exists("plugins/" . $plugin . "/assets/style.css")) {
                echo '<link rel="stylesheet" type="text/css" href="' . $settings['url'] . 'plugins/' . $plugin . '/assets/style.css">';
            }
        }
    } ?>
    <!--Copyright CyberByte 2015 http://cyberbyte.org.uk/-->
</head>