<?php
require_once(realpath($settings['url']) . "config/carNames.php");
require_once(realpath($settings['url']) . "config/images.php");
require_once(realpath($settings['url']) . "config/license.php");
require_once(realpath($settings['url']) . "config/crimes.php");

$db_link = serverConnect();

function getPlayerSkin($input, $list)
{
    if ($input !== '[]') {
        $name = after('[`', $input);
        $name = before('`', $name);

        if (in_array($name, $list)) {
            return $name;
        } else {
            return "Exile";
        }
    } else {
        return "Exile";
    }
}

if (isset($_POST["editType"])) {
    if (formtoken::validateToken($_POST)) {

    } else {
        message($lang['expired']);
    }
}

$sql = "SELECT * FROM `player` INNER JOIN `account` ON player.account_uid=account.uid WHERE `account_uid` = '" . $uID . "' OR account.name LIKE '%" . str_replace('-',' ',$uID) . "%' ORDER BY `id` DESC;";
$result = $db_link->query($sql);
if ($result->num_rows > 0) {
    $player = $result->fetch_object();

    $temp = "";
    $guid = $player->account_uid;
    for ($i = 0; $i < 8; $i++) {
        $temp .= chr($guid & 0xFF);
        $guid >>= 8;
    }
    $guid = md5('BE' . $temp);
?>
<div class="col-md-3" style="float:left; padding-top:20px;">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="panel-title"><i class="fa fa-child fa-fw"></i><?php echo $player->name; ?></h2>
        </div>

        <div class="panel-body">
            <?php
                echo '<center><img alt="' . $player->name . '" src="' . $settings['url'] . 'assets/img/uniform/' . getPlayerSkin($player->uniform, $playerSkins) . '.jpg">';

                echo '<h4>' . $lang['name'] . ": " . $player->name . '</h4>';
                echo '<h4>' . $lang['playerID'] . ": " . $player->account_uid . '</h4>';
                echo '<h4 style="word-wrap: break-word;">' . $lang['GUID'] . ': ' . $guid . '</h4>';
                if ($player->is_alive) {
                    echo '<h4><span class="label label-success">' . $lang['alive'] . '</span></h4>';
                } else {
                    echo '<h4><span class="label label-danger">' . $lang['not'] . ' ' . $lang['alive'] . '</span></h4>';
                }
            ?>

            <i class="fa fa-heartbeat"></i> <?php echo $lang['damage']; ?>
            <div class="progress">
              <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="<?php echo $player->damage; ?>" aria-valuemin="0" aria-valuemax="1" style="width: <?php echo round($player->damage * 100,2); ?>%;">
                <?php echo round($player->damage * 100,2); ?>%
              </div>
            </div>
            <i class="fa fa-cutlery"></i> <?php echo $lang['hunger']; ?>
            <div class="progress">
              <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $player->hunger; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo round($player->hunger,2); ?>%;">
                <?php echo round($player->hunger,2); ?>%
              </div>
            </div>
            <i class="fa fa-bed"></i> <?php echo $lang['fatigue']; ?>
            <div class="progress">
              <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $player->fatigue * 100; ?>" aria-valuemin="0" aria-valuemax="1" style="width: <?php echo $player->fatigue * 100; ?>%;">
                <?php echo $player->fatigue; ?>
              </div>
            </div>
            <i class="fa fa-glass"></i> <?php echo $lang['thirst']; ?>
            <div class="progress">
              <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="<?php echo $player->thirst; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo round($player->thirst,2); ?>%;">
                <?php echo round($player->thirst,2); ?>%
              </div>
            </div>
            <i class="fa fa-beer"></i> <?php echo $lang['alcohol']; ?>
            <div class="progress">
              <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="<?php echo $player->alcohol; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo round($player->alcohol,2); ?>%;">
                <?php echo round($player->alcohol,2); ?>%
              </div>
            </div>
            <?php
            if ($_SESSION['permissions']['view']['steam']) {
                echo '<a href="http://steamcommunity.com/profiles/' . $player->account_uid . '" target="_blank">';
                echo '<h4><i class="fa fa-steam"></i> Steam</h4></a>';
                echo '<div id="vacBan"></div>';
            }
            ?>
        </div>
    </div>
</div>

<!-- Right Container -->
<div class="col-md-9" style="float:right; padding-top:20px;">
    <div class="row mtbox">
        <div class="col-md-2 col-sm-2 col-md-offset-1 box0">
            <div class="box1">
                <span class="fa fa-3x fa-child"></span>
                <h4> <?php echo $lang['connections'] . ": " . $player->total_connections; ?></h4>
            </div>
        </div>
        <div class="col-md-2 col-sm-2 box0">
            <div class="box1">
                <span class="fa fa-3x fa-money"></span>
                <h4> <?php echo $lang['money'] . ": " . $player->money; ?></h4>
            </div>
        </div>
        <div class="col-md-2 col-sm-2 box0">
            <div class="box1">
                <span class="fa fa-3x fa-line-chart"></span>
                <h4> <?php echo $lang['score'] . ": " . $player->score; ?></h4>
            </div>
        </div>
        <div class="col-md-2 col-sm-2 box0">
            <div class="box1">
                <span class="fa fa-3x fa-heart-o"></span>
                <h4> <?php echo $lang['deaths'] . ": " . $player->deaths; ?></h4>
            </div>
        </div>
        <div class="col-md-2 col-sm-2 box0">
            <div class="box1">
                <span class="fa fa-3x fa-heartbeat"></span>
                <h4> <?php echo $lang['kills'] . ": " . $player->kills; ?></h4>
            </div>
        </div>
    </div>

    <div class="panel panel-default" style="float:left; width:100%; margin:0 auto;">
        <ul id="myTab" class="nav nav-tabs">
            <li class="dropdown active">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $lang['inventory']; ?> <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="#player_inv" data-toggle="tab"><?php echo $lang['player'] . ' ' . $lang['items']; ?></a></li>
                    <li><a href="#uniform_inv" data-toggle="tab"><?php echo $lang['uniform'] . ' ' . $lang['items']; ?></a></li>
                    <li><a href="#backpack_inv" data-toggle="tab"><?php echo $lang['backpack'] . ' ' . $lang['items']; ?></a></li>
                </ul>
            </li>
            <li><a href="#health" data-toggle="tab"> <?php echo $lang['playerHealth'] ?></a></li>
            <?php
            if ($_SESSION['permissions']['edit']['player']) {
                echo '<li><a href="#location" data-toggle="tab">' . $lang['spawnedAt'] . '</a></li>';
                echo '<li><a href="#account" data-toggle="tab">' . $lang['account'] . '</a></li>';
            }
            ?>
        </ul>
        <div class="panel-body">
            <div id="myTabContent" class="tab-content">
                <?php if ($_SESSION['permissions']['edit']['inventory']) { ?>
                    <div class="tab-pane fade in active well" id="player_inv">
                        <?php
                        echo '<div class="form-group"><label for="currentWeapon">' . $lang['current'] . ' ' . $lang['weapon'] . '</label><input class="form-control" id="currentWeapon" type="text" name="currentWeapon"';
                        echo "value='" . $player->current_weapon . "'></div>";

                        echo '<div class="form-group"><label for="primaryWeapon">' . $lang['primary'] . ' ' . $lang['weapon'] . '</label><input class="form-control" id="primaryWeapon" type="text" name="primaryWeapon"';
                        echo "value='" . $player->primary_weapon . "'></div>";
                        echo '<div class="form-group"><label for="primaryWeaponItems">' . $lang['primary'] . ' ' . $lang['weapon'] . ' ' . $lang['items'] . '</label><input class="form-control" id="primaryWeaponItems" type="text" name="primaryWeaponItems"';
                        echo "value='" . $player->primary_weapon_items . "'></div>";
                        echo '<div class="form-group"><label for="secondaryWeapon">' . $lang['secondary'] . ' ' . $lang['weapon'] . '</label><input class="form-control" id="secondaryWeapon" type="text" name="secondaryWeapon"';
                        echo "value='" . $player->secondary_weapon . "'></div>";
                        echo '<div class="form-group"><label for="secondaryWeaponItems">' . $lang['secondary'] . ' ' . $lang['weapon'] . ' ' . $lang['items'] . '</label><input class="form-control" id="secondaryWeaponItems" type="text" name="secondaryWeaponItems"';
                        echo "value='" . $player->secondary_weapon_items . "'></div>";

                        echo '<div class="form-group"><label for="handgunWeapon">' . $lang['handgun'] . ' ' . $lang['weapon'] . '</label><input class="form-control" id="handgunWeapon" type="text" name="handgunWeapon"';
                        echo "value='" . $player->handgun_weapon . "'></div>";
                        echo '<div class="form-group"><label for="handgunItems">' . $lang['handgun'] . ' ' . $lang['weapon'] . '</label><input class="form-control" id="handgunItems" type="text" name="handgunItems"';
                        echo "value='" . $player->handgun_items . "'></div>";

                        echo '<div class="form-group"><label for="goggles">' . $lang['goggles'] . '</label><input class="form-control" id="goggles" type="text" name="goggles"';
                        echo "value='" . $player->goggles . "'></div>";

                        echo '<div class="form-group"><label for="binocular">' . $lang['binocular'] . '</label><input class="form-control" id="binocular" type="text" name="binocular"';
                        echo "value='" . $player->binocular . "'></div>";

                        ?>
                    </div>
                    <div class="tab-pane fade well" id="uniform_inv">
                        <?php
                        echo '<div class="form-group"><label for="uniform">' . $lang['uniform'] . '</label><input class="form-control" id="uniform" type="text" name="uniform"';
                        echo "value='" . $player->uniform . "'></div>";
                        echo '<div class="form-group"><label for="uniformItems">' . $lang['uniform'] . ' ' . $lang['items'] . '</label><input class="form-control" id="uniformItems" type="text" name="uniformItems"';
                        echo "value='" . $player->uniform_items . "'></div>";
                        echo '<div class="form-group"><label for="uniformMagazines">' . $lang['uniform'] . ' ' . $lang['magazines'] . '</label><input class="form-control" id="uniformMagazines" type="text" name="uniformMagazines"';
                        echo "value='" . $player->uniform_magazines . "'></div>";
                        echo '<div class="form-group"><label for="uniformWeapons">' . $lang['primary'] . ' ' . $lang['weapon'] . '</label><input class="form-control" id="uniformWeapons" type="text" name="uniformWeapons"';
                        echo "value='" . $player->uniform_weapons . "'></div>";

                        echo '<div class="form-group"><label for="vest">' . $lang['uniform'] . '</label><input class="form-control" id="uniform" type="text" name="uniform"';
                        echo "value='" . $player->vest . "'></div>";
                        echo '<div class="form-group"><label for="vestItems">' . $lang['uniform'] . ' ' . $lang['items'] . '</label><input class="form-control" id="vestItems" type="text" name="vestItems"';
                        echo "value='" . $player->vest_items . "'></div>";
                        echo '<div class="form-group"><label for="vestMagazines">' . $lang['uniform'] . ' ' . $lang['magazines'] . '</label><input class="form-control" id="vestMagazines" type="text" name="vestMagazines"';
                        echo "value='" . $player->vest_magazines . "'></div>";
                        echo '<div class="form-group"><label for="vestWeapons">' . $lang['primary'] . ' ' . $lang['weapon'] . '</label><input class="form-control" id="vestWeapons" type="text" name="vestWeapons"';
                        echo "value='" . $player->vest_weapons . "'></div>";
                        echo '<div class="form-group"><label for="headgear">' . $lang['headgear'] . '</label><input class="form-control" id="headgear" type="text" name="headgear"';
                        echo "value='" . $player->headgear . "'></div>";
                        ?>
                    </div>
                    <div class="tab-pane fade well" id="backpack_inv">
                        <?php
                        echo '<div class="form-group"><label for="backpack">' . $lang['uniform'] . '</label><input class="form-control" id="uniform" type="text" name="uniform"';
                        echo "value='" . $player->backpack . "'></div>";
                        echo '<div class="form-group"><label for="backpackTtems">' . $lang['uniform'] . ' ' . $lang['items'] . '</label><input class="form-control" id="backpackTtems" type="text" name="backpackTtems"';
                        echo "value='" . $player->backpack_items . "'></div>";
                        echo '<div class="form-group"><label for="backpackMagazines">' . $lang['uniform'] . ' ' . $lang['magazines'] . '</label><input class="form-control" id="backpackMagazines" type="text" name="backpackMagazines"';
                        echo "value='" . $player->backpack_magazines . "'></div>";
                        echo '<div class="form-group"><label for="backpackWeapons">' . $lang['primary'] . ' ' . $lang['weapon'] . '</label><input class="form-control" id="backpackWeapons" type="text" name="backpackWeapons"';
                        echo "value='" . $player->backpack_weapons . "'></div>";
                        ?>
                    </div>
                <?php } else {
                    echo '<div class="tab-pane fade well" id="player_inv">';
                        echo '<div class="form-group"><label for="currentWeapon">' . $lang['current'] . ' ' . $lang['weapon'] . '</label><input class="form-control" id="currentWeapon" type="text" name="currentWeapon"';
                        echo "value='" . $player->current_weapon . "' readonly></div>";

                        echo '<div class="form-group"><label for="primaryWeapon">' . $lang['primary'] . ' ' . $lang['weapon'] . '</label><input class="form-control" id="primaryWeapon" type="text" name="primaryWeapon"';
                        echo "value='" . $player->primary_weapon . "' readonly></div>";
                        echo '<div class="form-group"><label for="primaryWeaponItems">' . $lang['primary'] . ' ' . $lang['weapon'] . ' ' . $lang['items'] . '</label><input class="form-control" id="primaryWeaponItems" type="text" name="primaryWeaponItems"';
                        echo "value='" . $player->primary_weapon_items . "'></div>";
                        echo '<div class="form-group"><label for="secondaryWeapon">' . $lang['secondary'] . ' ' . $lang['weapon'] . '</label><input class="form-control" id="secondaryWeapon" type="text" name="secondaryWeapon"';
                        echo "value='" . $player->secondary_weapon . "' readonly></div>";
                        echo '<div class="form-group"><label for="secondaryWeaponItems">' . $lang['secondary'] . ' ' . $lang['weapon'] . ' ' . $lang['items'] . '</label><input class="form-control" id="secondaryWeaponItems" type="text" name="secondaryWeaponItems"';
                        echo "value='" . $player->secondary_weapon_items . "' readonly></div>";

                        echo '<div class="form-group"><label for="handgunWeapon">' . $lang['handgun'] . ' ' . $lang['weapon'] . '</label><input class="form-control" id="handgunWeapon" type="text" name="handgunWeapon"';
                        echo "value='" . $player->handgun_weapon . "' readonly></div>";
                        echo '<div class="form-group"><label for="handgunItems">' . $lang['handgun'] . ' ' . $lang['weapon'] . '</label><input class="form-control" id="handgunItems" type="text" name="handgunItems"';
                        echo "value='" . $player->handgun_items . "' readonly></div>";

                        echo '<div class="form-group"><label for="goggles">' . $lang['goggles'] . '</label><input class="form-control" id="goggles" type="text" name="goggles"';
                        echo "value='" . $player->goggles . "' readonly></div>";
                        echo '<div class="form-group"><label for="headgear">' . $lang['headgear'] . '</label><input class="form-control" id="headgear" type="text" name="headgear"';
                        echo "value='" . $player->headgear . "' readonly></div>";
                        echo '<div class="form-group"><label for="binocular">' . $lang['binocular'] . '</label><input class="form-control" id="binocular" type="text" name="binocular"';
                        echo "value='" . $player->binocular . "' readonly></div>";

                        ?>
                    </div>
                    <div class="tab-pane fade well" id="uniform_inv">
                        <?php
                        echo '<div class="form-group"><label for="uniform">' . $lang['uniform'] . '</label><input class="form-control" id="uniform" type="text" name="uniform"';
                        echo "value='" . $player->uniform . "' readonly></div>";
                        echo '<div class="form-group"><label for="uniformItems">' . $lang['uniform'] . ' ' . $lang['items'] . '</label><input class="form-control" id="uniformItems" type="text" name="uniformItems"';
                        echo "value='" . $player->uniform_items . "' readonly></div>";
                        echo '<div class="form-group"><label for="uniformMagazines">' . $lang['uniform'] . ' ' . $lang['magazines'] . '</label><input class="form-control" id="uniformMagazines" type="text" name="uniformMagazines"';
                        echo "value='" . $player->uniform_magazines . "' readonly></div>";
                        echo '<div class="form-group"><label for="uniformWeapons">' . $lang['primary'] . ' ' . $lang['weapon'] . '</label><input class="form-control" id="uniformWeapons" type="text" name="uniformWeapons"';
                        echo "value='" . $player->uniform_weapons . "' readonly></div>";

                        echo '<div class="form-group"><label for="vest" readonly>' . $lang['uniform'] . '</label><input class="form-control" id="uniform" type="text" name="uniform"';
                        echo "value='" . $player->vest . "' readonly></div>";
                        echo '<div class="form-group"><label for="vestItems">' . $lang['uniform'] . ' ' . $lang['items'] . '</label><input class="form-control" id="vestItems" type="text" name="vestItems"';
                        echo "value='" . $player->vest_items . "' readonly></div>";
                        echo '<div class="form-group"><label for="vestMagazines">' . $lang['uniform'] . ' ' . $lang['magazines'] . '</label><input class="form-control" id="vestMagazines" type="text" name="vestMagazines"';
                        echo "value='" . $player->vest_magazines . "' readonly></div>";
                        echo '<div class="form-group"><label for="vestWeapons">' . $lang['primary'] . ' ' . $lang['weapon'] . '</label><input class="form-control" id="vestWeapons" type="text" name="vestWeapons"';
                        echo "value='" . $player->vest_weapons . "' readonly></div>";
                        ?>
                    </div>
                    <div class="tab-pane fade well" id="backpack_inv">
                        <?php
                        echo '<div class="form-group"><label for="backpack">' . $lang['uniform'] . '</label><input class="form-control" id="uniform" type="text" name="uniform"';
                        echo "value='" . $player->backpack . "' readonly></div>";
                        echo '<div class="form-group"><label for="backpackTtems">' . $lang['uniform'] . ' ' . $lang['items'] . '</label><input class="form-control" id="backpackTtems" type="text" name="backpackTtems"';
                        echo "value='" . $player->backpack_items . "' readonly></div>";
                        echo '<div class="form-group"><label for="backpackMagazines">' . $lang['uniform'] . ' ' . $lang['magazines'] . '</label><input class="form-control" id="backpackMagazines" type="text" name="backpackMagazines"';
                        echo "value='" . $player->backpack_magazines . "' readonly></div>";
                        echo '<div class="form-group"><label for="backpackWeapons">' . $lang['primary'] . ' ' . $lang['weapon'] . '</label><input class="form-control" id="backpackWeapons" type="text" name="backpackWeapons"';
                        echo "value='" . $player->backpack_weapons . "' readonly></div>";
                    echo '</div>';
                } if ($_SESSION['permissions']['edit']['player']) { ?>
                    <div class="tab-pane well fade" id="location">
                        <?php
                        echo '<h5>' . $lang['spawnedAt'] . ': ' . $player->spawned_at . '</h5>';
                        echo '<h5>' . $lang['position'] . ' X: ' . $player->position_x . '</h5>';
                        echo '<h5>' . $lang['position'] . ' Y: ' . $player->position_y . '</h5>';
                        echo '<h5>' . $lang['position'] . ' Z: ' . $player->position_z . '</h5>';
                        echo '<h5>' . $lang['direction'] . ': ' . $player->direction . '</h5>';
                        ?>
                    </div>
                <?php } ?>
                    <div class="tab-pane well fade" id="health">
                        <?php echo $lang['oxygen'] . ' ' . $lang['remaining']; ?>
                        <div class="progress">
                          <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="<?php echo $player->oxygen_remaining; ?>" aria-valuemin="0" aria-valuemax="1" style="width: <?php echo $player->oxygen_remaining * 100; ?>%;">
                            <?php echo $player->oxygen_remaining * 100; ?>%
                          </div>
                        </div>
                        <?php echo $lang['bleeding'] . ' ' . $lang['remaining']; ?>
                        <div class="progress">
                          <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="<?php echo $player->bleeding_remaining; ?>" aria-valuemin="0" aria-valuemax="1" style="width: <?php echo round($player->bleeding_remaining,2); ?>%;">
                            <?php echo round($player->bleeding_remaining,2); ?>%
                          </div>
                        </div>
                        <?php
                        echo '<h5>' . $lang['hitpoint'] . ' ' . $lang['body'] . ': ' . $player->hitpoint_body . '</h5>';
                        echo '<h5>' . $lang['hitpoint'] . ' ' . $lang['head'] . ': ' . $player->hitpoint_head . '</h5>';
                        echo '<h5>' . $lang['hitpoint'] . ' ' . $lang['hands'] . ': ' . $player->hitpoint_hands . '</h5>';
                        echo '<h5>' . $lang['hitpoint'] . ' ' . $lang['legs'] . ': ' . $player->hitpoint_legs . '</h5>';
                        ?>
                    </div>
                    <div class="tab-pane well fade" id="account">
                        <?php
                        echo '<div class="form-group"><label for="money">' . $lang['money'] . '</label><input class="form-control" id="money" type="text" name="money"';
                        echo "value='" . $player->money . "'></div>";
                        echo '<div class="form-group"><label for="score">' . $lang['uniform'] . ' ' . $lang['items'] . '</label><input class="form-control" id="score" type="text" name="score"';
                        echo "value='" . $player->score . "'></div>";
                        echo '<h5>' . $lang['first_connect_at'] . ': ' . $player->first_connect_at . '</h5>';
                        echo '<h5>' . $lang['last_connect_at'] . ': ' . $player->last_connect_at . '</h5>';
                        echo '<h5>' . $lang['last_disconnect_at'] . ': ' . $player->last_disconnect_at . '</h5>';
                        echo '<h5>' . $lang['total'] . ' ' . $lang['connections'] . ': ' . $player->total_connections . '</h5>';
                        ?>
                    </div>

            </div>
        </div>
    </div>
</div>

<script>
$("#theForm").ajaxForm({url: 'server.php', type: 'post'});
    <?php if (($_SESSION['permissions']['view']['steam']) && $settings['vacTest']) { ?>
    $.ajax({
        url: "https://steamrep.com/api/beta3/reputation/<?php echo  $player->account_uid ?>?json=1&extended=1",
        dataType: 'json',
        success: function(data) {
            if(data['steamrep']['vacban'] == "1") {
                $('#vacBan').html('<h4><span class="label label-danger">VAC BANNED</span></h4>');
            } else {
                $('#vacBan').html('<h4><span class="label label-success">VAC CLEAN</span></h4>');
            }
        }
    });
    <?php } ?>
</script>
        </div>
    </div>
</div>

<?php } else echo "<h1>" . errorMessage(36, $lang) . "</h1>";