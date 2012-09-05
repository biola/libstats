<?php
require_once 'Action.php';

class LibraryPatronTypeAction extends Action {
    // This class is rather abstract, and its methods
    // are all do-nothing methods.
    // Then again, subclasses aren't that much better...
	
    function perform() {
	
	// don't lose the db!	
		$db = $_REQUEST['db'];

		if(isset($_POST['all'])) {
			$patron_type_id = $_POST['all'];
		} else { $patron_type_id = $_POST['visible']; }
		
		$short_name = $_POST['library'];
		$lib_f = new LibraryFinder($db);
		$library = $lib_f->getLibraryByName($short_name);
		$library_id = $library['library_id'];
		
		$ptf = new PatronTypeFinder($db);
		if (isset($_POST['add'])) {
			$add = $ptf->addBridgeItem($patron_type_id, $library_id);
		}
		elseif (isset($_POST['remove'])) {
			$remove = $ptf->removeBridgeItem($patron_type_id, $library_id);
		}
		elseif (isset($_POST['up'])) {
			$patron_type_id = $_POST['visible'];
			$up = $ptf->moveBridgeItemUp($patron_type_id, $library_id);
		}
		elseif (isset($_POST['down'])) {
			$patron_type_id = $_POST['visible'];
			$down = $ptf->moveBridgeItemDown($patron_type_id, $library_id);
		}
		
		$loa = new LibraryAdminFormAction();
		$result = $loa->perform();
		
		$_REQUEST['library_id'] = $library['library_id'];
		$_REQUEST['library'] = $library['short_name'];
		$_REQUEST['full_name'] = $library['full_name'];
		$_REQUEST['parent_table'] = 'patron_types';
		$_REQUEST['locationList'] =
			$ptf->findByLibraryID($library['library_id']);
		
		return $result;
	}
}
?>