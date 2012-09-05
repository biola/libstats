<?php
require_once 'Action.php';

class AdvancedSearchAction extends Action {
	
    function perform() {
    
        // set display requirements
        $result = array(
            'renderer' => 'template_renderer.inc',
            'pageTitle' => SITE_NAME .' : Search Results',
            'content' => 'content/questionSearchResults.php');
            
        // The number of questions and page offset we want
		$count = grwd('count', 50);
		$page = grwd('page', 1);
		$result['count'] = $count;
		$result['page'] = $page;
    
        // don't lose the db!	
        $db = $_REQUEST['db'];
        
        $userFinder = new UserFinder($db);
        $user = $userFinder->findById($_SESSION['userId']);
        $result['user'] = $user;
        
        // Generate a base URL for this search -- can't have page or count data
        // cos we're going to build pagination links from this
        $criteriaArray = $_GET;
        unset($criteriaArray['page']);
        unset($criteriaArray['count']);
        $advancedSearchString = 
            implode_assoc('=', '&', $criteriaArray, true, true);
        $result['criteria_array'] = &$criteriaArray;
        $baseUrl = 'advancedSearch.do?'.$advancedSearchString;
        $result['advanced_search_string'] = $advancedSearchString;

        // gather request data
		$date1 = grwd('date1');
		$date2 = grwd('date2');
		$library_id_post = grwd('library_id') + 0;
		$location_id_post = grwd('location_id') + 0;
    $question_type_id_post = grwd('question_type_id') + 0;
    $patron_type_id_post = grwd('patron_type_id') + 0;
    $question_format_id_post = grwd('question_format_id') + 0;
		$initials = grwd('initials');
		$searchString = grwd('searchString');
		$result['searchWords'] = $searchString;

        $questionFinder = new QuestionFinder($db);
			
		$startDate = array(
			'database_field' => 'questions.question_date',
			'relation' => '>=',
			'value' => $date1,
			'type' => 'DATE');
		
		$endDate = array(
			'database_field' => 'questions.question_date',
			'relation' => '<=',
			'value' => $date2,
			'type' => 'DATE');
		
		$library_id = array(
			'database_field' => 'questions.library_id',
			'relation' => '=',
			'value' => $library_id_post,
			'type' => 'INT');
			
		$location_id = array(
			'database_field' => 'questions.location_id',
			'relation' => '=',
			'value' => $location_id_post,
			'type' => 'INT');
      
    $question_type_id = array(
			'database_field' => 'questions.question_type_id',
			'relation' => '=',
			'value' => $question_type_id_post,
			'type' => 'INT');
      
    $patron_type_id = array(
			'database_field' => 'questions.patron_type_id',
			'relation' => '=',
			'value' => $patron_type_id_post,
			'type' => 'INT');
      
    $question_format_id = array(
			'database_field' => 'questions.question_format_id',
			'relation' => '=',
			'value' => $question_format_id_post,
			'type' => 'INT');
      
		$initials = array(
			'database_field' => 'questions.initials',
			'relation' => 'LIKE',
			'value' => $initials,
			'type' => 'TEXT');
			
		$searchCriteria = array(
			'database_field' => array('question', 'answer'),
			'relation' => 'fulltext',
			'value' => $searchString,
			'type' => 'FULLTEXT');
			
		
					
		// pull together all of the search criteria	
		$criteria = array(
			'start_date' => $startDate,
			'end_date' => $endDate,
			'library_id' => $library_id,
			'location_id' => $location_id,
      'question_type_id' => $question_type_id,
      'patron_type_id' => $patron_type_id,
      'question_format_id' => $question_format_id,
			'initials' => $initials,
			'search_criteria' => $searchCriteria);
		
		$sql = "";
		
		$param = array();
		foreach ($criteria as $value){
			if(!$value["value"]) {continue;}
			if(count($param) != 0){
				$sql .= (' AND ');
			}
			if($value["type"] == "FULLTEXT"){
				// Track if this is the first fulltext in group
				$orNeeded = false; 
				$sql .= ' ( ';
				foreach ($value["database_field"] as $field){
					if ($orNeeded) { $sql .= " OR "; }
					$sql .= 
					   ('MATCH(' . $field . ") AGAINST(? IN BOOLEAN MODE)");
					$param[] = mySqlFulltextString($value["value"]);
					$orNeeded = true;
				}
				$sql .= ' ) ';
            } else if ($value['type'] == "DATE") {
                // Make dates sane; the only part of this that's special
                $sDate = makeDateSane($value['value']);
                $sDate = date('Y-m-d', strtotime($sDate));
				$sql .= $value["database_field"].' '.$value["relation"].' ? ';
				$param[] = $sDate;
			} else {
				$sql .= $value["database_field"].' '.$value["relation"].' ? ';
				$param[] = $value["value"];
			}
		}
		
		$questionFinder = new QuestionFinder($db);
		$qList = &$questionFinder->getPagedList($count, $page, $sql, $param);
		$result['questionList'] = &$qList['list'];
		$result['list_meta'] = $qList['meta'];

		$libraryFinder = new LibraryFinder($db);
		$searchLibName = $libraryFinder->getLibraryName($library_id_post);
		if ($library_id_post == 0) {
		  $searchLibName = "All";
        }
        
		if(isset($location_id_post)){
			$locationFinder = new LocationFinder($db);
			$searchLocName = $locationFinder->getLocation($location_id_post);
		}
    if(isset($question_type_id_post)){
			$questiontypeFinder = new QuestionTypeFinder($db);
			$searchQuestionType = $questiontypeFinder->getQuestionType($question_type_id_post);
		}
		if(isset($patron_type_id_post)){
			$patrontypeFinder = new PatronTypeFinder($db);
			$searchPatronType = $patrontypeFinder->getPatronType($patron_type_id_post);
    }
    if(isset($question_format_id_post)){
			$questionformatFinder = new QuestionFormatFinder($db);
			$searchQuestionFormat = $questionformatFinder->getQuestionFormat($question_format_id_post);
		}
    
        $result['origin'] = $baseUrl."&amp;page=$page&amp;count=$count";
	    $result['base_url'] = $baseUrl;
		$result['date1'] = $date1;
		$result['date2'] = $date2;
		$result['library_id'] = $library_id;
		$result['library_name'] = $searchLibName;
		$result['search_library_id'] = $library_id_post;
		$result['location_id'] = $location_id;
		$result['location_name'] = $searchLocName;
    $result['question_type_id'] = $question_type_id;
		$result['question_type'] = $searchQuestionType;
    $result['patron_type_id'] = $patron_type_id;
		$result['patron_type'] = $searchPatronType;
   	$result['question_format_id'] = $question_format_id;
		$result['question_format'] = $searchQuestionFormat;
		$result['criteria'] = $criteria;
		//$result['sql'] = $sql;
		return $result;
    }

    function isAuthenticationRequired() {
        return true;
    }
    

}

?>