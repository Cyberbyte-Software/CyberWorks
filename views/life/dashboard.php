<?php
$db_link = serverConnect();

if ($db_link->connect_error) {
    echo '<h1>'.$lang['dbError'].'</h1>';
    unset($_SESSION['server_type']);
    unset($_SESSION['dbid']);
} else {
    if ($_SESSION['user_level'] >= 2) {
        if ($settings['lifeVersion'] == 3) {
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
                            <h4>
                                <i class="fa fa-taxi fa-fw"></i>
                                <?php echo $lang['police'] . " " . $lang['overview']; ?>
                                <div class="col-lg-3 pull-right">
                                    <a href="<?php echo $settings['url'] ?>police"><?php echo $lang['viewAll']; ?> <i
                                            class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </h4>
                            <hr>
                            <thead>
                            <tr>
                                <th><i class="fa fa-user"></i> <?php echo $lang['name']; ?></th>
                                <th><i class="fa fa-eye"></i> <?php echo $lang['playerID']; ?></th>
                                <th>
                    </div>
                    <?php echo $lang['rank']; ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sql = "SELECT `name`,`coplevel`, $playerIdColumn as playerid FROM `players` WHERE `coplevel` >= '1' ORDER BY `coplevel` DESC LIMIT 10";
                    $result_of_query = $db_link->query($sql);
                    while ($row = mysqli_fetch_assoc($result_of_query)) {
                        $playersID = $row["playerid"];
                        echo "<tr>";
                        echo "<td>" . $row["name"] . "</td>";
                        echo "<td>" . $playersID . "</td>";
                        echo "<td>" . $row["coplevel"] . "</td>";
                        echo "</tr>";
                    };
                    ?>
                    </tbody>
                    </table>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="content-panel">
                    <table class="table table-striped table-advance table-hover">
                        <h4>
                            <i class="fa fa-money fa-fw"></i> <?php echo $lang['topRich']; ?>
                        </h4>
                        <hr>
                        <thead>
                        <tr>
                            <th><i class="fa fa-user"></i> <?php echo $lang['name']; ?></th>
                            <th><i class="fa fa-money"></i> <?php echo $lang['cash']; ?></th>
                            <th><i class="fa fa-bank"></i> <?php echo $lang['bank']; ?></th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        $sql = "SELECT `name`, `cash`, `bankacc` FROM `players` ORDER BY `bankacc` DESC, `cash` DESC LIMIT 10";
                        $result_of_query = $db_link->query($sql);
                        while ($row = mysqli_fetch_assoc($result_of_query)) {
                            echo "<tr>";
                            echo "<td>" . $row["name"] . "</td>";
                            echo "<td>" . $row["cash"] . "</td>";
                            echo "<td>" . $row["bankacc"] . "</td>";
                            echo "</tr>";
                        };
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="content-panel">
                    <table class="table table-striped table-advance table-hover">
                        <h4>
                            <i class="fa fa-ambulance fa-fw"></i>
                            <?php echo $lang['medic'] . " " . $lang['overview']; ?>
                            <div class="col-lg-3 pull-right">
                                <a href="<?php echo $settings['url'] ?>medic"><?php echo $lang['viewAll'] . " "; ?> <i
                                        class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </h4>
                        <hr>
                        <thead>
                        <tr>
                            <th><i class="fa fa-user"></i><?php echo " " . $lang['name']; ?></th>
                            <th><i class="fa fa-eye"></i><?php echo " " . $lang['playerID']; ?></th>
                            <th>
                                <?php echo $lang['rank']; ?></th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        $sql = "SELECT `name`,`mediclevel`, $playerIdColumn as playerid FROM `players` WHERE `mediclevel` >= '1' ORDER BY `mediclevel` DESC LIMIT 10";
                        $result_of_query = $db_link->query($sql);
                        while ($row = mysqli_fetch_assoc($result_of_query)) {
                            $playersID = $row["playerid"];
                            echo "<tr>";
                            echo "<td>" . $row["name"] . "</td>";
                            echo "<td>" . $playersID . "</td>";
                            echo "<td>" . $row["mediclevel"] . "</td>";
                            echo "</tr>";
                        };
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            </div>
            <br/>
            <div class="row">
                <div class="col-lg-4">
                    <div class="content-panel">
                        <table class="table table-striped table-advance table-hover">
                            <h4>
                                <i class="fa fa-taxi fa-fw"></i>
                                <?php echo $lang['admin'] . " " . $lang['overview']; ?>
                                <div class="col-lg-3 pull-right">
                                    <a href="<?php echo $settings['url'] ?>admins"><?php echo $lang['viewAll']; ?> <i
                                            class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </h4>
                            <hr>
                            <thead>
                            <tr>
                                <th><i class="fa fa-user"></i> <?php echo $lang['name']; ?></th>
                                <th><i class="fa fa-eye"></i> <?php echo $lang['playerID']; ?></th>
                                <th>
                    </div>
                    <?php echo $lang['rank']; ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sql = "SELECT `name`,`adminlevel`, $playerIdColumn as playerid FROM `players` WHERE `adminlevel` >= '1' ORDER BY `adminlevel` DESC LIMIT 10";
                    $result_of_query = $db_link->query($sql);
                    while ($row = mysqli_fetch_assoc($result_of_query)) {
                        $playersID = $row["playerid"];
                        echo "<tr>";
                        echo "<td>" . $row["name"] . "</td>";
                        echo "<td>" . $playersID . "</td>";
                        echo "<td>" . $row["adminlevel"] . "</td>";
                        echo "</tr>";
                    };
                    ?>
                    </tbody>
                    </table>
                </div>
            </div>
            <?php
        } else if ($settings['lifeVersion'] == 4) {
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
                <div class="col-lg-3 col-md-4 col-lg-offset-1">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3" style="font-size: 5em;">
                                    <i class="fa fa-user fa-fw"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <?php
                                    $sql = "SELECT `name` FROM `players` ORDER BY `uid` DESC LIMIT 1;";
                                    $result_of_query = $db_link->query($sql);
                                    while ($row = mysqli_fetch_assoc($result_of_query)) {
                                        echo "<h1>" . $row["name"] . "</h1>";
                                    };
                                    ?>
                                    <div>Newest Player</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3" style="font-size: 5em;">
                                    <i class="fa fa-fw fa-users"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <?php
                                        $sql = "SELECT `uid` FROM `players`;";
                                        $result_of_query = $db_link->query($sql);
                                        $total_records = mysqli_num_rows($result_of_query);
                                        echo "<h1>" . $total_records . "</h1>";
                                    ?>
                                    <div>Players</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3" style="font-size: 5em;">
                                    <i class="fa fa-fw fa-car"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <?php
                                    $sql = "SELECT `id` FROM `vehicles`;";
                                    $result_of_query = $db_link->query($sql);
                                    $total_records = mysqli_num_rows($result_of_query);
                                    echo "<h1>" . $total_records . "</h1>";
                                    ?>
                                    <span>Vehicles</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> Player Join Data
                        </div>
                        <div class="panel-body">
                            <div id="player_data_chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <ul id="myTab" class="nav nav-tabs nav-justified">
                            <li class="active">
                                <a href="#police" data-toggle="tab">
                                    <i class="fa fa-taxi fa-fw"></i>
                                    <small><?php echo $lang['police'] . " " . $lang['overview']; ?></small>
                                </a>
                            </li>
                            <li>
                                <a href="#medic" data-toggle="tab">
                                    <i class="fa fa-ambulance fa-fw"></i>
                                    <small><?php echo $lang['medic'] . " " . $lang['overview']; ?></small>
                                </a>
                            </li>
                            <li>
                                <a href="#money" data-toggle="tab">
                                    <i class="fa fa-money fa-fw"></i>
                                    <small>Top Ten</small>
                                </a>
                            </li>
                            <li>
                                <a href="#admins" data-toggle="tab">
                                    <i class="fa fa-users fa-fw"></i>
                                    <small><?php echo $lang['admin'] . " " . $lang['overview']; ?></small>
                                </a>
                            </li>
                        </ul>
                        <div class="panel-body">
                            <div id="myTabContent" class="tab-content">
                                <div class="tab-pane fade in active" id="police">
                                    <table class="table table-striped table-advance table-hover">
                                        <h4>
                                            <i class="fa fa-taxi fa-fw"></i>
                                            <?php echo $lang['police'] . " " . $lang['overview']; ?>
                                            <div class="col-lg-3 pull-right">
                                                <a href="<?php echo $settings['url'] ?>police"><?php echo $lang['viewAll']; ?> <i
                                                        class="fa fa-arrow-circle-right"></i></a>
                                            </div>
                                        </h4>
                                        <hr>
                                        <thead>
                                        <tr>
                                            <th><i class="fa fa-user"></i> <?php echo $lang['name']; ?></th>
                                            <th><i class="fa fa-eye"></i> <?php echo $lang['playerID']; ?></th>
                                            <th><?php echo $lang['rank']; ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $sql = "SELECT `name`,`coplevel`, $playerIdColumn as playerid FROM `players` WHERE `coplevel` >= '1' ORDER BY `coplevel` DESC LIMIT 10";
                                        $result_of_query = $db_link->query($sql);
                                        while ($row = mysqli_fetch_assoc($result_of_query)) {
                                            $playersID = $row["playerid"];
                                            echo "<tr>";
                                            echo "<td>" . $row["name"] . "</td>";
                                            echo "<td>" . $playersID . "</td>";
                                            echo "<td>" . $row["coplevel"] . "</td>";
                                            echo "</tr>";
                                        };
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="medic">
                                    <table class="table table-striped table-advance table-hover">
                                        <h4>
                                            <i class="fa fa-ambulance fa-fw"></i>
                                            <?php echo $lang['medic'] . " " . $lang['overview']; ?>
                                            <div class="col-lg-3 pull-right">
                                                <a href="<?php echo $settings['url'] ?>medic"><?php echo $lang['viewAll']; ?> <i
                                                        class="fa fa-arrow-circle-right"></i></a>
                                            </div>
                                        </h4>
                                        <hr>
                                        <thead>
                                        <tr>
                                            <th><i class="fa fa-user"></i> <?php echo $lang['name']; ?></th>
                                            <th><i class="fa fa-eye"></i> <?php echo $lang['playerID']; ?></th>
                                            <th><?php echo $lang['rank']; ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $sql = "SELECT `name`,`mediclevel`, $playerIdColumn as playerid FROM `players` WHERE `mediclevel` >= '1' ORDER BY `mediclevel` DESC LIMIT 10";
                                        $result_of_query = $db_link->query($sql);
                                        while ($row = mysqli_fetch_assoc($result_of_query)) {
                                            $playersID = $row["playerid"];
                                            echo "<tr>";
                                            echo "<td>" . $row["name"] . "</td>";
                                            echo "<td>" . $playersID . "</td>";
                                            echo "<td>" . $row["mediclevel"] . "</td>";
                                            echo "</tr>";
                                        };
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="money">
                                    <table class="table table-striped table-advance table-hover">
                                        <h4>
                                            <i class="fa fa-money fa-fw"></i>
                                            <?php echo $lang['topRich']; ?>
                                        </h4>
                                        <hr>
                                        <thead>
                                        <tr>
                                            <th><i class="fa fa-user"></i> <?php echo $lang['name']; ?></th>
                                            <th><i class="fa fa-money"></i> <?php echo $lang['cash']; ?></th>
                                            <th><i class="fa fa-bank"></i> <?php echo $lang['bank']; ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $sql = "SELECT `name`, `cash`, `bankacc` FROM `players` ORDER BY `bankacc` DESC, `cash` DESC LIMIT 10";
                                        $result_of_query = $db_link->query($sql);
                                        while ($row = mysqli_fetch_assoc($result_of_query)) {
                                            echo "<tr>";
                                            echo "<td>" . $row["name"] . "</td>";
                                            echo "<td>" . $row["cash"] . "</td>";
                                            echo "<td>" . $row["bankacc"] . "</td>";
                                            echo "</tr>";
                                        };
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="admins">
                                    <table class="table table-striped table-advance table-hover">
                                        <h4>
                                            <i class="fa fa-taxi fa-fw"></i>
                                            <?php echo $lang['admin'] . " " . $lang['overview']; ?>
                                            <div class="col-lg-3 pull-right">
                                                <a href="<?php echo $settings['url'] ?>admins"><?php echo $lang['viewAll']; ?> <i
                                                        class="fa fa-arrow-circle-right"></i></a>
                                            </div>
                                        </h4>
                                        <hr>
                                        <thead>
                                        <tr>
                                            <th><i class="fa fa-user"></i> <?php echo $lang['name']; ?></th>
                                            <th><i class="fa fa-eye"></i> <?php echo $lang['playerID']; ?></th>
                                            <th><?php echo $lang['rank']; ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $sql = "SELECT `name`,`adminlevel`, $playerIdColumn as playerid FROM `players` WHERE `adminlevel` >= '1' ORDER BY `adminlevel` DESC LIMIT 10";
                                        $result_of_query = $db_link->query($sql);
                                        while ($row = mysqli_fetch_assoc($result_of_query)) {
                                            $playersID = $row["playerid"];
                                            echo "<tr>";
                                            echo "<td>" . $row["name"] . "</td>";
                                            echo "<td>" . $playersID . "</td>";
                                            echo "<td>" . $row["adminlevel"] . "</td>";
                                            echo "</tr>";
                                        };
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php
        }
    }
}
