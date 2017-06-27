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

$crime['fr']['187V'] = 'Homicide involontaire';
$crime['fr']['187'] = 'Homicide';
$crime['fr']['901'] = 'Evasion de prison';
$crime['fr']['215'] = 'Tentative de vol de voiture';
$crime['fr']['213'] = 'Utilisation d\'explosifs illegaux';
$crime['fr']['211'] = 'Vol';
$crime['fr']['207'] = 'Kidnapping';
$crime['fr']['207A'] = 'Tentative de kidnapping';
$crime['fr']['390'] = 'Intoxication publique';
$crime['fr']['487'] = 'Vol majeur';
$crime['fr']['488'] = 'Vol mineur';
$crime['fr']['480'] = 'Fuite';
$crime['fr']['481'] = 'Possession de drogue';
$crime['fr']['482'] = 'Intention de distribution';
$crime['fr']['483'] = 'Traffic de drogue';
$crime['fr']['459'] = 'Cambriolage';
$crime['fr']['666'] = 'Evasion fiscale';
$crime['fr']['667'] = 'Terrorisme';
$crime['fr']['668'] = 'Braconnage';
$crime['fr']['919'] = 'Vol d\'organes';
$crime['fr']['919A'] = 'Tentative de vol d\'organes';
$crime['fr']['1'] = 'Conduite sans feux';
$crime['fr']['2'] = 'Conduite sans permis';
$crime['fr']['3'] = 'Excès de vitesse';
$crime['fr']['4'] = 'Conduite dangereuse';
$crime['fr']['5'] = 'Conduite d\'un véhicule volé';
$crime['fr']['6'] = 'Fuite';
$crime['fr']['7'] = 'Tentative de meurtre';

if (isset($crime[$_SESSION['lang']][$crimeName])) {
    return $crime[$_SESSION['lang']][$crimeName];
} elseif (isset($crime['en'][$crimeName])) {
    return $crime['en'][$crimeName];
} else {
    return $crimeName;
}
}