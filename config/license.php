<?php
if (isset($_SESSION['lang'])) {
    $licenseLang = $_SESSION['lang'];
} else {
    $licenseLang = 'en';
}
$license = array();

if ($licenseLang == 'en') {
    $license['license_civ_driver'] = 'Driver License';
    $license['license_civ_boat'] = 'Boating License';
    $license['license_civ_pilot'] = 'Pilot License';
    $license['license_civ_gun'] = 'Firearm License';
    $license['license_civ_dive'] = 'Diving License';
    $license['license_civ_oil'] = 'Oil Processing';
    $license['license_civ_heroin'] = 'Processing Heroin';
    $license['license_civ_marijuana'] = 'Processing Marijuana';
    $license['license_civ_rebel'] = 'Rebel Training';
    $license['license_civ_trucking'] = 'Truck License';
    $license['license_civ_diamond'] = 'Diamond Processing';
    $license['license_civ_salt'] = 'Salt Processing';
    $license['license_civ_cocaine'] = 'Cocaine Processing';
    $license['license_civ_sand'] = 'Sand Processing';
    $license['license_civ_iron'] = 'Iron Processing';
    $license['license_civ_copper'] = 'Copper Processing';
    $license['license_civ_cement'] = 'Cement Mixing License';
    $license['license_civ_home'] = 'Home Owners License';
    $license['license_civ_truck'] = 'Truck License';
    $license['license_civ_pilot'] = 'Medical Pilot';
    $license['license_cop_cAir'] = 'Cop Pilot';
    $license['license_cop_coastguard'] = 'Coast Guard License';
    $license['license_cop_swat'] = 'SWAT License';
} else if ($licenseLang == 'de') {
    // Civ
    $license['license_civ_driver'] = 'Führerschein';
    $license['license_civ_boat'] = 'Bootsschein';
    $license['license_civ_pilot'] = 'Pilotenschein';
    $license['license_civ_gun'] = 'Waffenschein';
    $license['license_civ_dive'] = 'Taucherschein';
    $license['license_civ_oil'] = 'Ölverarbeitung';
    $license['license_civ_heroin'] = 'Heroinherstellung';
    $license['license_civ_marijuana'] = 'Marihuanaherstellung';
    $license['license_civ_rebel'] = 'Rebellenausbildung';
    $license['license_civ_trucking'] = 'LKW-Führerschein';
    $license['license_civ_diamond'] = 'Diamantenverarbeitung';
    $license['license_civ_salt'] = 'Salzverarbeitung';
    $license['license_civ_sand'] = 'Sandverarbeitung';
    $license['license_civ_iron'] = 'Eisenverarbeitung';
    $license['license_civ_copper'] = 'Kupferverarbeitung';
    $license['license_civ_cement'] = 'Zementherstellung';
    $license['license_civ_home'] = 'Eigentumsurkunde';
    $license['license_civ_truck'] = 'LKW Führerschein';

    // Cop
    $license['license_cop_coastguard'] = 'Küstenwache';
    $license['license_cop_swat'] = 'SWAT-Lizenz';
} else if ($licenseLang == 'fr') {
    // Civ
    $license['license_civ_driver'] = 'Permis de Conduire';
    $license['license_civ_boat'] = 'Permis Bateau';
    $license['license_civ_pilot'] = 'License de Pilote';
    $license['license_civ_gun'] = 'Permis de Port d\'Arme';
    $license['license_civ_dive'] = 'Permis de Plongée';
    $license['license_civ_oil'] = 'Raffinage de du pétrole';
    $license['license_civ_heroin'] = 'Traitement d\'Heroine';
    $license['license_civ_marijuana'] = 'Traitement de Marijuana';
    $license['license_civ_rebel'] = 'Entrainement rebelle';
    $license['license_civ_trucking'] = 'Permis Poids Lourds';
    $license['license_civ_diamond'] = 'Taillage des Diamands';
    $license['license_civ_salt'] = 'Traitement du Sel';
    $license['license_civ_sand'] = 'Traitement du Sable';
    $license['license_civ_iron'] = 'Fonte du Fer';
    $license['license_civ_copper'] = 'Fonte du Cuivre';
    $license['license_civ_cement'] = 'Fabrication du Ciment';
    $license['license_civ_home'] = 'Droit de Propriété';
    $license['license_civ_truck'] = 'Truck License';


    // Cop
    $license['license_cop_coastguard'] = 'Garde-Cotes';
    $license['license_cop_swat'] = 'License du SWAT';
} else if ($licenseLang == 'it') {
    // Civ
    $license['license_civ_driver'] = 'Licenza di Guida';
    $license['license_civ_boat'] = 'Licenza Nautica';
    $license['license_civ_pilot'] = 'Licenza da Pilota';
    $license['license_civ_gun'] = 'Porto d\'Armi';
    $license['license_civ_dive'] = 'Licenza di Pesca';
    $license['license_civ_oil'] = 'Processo Olio';
    $license['license_civ_heroin'] = 'Processando Eroina';
    $license['license_civ_marijuana'] = 'Processando Marijuana';
    $license['license_civ_rebel'] = 'Licenza da Ribelle';
    $license['license_civ_trucking'] = 'Licenza Camion';
    $license['license_civ_diamond'] = 'Processo Diamanti';
    $license['license_civ_salt'] = 'Processo Sale';
    $license['license_civ_sand'] = 'Processo Sabbia';
    $license['license_civ_iron'] = 'Processo Ferro';
    $license['license_civ_copper'] = 'Processo Rame';
    $license['license_civ_cement'] = 'Processo Cemento';
    $license['license_civ_home'] = 'Licenza possesso Casa';
    $license['license_civ_truck'] = 'Truck License';

    // Cop
    $license['license_cop_coastguard'] = 'Licenza Guardia Costiera';
    $license['license_cop_swat'] = 'Licenza SWAT';
} else if ($licenseLang == 'por') {
    // Civ
    $license['license_civ_driver'] = 'Licença de Motorista';
    $license['license_civ_boat'] = 'Licença de Barco';
    $license['license_civ_pilot'] = 'Licença de Piloto';
    $license['license_civ_gun'] = 'Licença de Porte de Armas';
    $license['license_civ_dive'] = 'Licença de Mergulho';
    $license['license_civ_oil'] = 'Refinamento de Petróleo';
    $license['license_civ_heroin'] = 'Processando Heroina';
    $license['license_civ_marijuana'] = 'Processando Erva';
    $license['license_civ_rebel'] = 'Treinamento Rebelde';
    $license['license_civ_trucking'] = 'Licença de Caminhão';
    $license['license_civ_diamond'] = 'Lapidação de Diamante';
    $license['license_civ_salt'] = 'Processamento de Sal';
    $license['license_civ_sand'] = 'Processamento de Areia';
    $license['license_civ_iron'] = 'Processamento de Ferro';
    $license['license_civ_copper'] = 'Processamento de Bronze';
    $license['license_civ_cement'] = 'Licença de Cimento';
    $license['license_civ_home'] = 'Licença de Casas';
    $license['license_civ_truck'] = 'Truck License';

    // Cop
    $license['license_cop_coastguard'] = 'Licença de Guarda Costeira';
    $license['license_cop_swat'] = 'Licença do Bope';
}


function licName($lic, $license)
{
    // Civ
    if ($lic == 'license_civ_driver') {
        return $license['license_civ_driver'];
    } elseif ($lic == 'license_civ_boat') {
        return $license['license_civ_boat'];
    } elseif ($lic == 'license_civ_pilot') {
        return $license['license_civ_pilot'];
    } elseif ($lic == 'license_civ_gun') {
        return $license['license_civ_gun'];
    } elseif ($lic == 'license_civ_dive') {
        return $license['license_civ_dive'];
    } elseif ($lic == 'license_civ_oil') {
        return $license['license_civ_oil'];
    } elseif ($lic == 'license_civ_heroin') {
        return $license['license_civ_heroin'];
    } elseif ($lic == 'license_civ_marijuana') {
        return $license['license_civ_marijuana'];
    } elseif ($lic == 'license_civ_rebel') {
        return $license['license_civ_rebel'];
    } elseif ($lic == 'license_civ_trucking') {
        return $license['license_civ_trucking'];
    } elseif ($lic == 'license_civ_diamond') {
        return $license['license_civ_diamond'];
    } elseif ($lic == 'license_civ_salt') {
        return $license['license_civ_salt'];
    } elseif ($lic == 'license_civ_cocaine') {
        return $license['license_civ_cocaine'];
    } elseif ($lic == 'license_civ_sand') {
        return $license['license_civ_sand'];
    } elseif ($lic == 'license_civ_iron') {
        return $license['license_civ_iron'];
    } elseif ($lic == 'license_civ_copper') {
        return $license['license_civ_copper'];
    } elseif ($lic == 'license_civ_cement') {
        return $license['license_civ_cement'];
    } elseif ($lic == 'license_civ_home') {
        return $license['license_civ_home'];
    } elseif ($lic == 'license_civ_air') {
        return $license['license_civ_pilot'];
    } elseif ($lic == 'license_civ_truck') {
        return $license['license_civ_truck'];
    }

    // Medic
    elseif ($lic == 'license_med_mAir') {
        return $license['license_civ_pilot'];
    }

    // Cop
    elseif ($lic == 'license_cop_cAir') {
        return $license['license_civ_pilot'];
    } elseif ($lic == 'license_cop_coastguard') {
        return $license['license_cop_coastguard'];
    } elseif ($lic == 'license_cop_swat') {
        return $license['license_cop_swat'];
    } else {
        return $lic;
    }
    }
