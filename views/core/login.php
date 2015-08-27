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
            }?>
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
