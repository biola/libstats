<?php
require_once 'Action.php';

class QuestionSearchAction extends Action {

    function perform() {
    
        $db = $_REQUEST['db'];
    
        $result = array(
            'renderer' => 'template_renderer.inc',
            'pageTitle' => SITE_NAME .' : Search Results',
            'content' => 'content/questionSearchResults.php');

        // The number of questions and page offset we want
		$count = grwd('count', 50);
		$page = grwd('page', 1);
		$result['count'] = $count;
		$result['page'] = $page;
    

        $userFinder = new UserFinder($db);
        $user = $userFinder->findById($_SESSION['userId']);
    
    
        $libId = $user['library_id'];
    
        $result['user'] = $user;
    
        $searchString = grwd("criteria");
        $simpleCriteria = $this->_buildCriteria($libId, $searchString);
        $result['searchWords'] = $searchString;
        
        $questionFinder = new QuestionFinder($db);
        $qList = $questionFinder->getPagedList(
            $count, 
            $page,
            $simpleCriteria['sql'],
            $simpleCriteria['params']);
            
        $result['questionList'] = &$qList['list'];
        $result['list_meta'] = &$qList['meta'];
        $result['base_url'] = 'search.do?criteria='.$searchString;
        $result['criteria_array'] = array('criteria' => $searchString);
        
        $result['origin'] = 
            $result['base_url']."&amp;page=$page&amp;count=$count";
    
    
        return $result;
    }
    
    function isAuthenticationRequired() {
       return true;
    }
    
    function _buildCriteria($libraryId, $searchString) {
        $searchString = trim($searchString);
        $fulltext = mysqlFulltextString($searchString);
        $params = array();
        $sql = 
            "questions.library_id = ?
            AND (
                MATCH(question) AGAINST(? IN BOOLEAN MODE)
                OR MATCH(answer) AGAINST(? IN BOOLEAN MODE)
				OR initials LIKE ?";
        $params[] = $libraryId;
        $params[] = $fulltext;
        $params[] = $fulltext;
        $params[] = $searchString;
        if (is_numeric($searchString)) {
            $sql .= " OR question_id = ?";
            $params[] = $searchString + 0;
        }
        $sql .= ")";
        return array(
            'sql' => $sql,
            'params' => &$params);
    }
}
?>
