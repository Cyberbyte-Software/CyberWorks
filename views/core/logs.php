<?php
$db_link = serverConnect();

$max = 'LIMIT ' . ($pageNum - 1) * $_SESSION['items'] . ',' . $_SESSION['items'];

if (isset($search)) {
    $sql = "SELECT count(`logid`) FROM `logs` WHERE `logid` LIKE '" . $search . "' OR `user` LIKE '%" . $search . "%' OR `action` LIKE '%" . $search . "%' OR `level` LIKE '" . $search . "';";
    $total_records = $db_link->query($sql);
    if ($pageNum > $total_records) $pageNum = $total_records;
    $sql = "SELECT `logid`,`date_time`,`user`,`action`,`level` FROM `logs` WHERE `logid` LIKE '" . $search . "' OR `user` LIKE '%" . $search . "%' OR `action` LIKE '%" . $search . "%' OR `level` LIKE '" . $search . "' ORDER BY `logid` DESC " . $max . " ;";
    logAction($_SESSION['user_name'], $lang['searched'].' (' . $search . ') '.$lang['in'].' '.$lang['logs'], 2);
} else {
    $sql = "SELECT `logid` FROM `logs`;";
    $result_of_query = $db_link->query($sql);
    $total_records = mysqli_num_rows($result_of_query);
    if ($pageNum > $total_records) $pageNum = $total_records;
    $sql = "SELECT `logid`,`date_time`,`user`,`action`,`level` FROM `logs` ORDER BY `logid` DESC " . $max . " ;";
}
$result_of_query = $db_connection->query($sql);
if ($result_of_query->num_rows > 0) {
    ?>
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                <?php echo $lang['logs']; ?>
                <small><?php echo " " . $lang['overview']; ?></small>
            </h1>
        </div>
    </div>
    <!-- /.row -->

        <div class="content-panel">
            <h4>
                <i class="fa fa-edit"></i>
                <?php echo " " . $lang['logs'];
                include("views/templates/search.php"); ?>
            </h4>
            <hr class="hidden-xs">
            <table class="table table-striped table-advance table-hover">
                <thead>
                <tr>
                    <th class="hidden-xs"><i
                            class="fa fa-pencil-square-o"></i> <?php echo $lang['log'] . ' ' . $lang['id'] ?>
                    </th>
                    <th class="hidden-xs"><i class="fa fa-calendar"></i>  <?php echo $lang['time']; ?></th>
                    <th class="hidden-xs"><i class="fa fa-user"></i> <?php echo $lang['user']; ?></th>
                    <th><i class="fa fa-car"></i> <?php echo $lang['action']; ?></th>
                    <th class="hidden-xs"><i class="fa fa-signal"></i> <?php echo $lang['level']; ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result_of_query)) {
                    echo "<tr>";
                    echo "<td class='hidden-xs'>" . $row["logid"] . "</td>";
                    echo "<td class='hidden-xs'>" . $row["date_time"] . "</td>";
                    echo "<td class='hidden-xs'>" . $row["user"] . "</td>";
                    echo "<td>" . $row["action"] . "</td>";
                    echo "<td class='hidden-xs'>" . $row["level"] . "</td>";
                    echo "</tr>";
                };
                echo "</tbody></table>";

                include("views/templates/page.php");
                ?>
                <br>
        </div>
<?php
} else echo errorMessage(3,$lang);