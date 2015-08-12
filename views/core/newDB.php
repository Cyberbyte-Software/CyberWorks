<?php
if (isset($_POST['db_type']) && isset($_POST['SQL_host']) && isset($_POST['SQL_user']) && isset($_POST['SQL_pass']) && isset($_POST['SQL_name'])) {
    if (formtoken::validateToken($_POST)) {
        $db_type = $_POST['db_type'];
    
        $sql = "INSERT INTO `db` (`type`, `sql_host`, `sql_user`, `sql_pass`, `sql_name`) VALUES ('" . $db_type . "', '" . encrypt($_POST['SQL_host']) . "', '" . encrypt($_POST['SQL_user']) . "', '" . encrypt($_POST['SQL_pass']) . "', '" . encrypt($_POST['SQL_name']) . "');";
        $result_of_query = $db_connection->query($sql);
    
        message($lang['newdb']);
    } else {
        message($lang['expired']);
    }
    }
?>
<div id="login-page">
    <div class="col-lg-10 container">
        <form method="post" action="newDB" name="newDB" id="newDB">
            <?php echo formtoken::getField() ?>
            <h2 class="form-login-heading">
                <?php echo $lang['new'] . ' ' . $lang['database'] ?>
            </h2>
            <div class="form-group">
                <label for="db_type">Server type: </label>
                <select id="db_type" class=" form-control login_input" name="db_type">
                    <option value="life">Altis Life</option>
                    <option value="wasteland">Wasteland</option>
                </select>
            </div>
            <div class="form-group">
                <label for="SQL_host">SQL Host: </label>
                <input placeholder="SQL Host" id="SQL_host"
                       class=" form-control login_input" type="text"
                       name="SQL_host"

                    <?php if (isset($_POST['SQL_host'])) echo 'value="' . htmlspecialchars($_POST['SQL_host']) . '"' ?>
                       required>
                </div>
                <div class="form-group">
                    <label for="SQL_user">SQL User: </label>
                    <input placeholder="SQL User" id="SQL_user"
                       class=" form-control login_input" type="text"
                       name="SQL_user"
                        <?php if (isset($_POST['SQL_user'])) echo 'value="' . htmlspecialchars($_POST['SQL_user']) . '"' ?>
                       required autocapitalize="off">
                    </div>
                    <div class="form-group">
                        <label for="SQL_pass">SQL Password: </label>
                        <input placeholder="SQL Password" id="SQL_pass"
                       class=" form-control login_input" type="password"
                       name="SQL_pass"

                            <?php if (isset($_POST['SQL_pass'])) echo 'value="' . htmlspecialchars($_POST['SQL_pass']) . '"' ?>
                       required autocapitalize="off" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="SQL_name">SQL Database: </label>
                            <input placeholder="SQL Database Name" id="SQL_name"
                       class=" form-control login_input" type="text"
                       name="SQL_name"

                                <?php if (isset($_POST['SQL_name'])) echo 'value="' . htmlspecialchars($_POST['SQL_name']) . '"' ?>
                       required>
                            </div>
                            <input class="btn btn-lg btn-primary" style="float:right;" type="submit" name="setup"
                       value="Setup">
                            </form>
                        </div>
                        <script>
                            $(document).ready(function() {
                            $('#newDB').formValidation({
                                framework: 'bootstrap',
                                icon: {
                                    valid: 'fa fa-check',
                                    invalid: 'fa fa-times',
                                    validating: 'fa fa-refresh'
                                },
                                fields: {
                                    SQL_host: {
                                        validators: {
                                            notEmpty: {
                                            },
                                            ip: {
                                            }
                                        }
                                    },
                                    SQL_user: {
                                        validators: {
                                            stringLength: {
                                                min: 3,
                                                max: 15
                                            },
                                            notEmpty: {
                                            }
                                        }
                                    },
                                    SQL_pass: {
                                        validators: {
                                            stringLength: {
                                                min: 3,
                                                max: 80
                                            },
                                            notEmpty: {
                                            }
                                        }
                                    },
                                    SQL_name: {
                                        validators: {
                                            stringLength: {
                                                min: 3,
                                                max: 15
                                            },
                                            notEmpty: {
                                            }
                                        }
                                    },
                                }
                                });
                            });
                        </script>