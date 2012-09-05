<?php
require_once 'Action.php';

class LibraryEditFormAction extends Action {

    function perform() {
    
	// set display requirements
	  $result = array(
      	'renderer' => 'template_renderer.inc',
        'pageTitle' => SITE_NAME .' : Library Admin',
        'content' => 'content/admin/libraryEditForm.php');
	
	// don't lose the db!	
		$db = $_REQUEST['db'];
	
	// where are we?	
		$uf = new UserFinder($db);
        $user = $uf->findById($_SESSION['userId']);
		$result['user'] = $user;
		
					
		$af = new AdminFinder($db);
		$adminTableList = $af->getAdminTables();
		$result['adminTables'] = $adminTableList;
		
		$lf = new LibraryFinder($db);
		$table = 'libraries';
		$result['everything'] = 
			$lf->getAllLibraries();
		
		if (isset($_POST['library_id'])) {
			$libraryID = $_POST['library_id'];
			$library = $lf->getLibraryName($libraryID);
		} else {
			$library = $user['library_short_name'];
			$libraryID = $user['library_id'];
		}
		
		$af = new AdminFinder($db);
		$parent_table_data = $af->getAdminTableRow($table);
		$result['parent_table_data'] = $parent_table_data;


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