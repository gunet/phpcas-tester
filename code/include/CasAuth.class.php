<?php /* vim: set noet sw=4 ts=4: */
/**
 * CAS Authentication module
 *
 * @package guestpages
 * @author kouk
 * @version $Id: init.php 979 2009-04-22 16:46:36Z avel $
 */

use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Petert82\Monolog\Formatter\LogfmtFormatter;


/**
 * CAS Authentication module class
 *
 * using static and global calls.
 */

class CasAuth implements GuestAuth {


    /**
     * do we debug?
     */
    private $debug = false;

    /**
     * Logger Interface
     */
    private $logger = null;

    /**
     * constructor of the CAS authentication module
     *
     * based on the application configuration
     * database  and an optional logger create the CasAuth object.
     *
     * @return void
     */

    public function __construct($debug = false) {
        if ($debug === true) {
            $this->debug = true;

            $this->logger = new Monolog\Logger('phpcas-tester');
            $this->logger->setTimezone(new DateTimeZone('Europe/Athens'));
            $handler = new StreamHandler('php://stdout', Logger::DEBUG);
            $handler->setFormatter(new LogfmtFormatter('date','level','channel','msg','Y-m-d H:i:s'));
            $this->logger->pushHandler($handler);
            
            phpCAS::setLogger($this->logger);
            phpCAS::setVerbose(true);
        }
        if ($this->debug === true)
            error_log("phpCAS::client(cas_version=" . $_ENV['CAS_VERSION'] . " cas_server=" . $_ENV['CAS_SERVER'] . " cas_port=" . $_ENV['CAS_PORT'] . " cas_context=" . $_ENV['CAS_CONTEXT'] . " service_name=" . $_ENV['CAS_SERVICE_NAME'] . ")");
       phpCAS::client($_ENV['CAS_VERSION'], $_ENV['CAS_SERVER'], (int)$_ENV['CAS_PORT'], $_ENV['CAS_CONTEXT'], $_ENV['CAS_SERVICE_NAME']);
       if ($this->debug === true)
            error_log("return phpCAS::client()");
       // No SSL validation for the CAS server
       phpCAS::setNoCasServerValidation();
       if (isset($_ENV['CAS_CONTAINER']) && !empty($_ENV['CAS_CONTAINER'])){
            $serviceValidate = 'https://' . $_ENV['CAS_CONTAINER'] . ':' . $_ENV['CAS_PORT'] . $_ENV['CAS_CONTEXT'] . '/p3/serviceValidate';
            if ($this->debug === true)
                error_log("phpCAS serviceValidate URL = $serviceValidate");
            phpCAS::setServerServiceValidateURL($serviceValidate);
       }
       if ($this->debug)
            error_log("return __construct()");
    }

    /**
     * force a client to be authenticated
     *
     * calls {@link phpCAS::forceAuthentication} so that if the client is not
     * authenticated they are redirected to the CAS server.
     * @return bool true
     */

    public function force() {
        phpCAS::forceAuthentication();
        return true;
    }

    /**
     * login a client
     *
     * @see CasAuth::force()
     * @return bool true
     */

    public function login() {
        if ($this->debug)
            error_log("phpCAS::client login()");
        return $this->force();
    }

    /**
     * get the username of the authenticated client. Forces authentication.
     *
     * @return string the username
     */

    public function user() {
        if ($this->debug)
            error_log("phpCAS::client user()");
        $this->force();
        return phpCAS::getUser();
    }

    /**
     * does the user have attributes?
     * 
     * @return bool
     */

    public function hasAttributes() {
        if ($this->debug)
            error_log("phpCAS::client hasAttributes()");
        return phpCAS::hasAttributes();
    }
    /**
     * get the user attributes
     * 
     * @return array the user attributes
     */
    public function getAttributes() {
        if ($this->debug)
            error_log("phpCAS::client getAttributes()");

        return phpCAS::getAttributes();
    }

    /**
     * check if the client is authenticated
     *
     * @see phpCAS::isAuthenticated()
     * @return bool true if the client is authenticated
     */
    public function isAuthenticated() {
        return phpCAS::isAuthenticated();
    }

    /**
     * perform single sign-out
     *
     * @param string $url a url to be redirected to after logout
     * @return bool true
     */

    public function logout($url) {
        if ($this->debug)
            error_log("phpCAS::client logout() url=$url");
        phpCAS::logoutWithRedirectService($url);
        return true;
    }
}
