<?php 
require_once 'Finder.php';

class AdminFinder extends Finder {	

	//Beginning--location_name
	function getLocationNames() {
		$fullQuery =
		"SELECT DISTINCT locations.location_name, COUNT(library_locations.location_id) as count
		FROM locations
		JOIN library_locations ON
			(locations.location_id = library_locations.location_id)
		GROUP BY library_locations.location_name
		ORDER BY count DESC";

		$result = $this->db->getAll($fullQuery);
		return $result;
	}

	//Beginning--patron_type
	function getPatronTypes() {
		$fullQuery =
		"SELECT DISTINCT patron_types.patron_type, COUNT(library_patron_types.patron_type_id) as count
		FROM patron_types
		JOIN library_patron_types ON 
    	    (patron_types.patron_type_id = library_patron_types.patron_type_id)
		GROUP BY patron_types.patron_type
		ORDER BY count DESC";

		$result = $this->db->getAll($fullQuery);
		return $result;
	}

	//Beginning--question_type
	function getQuestionTypes() {
		$fullQuery =
		"SELECT DISTINCT question_types.question_type, COUNT(library_question_types.question_type_id) as count
		FROM question_types
		JOIN library_question_types ON 
    	    (question_types.question_type_id = library_question_types.question_type_id)
		GROUP BY question_types.question_type
		ORDER BY count DESC";

		$result = $this->db->getAll($fullQuery);
		return $result;
	}

	//Beginning--question_format
	function getQuestionFormat() {
		$fullQuery =
		"SELECT DISTINCT question_formats.question_format, COUNT(library_question_formats.question_format_id) as count
		FROM question_formats
		JOIN library_question_formats ON 
    	    (question_formats.question_format_id = library_question_formats.question_format_id)
		GROUP BY question_formats.question_format
		ORDER BY count DESC";

		$result = $this->db->getAll($fullQuery);
		return $result;
	}

	//Beginning--referral_type
	function getReferralType() {
		$fullQuery =
		"SELECT DISTINCT referral_options.referral, COUNT(library_referral_options.referral_id) as count
		FROM referral_options
		JOIN library_referral_options ON 
    	    (referral_options.referral_id = library_referral_options.referral_id)
		GROUP BY referral_options.referral
		ORDER BY count DESC";

		$result = $this->db->getAll($fullQuery);
		return $result;
	}
	
	//AdminPage--drop_box
	function getAdminTables() {
		$fullQuery =
		"SELECT parent_table,
		parent_pk,
		display_name,
		bridge_table
		FROM admin
		WHERE bridge_table_view = 1";
		
		$result = $this->db->getAll($fullQuery);
		return $result;
	}
	
	function getAdminTableRow($parent_table) {
		$fullQuery =
		"SELECT parent_table,
		parent_pk,
		descriptor,
		display_name,
		parent_finder,
		edit_action_class,
		bridge_table
		FROM admin
		WHERE parent_table = ?";
		
		$result = $this->db->getRow($fullQuery, array($parent_table));
		return $result;
	}
	
	function getTableFields($table) {
		$fullQuery =
		"SELECT *
		FROM !";
		
		$result = $this->db->getAll($fullQuery, array($table));
		return $result;
	}
}
?>