<?php
$db_link = serverConnect();

$max = 'LIMIT ' . ($pageNum - 1) * $_SESSION['items'] . ',' . $_SESSION['items'];

if (isset($search)) {
    $sql = "SELECT `uid` FROM `account` WHERE `uid` LIKE '" . $search . "' OR `name` LIKE '%" . $search . "%';";
    $result_of_query = $db_link->query($sql);
    $total_records = mysqli_num_rows($result_of_query);
    if ($pageNum > $total_records) $pageNum = $total_records;
    $sql = "SELECT * FROM `account` WHERE `uid` LIKE '" . $search . "' OR `name` LIKE '%" . $search . "%' " . $max . " ;";
    logAction($_SESSION['user_name'], $lang['searched'] . ' (' . $search . ') ' . $lang['in'] . ' ' . $lang['players'], 1);
} else {
    $sql = "SELECT `uid` FROM `account`;";
    $result_of_query = $db_link->query($sql);
    $total_records = mysqli_num_rows($result_of_query);
    if ($pageNum > $total_records) $pageNum = $total_records;
    $sql = "SELECT * FROM `account` " . $max . " ;";
}

$result_of_query = $db_link->query($sql);
if ($result_of_query->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result_of_query)) {
            $pids[] = $row['uid'];
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
                    <th><i class="fa fa-eye"></i> <?php echo $lang['uid']; ?></th>
                    <th class="hidden-xs"><i class="fa fa-money"></i> <?php echo $lang['money']; ?></th>
                    <th class="hidden-xs"><i class="fa fa-line-chart"></i> <?php echo $lang['score']; ?></th>
                    <th class="hidden-xs"><i class="fa fa-eye"></i> <?php echo $lang['deaths']; ?></th>
                    <th class="hidden-xs"><i class="fa fa-user-times"></i> <?php echo $lang['kills']; ?></th>
                    <th class="hidden-xs"><i class="fa fa-clock-o"></i> <?php echo $lang['first_connect_at']; ?></th>
                    <th class="hidden-xs"><i class="fa fa-clock-o"></i> <?php echo $lang['last_connect_at']; ?></th>
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
                    echo '<tr>';
                    echo '<td>' . $row["name"] . '</td>';
                    echo '<td>' . $row["uid"] . '</td>';
                    echo '<td class="hidden-xs">' . $row['money'] . '</td>';
                    echo '<td class="hidden-xs">' . $row['score'] . '</td>';
                    echo '<td class="hidden-xs">' . $row['deaths'] . '</td>';
                    echo '<td class="hidden-xs">' . $row['kills'] . '</td>';
                    echo '<td class="hidden-xs">' . $row['first_connect_at'] . '</td>';
                    echo '<td class="hidden-xs">' . $row['last_connect_at'] . '</td>';
                        echo '<td><a class="btn btn-primary btn-xs" href="' . $settings['url'] . 'editPlayer/' . str_replace(' ','-',$row['name']) . '">';
                        echo '<i class="fa ';
                        if ($_SESSION['permissions']['edit']['player']) {
                            echo 'fa-pencil';
                        } else {
                            echo 'fa-eye';
                        }
                        echo '"></i></a></td>';
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