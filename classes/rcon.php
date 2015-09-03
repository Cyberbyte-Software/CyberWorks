<?php
function strToHex($string)
{
    $hex = '';
    for ($i = 0; $i < strlen($string); $i++)
    {
        $hex .= dechex(ord($string[$i]));
    }
    return $hex;
}

function hexToStr($hex)
{
    $string = '';
    for ($i = 0; $i < strlen($hex) - 1; $i += 2)
    {
        $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
    }
    return $string;
}

function computeUnsignedCRC32($str) {
    sscanf(crc32($str), "%u", $var);
    $var = dechex($var + 0);
    return $var;
}

function dec_to_hex($dec)
{
    $sign = ""; // suppress errors
    $h = null;
    if ($dec < 0) { $sign = "-"; $dec = abs($dec); }

    $hex = Array(0 => 0, 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5,
                    6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 'a',
                    11 => 'b', 12 => 'c', 13 => 'd', 14 => 'e',
                    15 => 'f');

    do
    {
        $h = $hex[($dec % 16)] . $h;
        $dec /= 16;
    }
    while ($dec >= 1);

    return $sign . $h;
}

function get_checksum($cs)
{
    $var = computeUnsignedCRC32($cs);
    $x = ('0x');
    $a = substr($var, 0, 2);
    $a = $x . $a;
    $b = substr($var, 2, 2);
    $b = $x . $b;
    $c = substr($var, 4, 2);
    $c = $x . $c;
    $d = substr($var, 6, 2);
    $d = $x . $d;
    return chr($d) . chr($c) . chr($b) . chr($a);
}

function fixAnswer($answer)
{

    if ($answer == '') {
        return;
    }
    $answer = substr($answer, strpos($answer, 'Players on server'));
    $start_symbol = 0;

    while ($start_defect = strpos($answer, 'BE', $start_symbol)) {
        $last_symbol_defect = false;
        $seven_symbols = substr($answer, $start_defect, 7);
        $start_symbol = $start_defect + 7;
        if (mb_detect_encoding($seven_symbols, 'ASCII', true) == false) {
            for ($i = 2; $i < 7; $i++) {
                $symbol = substr($seven_symbols, $i, 1);
                $ord = ord($symbol);
                if (mb_detect_encoding($symbol) == 'ASCII' && $ord < 33 OR $ord > 126) {
                    $last_symbol_defect = $i;
                }
            }


            if ($last_symbol_defect) {
                    $answer = str_replace(substr($seven_symbols, 0, $last_symbol_defect + 1), '', $answer);
            }
        }
    }

    return $answer;
}

function rcon($serverip, $serverport, $rconpassword, $cmd)
{
    $passhead = chr(0xFF) . chr(0x00);
    $head = chr(0x42) . chr(0x45);
    $pass = $passhead . $rconpassword;
    $answer = "";
    $checksum = get_checksum($pass);

    $loginmsg = $head . $checksum . $pass;

    $rcon = fsockopen("udp://" . $serverip, $serverport, $errno, $errstr, 1);
    stream_set_timeout($rcon, 1);

    if (!$rcon) {
        echo "ERROR: $errno - $errstr<br />\n";
    } else {
        fwrite($rcon, $loginmsg);
        $res = fread($rcon, 16);

        $cmdhead = chr(0xFF) . chr(0x01) . chr(0x00);
        //$cmd = "Players";
        $cmd = $cmdhead . $cmd;
        $checksum = get_checksum($cmd);
        $cmdmsg = $head . $checksum . $cmd;
        $hlen = strlen($head . $checksum . chr(0xFF) . chr(0x01));

        fwrite($rcon, $cmdmsg);
        $answer = fread($rcon, 102400);

        if (strToHex(substr($answer, 9, 1)) == "0") {
            $count = strToHex(substr($answer, 10, 1));
            for ($i = 0; $i < $count - 1; $i++) {
                $answer .= fread($rcon, 102400);
            }
        }
        $cmd = "Exit";
        $cmd = $cmdhead . $cmd;
        $checksum = get_checksum($cmd);
        $cmdmsg = $head . $checksum . $cmd;
        fwrite($rcon, $cmdmsg);
    }

    $answer = fixAnswer($answer);
    return $answer;
}
