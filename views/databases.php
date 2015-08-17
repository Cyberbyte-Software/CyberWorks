<?php
if (isset($_GET["page"])) {
    $page = $_GET["page"];
} else {
    $page = 1;
}

$max = ' LIMIT ' . ($page - 1) * $_SESSION['items'] . ',' . $_SESSION['items'];

if (isset($search)) {
    logAction($_SESSION['user_name'], $lang['searched'] . ' (' . $search . ') ' . $lang['in'] . ' ' . $lang['database'], 1);
    $sql = "SELECT * FROM `db` WHERE `dbid` LIKE '" . $search . "' OR `type` LIKE '%" . $search . "%';"; //todo: name searching
    $result_of_query = $db_connection->query($sql);
    $total_records = mysqli_num_rows($result_of_query);
    $sql = "SELECT * FROM `db` WHERE `dbid` LIKE '" . $search . "' OR `type` LIKE '%" . $search . "%'" . $max . " ;";
} else {
    $sql = "SELECT * FROM `db` " . $max . " ;";
}

$result_of_query = $db_connection->query($sql);
if ($result_of_query->num_rows > 0) {
    ?>


    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                <?php echo $lang['wantList']; ?>
            </h1>
        </div>
    </div>

        <div class="content-panel">
            <table class="table table-striped table-advance table-hover">
                <h4>
                    <i class="fa fa-sitemap"></i>
                    <?php echo " " . $lang['wantList']; ?>

                </h4>
                <hr class="hidden-xs">
                <thead>
                <tr>
                    <th class="hidden-xs"><i class="fa fa-eye"></i> <?php echo $lang['id']; ?></th>
                    <th><i class="fa fa-user"></i> <?php echo $lang['name']; ?></th>
                    <th><i class="fa fa-user"></i> <?php echo $lang['crimes']; ?></th>
                    <th class="hidden-xs"><i class="fa fa-user"></i> <?php echo $lang['bounty']; ?></th>
                    <th class="hidden-xs"><i class="fa fa-user"></i> <?php echo $lang['active']; ?></th>
                    <th> <?php echo $lang['edit'] ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result_of_query)) {
                    echo "<tr>";
                    echo "<td class='hidden-xs'>" . $row["wantedID"] . "</td>";
                    echo "<td>" . $row["wantedName"] . "</td>";
                    echo "<td class='hidden-xs'>" . $row["wantedBounty"] . "</td>";
                    echo "<td class='hidden-xs'>" . yesNo($row["active"], $lang) . "</td>";
                    echo "<td><a class='btn btn-primary btn-xs' href='editWanted/" . $row["wantedID"] . "'>";
                    echo "<i class='fa fa-pencil'></i></a></td>";
                    echo "</tr>";
                };
                echo "</tbody></table>";
                ?>
                </tbody>
                <br>
            </table>
        </div>
    </div>
<?php
} else  echo errorMessage(3, $lang);