<?php
function crimeName($crimeName) {
$crime = array();

$crime['en']['187V'] = 'Vehicular Manslaughter';
$crime['en']['187'] = 'Manslaughter';
$crime['en']['901'] = 'Escaping Jail';
$crime['en']['215'] = 'Attempted Auto Theft';
$crime['en']['213'] = 'Use of illegal explosives';
$crime['en']['211'] = 'Robbery';
$crime['en']['207'] = 'Kidnapping';
$crime['en']['207A'] = 'Attempted Kidnapping';
$crime['en']['390'] = 'Public Intoxication';
$crime['en']['487'] = 'Grand Theft';
$crime['en']['488'] = 'Petty Theft';
$crime['en']['480'] = 'Hit and run';
$crime['en']['481'] = 'Drug Possession';
$crime['en']['482'] = 'Intent to distribute';
$crime['en']['483'] = 'Drug Trafficking';
$crime['en']['459'] = 'Burglary';
$crime['en']['666'] = 'Tax Evasion';
$crime['en']['667'] = 'Terrorism';
$crime['en']['668'] = 'Unlicensed Hunting';
$crime['en']['919'] = 'Organ Theft';
$crime['en']['919A'] = 'Attempted Organ Theft';
$crime['en']['1'] = 'Driving without Lights';
$crime['en']['2'] = 'Driving without License';
$crime['en']['3'] = 'Driving over the Speed Limit';
$crime['en']['4'] = 'Reckless Driving';
$crime['en']['5'] = 'Driving Stolen Vehicle';
$crime['en']['6'] = 'Hit and Run';
$crime['en']['7'] = 'Attempted Murder';

if (isset($crime[$_SESSION['lang']][$crimeName])) {
    return $crime[$_SESSION['lang']][$crimeName];
} elseif (isset($crime['en'][$crimeName])) {
    return $crime['en'][$crimeName];
} else {
    return $crimeName;
}
}