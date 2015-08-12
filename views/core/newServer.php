<?php
if (isset($_POST['server_name']) && isset($_POST['server_type']) && isset($_POST['server_dbid']) && isset($_POST['server_SQ'])) {
    if (formtoken::validateToken($_POST)) {
        $dbid = $_POST['server_dbid'];
        if ($dbid == 'none') {
            $server_type = 'life';

            $sql = "INSERT INTO `db` (`type`, `sql_host`, `sql_user`, `sql_pass`, `sql_name`) VALUES ('" . $server_type . "', '" . $settings['db']['host'] . "', '" . $settings['db']['user'] . "', '" . $settings['db']['pass'] . "', '" . $settings['db']['name'] . "');";
            $result_of_query = $db_connection->query($sql);

            $sql = "SELECT `dbid` FROM `db` WHERE `sql_name` = '" . $SQL_name . "';";
            $result_of_query = $db_connection->query($sql);
            while ($row = mysqli_fetch_assoc($result_of_query)) {
                $dbid = $row['dbid'];
            }
        }

        $server_name = $_POST['server_name'];
        $server_type = $_POST['server_type'];
        $server_SQ = $_POST['server_SQ'];

        if ($server_SQ == 1) {
            if (isset($_POST['server_SQ_host']) && isset($_POST['server_SQ_port']) && isset($_POST['server_RCON_pass'])) {
                $server_SQ_host = encrypt($_POST['server_SQ_host']);
                $server_SQ_port = encrypt($_POST['server_SQ_port']);
                $server_RCON_pass = encrypt($_POST['server_RCON_pass']);
                $server_RCON_pass = encrypt($_POST['server_RCON_pass']);

                $sql = "INSERT INTO `servers` (`name`, `dbid`, `type`, `use_sq`, `sq_port`,`sq_ip`, `rcon_pass`) VALUES
                ('" . $server_name . "', '" . $dbid . "', '" . $server_type . "', '" . $server_SQ . "', '" . $server_SQ_port . "', '" . $server_SQ_host . "', '" . $server_RCON_pass . "');";
            }
        } else {
            $sql = "INSERT INTO `servers` (`name`, `dbid`, `type`, `use_sq`) VALUES
                ('" . $server_name . "', '" . $dbid . "', '" . $server_type . "', '" . $server_SQ . "');";
        }

        $result_of_query = $db_connection->query($sql);
        message("Added New Server!");
    } else {
        message($lang['expired']);
    }
    }
?>
<div id="login-page">
    <div class="col-lg-10 container">
        <form method="post" action="newServer" name="newServer" id="newServer">
            <?php echo formtoken::getField() ?>
            <h2 class="form-login-heading">
                <?php echo $lang['new'] . ' ' . $lang['gameServers'] ?>
            </h2>
            <div class="form-group">
                <label for="server_name">Server Name: </label>
                <input placeholder="Server Name" id="server_name" class=" form-control login_input" type="text" name="server_name"
                    <?php if (isset($_POST['server_name'])) {
    echo 'value="' . $_POST['server_name'] . '"' ?>
                       autocorrect="off" required>
                </div>
                <div class="form-group">
                    <label for="server_type">Server type: </label>
                    <select id="server_type" class=" form-control login_input" name="server_type">
                        <option value="life">Altis Life</option>
                        <option value="wasteland">Wasteland</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="server_SQ">Use Server Query: </label>
                    <select id="server_SQ" class=" form-control login_input" name="server_SQ">
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>
                <div id="server_SQ_info">
                <div class="form-group">
                    <label for="server_SQ_host">SQ IP: </label>
                    <input placeholder="SQ IP" id="server_SQ_host"
                       class=" form-control login_input" type="text"
                       name="server_SQ_host"
                        <?php if (isset($_POST['server_SQ_host'])) echo 'value="' . $_POST['server_SQ_host'] . '"' ?> autocorrect="off" autocapitalize="off">
                    </div>
                    <div class="form-group">
                        <label for="server_SQ_port">SQ port: </label>
                        <input placeholder="SQ Port" id="server_SQ_port"
                       class=" form-control login_input" type="text"
                       name="server_SQ_port"
                            <?php if (isset($_POST['server_SQ_port'])) echo 'value="' . $_POST['server_SQ_port'] . '"' ?> autocorrect="off" autocapitalize="off" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="server_RCON_pass">RCON Password: </label>
                            <input placeholder="RCON Password" id="server_RCON_pass" class=" form-control login_input" type="password" name="server_RCON_pass"
                                <?php if (isset($_POST['server_RCON_pass'])) echo 'value="' . $_POST['server_RCON_pass'] . '"' ?> autocorrect="off" autocapitalize="off" autocomplete="off">
                            </div>
                            </div>
                            <?php
                $sql = "SELECT `dbid`,`sql_name` FROM `db`;";
}
                $result_of_query = $db_connection->query($sql);
                if ($result_of_query->num_rows >= 1) {
                    echo '<div class="form-group"><label for="server_dbid">Database: </label>';
                    echo '<select id="server_dbid" name="server_dbid" class="form-control login_input">';
                    while ($row = mysqli_fetch_assoc($result_of_query)) {
                        echo '<option value="' . $row['dbid'] . '">' . decrypt($row['sql_name'], $settings['key']) . '</option>';
                    }
                    echo '</select></div>';
                } else {
                    echo $lang['nodb'];
                    echo '<input type="hidden" id="server_dbid" name="server_dbid" value="none">';
                }
                ?>
                                <div class="form-group">
                                    <input class="btn btn-lg btn-primary" style="float:right;" type="submit" name="setup"
                       value="Setup">
                                    </div>
                                </form>
                            </div>
<script>
$(document).ready(function() {
$("#server_SQ").change(function () {
  var selected_option = $('#server_SQ').val();

  if (selected_option === '1') {
    $('#server_SQ_info').show();
  }
  if (selected_option != '1') {
    $("#server_SQ_info").hide();
  }
});
$('#newServer').formValidation({
    framework: 'bootstrap',
    icon: {
        valid: 'fa fa-check',
        invalid: 'fa fa-times',
        validating: 'fa fa-spin fa-refresh'
    },
    fields: {
        server_name: {
            validators: {
                notEmpty: {},
                stringLength: {
                    min: 3,
                    max: 15
                },
                regexp: {
                    regexp: /^[a-zA-Z0-9_]+$/,
                },
                remote: {
                    message: 'The name is not available',
                    url: '<?php echo $settings['url']; ?>validators/newServer.php',
                    data: {
                        type: 'username'
                    },
                    type: 'POST'
                }
            }
        },
        server_SQ_host: {
            validators: {
                ip: {
                }
            }
        },
        server_SQ_port: {
            validators: {
                regexp: {
                    regexp: /^([0-9]{1,4}|[1-5][0-9]{4}|6[0-4][0-9]{3}|65[0-4][0-9]{2}|655[0-2][0-9]|6553[0-5])$/,
                }
            }
        }
    }
    });
});
</script>
