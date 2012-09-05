<?php
require_once 'Action.php';
/**
 * An Action to add a question to the Library Stats. Does all the 
 * required checks and then adds the beast.
 */

class LoginAction extends Action {
	
    function perform() {
        $userFact = new UserFinder($_REQUEST['db']);
        $username = gpwd('username');
        $password = gpwd('password');
        $userId = $userFact->authenticate($username, md5($password));
        if (!($userId === null)) {
            $_SESSION['loggedIn'] = true;
            $_SESSION['userId'] = $userId;
            // Set a cookie as well...
            $cookieVal = $this->createCookieValue();
            setcookie('login', $cookieVal, mktime(0,0,0,0,0,2038));
            $userFact->setCookieCredentials($cookieVal, $userId);
            header("Location: {$_SESSION['destination']}");
            exit;
        }
        else {
            $result = array(
                'renderer' => 'template_renderer.inc',
                'pageTitle' => SITE_NAME .' : Please Log In',
                'header' => 'headers/header.php',
                'content' => 'content/loginForm.php');
            $result['username'] = $username;
            return $result;
        }
    }

    function isAuthenticationRequired() {
        return false;
    }

    function createCookieValue() {
        mt_srand(time());
        return md5(mt_rand());
    }
}
?>
