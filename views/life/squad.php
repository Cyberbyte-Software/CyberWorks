<?php
$xml = '<?xml version="1.0"?>
<?DOCTYPE squad SYSTEM "squad.dtd"?>
<?xml-stylesheet href="squad.xsl?" type="text/xsl"?>

<squad nick="CZ">
<name>Clan of Zombies</name>
<email>clanofzombies@clanofzombies.com</email>
<web></web>
<picture>logo.paa</picture>
title>CZ</title>';

$sql = "SELECT `name`,`members` FROM `gangs` WHERE `id` = '" . $id . "';";
$result = $db_link->query($sql);
$gang = $result->fetch_object();
$members = str_replace('`]"', '', str_replace('"[`', '', $gang->members));
$members = explode('`,`', $members);
foreach ($members as $member) {
    $name = nameID($member);
    $xml .= '<member id="' . $member . '" nick="' . $name . '">
    <name>'.$name . '</name><email></email><icq></icq><remark></remark></member>';
};

$xml .= '</squad>';