<?php
$user = new user;

class user
{
    public function GetPlayerSummaries($steamid)
    {
        $settings = require('config/settings.php');
        $response = file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=' . $settings['steamAPI'] . '&steamids=' . $steamid);
        $json = json_decode($response);
        return $json->response->players[0];
    }

    public function signIn()
    {
        $settings = require('config/settings.php');
        if ($settings['steamlogin']) {
            require_once 'openid.php';
            $openid = new LightOpenID($settings['url']);
            if (!$openid->mode) {
                $openid->identity = 'http://steamcommunity.com/openid';
                header('Location: ' . $openid->authUrl());
            } elseif ($openid->mode == 'cancel') {
                print ('User has canceled authentication!');
            } else {
                if ($openid->validate()) {
                    preg_match("/^http:\/\/steamcommunity\.com\/openid\/id\/(7[0-9]{15,25}+)$/", $openid->identity, $matches);
                    $_SESSION['playerid'] = $matches[1];

                    $db_connection = masterConnect();

                    $sql = "SELECT user_name, user_email, user_level, user_profile, permissions, user_password_hash, user_id
                            FROM users WHERE playerid = '" . $_SESSION['playerid'] . "';";
                    $result_of_login_check = $db_connection->query($sql);

                    if ($result_of_login_check->num_rows == 1) {
                        $result_row = $result_of_login_check->fetch_object();
                        if ($result_row->user_level <> 0) {
                            $_SESSION['user_name'] = $result_row->user_name;
                            $_SESSION['user_level'] = $result_row->user_level;
                            $_SESSION['user_profile'] = $result_row->user_profile;
                            $_SESSION['user_email'] = $result_row->user_email;
                            $_SESSION['user_id'] = $result_row->user_id;
                            $_SESSION['permissions'] = json_decode($result_row->permissions, true);
                            if (isset($result_row->items)) {
                                $_SESSION['items'] = $result_row->items;
                            } else {
                                $_SESSION['items'] = $settings['items'];
                            }
                            if (isset($_POST['lang'])) {
                                $_SESSION['lang'] = $_POST['lang'];
                            }
                            $_SESSION['user_login_status'] = 1;
                            $_SESSION['steamsignon'] = false; //used to determine if its a single sign on with no account
                            multiDB();

                            logAction($_SESSION['user_name'], 'Successful Steam Login (' . $_SERVER['REMOTE_ADDR'] . ')', 2);
                        } else {
                            $this->errors[] = "User is banned.";
                            logAction($_POST['user_name'], 'Steam Login Failed - Banned User (' . $_SERVER['REMOTE_ADDR'] . ')', 3);
                        }
                    } else {
                        if ($settings['annonlogin']) {
                            $permissions = require('config/permissions.php');
                            $steam = $this->GetPlayerSummaries($_SESSION['playerid']);
                            $_SESSION['user_name'] = $steam->personaname;
                            $_SESSION['user_level'] = 1;
                            $_SESSION['user_profile'] = $steam->avatarmedium;
                            $_SESSION['permissions'] = $permissions[1];
                            $_SESSION['items'] = $settings['items'];
                            $_SESSION['user_login_status'] = 1;
                            $_SESSION['profile_link'] = $steam->profileurl;
                            $_SESSION['steamsignon'] = true; //used to determine if its a single sign on with no account
                            multiDB();

                            logAction($_SESSION['user_name'], 'Successful Steam Login (' . $_SERVER['REMOTE_ADDR'] . ')', 2);
                        } else {
                            errorMessage(7);
                        }
                    }
                    header('Location: ' . $settings['url']);
                    exit;
                } else {
                    print ('Error');
                }
            }
        }
    }
}

if (isset($_GET['login'])) {
    $user->signIn();
}
if ($settings['steamlogin']) {
    print ('<form action="?login" method="post"><input type="image"
    src="http://cdn.steamcommunity.com/public/images/signinthroughsteam/sits_large_noborder.png"/>
    </form>');
}