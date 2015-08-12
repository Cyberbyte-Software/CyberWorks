<?php
$db_link = serverConnect();

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
        $sql = "SELECT * FROM `houses` WHERE `id` LIKE '" . $search . "' OR `pos` LIKE '" . $search . "' OR `inventory` LIKE '%" . $search . "%' OR `pid` IN ( " . $samepID . " );";
        $result_of_query = $db_link->query($sql);
        $total_records = mysqli_num_rows($result_of_query);
        $sql = "SELECT * FROM `houses` WHERE `id` LIKE '" . $search . "' OR `pos` LIKE '" . $search . "' OR `inventory` LIKE '%" . $search . "%' OR `pid` IN ( " . $samepID . " ) LIMIT " . ($pageNum - 1) * $_SESSION['items'] . ", " . $_SESSION['items'] . ";";
    } else {
        $sql = "SELECT * FROM `houses` WHERE `id` LIKE '" . $search . "' OR `pos` LIKE '" . $search . "' OR `inventory` LIKE '%" . $search . "%'";
        $result_of_query = $db_link->query($sql);
        $total_records = mysqli_num_rows($result_of_query);
        $sql = "SELECT * FROM `houses` WHERE `id` LIKE '" . $search . "' OR `pos` LIKE '" . $search . "' OR `inventory` LIKE '%" . $search . "%' LIMIT " . ($pageNum - 1) * $_SESSION['items'] . ", " . $_SESSION['items'] . ";";
    }
} else {
    $sql = "SELECT * FROM `houses` LIMIT " . ($pageNum - 1) * $_SESSION['items'] . ", " . $_SESSION['items'] . ";";
}

$result_of_query = $db_link->query($sql);
if ($result_of_query->num_rows > 0) {  ?>
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                <?php echo $lang['houses']; ?>
                <small><?php echo " " . $lang['overview']; ?></small>
            </h1>
        </div>
    </div>

    <div class="content-panel">
        <h4>
            <i class="fa fa-home fa-fw"></i>
            <?php echo " " . $lang['houses']; ?>
            <div class="col-md-2 pull-right">
                <form style="float:right;" name='rows' method="post" action="<?php echo $settings['url'] ?>houses">
                    <select id='items' name='items'>
                        <option value="5" <?php echo select('5', $_SESSION['items']) ?> >5</option>
                        <option value="10" <?php echo select('10', $_SESSION['items']) ?> >10</option>
                        <option value="15" <?php echo select('15', $_SESSION['items']) ?> >15</option>
                        <option value="25" <?php echo select('25', $_SESSION['items']) ?> >25</option>
                        <option value="50" <?php echo select('50', $_SESSION['items']) ?> >50</option>
                    </select>
                    <input class='btn btn-sm btn-primary' name='update' type="submit"
                           placeholder="<?php if (isset($search)) echo $search ?>"
                           value="<?php echo $lang['itemsPP'] ?>">
                    <script type='text/javascript'>
                        function searchpage() {
                            sn = document.getElementById('searchText').value;
                            redirecturl = '<?php echo $settings['url'] ?>houses/' + sn;
                            document.location.href = redirecturl;
                        }
                    </script>
                </form>
            </div>
            <div class="col-md-3 pull-right">
                <form style="float:right;" name='search'>
                    <input id='searchText' type='text' name='searchText'>
                    <input class='btn btn-sm btn-primary' type='button' name='search'
                           onclick='searchpage();' value='<?php echo $lang['search'] ?>'>
                </form>
            </div>
        </h4>
        <hr class="hidden-xs">
        <table class="table table-striped table-advance table-hover">
            <thead>
            <tr>
                <th><i class="fa fa-eye"></i><?php echo " " . $lang['owner'] ?></th>
                <th><i class="fa fa-user"></i><?php echo " " . $lang['position']; ?></th>
                <th class="hidden-xs"><i class="fa fa-user"></i><?php echo " " . $lang['owned']; ?></th>
                <?php if ($_SESSION['user_level'] >= P_EDIT_HOUSES) echo '<th>' . $lang['edit'] . '</th>'; ?>
            </tr>
            </thead>
            <tbody>
            <?php

            while ($row = mysqli_fetch_assoc($result_of_query)) {
                $hID = $row["id"];
                echo "<tr>";
                echo "<td>" . nameID($row["pid"], $db_link) . "</td>";
                echo "<td>" . substr($row["pos"], 1, -1) . "</td>";
                echo "<td class='hidden-xs'>" . yesNo($row["owned"], $lang) . "</td>";
                if ($_SESSION['permissions']['edit']['houses']) {
                    echo "<td><a class='btn btn-primary btn-xs' href='".$settings['url']."editHouse/" . $row["id"] . "'>";
                    echo "<i class='fa fa-pencil'></i></a></td>";
                }
                echo "</tr>";
            };
            echo "</tbody></table>";

            if (isset($search)) {
                $total_pages = ceil($total_records / $_SESSION['items']);
                if ($total_pages > 1) {
                    echo "<center><a class='btn btn-primary' href='" . $settings['url'] . "houses/" . $search . "?page=1'>" . $lang['first'] . "</a> ";
                    ?>
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                            <?php echo $lang['page'] . " " ?><span class="caret"></span>
                        </button>

                        <ul class="dropdown-menu scrollable-menu" role="menu">
                            <?php
                            for ($i = 1; $i <= $total_pages; $i++) {
                                ?>
                                <li><?php echo "<a href='" . $settings['url'] . "houses/" . $search . "?page=" . $i . "'>" . $i . "</a> "; ?></li>
                            <?php }; ?>
                        </ul>
                    </div>
                    <?php
                    echo "<a class='btn btn-primary' href='" . $settings['url'] . "houses/" . $search . "?page=" . $total_pages . "'>" . $lang['last'] . "</a></center>";
                }
            } else {
                $sql = "SELECT `id` FROM `houses`;";
                $result_of_query = $db_link->query($sql);
                $total_records = mysqli_num_rows($result_of_query);
                $total_pages = ceil($total_records / $_SESSION['items']);
                if ($total_pages > 1) {
                    echo "<center><a class='btn btn-primary' href='" . $settings['url'] . "houses?page=1'>" . $lang['first'] . "</a> ";
                    ?>
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                            <?php echo $lang['page'] . " " ?><span class="caret"></span>
                        </button>

                        <ul class="dropdown-menu scrollable-menu" role="menu">
                            <?php
                            for ($i = 1; $i <= $total_pages; $i++) {
                                ?>
                                <li><?php echo "<a href='" . $settings['url'] . "houses?page=" . $i . "'>" . $i . "</a> "; ?></li>
                            <?php }; ?>
                        </ul>
                    </div>

                    <?php
                    echo "<a class='btn btn-primary' href='" . $settings['url'] . "houses?page=$total_pages'>" . $lang['last'] . "</a></center>";
                }
            }
            ?>
    </div>

<?php
} else  echo errorMessage(3,$lang);