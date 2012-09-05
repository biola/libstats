<?php
require_once 'Action.php';
/**
 * An Action to add a question to the Library Stats. Does all the 
 * required checks and then adds the beast.
 */
class PageErrorAction extends Action {

    function perform() {
        // Dump a whole bunch of stuff for output buffering
        ob_start();
        echo "Dump of REQUEST:\n";
        var_dump($_REQUEST);
        echo "\n\nDump of SESSION:\n";
        var_dump($_SESSION);
        echo "\n\nDump of rInfo:\n";
        var_dump($rInfo);

        $message = ob_get_contents();
        // And send the error message as an email
        mail(DEV_EMAIL, "RefDB error", $message);
        ob_end_clean();

        $result = array();
        $result['renderer'] = 'template_renderer.inc';
        $result['pageTitle'] = SITE_NAME .' : Error';
        $result['content'] = 'content/pageError.php';
        return $result;
    }

    function isAuthenticationRequired() {
        return false;
    }
}
?>
