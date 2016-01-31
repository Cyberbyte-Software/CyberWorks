<?php
error_reporting(0);
//error_reporting(E_ALL); // Turn on for error messages

session_name('CyberWorks');
session_set_cookie_params(1209600);
session_start();

require_once("classes/csrf.php");
ob_start();

if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    errorMessage(1, $lang);
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    require_once("classes/password.php");
}

if (file_exists('config/settings.php')) {
    $settings = require_once 'config/settings.php';

    require_once("classes/login.php");
    $login = new Login();

    require_once("classes/googleAuth.php");
    $gauth = new PHPGangsta_GoogleAuthenticator();

    include_once('config/english.php');
    foreach ($settings['plugins'] as &$plugin) {
        if (file_exists("plugins/" . $plugin . "/lang/lang.php")) {
            include("plugins/" . $plugin . "/lang/lang.php");
        }
    }

    if (file_exists('views/debug')) {
        include("views/debug/init.php");
    } else {
        $debug = false;
    }

    if (isset($_GET['searchText'])) {
        $search = $_GET['searchText'];
    }
    require_once("gfunctions.php");

    include "classes/update.php";

    $url = (parse_url($_SERVER['REQUEST_URI']));
    $url['path'] = str_replace('.php', '', $url['path']);
    $url['path'] = explode('/', $url['path']);

    $url['path'][$settings['base']] = strtolower($url['path'][$settings['base']]);
    if (count($url['path']) > $settings['base'] + 1 && $url['path'][$settings['base'] + 1] <> '') {
        $query = true;
        $url['path'][$settings['base'] + 1] = str_replace("%20", " ", $url['path'][$settings['base'] + 1]);
    } else {
        $query = false;
    }

    $db_connection = masterConnect();
    $currentPage = $url['path'][$settings['base']];

    if (isset($_GET["page"])) {
        $pageNum = clean($_GET["page"], 'int');
        if ($pageNum < 1) {
            $pageNum = 1;
        }
    } else {
        $pageNum = 1;
    }

    $key = 0;
    foreach ($settings['plugins'] as &$plugin) {
        if (file_exists("plugins/" . $plugin . "/plugin.json")) {
            if (file_exists("plugins/" . $plugin . "/init.php")) {
                include("plugins/" . $plugin . "/init.php");
            }
        } else {
            if (array_count_values($settings['plugins']) <= 1) {
                $settings['plugins'] = array();
            } else {
                unset($settings['plugins'][$key]);
            } //todo: lang support when deleted
        }
        $key++;
    }

    if (!$db_connection->connect_errno) {
        if ($login->isUserLoggedIn() == true) {

           if ($_SESSION['multiDB'] && isset($_POST['dbid']) && isset($_POST['type'])) {
                $_SESSION['server_type'] = $_POST['type'];
                $_SESSION['dbid'] = $_POST['dbid'];
            }

            if (!isset($_SESSION['formtoken'])) {
                formtoken::generateToken();
            }
            if ($_SESSION['formtoken'][1] < time() - 600) {
                formtoken::generateToken();
            }
            $_SESSION['formtoken'][1] = time();

            if (isset($_GET['items'])) {
                if (in_array($_GET['items'],$settings['item'])) {
                    $sql = "UPDATE `users` SET `items` = " . $_GET['items'] . " WHERE `user_id` = '" . $_SESSION['user_id'] . "' ";
                    $db_connection->query($sql);
                    $_SESSION['items'] = intval($_GET['items']);
                }
            }

            $err = errorMessage(4, $lang);
            $page = "views/templates/error.php";

            if ($currentPage == '' || $currentPage == 'index' || $currentPage == 'dashboard') {
                if (isset($_SESSION['server_type'])) {
                    if ($_SESSION['server_type'] == 'life') {
                        if ($_SESSION['steamsignon'] || $_SESSION['user_level'] == 1) {
                            $page = "views/steam/life/dashboard.php";
                        } else {
                            $page = "views/life/dashboard.php";
                        }
                    } elseif ($_SESSION['server_type'] == 'waste') {
                        if ($_SESSION['steamsignon'] || $_SESSION['user_level'] == 1) {
                            $page = "views/steam/waste/dashboard.php";
                        } else {
                            $page = "views/waste/dashboard.php";
                        }
                    } elseif (isset($_SESSION['user_email'])) {
                        if ($_SESSION['user_level'] == 1) {
                            $page = "views/steam/dashboard.php";
                        } else {
                            $page = "views/core/dashboard.php";
                        }
                    } else {
                        $page = "views/steam/dashboard.php";
                    }
                } elseif (isset($_SESSION['user_email'])) {
                    if ($_SESSION['user_level'] == 1) {
                        $page = "views/steam/dashboard.php";
                    } else {
                        $page = "views/core/dashboard.php";
                    }
                } else {
                    $page = "views/steam/dashboard.php";
                }
            } elseif (isset($_SESSION['server_type'])) {
                if ($_SESSION['server_type'] == 'life' && !$_SESSION['steamsignon']) {

                    if ($currentPage == 'messages') {
                        if ($settings['sql_phone']) {
                            if ($_SESSION['permissions']['view']['messages']) {
                                if ($query) {
                                    $search = $url['path'][$settings['base'] + 1];
                                }
                                logAction($_SESSION['user_name'], $lang['visited'] . " '" . $currentPage . "'", 1);
                                $page = "views/life/messages.php";
                            } else {
                                $err = errorMessage(5, $lang);
                                $page = "views/templates/error.php";
                                logAction($_SESSION['user_name'], $lang['failedAccess'] . " 'messages'", 3);
                            }
                        }

                    } elseif ($currentPage == 'players') {
                        if ($_SESSION['permissions']['view']['player']) {
                            if ($query) {
                                $search = $url['path'][$settings['base'] + 1];
                            }
                            logAction($_SESSION['user_name'], $lang['visited'] . " '" . $currentPage . "'", 1);
                            $page = "views/life/players.php";
                        } else {
                            $err = errorMessage(5, $lang);
                            $page = "views/templates/error.php";
                            logAction($_SESSION['user_name'], $lang['failedAccess'] . " 'players'", 3);
                        }

                    } elseif ($currentPage == 'editplayer') {
                        if ($_SESSION['permissions']['edit']['player']) {
                            if ($query) {
                                $uID = $url['path'][$settings['base'] + 1];
                                logAction($_SESSION['user_name'], $lang['visited'] . " '" . $currentPage . "'", 1);
                                $page = "views/life/editPlayer.php";
                            } else {
                                $err = errorMessage(8, $lang);
                                $page = "views/templates/error.php";
                            }
                        } else {
                            $err = errorMessage(5, $lang);
                            $page = "views/templates/error.php";
                        }

                    } elseif ($currentPage == 'vehicles') {
                        if ($_SESSION['permissions']['view']['vehicles']) {
                            if ($query) {
                                $search = $url['path'][$settings['base'] + 1];
                            }
                            logAction($_SESSION['user_name'], $lang['visited'] . " '" . $currentPage . "'", 1);
                            $page = "views/life/vehicles.php";
                        } else {
                            $err = errorMessage(5, $lang);
                            $page = "views/templates/error.php";
                            logAction($_SESSION['user_name'], $lang['failedAccess'] . " 'vehicles'", 3);
                        }

                    } elseif ($currentPage == 'editveh') {
                        if ($_SESSION['permissions']['edit']['vehicles']) {
                            if ($query) {
                                $vehID = $url['path'][$settings['base'] + 1];
                                logAction($_SESSION['user_name'], $lang['visited'] . " '" . $currentPage . "'", 1);
                                $page = "views/life/editVeh.php";
                            } else {
                                $err = errorMessage(8, $lang);
                                $page = "views/templates/error.php";
                            }
                        } else {
                            $err = errorMessage(5, $lang);
                            $page = "views/templates/error.php";
                            logAction($_SESSION['user_name'], $lang['failedAccess'] . " 'editVeh'", 3);
                        }

                    } elseif ($currentPage == 'medic') {
                        if ($_SESSION['permissions']['view']['player']) {
                            if ($query) {
                                $start_from = ($url['path'][$settings['base'] + 1] - 1) * results_per_page;
                            } else {
                                $start_from = 0;
                            }
                            logAction($_SESSION['user_name'], $lang['visited'] . " '" . $currentPage . "'", 1);
                            $page = "views/life/medics.php";
                        }

                    } elseif ($currentPage == 'police') {
                        if ($_SESSION['permissions']['view']['player']) {
                            if ($query) {
                                $start_from = ($url['path'][$settings['base'] + 1] - 1) * results_per_page;
                            } else {
                                $start_from = 0;
                            }
                            logAction($_SESSION['user_name'], $lang['visited'] . " '" . $currentPage . "'", 1);
                            $page = "views/life/police.php";
                        } else {
                            $err = errorMessage(5, $lang);
                            $page = "views/templates/error.php";
                            logAction($_SESSION['user_name'], $lang['failedAccess'] . " 'houses'", 3);
                        }

                    } elseif ($currentPage == 'houses') {
                        if ($_SESSION['permissions']['view']['houses']) {
                            if ($query) {
                                $search = $url['path'][$settings['base'] + 1];
                            }
                            logAction($_SESSION['user_name'], $lang['visited'] . " '" . $currentPage . "'", 1);
                            $page = "views/life/houses.php";
                        } else {
                            $err = errorMessage(5, $lang);
                            $page = "views/templates/error.php";
                            logAction($_SESSION['user_name'], $lang['failedAccess'] . " 'houses'", 3);
                        }

                    } elseif ($currentPage == 'edithouse') {
                        if ($_SESSION['permissions']['edit']['houses']) {
                            if ($query) {
                                $hID = $url['path'][$settings['base'] + 1];
                                logAction($_SESSION['user_name'], $lang['visited'] . " '" . $currentPage . "'", 1);
                                $page = "views/life/editHouse.php";
                            } else {
                                $err = errorMessage(8, $lang);
                                $page = "views/templates/error.php";
                            }
                        } else {
                            $err = errorMessage(5, $lang);
                            $page = "views/templates/error.php";
                            logAction($_SESSION['user_name'], $lang['failedAccess'] . " 'editHouse'", 3);
                        }

                    } elseif ($currentPage == 'gangs') {
                        if ($_SESSION['permissions']['view']['gangs']) {
                            if ($query) {
                                $search = $url['path'][$settings['base'] + 1];
                            }
                            logAction($_SESSION['user_name'], $lang['visited'] . " 'gangs'", 1);
                            $page = "views/life/gangs.php";
                        } else {
                            $err = errorMessage(5, $lang);
                            $page = "views/templates/error.php";
                            logAction($_SESSION['user_name'], $lang['failedAccess'] . " 'gangs'", 3);
                        }

                    } elseif ($currentPage == 'editgang') {
                        if ($_SESSION['permissions']['edit']['gangs']) {
                            if ($query) {
                                $gID = $url['path'][$settings['base'] + 1];
                                logAction($_SESSION['user_name'], $lang['visited'] . " '" . $currentPage . "'", 1);
                                $page = "views/life/editGang.php";
                            } else {
                                $err = errorMessage(8, $lang);
                                $page = "views/templates/error.php";
                            }
                        } else {
                            $err = errorMessage(5, $lang);
                            $page = "views/templates/error.php";
                            logAction($_SESSION['user_name'], $lang['failedAccess'] . " 'editGang'", 3);
                        }

                    } elseif ($currentPage == 'wanted') {
                        if ($_SESSION['permissions']['view']['wanted']) {
                            if ($query) {
                                $search = $url['path'][$settings['base'] + 1];
                            }
                            logAction($_SESSION['user_name'], $lang['visited'] . " '" . $currentPage . "'", 1);
                            $page = "views/life/wanted.php";
                        } else {
                            $err = errorMessage(5, $lang);
                            $page = "views/templates/error.php";
                            logAction($_SESSION['user_name'], $lang['failedAccess'] . " 'wanted'", 3);
                        }

                    } elseif ($currentPage == 'editwanted') {
                        if ($_SESSION['permissions']['edit']['wanted']) {
                            if ($query) {
                                $wantedID = $url['path'][$settings['base'] + 1];
                                logAction($_SESSION['user_name'], $lang['visited'] . " '" . $currentPage . "'", 1);
                                $page = "views/life/editWanted.php";
                            } else {
                                $err = errorMessage(8, $lang);
                                $page = "views/templates/error.php";
                            }
                        } else {
                            $err = errorMessage(5, $lang);
                            $page = "views/templates/error.php";
                            logAction($_SESSION['user_name'], $lang['failedAccess'] . " 'editWanted'", 3);
                        }
                    }

                } elseif ($_SESSION['server_type'] == 'life' && $_SESSION['steamsignon'] || $_SESSION['user_level'] == 1) {
                    if ($currentPage == 'cars') {
                        $page = "views/steam/life/cars.php";
                    } elseif ($currentPage == 'houses') {
                        $page = "views/steam/life/houses.php";
                    } elseif ($currentPage == 'editveh') {
                        if ($query) {
                            $vehID = $url['path'][$settings['base'] + 1];
                            logAction($_SESSION['user_name'], $lang['visited'] . " '" . $currentPage . "'", 1);
                            $page = "views/steam/life/editVeh.php";
                        } else {
                            $err = errorMessage(8, $lang);
                            $page = "views/templates/error.php";
                        }
                    } elseif ($currentPage == 'edithouse') {
                        if ($query) {
                            $hID = $url['path'][$settings['base'] + 1];
                            logAction($_SESSION['user_name'], $lang['visited'] . " '" . $currentPage . "'", 1);
                            $page = "views/steam/life/editHouse.php";
                        } else {
                            $err = errorMessage(8, $lang);
                            $page = "views/templates/error.php";
                        }
                    }
                }
            }
            if ($currentPage == 'newdb' || $currentPage == 'newserver' || $currentPage == 'settings' || $currentPage == 'editstaff' || $currentPage == 'staff' || $currentPage == 'pluginstore' || $currentPage == 'newuser' || $currentPage == 'logs') {
                if (isset($_POST['passTest'])) {
                    $sql = "SELECT user_password_hash FROM users WHERE user_id = '" . $_SESSION['user_id'] . "';";
                    $pass = $db_connection->query($sql)->fetch_object()->user_password_hash;
                    if (password_verify($_POST['passTest'], $pass)) {
                        $_SESSION['sudo'] = time();
                    } else {
                        message($lang['incorrectPass']);
                    }
                }
                if ($_SESSION['sudo'] + 10800 < time()) {
                        $page = "views/core/sudo.php";
                    } else {
                        if ($currentPage == 'newdb') {
                            if ($_SESSION['permissions']['super_admin']) {
                                logAction($_SESSION['user_name'], $lang['visited'] . " '" . $currentPage . "'", 2);
                                $page = "views/core/newDB.php";
                            } else {
                                $err = errorMessage(5, $lang);
                                $page = "views/templates/error.php";
                                logAction($_SESSION['user_name'], $lang['failedAccess'] . " 'newDB'", 3);
                            }
                        } elseif ($currentPage == 'newserver') {
                            if ($_SESSION['permissions']['super_admin']) {
                                logAction($_SESSION['user_name'], $lang['visited'] . " '" . $currentPage . "'", 2);
                                $page = "views/core/newServer.php";
                            } else {
                                $err = errorMessage(5, $lang);
                                $page = "views/templates/error.php";
                                logAction($_SESSION['user_name'], $lang['failedAccess'] . " 'newServer'", 3);
                            }

                        } elseif ($currentPage == 'settings') {
                            if ($_SESSION['permissions']['super_admin']) {
                                logAction($_SESSION['user_name'], $lang['visited'] . " '" . $currentPage . "'", 2);
                                $page = "views/core/settings.php";
                            } else {
                                $err = errorMessage(5, $lang);
                                $page = "views/templates/error.php";
                                logAction($_SESSION['user_name'], $lang['failedAccess'] . " 'settings'", 3);
                            }

                        } elseif ($currentPage == 'editstaff') {
                            if ($_SESSION['permissions']['edit']['staff']) {
                                if ($query) {
                                    $uId = $url['path'][$settings['base'] + 1];
                                    logAction($_SESSION['user_name'], $lang['visited'] . " '" . $currentPage . "'", 2);
                                    $page = "views/core/editStaff.php";
                                } else {
                                    $err = errorMessage(8, $lang);
                                    $page = "views/templates/error.php";
                                }
                            } else {
                                $err = errorMessage(5, $lang); $page = "views/templates/error.php";
                                logAction($_SESSION['user_name'], $lang['failedAccess'] . " 'editStaff'", 3);
                            }
                        } elseif ($currentPage == 'staff') {
                            if ($_SESSION['permissions']['view']['staff']) {
                                if ($query) {
                                    $search = $url['path'][$settings['base'] + 1];
                                }
                                logAction($_SESSION['user_name'], $lang['visited'] . " '" . $currentPage . "'", 2);
                                $page = "views/core/staff.php";
                            } else {
                                $err = errorMessage(5, $lang); $page = "views/templates/error.php";
                                logAction($_SESSION['user_name'], $lang['failedAccess'] . " 'staff'", 3);
                            }

                        } elseif ($currentPage == 'pluginstore') {
                            if ($_SESSION['permissions']['super_admin']) {
                                logAction($_SESSION['user_name'], $lang['visited'] . " '" . $currentPage . "'", 2);
                                $page = "views/core/pluginstore.php";
                            } else {
                                $err = errorMessage(5, $lang); $page = "views/templates/error.php";
                                logAction($_SESSION['user_name'], $lang['failedAccess'] . " 'pluginstore'", 3);
                            }

                        } elseif ($currentPage == 'newuser') {
                            if ($_SESSION['permissions']['edit']['staff']) {
                                require_once("classes/registration.php");
                                $registration = new Registration();
                                logAction($_SESSION['user_name'], $lang['visited'] . " '" . $currentPage . "'", 2);
                                $page = "views/core/newUser.php";
                            } else {
                                $err = errorMessage(5, $lang); $page = "views/templates/error.php";
                                logAction($_SESSION['user_name'], $lang['failedAccess'] . " 'newUser'", 3);
                            }

                        } elseif ($currentPage == 'logs' && $settings['logging']) {
                            if ($_SESSION['permissions']['view']['logs']) {
                                if ($query) {
                                    $search = $url['path'][$settings['base'] + 1];
                                }
                                logAction($_SESSION['user_name'], $lang['visited'] . " '" . $currentPage . "'", 2);
                                $page = "views/core/logs.php";
                            } else {
                                $err = errorMessage(5, $lang); $page = "views/templates/error.php";
                                logAction($_SESSION['user_name'], $lang['failedAccess'] . " 'noPerm'", 3);
                            }
                        }
                    }
            }

            if ($currentPage == 'curplayers') {
                if ($_SESSION['permissions']['view']['curplayer']) {
                    if ($query) {
                        $sid = $url['path'][$settings['base'] + 1];
                        logAction($_SESSION['user_name'], $lang['visited'] . " '" . $currentPage . "'", 2);
                        $page = "views/core/curPlayers.php";
                    } else {
                        $err = errorMessage(8, $lang);
                        $page = "views/templates/error.php";
                    }
                }
            } elseif ($currentPage == 'servers') {
                if ($_SESSION['permissions']['super_admin']) {
                    logAction($_SESSION['user_name'], $lang['visited'] . " '" . $currentPage . "'", 2);
                    $page = "views/core/servers.php";
                }
            } elseif ($currentPage == 'editserver') {
                if ($_SESSION['permissions']['super_admin']) {
                    if ($query) {
                        $id = $url['path'][$settings['base'] + 1];
                        logAction($_SESSION['user_name'], $lang['visited'] . " '" . $currentPage . "'", 2);
                        $page = "views/core/editServer.php";
                    } else {$err = errorMessage(8, $lang); $page = "views/templates/error.php"; }
                } else {$err = errorMessage(5, $lang); $page = "views/templates/error.php"; }
            }
            foreach ($settings['plugins'] as &$plugin) {
                if (file_exists("plugins/" . $plugin . "/pageRules.php")) {
                    include("plugins/" . $plugin . "/pageRules.php");
                }
            }

            if ($currentPage == '2factor' && isset($_SESSION['user_email'])) {
                $page = 'views/core/2factor.php';
            } elseif ($currentPage == 'donate') {
                $page = 'views/core/donate.php';
            }

            if ($currentPage == 'profile') {
                if (isset($_SESSION['user_email'])) {
                    $page = "views/core/profile.php";
                }
            }

            if ($currentPage == 'register') {
                if ($settings['register']) {
                    require_once("classes/GuestReg.php");
                    $GuestReg = new GuestReg();
                    $page = "views/core/register.php";
                }
            }
            if ($settings['2factor']) {
                if ($_SESSION['2factor'] == 0) {
                if ($settings['force2factor'] == 'steam') {
                    if (!$_SESSION['steamsignon']) $_SESSION['2factor'] == 5;
                } elseif ($settings['force2factor'] == 'all') $_SESSION['2factor'] == 5;
                    $page = 'views/core/2factor.php';
                } elseif ($_SESSION['2factor'] == 1 || $_SESSION['2factor'] == 3) {
                if (isset($_POST['code'])) {
                    $sql = "SELECT `twoFactor` FROM `users` WHERE `user_id` = '" . $_SESSION['user_id'] . "';";
                    $user = $db_connection->query($sql)->fetch_object();
                    if ($gauth->verifyCode($user->twoFactor, $_POST['code'])) $_SESSION['2factor'] = 2;
                    else {
                    $sql = "SELECT `backup` FROM `users` WHERE `user_id` = '" . $_SESSION['user_id'] . "';";
                    $user = $db_connection->query($sql)->fetch_object();
                    if ($user->backup == $_POST['code']) {
                        $_SESSION['2factor'] = 2;
                    } else {
                        $_SESSION['2factor'] = 3;
                        $page = 'views/core/2factor.php';
                    }
                    }
                } else $page = 'views/core/2factor.php';
                }
            }

            if ($debug) {
                if ($currentPage == 'debug') {
                    $page = "views/debug/debug.php";
                } elseif ($currentPage == 'phpinfo') {
                    $page = "views/debug/phpinfo.php";
                } elseif ($currentPage == 'debuglogs') {
                    $page = "views/debug/logs.php";
                } elseif ($currentPage == 'phplogs') {
                    $page = "views/debug/phplogs.php";
                }
            }
            include("views/templates/template.php");
        } else {
            include("views/core/login.php");
        }
    } else {
        $err = errorMessage(2, $lang);
    }
} else {
    include ('views/firstTime.php');
}
