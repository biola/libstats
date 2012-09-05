<?php
require_once 'Finder.php';
/**
 * Class info
 *
 * @author Nathan Vack <njvack@library.wisc.edu>
 */

class LocationFinder extends Finder
{
    var $cols = array('location_id', 'location_name', 'parent_list', 'description', 'examples');
    var $table = 'locations';
    var $joinTable = 'library_locations';
	var $joinCol = 'location_id';
	var $joinCols = array('location_id', 'library_id', 'location_name', 'list_order');

    function findByLibrary($libraryId = -1) {
        return parent::_findByLibrary(
            $this->table,
            $this->cols,
            $this->joinCol,
            $libraryId);
    }
	
    function getAllLocations() {
        $query = 
        "SELECT 
            locations.location_id,
            locations.location_name,
            library_locations.library_id
        FROM
            locations
        JOIN library_locations ON
                  (locations.location_id = library_locations.location_id)
        ORDER BY
		    library_locations.list_order";
        return $this->db->getAll($query);
    }
	
	function getDistinctList() {
		$query =
		"SELECT
			location_id,
			location_name
		FROM
			locations
		ORDER BY
			location_name ASC";
		return $this->db->getAll($query);
	}
		
	
	function getLastLocationId($clientAddr, $libraryId = -1) {
        $query = 
        "SELECT 
            location_id
        FROM
            questions
        WHERE 
            client_ip = ?
            AND library_id = ?
        ORDER BY 
            question_id DESC
        LIMIT 1";
        return $this->db->getOne($query, array($clientAddr, $libraryId));
    }

   function findByLibraryID($libraryId) {
        $query = 
        "SELECT
			library_id,
            location_id,
            location_name,
			list_order
        FROM
            library_locations
        WHERE 
            library_id = ?
        ORDER BY list_order";
        return $this->db->getAll($query, array($libraryId+0));
	}
	
	function findMoverLibraryID($libraryId) {
        return parent::_findMoverLibraryID(
            $this->table,
            $this->cols,
            $this->joinCol,
            $libraryId);
	}
	
	function getLocation($location_id){
		$query =
		"SELECT
			location_name
		FROM
			locations
		WHERE
			location_id = ?";
		
		$result = $this->db->getOne($query, array($location_id));
		return $result;
	}
		
	function addOption($option_pk, $location_name, $parent_pk, $description, $examples) {
        return parent::_addOption(
            $this->table,
            $this->cols,
            $this->joinCol,
            $option_pk,
			$location_name,
			$parent_pk,
			$description,
			$examples);
    }

	function editOption($option_pk, $location_name, $parent_pk, $description, $examples) {
        return parent::_editOption(
            $this->table,
            $this->cols,
            $this->joinCol,
            $option_pk,
			$location_name,
			$parent_pk,
			$description,
			$examples);
    }
	
	function deleteOption($option_pk, $location_name, $parent_pk, $description, $examples) {
        return parent::_deleteOption(
            $this->table,
            $this->cols,
            $this->joinCol,
            $option_pk,
			$location_name,
			$parent_pk,
			$description,
			$examples);
    }
	
	function addBridgeItem($location_id, $library_id, $location_name) {
		//differs from other Finder classes because 'location_name' must be populated	
		$table = 'library_locations';
		/* 01-05-2009
		removed 	'list_order' => '' from the array below.  Causes a problem passing
		a NULL value to an auto increment field.
		*/
		$field_values = array(
			'location_id' => $location_id,
			'library_id' => $library_id,
			'location_name' => $location_name,
		);
		
		return $this->db->autoExecute(
			$table,
			$field_values,
			DB_AUTOQUERY_INSERT);
	}
	
	function removeBridgeItem($location_id, $library_id) {
        return parent::_removeBridgeItem(
            $this->joinTable,
            $this->joinCols,
            $location_id,
			$library_id);
    }	
	
	function moveBridgeItemUp($location_id, $library_id) {
        return parent::_moveBridgeItemUp(
            $this->table,
			$this->cols,
			$this->joinTable,
            $this->joinCol,
			$this->joinCols,
            $location_id,
			$library_id);
	}

	function moveBridgeItemDown($location_id, $library_id) {
        return parent::_moveBridgeItemDown(
            $this->table,
			$this->cols,
			$this->joinTable,
            $this->joinCol,
			$this->joinCols,
            $location_id,
			$library_id);
	}

}
?>
