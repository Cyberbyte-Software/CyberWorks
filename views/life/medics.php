<?php
$db_link = serverConnect();
$max = 'LIMIT ' . $start_from . ',' . $_SESSION['items'];

$sql = "SELECT `name`,`mediclevel`,`playerid`,`uid` FROM `players` WHERE `mediclevel` >= '1' ORDER BY `mediclevel` " . $max . " ;";
$result_of_query = $db_link->query($sql);
if ($result_of_query->num_rows > 0) {
    ?>

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                <?php echo $lang['medic']; ?>
                <small><?php echo $lang['overview']; ?></small>
            </h1>
        </div>
    </div>
    <!-- /.row -->

    <div class="col-md-12">
        <div class="content-panel">
            <table class="table table-striped table-advance table-hover">
                <h4>
                    <i class="fa fa-ambulance fa-fw"></i>
                    <?php echo $lang['medic']; ?>
                </h4>
                <hr>
                <thead>
                <tr>
                    <th><i class="fa fa-user"></i> <?php echo $lang['name']; ?></th>
                    <th><i class="fa fa-eye"></i> <?php echo $lang['playerID']; ?></th>
                    <th><i class="fa fa-user"></i> <?php echo $lang['rank']; ?></th>
                    <?php if ($_SESSION['permissions']['edit']['player']) {
    echo '<th><i class="fa fa-pencil"></i> ' . $lang['edit'] . '</th>';
}
?>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result_of_query)) {
                    echo "<tr>";
                    echo "<td>" . $row["name"] . "</td>";
                    echo "<td>" . $row["playerid"] . "</td>";
                    echo "<td>" . $row["mediclevel"] . "</td>";
                    if ($_SESSION['permissions']['edit']['player']) {
                        echo "<td><a class='btn btn-primary btn-xs' href='" . $settings['url'] . "editPlayer/" . uID($row["uid"], $db_link) . "'>";
                        echo "<i class='fa fa-pencil'></i></a></td>";
                    }
                    echo "</tr>";
                };
                echo "</tbody></table>";

                $sql = "SELECT * FROM `players` WHERE `mediclevel` >= '1';";
                $result_of_query = $db_link->query($sql);
                $total_records = mysqli_num_rows($result_of_query);
                $total_pages = ceil($total_records / $_SESSION['items']);
                if ($total_pages > 1) {
                    echo "<center><a class='btn btn-primary' href='" . $settings['url'] . "medic/1'>" . $lang['first'] . "</a> ";
                    ?>
					<div class="btn-group">
						<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
							<?php echo $lang['page'] . " " ?><span class="caret"></span>
						</button>

						<ul class="dropdown-menu scrollable-menu" role="menu">
							<?php
                            for ($i = 1; $i <= $total_pages; $i++) {
                                echo "<li><a href='" . $settings['url'] . "medic/" . $i . "'>" . $i . "</a></li>";
                            }; ?>
						</ul>
					</div>
					<?php
                    echo "<a class='btn btn-primary' href='" . $settings['url'] . "medic/".$total_pages."'>" . $lang['last'] . "</a></center>";
                } ?>
                <br>
                </tbody>
            </table>
        </div>
    </div>
<?php } else {
    echo '<h1>'.errorMessage(36,$lang).'</h1>';
}
