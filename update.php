<?php
$settings = 'config/settings.php';
if (!isset($settings['installedLanguage'])) $settings['installedLanguage'] = array();
$lang = array('English','en');
array_push($settings['installedLanguage'], $lang);
file_put_contents('config/settings.php', '<?php return ' . var_export($settings, true) . ';');
die('All done!');



//ALTER TABLE  `users` ADD  `twoFactor` VARCHAR( 25 ) NULL ,
//ADD  `backup` VARCHAR( 25 ) NULL ;
//ADD  `backup` VARCHAR( 255 ) NULL ;
