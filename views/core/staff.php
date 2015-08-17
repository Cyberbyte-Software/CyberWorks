<?php
var_dump($_SESSION);
$max = ' LIMIT ' . ($pageNum - 1) * $_SESSION['items'] . ',' . $_SESSION['items'];

if (isset($search)) {
    logAction($_SESSION['user_name'], $lang['searched'] . ' (' . $search . ') ' . $lang['in'] . ' ' . $lang['staff'], 3);

    $sql = "SELECT * FROM `users` WHERE `user_name` LIKE '" . $search . "' OR `user_email` LIKE '" . $search . "' OR `user_id` LIKE '" . $search . "' OR `playerid` LIKE '" . $search . "';";
    $result_of_query = $db_connection->query($sql);
    $total_records = mysqli_num_rows($result_of_query);
    $sql = "SELECT * FROM `users` WHERE `user_name` LIKE '" . $search . "' OR `user_email` LIKE '" . $search . "' OR `user_id` LIKE '" . $search . "' OR `playerid` LIKE '" . $search . "'" . $max . " ;";
} else {
    $sql = "SELECT count(`user_id`) FROM `users`;";
    $total_records = $db_connection->query($sql);
    $sql = "SELECT * FROM `users` " . $max . " ;";
}
?>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            <?php echo $lang['staff']; ?>
            <small><?php echo " " . $lang['overview']; ?></small>
        </h1>
    </div>
    <div class="col-md-12">
        <div class="content-panel">
            <h4>
                <i class="fa fa-cogs fa-fw"></i>
                <?php echo $lang['staff'];
                include("views/templates/search.php"); ?>
            </h4>
            <hr class='hidden-xs'>
            <table class="table table-striped table-advance table-hover">
                <thead>
                <tr>
                    <th><i class="fa fa-user"></i> <?php echo $lang['staffName']; ?></th>
                    <th class='hidden-xs'><i class="fa fa-user"></i> <?php echo $lang['emailAdd']; ?></th>
                    <th><i class="fa fa-user"></i> <?php echo $lang['rank']; ?></th>
                    <th class='hidden-xs'><i class="fa fa-eye"></i> <?php echo $lang['playerID']; ?></th>
                    <?php if ($_SESSION['permissions']['edit']['staff']) {
    echo '<th><i class="fa fa-pencil"></i> ' . $lang['edit'] . '</th>';
}
?>
                </tr>
                </thead>
                <tbody>
                <?php
                $result_of_query = $db_connection->query($sql);
                while ($row = mysqli_fetch_assoc($result_of_query)) {
                    $userID = $row["user_id"];
                    echo "<tr";
                    if ($row["user_level"] == 0) {
                        echo ' class="danger"';
                    }
                    echo ">";
                    echo "<td>" . $row["user_name"] . "</td>";
                    echo "<td class='hidden-xs'>" . $row["user_email"] . "</td>";
                    echo "<td>" . $settings['ranks'][$row["user_level"]];
                    if ($row["user_level"] != 0) {
                        echo " (" . $row["user_level"] . ")";
                    }
                    echo "</td><td class='hidden-xs'>" . $row["playerid"] . "</td>";
                    if ($_SESSION['permissions']['edit']['staff']) {
                        echo "<td><a class='btn btn-primary btn-xs' href='" . $settings['url'] . "editStaff/" . $row["user_id"] . "'>";
                        echo "<i class='fa fa-pencil'></i></a></td>";
                    }
                    echo "</tr>";
                };
                echo "</tbody></table>";

                include("views/templates/page.php");
                ?>
        </div>
    </div>