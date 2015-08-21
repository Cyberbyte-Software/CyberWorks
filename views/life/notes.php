<?php
$db_link = serverConnect();

require_once("config/carNames.php");

if (isset($_GET["page"])) {
    $page = $_GET["page"];
} else {
    $page = 1;
}

$max = 'LIMIT ' . ($page - 1) * $_SESSION['items'] . ',' . $_SESSION['items'];

if (isset($search)) {
    logAction($_SESSION['user_name'], $lang['searched'] . ' (' . $search . ') ' . $lang['in'] . ' ' . $lang['vehicles'], 1);
    $sql = "SELECT `playerid` FROM `players` WHERE `name` LIKE '%" . $search . "%' ";
    $result_of_query = $db_link->query($sql);
    if ($result_of_query->num_rows > 0) {
        while ($row = mysqli_fetch_row($result_of_query)) {
            $samepID[] = $row;
        }
        $samepID = array_reduce($samepID, 'array_merge', array());
        $sql = "SELECT * FROM `vehicles` WHERE `pid` LIKE '" . $search . "' OR `classname` LIKE '%" . $search . "%' OR `pid` IN (" . implode(',', $samepID) . ") OR `plate` LIKE '" . $search . "' OR `inventory` LIKE '%" . $search . "%';";
        $result_of_query = $db_link->query($sql);
        $total_records = mysqli_num_rows($result_of_query);
        $sql = "SELECT * FROM `vehicles` WHERE `pid` LIKE '" . $search . "' OR `classname` LIKE '%" . $search . "%' OR `pid` IN (" . implode(',', $samepID) . ") OR `plate` LIKE '" . $search . "' OR `inventory` LIKE '%" . $search . "%'" . $max . " ;";
    } else {
        $sql = "SELECT * FROM `vehicles` WHERE `pid` LIKE '" . $search . "' OR `classname` LIKE '%" . $search . "%' OR `plate` LIKE '" . $search . "' OR `inventory` LIKE '%" . $search . "%';";
        $result_of_query = $db_link->query($sql);
        $total_records = mysqli_num_rows($result_of_query);
        $sql = "SELECT * FROM `vehicles` WHERE `pid` LIKE '" . $search . "' OR `classname` LIKE '%" . $search . "%' OR `plate` LIKE '" . $search . "' OR `inventory` LIKE '%" . $search . "%'" . $max . " ;";
    }
} else {
        $sql = "SELECT * FROM `vehicles` " . $max . " ;";
}
$result_of_query = $db_link->query($sql);
?>
<!-- Page Heading -->
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            <?php echo $lang['vehicles']; ?>
            <small><?php echo " " . $lang['overview']; ?></small>
        </h1>
    </div>
</div>
<!-- /.row -->

<div class="col-md-12">
    <div class="content-panel">
        <h4>
            <i class="fa fa-car"></i>
            <?php echo " " . $lang['vehicles']; ?>
            <div class="col-md-2 pull-right">
                <form style="float:right;" name='rows' method="post" action="<?php echo $settings['url'] ?>vehicles">
                    <select id='items' name='items'>
                        <?php echo $_SESSION['items']; ?>
                        <option value="5" <?php if ('5' == $_SESSION['items']) {
    echo 'selected';
}
?>>5</option>
                        <option value="10" <?php if ('10' == $_SESSION['items']) {
    echo 'selected';
}
?>>10</option>
                        <option value="15" <?php if ('15' == $_SESSION['items']) {
    echo 'selected';
}
?>>15</option>
                        <option value="25" <?php if ('25' == $_SESSION['items']) {
    echo 'selected';
}
?>>25</option>
                        <option value="50" <?php if ('50' == $_SESSION['items']) {
    echo 'selected';
}
?>>50</option>
                    </select>
                    <input class='btn btn-sm btn-primary' name='update' type="submit"
                           value="<?php echo $lang['itemsPP'] ?>">
                    <script type='text/javascript'>
                        function searchpage() {
                            sn = document.getElementById('searchText').value;
                            redirecturl = '<?php echo $settings['url'] ?>vehicles/' + sn;
                            document.location.href = redirecturl;
                        }
                    </script>
                </form>
            </div>
            <div class="col-md-3 pull-right">
                <form style="float:right;" name='search'>
                    <input id='searchText' type='text' name='searchText' placeholder="<?php if (isset($search)) echo $search ?>">
                    <input class='btn btn-sm btn-primary' type='button' name='search'
                           onclick='searchpage();' value='<?php echo $lang['search'] ?>'>
                </form>
            </div>
        </h4>
        <hr class="hidden-xs">
        <table class="table table-striped table-advance table-hover">
            <thead>
            <tr>
                <th><i class="fa fa-eye"></i> <?php echo $lang['owner']; ?></th>
                <th><i class="fa fa-car"></i> <?php echo $lang['class']; ?></th>
                <th class="hidden-xs"><i class="fa fa-car"></i> <?php echo $lang['type']; ?></th>
                <th class="hidden-xs"><i class="fa fa-car"></i> <?php echo $lang['plate']; ?></th>
                <th class="hidden-xs"><i class="fa fa-car"></i> <?php echo $lang['alive']; ?></th>
                <th class="hidden-xs"><i class="fa fa-info"></i> <?php echo $lang['active']; ?></th>
                <?php if ($_SESSION['user_level'] >= $_SESSION['permission']['edit']['playet']) {
    echo '<th><i class="fa fa-pencil"></i><div class="hidden-xs"> ' . $lang['edit'] . '</div></th>';
}
?>
            </tr>
            </thead>
            <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result_of_query)) {
                echo "<tr>";
                echo "<td>" . nameID($row["pid"]) . "</td>";
                echo "<td>" . carName($row["classname"]) . "</td>";
                echo "<td class='hidden-xs'>" . carType($row["type"], $lang) . "</td>";
                echo "<td class='hidden-xs'>" . $row["plate"] . "</td>";
                echo "<td class='hidden-xs'>" . yesNo($row["alive"], $lang) . "</td>";
                echo "<td class='hidden-xs'>" . yesNo($row["active"], $lang) . "</td>";
                if ($_SESSION['user_level'] >= P_EDIT_VEHICLES) {
                    echo "<td><a class='btn btn-primary btn-xs' href='" . $settings['url'] . "editVeh/" . $row["id"] . "'>";
                    echo "<i class='fa fa-pencil'></i></a></td>";
                }
                echo "</tr>";
            }

            echo "</tbody></table>";

            if (isset($search)) {
                $total_pages = ceil($total_records / $_SESSION['items']);
                if ($total_pages > 1) {

                    echo "<center><a class='btn btn-primary' href='" . $settings['url'] . "vehicles/" . $search . "?page=1'>" . $lang['first'] . "</a> ";
                    ?>
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                            <?php echo $lang['page'] . " " ?><span class="caret"></span>
                        </button>

                        <ul class="dropdown-menu scrollable-menu" role="menu">
                            <?php
                            for ($i = 1; $i <= $total_pages; $i++) {
                                ?>
                                <li><?php echo "<a href='" . $settings['url'] . "vehicles/" . $search . "?page=" . $i . "'>" . $i . "</a> "; ?></li>
                            <?php }; ?>
                        </ul>
                    </div>

                    <?php
                    echo "<a class='btn btn-primary' href='" . $settings['url'] . "vehicles/" . $search . "?page=" . $total_pages . "'>" . $lang['last'] . "</a></center>";
                }
            } else {
                $sql = "SELECT `id` FROM `vehicles`;";
                $result_of_query = $db_link->query($sql);
                $total_records = mysqli_num_rows($result_of_query);
                $total_pages = ceil($total_records / $_SESSION['items']);
                if ($total_pages > 1) {
                    echo "<center><a class='btn btn-primary' href='" . $settings['url'] . "vehicles?page=1'>" . $lang['first'] . "</a> ";
                    ?>
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                            <?php echo $lang['page'] . " " ?><span class="caret"></span>
                        </button>

                        <ul class="dropdown-menu scrollable-menu" role="menu">
                            <?php
                            for ($i = 1; $i <= $total_pages; $i++) {
                                ?>
                                <li><?php echo "<a href='" . $settings['url'] . "vehicles?page=" . $i . "'>" . $i . "</a> "; ?></li>
                            <?php }; ?>
                        </ul>
                    </div>

                    <?php
                    echo "<a class='btn btn-primary' href='" . $settings['url'] . "vehicles?page=$total_pages'>" . $lang['last'] . "</a></center>";
                }
            }
            ?>
            <br>
            </tbody>
        </table>
    </div>
</div>
