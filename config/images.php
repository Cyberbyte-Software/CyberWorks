<?php

function skinImage($uniform)
{
    $playerSkin = $this->getPlayerSkin(stripArray($uniform, 3));
    return "assets/img/uniform/$playerSkin.jpg";
}

function getPlayerSkin($uniform)
{
    $playerSkins = array(
        'U_B_CombatUniform_mcam',
        'U_B_CombatUniform_mcam_tshirt',
        'U_B_CombatUniform_mcam_vest',
        'U_B_GhillieSuit',
        'U_B_HeliPilotCoveralls',
        'U_B_Wetsuit',
        'U_O_CombatUniform_ocamo',
        'U_O_GhillieSuit',
        'U_O_PilotCoveralls',
        'U_O_Wetsuit',
        'U_C_Poloshirt_blue',
        'U_C_Poloshirt_burgundy',
        'U_C_Poloshirt_stripped',
        'U_C_Poloshirt_tricolour',
        'U_C_Poloshirt_salmon',
        'U_C_Poloshirt_redwhite',
        'U_C_Commoner1_1',
        'U_C_Commoner1_2',
        'U_C_Commoner1_3',
        'U_Rangemaster',
        'U_OrestesBody',
        'U_NikosBody',
        'U_BasicBody',
        'U_B_CombatUniform_mcam_worn',
        'U_B_SpecopsUniform_sgg',
        'U_B_PilotCoveralls',
        'U_O_CombatUniform_oucamo',
        'U_O_SpecopsUniform_ocamo',
        'U_O_SpecopsUniform_blk',
        'U_O_OfficerUniform_ocamo',
        'U_I_CombatUniform',
        'U_I_CombatUniform_tshirt',
        'U_I_CombatUniform_shortsleeve',
        'U_I_pilotCoveralls',
        'U_I_HeliPilotCoveralls',
        'U_I_GhillieSuit',
        'U_I_OfficerUniform',
        'U_I_Wetsuit',
        'U_Competitor',
        'U_MillerBody',
        'U_KerryBody',
        'U_IG_Guerilla1_1',
        'U_IG_Guerilla2_1',
        'U_IG_Guerilla2_2',
        'U_IG_Guerilla2_3',
        'U_IG_Guerilla3_1',
        'U_IG_Guerilla3_2',
        'U_IG_leader',
        'U_BG_Guerilla1_1',
        'U_BG_Guerilla2_1',
        'U_BG_Guerilla2_2',
        'U_BG_Guerilla2_3',
        'U_BG_Guerilla3_1',
        'U_BG_Guerilla3_2',
        'U_BG_leader',
        'U_OG_Guerilla1_1',
        'U_OG_Guerilla2_1',
        'U_OG_Guerilla2_2',
        'U_OG_Guerilla2_3',
        'U_OG_Guerilla3_1',
        'U_OG_Guerilla3_2',
        'U_OG_leader',
        'U_C_Poor_1',
        'U_C_Poor_2',
        'U_C_WorkerCoveralls',
        'U_C_HunterBody_grn',
        'U_C_Poor_shorts_1',
        'U_C_Commoner_shorts',
        'U_C_ShirtSurfer_shorts',
        'U_C_TeeSurfer_shorts_1',
        'U_C_TeeSurfer_shorts_2',
        'U_B_CTRG_1',
        'U_B_CTRG_2',
        'U_B_CTRG_3',
        'U_B_survival_uniform',
        'U_I_G_Story_Protagonist_F',
        'U_I_G_resistanceLeader_F',
        'U_C_Journalist',
        'U_C_Scientist',
        'U_NikosAgedBody'
    );
    if (in_array($uniform, $playerSkins)) {
        return $uniform;
    }
    return "Default";
}

function getPic($input)
{
    $carPics = array(
        'B_Boat_Armed_01_minigun_F',
        'B_Boat_Transport_01_F',
        'B_G_Offroad_01_armed_F',
        'B_G_Offroad_01_F',
        'B_G_Offroad_01_F_1',
        'B_G_Van_01_transport_F',
        'B_Heli_Light_01_F',
        'B_Heli_Transport_01_F',
        'B_Lifeboat',
        'B_MRAP_01_F',
        'B_MRAP_01_hmg_F',
        'B_Quadbike_01_F',
        'B_SDV_01_F',
        'B_Truck_01_ammo_F',
        'B_Truck_01_box_F',
        'B_Truck_01_covered_F',
        'B_Truck_01_transport_F',
        'C_Boat_Civil_01_F',
        'C_Boat_Civil_01_police_F',
        'C_Hatchback_01_F',
        'C_Hatchback_01_sport_F',
        'C_Offroad_01_F',
        'C_Rubberboat',
        'C_SUV_01_F',
        'C_Van_01_box_F',
        'C_Van_01_fuel_F',
        'C_Van_01_transport_F',
        'I_Heli_light_03_unarmed_F',
        'I_Heli_Transport_02_F',
        'I_MRAP_03_F',
        'I_Truck_02_covered_F',
        'I_Truck_02_transport_F',
        'O_Heli_Light_02_unarmed_F',
        'O_MRAP_02_F'
    );
    if (in_array($input, $carPics)) {
        return $input;
    }
    return "Default";
}