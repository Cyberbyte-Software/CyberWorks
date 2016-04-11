<?php

require("config/carNames.php");
require_once("config/images.php");


$db_link = serverConnect();

if (isset($_POST["editType"])) {
    if ($_SESSION['permissions']['edit']['vehicles']) {
        if (formtoken::validateToken($_POST)) {
            switch ($_POST["editType"]) {
                case "veh_inv":
                    $vehInv = $_POST["vehInv"];
                    $sql = "UPDATE `vehicles` SET `inventory`='" . $vehInv . "' WHERE `vehicles`.`id` = '" . $vehID . "'";
                    $result_of_query = $db_link->query($sql);
                    message($lang['vehicle'] . ' ' . $lang['edited']);
                    $sql = "SELECT `pid`,`classname` FROM `vehicles` WHERE `id` ='" . $vehID . "';";
                    $result_of_query = $db_link->query($sql);
                    $vehTemp = $result_of_query->fetch_object();
                    logAction($_SESSION['user_name'], $lang['edited'] . ' a ' . carName($vehTemp->classname) . ' (' . $vehID . ') ' . $lang['inventory'] . ' belonging to '.  nameID($vehTemp->pid, $db_link), 1);
                    break;

                case "veh_store":
                    $sql = "UPDATE `vehicles` SET `alive`='1',`active`='0' WHERE `vehicles`.`id` = '" . $vehID . "'";
                    $result_of_query = $db_link->query($sql);
                    message($lang['vehicle'] . ' stored');
                    $sql = "SELECT `pid`,`classname` FROM `vehicles` WHERE `id` ='" . $vehID . "';";
                    $result_of_query = $db_link->query($sql);
                    $vehTemp = $result_of_query->fetch_object();
                    logAction($_SESSION['user_name'], $lang['stored'] . ' ' . nameID($vehTemp->pid, $db_link) . ' ' . carName($vehTemp->classname) . '(' . $vehID . ')', 1);

                    break;

                case "veh_del":
                    $sql = "SELECT `pid`, `classname`  FROM `vehicles` WHERE `id` ='" . $vehID . "';";
                    $result_of_query = $db_link->query($sql);
                    $vehTemp = $result_of_query->fetch_object();
                    logAction($_SESSION['user_name'], $lang['deleted'] . ' ' . nameID($vehTemp->pid, $db_link) . ' ' . carName($vehTemp->classname) . '(' . $vehID . ')', 2);

                    $sql = "DELETE FROM `vehicles` WHERE `vehicles`.`id` = '" . $vehID . "'";
                    $result_of_query = $db_link->query($sql);
                    message($lang['vehicle'] . ' ' . $lang['deleted']);
                    break;

                case "veh_edit":
                    $vehSide = $_POST["vehSide"];
                    $vehPlate = $_POST["vehPlate"];
                    $vehCol = $_POST["vehCol"];
                    $vehType = $_POST["vehType"];

                    $sql = "UPDATE `vehicles` SET `side`='" . $vehSide . "',`type`='" . $vehType . "',`color`='" . $vehCol . "' WHERE `vehicles`.`id` = '" . $vehID . "'";
                    $result_of_query = $db_link->query($sql);

                    message($lang['vehicle'] . ' ' . $lang['edited']);

                    $sql = "SELECT `pid`, `classname` FROM `vehicles` WHERE `id` ='" . $vehID . "';";
                    $result_of_query = $db_link->query($sql);
                    $vehTemp = $result_of_query->fetch_object();
                    logAction($_SESSION['user_name'], $lang['edited'] . ' ' . nameID($vehTemp->pid, $db_link) . ' ' . carName($vehTemp->classname) . '(' . $vehID . ')', 1);
                    break;
            }
        } else {
            message($lang['expired']);
        }
    }
}

$sql = "SELECT * FROM `vehicles` WHERE `id` ='" . $vehID . "';";
$result_of_query = $db_link->query($sql);
if ($result_of_query->num_rows > 0) {
    $veh = $result_of_query->fetch_object();
?>
<div class="col-md-4" style="float:left;  padding-top:20px;">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="panel-title"><i
                    class="fa fa-child fa-fw"></i><?php echo nameID($veh->pid, $db_link) . "'s " . carName($veh->classname); ?>
            </h2>
        </div>
        <div class="panel-body">
            <?php $carPic = getPic($veh->classname);
            echo '<center><img src="' . $settings['url'] . 'assets/img/cars/' . $carPic . '.jpg" class="img-responsive" alt="' . $veh->classname . '">'; ?>
            <?php
            echo "<h4>" . $lang['owner'] . ": <a href='" . $settings['url'] . "editPlayer/" . uID($veh->pid, $db_link) . "'>" . nameID($veh->pid, $db_link) . "</a></h4>";
            echo "<h4>" . $lang['class'] . ": " . carName($veh->classname) . "</h4>";
            echo "<h4>" . $lang['plate'] . ": " . $veh->plate . "</h4>";

            if ($veh->alive == false) {
                echo "<h4><span class='label label-danger'>" . $lang["not"] . " " . $lang["alive"] . "</span></h4>";
            } else {
                echo "<h4><span class='label label-success'>" . $lang["alive"] . "</span></h4> ";
            }

            if ($veh->active == false) {
                echo " <h4><span class='label label-danger'>" . $lang["not"] . " " . $lang["active"] . "</span></h4>";
            } else {
                echo " <h4><span class='label label-success'>" . $lang["active"] . "</span></h4>";
            }
            if ($_SESSION['permissions']['edit']['vehicles']) {
                echo '
                <div style="float: right;">
                    <a data-toggle="modal" href="#edit_veh" class="btn btn-primary btn-xs" style="margin-right:3px">
                        <i class="fa fa-pencil"></i>
                    </a>
                    <a data-toggle="modal" href="#store_veh" class="btn btn-warning btn-xs" style="margin-right:3px">
                        <i class="fa fa-wrench"></i>
                    </a>
                    <a data-toggle="modal" href="#del_veh" class="btn btn-danger btn-xs" style="margin-right:3px">
                        <i class="fa fa-exclamation-triangle"></i>
                    </a>
                </div>';
            }
            ?>
        </div>
    </div>
</div>

<div class="col-md-8" style="float:right; padding-top:20px;">
    <div class="row mtbox">
        <?php
        echo '<div class="col-md-2 col-sm-2 col-md-offset-1 box0">';
        echo '<div class="box1">';
        switch ($veh->side) {
            case 'civ':
                ?>
                <span class="fa fa-3x fa-car"></span>
                <h4> <?php echo $lang['side'] . ": " . $lang['civ']; ?> </h4>
                <?php
                break;
            case 'cop':
                ?>
                <div class="col-md-2 col-sm-2 col-md-offset-1 box0">
                    <div class="box1">
                        <span class="fa fa-3x fa-taxi"></span>
                        <h4> <?php echo $lang['side'] . ": " . $lang['police']; ?> </h4>
                    </div>
                </div>
                <?php
                break;
            case 'med':
                ?>
                <span class="fa fa-3x fa-ambulance"></span>
                <h4> <?php echo $lang['side'] . ": " . $lang['medic']; ?> </h4>
                <?php
                break;
        }
        echo '</div></div>';
        echo '<div class="col-md-2 col-sm-2 box0">';
        echo '<div class="box1">';
        switch ($veh->type) {
            case 'Car':
                echo "<span class='fa fa-3x fa-car'></span>";
                echo "<h4>" . $lang['type'] . ": " . $lang['car'] . "</h4>";
                break;
            case 'Air':
                echo "<span class='fa fa-3x fa-fighter-jet'></span>";
                echo "<h4>" . $lang['type'] . ": " . $lang['air'] . "</h4>";
                break;
            case 'Ship':
                echo "<span class='fa fa-3x fa-ship'></span>";
                echo "<h4>" . $lang['type'] . ": " . $lang['ship'] . "</h4>";
                break;
        }
        echo '</div>
        </div>
        </div>';
        ?>

        <div class="panel panel-default" style="float:left; width:100%; margin:0 auto;">
            <div class="panel-body">
                <h4 style="centred"><?php echo $lang['vehicle'] . " " . $lang['inventory']; ?> </h4>
                <?php
                $inv = str_replace(']"', "", str_replace('"[', "", $veh->inventory));
                if (empty($inv)) {
                    $inv = 'Empty';
                }
                echo "<textarea class='form-control' readonly rows='5' style='width: 100%' id='civ_gear' name='civ_gear'>" . $inv . "</textarea>";
                ?>
                <br>
                <a data-toggle="modal" href="#edit_veh_inv" class="btn btn-primary btn-xs" style="float: right;">
                    <i class="fa fa-pencil"></i>
                </a>
                <br>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit_veh_inv" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><span class="glyphicon glyphicon-pencil"></span><?php echo " " . $lang['edit'] . " " . $lang['vehicle'] . " " . $lang['inventory']; ?>
                    </h4>
                </div>
                <?php  echo '<form method="post" action="' . $settings['url'] . 'editVeh/' . $vehID . '">' ?>
                        <?php echo formtoken::getField() ?>
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="hidden" name="editType" value="veh_inv"/>

                                <div class="row">
                                    <textarea class="form-control" rows="10"
                                              name="vehInv"><?php echo $veh->inventory; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-default" data-dismiss="modal" type="reset"><?php echo $lang['close']; ?></button>
                            <button class="btn btn-primary" type="submit"><?php echo $lang['subChange'] ?></button>
                        </div>
                    </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="del_veh" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><span
                            class="glyphicon glyphicon-pencil"></span><?php echo " " . $lang['delete'] . " " . $lang['vehicle']; ?>
                    </h4>
                </div>
                <?php  echo '<form method="post" action="' . $settings['url'] . 'editVeh/' . $vehID . '">' ?>
                    <?php echo formtoken::getField() ?>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="hidden" name="editType" value="veh_del"/>

                            <div class="row">
                                <center><h4>Are you Sure?</h4></center>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" type="submit"><?php echo $lang['yes']; ?></button>
                        <button class="btn btn-primary" data-dismiss="modal"
                                type="reset"><?php echo $lang['no'] ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="store_veh" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><span
                            class="glyphicon glyphicon-pencil"></span><?php echo " " . $lang['store'] . " " . $lang['vehicle']; ?>
                    </h4>
                </div>
                <?php  echo '<form method="post" action="' . $settings['url'] . 'editVeh/' . $vehID . '">' ?>
                    <?php echo formtoken::getField() ?>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="hidden" name="editType" value="veh_store"/>

                            <div class="row">
                                <center><h4>Are you Sure?</h4></center>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" type="submit"><?php echo $lang['yes']; ?></button>
                        <button class="btn btn-primary" data-dismiss="modal"
                                type="reset"><?php echo $lang['no']; ?></button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <div class="modal fade" id="edit_veh" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><span class="glyphicon glyphicon-pencil"></span> Edit Player</h4>
                </div>
                <?php  echo '<form method="post" action="' . $settings['url'] . 'editVeh/' . $vehID . '">' ?>
                <?php echo formtoken::getField() ?>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="hidden" name="editType" value="veh_edit"/>

                            <div class="row">
                                <center>
                                    <?php
                                    echo "<h4>" . $lang['class'] . ":   <input id='vehClass' name='vehClass' type='text' value='" . $veh->classname . "' readonly></td><br/>";
                                    echo "<h4>" . $lang['plate'] . ":    <input id='vehPlate' name='vehPlate' type='number' value='" . $veh->plate . "'readonly></td><br/>";
                                    echo "<h4>" . $lang['side'] . ":   ";
                                    echo "<select id='vehSide' name='vehSide'>";
                                    echo '<option value="civ"' . select('civ', $veh->side) . '>' . $lang['civ'] . '</option>';
                                    echo '<option value="cop"' . select('cop', $veh->side) . '>' . $lang['cop'] . '</option>';
                                    echo '<option value="med"' . select('med', $veh->side) . '>' . $lang['medic'] . '</option>';
                                    echo "</select>";
                                    echo "<h4>" . $lang['type'] . ":   ";
                                    echo "<select id='vehType' name='vehType'>";
                                    echo '<option value="Car"' . select('Car', $veh->type) . '>' . $lang['car'] . '</option>';
                                    echo '<option value="Air"' . select('Air', $veh->type) . '>' . $lang['air'] . '</option>';
                                    echo '<option value="Ship"' . select('Ship', $veh->type) . '>' . $lang['ship'] . '</option>';
                                    echo "</select>";
                                    echo "<h4>" . $lang['colour'] . ":   <input id='vehCol' name='vehCol' type='number' value='" . $veh->color . "'></td><br/>";
                                    echo "</center>";
                                    ?>
                                </center>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default" data-dismiss="modal" type="reset">Close</button>
                        <button class="btn btn-primary" type="submit"><?php echo $lang['subChange']; ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php } else echo "<h1>" . errorMessage(32, $lang) . "</h1>";
