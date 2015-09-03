<?php
if ($settings['version'] < 0.3) {
    if (!isset($settings['installedLanguage'])) {
        $settings['installedLanguage'] = array();
    }
    $lang = array('English', 'en');
    array_push($settings['installedLanguage'], $lang);
    $settings['version'] = 0.3;
    file_put_contents('config/settings.php', '<?php return ' . var_export($settings, true) . ';');
    $updated = true;
}
if ($settings['version'] < 0.4) {
    $sql = 'ALTER TABLE  `users` ADD  `twoFactor` VARCHAR(25) NULL ,
    ADD  `token` VARCHAR(64) NULL;
    ADD  `backup` VARCHAR(255) NULL;';
    $db_connection->query($sql);
    $settings['2factor'] = true;
    $settings['force2factor'] = 'none';
    $settings['version'] = 0.4;
    $settings['item'] = array(5,10,15,25,50);
    file_put_contents('config/settings.php', '<?php return ' . var_export($settings, true) . ';');
    $updated = true;
}
if (isset($updated)) {
    message('Updated :)');
}
