<?php
if (isset($GuestReg)) {
    if ($GuestReg->errors) {
        foreach ($GuestReg->errors as $error) {
            message($message);
        }
    }
    if ($GuestReg->messages) {
        foreach ($GuestReg->messages as $message) {
            message($message);
        }
    }
}
?>

<div id="login-page">
    <div class="col-lg-12 container">
        <form method="post" action="<?php echo $settings['url'] ?>register" name="registerform" id="registerform">
            <h2 class="form-login-heading">New User</h2>

            <div class="form-group">
                <p>Username:</p>
                <input id="user_name" type="text" class="form-control"
                       placeholder="Username (only letters and numbers, 2 to 30 characters)" autofocus
                       name="user_name" <?php if (isset($_POST['user_name'])) echo 'value="' . $_POST['user_name'] . '"'; ?> required>
            </div>

            <div class="form-group">
                <p>Email Address:</p>
                <input id="user_email" placeholder="User's email" class=" form-control" type="email"
                       name="user_email" <?php if (isset($_POST['user_name'])) echo 'value="' . $_POST['user_name'] . '"'; ?> required/>
            </div>
            <div class="form-group">
                <p>Password:</p>
                <input id="user_password_new" placeholder="Password"
                       class=" form-control login_input" type="password"
                       name="user_password_new" required autocorrect="off" autocapitalize="off" autocomplete="off">
            </div>
            <div class="form-group">
                <p>Repeat password:</p>
                <input id="user_password_repeat" placeholder="Repeat password" class=" form-control login_input"
                       type="password"
                       name="user_password_repeat" required autocorrect="off" autocapitalize="off" autocomplete="off">
            </div>
            <div class="form-group">
                <p>Profile Picture:</p>
                <select class=" form-control" name="profile_pic">
                    <?php for ($icon = 1; $icon <= 6; $icon++) {
                        echo '<option value="' . $icon . '" ';
                        if (isset($_POST['user_name'])) if ($icon == $_POST['user_name']) echo 'selected';
                        echo '>' . $settings['names'][$icon] . '</option>';
                    } ?>
                </select>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-theme btn-block" name="register" value="Add New User"/>
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
            });
    });
</script>