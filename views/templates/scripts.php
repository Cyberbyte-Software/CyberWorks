<div class="modal fade" id="changeDB" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><i class="fa fa-pencil"></i>
                    Switch Database
                </h4>
            </div>
            <section class="task-panel tasks-widget">
                <div class="panel-heading">
                    <div class="pull-left"><h5><i class="fa fa-tasks"></i> <?php echo $lang['database'] . 's'  ?></h5></div>
                    <br>
                </div>
                <div class="panel-body">
                    <div class="task-content">
                        <ul id="sortable" class="task-list ui-sortable">
                            <?php
                            $sql = "SELECT `sid`,`dbid`,`type`,`name` FROM `servers`;";
                            $result_of_query = $db_connection->query($sql);

                            if ($result_of_query->num_rows > 1) {
                                while ($row = mysqli_fetch_assoc($result_of_query)) {
                                    if ($row['type'] == 'life') {
                                        ?>
                                        <li class="list-primary">
                                            <i class=" fa fa-ellipsis-v"></i>

                                            <div class="task-title">
                                                <span class="task-title-sp"><?php echo $row['name']; ?></span>
                                                <span class="badge bg-theme">Life</span>

                                                <div class="pull-right hidden-phone">
                                                    <form method="post" action="<?php echo $settings['url'] ?>dashboard">
                                                        <input type="hidden" name="type"
                                                               value="<?php echo $row['type']; ?>">
                                                        <input type="hidden" name="dbid"
                                                               value="<?php echo $row['dbid']; ?>">
                                                        <button class="btn btn-success btn-sm fa fa-eye"
                                                                type="submit" style="margin-right: 8px;  margin-bottom: 15px;"></button>
                                                    </form>
                                                </div>
                                            </div>
                                        </li>
                                    <?php
                                    } elseif ($row['type'] == 'waste') {
                                        ?>
                                        <li class="list-danger">
                                            <i class=" fa fa-ellipsis-v"></i>

                                            <div class="task-title">
                                                <span class="task-title-sp"><?php echo $row['name']; ?></span>
                                                <span class="badge bg-important">Wasteland</span>

                                                <div class="pull-right hidden-phone">
                                                    <form method="post" action="<?php echo $settings['url'] ?>dashboard">
                                                        <input type="hidden" name="type"
                                                               value="<?php echo $row['type']; ?>">
                                                        <input type="hidden" name="dbid"
                                                               value="<?php echo $row['dbid']; ?>">
                                                        <button class="btn btn-success btn-sm fa fa-eye"
                                                                type="submit" style="margin-right: 8px;  margin-bottom: 15px;"></button>
                                                    </form>
                                                </div>
                                        </li>
                                    <?php
                                    }
                                }
                                echo '</select>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<script async src="<?php echo $settings['url'] ?>assets/js/main.min.js"></script>
<?php if (isset($_SESSION['forum_lang'])) echo '<script async type="text/javascript" src="' . $settings["url"] . 'assets/js/language/' . $_SESSION['forum_lang'] . '.js"></script>'; ?>
<script>
    function searchpage() {
        sn = document.getElementById('searchText').value;
        redirecturl = '<?php echo $settings["url"] . $currentPage?>/' + sn;
        document.location.href = redirecturl;
    }
</script>
<script type="text/javascript">
    $('#myTab a').click(function (e) {
        console.log('clicked ' + this);
        if ($(this).parent('li').hasClass('active')) {
            var target_pane = $(this).attr('href');
            console.log('pane: ' + target_pane);
            $(target_pane).toggle(!$(target_pane).is(":visible"));
        }
    });
</script>
<?php
foreach ($settings['plugins'] as &$plugin) {
    if (file_exists("plugins/" . $plugin . "/assets/scripts.js")) {
        echo '<script type="text/javascript" src="' . $settings['url'] . 'plugins/' . $plugin . '/assets/scripts.js"></script>';
    }
}

