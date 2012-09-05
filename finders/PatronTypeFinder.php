<?php
require_once 'Finder.php';
/**
 * Class info
 *
 * @author Nathan Vack <njvack@library.wisc.edu>
 */

class PatronTypeFinder extends Finder
{
    var $cols = array('patron_type_id', 'patron_type', 'parent_list', 'description', 'examples');
    var $table = 'patron_types';
	var $joinTable = 'library_patron_types';
    var $joinCol = 'patron_type_id';
	var $joinCols = array('patron_type_id', 'library_id', 'list_order');

    function findByLibrary($libraryId = -1) {
        return parent::_findByLibrary(
            $this->table,
            $this->cols,
            $this->joinCol,
            $libraryId);
    }

    function getLast($clientAddr) {
        $query = 
        "SELECT
        {$this->joinCol}
        FROM
            questions
        WHERE
            client_ip = ?
        ORDER BY 
            question_id DESC
        LIMIT 1";

        return $this->db->getOne($query, array($clientAddr));
    }

   function findByLibraryID($libraryId) {
        $query = 
        "SELECT
			library_patron_types.library_id,
            library_patron_types.patron_type_id,
            library_patron_types.list_order,
			patron_types.patron_type
        FROM
            library_patron_types
		JOIN patron_types ON
			(library_patron_types.patron_type_id = patron_types.patron_type_id)
        WHERE 
            library_id = ?
        ORDER BY list_order";
        return $this->db->getAll($query, array($libraryId+0));
	}
	
	function getDistinctList() {
		$query =
		"SELECT
			patron_type_id,
			patron_type
		FROM
			patron_types
		ORDER BY
			patron_type ASC";
		return $this->db->getAll($query);
	}
		
	function addOption($option_pk, $patron_type, $parent_pk, $description, $examples) {
        return parent::_addOption(
            $this->table,
            $this->cols,
            $this->joinCol,
            $option_pk,
			$patron_type,
			$parent_pk,
			$description,
			$examples);
    }

	function editOption($option_pk, $patron_type, $parent_pk, $description, $examples) {
        return parent::_editOption(
            $this->table,
            $this->cols,
            $this->joinCol,
            $option_pk,
			$patron_type,
			$parent_pk,
			$description,
			$examples);
    }

	function deleteOption($option_pk, $patron_type, $parent_pk, $description, $examples) {
        return parent::_deleteOption(
            $this->table,
            $this->cols,
            $this->joinCol,
            $option_pk,
			$patron_type,
			$parent_pk,
			$description,
			$examples);
    }	
	
	function addBridgeItem($patron_type_id, $library_id) {
        return parent::_addBridgeItem(
            $this->joinTable,
            $this->joinCols,
            $patron_type_id,
			$library_id);
    }	
	
	function removeBridgeItem($patron_type_id, $library_id) {
        return parent::_removeBridgeItem(
            $this->joinTable,
            $this->joinCols,
            $patron_type_id,
			$library_id);
    }	
	
	function findMoverLibraryID($libraryId) {
        return parent::_findMoverLibraryID(
            $this->table,
            $this->cols,
            $this->joinCol,
            $libraryId);
	}
	
	function moveBridgeItemUp($patron_type_id, $library_id) {
        return parent::_moveBridgeItemUp(
            $this->table,
			$this->cols,
			$this->joinTable,
            $this->joinCol,
			$this->joinCols,
            $patron_type_id,
			$library_id);
	}
	
	function moveBridgeItemDown($patron_type_id, $library_id) {
        return parent::_moveBridgeItemDown(
            $this->table,
			$this->cols,
			$this->joinTable,
            $this->joinCol,
			$this->joinCols,
            $patron_type_id,
			$library_id);
	}
}
?>
