<?php
if (isset($_POST['email'])) {
    if (formtoken::validateToken($_POST)) {
        $email = $_POST['email'];
        $user_pic = $_POST['user_pic'];
        $pId = $_POST['player_id'];
        $_SESSION['user_profile'] = $user_pic;
        $sql = "UPDATE `users` SET `user_email`= '" . $email . "',`playerid`= '" . $pId . "', `user_profile`= '" . $user_pic . "'WHERE `user_id` = '" . $_SESSION['user_id'] . "' ";
        $result_of_query = $db_connection->query($sql);
    } else {
        message($lang['expired']);
    }
}
if (isset($_POST['user_password'])) {
    if (formtoken::validateToken($_POST)) {
        $sql = "SELECT `user_password_hash` FROM `users` WHERE `user_id` = '" . $_SESSION['user_id'] . "';";
        $result = $db_connection->query($sql)->fetch_object();
        if ($_POST['user_password'] == $_POST['user_password_again'] && password_verify($_POST['current_password'],$result->user_password_hash)) {
            $sql = "UPDATE `users` SET `user_password_hash`= '" . password_hash($_POST['user_password'], PASSWORD_DEFAULT) . "' WHERE `user_id` = '" . $_SESSION['user_id'] . "';";
            $result_of_query = $db_connection->query($sql);
            message($lang['passChanged']);
        }
    } else {
        message($lang['expired']);
    }
}

$sql = "SELECT * FROM `users` WHERE `user_name` ='" . $_SESSION['user_name'] . "';";
$profile = $db_connection->query($sql)->fetch_object();
?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            <?php echo $lang['navProfile']; ?>
            <small> <?php echo $lang['overview']; ?></small>
        </h1>
    </div>
</div>

<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title"><?php echo $_SESSION['user_name']; ?></h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <div  style="padding-top:4%;" class="col-md-3 col-lg-3 " align="center">
                <?php if(!isset($_SESSION['profile_link'])) {
                    if(isset($_SESSION['user_email']) && $settings['gravatar']) { ?>
                        <a href="<?php echo $settings['url'] . 'profile' ?>"> <img alt="User Pic" src="<?php get_gravatar($_SESSION['user_email'],64,'retro')  ?>" class="img-circle img-responsive"></a>
                    <?php } else {?>
                        <a href="<?php echo $settings['url'] . 'profile' ?>"> <img alt="User Pic" src="<?php echo $settings['url'] . 'assets/img/profile/' . $_SESSION['user_profile'] . '.jpg' ?>" class="img-circle img-responsive"></a>
                    <?php } ?>
                <?php } else { ?>
                    <img alt="User Pic" src="<?php echo $settings['url'] . 'assets/img/profile/' . $_SESSION['user_profile'] . '.jpg' ?>" class="img-circle img-responsive">
                <?php } ?>
            </div>

            <div class=" col-md-9 col-lg-9 ">
                <table class="table table-user-information">
                    <tbody>
                    <tr>
                        <td><?php echo $lang['rank'] ?>:</td>
                        <td><?php echo $settings['ranks'][$profile->user_level] . " (" . $profile->user_level . ")" ?></td>
                    </tr>
                    <form method="post" action="profile" name="profileEdit" id="profileEdit">
                        <?php echo formtoken::getField(); ?>
                        <tr>
                            <td><?php echo $lang['playerID'] ?>:</td>
                            <td><input class='form-control' id='player_id' type='number' name='player_id' value='<?php echo $profile->playerid ?>'></td>
                        </tr>
                        <tr>
                            <td><?php echo $lang['emailAdd'] ?>:</td>
                            <td><input class='form-control' id='email' type='email' name='email' value='<?php echo $profile->user_email ?>'></td>
                        </tr>
                        <tr>
                            <td>Profile Picture</td>
                            <td>
                                <select id='user_pic' name='user_pic' class='form-control'>";
                                    <?php
                                    for ($icon = 1; $icon < 6; $icon++) {
                                        echo '<option value="' . $icon . '" ' . select($icon, $profile->user_profile) . '>' . $settings['names'][$icon] . '</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $lang['current'] . " " . $lang['password'] ?></td>
                            <td>
                                <input type="password" id="current_password" name="current_password" class="form-control" autocorrect="off" autocapitalize="off" autocomplete="off">
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo "New " . $lang['password'] ?></td>
                            <td>
                                <input type="password" id="user_password" name="user_password" class="form-control" autocorrect="off" autocapitalize="off" autocomplete="off">
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $lang['repeat'] . " New " . $lang['password'] ?></td>
                            <td>
                                <input type="password" id="user_password_again" name="user_password_again" autocorrect="off" class="form-control" autocapitalize="off" autocomplete="off">
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input class='btn btn-sm btn-success pull-right' type='submit'  name='edit' value='<?php echo $lang['subChange']?>'>
                            </td>
                        </tr>
                    </form>
                    </tbody>
                </table>
                <span class="pull-right">
                     <?php
                     $sql = "SELECT `uid`,`playerid` FROM `players` WHERE `playerid` = '" . $profile->playerid . "' ";
                     $result = $db_connection->query($sql);

                     if ($result->num_rows > 0) {
                         ?> <a class="btn btn-sm btn-primary" href="<?php echo $settings['url'] . 'editPlayer/' . $result->fetch_object()->uid ?> ">  My Player</a> <?php
                     }
                     ?>
                </span>
            </div>
        </div>
    </div>
    <div class="panel-footer">

    </div>

</div>
<script>
    $(document).ready(function () {
        $('#profileEdit')
            .formValidation({
                framework: 'bootstrap',
                icon: {
                    valid: 'fa fa-check',
                    invalid: 'fa fa-times',
                    validating: 'fa fa-refresh'
                },
                locale: '<?php if (isset($_SESSION['forum_lang'])) echo $_SESSION['forum_lang']; else echo 'en_US' ?>',
                fields: {
                    email: {
                        validators: {
                            notEmpty: {
                            }
                            <?php if (isset($settings['mailgunAPI'])) { ?>,
                            remote: {
                                type: 'GET',
                                url: 'https://api.mailgun.net/v2/address/validate?callback=?',
                                crossDomain: true,
                                name: 'address',
                                data: {
                                    api_key: '<?php echo $settings['mailgunAPI'] ?>'
                                },
                                dataType: 'jsonp',
                                validKey: 'is_valid',
                                message: 'The email is not valid'
                            }
                            <?php } ?>
                        }
                    },
                    player_id: {
                        validators: {
                            regexp: {
                                regexp: /^\d{15,25}$/
                            },
                            remote: {
                                url: '<?php echo $settings['url']; ?>validators/pid.php',
                                type: 'POST',
                                message: '<?php echo $lang['failPID'] ?>'
                            }
                        }
                    },
                    current_password: {
                        validators: {
                            stringLength: {
                                min: 6,
                                max: 64
                            },
                            remote: {
                                url: '<?php echo $settings['url']; ?>validators/pass.php',
                                type: 'POST',
                                message: '<?php echo $lang['incorrectPass'] ?>'
                            }
                        }
                    },
                    user_password: {
                        enabled: false,
                        validators: {
                            notEmpty: {},
                            stringLength: {
                                min: 6,
                                max: 64
                            },
                            different: {
                                field: 'current_password',
                                message: 'The username and password cannot be the same as each other'
                            }
                        }
                    },
                    user_password_again: {
                        enabled: false,
                        validators: {
                            notEmpty: {},
                            stringLength: {
                                min: 6,
                                max: 64
                            },
                            identical: {
                                field: 'user_password',
                                message: 'The password and its confirm are not the same'
                            }
                        }
                    }
                }
            })

            .on('keyup', '[name="current_password"]', function (e) {
                if ($('#profileEdit').find('[name="current_password"]').val() === '') {
                    $('#profileEdit').formValidation('enableFieldValidators', 'user_password', false);
                    $('#profileEdit').formValidation('enableFieldValidators', 'user_password_again', false);
                } else {
                    $('#profileEdit').formValidation('enableFieldValidators', 'user_password', true);
                    $('#profileEdit').formValidation('enableFieldValidators', 'user_password_again', true);
                }
            })

            .on('success.validator.fv', function (e, data) {
                if (data.field === 'player_id' && data.validator === 'remote') {
                    document.getElementById("steam").innerHTML = '<?php echo $lang['steamFound']; ?>' + ' <a href="' + data.result.url + '" target="_blank">' + data.result.name + '</a>';
                }
                if (data.field === 'email' && data.validator === 'remote') {
                    var response = data.result;  // response is the result returned by MailGun API
                    if (response.did_you_mean) {
                        // Update the message
                        data.element                    // The field element
                            .data('fv.messages')        // The message container
                            .find('[data-fv-validator="remote"][data-fv-for="email"]')
                            .html('Did you mean ' + response.did_you_mean + '?')
                            .show();
                    }
                }
            })
            .on('err.validator.fv', function(e, data) {
                if (data.field === 'email' && data.validator === 'remote') {
                    // We need to reset the error message
                    data.element                // The field element
                        .data('fv.messages')    // The message container
                        .find('[data-fv-validator="remote"][data-fv-for="email"]')
                        .html('The email is not valid')
                        .show();
                }
            });
    });
</script>