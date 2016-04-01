<?php
$db_link = serverConnect();
require_once("config/carNames.php");

$max = 'LIMIT ' . ($pageNum - 1) * $_SESSION['items'] . ',' . $_SESSION['items'];

if (isset($search)) {
    logAction($_SESSION['user_name'], $lang['searched'] . ' (' . $search . ') ' . $lang['in'] . ' ' . $lang['vehicles'], 1);

    $sql = "SELECT `id` FROM `vehicles` INNER JOIN `players` ON vehicles.pid=players.playerid WHERE `pid` LIKE '" . $search . "' OR `classname` LIKE '%" . $search . "%' OR `name` LIKE '%" . $search . "%' OR `plate` LIKE '" . $search . "' OR `inventory` LIKE '%" . $search . "%' OR `name` LIKE '%" . $search . "%';";
    $result_of_query = $db_link->query($sql);
    $total_records = mysqli_num_rows($result_of_query);
    if ($pageNum > $total_records) $pageNum = $total_records;
    $sql = "SELECT vehicles.id,vehicles.pid,vehicles.classname,vehicles.active,vehicles.type,vehicles.plate,vehicles.alive,vehicles.active,players.name FROM `vehicles` INNER JOIN `players` ON vehicles.pid=players.playerid WHERE `pid` LIKE '" . $search . "' OR `vehicles.classname` LIKE '%" . $search . "%' OR `name` LIKE '%" . $search . "%' OR `vehicles.plate` LIKE '" . $search . "' OR `inventory` LIKE '%" . $search . "%' OR `name` LIKE '%" . $search . "%' " . $max . " ;";
} else {
    $sql = "SELECT `id` FROM `vehicles` INNER JOIN `players` ON vehicles.pid=players.playerid;";
    $result_of_query = $db_link->query($sql);
    $total_records = mysqli_num_rows($result_of_query);
    if ($pageNum > $total_records) $pageNum = $total_records;
    $sql = "SELECT vehicles.id,vehicles.pid,vehicles.classname,vehicles.active,vehicles.type,vehicles.plate,vehicles.alive,vehicles.active,players.name FROM `vehicles` INNER JOIN `players` ON vehicles.pid=players.playerid " . $max . " ;";
}
$result_of_query = $db_link->query($sql);
if ($result_of_query->num_rows > 0) {  ?>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            <?php echo $lang['vehicles']; ?>
            <small> <?php echo $lang['overview']; ?></small>
        </h1>
    </div>
</div>

    <div class="content-panel">
        <h4>
            <i class="fa fa-car"></i>
            <?php echo $lang['vehicles'];
            include("views/templates/search.php"); ?>
        </h4>
        <hr class="hidden-xs">
        <table class="table table-striped table-advance table-hover">
            <thead>
            <tr>
                <th><i class="fa fa-eye"></i> <?php echo $lang['owner'] ?></th>
                <th><i class="fa fa-car"></i> <?php echo $lang['class']; ?></th>
                <th class="hidden-xs"><i class="fa fa-car"></i> <?php echo $lang['type']; ?></th>
                <th class="hidden-xs"><i class="fa fa-car"></i> <?php echo $lang['plate']; ?></th>
                <th class="hidden-xs"><i class="fa fa-car"></i> <?php echo $lang['alive']; ?></th>
                <th class="hidden-xs"><i class="fa fa-info"></i> <?php echo $lang['active']; ?></th>
                <?php if ($_SESSION['permissions']['edit']['vehicles']) {
    echo '<th><i class="fa fa-pencil"></i><span class="hidden-xs"> ' . $lang['edit'] . '</span></th>';
}
?>
            </tr>
            </thead>
            <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result_of_query)) {
                echo "<tr>";
                echo "<td>" . $row["name"] . "</td>";
                echo "<td>" . carName($row["classname"]) . "</td>";
                echo "<td class='hidden-xs'> " . carType($row["type"], $lang) . "</td>";
                echo "<td class='hidden-xs'> " . $row["plate"] . "</td>";
                echo "<td class='hidden-xs'> " . yesNo($row["alive"], $lang) . "</td>";
                echo "<td class='hidden-xs'> " . yesNo($row["active"], $lang) . "</td>";
                if ($_SESSION['permissions']['edit']['vehicles']) {
                    echo "<td><a class='btn btn-primary btn-xs' href='" . $settings['url'] . "editVeh/" . $row["id"] . "'>";
                    echo "<i class='fa fa-pencil'></i></a></td>";
                }
                echo "</tr>";
            }
            echo "</tbody></table>";
        include("views/templates/page.php");
        ?>
        <br>
        </tbody>
    </table>
</div>
<?php
} else echo errorMessage(32, $lang);
