<?php
$db_link = serverConnect();

if ($db_link->connect_error) {
    echo '<h1>'.$lang['dbError'].'</h1>';
    unset($_SESSION['server_type']);
    unset($_SESSION['dbid']);
} else {
    if ($_SESSION['user_level'] >= 2) {
    ?>

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                <?php echo $lang['navDashboard']; ?>
            </h1>
        </div>
    </div>
    <?php if (isset($_SESSION['update'])) echo '<div class="alert alert-info" role="alert">' . $land['updateMessage'] . ' (' . $_SESSION['message']->version . ') <a href="https://github.com/Cyberbyte-Studios/CyberWorks/releases">Download Section</a</div>'; ?>
    <div class="row">
        <div class="col-lg-4">
            <div class="content-panel">
                <table class="table table-striped table-advance table-hover">
                    <h2 class="text-center">
                        <!--<a href="<?php echo $settings['url'] ?>clans"><?php echo $lang['clan']; ?>  <i class="fa fa-arrow-circle-right"></i></a>-->
                    </h2>
                    <!--<<thead>
                    <tr>
                        <th><i class="fa fa-user"></i> <?php echo $lang['name']; ?></th>
                        <th><i class="fa fa-eye"></i> <?php echo $lang['leader']; ?></th>
                        <th><i class="fa fa-clock-o"></i> <?php echo $lang['createdAt']; ?></th>
                        <th><i class="fa fa-flag"></i> <?php echo $lang['insigniaTexture']; ?></th>
                    </tr>
                    </thead>
                    <tbody>-->
                    <h2 class="text-center">Coming Soon</h2>
                    <?php
                    /*
                    $sql = "SELECT * FROM `clan` INNER JOIN `account` ON clan.leader_uid=account.uid DESC LIMIT 10";
                    $result_of_query = $db_link->query($sql);
                    while ($row = mysqli_fetch_assoc($result_of_query)) {
                        var_dump($row);

                        echo "<tr>";
                        echo "<td>" . $row["name"] . "</td>";
                        echo "<td>" . $playersID . "</td>";
                        echo "<td>" . $row["created_at"] . "</td>";
                        echo "<td>" . $row["insignia_texture"] . "</td>";
                        echo "</tr>";
                    };*/

                    ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="content-panel">
                <table class="table table-striped table-advance table-hover">
                    <h2 class="text-center"> <?php echo $lang['top10Players']; ?><small> Sorted By Score </small> </h2>
                    <thead>
                    <tr>
                        <th><i class="fa fa-user"></i>  <?php echo $lang['name']; ?></th>
                        <th><i class="fa fa-money"></i> <?php echo $lang['money']; ?></th>
                        <th><i class="fa fa-user-times"></i>  <?php echo $lang['kills']; ?></th>
                        <th><i class="fa fa-eye-slash"></i>  <?php echo $lang['deaths']; ?></th>
                        <th><i class="fa fa-line-chart"></i>  <?php echo $lang['score']; ?></th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    $sql = "SELECT `name`, `money`, `kills`, `deaths`, `score` FROM `account` ORDER BY `score` DESC, `money` DESC LIMIT 10";
                    $result_of_query = $db_link->query($sql);
                    while ($row = mysqli_fetch_assoc($result_of_query)) {
                        echo "<tr>";
                        echo '<td><a href="' . $settings['url'] . 'editPlayer/' . str_replace(' ','-',$row['name']) . '">' . $row['name'] . '</a></td>';
                        echo "<td>" . $row["money"] . "</td>";
                        echo "<td>" . $row["kills"] . "</td>";
                        echo "<td>" . $row["deaths"] . "</td>";
                        echo "<td>" . $row["score"] . "</td>";
                        echo "</tr>";
                    };
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="content-panel">
            <h2 class="text-center"><?php echo $lang['dash-stat']; ?></h2>
                    <?php

                        $sql = "SELECT `id` FROM `player` WHERE `is_alive` = '1'; ";
                        $result = $db_link->query($sql);
                        $count = $result->num_rows;
                        $data[] = $count;

                        $sql = "SELECT SUM(total_connections) as 'total' FROM `account`;";
                        $result = $db_link->query($sql);
                        $row = $result->fetch_assoc();
                        $data[] = $row;

                        $sql = "SELECT SUM(kills) as 'total' FROM `account`;";
                        $result = $db_link->query($sql);
                        $row = $result->fetch_assoc();
                        $data[] = $row;

                        $sql = "SELECT SUM(deaths) as 'total' FROM `account`;";
                        $result = $db_link->query($sql);
                        $row = $result->fetch_assoc();
                        $data[] = $row;

                        $sql = "SELECT SUM(money) as 'total' FROM `account`;";
                        $result = $db_link->query($sql);
                        $row = $result->fetch_assoc();
                        $data[] = $row;
                    ?>
                    <hr>
                    <center><h3><i class="fa fa-user"></i> <?php echo $lang['total'] . ' ' . $lang['connections'].': '. $data[1]['total']; ?></span></h3></center>
                    <center><h3><i class="fa fa-heartbeat"></i> <?php echo $lang['kills'].': '. $data[2]['total']; ?></span></h3></center>
                    <center><h3><i class="fa fa-heart-o"></i> <?php echo $lang['deaths'].': '. $data[3]['total']; ?></span></h3></center>
                    <center><h3><i class="fa fa-money"></i> <?php echo $lang['money'].': '. $data[4]['total']; ?></span></h3></center>
                    <center><h3><i class="fa fa-child"></i> <?php echo $lang['alivePlayers'].': '. $data[0]; ?></span></h3></center>
                </div>
            </div>
        </div>
    </div>

    <?php include("views/templates/news.php");
    }
}