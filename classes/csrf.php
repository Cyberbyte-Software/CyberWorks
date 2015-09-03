<?php
/**
 * PHP Class for quickly securing forms against CSRF attacks
 *
 * @author Sam Collins
 * @copyright 2015 Sam Collins
 * @link https://gist.github.com/MightySCollins/0096d193fdc4160565b3
 */

class formToken
{
    /**
     * Makes hidden form input using session form token.
     *
     * @return string
     */
    public static function getField()
    {
        return "<input name='token' value='" . $_SESSION['formtoken'][0] . "' type='hidden' />";
    }

        /**
         * Makes sure token in session is valid
         *
         * @return bool
         * @param string $curToken
         */
    public static function validateToken($curToken)
    {
        if (!isset($_SESSION['formtoken']) || !isset($curToken['token'])) {
            return false;
        } else {
            if ($_SESSION['formtoken'][0] == $curToken['token']) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Sets token in session.
     */
    public static function generateToken()
    {
        $_SESSION['formtoken'] = array(sha1(mt_rand(0, 1000000)), time());
    }
}