<?php

if ($settings['url'] == "/") {
    require_once("config/carNames.php");
    require_once("config/images.php");
    require_once("config/license.php");
} else {
    require_once(realpath($settings['url']) . "config/carNames.php");
    require_once(realpath($settings['url']) . "config/images.php");
    require_once(realpath($settings['url']) . "config/license.php");
}

$db_link = serverConnect();

function getPlayerSkin($input, $list)
{
    if ($input !== '"[]"') {
        $name = after('"[`', $input);
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

$sql = "SELECT * FROM `players` WHERE `playerid` = '" . $_SESSION['playerid'] . "'";
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
                echo "<h4>" . $lang['aliases'] . ": " . $alias . "</h4>";
                echo "<h4>" . $lang['uid'] . ": " . $player->uid . "</h4>";
                echo "<h4>" . $lang['playerID'] . ": " . $player->playerid . "</h4>";
                echo "<h4 style='word-wrap: break-word;'>" . $lang['GUID'] . ": " . $pGID . "</h4>";
            ?>
            <i class="fa fa-2x fa-money"></i>
            <h4> <?php echo $lang['cash'] . ": " . $player->cash; ?> </h4>
            <i style="padding-left:15px;" class="fa fa-2x fa-bank"></i>
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

                <h3> <?php echo $lang['donator'] . ": " . $player->donorlevel; ?> </h3>
            </div>
        </div>
        <div class="col-md-2 col-sm-2 box0">
            <div class="box1">
                <span class="fa fa-3x fa-group"></span>

                <h3> <?php echo $lang['admin'] . ": " . $player->adminlevel; ?> </h3>
            </div>
        </div>
        <?php
        if ($_SESSION['permissions']['view']['steam'] || $uID == $_SESSION['playerid']) {
            echo '<div class="col-md-2 col-sm-2 box0">';
            echo '<a href="http://steamcommunity.com/profiles/' . $row["playerid"] . '"';
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
                    <li><a href="#civ_lic" data-toggle="tab"><?php echo $lang['civ']; ?></a></li>
                    <li><a href="#medic_lic" data-toggle="tab"><?php echo $lang['medic']; ?></a></li>
                    <li><a href="#police_lic" data-toggle="tab"><?php echo $lang['police']; ?></a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $lang['inventory']; ?> <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="#civ_inv" data-toggle="tab"><?php echo $lang['civ']; ?></a></li>
                    <li><a href="#medic_inv" data-toggle="tab"><?php echo $lang['medic']; ?></a></li>
                    <li><a href="#police_inv" data-toggle="tab"><?php echo $lang['police']; ?></a></li>
                </ul>
            </li>
            <li><a href="#house" data-toggle="tab"><?php echo $lang['houses']; ?></a></li>
            <li><a href="#veh" data-toggle="tab"><?php echo $lang['vehicles']; ?></a></li>
        </ul>
        <div class="panel-body">
            <div id="myTabContent" class="tab-content">
                <?php if ($player->playerid == $_SESSION['playerid']) { ?>
                    <div class="tab-pane fade in active well" id="civ_lic">
                        <h4 style="centred"><?php echo $lang['civ'] . " " . $lang['licenses']; ?> </h4>
                        <?php
                            if ($player->civ_licenses !== '"[]"') {
                                $return = stripArray($player->civ_licenses, 0);
                                foreach ($return as $value) {
                                    if (strpos($value, "1") == TRUE) {
                                        $name = before(',', $value);
                                        echo "<button class='license btn btn-xs btn-success' style='margin-bottom: 3px;'>" . licName($name, $license) . "</button> ";
                                    } else {
                                        $name = before(',', $value);
                                        echo "<button class='license btn btn-xs btn-theme01' style='margin-bottom: 3px;'>" . licName($name, $license) . "</button> ";
                                    }
                                }
                            } else {
                                    echo errorMessage(37,$lang);
                            }?>
                    </div>
                    <div class="tab-pane well fade" id="medic_lic">
                        <h4 style="centred"><?php echo $lang['medic'] . " " . $lang['licenses']; ?> </h4>
                        <?php
                            if ($player->med_licenses !== '"[]"') {
                                $return = stripArray($player->med_licenses,0);

                                foreach ($return as $value) {
                                    if (strpos($value, "1") == TRUE) {
                                        $name = before(',', $value);
                                        echo "<button class='license btn btn-xs btn-success' style='margin-bottom: 3px;'>" . licName($name, $license) . "</button> ";
                                    } else {
                                        $name = before(',', $value);
                                        echo "<button class='license btn btn-xs btn-theme01' style='margin-bottom: 3px;'>" . licName($name, $license) . "</button> ";
                                    }
                                }
                            } else {
                                    echo errorMessage(37,$lang);
                            } ?>
                    </div>
                    <div class="tab-pane well fade" id="police_lic">
                        <h4 style="centred"><?php echo $lang['cop'] . " " . $lang['licenses']; ?> </h4>
                        <?php
                            if ($player->cop_licenses !== '"[]"') {
                                $return = stripArray($player->cop_licenses,0);

                                foreach ($return as $value) {
                                    if (strpos($value, "1") == TRUE) {
                                        $name = before(',', $value);
                                        echo "<button class='license btn btn-xs btn-success' style='margin-bottom: 3px;'>" . licName($name, $license) . "</button> ";
                                    } else {
                                        $name = before(',', $value);
                                        echo "<button class='license btn btn-xs btn-theme01' style='margin-bottom: 3px;'>" . licName($name, $license) . "</button> ";
                                    }
                                }
                            } else {
                                    echo errorMessage(37,$lang);
                            }
                        ?>
                    </div>
                <?php } ?>

                <?php if ($player->playerid == $_SESSION['playerid']) { ?>
                    <div class="tab-pane fade well" id="civ_inv">
                        <h4 style="centred"><?php echo $lang['civ'] . " " . $lang['gear']; ?> </h4>
                        <?php
                        echo "<textarea class='form-control' readonly rows='5' style='width: 100%' id='civ_gear' name='civ_gear'>" . $player->civ_gear . "</textarea>";
                        echo '<br>';

                        if ($_SESSION['permissions']['edit']['inventory']) {
                            echo '<a data-toggle="modal" href="#edit_civ_inv" class="btn btn-primary btn-xs" style="float: right;">';
                            echo '<i class="fa fa-pencil"></i></a>';
                        } ?>

                        <br>
                    </div>
                    <div class="tab-pane fade well" id="police_inv">
                        <h4 style="centred"><?php echo $lang['police'] . " " . $lang['gear']; ?> </h4>
                        <?php
                        echo "<textarea class='form-control' readonly rows='5' style='width: 100%' id='civ_gear' name='cop_gear'>" . $player->cop_gear . "</textarea>";
                        echo '<br>';
                        if ($_SESSION['permissions']['edit']['inventory']) {
                            echo '<a data-toggle="modal" href="#edit_cop_inv" class="btn btn-primary btn-xs" style="float: right;">';
                            echo '<i class="fa fa-pencil"></i></a>';
                        } ?>

                        <br>
                    </div>
                    <div class="tab-pane fade well" id="medic_inv">
                        <h4 style="centred"><?php echo $lang['medic'] . " " . $lang['gear']; ?> </h4>
                        <?php
                        echo "<textarea class='form-control' readonly rows='5' style='width: 100%' id='civ_gear' name='med_gear'>" . $player->med_gear . "</textarea>";
                        echo '<br>';
                        if ($_SESSION['permissions']['edit']['inventory']) {
                            echo '<a data-toggle="modal" href="#edit_med_inv" class="btn btn-primary btn-xs" style="float: right;">';
                            echo '<i class="fa fa-pencil"></i></a>';
                        } ?>
                        <br>
                    </div>
                <?php } ?>

                <?php if ($player->playerid == $_SESSION['playerid']) { ?>
                    <div class="tab-pane fade" id="house">
                        <div class="table-responsive">
                            <?php
                            $sql = "SELECT `pos`,`id` FROM `houses` WHERE `pid` = '" . $player->playerid . "' ORDER BY `id` DESC LIMIT 8";
                            $result_of_query = $db_link->query($sql);
                            if ($result_of_query->num_rows > 0) {
                                ?>
                                <table class="table table-bordered table-hover table-striped">
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
                                        echo "<td>" . $row["pos"] . "</td>";
                                        echo "<td><a class='btn btn-primary btn-xs' href='" . $settings['url'] . "editHouse/" . $row["id"] . "'>";
                                        echo "<i class='fa fa-pencil'></i></a></td>";
                                        echo "</tr>";
                                    };
                                    ?>
                                    </tbody>
                                </table>
                                <?php echo '<a class="fa fa-caret-right fa-2x" style="float: right; padding-right:15px;" href="' . $settings['url'] . 'houses/' . $player->playerid . '"> More</a>';
                            } else  echo errorMessage(31, $lang);
                        < / div >
                    < / div >
                < ? php } ?>

                <?php if ($player->playerid == $_SESSION['playerid']) { ?>
                    <div class="tab-pane fade" id="veh">
                        <div class="table-responsive">
                        <?php
                            $sql = "SELECT `classname`,`type`,`id`,`plate` FROM `vehicles` WHERE `pid` = '" . $player->playerid . "' ORDER BY `id` DESC LIMIT 8";
                            $result_of_query = $db_link->query($sql);
                            if ($result_of_query->num_rows > 0) {
                                $veh = $result_of_query->fetch_object();
                                echo '<table class="table table-bordered table-hover table-striped">';
                                echo '<thead><tr>';
                                echo '<th>' . $lang['class'] . '</th>';
                                echo '<th>' . $lang['type'] . '</th>';
                                echo '<th>' . $lang['plate'] . '</th>';
                                if ($_SESSION['permissions']['edit']['vehicles']) {
                                    echo "<th>" . $lang['edit'] . "</th>";
                                }
                                echo '</tr></thead><tbody';
                                echo '<tr>';
                                echo '<td>' . carName($veh->classname) . '</td>';
                                echo '<td>' . carType($veh->type, $lang) . '</td>';
                                echo '<td>' . $veh->plate . '</td>';

                                if ($_SESSION['permissions']['edit']['vehicles']) {
                                    echo "<td><a class='btn btn-primary btn-xs' href='" . $settings['url'] . "editVeh.php?ID=" . $veh->id . "'>";
                                    echo "<i class='fa fa-pencil'></i></a></td>";
                                }

                                echo '</tr>';
                                echo '</tbody></table>';
                                echo '<a class="fa fa-caret-right fa-2x" style="float: right; padding-right:15px;" href="' . $settings['url'] . 'vehicles/' . $player->playerid . '"> More</a>';

                            } else  echo errorMessage(31, $lang);
                        ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?php } 