<?php
require_once(realpath($settings['url']) . "config/carNames.php");
require_once(realpath($settings['url']) . "config/images.php");
require_once(realpath($settings['url']) . "config/license.php");
require_once(realpath($settings['url']) . "config/crimes.php");

$db_link = serverConnect();

function getPlayerSkin($input, $list) {
    if ($input !== '[]') {
        $name = after('[`', $input);
        $name = before('`', $name);

        if (in_array($name, $list)) {
            return $name;
        } else {
            return "Default";
        }
    } else {
        return "Default";
    }
}

if (isset($_POST["editType"])) {
    if (formtoken::validateToken($_POST)) {
        switch ($_POST["editType"]) {
            case "civ_inv":
                $civ_gear_value = $_POST["civ_inv_value"];
                $update = "UPDATE `players` SET civ_gear = '" . $civ_gear_value . "' WHERE `uid` = '" . $uID . "' ";
                $result_of_query = $db_link->query($update);
                logAction($_SESSION['user_name'], $lang['edited'] . ' ' . nameID($player->playerid, $db_link) . '(' . $player->playerid . ') ' . $lang['civ'] . ' ' . $lang['inventory'], 1);
                message($lang['edited'] . ' ' . $lang['civ'] . ' ' . $lang['inventory']);
                break;

            case "cop_inv":
                $cop_gear_value = $_POST["cop_inv_value"];
                $update = "UPDATE `players` SET cop_gear = '" . $cop_gear_value . "' WHERE `uid` = '" . $uID . "' ";
                $result_of_query = $db_link->query($update);
                logAction($_SESSION['user_name'], $lang['edited'] . ' ' . nameID($player->playerid, $db_link) . '(' . $player->playerid . ') ' . $lang['cop'] . ' ' . $lang['inventory'], 1);
                message($lang['edited'] . ' ' . $lang['cop'] . ' ' . $lang['inventory']);
                break;

            case "med_inv":
                $med_gear_value = $_POST["med_inv_value"];
                $update = "UPDATE `players` SET med_gear = '" . $med_gear_value . "' WHERE `uid` = '" . $uID . "' ";
                $result_of_query = $db_link->query($update);
                logAction($_SESSION['user_name'], $lang['edited'] . ' ' . nameID($player->playerid, $db_link) . '(' . $player->playerid . ') ' . $lang['medic'] . ' ' . $lang['inventory'], 1);
                message($lang['edited'] . ' ' . $lang['medic'] . ' ' . $lang['inventory']);
                break;

            case "player_edit":
                if ($_SESSION['user_level'] >= 4) {
                    $coplevel = clean(intval($_POST["player_coplvl"]), 'int');
                    $mediclevel = clean(intval($_POST["player_medlvl"]), 'int');
                    $donorlevel = clean(intval($_POST["player_donlvl"]), 'int');
                    $adminlevel = clean(intval($_POST["player_adminlvl"]), 'int');
                    $cash = clean(intval($_POST["player_cash"]), 'int');
                    $bankacc = clean(intval($_POST["player_bank"]), 'int');
                    $sql = "SELECT * FROM `players` WHERE `uid` = '" . $uID . "'";
                    $result = $db_link->query($sql);
                    if ($result->num_rows > 0) {
                        $player = $result->fetch_object();

                        if ($coplevel != $player->coplevel) logAction($_SESSION['user_name'], $lang['edited'] . " " . nameID($player->playerid, $db_link) . "(" . $player->playerid . ") " . $lang['cop'] . " " . $lang['level'] . " " . $lang['from'] . " (" . $player->coplevel . ") " . $lang['to'] . " (" . $coplevel . ")", 2);
                        if ($mediclevel != $player->mediclevel) logAction($_SESSION['user_name'], $lang['edited'] . " " . nameID($player->playerid, $db_link) . "(" . $player->playerid . ") " . $lang['medic'] . " " . $lang['level'] . " " . $lang['from'] . " (" . $player->mediclevel . ") " . $lang['to'] . " (" . $mediclevel . ")", 2);
                        if ($donorlevel != $player->$settings['donorFormat']) logAction($_SESSION['user_name'], $lang['edited'] . " " . nameID($player->playerid, $db_link) . "(" . $player->playerid . ") " . $lang['donator'] . " " . $lang['level'] . " " . $lang['from'] . " (" . $player->$settings['donorFormat'] . ") " . $lang['to'] . " (" . $donorlevel . ")", 2);
                        if ($adminlevel != $player->adminlevel) logAction($_SESSION['user_name'], $lang['edited'] . " " . nameID($player->playerid, $db_link) . "(" . $player->playerid . ") " . $lang['admin'] . " " . $lang['level'] . " " . $lang['from'] . " (" . $player->adminlevel . ") " . $lang['to'] . " (" . $adminlevel . ")", 2);
                        if ($cash != $player->cash) logAction($_SESSION['user_name'], $lang['edited'] . " " . nameID($player->playerid, $db_link) . "(" . $player->playerid . ") " . $lang['cash'] . " " . $lang['from'] . " (" . $player->cash . ") " . $lang['to'] . " (" . $cash . ")", 2);
                        if ($bankacc != $player->bankacc) logAction($_SESSION['user_name'], $lang['edited'] . " " . nameID($player->playerid, $db_link) . "(" . $player->playerid . ") " . $lang['bank'] . " " . $lang['from'] . " (" . $player->bankacc . ") " . $lang['to'] . " (" . $bankacc . ")", 2);

                        $update = "UPDATE `players` SET coplevel = '" . $coplevel . "', mediclevel = '" . $mediclevel . "', ".$settings['donorFormat']."= '" . $donorlevel . "', adminlevel = '" . $adminlevel . "', cash = '" . $cash . "', bankacc = '" . $bankacc . "' WHERE `uid` = '" . $uID . "' ";
                        $result_of_query = $db_link->query($update);
                        message($lang['edited'] . ' ' . nameID($player->playerid, $db_link));
                    } else {
                        message("ERROR");
                    }
                } elseif ($_SESSION['user_level'] >= 3) {
                    $coplevel = intval($_POST["player_coplvl"]);
                    $mediclevel = intval($_POST["player_medlvl"]);
                    $cash = intval($_POST["player_cash"]);
                    $bankacc = intval($_POST["player_bank"]);
                    $donorlevel = isset($_POST['player_donlvl']) ? intval($_POST['player_donlvl']) : null;
                    $sql = "SELECT * FROM `players` WHERE `uid` = '" . $uID . "'";
                    $result = $db_link->query($sql);
                    if ($result->num_rows > 0) {
                        $player = $result->fetch_object();
                        if (is_null($donorlevel)) {
                            $donorlevel = $player->$settings['donorFormat'];
                        }
                        if ($coplevel != $player->coplevel) logAction($_SESSION['user_name'], $lang['edited'] . " " . nameID($player->playerid, $db_link) . "(" . $player->playerid . ") " . $lang['cop'] . " " . $lang['level'] . " " . $lang['from'] . " (" . $player->coplevel . ") " . $lang['to'] . " (" . $coplevel . ")", 2);
                        if ($mediclevel != $player->mediclevel) logAction($_SESSION['user_name'], $lang['edited'] . " " . nameID($player->playerid, $db_link) . "(" . $player->playerid . ") " . $lang['medic'] . " " . $lang['level'] . " " . $lang['from'] . " (" . $player->mediclevel . ") " . $lang['to'] . " (" . $mediclevel . ")", 2);
                        if ($donorlevel != $player->$settings['donorFormat']) logAction($_SESSION['user_name'], $lang['edited'] . " " . nameID($player->playerid, $db_link) . "(" . $player->playerid . ") " . $lang['donator'] . " " . $lang['level'] . " " . $lang['from'] . " (" .$player->$settings['donorFormat'] . ") " . $lang['to'] . " (" . $donorlevel . ")", 2);
                        if ($cash != $player->cash) logAction($_SESSION['user_name'], $lang['edited'] . " " . nameID($player->playerid, $db_link) . "(" . $player->playerid . ") " . $lang['cash'] . " " . $lang['from'] . " (" . $player->cash . ") " . $lang['to'] . " (" . $cash . ")", 2);
                        if ($bankacc != $player->bankacc) logAction($_SESSION['user_name'], $lang['edited'] . " " . nameID($player->playerid, $db_link) . "(" . $player->playerid . ") " . $lang['bank'] . " " . $lang['from'] . " (" . $player->bankacc . ") " . $lang['to'] . " (" . $bankacc . ")", 2);

                        $update = "UPDATE `players` SET coplevel = '" . $coplevel . "', mediclevel = '" . $mediclevel . "', ".$settings['donorFormat']."= '" . $donorlevel . "', cash = '" . $cash . "', bankacc = '" . $bankacc . "' WHERE `uid` = '" . $uID . "' ";
                        $result_of_query = $db_link->query($update);
                        logAction($_SESSION['user_name'], $lang['edited'] . ' ' . nameID($player->playerid, $db_link) . '(' . $player->playerid . ') ' . $lang['levels'], 2);
                        message($lang['edited'] . ' ' . nameID($player->playerid, $db_link));
                    } else {
                        message("ERROR");
                    }
                } elseif ($_SESSION['user_level'] >= 2) {
                    $coplevel = intval($_POST["player_coplvl"]);
                    $mediclevel = intval($_POST["player_medlvl"]);
                    if ($coplevel != $player->coplevel) logAction($_SESSION['user_name'], $lang['edited'] . " " . nameID($player->playerid, $db_link) . "(" . $player->playerid . ") " . $lang['cop'] . " " . $lang['level'] . " " . $lang['from'] . " (" . $player->coplevel . ") " . $lang['to'] . " (" . $coplevel . ")", 2);
                    if ($mediclevel != $player->mediclevel) logAction($_SESSION['user_name'], $lang['edited'] . " " . nameID($player->playerid, $db_link) . "(" . $player->playerid . ") " . $lang['medic'] . " " . $lang['level'] . " " . $lang['from'] . " (" . $player->mediclevel . ") " . $lang['to'] . " (" . $mediclevel . ")", 2);

                    $update = "UPDATE `players` SET coplevel = '" . $coplevel . "', mediclevel = '" . $mediclevel . "' WHERE `uid` = '" . $uID . "' ";
                    $result_of_query = $db_link->query($update);
                    logAction($_SESSION['user_name'], $lang['edited'] . ' ' . nameID($player->playerid, $db_link) . '(' . $player->playerid . ') ' . $lang['levels'], 2);
                    message($lang['edited'] . ' ' . nameID($player->playerid, $db_link));
                }
                break;
            case "add_note":
                $note_text = $_POST["note_text"];
                $update = "INSERT INTO `notes` (`uid`, `staff_name`, `note_text`, `note_updated`) VALUES ('" . $uID . "', '" . $_SESSION['user_name'] . "', '" . $note_text . "', CURRENT_TIMESTAMP); ";
                $result_of_query = $db_link->query($update);
                logAction($_SESSION['user_name'], $lang['edited'] . ' ' . nameID($player->playerid, $db_link) . '(' . $player->playerid . ') ' . $lang['notes'], 1);
                message($lang['edited'] . ' ' . $lang['notes']);
                break;
        }
    } else {
        message($lang['expired']);
    }
    }

$sql = "SELECT * FROM `players` WHERE `uid` = '" . $uID . "'";
$result = $db_link->query($sql);
if ($result->num_rows > 0) {
    $player = $result->fetch_object();

    $temp = "";
    $pGID = $player->playerid;
    for ($i = 0; $i < 8; $i++) {
        $temp .= chr($pGID & 0xFF);
        $pGID >>= 8;
    }
    $pGID = md5('BE' . $temp);
?>
<div class="col-md-3" style="float:left;  padding-top:20px;">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="panel-title"><i class="fa fa-child fa-fw"></i><?php echo $player->name; ?></h2>
        </div>

        <div class="panel-body">
            <?php
                $alias = str_replace('"[`', "", $player->aliases);
                $alias = str_replace('`]"', "", $alias);

                echo '<center><img alt="' . $alias . '" src="' . $settings['url'] . 'assets/img/uniform/' . getPlayerSkin($player->civ_gear, $playerSkins) . '.jpg">';
                 echo "<h5 style='word-wrap: break-word; '> <a href='http://playerindex.de/check.aspx?id=" . $pGID . "' class='btn btn-xs btn-warning' target='_blank' role='button'>Check Playerindex Ban </a></h5>";
                if ($_SESSION['permissions']['view']['steam'] && $settings['vacTest']) {
                    echo '<div id="vacBan"></div>';
                }
                if ($_SESSION['permissions']['view']['steam'] && $settings['communityBansTest']) {
                    echo '<div id="communityBanned">';
                    if (communityBanned($pGID)) {
                        echo '<h4><span class="label label-danger" style="margin-left:3px; line-height:2;">Community Banned</span></h4>';
                    }
                    echo '</div>';
                }
                echo "<h4>" . $lang['aliases'] . ": " . $alias . "</h4>";
                echo "<h4>" . $lang['uid'] . ": " . $player->uid . "</h4>";
                echo "<h4>" . $lang['playerID'] . ": " . $player->playerid . "</h4>";
                echo "<h4 style='word-wrap: break-word;'>" . $lang['GUID'] . ": " . $pGID . "</h4>";
            ?>
            <i class="fa fa-2x fa-money"></i>
            <h4><?php echo $lang['cash'] . ": " . $player->cash; ?> </h4>
            <i class="fa fa-2x fa-bank"></i>
            <h4> <?php echo $lang['bank'] . ": " . $player->bankacc; ?> </h4>
            <?php
                if ($player->arrested == 0) {
                    echo "<h4><button type='button' id='arrested' class='arrest btn btn-xs btn-success'>" . $lang["not"] . " " . $lang["arrested"] . "</button></h4>";
                } else {
                    echo "<h4><button type='button' id='arrested' class='arrest btn btn-xs btn-theme01'>" . $lang["arrested"] . "</button></h4>";
                }

                if ($player->blacklist == 0) {
                    echo "<h4><button type='button' id='blacklist' class='arrest btn btn-xs btn-success'>" . $lang["not"] . " " . $lang["blacklisted"] . "</button></h4>";
                } else {
                    echo "<h4><button type='button' id='blacklist' class='arrest btn btn-xs btn-theme01'>" . $lang["blacklisted"] . "</button></h4>";
                }

                if ($settings['wanted'] && ($_SESSION['permissions']['view']['wanted'] || $player->playerid == $_SESSION['playerid'])) {
                    $sql = "SELECT `active` FROM `wanted` WHERE `wantedID` = '" . $player->playerid . "'";
                    $result_of_query = $db_link->query($sql);
                    if ($result_of_query->num_rows > 0) {
                        while ($row = mysqli_fetch_assoc($result_of_query)) {
                            if ($row["active"] == 1) {
                                echo "<h4><a href='" . $settings['url'] . "editwanted/" . $player->playerid . "' class='label label-danger'>" . $lang["wanted"] . "</span></h4>";
                            } else {
                                echo "<h4><span class='label label-success'>" . $lang["not"] . " " . $lang["wanted"] . "</span></h4>";
                            }
                        }
                    } else {
                        echo "<h4><span class='label label-success'>" . $lang["not"] . " " . $lang["wanted"] . "</span></h4>";
                    }
                }

                if ($_SESSION['permissions']['edit']['player']) {
                    echo '<a data-toggle="modal" href="#edit_player" class="btn btn-primary btn-xs" style="float: right;">';
                    echo '<i class="fa fa-pencil"></i>';
                    echo '</a>';
                }
                echo "</center>";
            ?>
        </div>
    </div>
</div>

<!-- Right Container -->
<div class="col-md-9" style="float:right; padding-top:20px;">
    <div class="row mtbox">
        <div class="col-md-2 col-sm-2 col-md-offset-1 box0">
            <div class="box1">
                <span class="fa fa-3x fa-taxi"></span>
                <h3> <?php echo $lang['police'] . ": " . $player->coplevel; ?> </h3>
            </div>
        </div>
        <div class="col-md-2 col-sm-2 box0">
            <div class="box1">
                <span class="fa fa-3x fa-ambulance"></span>
                <h3> <?php echo $lang['medic'] . ": " . $player->mediclevel; ?> </h3>
            </div>
        </div>
        <div class="col-md-2 col-sm-2 box0">
            <div class="box1">
                <span class="fa fa-3x fa-usd"></span>
                <h3> <?php echo $lang['donator'] . ": " . $player->$settings['donorFormat']; ?> </h3>
            </div>
        </div>
        <div class="col-md-2 col-sm-2 box0">
            <div class="box1">
                <span class="fa fa-3x fa-group"></span>
                <h3> <?php echo $lang['admin'] . ": " . $player->adminlevel; ?> </h3>
            </div>
        </div>
        <?php
        if ($_SESSION['permissions']['view']['steam'] || $player->playerid == $_SESSION['playerid']) {
            echo '<div class="col-md-2 col-sm-2 box0">';
            echo '<a href="http://steamcommunity.com/profiles/' . $player->playerid . '"';
            echo 'target="_blank">';
            echo '<div class="box1">';
            echo '<span class="fa fa-3x fa-steam"></span>';
            echo '<h3>Steam</h3>';
            echo '</div>';
            echo '</div></a>';
        } ?>
    </div>

    <div class="panel panel-default" style="float:left; width:100%; margin:0 auto;">
        <ul id="myTab" class="nav nav-tabs">
            <li class="dropdown active">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $lang['licenses']; ?> <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="#civ_lic" data-toggle="tab"><?php echo $lang['civil']; ?></a></li>
                    <li><a href="#medic_lic" data-toggle="tab"><?php echo $lang['medic']; ?></a></li>
                    <li><a href="#police_lic" data-toggle="tab"><?php echo $lang['police']; ?></a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $lang['inventory']; ?> <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="#civ_inv" data-toggle="tab"><?php echo $lang['civil']; ?></a></li>
                    <li><a href="#medic_inv" data-toggle="tab"><?php echo $lang['medic']; ?></a></li>
                    <li><a href="#police_inv" data-toggle="tab"><?php echo $lang['police']; ?></a></li>
                </ul>
            </li>
            <?php
            if ($_SESSION['permissions']['edit']['houses']) {
                echo '<li><a href="#house" data-toggle="tab">' . $lang['houses'] . '</a></li>';
            }
            if ($_SESSION['permissions']['edit']['vehicles']) {
                echo '<li><a href="#veh" data-toggle="tab">' . $lang['vehicles'] . '</a></li>';
            }
            if ($_SESSION['permissions']['edit']['notes']) {
                echo '<li><a href="#notes" data-toggle="tab"> Notes</a></li>';
            }
            if ($_SESSION['permissions']['view']['wanted'] && $settings['wanted']) {
                echo '<li><a href="#wanted" data-toggle="tab">' . $lang['wanted'] . '</a></li>';
            }
            ?>
        </ul>
        <div class="panel-body">
            <div id="myTabContent" class="tab-content">
                <?php if ($_SESSION['permissions']['view']['licences'] || $player->playerid == $_SESSION['playerid']) { ?>
                    <div class="tab-pane fade in active well" id="civ_lic">
                        <?php
                            if ($player->civ_licenses !== '"[]"' && $player->civ_licenses !== '') {
                                echo '<h4 style="centred">' . $lang['civil'] . ' ' . $lang['licenses'] . '</h4>';
                                $return = stripArray($player->civ_licenses, 0);
                                foreach ($return as $value) {
                                    if (strpos($value, "1") == TRUE) {
                                        $name = before(',', $value);
                                        echo "<button type='button' id=" . $name . " class='license btn btn-xs btn-success' style='margin-bottom: 3px;'>" . licName($name, $license) . "</button> ";
                                    } else {
                                        $name = before(',', $value);
                                        echo "<button type='button' id=" . $name . " class='license btn btn-xs btn-theme01' style='margin-bottom: 3px;'>" . licName($name, $license) . "</button> ";
                                    }
                                }
                            } else {
                                echo '<h4>' . errorMessage(371, $lang) . '</h4>';
                            }?>
                    </div>
                    <div class="tab-pane well fade" id="medic_lic">
                        <?php
                            if ($player->med_licenses !== '"[]"' && $player->med_licenses !== '') {
                                echo '<h4 style="centred">' . $lang['medic'] . ' ' . $lang['licenses'] . '</h4>';
                                $return = stripArray($player->med_licenses, 0);
                                foreach ($return as $value) {
                                    if (strpos($value, "1") == TRUE) {
                                        $name = before(',', $value);
                                        echo "<button type='button' id=" . $name . " class='license btn btn-xs btn-success' style='margin-bottom: 3px;'>" . licName($name, $license) . "</button> ";
                                    } else {
                                        $name = before(',', $value);
                                        echo "<button type='button' id=" . $name . " class='license btn btn-xs btn-theme01' style='margin-bottom: 3px;'>" . licName($name, $license) . "</button> ";
                                    }
                                }
                            } else {
                                echo '<h4>' . errorMessage(372, $lang) . '</h4>';
                            } ?>
                    </div>
                    <div class="tab-pane well fade" id="police_lic">
                        <?php
                            if ($player->cop_licenses !== '"[]"' && $player->cop_licenses !== '') {
                                $return = stripArray($player->cop_licenses, 0);
                                echo '<h4 style="centred">' . $lang['cop'] . ' ' . $lang['licenses'] . '</h4>';
                                foreach ($return as $value) {
                                    if (strpos($value, "1") == TRUE) {
                                        $name = before(',', $value);
                                        echo "<button type='button' id=" . $name . " class='license btn btn-xs btn-success' style='margin-bottom: 3px;'>" . licName($name, $license) . "</button> ";
                                    } else {
                                        $name = before(',', $value);
                                        echo "<button type='button' id=" . $name . " class='license btn btn-xs btn-theme01' style='margin-bottom: 3px;'>" . licName($name, $license) . "</button> ";
                                    }
                                }
                            } else {
                                echo '<h4>' . errorMessage(373, $lang) . '</h4>';
                            }
                        ?>
                    </div>
                <?php } if ($_SESSION['permissions']['edit']['inventory']) { ?>
                    <div class="tab-pane fade well" id="civ_inv">
                        <?php
                        if ($player->civ_gear !== '"[]"' && $player->civ_gear !== '') {
                            echo '<h4 style="centred">' . $lang['civil'] . ' ' . $lang['gear'] . '</h4>';
                            echo "<textarea class='form-control' readonly rows='5' style='width: 100%' id='civ_gear' name='civ_gear'>" . $player->civ_gear . "</textarea><br>";

                            if ($_SESSION['permissions']['edit']['inventory']) {
                                echo '<a data-toggle="modal" href="#edit_civ_inv" class="btn btn-primary btn-xs" style="float: right;">';
                                echo '<i class="fa fa-pencil"></i></a>';
                            }
                        } else {
                            echo '<h4>' . errorMessage(381, $lang) . '</h4>';
                        } ?>
                    </div>
                    <div class="tab-pane fade well" id="police_inv">
                        <?php
                        if ($player->cop_gear !== '"[]"' && $player->cop_gear !== '') {
                            echo '<h4 style="centred">' . $lang['cop'] . ' ' . $lang['gear'] . '</h4>';
                            echo "<textarea class='form-control' readonly rows='5' style='width: 100%' id='cop_gear' name='cop_gear'>" . $player->cop_gear . "</textarea><br>";
                            if ($_SESSION['permissions']['edit']['inventory']) {
                                echo '<a data-toggle="modal" href="#edit_cop_inv" class="btn btn-primary btn-xs" style="float: right;">';
                                echo '<i class="fa fa-pencil"></i></a>';
                            }
                        } else {
                            echo '<h4>' . errorMessage(383, $lang) . '</h4>';
                        } ?>
                    </div>
                    <div class="tab-pane fade well" id="medic_inv">
                        <?php
                        if ($player->med_gear !== '"[]"' && $player->med_gear !== '') {
                            echo '<h4 style="centred">' . $lang['medic'] . ' ' . $lang['gear'] . '</h4>';
                            echo "<textarea class='form-control' readonly rows='5' style='width: 100%' id='med_gear' name='med_gear'>" . $player->med_gear . "</textarea><br>";
                            if ($_SESSION['permissions']['edit']['inventory']) {
                                echo '<a data-toggle="modal" href="#edit_med_inv" class="btn btn-primary btn-xs" style="float: right;">';
                                echo '<i class="fa fa-pencil"></i></a>';
                            }
                        } else {
                            echo '<h4>' . errorMessage(382, $lang) . '</h4>';
                        } ?>
                    </div>
                <?php }
                if ($_SESSION['permissions']['view']['houses'] || $player->playerid == $_SESSION['playerid']) { ?>
                    <div class="tab-pane fade" id="house">
                        <div class="table-responsive">
                            <?php
                            $sql = "SELECT `pos`,`id` FROM `houses` WHERE `pid` = '" . $player->playerid . "' ORDER BY `id` DESC LIMIT 8";
                            $result_of_query = $db_link->query($sql);
                            if ($result_of_query->num_rows > 0) {
                                ?>
                                <table class="table table-bordered table-hover table-striped" style="margin-bottom: 0px;">
                                    <thead>
                                    <tr>
                                        <th><?php echo $lang['position']; ?></th>
                                        <th><?php echo $lang['edit']; ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    while ($row = mysqli_fetch_assoc($result_of_query)) {
                                        echo "<tr>";
                                        echo "<td>" . substr($row["pos"], 1, -1) . "</td>";
                                        echo "<td><a class='btn btn-primary btn-xs' href='" . $settings['url'] . "editHouse/" . $row["id"] . "'>";
                                        echo "<i class='fa fa-pencil'></i></a></td>";
                                        echo "</tr>";
                                    } ?>
                                    </tbody>
                                </table>
                                <?php echo '<a style="float: right;" href="' . $settings['url'] . 'houses/' . $player->playerid . '"><h4>' . $lang['more'] . ' <i class="fa fa-arrow-circle-right"></i></h4></a>';
                            } else echo '<h4>' . errorMessage(31, $lang) . '</h4>'; ?>
                        </div>
                    </div>
                <?php } if ($_SESSION['permissions']['view']['vehicles'] || $player->playerid == $_SESSION['playerid']) { ?>
                    <div class="tab-pane fade" id="veh">
                        <div class="table-responsive">
                        <?php
                            $sql = "SELECT `classname`,`type`,`id`,`plate` FROM `vehicles` WHERE `pid` = '" . $player->playerid . "' ORDER BY `id` DESC LIMIT 8";
                            $result_of_query = $db_link->query($sql);
                            if ($result_of_query->num_rows > 0) {
                                $veh = $result_of_query->fetch_object();
                                echo '<table class="table table-bordered table-hover table-striped" style="margin-bottom: 0px;">';
                                echo '<thead><tr>';
                                echo '<th>' . $lang['class'] . '</th>';
                                echo '<th class="hidden-xs">' . $lang['type'] . '</th>';
                                echo '<th class="hidden-xs">' . $lang['plate'] . '</th>';
                                if ($_SESSION['permissions']['edit']['vehicles']) {
                                    echo "<th>" . $lang['edit'] . "</th>";
                                }
                                echo '</tr></thead><tbody';
                                echo '<tr>';
                                echo '<td>' . carName($veh->classname) . '</td>';
                                echo '<td class="hidden-xs">' . carType($veh->type, $lang) . '</td>';
                                echo '<td class="hidden-xs">' . $veh->plate . '</td>';

                                if ($_SESSION['permissions']['edit']['vehicles']) {
                                    echo "<td><a class='btn btn-primary btn-xs' href='" . $settings['url'] . "editVeh/" . $veh->id . "'>";
                                    echo "<i class='fa fa-pencil'></i></a></td>";
                                }

                                while ($row = mysqli_fetch_assoc($result_of_query)) {
					echo "<tr>";
					echo "<td>" . carName($row["classname"]) . "</td>";
					echo "<td class='hidden-xs'> " . carType($row["type"], $lang) . "</td>";
					echo "<td class='hidden-xs'> " . $row["plate"] . "</td>";
					if ($_SESSION['permissions']['edit']['vehicles']) {
						echo "<td><a class='btn btn-primary btn-xs' href='" . $settings['url'] . "editVeh/" . $row["id"] . "'>";
						echo "<i class='fa fa-pencil'></i></a></td>";
					}
					echo "</tr>";
				}

                                echo '</tr></tbody></table>';
                                echo '<a style="float: right; padding-right:15px;" href="' . $settings['url'] . 'vehicles/' . $player->playerid . '"><h4>' . $lang['more'] . ' <i class="fa fa-arrow-circle-right"></i></h4></a>';

                            } else echo '<h4>' . errorMessage(32, $lang) . '</h4>';
                        ?>
                        </div>
                    </div>
                <?php }
                if ($_SESSION['permissions']['view']['notes']) { ?>
                    <div class="tab-pane fade" id="notes">
                        <div class="table-responsive">
                            <?php
                                $sql = 'SELECT * FROM `notes` WHERE `uid` = "' . $uID . '" ORDER BY `note_updated` DESC LIMIT 10';
                                $result_of_query = $db_link->query($sql);
                                if ($result_of_query->num_rows > 0) {
                                    ?>
                                    <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                    <tr>
                                        <th><?php echo $lang['owner']; ?></th>
                                        <th><?php echo $lang['note']; ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    while ($row = mysqli_fetch_assoc($result_of_query)) {
                                        echo "<tr>";
                                        echo "<td>" . $row["staff_name"] . "</td>";
                                        echo "<td>" . $row["note_text"] . "</td>";
                                        echo "</tr>";
                                    };
                                    ?>
                                    </tbody>
                                    </table>
                                <?php
                                    if ($_SESSION['permissions']['edit']['notes']) {
                                                                                echo '<a data-toggle="modal" href="#add_note" class="btn btn-primary btn-xs" style="float: right; margin-right:5px; margin-bottom:5px;">
                                                    <i class="fa fa-file-o"></i></a>';
                                    }
                                    } else {
                                        echo '<h1>' . $lang['noNotes'] . '</h1>';
                                        if ($_SESSION['permissions']['edit']['notes']) {
                                                                                    echo '<a data-toggle="modal" href="#add_note" class="btn btn-primary btn-xs" style="float: right; margin-right:5px; margin-bottom:5px;">
                                                    <i class="fa fa-file-o"></i></a>';
                                        }
                                    };
                                ?>
                        </div>
                    </div>
                <?php } if ($_SESSION['permissions']['view']['wanted'] && $settings['wanted']) { ?>
                    <div class="tab-pane fade well" id="wanted">
                        <div class="table-responsive">
                        <?php
                            $sql = "SELECT `wantedCrimes` FROM `wanted` WHERE `wantedID`='" . $player->playerid . "'";
                            $result_of_query = $db_link->query($sql);
                            if ($result_of_query->num_rows > 0) {
                                echo "<h3>" . $lang['crimes'] . "</h3>";
                                while ($row = mysqli_fetch_assoc($result_of_query)) {
                                    if ($row['wantedCrimes'] !== "[]") {
                                        $return = stripArray($row['wantedCrimes'], 3);
                                        foreach ($return as $value) {
                                            echo "<button type='button' id=" . $value . " class='wanted btn btn-xs btn-theme01' style='margin-bottom: 3px;'>" . crimeName($value) . "</button> ";
                                        }
                                    } else {
                                        echo "<h3>" . errorMessage(34, $lang) . "</h3>";

                                    }
                                }
                            } else echo "<h3>" . errorMessage(34, $lang) . "</h3>";
                        ?>

                        </div>
                    </div>
                <?php } ?>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="edit_civ_inv" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><i class="fa fa-pencil"></i>
                    <?php echo $lang['edit'] . " " . $lang['civ'] . " " . $lang['inventory']; ?>
                </h4>
            </div>
            <?php if ($_SESSION['permissions']['edit']['inventory']) { ?>
                <form method="post" action="<?php echo $settings['url'] . 'editPlayer/' . $uID; ?>"
                <?php echo formtoken::getField() ?>
                      role="form">
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="hidden" name="editType" value="civ_inv"/>

                            <div class="row">
                                <textarea class="form-control" rows="10"
                                          name="civ_inv_value"><?php echo $player->civ_gear; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default" data-dismiss="modal" type="reset">Close</button>
                        <button class="btn btn-primary" type="submit"><?php echo $lang['subChange']; ?></button>
                    </div>
                </form>
            <?php } else errorMessage(5, $lang); ?>
        </div>
    </div>
</div>

<div class="modal fade" id="edit_med_inv" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><i class="fa fa-pencil"></i>
                    <?php echo $lang['edit'] . " " . $lang['medic'] . " " . $lang['inventory']; ?>
                </h4>
            </div>
            <?php if ($_SESSION['permissions']['edit']['inventory']) { ?>
                <form method="post" action="<?php echo $settings['url'] . 'editPlayer/' . $uID; ?>"
                <?php echo formtoken::getField() ?>
                      role="form">
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="hidden" name="editType" value="med_inv"/>

                            <div class="row">
                                <textarea class="form-control" rows="10"
                                          name="med_inv_value"><?php echo $player->med_gear; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default" data-dismiss="modal" type="reset">Close</button>
                        <button class="btn btn-primary" type="submit"><?php echo $lang['subChange']; ?></button>
                    </div>
                </form>
            <?php } else errorMessage(5, $lang); ?>
        </div>
    </div>
</div>

<div class="modal fade" id="edit_cop_inv" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><i class="fa fa-pencil"></i>
                    <?php echo $lang['edit'] . " " . $lang['police'] . " " . $lang['inventory']; ?>
                </h4>
            </div>
            <?php if ($_SESSION['permissions']['edit']['inventory']) { ?>
                <form method="post" action="<?php echo $settings['url'] . 'editPlayer/' . $uID; ?>"
                      role="form">
                    <?php echo formtoken::getField() ?>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="hidden" name="editType" value="cop_inv"/>

                            <div class="row">
                                <textarea class="form-control" rows="10"
                                          name="cop_inv_value"><?php echo $player->cop_gear; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default" data-dismiss="modal" type="reset">Close</button>
                        <button class="btn btn-primary" type="submit"><?php echo $lang['subChange']; ?></button>
                    </div>
                </form>
            <?php } else errorMessage(5, $lang); ?>
        </div>
    </div>
</div>

<div class="modal fade" id="add_note" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><i class="fa fa-pencil"></i>
                    <?php echo $lang['new'] . " " . $lang['note']; ?>
                </h4>
            </div>
            <?php if ($_SESSION['permissions']['edit']['notes']) { ?>
                <form method="post" action="<?php echo $settings['url'] . 'editPlayer/' . $uID; ?>" role="form">
                    <div class="modal-body">
                        <?php echo formtoken::getField() ?>
                        <div class="form-group">
                            <input type="hidden" name="editType" value="add_note"/>

                            <div class="row">
                                <div class="form-group">
                                    <textarea class="form-control" rows="8" name="note_text"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default" data-dismiss="modal" type="reset">Close</button>
                        <button class="btn btn-primary" type="submit"><?php echo $lang['subChange']; ?></button>
                    </div>
                </form>
            <?php } else errorMessage(5, $lang); ?>
        </div>
    </div>
</div>

<div class="modal fade" id="edit_player" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><i class="fa fa-pencil"></i>
                    <?php echo $lang['edit'] . " " . $lang['player']; ?>
                </h4>
            </div>
            <?php if ($_SESSION['permissions']['edit']['player']) { ?>
                <form method="post" action="<?php echo $settings['url'] . 'editPlayer/' . $uID; ?>" role="form">
                    <div class="modal-body">
                        <?php echo formtoken::getField() ?>
                        <div class="form-group">
                            <input type="hidden" name="editType" value="player_edit"/>

                            <div class="row">
                                <center>
                                    <?php if ($_SESSION['permissions']['edit']['bank']) {
                                        echo "<h4>" . $lang['cash'] . ":    <input id='player_cash' name='player_cash' type='number' value='" . $player->cash . "'>";
                                        echo "<h4>" . $lang['bank'] . ":    <input id='player_bank' name='player_bank' type='number' value='" . $player->bankacc . "'>";
                                    }?>
                                    <?php if ($_SESSION['permissions']['edit']['ranks']) {
                                        echo "<h4>" . $lang['cop'] . ": ";
                                        echo "<select id='player_coplvl' name='player_coplvl'>";
                                        for ($lvl = 0;
                                                $lvl <= $settings['maxLevels']['cop'];
                                                $lvl++) {
                                            echo '<option value="' . $lvl . '"' . select($lvl, $player->coplevel) . '>' . $lvl . '</option>';
                                        }
                                        echo "</select>";
                                        echo "<h4>" . $lang['medic'] . ": ";
                                        echo "<select id='player_medlvl' name='player_medlvl'>";
                                        for ($lvl = 0;
                                                $lvl <= $settings['maxLevels']['medic'];
                                                $lvl++) {
                                            echo '<option value="' . $lvl . '"' . select($lvl, $player->mediclevel) . '>' . $lvl . '</option>';
                                        }
                                        echo "</select>";

                                        if ($_SESSION['permissions']['edit']['ignLVL']) {
                                            echo "<h4>" . $lang['admin'] . ": ";
                                            echo "<select id='player_adminlvl' name='player_adminlvl'>";
                                            for ($lvl = 0;
                                                    $lvl <= $settings['maxLevels']['admin'];
                                                    $lvl++) {
                                                echo '<option value="' . $lvl . '"' . select($lvl, $player->adminlevel) . '>' . $lvl . '</option>';
                                            }
                                            echo "</select>";
                                            echo "<h4>" . $lang['donator'] . ": ";
                                            echo "<select id='player_donlvl' name='player_donlvl'>";
                                            for ($lvl = 0;
                                                    $lvl <= $settings['maxLevels']['donator'];
                                                    $lvl++) {
                                                echo '<option value="' . $lvl . '"' . select($lvl, $player->$settings['donorFormat']) . '>' . $lvl . '</option>';
                                            }
                                            echo "</select>";
                                        }
                                        }?>
                                </center>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default" data-dismiss="modal" type="reset">Close</button>
                        <button class="btn btn-primary" type="submit"><?php echo $lang['subChange']; ?></button>
                    </div>
                </form>
            <?php } else "<h1>" . errorMessage(5, $lang) . "/<h1>"; ?>
        </div>
    </div>
</div>

<script>
$( document ).ready(function() {
    <?php if ($_SESSION['permissions']['edit']['licences']) { ?>
    $(".license").click(function () {
        $(this).toggleClass('btn-success btn-theme01');
        $.post( "<?php echo $settings['url'] ?>hooks/license.php", { id: this.id, player: "<?php echo $uID ?>"} );
    });
    <?php } if ($_SESSION['permissions']['edit']['player']) { ?>
    $(".arrest").click(function () {
        $(this).toggleClass('btn-success btn-theme01');
        $.post( "<?php echo $settings['url'] ?>hooks/arrest.php", { id: this.id, player: "<?php echo $uID ?>"} );
    });
    <?php } if ($_SESSION['permissions']['edit']['wanted']) { ?>
    $(".wanted").click(function () {
        $(this).toggleClass('btn-success btn-theme01');
        $.post( "<?php echo $settings['url'] ?>hooks/wanted.php", { id: this.id, player: "<?php echo $uID ?>"} );
    });
    <?php } if (($_SESSION['permissions']['view']['steam'] || $player->playerid == $_SESSION['playerid']) && $settings['vacTest']) { ?>
    $.ajax({
        url: "https://steamrep.com/api/beta3/reputation/<?php echo  $player->playerid ?>?json=1&extended=1",
        dataType: 'json',
        success: function(data) {
            if(data['steamrep']['vacban'] == "1") {
                $('#vacBan').html('<h4><span class="label label-danger" style="margin-left:3px; line-height:2;">VAC BANNED</span></h4>');
            }
        }
    });
    <?php } if ($_SESSION['permissions']['view']['steam'] && $settings['vacTest']) { ?>
    $.ajax({
        url: "http://bans.itsyuka.tk/api/bans/player/id/6e96f18ddaaa2dadcc32482b2d6a0593/format/json/key/<?php echo $settings['communityBansAPI'] ?>",
        dataType: 'json',
        success: function(data) {
            if(data['level'] == '2') {
                $('#communityBanned').html('<h4><span class="label label-danger" style="margin-left:3px; line-height:2;">Community Banned</span></h4>');
            }
        }
    });
    <?php }?>
});
</script>

<?php } else echo "<h1>" . errorMessage(36, $lang) . "</h1>";