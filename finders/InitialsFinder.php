<?php
require_once 'Finder.php';
/**
 * Class info
 *
 * @author Nathan Vack <njvack@library.wisc.edu>
 */

class InitialsFinder extends Finder {
    function getLastInitials($clientAddr) {
        return $this->getLast($clientAddr);
    }

    function getLast($clientAddr) {
        $query = 
        "SELECT
            initials
        FROM
            questions
        WHERE
            client_ip = ?
        ORDER BY 
            question_id DESC
        LIMIT 1";

        return $this->db->getOne($query, array($clientAddr));
    }
}
?>
