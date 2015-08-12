<?php
if (isset($_SESSION['lang']))
    $licenseLang = $_SESSION['lang'];
else
    $licenseLang = 'en';
$license = array();

if ($licenseLang == 'en') {
    // Civ
    $license['driver'] = 'Driver License';
    $license['boat'] = 'Boating License';
    $license['pilot'] = 'Pilot License';
    $license['gun'] = 'Firearm License';
    $license['dive'] = 'Diving License';
    $license['oil'] = 'Oil Processing';
    $license['heroin'] = 'Processing Heroin';
    $license['marijuana'] = 'Processing Marijuana';
    $license['rebel'] = 'Rebel Training';
    $license['trucking'] = 'Truck License';
    $license['diamond'] = 'Diamond Processing';
    $license['salt'] = 'Salt Processing';
    $license['cocaine'] = 'Cocaine Processing';
    $license['sand'] = 'Sand Processing';
    $license['iron'] = 'Iron Processing';
    $license['copper'] = 'Copper Processing';
    $license['cement'] = 'Cement Mixing License';
    $license['home'] = 'Home Owners License';
	$license['truck'] = 'Truck License';
	
    // Cop
    $license['coastguard'] = 'Coast Guard License';
    $license['swat'] = 'SWAT License';
}
else if ($licenseLang == 'de') {
    // Civ
    $license['driver'] = 'Führerschein';
    $license['boat'] = 'Bootsschein';
    $license['pilot'] = 'Pilotenschein';
    $license['gun'] = 'Waffenschein';
    $license['dive'] = 'Taucherschein';
    $license['oil'] = 'Ölverarbeitung';
    $license['heroin'] = 'Heroinherstellung';
    $license['marijuana'] = 'Marihuanaherstellung';
    $license['rebel'] = 'Rebellenausbildung';
    $license['trucking'] = 'LKW-Führerschein';
    $license['diamond'] = 'Diamantenverarbeitung';
    $license['salt'] = 'Salzverarbeitung';
    $license['sand'] = 'Sandverarbeitung';
    $license['iron'] = 'Eisenverarbeitung';
    $license['copper'] = 'Kupferverarbeitung';
    $license['cement'] = 'Zementherstellung';
    $license['home'] = 'Eigentumsurkunde';
	$license['truck'] = 'LKW Führerschein';
	
    // Cop
    $license['coastguard'] = 'Küstenwache';
    $license['swat'] = 'SWAT-Lizenz';
}
else if ($licenseLang == 'fr') {
    // Civ
    $license['driver'] = 'Permis de Conduire';
    $license['boat'] = 'Permis Bateau';
    $license['pilot'] = 'License de Pilote';
    $license['gun'] = 'Permis de Port d\'Arme';
    $license['dive'] = 'Permis de Plongée';
    $license['oil'] = 'Raffinage de du pétrole';
    $license['heroin'] = 'Traitement d\'Heroine';
    $license['marijuana'] = 'Traitement de Marijuana';
    $license['rebel'] = 'Entrainement rebelle';
    $license['trucking'] = 'Permis Poids Lourds';
    $license['diamond'] = 'Taillage des Diamands';
    $license['salt'] = 'Traitement du Sel';
    $license['sand'] = 'Traitement du Sable';
    $license['iron'] = 'Fonte du Fer';
    $license['copper'] = 'Fonte du Cuivre';
    $license['cement'] = 'Fabrication du Ciment';
    $license['home'] = 'Droit de Propriété';
	$license['truck'] = 'Truck License';
	
    // Cop
    $license['coastguard'] = 'Garde-Cotes';
    $license['swat'] = 'License du SWAT';
}
else if ($licenseLang == 'it') {
    // Civ
    $license['driver'] = 'Licenza di Guida';
    $license['boat'] = 'Licenza Nautica';
    $license['pilot'] = 'Licenza da Pilota';
    $license['gun'] = 'Porto d\'Armi';
    $license['dive'] = 'Licenza di Pesca';
    $license['oil'] = 'Processo Olio';
    $license['heroin'] = 'Processando Eroina';
    $license['marijuana'] = 'Processando Marijuana';
    $license['rebel'] = 'Licenza da Ribelle';
    $license['trucking'] = 'Licenza Camion';
    $license['diamond'] = 'Processo Diamanti';
    $license['salt'] = 'Processo Sale';
    $license['sand'] = 'Processo Sabbia';
    $license['iron'] = 'Processo Ferro';
    $license['copper'] = 'Processo Rame';
    $license['cement'] = 'Processo Cemento';
    $license['home'] = 'Licenza possesso Casa';
	$license['truck'] = 'Truck License';
	
    // Cop
    $license['coastguard'] = 'Licenza Guardia Costiera';
    $license['swat'] = 'Licenza SWAT';
}
else if ($licenseLang == 'por') {
    // Civ
    $license['driver'] = 'Licença de Motorista';
    $license['boat'] = 'Licença de Barco';
    $license['pilot'] = 'Licença de Piloto';
    $license['gun'] = 'Licença de Porte de Armas';
    $license['dive'] = 'Licença de Mergulho';
    $license['oil'] = 'Refinamento de Petróleo';
    $license['heroin'] = 'Processando Heroina';
    $license['marijuana'] = 'Processando Erva';
    $license['rebel'] = 'Treinamento Rebelde';
    $license['trucking'] = 'Licença de Caminhão';
    $license['diamond'] = 'Lapidação de Diamante';
    $license['salt'] = 'Processamento de Sal';
    $license['sand'] = 'Processamento de Areia';
    $license['iron'] = 'Processamento de Ferro';
    $license['copper'] = 'Processamento de Bronze';
    $license['cement'] = 'Licença de Cimento';
    $license['home'] = 'Licença de Casas';
	$license['truck'] = 'Truck License';
	
    // Cop
    $license['coastguard'] = 'Licença de Guarda Costeira';
    $license['swat'] = 'Licença do Bope';
}


function licName($lic,$license)
{
    // Civ
    if ($lic == 'license_civ_driver') return $license['driver'];
    elseif ($lic == 'license_civ_boat') return $license['boat'];
    elseif ($lic == 'license_civ_pilot') return $license['pilot'];
    elseif ($lic == 'license_civ_gun') return $license['gun'];
    elseif ($lic == 'license_civ_dive') return $license['dive'];
    elseif ($lic == 'license_civ_oil') return $license['oil'];
    elseif ($lic == 'license_civ_heroin') return $license['heroin'];
    elseif ($lic == 'license_civ_marijuana') return $license['marijuana'];
    elseif ($lic == 'license_civ_rebel') return $license['rebel'];
    elseif ($lic == 'license_civ_trucking') return $license['trucking'];
    elseif ($lic == 'license_civ_diamond') return $license['diamond'];
    elseif ($lic == 'license_civ_salt') return $license['salt'];
    elseif ($lic == 'license_civ_cocaine') return $license['cocaine'];
    elseif ($lic == 'license_civ_sand') return $license['sand'];
    elseif ($lic == 'license_civ_iron') return $license['iron'];
    elseif ($lic == 'license_civ_copper') return $license['copper'];
    elseif ($lic == 'license_civ_cement') return $license['cement'];
    elseif ($lic == 'license_civ_home') return $license['home'];
	elseif ($lic == 'license_civ_air') return $license['pilot'];
	elseif ($lic == 'license_civ_truck') return $license['truck'];
	
    // Medic
    elseif ($lic == 'license_med_mAir') return $license['pilot'];

    // Cop
    elseif ($lic == 'license_cop_cAir') return $license['pilot'];
    elseif ($lic == 'license_cop_coastguard') return $license['coastguard'];
    elseif ($lic == 'license_cop_swat') return $license['swat'];
    else return $lic;
}
