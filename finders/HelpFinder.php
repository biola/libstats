<?php 
require_once 'Finder.php';

class HelpFinder extends Finder {	

	//Help Topics
	function getHelpDesc($help_id){
		$fullQuery =
		"SELECT *
		FROM help_list
		WHERE help_id = ?";
		
		$result = $this->db->getRow($fullQuery, array($help_id)); 
		return $result;
	}
	
	//Location field options
	function getFieldOptions($sql, $param){
		$fullQuery =
		"SELECT *" . 
		$sql;
			
		$result = $this->db->getAll($fullQuery, $param);
		return $result;
	}
	
	//Locate field option descriptions--patron_type
	function getPatronOptions($library_id){
		$fullQuery =
		"SELECT *
		FROM `patron_types`
		LEFT JOIN (library_patron_types) ON
			(patron_types.patron_type_id = library_patron_types.patron_type_id)
		WHERE library_patron_types.library_id = ?
		ORDER BY list_order";
		
		$result = $this->db->getAll($fullQuery, array($library_id));
		return $result;
	}
		
	//Locate field option descriptions--question_type
	function getQuestionTypeOptions($library_id){
		$fullQuery =
		"SELECT *
		FROM `question_types`
		LEFT JOIN (library_question_types) ON
			(question_types.question_type_id = library_question_types.question_type_id)
		WHERE library_question_types.library_id = ?
		ORDER BY list_order";

		$result = $this->db->getAll($fullQuery, array($library_id));
		return $result;
	}

	//Locate field option descriptions--location_name
	function getLocationOptions($library_id){
		$fullQuery =
		"SELECT *
		FROM `locations`
		LEFT JOIN (library_locations) ON
			(locations.location_id = library_locations.location_id)
		WHERE library_locations.library_id = ?
		ORDER BY list_order";

		$result = $this->db->getAll($fullQuery, array($library_id));
		return $result;
	}
}


?>