<?php
$db_link = serverConnect();

if (isset($_POST["squad"])) {
    $xml = '<?xml version="1.0"?>
    <?DOCTYPE squad SYSTEM "squad.dtd"?>
    <?xml-stylesheet href="squad.xsl?" type="text/xsl"?>

    <squad nick="CZ">
    <name>Clan of Zombies</name>
    <email>clanofzombies@clanofzombies.com</email>
    <web></web>
    <picture>logo.paa</picture>
    <title>CZ</title>';

    $sql = "SELECT `name`,`members` FROM `gangs` WHERE `id` = '" . $gID . "';";
    $result = $db_link->query($sql);
    $gang = $result->fetch_object();
    $members = str_replace('`]"', '', str_replace('"[`', '', $gang->members));
    $members = explode('`,`', $members);
    foreach ($members as $member) {
        $name = nameID($member, $db_link);
        $xml .= '<member id="' . $member . '" nick="' . $name . '">
        <name>'.$name . '</name><email></email><icq></icq><remark></remark></member>';
    }
    $xml .= '</squad>';
    var_dump($xml);
}

if (isset($_POST["editType"])) {
    if (formtoken::validateToken($_POST)) {
        if ($_SESSION['permissions']['edit']['gangs']) {
            switch ($_POST["editType"]) {
                case "edit_members":
                    $gMem = clean($_POST["gMem"], 'string');
                    $sql = "UPDATE `gangs` SET `members`='" . $gMem . "' WHERE `gangs`.`id` = '" . $gID . "'";
                    $result_of_query = $db_link->query($sql);
                    message($lang['updated']);
                    break;

                case "del_gang":
                    $sql = "DELETE FROM `gangs` WHERE `gangs`.`id` = '" . $gID . "'";
                    $result_of_query = $db_link->query($sql);
                    message($lang['updated']);
                    break;

                case "gang_edit":
                    $gname = clean($_POST["gname"], 'string');
                    $gowner = clean($_POST["gowner"], 'int');
                    $gMM = clean($_POST["gMM"], 'int');
                    $gbank = clean($_POST["gbank"], 'int');
                    $gAct = clean($_POST["gAct"], 'int');
                    $sql = "UPDATE `gangs` SET `owner`='" . $gowner . "',`name`='" . $gname . "',`maxmembers`='" . $gMM . "',`bank`='" . $gbank . "',`active`='" . $gAct . "' WHERE `gangs`.`id` = '" . $gID . "'";
                    $result_of_query = $db_link->query($sql);
                    message($lang['updated']);
                    break;
            }
        }
    } else {
        message($lang['expired']);
    }
    }

$sql = 'SELECT * FROM `gangs` WHERE `id` ="' . $gID . '";';
$result_of_query = $db_link->query($sql);
if ($result_of_query->num_rows > 0) {
    $gang = $result_of_query->fetch_object();
?>
<div class="col-md-3" style="float:left;  padding-top:20px;">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="panel-title"><i
                    class="fa fa-child fa-fw"></i><?php echo nameID($gang->owner, $db_link) . "'s " . $lang['gang']; ?>
            </h2>
        </div>
        <div class="panel-body">
            <center><img src="<?php echo $settings['url'] ?>assets/img/uniform/U_BG_Guerilla2_3.jpg"/>
                <?php
                echo "<h4>" . $lang['owner'] . ": <a href='" . $settings['url'] . "editPlayer/" . uID($gang->owner, $db_link) . "'>" . nameID($gang->owner, $db_link) . "</a></h4>";
                echo "<h4>" . $lang['name'] . ": " . $gang->name . "</h4>";
                ?>
                <span class="fa fa-2x fa-bank"></span>
                <h4> <?php echo $lang['bank'] . ": " . $gang->bank; ?> </h4>
                <?php
                if ($gang->active == 0) {
                    echo "<h4><button type='button' class='gangActive btn btn-danger'>" . $lang["not"] . " " . $lang["active"] . "</button></h4> ";
                } else {
                    echo "<h4><button type='button' class='gangActive btn btn-success'>" . $lang["active"] . "</button></h4> ";
                }
                if ($_SESSION['permissions']['edit']['gangs']) {
                    echo '<a data-toggle="modal" href="#edit_gang" class="btn btn-primary btn-xs" style="float: right; margin-right:3px;">';
                    echo '<i class="fa fa-pencil"></i>';
                    echo '</a>';
                    echo '<a data-toggle="modal" href="#gang_del" class="btn btn-danger btn-xs" style="float: right; margin-right:3px;">';
                    echo '<i class="fa fa-warning"></i>';
                    echo '</a>';
                }
                echo "</center>";
                ?>
        </div>
    </div>
</div>

<div class="col-md-9" style="float:right; padding-top:20px;">
    <div class="row mtbox">
        <div class="col-md-2 col-sm-2 col-md-offset-1 box0">
            <div class="box1">
                <span class="fa fa-3x fa-users"></span>
                <h4> <?php echo $lang['maxMembers'] . ": " . $gang->maxmembers; ?> </h4>
            </div>
        </div>
    </div>

    <div class="panel panel-default" style="float:left; width:100%; margin:0 auto;">
        <ul id="myTab" class="nav nav-tabs">
            <li><a href="#gang_members" data-toggle="tab"><?php echo $lang['members']; ?></a></li>
        </ul>
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade active in well" id="civ_inv">
                <h4 style="centred"><?php echo $lang['gang'] . " " . $lang['members']; ?> </h4>
                <?php
                    $return = stripArray($gang->members, 1);

                    foreach ($return as $value) {
                        echo "<span class='label label-success' style='margin-right:3px; line-height:2;'>" . nameID($value, $db_link) . "</span> ";
                    }
                }
                ?>
                <br>
                <a data-toggle="modal" href="#edit_gang_members" class="btn btn-primary btn-xs" style="float: right;">
                    <i class="fa fa-pencil"></i>
                </a>
                <br>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="edit_gang_members" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><span
                        class="glyphicon glyphicon-pencil"></span><?php echo " " . $lang['edit'] . " " . $lang['gang'] . " " . $lang['members']; ?>
                </h4>
            </div>
            <form method="post" action="<?php echo $settings['url'] . 'editGang/' . $gID; ?>" role="form">
                <?php echo formtoken::getField() ?>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="editType" value="edit_members"/>

                        <div class="row">
                            <textarea id='gMem' name='gMem' class="form-control"
                                      rows="10"><?php echo $gang->members ?></textarea>
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
<div class="modal fade" id="gang_del" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">
                    <span
                        class="glyphicon glyphicon-pencil"></span><?php echo " " . $lang['delete'] . " " . $lang['gang']; ?>
                </h4>
            </div>
            <form method="post" action="<?php echo $settings['url'] . 'editGang/' . $gID; ?>" role="form">
                <?php echo formtoken::getField() ?>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="editType" value="del_gang"/>

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
<div class="modal fade" id="edit_gang" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><span
                        class="glyphicon glyphicon-pencil"></span><?php echo " " . $lang['edit'] . " " . $lang['gang']; ?>
                </h4>
            </div>
            <form method="post" action="<?php echo $settings['url'] . 'editGang/' . $gID; ?>" role="form">
                <?php echo formtoken::getField() ?>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="editType" value="gang_edit"/>

                        <div class="row">
                            <center>
                                <?php
                                echo "<center>";
                                echo "<h3>" . $lang['name'] . ":  <input id='gname' name='gname' type='text' value='" . $gang->name . "'></td><br/>";
                                echo "<h4>" . $lang['owner'] . ":   <input id='gowner' name='gowner' type='number' value='" . $gang->owner . "'></td><br/>";
                                echo "<h4>" . $lang['maxMembers'] . ":   <input id='gMM' name='gMM' type='number' value='" . $gang->maxmembers . "'></td><br/>";
                                echo "<h4>" . $lang['bank'] . ":    <input id='gbank' name='gbank' type='number' value='" . $gang->bank . "'></td><br/>";
                                echo "<h4>" . $lang['active'] . ":   ";
                                echo "<select id='gAct' name='gAct'>";
                                    echo '<option value="0"' . select('0', $gang->active) . '>' . $lang['no'] . '</option>';
                                    echo '<option value="1"' . select('1', $gang->active) . '>' . $lang['yes'] . '</option>';
                                echo "</select>";
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
<?php if ($_SESSION['permissions']['edit']['licences']) { ?>
<script>
$( document ).ready(function() {
    $(".gangActive").click(function () {
        $(this).toggleClass('btn-success btn-danger');
        $.post( "<?php echo $settings['url'] ?>hooks/gangActive.php", {gang: "<?php echo $gID ?>"} );
    });
});
</script>
<?php } ?>
