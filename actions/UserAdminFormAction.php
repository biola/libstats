<?php
require_once 'Action.php';

class UserAdminFormAction extends Action {

    function perform() {
    
	// set display requirements
	  $result = array(
      	'renderer' => 'template_renderer.inc',
        'pageTitle' => SITE_NAME .' : User Admin',
        'content' => 'content/admin/userAdminForm.php');
	
	// don't lose the db!	
		$db = $_REQUEST['db'];
	
	// where are we?	
		$uf = new UserFinder($db);
        $user = $uf->findById($_SESSION['userId']);
		$result['user'] = $user;

        // Find the list of users, and flag one as selected		
        // If one isn't found, use "new" as the requester id
        $userList = $uf->findUsers();
        $result['userList'] = $userList;		
		$result['selId'] = grwd('selUserId', -1);
		$result['selUser'] = $uf->findById($result['selId']);
		if (!is_numeric($result['selId']))
		{
		  $result['selUser']['user_id'] = 'new';
        }
        
        // Find libraries, and which library is selected
        $lf = new LibraryFinder($db);
        $result['libraryList'] = $lf->getAllLibraries();
        

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