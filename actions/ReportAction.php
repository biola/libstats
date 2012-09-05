<?php
require_once 'Action.php';

class ReportAction extends Action {
	// This class is rather abstract, and its methods
	// are all do-nothing methods.
	// Then again, subclasses aren't that much better...
	
  function perform() {
		// set display requirements
	  $result = array(
			'renderer' => 'template_renderer.inc',
			'pageTitle' => SITE_NAME .' : Reports',
			'content' => 'content/reportForm.php');
	
		// don't lose the db!	
		$db = $_REQUEST['db'];
	
		// where are we?	
		$userFinder = new UserFinder($db);
		$user = $userFinder->findById($_SESSION['userId']);
		$result['user'] = $user;
		
		$reportFinder = new ReportFinder($db);
		$reportCount = $reportFinder->getReportCount($db);
		$result['reportCount'] = $reportCount;
		
		return $result;
	}

	function isAuthenticationRequired() {
			return true;
	}
}
?>
