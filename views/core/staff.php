<?php
$max = 'LIMIT ' . ($pageNum - 1) * $_SESSION['items'] . ',' . $_SESSION['items'];

if (isset($search)) {
    $sql = "SELECT * FROM `users` WHERE `user_name` LIKE '%" . $search . "%' OR `user_email` LIKE '" . $search . "' OR `user_id` LIKE '" . $search . "' OR `playerid` LIKE '%" . $search . "%';";
    $result_of_query = $db_connection->query($sql);
    $total_records = mysqli_num_rows($result_of_query);
    if ($pageNum > $total_records) $pageNum = $total_records;
    $sql = "SELECT * FROM `users` WHERE `user_name` LIKE '%" . $search . "%' OR `user_email` LIKE '" . $search . "' OR `user_id` LIKE '" . $search . "' OR `playerid` LIKE '%" . $search . "%'" . $max . " ;";
    logAction($_SESSION['user_name'], $lang['searched'] . ' (' . $search . ') ' . $lang['in'] . ' ' . $lang['users'], 1);
} else {
    $sql = "SELECT `user_name` FROM `users`;";
    $result_of_query = $db_connection->query($sql);
    $total_records = mysqli_num_rows($result_of_query);
    if ($pageNum > $total_records) $pageNum = $total_records;
    $sql = "SELECT * FROM `users` ORDER BY `user_level` DESC " . $max . " ;";
}
$result_of_query = $db_connection->query($sql);
if ($result_of_query->num_rows > 0) {
    ?>
    <h1 class="page-header">
        <?php echo $lang['staff']; ?>
        <small><?php echo $lang['overview']; ?></small>
    </h1>
    <div class="content-panel">
        <table class="table table-striped table-advance table-hover">
            <h4>
                <i class="fa fa-user"></i>
                <?php echo $lang['staff'];
                include("views/templates/search.php"); ?>
            </h4>
            <hr class="hidden-xs">
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
                if ($_SESSION['permissions']['edit']['staff'] && $_SESSION['user_level'] >= $row["user_level"]) {
                    echo "<td><a class='btn btn-primary btn-xs' href='" . $settings['url'] . "editStaff/" . $row["user_id"] . "'>";
                    echo "<i class='fa fa-pencil'></i></a></td>";
                } else {
					echo "<td></td>";
				}
                echo "</tr>";
            };
            echo "</tbody></table>";
            include("views/templates/page.php");
            ?>
    </div>
    <?php
} else echo '<h3>' . errorMessage(36, $lang) . '</h3>';