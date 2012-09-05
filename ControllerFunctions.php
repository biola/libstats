<?php

function isLoggedIn() {
    if (AUTO_ADMIN_LOGON) {
        $_SESSION['loggedIn'] = true;
        $_SESSION['userId'] = 1;
        return true;
    }
    
    // Get loggedIn
    $loggedIn = false;
    if (isset($_SESSION['loggedIn'])) { $loggedIn = $_SESSION['loggedIn']; }
    // and userId
    $userId = -1;
    if (isset($_SESSION['userId'])) { $userId = $_SESSION['userId'] + 0; }
    if (!($loggedIn && ($userId > 0))) {
        return doCookieAuth();
    } else {
        return true;
    }
}

function doCookieAuth() {
    // Check for cookie logged-in-ness
    $cookieVal = isset($_COOKIE['login']) ? $_COOKIE['login'] : '';
    if ($cookieVal != '') {
        $uf = new UserFinder($_REQUEST['db']);
        $result = $uf->checkCookieCredentials($cookieVal);
        if (!DB::isError($result) && $result != null) {
            $userID = $result['user_id']+0;
			if ($userID != 0){
				$_SESSION['loggedIn'] = true;
            	$_SESSION['userId'] = $result['user_id']+0;
            	return true;
			}
			else { $_SESSION['loggedIn'] = false; }
        }
    }
    return false;
}

function isAdmin() {
	//Check for Admin
	if (AUTO_ADMIN_LOGON) { return true; }
	$userId = $_SESSION['userId'] + 0;
	$admin = false;
    $uf = new UserFinder($_REQUEST['db']);
    $result = $uf->findByID($userId);
	if ($result['admin'] == 1) {
		$admin = true;
	} 
	return $admin;
}


/**
 * getOperation
 * 
 * Takes a string such as "http://www.wendt.wisc.edu/refstats/operation.do?garbade=flo
 * and returns "operation.do"
 */
function getOperation($uri) {
	$operation = substr(strrchr($uri, '/'), 1);
    
	$operation = substr($operation, 0, strpos($operation, '.do')+3);
	
	return $operation;
}

/**
 * Creates a map of operations to action objects
 */
function buildOpMap() {
    $opMap = array(
        'login.do' => new LoginAction(),
        'loginForm.do' => new LoginFormAction(),
        'logout.do' => new LogoutAction(),
        'questionAddForm.do' => new QuestionAddFormAction(),
        'addQuestion.do' => new QuestionAddAction(),
        'questionEditForm.do' => new QuestionEditFormAction(),
        'search.do' => new QuestionSearchAction(),
        'editQuestion.do' => new QuestionEditAction(),
		'reportList.do' => new ReportAction(),
		'reportAddDate.do' => new ReportAddDateAction(),
		'reportReturn.do' => new ReportReturnAction(),
		'help.do' => new HelpAction(),
		'advancedSearchForm.do' => new AdvancedSearchFormAction(),
		'advancedSearch.do' => new AdvancedSearchAction(),
		'libraryAdminForm.do' => new LibraryAdminFormAction(),
		'addLibrary.do' => new LibraryAddAction(),
		'libraryEditForm.do' => new LibraryEditFormAction(),
		'addOption.do' => new OptionAddAction(),
		'optionAdminForm.do' => new OptionAdminFormAction(),
		'libraryLocation.do' => new LibraryLocationAction(),
		'libraryPatronType.do' => new LibraryPatronTypeAction(),
		'libraryQuestionFormat.do' => new LibraryQuestionFormatAction(),
		'libraryQuestionType.do' => new LibraryQuestionTypeAction(),
		'libraryTimeSpent.do' => new LibraryTimeSpentAction(),
		'setAdminTable.do' => new SetAdminTableAction(),
        'userAdminForm.do' => new UserAdminFormAction(),
        'userEdit.do' => new UserEditAction()
	);

    return $opMap;
}

function fixRenderDefaults($hash) {
    $origin = "";
	$killfoo = "";
	
	if (!is_array($hash)) { $hash = array(); }
    if (!isset($hash['renderer'])) {
        $hash['renderer'] = 'template_renderer.inc';
    }
    if (!isset($hash['pageTitle'])) {
        $hash['pageTitle'] = SITE_NAME;
    }
    if (isLoggedIn()) {
        $hash['header'] = 'headers/authHeader.php';
    } else {
        $hash['header'] = 'headers/unauthHeader.php';
    }

    if (!isset($hash['content'])) {
        $hash['content'] = 'content/pageError.php';
    }
	
	// Fix defaults for layout option and count -- these should live in the
	// cookie
	if (!isset($hash['layout'])) {
	   // Figure out what it should be
	   $layout = grwd('layout', 'menus');
	   $hash['layout'] = $layout;
    }
    setcookie('layout', $hash['layout'], mktime(0,0,0,0,0,2038));
	if (!isset($hash['count'])) {
	   // Figure out what it should be
	   $count = grwd('count', '50');
	   $hash['count'] = $count;
    }
    setcookie('count', $hash['count'], mktime(0,0,0,0,0,2038));
	
	if (!isset($hash['origin'])) {
        $hash['origin'] = 'questionAddForm.do';
	}
	
    if (!isset($hash['taskLinks'])) {
        $hash['taskLinks'] = array(
        	'<a href = "questionAddForm.do">Add Question Page</a>',
			'<a href = "reportList.do">Reports</a>',
			'<a href = "logout.do">Log out</a>');
    }
    return $hash;
}
?>