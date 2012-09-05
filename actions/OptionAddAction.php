<?php
require_once 'Action.php';

class OptionAddAction extends Action {
    // This class is rather abstract, and its methods
    // are all do-nothing methods.
    // Then again, subclasses aren't that much better...
	
    function perform() {
	
	// don't lose the db!	
		$db = $_REQUEST['db'];
	// decipher finder function (add or edit)
		//echo($_POST['option_pk']);
		if (isset($_POST['save']) && $_POST['option_pk'] == 0) {
			$function = 'addOption';
		}
		elseif (isset($_POST['save']) && $_POST['option_pk'] != '') {
			$function = 'editOption';
		}
		
	// OptionAddAction
		$finder = $_POST['parent_finder'];
		$table = $_POST['parent_table'];
		$option_pk = $_POST['option_pk'];
		$option = $_POST['option'];
		$parent_pk = $_POST['parent_pk'];
		$description = $_POST['description'];
		$examples = $_POST['examples'];

		
		$finder = new $finder($db);
		$option = $finder->$function($option_pk, $option, $parent_pk, $description, $examples);
		
		$short_name = $_POST['library'];
		
		$lib_f = new LibraryFinder($db);
		$library = $lib_f->getLibraryByName($short_name);
		
		header('Location: optionAdminForm.do?table=' . $table);
	}
}
?>