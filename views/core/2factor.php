<?php
if (isset($_GET['backup']) && $_SESSION['2factor'] == 2) {
    $backup = $gauth->createSecret(8);
    $sql = "UPDATE `users` SET `backup`='" . $backup . "' WHERE `user_id` = '" . $_SESSION['user_id'] . "';";
    $db_connection->query($sql);
    message($lang['2factorBackup1'] . ' <b>' . $backup . '</b> ' . $lang['2factorBackup2']);
} elseif (isset($_GET['revokeBackup']) && $_SESSION['2factor'] == 2) {
    $sql = "UPDATE `users` SET `backup`=NULL WHERE `user_id` = '" . $_SESSION['user_id'] . "';";
    $db_connection->query($sql);
    message($lang['2factorBackupRevoke']);
} elseif (isset($_GET['revoke']) && $_SESSION['2factor'] == 2) {
    $sql = "UPDATE `users` SET `backup`=NULL,`twoFactor`=NULL,`token`=NULL WHERE `user_id` = '" . $_SESSION['user_id'] . "';";
    $db_connection->query($sql);
    unset($_COOKIE['token']);
    setcookie('token', '', time() - 3600, '/');
    $_SESSION['2factor'] = 0;
    message($lang['2factorRevoke']);
} elseif (isset($_GET['remember']) && $_SESSION['2factor'] == 2 && !isset($_COOKIE['token'])) {
    $sql = "SELECT `token` FROM `users` WHERE `user_id` = '" . $_SESSION['user_id'] . "';";
    $token = $db_connection->query($sql)->fetch_object()->token;
    if (empty($token)) {
        $key = $gauth->createSecret(32);
        $sql = "UPDATE `users` SET `token`='" . encrypt($key) . "' WHERE `user_id` = '" . $_SESSION['user_id'] . "';";
        $db_connection->query($sql);
        setcookie('token', $key, time() + 5184000, "/");
    } else {
        setcookie('token', decrypt($token), time() + 5184000, "/");
    }
    message($lang['2factorRemember']);
} elseif (isset($_GET['revokeDevice']) && $_SESSION['2factor'] == 2 && isset($_COOKIE['token'])) {
    unset($_COOKIE['token']);
    setcookie('token', '', time() - 3600, '/');
    message($lang['2factorDeviceRevoke']);
} elseif (isset($_GET['revokeToken']) && $_SESSION['2factor'] == 2) {
    if (isset($_COOKIE['token'])) unset($_COOKIE['token']);
    $sql = "UPDATE `users` SET `token`='NULL' WHERE `user_id` = '" . $_SESSION['user_id'] . "';";
    $db_connection->query($sql);
    message($lang['2factorTokenRevoke']);
} elseif (isset($_POST['testCode']) && isset($_POST['secret']) && $_SESSION['2factor'] == 0) {
    if ($gauth->verifyCode($_POST['secret'], $_POST['testCode'])) {
    $sql = "UPDATE `users` SET `twoFactor`='" . $_POST['secret'] . "' WHERE `user_id` = '" . $_SESSION['user_id'] . "';";
    $db_connection->query($sql);
    $_SESSION['2factor'] = 2;
    message($lang['2factor1']);
    } else message($lang['2factor2']);
} ?>
<div class="row">
    <div class="col-lg-8">
        <h1 class="page-header">
            <?php echo $lang['2factor']; ?>
        </h1>
    </div>
</div>
<?php
if ($_SESSION['2factor'] == 1 || $_SESSION['2factor'] == 5 || $_SESSION['2factor'] == 3) {
    if ($_SESSION['2factor'] == 3) message($lang['2factorError2']);
    echo '<form method="post" action="' . $currentPage . '" class="form-inline">
  <label for="code">'.$lang['2factorSetup3'] . '<div class="form-group"></label><input class="form-control" id="code" type="text" name="code"></div>
  <button type="submit" class="btn btn-default">Verify</button></form>';
} elseif ($_SESSION['2factor'] == 2) {
    echo $lang['2factor1'] . '<br><br><a href="?backup" class="btn btn-default">';
    $sql = "SELECT `backup`,`token` FROM `users` WHERE `user_id` = '" . $_SESSION['user_id'] . "';";
    $twoFactor = $db_connection->query($sql)->fetch_object();
    if (isset($twoFactor->backup)) echo $lang['new'] . ' ' . $lang['2factor3']; else echo $lang['2factor3'];
    echo '</a>';
    echo '<a href="?revoke" style="margin-left: 5px;" class="btn btn-danger">' . $lang['2factor4'] . '</a>';
    if (isset($twoFactor->token)) echo '<a href="?revokeToken" style="margin-left: 5px;" class="btn btn-danger">' . $lang['2factorTokenRevoke'] . '</a>';
    if (isset($_COOKIE['token'])) echo '<a href="?revokeDevice" style="margin-left: 5px;" class="btn btn-danger">' . $lang['2factorRevokeRememberDevice'];
    else echo '<a href="?remember" style="margin-left: 5px;" class="btn btn-primary">' . $lang['2factorRememberDevice'];
    echo '</a>';

} elseif ($_SESSION['2factor'] == 0 || $_SESSION['2factor'] == 5) {
    if ($_SESSION['2factor'] == 5) {
        echo "<div class='alert alert-danger alert-dismissable'>";
        echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
        echo "<i class='fa fa-info-circle'></i> " . $lang['2factorForce'] . "</div></div>";
    }
    $secret = $gauth->createSecret();
    if (isset($settings['communityName'])) $name = urlencode(str_replace(' ', '', $settings['communityName']) . "CyberWorks");
    else $name = 'CyberWorks';
    echo '<div class="col-md-6">' . $lang['2factorSetup1'] . '<br>' . $lang['2factorSetup2'] . ' <b>' . $secret . '</b><br><form method="post" action="2factor" class="form-inline">
  <label for="testCode">'.$lang['2factorSetup3'] . '<div class="form-group"></label><input style="margin-left: 5px;" class="form-control" id="testCode" type="text" name="testCode"></div>
  <input type="hidden" id="secret" name="secret" value="'.$secret . '"><button type="submit" class="btn btn-default">Verify</button>
  </form></div><div class="col-md-6"><img src="'.$gauth->getQRCodeGoogleUrl($name, $secret) . '"></div>';
}