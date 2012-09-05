<?php
require_once 'Action.php';

class SetAdminTableAction extends Action {
    // This class is rather abstract, and its methods
    // are all do-nothing methods.
    // Then again, subclasses aren't that much better...

	
	
    function perform() {
	
    
	// set display requirements
	  $result = array(
      	'renderer' => 'template_renderer.inc',
        'pageTitle' => SITE_NAME .' : Library Admin	',
        'content' => 'content/admin/libraryAdmin.php');
	
	// don't lose the db!	
		$db = $_REQUEST['db'];
	
	// where are we?	
		$uf = new UserFinder($db);
        $user = $uf->findById($_SESSION['userId']);
		$result['user'] = $user;
		
		$lf = new LibraryFinder($db);
		$libraryList = $lf->getAllLibraries();
		$result['libraryList'] = $libraryList;
		
		if (isset($_POST['library_id'])) {
			$libraryID = $_POST['library_id'];
			$library = $lf->getLibraryName($libraryID);
		} else {
			$library = $user['library_short_name'];
			$libraryID = $user['library_id'];
		}
		
		$result['library_id'] = $libraryID;
		$result['library'] = $library;
		$locationFinder = new LocationFinder($db);
		$result['locationList'] =
			$locationFinder->findByLibraryID($libraryID);
		$result['distinctLocationList'] =
			$locationFinder->getDistinctLocations();
			
		$af = new AdminFinder($db);
		$adminTableList = $af->getAdminTables();
		$result['adminTables'] = $adminTableList;
		
		
		return $result;
    }

    function isAuthenticationRequired() {
        return true;
    }
	
	function isAdminRequired() {
		return true;
	}
}
?>
