<?php
$db_link = serverConnect();

$max = ' LIMIT ' . ($pageNum - 1) * $_SESSION['items'] . ',' . $_SESSION['items'];

if (isset($search)) {
    logAction($_SESSION['user_name'], $lang['searched'] . ' (' . $search . ') ' . $lang['in'] . ' ' . $lang['gangs'], 1);
    $sql = "SELECT `playerid` FROM `players` WHERE `name` LIKE '%" . $search . "%' ";
    $result_of_query = $db_link->query($sql);
    if ($result_of_query->num_rows > 0) {
        while ($row = mysqli_fetch_row($result_of_query)) {
            $samepID[] = $row;
        }
        $samepID = array_reduce($samepID, 'array_merge', array());
        $samepID = implode(',', $samepID);
        $sql = "SELECT `id`,`name`,`owner`,`bank`,`members`,`maxmembers`,`active` FROM `gangs` WHERE `owner` IN ( " . $samepID . " ) OR `name` LIKE '%" . $search . "%' OR `id` = '" . $search . "' OR `owner` LIKE '" . $search . "' OR `members` LIKE '%" . $search . "%' ;";
        $result_of_query = $db_link->query($sql);
        $total_records = mysqli_num_rows($result_of_query);
        if ($pageNum > $total_records) $pageNum = $total_records;
        $sql = "SELECT `id`,`name`,`owner`,`bank`,`members`,`maxmembers`,`active` FROM `gangs` WHERE `owner` IN ( " . $samepID . " ) OR `name` LIKE '%" . $search . "%' OR `id` = '" . $search . "' OR `owner` LIKE '" . $search . "' OR `members` LIKE '%" . $search . "%' " . $max . " ;";
    } else {
        $sql = "SELECT `id` FROM `gangs` WHERE `name` LIKE '%" . $search . "%'  OR `id` = '" . $search . "' OR `owner` LIKE '" . $search . "' OR `members` LIKE '%" . $search . "%' ;";
        $result_of_query = $db_link->query($sql);
        $total_records = mysqli_num_rows($result_of_query);
        if ($pageNum > $total_records) $pageNum = $total_records;
        $sql = "SELECT `id`,`name`,`owner`,`bank`,`members`,`maxmembers`,`active` FROM `gangs` WHERE `name` LIKE '%" . $search . "%' OR `id` = '" . $search . "' OR `owner` LIKE '" . $search . "' OR `members` LIKE '%" . $search . "%' " . $max . " ;";
    }
} else {
    $sql = "SELECT `id` FROM `gangs`;";
    $result_of_query = $db_link->query($sql);
    $total_records = mysqli_num_rows($result_of_query);
    if ($pageNum > $total_records) $pageNum = $total_records;
    $sql = "SELECT `id`,`name`,`owner`,`bank`,`members`,`maxmembers`,`active` FROM `gangs` " . $max . " ;";
}
$result_of_query = $db_link->query($sql);
if ($result_of_query->num_rows > 0) {
?>

<h1 class="page-header">
    <?php echo $lang['gangs']; ?>
    <small> <?php echo $lang['overview']; ?></small>
</h1>
        
<div class="content-panel">
    <table class="table table-striped table-advance table-hover">
        <h4>
            <i class="fa fa-sitemap fa-fw"></i>
            <?php echo " " . $lang['gangs'];
            include("views/templates/search.php"); ?>
        </h4>
        <hr class='hidden-xs'>
        <thead>
        <tr>
            <th class="hidden-xs"><i class="fa fa-eye"></i> <?php echo $lang['id']; ?></th>
            <th><i class="fa fa-user"></i> <?php echo $lang['gang'] . " " . $lang['name']; ?></th>
            <th class="hidden-xs"><i class="fa fa-user"></i> <?php echo $lang['owner']; ?></th>
            <th class="hidden-xs"><i class="fa fa-bank"></i> <?php echo $lang['bank']; ?></th>
            <?php if ($_SESSION['permissions']['edit']['gangs']) {
                echo '<th class="hidden-xs"><i class="fa fa-user"></i> ' . $lang['members'] . '</th>';
            } else {
                echo '<th><i class="fa fa-user"></i> ' . $lang['members'] . '</th>';
            }?>
            <th class="hidden-xs"><i class="fa fa-user"></i> <?php echo $lang['maxmembers']; ?></th>
            <th class="hidden-xs"><i class="fa fa-user"></i> <?php echo $lang['active']; ?></th>
            <?php if ($_SESSION['permissions']['edit']['gangs']) {
    echo '<th><i class="fa fa-pencil"></i>' . $lang['edit'] . '</th>';
}
?>
        </tr>
        </thead>
        <tbody>
        <?php
        while ($row = mysqli_fetch_assoc($result_of_query)) {
            echo "<tr>";
            echo "<td class='hidden-xs'>" . $row["id"] . "</td>";
            echo "<td>" . $row["name"] . "</td>";
            echo "<td class='hidden-xs'>" . nameID($row["owner"], $db_link) . "</td>";
            echo "<td class='hidden-xs'>" . $row["bank"] . "</td>";
            $members = substr_count($row['members'], ",");
            $members++;
            if ($_SESSION['permissions']['edit']['gangs']) {
                echo "<td class='hidden-xs'>" . $members . "</td>";
            } else {
                echo "<td>" . $members . "</td>";
            }
            echo "<td class='hidden-xs'>" . $row["maxmembers"] . "</td>";
            echo "<td class='hidden-xs'>" . yesNo($row["active"], $lang) . "</td>";
            if ($_SESSION['permissions']['edit']['gangs']) {
                echo "<td><a class='btn btn-primary btn-xs' href='" . $settings['url'] . "editGang/" . $row["id"] . "'>";
                echo "<i class='fa fa-pencil'></i></a></td>";
            }
            echo "</tr>";
        }
        echo "</tbody></table>";
        include("views/templates/page.php");
        ?>
        </tbody>
        <br>
    </table>
</div>

<?php
} else echo errorMessage(3, $lang);