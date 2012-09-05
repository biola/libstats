<?php
require_once 'Action.php';
/**
 * An Action to add a question to the Library Stats. Does all the 
 * required checks and then adds the beast.
 */
class SiteMaintanenceAction extends Action {

    function perform() {
        $result = array();
        $result['renderer'] = 'template_autorefresh.inc';
        $result['pageTitle'] = SITE_NAME .' : Site Maintanence';
        $result['content'] = 'content/siteMaintanence.php';
        return $result;
    }

    function isAuthenticationRequired() {
        return false;
    }
}
?>
