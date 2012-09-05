<?php
require_once 'Action.php';

class HelpAction extends Action {
	// This class is rather abstract, and its methods
    // are all do-nothing methods.
    // Then again, subclasses aren't that much better...
	
	
    function perform() {
    	// set display requirements
		$result = array(
      	'renderer' => 'template_renderer.inc',
        'pageTitle' => 'Help? ',
        'content' => 'content/admin/help.php');
	
		// don't lose the db!	
		$db = $_REQUEST['db'];
	
		// where are we?	
		$userFinder = new UserFinder($db);
        $user = $userFinder->findById($_SESSION['userId']);
		$result['user'] = $user;
		$library_id_post = $user['library_id'];
		$help_id = $_REQUEST['advice'];
		$result['help_id'] = $help_id;
		 
		
		//$help_id = 1;

		
		$helpFinder = new HelpFinder($db);
		$result['helpList'] =
			$helpFinder->getHelpDesc($help_id);
		$table = $result['helpList']['related_table'];
		//$result['pageTitle'] .= $result['helpList']['help_name'];
			
		$library_id = array(
			'database_field' => 'library_id',
			'relation' => '=',
			'value' => $library_id_post,
			'type' => 'INT');

		$criteria = array(
			'library_id' => $library_id);
		
		$sql = (" FROM " . $table . " WHERE ");
		$i = 0;
		$param = array();
		foreach ($criteria as $value){
			if($value["value"] == 0 and $value["type"] == 'INT'){ continue;}
			if($i != 0){
				$sql .= ('AND' . ' ' . $value["database_field"] . ' ' . $value["relation"] . ' ? ');
				$param[$i] = $value["value"];
			}else {
				$sql .= ($value["database_field"] . ' ' . $value["relation"] . ' ? ');
				$param[$i] = $value["value"];
			}
			$i++;
		}
		$sql .= " ORDER BY list_order";			
			
			
			
		$result['optionList'] =
			$helpFinder->getFieldOptions($sql, $param);
		$result['descPatron'] =
			$helpFinder->getPatronOptions($library_id_post);
		$result['descQuestionType'] =
			$helpFinder->getQuestionTypeOptions($library_id_post);
		$result['descLocation'] =
			$helpFinder->getLocationOptions($library_id_post);
		return $result;
    }

    function isAuthenticationRequired() {
        return false;
    }
}
?>