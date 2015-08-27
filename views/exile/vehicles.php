<?php
$db_link = serverConnect();
require_once("config/carNames.php");

$max = 'LIMIT ' . ($pageNum - 1) * $_SESSION['items'] . ',' . $_SESSION['items'];

if (isset($search)) {
    logAction($_SESSION['user_name'], $lang['searched'] . ' (' . $search . ') ' . $lang['in'] . ' ' . $lang['vehicles'], 1);

    $sql = "SELECT `id` FROM `vehicles` WHERE `account_uid` LIKE '" . $search . "' OR `classname` LIKE '%" . $search . "%'";
    $result_of_query = $db_link->query($sql);
    $total_records = mysqli_num_rows($result_of_query);
    if ($pageNum > $total_records) $pageNum = $total_records;
    $sql = "SELECT * FROM `vehicles` INNER JOIN `account` ON vehicles.account_uid=account.uid WHERE `account_uid` LIKE '" . $search . "' OR `classname` LIKE '%" . $search . "%' " . $max . " ;";
} else {
    $sql = "SELECT `id` FROM `vehicle`;";
    $result_of_query = $db_link->query($sql);
    $total_records = mysqli_num_rows($result_of_query);
    if ($pageNum > $total_records) $pageNum = $total_records;
    $sql = "SELECT * FROM `vehicle` INNER JOIN `account` ON vehicle.account_uid=account.uid " . $max . " ;";
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
                <th class="hidden-xs"><i class="fa fa-car"></i> <?php echo $lang['fuel']; ?></th>
                <th class="hidden-xs"><i class="fa fa-car"></i> <?php echo $lang['damage']; ?></th>
                <th class="hidden-xs"><i class="fa fa-lock"></i> <?php echo $lang['locked']; ?></th>
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
                echo "<td>" . carName($row["class"]) . "</td>";
                echo "<td class='hidden-xs'> ";
                $width = $row['fuel'] * 100;
            ?>
                <div class="progress">
                    <div class="progress-bar progress-bar-success" role="progressbar"  aria-valuemin="0" aria-valuemax="100" aria-valuenow="<?php echo $width; ?>" style="width:<?php echo $width; ?>%">
                        <?php if ($width > 0) echo $width . '%'; else echo $lang['noFuel']; ?>
                    </div>
                </div>
            <?php
                echo "</td>";
                echo "<td class='hidden-xs'> ";
                $damage = $row['damage'] * 100;
            ?>
                <div class="progress">
                    <div class="progress-bar progress-bar-danger" role="progressbar"  aria-valuemin="0" aria-valuemax="100" aria-valuenow="<?php echo $damage; ?>" style="width:<?php echo $damage; ?>%">
                        <?php if ($width > 0) echo $width . '%'; else echo $lang['broken']; ?>
                    </div>
                </div>
            <?php
                echo "</td>";
                if ($row["is_locked"] == '-1') {
                    echo "<td class='hidden-xs'> <span class='label label-danger '> LOCKED </span> </td>";
                } else {
                    echo "<td class='hidden-xs'> <span class='label label-success '> UNLOCKED </span> </td>";
                }
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
} else echo errorMessage(37, $lang);