<?php
require_once 'Action.php';

class LibraryAddAction extends Action {
    // This class is rather abstract, and its methods
    // are all do-nothing methods.
    // Then again, subclasses aren't that much better...
	
    function perform() {
	
	// don't lose the db!	
		$db = $_REQUEST['db'];
	// decipher finder function (add or edit)
		if (isset($_POST['save']) && $_POST['option_pk'] == 0) {
			$function = 'addLibrary';
		}
		elseif (isset($_POST['save']) && $_POST['option_pk'] != '') {
			$function = 'editLibrary';
		}

	// OptionAddAction
		$finder = $_POST['parent_finder'];
		$table = $_POST['parent_table'];
		$option_pk = $_POST['option_pk'];
		$short_name = $_POST['shortname'];
		$full_name = $_POST['fullname'];
		
		$finder = new $finder($db);
		$option = $finder->$function($option_pk, $short_name, $full_name);
		
		header('Location: libraryAdminForm.do');
	}
}
?>