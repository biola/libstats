<?php
require_once 'Action.php';
/**
 * An Action to add a question to the Library Stats. Does all the 
 * required checks and then adds the beast.
 */
class PageNotFoundAction extends Action {

    function perform() {
        $result = array();
        $result['renderer'] = 'template_renderer.inc';
        $result['pageTitle'] = 'Page not found';
        $result['header'] = 'headers/header.php';
        $result['content'] = 'content/pageNotFound.php';
    }

    function isAuthenticationRequired() {
        return false;
    }
}
?>
