<?php
$id = clean($id, "int");
if (isset($_POST['del']) && $id != 1) {
    $sql = "DELETE FROM `db` WHERE `dbid`='" . $id . "';";
    $result_of_query = $db_connection->query($sql);
    $sql = "DELETE FROM `servers` WHERE `dbid`='" . $id . "';";
    $result_of_query = $db_connection->query($sql);

}
elseif (isset($_POST['edit'])) {
    if (isset($_POST['sql_host'])) {
        if ($_SESSION['permissions']['super_admin']) {
            if (formtoken::validateToken($_POST)) {
                $host = encrypt(clean($_POST['sql_host'], "string"));
                $user = encrypt(clean($_POST['sql_user'], "string"));
                $pass = encrypt(clean($_POST['sql_pass'], "string"));
                $name = encrypt(clean($_POST['sql_name'], "string"));

                $sql = "UPDATE `db` SET `sql_host` = '" . $host . "',`sql_name` = '" . $name . "',`sql_pass` = '" . $pass . "',`sql_user` = '" . $user . "' WHERE `dbid`='" . $id . "';";
                $result_of_query = $db_connection->query($sql);

                $type = clean($_POST['type'], "string");
                $name = clean($_POST['name'], "string");

                $usegsq = clean($_POST['usegsq'], "int");
                if ($_POST['usegsq'] == 1) {
                    $sq_ip = encrypt(clean($_POST['sq_ip'], "string"));
                    $sq_port = encrypt(clean($_POST['sq_port'], "string"));
                    $rcon_pass = encrypt(clean($_POST['rcon_pass'], "string"));
                    $sql = "UPDATE `servers` SET `name`= '" . $name . "',`type`= '" . $type . "',`use_sq`= '" . $usegsq . "',`sq_port`= '" . $sq_port . "',`sq_ip`= '" . $sq_ip . "',`rcon_pass`= '" . $rcon_pass . "' WHERE `dbid`='" . $id . "';";
                } else {
                    $sql = "UPDATE `servers` SET `name`= '" . $name . "',`type`= '" . $type . "',`use_sq`= '" . $usegsq . "' WHERE `dbid`='" . $id . "';";
                }
                $result_of_query = $db_connection->query($sql);
            } else message($lang['expired']);
        } else logAction($_SESSION['user_name'], $lang['failedUpdate'] . ' ' . $lang['gsq'], 3);
    }
}
    $sql = "SELECT * FROM `servers` WHERE `dbid`='" . $id . "';";
    $result_of_query = $db_connection->query($sql);

    if ($result_of_query->num_rows == 1) {
        $server = $result_of_query->fetch_object();
        $sql = "SELECT `sql_host`,`dbid`,`sql_name`,`sql_pass`,`sql_user` FROM `db` WHERE `dbid`='" . $id . "';";
        $result = $db_connection->query($sql);
        if ($result->num_rows == 1) {
            $db = $result->fetch_object();

?>
<div class="row">
    <div class="col-lg-8">
        <h1 class="page-header">
            <?php echo $lang['settings']; ?>
        </h1>
    </div>
</div>
<div id='text'></div>

    <form method='post' action='<?php echo $settings['url'] ?>editServer/<?php echo $id ?>' id='updateServer' name='updateServer'>
        <div class="col-md-6">
            <?php
            echo "<h3>" . $lang['database'] . "</h3>";
            echo "<div class='form-group'><label for='sql_host'>" . $lang['database'] . " " . $lang['host'] . ": </label><input class='form-control' id='sql_host' type='text' name='sql_host' value='" . decrypt($db->sql_host) . "'></div>";
            echo "<div class='form-group'><label for='sql_user'>" . $lang['database'] . " " . $lang['user'] . ": </label><input class='form-control' id='sql_user' type='text' name='sql_user' value='" . decrypt($db->sql_user) . "'></div>";
            echo "<div class='form-group'><div class='input-group'><label for='sql_pass'>" . $lang['database'] . " " . $lang['password'] . ": </label><input class='form-control pwd' id='sql_pass' type='password' name='sql_pass' value='" . decrypt($db->sql_pass) . "'>";
            echo "<span class='input-group-btn'><button style='margin-top: 23px; background-color: #eee;' ";
            echo "class='btn btn-default reveal' type='button'><i class='fa fa-eye-slash'></i></button></span></div></div>";
            echo "<div class='form-group'><label for='sql_name'>" . $lang['database'] . " " . $lang['name'] . ": </label><input class='form-control' id='sql_name' type='text' name='sql_name' value='" . decrypt($db->sql_name) . "'></div>";
            echo "<div class='form-group'><label for='name'>" . $lang['name'] . ": </label><input class='form-control' id='name' type='text' name='name' value='" . $server->name . "'></div>";
            ?>
            <div class='form-group'><label for="type"><?php echo $lang['database'] . " " . $lang['type'] ?>: </label>
                <select name="type" id="type" class="form-control">
                    <option value="life"
                    <?php echo select('life', $server->type) . '>' . $lang['life'] ?></option>
                    <option value="waste"
                    <?php echo select('waste', $server->type) . '>' . $lang['waste'] ?></option>
                </select>
            </div>
        </div>
        <div class="col-md-6" style="float:right;">
                <h3><?php echo $lang['gsq'] ?></h3>
                <div class='form-group'><label for="usegsq"><?php echo $lang['use'] . " " . $lang['gsq'] ?>: </label>
                <select name="usegsq" id="usegsq" class="form-control">
                    <option value="1"
                    <?php echo select('1', $server->use_sq) . '>' . $lang['yes'] ?></option>
                    <option value="0"
                    <?php echo select('0', $server->use_sq) . '>' . $lang['no'] ?></option>
                </select>
            </div> <?php
                echo "<div id='sq_details'><div class='form-group'><label for='sq_ip'>" . $lang['gsq'] . " " . $lang['gsqa'] . ": </label><input class='form-control' id='sq_ip' type='text' name='sq_ip' value='";
                if (isset($server->sq_ip)) {
                    echo decrypt($server->sq_ip);
                }
                echo "'></div>";
                echo "<div class='form-group'><label for='sq_port'>" . $lang['gsq'] . " " . $lang['gsqp'] . ": </label><input class='form-control' id='sq_port' type='text' name='sq_port' value='";
                if (isset($server->sq_port)) {
                    echo decrypt($server->sq_port);
                }
                echo "'></div>";
                echo "<div class='form-group'><div class='input-group'><label for='rcon_pass'>" . $lang['gsq'] . " " . $lang['gsrc'] . ": </label><input class='form-control pwd' id='rcon_pass' type='password' name='rcon_pass' value='";
                if (isset($server->rcon_pass)) {
                    echo decrypt($server->rcon_pass);
                }
                echo "'>";
                ?>
                <span class='input-group-btn'><button style='margin-top: 23px; background-color: #eee;'
                class='btn btn-default reveal' type='button'><i class='fa fa-eye-slash'></i></button></span></div></div></div>
        </div>
        <br>
        <input class='btn btn-primary' type='submit'  name='edit' value='<?php echo $lang['subChange'] ?>'>
        <?php if ($db->dbid != 1) {?>
        <input class='btn btn-danger' type='submit'  name='del' value='<?php echo $lang['delete'] ?>'>
        <?php }?>
        <?php echo formtoken::getField() ?>
    </form>

<script>
$(document).ready(function() {
if (<?php if ($server->usegsq == 1) echo 'false'; else echo 'true'; ?>) {
    $("#sq_details").hide();
};
$("#usegsq").change(function () {
  var selected_option = $('#usegsq').val();

  if (selected_option === '1') {
    $('#sq_details').show();
  }
  if (selected_option != '1') {
    $("#sq_details").hide();
  }
});
    $(".reveal").mousedown(function() {
        $(this).closest('.input-group').find('.pwd').attr('type', 'text');
    })
    .mouseup(function() {
           $(this).closest('.input-group').find('.pwd').attr('type', 'password');
    })
    .mouseout(function() {
            $(this).closest('.input-group').find('.pwd').attr('type', 'password');
    });
});
</script>

<?php
    }
} ?>