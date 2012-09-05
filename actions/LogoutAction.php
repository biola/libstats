<?php
require_once 'Action.php';

class LogoutAction extends Action {

    // This class is rather abstract, and its methods
    // are all do-nothing methods.
    // Then again, subclasses aren't that much better...


    function perform() {
        $db = $_REQUEST['db'];
        $uf = new UserFinder($db);
        $cookieVal = isset($_COOKIE['login']) ? $_COOKIE['login'] : '';
        $uf->clearCookieCredentials($cookieVal);
        setcookie('login', '', mktime(0,0,0,0,0,2038));

        // Destroy the loggedIn and userId session varialbes
        $_SESSION['loggedIn'] = false;
        unset($_SESSION['loggedIn']);
        unset($_SESSION['userId']);
        // and kill the whole session
        session_destroy();

        $result = array(
            'renderer' => 'template_renderer.inc',
            'pageTitle' => SITE_NAME .' : Logged Out',
            'content' => 'content/loggedOut.php');
        $result['username'] = '';
        return $result;

    }

    function isAuthenticationRequired() {
        false;
    }
}
?>
