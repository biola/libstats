<?php
require_once 'Action.php';

class LibraryTimeSpentAction extends Action {
    // This class is rather abstract, and its methods
    // are all do-nothing methods.
    // Then again, subclasses aren't that much better...
	
    function perform() {
	
	// don't lose the db!	
		$db = $_REQUEST['db'];

		if(isset($_POST['all'])) {
			$time_spent_id = $_POST['all'];
		} else { $time_spent_id = $_POST['visible']; }
		
		$short_name = $_POST['library'];
		$lib_f = new LibraryFinder($db);
		$library = $lib_f->getLibraryByName($short_name);
		$library_id = $library['library_id'];
		
		$tsf = new TimeSpentFinder($db);
		if (isset($_POST['add'])) {
			$add = $tsf->addBridgeItem($time_spent_id, $library_id);
		}
		elseif (isset($_POST['remove'])) {
			$remove = $tsf->removeBridgeItem($time_spent_id, $library_id);
		}
		elseif (isset($_POST['up'])) {
			$time_spent_id = $_POST['visible'];
			$up = $tsf->moveBridgeItemUp($time_spent_id, $library_id);
		}
		elseif (isset($_POST['down'])) {
			$time_spent_id = $_POST['visible'];
			$down = $tsf->moveBridgeItemDown($time_spent_id, $library_id);
		}
		
		$loa = new LibraryAdminFormAction();
		$result = $loa->perform();
		
		$_REQUEST['library_id'] = $library['library_id'];
		$_REQUEST['library'] = $library['short_name'];
		$_REQUEST['full_name'] = $library['full_name'];
		$_REQUEST['parent_table'] = 'time_spent_options';
		$_REQUEST['locationList'] =
			$tsf->findByLibraryID($library['library_id']);
		
		return $result;
	}
}
?>
