<?php
require_once("gfunctions.php");
/**
     * Class login
     * handles the user's login and logout process
     */
class Login
{
    /**
     * @var array Collection of error messages
     */
    public $errors = array();
    /**
     * @var array Collection of success / neutral messages
     */
    public $messages = array();
    /**
     * @var object The database connection
     */
    private $db_connection = null;

    /**
     * the function "__construct()" automatically starts whenever an object of this class is created,
     * you know, when you do "$login = new Login();"
     */
    public function __construct()
    {
        // create/read session, absolutely necessary
        //session_start();
        // check the possible login actions:
        // if user tried to log out (happen when user clicks logout button)
        if (isset($_GET["logout"])) {
            $this->doLogout();
        } // login via post data (if user just submitted a login form)
        elseif (isset($_POST["login"])) {
            $this->dologinWithPostData();
        }
    }


    /**
     * perform the logout
     */
    public function doLogout()
    {
        // delete the session of the user
        if (isset($_SESSION['user_name'])) {
            logAction($_SESSION['user_name'], 'Logged Out', 1);
        }
        $_SESSION = array();
        session_destroy();
        // return a little feeedback message
        $this->messages[] = 'You have been logged out';

    }

    /**
     * log in with post data
     */
    private function dologinWithPostData()
    {
        $settings = require('config/settings.php');

        // check login form contents
        if (empty($_POST['user_name'])) {
            $this->errors[] = "Username field was empty.";
        } elseif (empty($_POST['user_password'])) {
            $this->errors[] = "Password field was empty.";
        } elseif (!empty($_POST['user_name']) && !empty($_POST['user_password'])) {

            if (isset($settings['db']['port'])) {
                $this->db_connection = new mysqli(decrypt($settings['db']['host']), decrypt($settings['db']['user']), decrypt($settings['db']['pass']), decrypt($settings['db']['name']), decrypt($settings['db']['port']));
            } else {
                $this->db_connection = new mysqli(decrypt($settings['db']['host']), decrypt($settings['db']['user']), decrypt($settings['db']['pass']), decrypt($settings['db']['name']));
            }

            // change character set to utf8 and check it
            if (!$this->db_connection->set_charset("utf8")) {
                $this->errors[] = $this->db_connection->error;
            }

            // if no connection errors (= working database connection)
            if (!$this->db_connection->connect_errno) {

                // escape the POST stuff
                $user_name = $this->db_connection->real_escape_string($_POST['user_name']);

                // database query, getting all the info of the selected user (allows login via email address in the
                // username field)
                $sql = "SELECT user_name, user_email, user_level, user_profile, permissions, user_password_hash, user_id, playerid, twoFactor, token
                        FROM users
                        WHERE user_name = '" . $user_name . "' OR user_email = '" . $user_name . "';";
                $result_of_login_check = $this->db_connection->query($sql);

                // if this user exists
                if ($result_of_login_check->num_rows == 1) {

                    // get result row (as an object)
                    $result_row = $result_of_login_check->fetch_object();

                    // using PHP 5.5's password_verify() function to check if the provided password fits
                    // the hash of that user's password
                    //var_dump(password_hash($_POST['user_password'], PASSWORD_DEFAULT));
                    if (password_verify($_POST['user_password'], $result_row->user_password_hash)) {
                        if ($result_row->user_level <> 0) {
                            //$verify = json_decode(file_get_contents('http://cyberbyte.org.uk/hooks/cyberworks/messages.php?id=' . $settings['id']));
                            //if (!isset($verify->verify)) {
                                $_SESSION['2factor'] = 0;
                                if (!empty($result_row->twoFactor)) {
                                    if ($settings['2factor']) $_SESSION['2factor'] = 1; else {
                                    $sql = "UPDATE `users` SET `backup`=NULL,`twoFactor`=NULL WHERE `userid` = '" . $result_row->user_id . "';";
                                    $this->db_connection->query($sql);
                                    $this->errors[] = $lang['2factorForceRevoke'];
                                    }
                                }

                                if (isset($_COOKIE['token']) && !empty($result_row->token)) {
                                    if (decrypt($result_row->token) == $_COOKIE['token']) {
                                        $_SESSION['2factor'] = 2;
                                    }
                                }
                                $_SESSION['sudo'] = time();
                                //$_SESSION['message'] = $verify;
                                $_SESSION['user_name'] = $result_row->user_name;
                                $_SESSION['user_level'] = $result_row->user_level;
                                $_SESSION['user_profile'] = $result_row->user_profile;
                                $_SESSION['user_email'] = $result_row->user_email;
                                $_SESSION['playerid'] = $result_row->playerid;
                                $_SESSION['user_id'] = $result_row->user_id;
                                $_SESSION['steamsignon'] = false;
                                $_SESSION['permissions'] = json_decode($result_row->permissions, true);
                                if (isset($result_row->items))$_SESSION['items'] = $result_row->items; else $_SESSION['items'] = $settings['items'];
                                if (isset($_POST['lang'])) {
                                    setcookie('lang', $_POST['lang'], time() + (3600 * 24 * 30));
                                    $_SESSION['lang'] = $_POST['lang'];
                                }
                                $_SESSION['steamsignon'] = false;
                                $_SESSION['user_login_status'] = 1;

                                multiDB();
                                logAction($_SESSION['user_name'], 'Successful Login (' . $_SERVER['REMOTE_ADDR'] . ')', 2);
                            /*} else {
                                if (isset($verify->message)) {
                                    $this->errors[] = $verify->message;
                                } else {
                                    $this->errors[] = "Verifcation Failed";
                                }
                            }*/
                        } else {
                            $this->errors[] = "User is banned.";
                            logAction($_POST['user_name'], 'Login Failed - Banned User (' . $_SERVER['REMOTE_ADDR'] . ')', 3);
                        }
                    } else {
                        $this->errors[] = "Wrong password. Try again.";
                        logAction($_POST['user_name'], 'Login Failed - Wrong Password (' . $_SERVER['REMOTE_ADDR'] . ')', 3);
                    }
                } else {
                    $this->errors[] = "This user does not exist.";
                    logAction($_POST['user_name'], 'Login Failed - Wrong Username (' . $_SERVER['REMOTE_ADDR'] . ')', 3);
                }
            } else {
                $this->errors[] = "Database connection problem.";
            }
        }
    }

    /**
     * simply return the current state of the user's login
     * @return boolean user's login status
     */
    public function isUserLoggedIn()
    {
        if (isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] == 1) {
            return true;
        }
        // default return
        return false;
    }
}
