<?php
require_once 'Action.php';

class LibraryQuestionTypeAction extends Action {
    // This class is rather abstract, and its methods
    // are all do-nothing methods.
    // Then again, subclasses aren't that much better...
	
    function perform() {
	
	// don't lose the db!	
		$db = $_REQUEST['db'];

		if(isset($_POST['all'])) {
			$question_type_id = $_POST['all'];
		} else { $question_type_id = $_POST['visible']; }
		
		$short_name = $_POST['library'];
		$lib_f = new LibraryFinder($db);
		$library = $lib_f->getLibraryByName($short_name);
		$library_id = $library['library_id'];
		
		$qtf = new QuestionTypeFinder($db);
		if (isset($_POST['add'])) {
			$add = $qtf->addBridgeItem($question_type_id, $library_id);
		}
		elseif (isset($_POST['remove'])) {
			$remove = $qtf->removeBridgeItem($question_type_id, $library_id);
		}
		elseif (isset($_POST['up'])) {
			$question_type_id = $_POST['visible'];
			$up = $qtf->moveBridgeItemUp($question_type_id, $library_id);
		}
		elseif (isset($_POST['down'])) {
			$question_type_id = $_POST['visible'];
			$down = $qtf->moveBridgeItemDown($question_type_id, $library_id);
		}
		
		$loa = new LibraryAdminFormAction();
		$result = $loa->perform();
		
		$_REQUEST['library_id'] = $library['library_id'];
		$_REQUEST['library'] = $library['short_name'];
		$_REQUEST['full_name'] = $library['full_name'];
		$_REQUEST['parent_table'] = 'question_types';
		$_REQUEST['locationList'] =
			$qtf->findByLibraryID($library['library_id']);
		
		return $result;
	}
}
?>