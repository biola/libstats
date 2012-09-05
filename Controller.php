<?php
// Handle some very basic initalization functions... make sure everything
// is in place, do requires, and such. Also set up global constants
// such as DSN.
require_once "Init.php";
require_once "ControllerFunctions.php";

// We do includes based on rInfo; ensure that the only way to set it
// is internally.
$rInfo['renderer'] = '';
$rInfo['content'] = '';

// This will be used to find out our intended action
$opMap = buildOpMap();

$operation = getOperation($_SERVER['REQUEST_URI']);

// Get the Action for this operation, or a PageNotFoundAction if there's
// no match.
$action = new PageErrorAction();
if (isset($opMap[$operation])) { $action = $opMap[$operation]; }

// Defined in Init.php
if (SITE_MAINTANENCE && getRemoteIp() != DEBUG_IP) {
    $action = new SiteMaintanenceAction();
}

// There's one special case to worry about: the action requires
// authentication, and we're not logged in. Handle that and perform the
// Action.
if ($action->isAuthenticationRequired() && !isLoggedIn()) {
    $action = new LoginFormAction();
}

if ($action->isAdminRequired() && !isAdmin()) {
	$action = new PageErrorAction();
}

$rInfo = $action->perform();
$rInfo = fixRenderDefaults($rInfo);

// And dispatch the request to the view...
include $rInfo['renderer'];
?>