<?php
require_once 'Action.php';
/**
 * An Action to add a question to the Library Stats. Does all the 
 * required checks and then adds the beast.
 */
class LoginFormAction extends Action {

    function perform() 
    {
        $this->saveRequestToSession();
        $result = array(
            'renderer' => 'template_renderer.inc',
            'pageTitle' => SITE_NAME .' : Please Log In',
            'header' => 'headers/header.php',
            'content' => 'content/loginForm.php');
        $result['username'] = '';
        return $result;
    }

    function isAuthenticationRequired() {
        return false;
    }

    function saveRequestToSession() {
        $url = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
        $_SESSION['destination'] = $url;
    }
}
?>
