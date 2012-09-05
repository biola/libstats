<?php
class Action {
    // This class is rather abstract, and its methods
    // are all do-nothing methods.
    // Then again, subclasses aren't that much better...

    function perform() {
        return null;
    }

    function isAuthenticationRequired() {
        return true;
    }
	
	function isAdminRequired() {
		return false;
	}
}
?>
