<?php
require_once 'Action.php';

class AdvancedSearchFormAction extends Action {
    // Not sure how this is going to work here--EL
 	
    function perform() {
	
    
	// set display requirements
	  $result = array(
      	'renderer' => 'template_renderer.inc',
        'pageTitle' => SITE_NAME .' : Advanced Search',
        'content' => 'content/advancedSearchForm.php');
		
	// don't lose the db!	
		$db = $_REQUEST['db'];
		//var_dump($_REQUEST);
	
	// where are we?	
		$userFinder = new UserFinder($db);
        $user = $userFinder->findById($_SESSION['userId']);
		
		$library = $user['library_short_name'];
		$libraryId = $user['library_id'];

		
		$result['library_id'] = $libraryId;
		$result['library'] = $library;
		
		$result['user'] = $user;

        // The library that got searched for last time, probably...
    	$selLibId = grwd('search_library_id', $libraryId);
    	$result['search_library_id'] = $selLibId;

		$libraryFinder = new LibraryFinder($db);

        $libraryList = 		
			$libraryFinder->getAllLibraries();
        array_unshift($libraryList,
            array(
                'library_id' => $libraryId,
                'short_name' => $user['library_short_name'],
                'full_name' => $user['library_full_name'],
                ),
            array(
                'library_id' => '0',
                'short_name' => 'All Libraries',
                'full_name' => 'All Libraries'),
            array(
                'library_id' => '',
                'short_name' => '----------------------------',
                'full_name' => '----------------------------')
                );
		$result['libraryList'] = $libraryList;

		$locationFinder = new LocationFinder($db);
		
		$result['locationList'] =
			$locationFinder->getAllLocations();
		
		
				
		return $result;
    }

    function isAuthenticationRequired() {
        return true;
    }
}

?>