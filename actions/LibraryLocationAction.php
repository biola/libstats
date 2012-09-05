<?php
require_once 'Action.php';

class LibraryLocationAction extends Action {
    // This class is rather abstract, and its methods
    // are all do-nothing methods.
    // Then again, subclasses aren't that much better...
	
    function perform() {
	
	// don't lose the db!	
		$db = $_REQUEST['db'];

		if(isset($_POST['all'])) {
			$location_id = $_POST['all'];
		} else { $location_id = $_POST['visible']; }
		
		$short_name = $_POST['library'];
		$lib_f = new LibraryFinder($db);
		$library = $lib_f->getLibraryByName($short_name);
		$library_id = $library['library_id'];
		
		$lf = new LocationFinder($db);
		$location_name =
			$lf->getLocation($location_id);

		if (isset($_POST['add'])) {
			$add = $lf->addBridgeItem
                ($location_id, $library_id, $location_name);
		}
		elseif (isset($_POST['remove'])) {
			$remove = $lf->removeBridgeItem($location_id, $library_id);
		}
		elseif (isset($_POST['up'])) {
			$location_id = $_POST['visible'];
			$up = $lf->moveBridgeItemUp($location_id, $library_id);
		}
		elseif (isset($_POST['down'])) {
			$location_id = $_POST['visible'];
			$down = $lf->moveBridgeItemDown($location_id, $library_id);
		}

		$loa = new LibraryAdminFormAction();
		$result = $loa->perform();
		
		$_REQUEST['library_id'] = $library['library_id'];
		$_REQUEST['library'] = $library['short_name'];
		$_REQUEST['full_name'] = $library['full_name'];
		$_REQUEST['parent_table'] = 'locations';
		$_REQUEST['locationList'] =
			$lf->findByLibraryID($library['library_id']);
				
		return $result;
	}
}
?>