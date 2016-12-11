<?php
$db_link = serverConnect();

if (isset($_POST["editType"])) {
    if ($_SESSION['permissions']['edit']['houses']) {
    switch ($_POST["editType"]) {
        case "house_inv":
            $hInv = $_POST["hInv"];
            $sql = "UPDATE `houses` SET `inventory`='" . $hInv . "' WHERE `houses`.`id` = '" . $hID . "'";
            $db_link->query($sql);
            message($lang['house'] . ' ' . $lang['updated']);
            break;

        case "house_cont":
            $hCont = $_POST["hCont"];
            $sql = "UPDATE `houses` SET `containers`='" . $hCont . "' WHERE `houses`.`id` = '" . $hID . "'";
            $db_link->query($sql);
            message($lang['house'] . ' ' . $lang['updated']);
            break;

        case "house_del":
            $sql = "DELETE FROM `houses` WHERE `houses`.`id` = '" . $hID . "'";
            $db_link->query($sql);
            header("location: " . $settings['url'] . "houses");
            break;

        case "house_details":
            $hPos = $_POST["hPos"];
            $hOwn = $_POST["hOwn"];
            $hOwned = $_POST["hOwned"];
            $sql = "UPDATE `houses` SET `pid`='" . $hOwn . "',`pos`='" . $hPos . "',`owned`='" . $hOwned . "' WHERE `id` = '" . $hID . "'";
            $db_link->query($sql);
            message($lang['house'] . ' ' . $lang['updated']);
            break;
        }
    }
}

$sql = "SELECT * FROM `houses` WHERE `id` ='" . $hID . "';";
$result_of_query = $db_link->query($sql);
if ($result_of_query->num_rows > 0) {
    $house = $result_of_query->fetch_object();
?>
<div class="col-md-4" style="float:left;  padding-top:20px;">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="panel-title"><i
                    class="fa fa-child fa-fw"></i><?php echo nameID($house->pid, $db_link) . "'s " . $lang['house']; ?>
            </h2>
        </div>
        <div class="panel-body">
            <?php
            echo '<center><img class="img-responsive" src="' . $settings['url'] . 'assets/img/house/1.jpg"/>';

            echo "<h4>" . $lang['owner'] . ": <a href='" . $settings['url'] . "editPlayer/" . uID($house->pid, $db_link) . "'>" . nameID($house->pid, $db_link) . "</a></h4>";
            echo "<h4>" . $lang['position'] . ": " . $house->pos . "</h4>";

            if ($_SESSION['permissions']['edit']['houses']) {
                echo '
                <div style="float: right;">
                    <a data-toggle="modal" href="#edit_house" class="btn btn-primary btn-xs" style="margin-right:3px">
                        <i class="fa fa-pencil"></i>
                    </a>
                    <a data-toggle="modal" href="#del_house" class="btn btn-danger btn-xs" style="margin-right:3px">
                        <i class="fa fa-exclamation-triangle"></i>
                    </a>
                </div>';
            }
            echo "</center>";
            ?>
        </div>
    </div>
</div>

<div class="col-md-8" style="float:right; padding-top:20px;">
    <?php
    echo '<div class="panel panel-default" style="float:left; width:100%; margin:0 auto;">';
    echo '<ul id="myTab" class="nav nav-tabs">';
    echo '<li><a href="#house_inv" data-toggle="tab">' . $lang['inventory'] . '</a></li>';
    echo '<li><a href="#house_cont" data-toggle="tab">' . $lang['containers'] . '</a></li>';
    echo '</ul>';
    ?>
    <div id="myTabContent" class="tab-content">
        <div class="tab-pane fade active in well" id="house_inv">
            <h4 style="centred"><?php echo $lang['house'] . " " . $lang['inventory']; ?> </h4>
            <?php
                echo "<textarea class='form-control' readonly rows='5' style='width: 100%' id='civ_gear' name='civ_gear'>" . $house->inventory . "</textarea>";
            ?>
            <br>
            <?php if ($_SESSION['permissions']['edit']['houses']) { ?>
            <a data-toggle="modal" href="#edit_house_inv" class="btn btn-primary btn-xs" style="float: right;">
                <i class="fa fa-pencil"></i>
            </a>
            <?php } ?>
            <br>
        </div>
        <div class="tab-pane fade well" id="house_cont">
            <h4 style="centred"><?php echo $lang['house'] . " " . $lang['containers']; ?> </h4>
            <?php
                echo "<textarea class='form-control' readonly rows='5' style='width: 100%' id='house_cont' name='house_cont'>" . $house->containers . "</textarea>";
            ?>
            <br>
            <?php if ($_SESSION['permissions']['edit']['houses']) { ?>
            <a data-toggle="modal" href="#edit_house_cont" class="btn btn-primary btn-xs" style="float: right;">
                <i class="fa fa-pencil"></i>
            </a>
            <?php } ?>
            <br>
        </div>
    </div>
</div>
</div>

<div class="modal fade" id="edit_house_inv" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><span class="glyphicon glyphicon-pencil"></span><?php echo $lang['edit'] . " " . $lang['house'] . " " . $lang['inventory'] ?>
                </h4>
            </div>
                <form method="post" action="<?php echo $settings['url'] . "editHouse/" . $hID ?>" role="form">
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="hidden" name="editType" value="house_inv"/>

                            <div class="row">
                                    <textarea class="form-control" rows="10"
                                              name="hInv"><?php echo $house->inventory ?></textarea>
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
<div class="modal fade" id="edit_house_cont" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">
                    <span
                        class="glyphicon glyphicon-pencil"></span> <?php echo $lang['edit'] . " " . $lang['house'] . " " . $lang['containers'] ?>
                </h4>
            </div>
                <form method="post" action="<?php echo $settings['url'] . "editHouse/" . $hID ?>" role="form">
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="hidden" name="editType" value="house_cont"/>

                            <div class="row">
                                    <textarea class="form-control" rows="10"
                                              name="hCont"><?php echo $house->containers; ?></textarea>
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
<div class="modal fade" id="del_house" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><span class="glyphicon glyphicon-pencil"></span> <?php echo $lang['delete'] . " " . $lang['house'] ?>
                </h4>
            </div>
            <form method="post" action="<?php echo $settings['url'] . "editHouse/" . $hID ?>" role="form">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="editType" value="house_del"/>

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
<div class="modal fade" id="edit_house" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">
                    <span class="glyphicon glyphicon-pencil"></span> <?php echo $lang['edit'] . " " . $lang['house'] ?>
                </h4>
            </div>
                <form method="post" action="<?php echo $settings['url'] . "editHouse/" . $hID ?>" role="form">
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="hidden" name="editType" value="house_details"/>

                            <div class="row">
                                <center>
                                    <?php
                                    echo "<h4>" . $lang['owner'] . ": <input id='hOwn' name='hOwn' type='text' value='" . $house->pid . "'></td><br/>";
                                    echo "<h4>" . $lang['position'] . ": <input id='hPos' name='hPos' type='text' value='" . $house->pos . "'readonly></td><br/>";
                                    echo "<h4>" . $lang['owned'] . ":  ";
                                    echo "<select id='hOwned' name='hOwned'>";
                                    echo '<option value="0"' . select('0', $house->owned) . '>' . $lang['no'] . '</option>';
                                    echo '<option value="1"' . select('1', $house->owned) . '>' . $lang['yes'] . '</option>';
                                    echo "</select>";
                                    echo "</center>";
                                    ?>
                                </center>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default" data-dismiss="modal" type="reset">Close</button>
                        <button class="btn btn-primary" type="submit"><?php echo $lang['subChange'] ?></button>
                    </div>
                </form>
        </div>
    </div>
</div>
<?php } else errorMessage(3, $lang); ?>
