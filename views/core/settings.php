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
            $settings['allowLang'] = $_POST['allowLang'];
            $settings['wanted'] = $_POST['wanted'];
            $settings['news'] = $_POST['news'];
            $settings['register'] = $_POST['register'];
            $settings['steamlogin'] = $_POST['steamlogin'];
            $settings['vacTest'] = $_POST['vacTest'];
            $settings['performance'] = $_POST['performance'];
            $settings['notifications'] = $_POST['notifications'];
            $settings['colour'] = $_POST['colour'];

            $settings['2factor'] = $_POST['2factor'];
            $settings['force2factor'] = $_POST['force2factor'];
            $settings['gravatar'] = $_POST['gravatar'];

            $settings['maxLevels']['cop'] = $_POST['max_cop'];
            $settings['maxLevels']['medic'] = $_POST['max_medic'];
            $settings['maxLevels']['admin'] = $_POST['max_admin'];
            $settings['maxLevels']['donator'] = $_POST['max_donator'];

            file_put_contents('config/settings.php', '<?php return ' . var_export($settings, true) . ';');
            var_dump($settings['allowLang']);
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
<style>
.colorpicker-saturation{float:left;width:100px;height:100px;cursor:crosshair;background-image:url("<?php echo $settings['url']; ?>assets/img/picker/saturation.png")}.colorpicker-saturation i{position:absolute;top:0;left:0;display:block;width:5px;height:5px;margin:-4px 0 0 -4px;border:1px solid #000;-webkit-border-radius:5px;-moz-border-radius:5px;border-radius:5px}.colorpicker-saturation i b{display:block;width:5px;height:5px;border:1px solid #fff;-webkit-border-radius:5px;-moz-border-radius:5px;border-radius:5px}.colorpicker-hue,.colorpicker-alpha{float:left;width:15px;height:100px;margin-bottom:4px;margin-left:4px;cursor:row-resize}.colorpicker-hue i,.colorpicker-alpha i{position:absolute;top:0;left:0;display:block;width:100%;height:1px;margin-top:-1px;background:#000;border-top:1px solid #fff}.colorpicker-hue{background-image:url("<?php echo $settings['url']; ?>assets/img/picker/hue.png")}.colorpicker-alpha{display:none;background-image:url("<?php echo $settings['url']; ?>assets/img/picker/alpha.png")}.colorpicker-saturation,.colorpicker-hue,.colorpicker-alpha{background-size:contain}.colorpicker{top:0;left:0;z-index:2500;min-width:130px;padding:4px;margin-top:1px;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px;*zoom:1}.colorpicker:before,.colorpicker:after{display:table;line-height:0;content:""}.colorpicker:after{clear:both}.colorpicker:before{position:absolute;top:-7px;left:6px;display:inline-block;border-right:7px solid transparent;border-bottom:7px solid #ccc;border-left:7px solid transparent;border-bottom-color:rgba(0,0,0,0.2);content:''}.colorpicker:after{position:absolute;top:-6px;left:7px;display:inline-block;border-right:6px solid transparent;border-bottom:6px solid #fff;border-left:6px solid transparent;content:''}.colorpicker div{position:relative}.colorpicker.colorpicker-with-alpha{min-width:140px}.colorpicker.colorpicker-with-alpha .colorpicker-alpha{display:block}.colorpicker-color{height:10px;margin-top:5px;clear:both;background-image:url("<?php echo $settings['url']; ?>assets/img/picker/alpha.png");background-position:0 100%}.colorpicker-color div{height:10px}.colorpicker-selectors{display:none;height:10px;margin-top:5px;clear:both}.colorpicker-selectors i{float:left;width:10px;height:10px;cursor:pointer}.colorpicker-selectors i+i{margin-left:3px}.colorpicker-element .input-group-addon i,.colorpicker-element .add-on i{display:inline-block;width:16px;height:16px;vertical-align:text-top;cursor:pointer}.colorpicker.colorpicker-inline{position:relative;z-index:auto;display:inline-block;float:none}.colorpicker.colorpicker-horizontal{width:110px;height:auto;min-width:110px}.colorpicker.colorpicker-horizontal .colorpicker-saturation{margin-bottom:4px}.colorpicker.colorpicker-horizontal .colorpicker-color{width:100px}.colorpicker.colorpicker-horizontal .colorpicker-hue,.colorpicker.colorpicker-horizontal .colorpicker-alpha{float:left;width:100px;height:15px;margin-bottom:4px;margin-left:0;cursor:col-resize}.colorpicker.colorpicker-horizontal .colorpicker-hue i,.colorpicker.colorpicker-horizontal .colorpicker-alpha i{position:absolute;top:0;left:0;display:block;width:1px;height:15px;margin-top:0;background:#fff;border:0}.colorpicker.colorpicker-horizontal .colorpicker-hue{background-image:url("<?php echo $settings['url']; ?>assets/img/picker/hue-horizontal.png")}.colorpicker.colorpicker-horizontal .colorpicker-alpha{background-image:url("<?php echo $settings['url']; ?>assets/img/picker/alpha-horizontal.png")}.colorpicker.colorpicker-hidden{display:none}.colorpicker.colorpicker-visible{display:block}.colorpicker-inline.colorpicker-visible{display:inline-block}.colorpicker-right:before{right:6px;left:auto}.colorpicker-right:after{right:7px;left:auto}
</style>
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
    <div class='form-group'>
        <label for="language"><?php echo $lang['language'] ?>: </label>
        <select name="language" id="language" class="form-control">
            <?php
            foreach ($settings['installedLanguage'] as $language) {
                echo '<option value = "' . $language[1] . '" ';
                if ($settings['language'] == $language[1]) echo 'selected';
                echo '> ' . $language[0] . '</option>';
            } ?>
        </select>
    </div>

    <div class='form-group'>
        <label for="allowLang"><?php echo $lang['allowLang'] ?>: </label>
        <select name="allowLang" id="allowLang" class="form-control">
            <option value="true"<?php echo select(true, $settings['allowLang']) . '>' . $lang['yes'] ?></option>
            <option value="false"<?php echo select(false, $settings['allowLang']) . '>' . $lang['no'] ?></option>
        </select>
    </div>

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

    <h3>API's</h3>
    <div class='form-group'><div class='input-group'><label for='steamAPI'>Steam API <?php echo $lang['key'] ?>: </label><input class='form-control pwd' id='steamAPI' type='password' name='steamAPI' value='<?php echo $settings['steamAPI']?>'>
    <span class='input-group-btn'><button style='margin-top: 21px;' class='btn btn-default reveal' type='button'><i class='fa fa-eye-slash'></i></button></span></div>

    <div class='form-group'><label for='steamDomain'>Steam Domain: </label><input class='form-control' id='steamDomain' type='text' name='steamDomain' value='<?php echo $settings['steamdomain']?>'></div>

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

    <div class='form-group'>
        <label for="colour"><?php echo $lang['colour'] ?>: </label>
        <div class="input-group colourPicker">
            <input type="text" name="colour" id="colour" value="<?php echo $settings['colour']; ?>" class="form-control" />
            <span class="input-group-addon"><i></i></span>
        </div>
    </div>

    <div class='form-group'><label for="communityBansTest"><?php echo $lang['useCommunityBans'] ?>: </label>
        <select name="communityBansTest" id="communityBansTest" class="form-control">
        <option value="true"
        <?php echo select(true, $settings['communityBansTest']) . '>' . $lang['yes'] ?></option>
        <option value="false"
        <?php echo select(false, $settings['communityBansTest']) . '>' . $lang['no'] ?></option>
    </select></div>

    <div class='form-group'><label for="2factor"><?php echo $lang['use2factor'] ?>: </label>
        <select name="2factor" id="2factor" class="form-control">
        <option value="true"
        <?php echo select(true, $settings['2factor']) . '>' . $lang['yes'] ?></option>
        <option value="false"
        <?php echo select(false, $settings['2factor']) . '>' . $lang['no'] ?></option>
    </select></div>

    <div class='form-group'><label for="force2factor"><?php echo $lang['force2factor'] ?>: </label>
        <select name="force2factor" id="force2factor" class="form-control">
        <option value="none"
        <?php echo select("none", $settings['force2factor']) . '>' . $lang['none'] ?></option>
        <option value="steam"
        <?php echo select("steam", $settings['force2factor']) . '>' . $lang['steam'] ?></option>
        <option value="all"
        <?php echo select("all", $settings['force2factor']) . '>' . $lang['all'] ?></option>
    </select></div>

    <div class='form-group'><label for="gravatar"><?php echo $lang['useGravatar'] ?>: </label>
        <select name="gravatar" id="gravatar" class="form-control">
        <option value="true"
        <?php echo select(true, $settings['gravatar']) . '>' . $lang['yes'] ?></option>
        <option value="false"
        <?php echo select(false, $settings['gravatar']) . '>' . $lang['no'] ?></option>
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

<script src="<?php echo $settings['url'] ?>assets/js/colorpicker.min.js"></script>

<script>
$(document).ready(function() {
    $('.colourPicker').colorpicker();
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