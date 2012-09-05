<?php
require_once 'Finder.php';
/**
 * Class info
 *
 * @author Nathan Vack <njvack@library.wisc.edu>
 */

class LibraryFinder extends Finder
{
    function getAllLibraries() {
        $query = 
        "SELECT
			library_id, 
            short_name,
			full_name
        FROM
            libraries
        ORDER BY 
            short_name";
        return $this->db->getAll($query);
	}
		
	function getLibraryName($lib_id) {
		//var_dump($lib_id);
		$query =
		"SELECT
			short_name
		FROM
			libraries
		WHERE
			library_id = ?";
		
			
		$result = $this->db->getOne($query, array($lib_id));
		return $result;
	}
	
	function getLibraryByName($short_name) {
		$query =
		"SELECT
			library_id,
			full_name,
			short_name
		FROM
			libraries
		WHERE
			short_name = ?";
			
		$result = $this->db->getRow($query, array($short_name));
		return $result;
	}

    function addLibrary($option_pk, $short_name, $full_name) {
        
		// Just add it... it should be easy ;-)
        $table = 'libraries';
		$field_values = array(
			//'library_id' => '', // commented line out as some had issues passing NULL values
			'full_name' => $full_name,
			'short_name' => $short_name
		);
		
		return $this->db->autoExecute(
            $table,
            $field_values,
            DB_AUTOQUERY_INSERT);
    }
	
		function editLibrary($option_pk, $short_name, $full_name) {
        
		// Edit with PEAR...
		$table = 'libraries';
		$field_values = array(
			'library_id' => $option_pk,
			'full_name' => $full_name,
			'short_name' => $short_name
		);
		$whereClause = ('library_id = ' . $option_pk);
		
		return $this->db->autoExecute(
            $table,
            $field_values,
            DB_AUTOQUERY_UPDATE, $whereClause);
	}
}


?>
