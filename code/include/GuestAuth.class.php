<?php /* vim: set noet sw=4 ts=4: */
/**
 * Guest Authentication Interface
 *
 * @package guestpages
 * @author kouk
 * @version $Id: init.php 979 2009-04-22 16:46:36Z avel $
 */


/**
 * interface to guest authentication modules
 */
interface GuestAuth {

    /**
     * login a user
     *
     * @return bool true
     */

    public function login();

    /**
     * set MFA authentication
     * @return string $loginURL the login URL with MFA parameters
     */

    public function setMFA();

    /**
     * get the username of the authenticated user.
     *
     * @return string the username
     */

    public function user();

    /**
     * log out of the security domain
     *
     * @param string $url a url to be redirected to after logout
     * @return bool true
     */

    public function logout($url);

    /**
     * check if the user is authenticated
     *
     * @return bool true if the client is authenticated
     */

    public function isAuthenticated();

    /**
     * check if the user has attributes
     * 
     * @return bool true if the user has attributes
     */
    public function hasAttributes();

    /**
     * get user attributes
     * 
     * @return array of user attributes
     */
    public function getAttributes();
}
