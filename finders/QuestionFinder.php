<?php
require_once 'Finder.php';
/**
 * Class info
 *
 * @author Nathan Vack <njvack@library.wisc.edu>
 */

class QuestionFinder extends Finder {

    var $table = 'questions';
    var $selQueryJoin = 
    "SELECT
		question_id,
		questions.library_id,
        libraries.full_name AS library_full_name,
        libraries.short_name AS library_short_name,
        questions.location_id,
        locations.location_name,
        questions.question_type_id,
        question_types.question_type,
        questions.question_type_other,
        questions.time_spent_id,
        time_spent_options.time_spent,
        questions.patron_type_id,
        patron_types.patron_type,
        questions.question_format_id,
        question_formats.question_format,
        initials,
        hide,
        obsolete,
        question_date,
        client_ip,
        user_id,
        date_added,
        question,
		answer,
		delete_hide
	FROM questions
	JOIN libraries ON 
        (questions.library_id = libraries.library_id)
    JOIN locations ON 
        (questions.location_id = locations.location_id)
    JOIN question_types ON 
        (questions.question_type_id = question_types.question_type_id)
    LEFT JOIN time_spent_options ON 
        (questions.time_spent_id = time_spent_options.time_spent_id)
    LEFT JOIN patron_types ON 
        (questions.patron_type_id = patron_types.patron_type_id)
    LEFT JOIN question_formats ON 
        (questions.question_format_id = question_formats.question_format_id)";

    function addQuestion( $questionHash ) {
        // Just add it... it should be easy ;-)
        return $this->db->autoExecute(
            $this->table,
            $questionHash,
            DB_AUTOQUERY_INSERT);
    }

    function getLastQuestionTime($clientAddr, $libraryId) {
        $query = 
            "SELECT date_added
                FROM questions 
                WHERE client_ip = ? AND
                library_id = ?
                ORDER BY question_id DESC LIMIT 1";
        
        return $this->db->getOne($query, array($clientAddr, $libraryId));
    }

    function getQuestion($questionId) {
        $fullQuery = $this->selQueryJoin."
        WHERE question_id = ?";
        return $this->db->getRow($fullQuery, array($questionId));
    }

    function editQuestion($questionId, $questionHash) {
        //
        $whereClause = "question_id = $questionId";
        return $this->db->autoExecute(
            $this->table, 
            $questionHash,
            DB_AUTOQUERY_UPDATE,
            $whereClause);
    }


    function _makeWhereClause($conditions) {
        $whereClause = trim($conditions);
        if ($whereClause <> '') { $whereClause = " AND $whereClause"; }
        $whereClause = "WHERE delete_hide = 0 $whereClause";
        
        return $whereClause;
    }
    
    /** Returns an array of question rows from the database.
      *
      * This should be the only method needed to return questions.
      */
    function getQuestions(
        $conditions,
        $params) {
        
        $whereClause = $this->_makeWhereClause($conditions);
        
        $fullSql = $this->selQueryJoin . $whereClause . 
            " ORDER BY question_id DESC";
        return $this->db->getAll($fullSql, $params);
    }
    
    function getPagedList(
        $count,
        $page,
        $conditions,
        $params
    ) {
        $qData = array();
        $meta = $this->getQuestionIdRange($count, $page, $conditions, $params);
        $qData['meta'] = $meta;
        
        $questionIdConditions = 
            " question_id >= ".$meta['leastId'];
        if ($meta['greatestId'] >= 0) {
            $questionIdConditions .=" AND question_id < " . $meta['greatestId'];
        }
        
        $conditions = trim($conditions);
        if ($conditions <> '') {
            $questionIdConditions = " AND $questionIdConditions ";
        }
        $queryConditions = $conditions . $questionIdConditions;
        $qData['list'] = $this->getQuestions($queryConditions, $params);
        return $qData;
    }
    
    /** Returns the first and last question ID for a given page, question count,
      * and set of criteria. Deleted questions are automatically discounted.
      * 
      * @param count        The number of non-empty questions we want to see
      * @param page         The result set page we want to see
      * @param conditions   The where clause (do not include WHERE) with ? for
      *                     parameter values
      * @param params       The parameter values in an array
      *
      * @return An associative array with elements 'firstId,' 'lastId',
      *         'totalQuestions', 'nonemptyQuestions'
      */
    function getQuestionIdRange(
        $count,
        $page,
        $conditions,
        $params
    ) {
        // Arbitrarily clamp $page and $count to valid values
        $page = $page + 0;
        $count = $count + 0;
        if ($page <= 0) { $page = 1; }
        if ($count <= 1) { $count = 2; }
        
        // Come up with a WHERE clause
        $whereClause = $this->_makeWhereClause($conditions);
        
        $baseQuery = "
            SELECT question_id
            FROM questions " . $whereClause;
        $leastTarget = ($count * $page) - 1;
        $greatestTarget = ($count * ($page - 1)) -1;
        
        // Prepare to get the smallest quesiton id for this page
        // Don't select hidden questions at this end.
        $leastQuery = "$baseQuery 
            AND hide = 0 
            ORDER BY question_id DESC 
            LIMIT $leastTarget,1";
        
        $leastId = $this->db->getOne($leastQuery, $params);
        if ($leastId == null) {
            $leastId = 0;
        }
        
        // Prepare to get the question id of the largest question we *don't*
        // want to display -- we go one too far.
        if ($greatestTarget == -1) { // Don't constrain here
            $greatestId = -1;
        } else {
            $greatestQuery = $baseQuery . " AND hide = 0
                ORDER BY question_id DESC
                LIMIT $greatestTarget,1";
            $greatestId = $this->db->getOne($greatestQuery, $params);
        }
        $idRange = array(
            'greatestId' => $greatestId + 0,
            'leastId' => $leastId + 0
        );
        $idRange['leastTarget'] = $leastTarget;
        $idRange['greatestTarget'] = $greatestTarget;
        $fullCountQuery = "SELECT COUNT(*) FROM questions $whereClause";
        $totalQuestions = $this->db->getOne($fullCountQuery, $params);
        $idRange['totalQuestions'] = $totalQuestions;
        // Necessary for pagination calculations
        $nonemptyCountQuery = 
            "SELECT COUNT(*) FROM questions $whereClause AND hide = 0";
        $idRange['nonemptyQuestions'] = 
            $this->db->getOne($nonemptyCountQuery, $params);
        return $idRange;
    }
}       
?>