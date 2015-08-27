<?php
$db_link = serverConnect();

require_once("config/carNames.php");
require_once("config/images.php");

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
                    while ($row = mysqli_fetch_assoc($result_of_query)) {
                        logAction($_SESSION['user_name'], $lang['edited'] . ' ' . nameID($row["pid"], $db_link) . '\'s ' . carName($row["classname"]) . '(' . $vehID . ')', 1);
                    }
                    break;

                case "veh_store":
                    $sql = "UPDATE `vehicles` SET `alive`='1',`active`='0' WHERE `vehicles`.`id` = '" . $vehID . "'";
                    $result_of_query = $db_link->query($sql);
                    message($lang['vehicle'] . ' ' . $lang['editstoreded']);
                    $sql = "SELECT `pid`,`classname` FROM `vehicles` WHERE `id` ='" . $vehID . "';";
                    $result_of_query = $db_link->query($sql);
                    while ($row = mysqli_fetch_assoc($result_of_query)) {
                        logAction($_SESSION['user_name'], $lang['stored'] . ' ' . nameID($row["pid"], $db_link) . '\'s ' . carName($row["classname"]) . '(' . $vehID . ')', 1);
                    }
                    break;

                case "veh_del":
                    $sql = "DELETE FROM `vehicles` WHERE `vehicles`.`id` = '" . $vehID . "'";
                    $result_of_query = $db_link->query($sql);
                    message($lang['vehicle'] . ' ' . $lang['deleted']);
                    $sql = "SELECT `pid`,`classname` FROM `vehicles` WHERE `id` ='" . $vehID . "';";
                    $result_of_query = $db_link->query($sql);
                    while ($row = mysqli_fetch_assoc($result_of_query)) {
                        logAction($_SESSION['user_name'], $lang['deleted'] . ' ' . nameID($row["pid"], $db_link) . '\'s ' . carName($row["classname"]) . '(' . $vehID . ')', 2);
                    }
                    break;

                case "veh_edit":
                    $vehSide = $_POST["vehSide"];
                    $vehPlate = $_POST["vehPlate"];
                    $vehCol = $_POST["vehCol"];
                    $sql = "UPDATE `vehicles` SET `side`='" . $vehSide . "',`type`='" . $vehType . "',`color`='" . $vehCol . "' WHERE `vehicles`.`id` = '" . $vehID . "'";
                    $result_of_query = $db_link->query($sql);
                    message($lang['vehicle'] . ' ' . $lang['edited']);
                    $sql = "SELECT `pid` FROM `vehicles` WHERE `id` ='" . $vehID . "';";
                    $result_of_query = $db_link->query($sql);
                    while ($row = mysqli_fetch_assoc($result_of_query)) {
                        logAction($_SESSION['user_name'], $lang['edited'] . ' ' . nameID($row["pid"], $db_link) . '\'s ' . carName($vehClass) . '(' . $vehID . ')', 1);
                    }
                    break;
            }
        } else {
            message($lang['expired']);
        }
    }
}

$sql = "SELECT * FROM `vehicle` INNER JOIN `account` ON vehicle.account_uid=account.uid WHERE `id` ='" . $vehID . "';";
$result_of_query = $db_link->query($sql);
if ($result_of_query->num_rows > 0) {
    $veh = $result_of_query->fetch_object();
    $car = carName($veh->class)
?>
<style>
    .state, .state-full { cursor: pointer; }
    .state-full { display: none; }
    .state span { float: left; }
    div { overflow: hidden; }
</style>
<script>
function fill() {
$.post( "<?php echo $settings['url']; ?>hooks/car.php", { type: "fuel", vehID: "<?php echo $veh->id ?>" } );
    var $fuel = $('#fill');
    $fuel.width('100%');
    $fuel.text('100%');
}
function fix() {
$.post( "<?php echo $settings['url']; ?>hooks/car.php", { type: "repair", vehID: "<?php echo $veh->id ?>" } );
    var $fix = $('#fix');
    $fix.width('0%');
    $fix.text('0%');
}
</script>
<div class="col-md-4" style="float:left; padding-top:20px;">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="panel-title"><i class="fa fa-taxi fa-fw"></i><?php echo $veh->name . "'s " . $car; ?>
            </h2>
        </div>
        <div class="panel-body">
            <?php $carPic = getPic($veh->class);
            echo '<center><img src="' . $settings['url'] . 'assets/img/cars/' . $carPic . '.jpg" class="img-responsive" alt="' . $veh->class . '">'; ?>
            <?php
            echo "<h4>" . $lang['owner'] . ": <a href='" . $settings['url'] . "editPlayer/" . str_replace(' ','-',$veh->name) . "'>" . $veh->name . "</a></h4>";
            echo "<h4>" . $lang['class'] . ": " . $car . "</h4>";
            $width = $veh->fuel * 100;
            ?>
            <div class="col-md-10">
                <div class="progress">
                    <div class="progress-bar progress-bar-success" id="fill" role="progressbar"  aria-valuemin="0" aria-valuemax="100" aria-valuenow="<?php echo $width; ?>" style="width:<?php echo $width; ?>%">
                        <?php if ($width > 0) echo $width . '%'; else echo $lang['noFuel']; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <button class="btn btn-primary btn-xs" onclick="fill()"><i class="fa fa-filter"></i></button>
            </div>
            <?php
                $width = $veh->damage * 100;
            ?>
            <div class="col-md-10">
                <div class="progress">
                    <div class="progress-bar progress-bar-danger" id="fix" role="progressbar"  aria-valuemin="0" aria-valuemax="100" aria-valuenow="<?php echo $width; ?>" style="width:<?php echo $width; ?>%">
                        <?php if ($width < 100) echo $width . '%'; else echo $lang['broken']; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <button class="btn btn-primary btn-xs" onclick="fix()"><i class="fa fa-wrench"></i></button>
            </div>
            <div class="col-md-5">
                <h4>Pin Code: <span class="pin">••••</span></h4>
            </div>
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
                            class="glyphicon glyphicon-pencil"></span><?php echo " " . $lang['DELETE'] . " " . $lang['vehicle']; ?>
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
<?php if ($_SESSION['permissions']['super_admin']) { ?>
<script>
$('.pin').hover(function() {
    if ($(this).text() == '••••'){ $(this).text('<?php echo $veh->pin_code ?>'); }
    else { $(this).text('••••') }
});
</script>
<?php } ?>
<?php } else echo "<h1>" . errorMessage(32, $lang) . "</h1>";