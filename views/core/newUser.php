<?php
if (isset($registration)) {
    if ($registration->errors) {
        foreach ($registration->errors as $error) {
            message($error);
        }
    }
    if ($registration->messages) {
        foreach ($registration->messages as $message) {
            message($message);
        }
    }
}
?>

<div id="login-page">
    <div class="col-lg-12 container">
        <form method="post" action="<?php echo $settings['url'] ?>newUser" name="registerform" id="registerform">
            <h2 class="form-login-heading"><?php echo $lang['navNewUser'] ?></h2>

            <div class="form-group">
                <p><?php echo $lang['username'] ?>:</p>
                <input id="user_name" type="text" class="form-control"
                       placeholder="<?php echo $lang['username'] ?>" autofocus
                       name="user_name" <?php if (isset($_POST['user_name'])) echo 'value="' . $_POST['user_name'] . '"'; ?> required>
            </div>
            <div class="form-group">
                <p><?php echo $lang['player'] . " " . $lang['id'] ?>:</p>
                <input id="player_id" class="form-control" placeholder="<?php echo $lang['playerID'] ?>" type="number" name="player_id">
                <p id='steam'></p>
            </div>
            <div class="form-group">
                <p><?php echo $lang['emailAdd'] ?>:</p>
                <input id="user_email" placeholder="<?php echo $lang['emailAdd'] ?>" class=" form-control" type="email"
                       name="user_email" <?php if (isset($_POST['user_name'])) echo 'value="' . $_POST['user_name'] . '"'; ?> required/>
            </div>
            <div class="form-group">
                <p><?php echo $lang['password'] ?>:</p>
                <input id="user_password_new" placeholder="<?php echo $lang['password'] ?>"
                       class=" form-control login_input" type="password"
                       name="user_password_new" required autocorrect="off" autocapitalize="off" autocomplete="off">
            </div>
            <div class="form-group">
                <p><?php echo $lang['repeat'] . ' ' . $lang['password']?>:</p>
                <input id="user_password_repeat" placeholder="<?php echo $lang['repeat'] . ' ' . $lang['password']?>" class=" form-control login_input"
                       type="password"
                       name="user_password_repeat" required autocorrect="off" autocapitalize="off" autocomplete="off">
            </div>

            <div class="form-group">
                <p><?php echo $lang['level'] ?>:</p>
                <select class="form-control" name="user_lvl">
                    <?php for ($rank = 1; $rank <= $_SESSION['user_level']; $rank++) {
                        echo '<option value="' . $rank . '"';
                        if (isset($_POST['user_lvl'])) if ($rank == $_POST['user_lvl']) echo 'selected';
                        echo '>' . $settings['ranks'][$rank] . '</option>';
                    } ?>
                </select>
            </div>

            <div class="form-group">
                <p><?php echo $lang['picture']?>:</p>
                <select class=" form-control" name="profile_pic">
                    <?php for ($icon = 1; $icon <= 6; $icon++) {
                        echo '<option value="' . $icon . '" ';
                        if (isset($_POST['user_name'])) if ($icon == $_POST['user_name']) echo 'selected';
                        echo '>' . $settings['names'][$icon] . '</option>';
                    } ?>
                </select>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-theme btn-block" name="register" value="<?php echo $lang['navNewUser'] ?>"/>
            </div>
    </div>
    </form>
</div>
</div>

<script>
    $(document).ready(function () {
        $('#registerform')
            .formValidation({
                framework: 'bootstrap',
                icon: {
                    valid: 'fa fa-check',
                    invalid: 'fa fa-times',
                    validating: 'fa fa-refresh'
                },
                fields: {
                    player_id: {
                        validators: {
                            stringLength: {
                                min: 15,
                                max: 25
                            },
                            remote: {
                                url: '<?php echo $settings['url']; ?>validators/pid.php',
                                type: 'POST',
                                message: '<?php echo $lang['failPID'] ?>'
                            }
                        }
                    },
                    user_name: {
                        validators: {
                            notEmpty: {},
                            stringLength: {
                                min: 3,
                                max: 30
                            },
                            regexp: {
                                regexp: /^[a-zA-Z0-9_]+$/,
                            },
                            remote: {
                                message: 'The username is not available',
                                url: '<?php echo $settings['url']; ?>validators/newUser.php',
                                data: {
                                    type: 'username'
                                },
                                type: 'POST'
                            }
                        }
                    },
                    user_email: {
                        validators: {
                            notEmpty: {},
                            stringLength: {
                                min: 3,
                                max: 64
                            },
                            remote: {
                                message: 'The email is not available',
                                url: '<?php echo $settings['url']; ?>validators/newUser.php',
                                data: {
                                    type: 'email'
                                },
                                type: 'POST'
                            }
                        }
                    },
                    user_password_new: {
                        validators: {
                            notEmpty: {},
                            stringLength: {
                                min: 6,
                                max: 64
                            }
                        }
                    },
                    user_password_repeat: {
                        validators: {
                            notEmpty: {},
                            identical: {
                                field: 'user_password_new',
                                message: '<?php echo $lang['matchPass']; ?>'
                            }
                        }
                    }
                }
            })
            .on('success.validator.fv', function(e, data) {
            if (data.field === 'player_id' && data.validator === 'remote') {
                document.getElementById("steam").innerHTML = '<?php echo $lang['steamFound']; ?>' + ' <a href="' + data.result.url + '" target="_blank">' + data.result.name + '</a>';
            }
            });
    });
</script>