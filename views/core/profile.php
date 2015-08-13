<?php

if (isset($_POST['email'])) {
    if (formtoken::validateToken($_POST)) {
        $email = $_POST['email'];
        $user_pic = $_POST['user_pic'];
        $pId = $_POST['player_id'];
        $_SESSION['user_profile'] = $user_pic;
        $sql = "UPDATE `users` SET `user_email`= '" . $email . "',`playerid`= '" . $pId . "', `user_profile`= '" . $user_pic . "'WHERE `user_name` = '" . $_SESSION['user_name'] . "' ";
        $result_of_query = $db_connection->query($sql);
    } else {
        message($lang['expired']);
    }
    }
if (isset($_POST['user_password'])) {
    if (formtoken::validateToken($_POST)) {
        $sql = "UPDATE `users` SET `user_password_hash`= '" . password_hash($_POST['user_password'], PASSWORD_DEFAULT) . "' WHERE `user_name` = '" . $_SESSION['user_name'] . "' ";
        $result_of_query = $db_connection->query($sql);
    } else {
        message($lang['expired']);
    }
    }
?>

<div class="row">
    <div class="col-lg-4">
    </div>
    <div class="col-lg-8">
        <h1 class="page-header">
            <?php echo $lang['navProfile']; ?>
            <small><?php echo " " . $lang['overview']; ?></small>
        </h1>
    </div>
</div>

<h2 class="form-login-heading"><?php echo $_SESSION['user_name']; ?></h2>
<?php
$sql = "SELECT * FROM `users` WHERE `user_name` ='" . $_SESSION['user_name'] . "' ;";
$result_of_query = $db_connection->query($sql);
while ($row = mysqli_fetch_assoc($result_of_query)) {

    echo '<form method="post" action="profile" name="profileEdit" id="profileEdit">';
    echo formtoken::getField();
    $userPid = $row["playerid"];
    echo "<div class='form-group'>" . $lang['emailAdd'] . ": <input class='form-control' id='email' type='email' name='email' value='" . $row["user_email"] . "'></div>";
    echo "<div class='form-group'>" . $lang['rank'] . ": " . $staff_levels[$row["user_level"]] . " (" . $row["user_level"] . ")</div>";
    echo "<div class='form-group'>" . $lang['picture'] . ": ";

    echo "<select id='user_pic' name='user_pic' class='form-control'>";
    for ($icon = 1; $icon < 6; $icon++) {
        echo '<option value="' . $icon . '" ' . select($icon, $row['user_profile']) . '>' . $settings['names'][$icon] . '</option>';
    }
    echo "</select></div>";

    echo "<div class='form-group'>" . $lang['playerID'] . ": <input class='form-control' id='player_id' type='number' name='player_id' value='" . $row["playerid"] . "'>";
    echo "<p id='steam'></p>";
    $sql = "SELECT `uid`,`playerid` FROM `players` WHERE `playerid` = '" . $row["playerid"] . "' ";
    $result_of_query = $db_connection->query($sql);

    if ($result_of_query->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result_of_query)) {
            echo '<a href="' . $settings['url'] . 'editPlayer/' . $row["uid"] . '">  View</a>';
        }
    }
    echo "</div><br>";

    echo "<div class='form-group'><label for='current_password'>" . $lang['current'] . " " . $lang['password'] . "</label>: ";
    echo '<input type="password" id="current_password" name="current_password" class="form-control"
                           autocorrect="off" autocapitalize="off" autocomplete="off"></div>';

    echo "<div class='form-group'><label for='user_password'>" . $lang['password'] . "</label>: ";
    echo '<input type="password" id="user_password" name="user_password" class="form-control"
                           autocorrect="off" autocapitalize="off" autocomplete="off"></div>';

    echo "<div class='form-group'><label for='user_password_again'>" . $lang['repeat'] . " " . $lang['password'] . "</label>: ";
    echo '<input type="password" id="user_password_again" name="user_password_again" autocorrect="off" class="form-control"
                           autocapitalize="off" autocomplete="off"></div>';

    echo "<input class='btn btn-sm btn-primary' type='submit'  name='edit' value='" . $lang['subChange'] . "'> ";
    echo "</form>";
};
?>
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