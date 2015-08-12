<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CyberWorks Server Admin Panel">
    <meta name="keyword" content="CyberWorks, Server, Admin Panel">

    <title>Cyber Works</title>

    <link rel="stylesheet" type="text/css" href="<?php echo $settings['url'] ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $settings['url'] ?>assets/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $settings['url'] ?>assets/js/gritter/css/jquery.gritter.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $settings['url'] ?>assets/css/animate.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $settings['url'] ?>assets/css/awesomplete.min.css" />
    
    <script type="text/javascript" src="<?php echo $settings['url'] ?>assets/js/jquery-1.11.3.min.js"></script> 
    
    <link href="<?php echo $settings['url'] ?>assets/css/style.css" rel="stylesheet">
    <link href="<?php echo $settings['url'] ?>assets/css/style-responsive.css" rel="stylesheet">
    
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <?php
    if (isset($settings)){
        foreach ($settings['plugins'] as &$plugin) {
            if (file_exists("plugins/". $plugin. "/assets/style.css")) {
                echo '<link rel="stylesheet" type="text/css" href="'. $settings['url'] . 'plugins/' . $plugin . '/assets/style.css">';
            }
        }
    } ?>

    <!--Copyright CyberByte 2015 http://cyberbyte.org.uk/-->
</head>