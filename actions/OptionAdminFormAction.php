<?php
require_once 'Action.php';

class OptionAdminFormAction extends Action {
    // This class is rather abstract, and its methods
    // are all do-nothing methods.
    // Then again, subclasses aren't that much better...

    function perform() {
    
	// set display requirements
	  $result = array(
      	'renderer' => 'template_renderer.inc',
        'pageTitle' => SITE_NAME .' : Option Admin	',
        'content' => 'content/admin/optionAdminForm.php');
	
	// don't lose the db!	
		$db = $_REQUEST['db'];
	
	// where are we?	
		$uf = new UserFinder($db);
        $user = $uf->findById($_SESSION['userId']);
		$result['user'] = $user;
		
					
		$af = new AdminFinder($db);
		$adminTableList = $af->getAdminTables();
		$result['adminTables'] = $adminTableList;
		if (isset($_GET['table'])) {
			$table = $_GET['table'];
		}
		//echo($table);
		$result['everything'] = 
			$af->getTableFields($table);
		//var_dump($result['everything']);
		
		$lf = new LibraryFinder($db);
		$libraryList = $lf->getAllLibraries();
		$result['libraryList'] = $libraryList;
		
		if (isset($_GET['library_id'])) {
			$libraryID = $_GET['library_id'];
			$library = $lf->getLibraryName($libraryID);
		} else {
			$library = $user['library_short_name'];
			$libraryID = $user['library_id'];
		}
		
		if (isset($table)) {
			$parent_table = $table;
		} else {
			$parent_table = 'locations';
		}
		
		$af = new AdminFinder($db);
		$parent_table_data = $af->getAdminTableRow($parent_table);
		$result['library_id'] = $libraryID;
		$result['library'] = $library;
		$parentFinder = new $parent_table_data['parent_finder']($db);
		$result['bridgeTableList'] =
			$parentFinder->findByLibraryID($libraryID);
		$result['parentTableList'] =
			$parentFinder->getDistinctList();
		$result['parent_table_data'] =
			$parent_table_data;

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