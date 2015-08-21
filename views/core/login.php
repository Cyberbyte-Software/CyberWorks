<?php
include("views/templates/head.php");

if (isset($_GET['setup'])) {
    if ($_GET['setup'] == 1) {
        $message = 'The database has now been setup';
    } elseif ($_GET['setup'] == 2) {
        $message = 'The database has now been upgraded';
    } else {
        $message = $_GET['setup'];
    }
}

if (isset($_POST['emailed']) && $settings['passreset']) {
    if (formtoken::validateToken($_POST)) {
        $to = $_POST['emailed'];
        $token = tokenGen(32);
        $sql = "SELECT  `user_id` FROM `users` WHERE  `user_email` =  '" . $to . "';";
        $result = $db_connection->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $sql = "UPDATE  `users` SET  `token` =  '" . $token . "' WHERE  `user_id` = '" . $row['user_id'] . "';";
            $result_of_query = $db_connection->query($sql);

            //Send the reset Email
            $subject = "Password Reset";
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
            //$headers .= "From: Password Reset <no-reply@cyberbyte.org.uk>\r\n";
            $headers .= "From: " . $settings['community'] . " Panel <" . $email . ">\r\n" . "Reply-To: " . $email . "\r\n";
            $msg = "Password reset<br/> token: " . $token . " <br/> url: <a href='" . $settings['url'] . "?token=" . $token . "&uID=" . $row['user_id'] . "'>" . $settings['url'] . "?token=" . $token . "&uID=" . $row['user_id'] . "</a>";
            $mail = mail($to, $subject, $msg, $headers);

            $message = "Your password has been reset please check your email";
            //$message = $settings['url']."?token=".$token."&uID=".$row['user_id']; // DEBUG ONLY
        }
    }
}

if (isset($_GET['token']) && isset($_GET['uID']) && $settings['passreset']) {
    if (isset($_POST['user_password_new']) && isset($_POST['user_password_repeat'])) {
        if ($_POST['user_password_new'] !== $_POST['user_password_repeat']) {
            $error = 'Password and password repeat are not the same';
        } else {
            $sql = "SELECT `user_id` FROM `users` WHERE  `user_id` = '" . $_GET['uID'] . "' AND `token` =  '" . $_GET['token'] . "';";
            $result_of_query = $db_connection->query($sql);
            if ($result_of_query->num_rows == 1) {
                $user_password_hash = password_hash($_POST['user_password_new'], PASSWORD_DEFAULT);
                $sql = "UPDATE `users` SET `user_password_hash` =  '" . $user_password_hash . "', `token` = '' WHERE  `user_id` = '" . $_GET['uID'] . "' AND `token` =  '" . $_GET['token'] . "';";
                $result_of_query = $db_connection->query($sql);
                $message = 'Your password been updated';
            } else {
                $error = 'User not found or token invalid';
            }
        }
    } else {
    $sql = "SELECT `user_id` FROM `users` WHERE  `user_id` = '" . $_GET['uID'] . "' AND `token` =  '" . $_GET['token'] . "';";
    $result_of_query = $db_connection->query($sql);
    if ($result_of_query->num_rows == 1) {
?>
   <script>$(document).ready(function () { $('#memberModal').modal('show'); });</script>

   <div class="modal fade" id="memberModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
    	<div class="modal-dialog">
    		<div  class="modal-content">
    			<div class="modal-header">
    				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    				<h4 class="modal-title">Password Reset</h4>
    			</div>
    			<form method="post" action="<?php echo $settings['url'] ?>?token=<?php echo $_GET['token']?>&uID=<?php echo $_GET['uID']?>">
    				<div class="modal-body">
                        <div class="form-group">
                            <p>Password:</p>
                            <input id="user_password_new" placeholder="Password"
                                   class=" form-control login_input" type="password"
                                   name="user_password_new" required autocorrect="off" autocapitalize="off" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <p>Repeat Password:</p>
                            <input id="user_password_repeat" placeholder="Repeat Password" class=" form-control login_input" type="password" name="user_password_repeat" required autocorrect="off" autocapitalize="off" autocomplete="off">
                        </div>
    				</div>
    				<div class="modal-footer centered">
    					<button data-dismiss="modal" class="btn btn-theme04" type="button">Cancel</button>
    					<button class="btn btn-theme03" href="index" type="submit" name="pass">Reset Password</button>
    				</div>
    			</form>
    		</div>
    	</div>
    </div>
<?php
        } else {
            $error = 'User not found or token invalid';
            logAction($_POST['email'], ' ' . $lang['passreset'], 3);
        }
    }
}

?>

<body onload="getTime()">

<div class="container">
    <div class="col-lg-4 col-lg-offset-4">
        <div class="lock-screen">
            <?php
            if ($login->messages) {
                foreach ($login->messages as $message) {
                    echo '<div style="margin-top: 120px;" class="alert alert-info animated infinite bounce" role="alert">' . $message . '</div>';
                }
            } elseif ($login->errors) {
                foreach ($login->errors as $error) {
                    echo '<div style="margin-top: 120px;" class="alert alert-danger animated infinite bounce" role="alert">' . $error . '</div>';
                }
            } elseif (isset($message)) {
                echo '<div style="margin-top: 120px;" class="alert alert-info animated infinite bounce" role="alert">' . $message . '</div>';
            } elseif (isset($error)) {
                echo '<div style="margin-top: 120px;" class="alert alert-danger animated infinite bounce" role="alert">' . $error . '</div>';
            } else {
                echo '<div style="margin-top: 190px;"></div>';
            } ?>

            <div id="showtime"></div>
            <h2><a data-toggle="modal" href="#login"><i class="fa fa-lock"></i></a></h2>

            <h3>LOGIN</h3>
            <?php if (isset($settings['steamAPI']) && $settings['steamlogin'] == 'true' && isset($settings['steamdomain'])) {
                include 'classes/steamlogin.php';
            }
            if ($settings['passreset']) {
                echo '<a data-toggle="modal" href="#pass"> <span>Password Reset</span></a>';
            } ?>
            <div aria-hidden="true" aria-labelledby="login" role="dialog" tabindex="-1" id="login" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">LOGIN</h4>
                        </div>
                        <form method="post" action="<?php echo $settings['url'] ?>index">
                            <div class="modal-body">
                                <p class="centered"><img class="img-circle" width="80"src="<?php echo $settings['url'] ?>assets/img/profile/2.jpg">
                                </p>

                                <div class="login-wrap">
                                    <input type="text" id="login_input_username" class="form-control"
                                           placeholder="Username" name="user_name" required autofocus>
                                    <br>
                                    <input type="password" id="login_input_password" class="form-control"
                                           placeholder="Password" name="user_password" autocorrect="off"
                                           autocapitalize="off" required>
                                    <br>
                                    <?php if ($settings['allowLang'] == 'true') {
                                        if (isset($_COOKIE['lang'])) {
                                            $tempLang = $_COOKIE['lang'];
                                        } elseif (isset($_SESSION['lang'])) {
                                            $tempLang = $_SESSION['lang'];
                                        } else {
                                            $tempLang = 'en';
                                        }
                                        echo '<select id = "lang" name = "lang" class="form-control login_input" >';

                                        foreach ($settings['installedLanguage'] as $language) {
                                            echo '<option value = "' . $language[1] . '" ';
                                            if ($tempLang == $language[1]) {
                                                echo 'selected';
                                            }
                                            echo '> ' . $language[0] . '</option>';
                                        }
                                    echo '</select><br>';
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="modal-footer centered">
                                <button data-dismiss="modal" class="btn btn-theme04"
                                        type="button">Cancel</button>
                                <button class="btn btn-theme03" href="index" type="submit"
                                        name="login">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div aria-hidden="true" aria-labelledby="pass" role="dialog" tabindex="-1" id="pass" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">Password Reset</h4>
                        </div>
                        <form method="post" action="<?php echo $settings['url'] ?>index">
                            <?php echo formtoken::getField() ?>
                            <div class="modal-body">
                                <div class="alert alert-danger" role="alert"><strong>Warning</strong> reset password is insecure and will break your passwords</div>
                                <div class="login-wrap">
                                    <input type="text" id="emailed" class="form-control" placeholder="Email Address" name="emailed" required autofocus>
                                </div>
                            </div>
                            <div class="modal-footer centered">
                                <button data-dismiss="modal" class="btn btn-theme04"
                                        type="button">Cancel</button>
                                <button class="btn btn-theme03" href="index" type="submit"
                                        name="pass">Reset Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo $settings['url'] ?>assets/js/main.min.js"></script>
<script type="text/javascript" src="<?php echo $settings['url'] ?>assets/js/jquery.backstretch.min.js"></script>
<script>
    $.backstretch([
        "<?php echo $settings['url'] ?>assets/img/bg/1.jpg",
        "<?php echo $settings['url'] ?>assets/img/bg/2.jpg",
        "<?php echo $settings['url'] ?>assets/img/bg/3.jpg",
        "<?php echo $settings['url'] ?>assets/img/bg/4.jpg",
        "<?php echo $settings['url'] ?>assets/img/bg/5.jpg"
    ], {duration: 10000, fade: 800});
</script>
<script>
    function getTime() {
        var today = new Date();
        var h = today.getHours();
        var m = today.getMinutes();
        var s = today.getSeconds();
        m = checkTime(m);
        s = checkTime(s);
        document.getElementById('showtime').innerHTML = h + ":" + m + ":" + s;
        t = setTimeout(function () {
            getTime()
        }, 500);
    }

    function checkTime(i) {
        if (i < 10) {
            i = "0" + i;
        }
        return i;
    }
</script>
</body>
</html>
