<?php
$db_link = serverConnect();

$max = 'LIMIT ' . ($pageNum - 1) * $_SESSION['items'] . ',' . $_SESSION['items'];

if (isset($search)) {
    $sql = "SELECT `uid` FROM `players` WHERE `uid` LIKE '" . $search . "' OR `name` LIKE '" . $search . "' OR `playerid` LIKE '" . $search . "';";
    $result_of_query = $db_link->query($sql);
    $total_records = mysqli_num_rows($result_of_query);
    if ($pageNum > $total_records) $pageNum = $total_records;
    $sql = "SELECT `playerid`,`name`,`bankacc`,`cash`,`coplevel`,`mediclevel`,`adminlevel`,`uid` FROM `players` WHERE `uid` LIKE '" . $search . "' OR `name` LIKE '" . $search . "' OR `playerid` LIKE '" . $search . "'" . $max . " ;";
    logAction($_SESSION['user_name'], $lang['searched'] . ' (' . $search . ') ' . $lang['in'] . ' ' . $lang['players'], 1);
} else {
    $sql = "SELECT `uid` FROM `players`;";
    $result_of_query = $db_link->query($sql);
    $total_records = mysqli_num_rows($result_of_query);
    if ($pageNum > $total_records) $pageNum = $total_records;
    $sql = "SELECT `playerid`,`name`,`bankacc`,`cash`,`coplevel`,`mediclevel`,`adminlevel`,`uid` FROM `players` " . $max . " ;";
}

$result_of_query = $db_link->query($sql);
if ($result_of_query->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result_of_query)) {
            $pids[] = $row['playerid'];
        }
        $pids = implode(',', $pids);
    if ($settings['steamAPI'] && $_SESSION['permissions']['view']['steam'] && !$settings['performance'] && $settings['vacTest']) {
        $api = "http://api.steampowered.com/ISteamUser/GetPlayerBans/v1/?key=" . $settings['steamAPI'] . "&steamids=" . $pids;
        $bans = get_object_vars(json_decode(file_get_contents($api)));
        $bans = $bans['players'];
        $steamPlayers = count($bans);
    } else {
        $steamPlayers = 0;
    }

    $result_of_query = $db_link->query($sql);
    ?>
    <h1 class="page-header">
        <?php echo $lang['players']; ?>
        <small><?php echo $lang['overview']; ?></small>
    </h1>
        <div class="content-panel">
            <table class="table table-striped table-advance table-hover">
                <h4>
                    <i class="fa fa-user"></i>
                    <?php echo $lang['players'];
                    include("views/templates/search.php"); ?>
                </h4>
                <hr class="hidden-xs">
                <thead>
                <tr>
                    <th><i class="fa fa-user"></i> <?php echo $lang['name']; ?></th>
                    <th><i class="fa fa-eye"></i> <?php echo $lang['playerID']; ?></th>
                    <th class="hidden-xs"><i class="fa fa-money"></i> <?php echo $lang['cash']; ?></th>
                    <th class="hidden-xs"><i class="fa fa-bank"></i> <?php echo $lang['bank']; ?></th>
                    <th class="hidden-xs"><i class="fa fa-taxi"></i> <?php echo $lang['cop']; ?></th>
                    <th class="hidden-xs"><i class="fa fa-ambulance"></i> <?php echo $lang['medic']; ?></th>
                    <th class="hidden-xs"><i class="fa fa-cogs"></i> <?php echo $lang['admin']; ?></th>
                    <?php if ($_SESSION['permissions']['edit']['player']) {
                            echo '<th class="hidden-xs"><i class="fa fa-pencil"></i> ' . $lang['edit'] . '</th>';
                        } else {
                            echo '<th class="hidden-xs"><i class="fa fa-eye"></i>' . $lang['view'] . '</th>';
                        }
                    if ($_SESSION['permissions']['view']['steam'] && $steamPlayers > 0) {
                        echo '<th class="hidden-xs"><i class="fa fa-fw fa-steam"></i> Steam</th>';
                        } ?>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result_of_query)) {
                    $playersID = $row["playerid"];
                    echo "<tr>";
                    echo "<td>" . $row["name"] . "</td>";
                    echo "<td>" . $playersID . "</td>";
                    echo "<td class='hidden-xs'>" . $row["cash"] . "</td>";
                    echo "<td class='hidden-xs'>" . $row["bankacc"] . "</td>";
                    echo "<td class='hidden-xs'>" . $row["coplevel"] . "</td>";
                    echo "<td class='hidden-xs'>" . $row["mediclevel"] . "</td>";
                    echo "<td class='hidden-xs'>" . $row["adminlevel"] . "</td>";
                    if ($_SESSION['permissions']['edit']['player']) {
                        echo "<td><a class='btn btn-primary btn-xs' href='" . $settings['url'] . "editPlayer/" . $row["uid"] . "'>";
                        echo "<i class='fa fa-pencil'></i></a></td>";
                    } else {
                        echo "<td><a class='btn btn-primary btn-xs' href='" . $settings['url'] . "editPlayer/" . $row["uid"] . "'>";
                        echo "<i class='fa fa-eye'></i></a></td>";
                    }
                    if ($_SESSION['permissions']['view']['steam'] && $steamPlayers > 0) {
                        echo "<td><a href='http://steamcommunity.com/profiles/" . $row["playerid"] . "' ";
                        for ($player = 0; $player <= $steamPlayers; $player++) {
                            if ($bans[$player]->SteamId == $row['playerid']) {
                                if ($bans[$player]->VACBanned == true) {
                                    echo "<td><a href='http://steamcommunity.com/profiles/" . $row["playerid"] . "' ";
                                    echo "class='btn btn-danger btn-xs hidden-xs' target='_blank'><i class='fa fa-steam'></i></a>";

                                } else {
                                    echo "<td><a href='http://steamcommunity.com/profiles/" . $row["playerid"] . "' ";
                                    echo "class='btn btn-primary btn-xs hidden-xs' target='_blank'><i class='fa fa-steam'></i></a>"; }
                            }
                        }
                    echo '</td>';
                    }
                    echo "</tr>";
                }
                echo "</tbody></table>";
                include("views/templates/page.php");
                ?>
        </div>
<?php
} else echo '<h3>' . errorMessage(36, $lang) . '</h3>';