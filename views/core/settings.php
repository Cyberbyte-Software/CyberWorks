<?php
if (isset($_POST['db_host'])) {
    if (formtoken::validateToken($_POST)) {
        if ($_SESSION['permissions']['super_admin']) {
            $settings['language'] = $_POST['language'];
            $settings['items'] = $_POST['items'];
            $settings['steamAPI'] = $_POST['steamAPI'];
            $settings['communityBansAPI'] = $_POST['communityBansAPI'];
            $settings['steamdomain'] = $_POST['steamDomain'];
            $settings['community'] = $_POST['community'];

            $settings['refesh'] = $_POST['refresh'];
            $settings['lifeVersion'] = intval($_POST['lifeVersion']);

            $settings['allowLang'] = filter_var($_POST['allowLang'], FILTER_VALIDATE_BOOLEAN);
            $settings['wanted'] = filter_var($_POST['wanted'], FILTER_VALIDATE_BOOLEAN);
            $settings['news'] = filter_var($_POST['news'], FILTER_VALIDATE_BOOLEAN);
            $settings['register'] =  filter_var($_POST['register'], FILTER_VALIDATE_BOOLEAN);
            $settings['steamlogin'] =  filter_var($_POST['steamlogin'], FILTER_VALIDATE_BOOLEAN);
            $settings['vacTest'] = filter_var($_POST['vacTest'], FILTER_VALIDATE_BOOLEAN);
            $settings['performance'] =  filter_var($_POST['performance'], FILTER_VALIDATE_BOOLEAN);
            $settings['notifications'] =  filter_var($_POST['notifications'], FILTER_VALIDATE_BOOLEAN);
            
            $settings['2factor'] = false;
            $settings['gravatar'] = false;
            $settings['force2factor'] = 'none';

            $settings['maxLevels']['cop'] = $_POST['max_cop'];
            $settings['maxLevels']['medic'] = $_POST['max_medic'];
            $settings['maxLevels']['admin'] = $_POST['max_admin'];
            $settings['maxLevels']['donator'] = $_POST['max_donator'];

            
            file_put_contents('config/settings.php', '<?php return ' . var_export($settings, true) . ';');
        } else {
            logAction($_SESSION['user_name'], $lang['failedUpdate'] . ' ' . $lang['server'] . ' ' . $lang['settings'], 3);
        }
    } else {
        message($lang['expired']);
    }
}
?>

<div class="row">
    <div class="col-lg-8">
        <h1 class="page-header">
            <?php echo $lang['settings']; ?>
        </h1>
    </div>
</div>
<div id='text'></div>
<div class="col-md-6">
    <form method='post' action='settings' id='settings' name='settingsEdit'>
        <?php
        echo formtoken::getField();
        echo "<h3>" . $lang['database'] . "</h3>";
        echo "<div class='form-group'><label for='db_host'>" . $lang['database'] . " " . $lang['host'] . ": </label><input class='form-control' id='db_host' type='text' name='db_host' value='" . decrypt($settings['db']['host']) . "' readonly></div>";
        echo "<div class='form-group'><label for='db_user'>" . $lang['database'] . " " . $lang['user'] . ": </label><input class='form-control' id='db_user' type='text' name='db_user' value='" . decrypt($settings['db']['user']) . "' readonly></div>";
        echo "<div class='form-group'><div class='input-group'><label for='db_pass'>" . $lang['database'] . " " . $lang['password'] . ": </label><input class='form-control pwd' id='db_pass' type='password' name='db_pass' value='" . decrypt($settings['db']['pass']) . "' readonly>";
        echo "<span class='input-group-btn'><button style='margin-top: 21px; background-color: #eee;' ";
        echo "class='btn btn-default reveal' type='button'><i class='fa fa-eye-slash'></i></button></span></div></div>";
        echo "<div class='form-group'><label for='db_name'>" . $lang['database'] . " " . $lang['name'] . ": </label><input class='form-control' id='db_name' type='text' name='db_name' value='" . decrypt($settings['db']['name']) . "' readonly></div>";
        ?>

    <h3><?php echo $lang['panel']?></h3>
    <div class='form-group'><label for='max_cop'> <?php echo $lang['community'] ?>: </label>
    <input class='form-control' id='community' type='text' name='community' value='<?php echo $settings['community']; ?>'></div>

    <?php if (count($settings['installedLanguage']) > 1) { ?>
    <div class='form-group'><label for="language"><?php echo $lang['language'] ?>: </label>
        <select name="language" id="language" class="form-control">
            <?php
            foreach ($settings['installedLanguage'] as $language) {
                echo '<option value = "' . $language[1] . '" ';
                if ($settings['language'] == $language[1]) echo 'selected';
                echo '> ' . $language[0] . '</option>';
            } ?>
        </select></div>

    <div class='form-group'><label for="allowLang"><?php echo $lang['allowLang'] ?>: </label>
        <select name="allowLang" id="allowLang" class="form-control">
            <option value="true"<?php echo select(true, $settings['allowLang']) . '>' . $lang['yes'] ?></option>
            <option value="false"<?php echo select(false, $settings['allowLang']) . '>' . $lang['no'] ?></option>
        </select></div>
        
    <?php } ?>
        
    <div class='form-group'><label for="performance"><?php echo $lang['performance'] ?>: </label>
        <select name="performance" id="performance" class="form-control">
            <option value="true"
            <?php echo select(true, $settings['performance']) . '>' . $lang['yes'] ?></option>
            <option value="false"
            <?php echo select(false, $settings['performance']) . '>' . $lang['no'] ?></option>
        </select></div>

    <div class='form-group'><label for="items"><?php echo $lang['itemsPP'] ?></label>
        <select id='items' name='items' class="form-control">
            <option value="5" <?php echo select('5', $_SESSION['items']) ?> >5</option>
            <option value="10" <?php echo select('10', $_SESSION['items']) ?> >10</option>
            <option value="15" <?php echo select('15', $_SESSION['items']) ?> >15</option>
            <option value="25" <?php echo select('25', $_SESSION['items']) ?> >25</option>
            <option value="50" <?php echo select('50', $_SESSION['items']) ?> >50</option>
        </select></div>

    <div class='form-group'><label for="register"><?php echo $lang['registration'] ?>: </label>
        <select name="register" id="register" class="form-control">
        <option value="true"<?php echo select(true, $settings['register']) . '>' . $lang['yes'] ?></option>
        <option value="false"<?php echo select(false, $settings['register']) . '>' . $lang['no'] ?></option>
    </select>
    </div>

    <div class='form-group'><label for="news"><?php echo $lang['useNews'] ?>: </label>
        <select name="news" id="news" class="form-control">
        <option value="true"<?php echo select(true, $settings['news']) . '>' . $lang['yes'] ?></option>
        <option value="false"<?php echo select(false, $settings['news']) . '>' . $lang['no'] ?></option>
    </select></div>

    <div class='form-group'><label for="notifications">Notifications: </label>
        <select name="notifications" id="notifications" class="form-control">
        <option value="true"<?php echo select(true, $settings['notifications']) . '>' . $lang['yes'] ?></option>
        <option value="false"<?php echo select(false, $settings['notifications']) . '>' . $lang['no'] ?></option>
    </select></div>
    <?php echo "<div class='form-group'><label for='refresh'>" . $lang['refresh'] . ": </label><input class='form-control' id='refresh' type='number' name='refresh' value='" . $settings['refresh'] . "'></div>"; ?>
</div>
<div class="col-md-6">
    <h3>Altis Life</h3>
    <?php
    echo "<div class='form-group'><label for='max_cop'>" . $lang['max'] . " " . $lang['cop'] . " " . $lang['level'] . ": </label><input class='form-control' id='max_cop' type='number' name='max_cop' value='" . $settings['maxLevels']['cop'] . "'></div>";
    echo "<div class='form-group'><label for='max_medic'>" . $lang['max'] . " " . $lang['medic'] . " " . $lang['level'] . ": </label><input class='form-control' id='max_medic' type='number' name='max_medic' value='" . $settings['maxLevels']['medic'] . "'></div>";
    echo "<div class='form-group'><label for='max_admin'>" . $lang['max'] . " " . $lang['admin'] . " " . $lang['level'] . ": </label><input class='form-control' id='max_admin' type='number' name='max_admin' value='" . $settings['maxLevels']['admin'] . "'></div>";
    echo "<div class='form-group'><label for='max_donator'>" . $lang['max'] . " " . $lang['donator'] . " " . $lang['level'] . ": </label><input class='form-control' id='max_donator' type='number' name='max_donator' value='" . $settings['maxLevels']['donator'] . "'></div>";
    ?>
    <div class='form-group'><label for="wanted"><?php echo $lang['wanted'] ?>: </label>
        <select name="wanted" id="wanted" class="form-control">
            <option value="true"
            <?php echo select(true, $settings['wanted']) . '>' . $lang['yes'] ?></option>
            <option value="false"
            <?php echo select(false, $settings['wanted']) . '>' . $lang['no'] ?></option>
        </select></div>

    <div class='form-group'>
        <label for="lifeVersion">Altis Life Version: </label>
        <select name="lifeVersion" id="lifeVersion" class="form-control">
            <option value="3" <?php echo select('3', $settings['lifeVersion']) ?> >3.X.X.X</option>
            <option value="4" <?php echo select('4', $settings['lifeVersion']) ?> >4.X</option>
        </select></div>
    <h3>API's</h3>
    <div class='form-group'>
        <div class='input-group'>
            <label for='steamAPI'>Steam API <?php echo $lang['key'] ?>: </label>
            <input class='form-control pwd' id='steamAPI' type='password' name='steamAPI' value='<?php echo $settings['steamAPI']?>'>
            <span class='input-group-btn'>
                <button style='margin-top: 21px;' class='btn btn-default reveal' type='button'><i class='fa fa-eye-slash'></i></button>
            </span>
        </div>
    </div>
    
    <div class='form-group'>
        <label for='steamDomain'>Steam Domain: </label>
        <input class='form-control' id='steamDomain' type='text' name='steamDomain' value='<?php echo $settings['steamdomain']?>'>
    </div>

    <div class='form-group'><label for="steamlogin"><?php echo $lang['steamlogin'] ?>: </label>
        <select name="steamlogin" id="steamlogin" class="form-control">
        <option value="true"
        <?php echo select(true, $settings['steamlogin']) . '>' . $lang['yes'] ?></option>
        <option value="false"
        <?php echo select(false, $settings['steamlogin']) . '>' . $lang['no'] ?></option>
    </select></div>

    <div class='form-group'><label for="vacTest"><?php echo $lang['useVAC'] ?>: </label>
        <select name="vacTest" id="vacTest" class="form-control">
        <option value="true"
        <?php echo select(true, $settings['vacTest']) . '>' . $lang['yes'] ?></option>
        <option value="false"
        <?php echo select(false, $settings['vacTest']) . '>' . $lang['no'] ?></option>
    </select></div>

    <div class='form-group'><label for="communityBansTest"><?php echo $lang['useCommunityBans'] ?>: </label>
        <select name="communityBansTest" id="communityBansTest" class="form-control">
        <option value="true"
        <?php echo select(true, $settings['communityBansTest']) . '>' . $lang['yes'] ?></option>
        <option value="false"
        <?php echo select(false, $settings['communityBansTest']) . '>' . $lang['no'] ?></option>
    </select></div>

    <div class='form-group'><div class='input-group'><label for='communityBansAPI'>Community Bans API <?php echo $lang['key'] ?>: </label><input class='form-control pwd' id='communityBansAPI' type='password' name='communityBansAPI' value='<?php echo $settings['communityBansAPI']?>'>
    <span class='input-group-btn'><button style='margin-top: 21px;' class='btn btn-default reveal' type='button'><i class='fa fa-eye-slash'></i></button></span></div>

    <?php
    if (!isset($pluginSettings)) echo '<h3>' . $lang['plugin'] . ' ' . $lang['settings'] . '</h3>';
    foreach ($settings['plugins'] as &$plugin) {
        if (file_exists("plugins/" . $plugin . "/settings.php")) {
            include("plugins/" . $plugin . "/settings.php");
        }
    }
    ?>

    <br><input class='btn btn-primary' type='submit'  name='edit' value='<?php echo $lang['subChange'] ?>'>
</div>
</form>

<script>
$(document).ready(function() {
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