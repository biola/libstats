<?php
/**
 * This class is rather abstract, and its methods are all do-nothing methods.
 * Then again, subclasses aren't that much better...
 */
class Report {

  /**
   * include all reports and get a listing of their name
   * @return          : an array containing report names class/file names
   */
	function get() {
		// trying new reporting feature
		foreach (glob("reports/*.php") as $report) {
			require_once($report);

			// get list of classes, skip Report.php
			if ((preg_match("/\/(.*).php/", $report, $matches)) && ($report != "reports/Report.php")) {
				$temp_get[] = $matches[1];
			}
		}
		return($temp_get);	
	}


  /**
   * have no idea. this was originally in the older Reports class
   * @return          : NULL
   */
	function perform() {
		return null;
	}


  /**
   * have no idea. this was originally in the older Reports class
   * @return          : TRUE
   */
	function isAuthenticationRequired() {
		return true;
	}
}
?>
