<?php
if ($settings['url'] == "/") {
    require_once("config/carNames.php");
    require_once("config/images.php");
} else {
    require_once(realpath($settings['url']) . "config/carNames.php");
    require_once(realpath($settings['url']) . "config/images.php");
}

$db_link = serverConnect();

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
            echo "<h4>" . $lang['owner'] . ": " . nameID($veh->pid, $db_link) . "</h4>";
            echo "<h4>" . $lang['class'] . ": " . carName($veh->classname) . "</h4>";
            echo "<h4>" . $lang['plate'] . ": " . $veh->plate . "</h4>";

            if ($veh->alive == false) {
                echo "<h4><span class='label label-danger'>" . $lang["not"] . " " . $lang["alive"] . "</span> ";
            } else {
                echo "<h4><span class='label label-success'>" . $lang["alive"] . "</span> ";
            }

            if ($veh->active == false) {
                echo " <span class='label label-danger'>" . $lang["not"] . " " . $lang["active"] . "</span></h4>";
            } else {
                echo " <span class='label label-success'>" . $lang["active"] . "</span></h4>";
            }

            } else {
                echo "<h1>" . $lang['noRes'] . "</h1>";
            }
            echo "</center>";
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
                <br>
            </div>
        </div>
    </div>