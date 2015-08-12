<?php
$db_link = serverConnect();
$max = 'LIMIT ' . ($pageNum - 1) * $_SESSION['items'] . ',' . $_SESSION['items'];

if (isset($search)) {
    logAction($_SESSION['user_name'], $lang['searched'] . ' (' . $search . ') ' . $lang['in'] . ' ' . $lang['vehicles'], 1);
    $sql = "SELECT `id` FROM `houses` INNER JOIN `players` ON houses.pid=players.playerid WHERE `id` LIKE '" . $search . "' OR `pos` LIKE '" . $search . "' OR `inventory` LIKE '%" . $search . "%' OR `name` LIKE '%" . $search . "%' AND `pid` = '" . $_SESSION['playerid'] . "';";
    $result_of_query = $db_link->query($sql);
    $total_records = mysqli_num_rows($result_of_query);
    if ($pageNum > $total_records) $pageNum = $total_records;
    $sql = "SELECT `id`,`pid`,`pos`,`name`,`owned` FROM `houses` INNER JOIN `players` ON houses.pid=players.playerid WHERE `id` LIKE '" . $search . "' OR `pos` LIKE '" . $search . "' OR `inventory` LIKE '%" . $search . "%' OR `name` LIKE '%" . $search . "%' AND `pid` = '" . $_SESSION['playerid'] . "' " . $max . " ;";
} else {
    $sql = "SELECT `id` FROM `houses`;";
    $result_of_query = $db_link->query($sql);
    $total_records = mysqli_num_rows($result_of_query);
    if ($pageNum > $total_records) $pageNum = $total_records;
    $sql = "SELECT `id`,`pid`,`pos`,`name`,`owned` FROM `houses` INNER JOIN `players` ON houses.pid=players.playerid AND `pid` = '" . $_SESSION['playerid'] . "' " . $max . " ;";
}

$result_of_query = $db_link->query($sql);
if ($result_of_query->num_rows > 0) {  ?>
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                <?php echo $lang['houses']; ?>
            </h1>
        </div>
    </div>

    <div class="content-panel">
        <h4>
            <i class="fa fa-home fa-fw"></i>
            <?php echo $lang['houses'];
            include("views/templates/search.php"); ?>
        </h4>
        <hr class="hidden-xs">
        <table class="table table-striped table-advance table-hover">
            <thead>
            <tr>
                <th><i class="fa fa-eye"></i> <?php echo $lang['owner'] ?></th>
                <th><i class="fa fa-user"></i> <?php echo $lang['position']; ?></th>
                <th class="hidden-xs"><i class="fa fa-user"></i> <?php echo $lang['owned']; ?></th>
                <?php if ($_SESSION['permissions']['edit']['houses']) {
    echo '<th>' . $lang['edit'] . '</th>';
}
?>
            </tr>
            </thead>
            <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result_of_query)) {
                echo "<tr>";
                echo "<td>" . $row["name"] . "</td>";
                echo "<td>" . substr($row["pos"], 1, -1) . "</td>";
                echo "<td class='hidden-xs'>" . yesNo($row["owned"], $lang) . "</td>";
                if ($_SESSION['permissions']['edit']['houses']) {
                    echo "<td><a class='btn btn-primary btn-xs' href='" . $settings['url'] . "editHouse/" . $row["id"] . "'>";
                    echo "<i class='fa fa-pencil'></i></a></td>";
                }
                echo "</tr>";
            };
            echo "</tbody></table>";
            include("views/templates/page.php");
            ?>
    </div>

<?php
} else echo errorMessage(3, $lang);