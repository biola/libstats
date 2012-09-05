<?php
require_once 'Action.php';

class ReportAddDateAction extends Action {
	// Not sure how this is going to work here--EL
	function perform() {
	
		// set display requirements
	  $result = array(
			'renderer' => 'template_renderer.inc',
			'pageTitle' => SITE_NAME .' : Add Date Criteria',
			'content' => 'content/reportFormDate.php');
	
		// don't lose the db!	
		$db = $_REQUEST['db'];
	
		// where are we?	
		$userFinder = new UserFinder($db);
		$user = $userFinder->findById($_SESSION['userId']);
		
		$library = $user['library_short_name'];
		$libraryID = $user['library_id'];
		
		$result['library_id'] = $libraryID;
		$result['library'] = $library;
		$result['user'] = $user;

		$report_id = grwd('report_id');
		$result['report_id'] = $report_id;
		
		// get all the info on the reports
    $reportFinder = new ReportFinder($db);
    $reportCount = $reportFinder->getReportCount();
    $result['reportCount'] = $reportCount;

    // get the information for the chosen report by requiring Reports.php and all the reports
    $report_class_handle = new Report();
    $report_class_get = $report_class_handle->get();

    // declare the report class by using it's ID
    $report_info = new $report_id();
    $result['reportList'] = $report_info->info();

		$libraryFinder = new LibraryFinder($db);
		$result['libraryList'] = $libraryFinder->getAllLibraries();
		
		$locationFinder = new LocationFinder($db);
		$result['locationList'] = $locationFinder->getAllLocations(); 
				
		return $result;
	}

	function isAuthenticationRequired() {
			return true;
	}
}

?>
