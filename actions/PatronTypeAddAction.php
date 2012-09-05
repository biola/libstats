<?php
require_once 'Action.php';

class PatronTypeAddAction extends Action {
    // This class is rather abstract, and its methods
    // are all do-nothing methods.
    // Then again, subclasses aren't that much better...
	
    function perform() {
	
	// don't lose the db!	
		$db = $_REQUEST['db'];
		
	// PatronTypeAddAction
		$patron_type = $_POST['new_option'];
		$parent_pk = $_POST['parent_pk'];
		$description = $_POST['description'];
		$examples = $_POST['examples'];
		
		$ptf = new PatronTypeFinder($db);
		$new_option = $ptf->addPatronType($patron_type, $parent_pk, $description, $examples);
		
		$short_name = $_POST['library'];
		
		$lib_f = new LibraryFinder($db);
		$library = $lib_f->getLibraryByName($short_name);
		
		$laa = new LibraryAdminAction;
		$result = $laa->perform();
		$result['library_id'] = $library['library_id'];
		$result['library'] = $library['short_name'];
		$result['full_name'] = $library['full_name'];
		return $result;
	}
}
?>