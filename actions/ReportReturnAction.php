<?php
require_once 'Action.php';

class ReportReturnAction extends Action {
    // Not sure how this is going to work here--EL
	
    function perform() {
    
	// set display requirements
	  	$result = array(
      	'renderer' => 'template_renderer.inc',
        'pageTitle' => SITE_NAME .' : Finished Report',
        'content' => 'content/reportReturn.php');
	
	// don't lose the db!	
		$db = $_REQUEST['db'];
	
	// where are we?	
		$userFinder = new UserFinder($db);
        $user = $userFinder->findById($_SESSION['userId']);
		$result['user'] = $user;
	
	// gather posted data
		$date1 = trim(grwd('date1'));
		$date2 = trim(grwd('date2'));
		if ($date1 == '') { $date1 = '1/1/1990'; }
        if ($date2 == '') { $date2 = 'now'; }
		$report_id = grwd('report_id');
		$library_id_post = grwd('library_id') + 0;
		$location_id_post = grwd('location_id') + 0;
    $question_type_id_post = grwd('question_type_id') + 0;
		$patron_type_id_post = grwd('patron_type_id') + 0;
    $question_format_id_post = grwd('question_format_id') + 0;
				
	// function to sanity check dates	
		$date1 = makeDateSane($date1);
		$date1 = (date('Y-m-d G:i:s',strtotime($date1)));
		$date2 = makeDateSane($date2);
		$date2 = (date('Y-m-d G:i:s',strtotime($date2)));
		
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
		
		// pull together all of the search criteria	
		$criteria = array(
			'start_date' => $startDate,
			'end_date' => $endDate,
			'library_id' => $library_id,
			'location_id' => $location_id,
      'question_type_id' => $question_type_id,
			'patron_type_id' => $patron_type_id,
      'question_format_id' => $question_format_id);
		
		$sql = " WHERE questions.delete_hide = 0 ";
		$i = 0;
		$param = array();
		foreach ($criteria as $value){
			if(!$value["value"]){ continue;}
			
            $sql .= ('AND' . ' ' . $value["database_field"] . ' ' . $value["relation"] . ' ? ');
            $param[$i] = $value["value"];
			$i++;
		}

		// get the relevant data from the Report class
		$reportFinder = new ReportFinder($db);
		$reportCount = $reportFinder->getReportCount();
		$reportQuestionCount = $reportFinder->getReportQuestionCount($sql, $param);
		
    // call the specific class of the report
    $report_class_handle = new Report();
    $report_class_get = $report_class_handle->get();
    $report_info = new $report_id(); 	// declare the report class by using it's ID
    $result['reportList'] = $report_info->info();

		// start preparing the report for processing
		$reportPerform = new $_REQUEST["report_id"]($db);
		$reportResults = $reportPerform->perform($sql, $param);

		$libraryFinder = new LibraryFinder($db);
		$reportLibName = $libraryFinder->getLibraryName($library_id_post);
		if(isset($location_id_post)){
			$locationFinder = new LocationFinder($db);
			$reportLocName = $locationFinder->getLocation($location_id_post);
		}
    if(isset($question_type_id_post)){
			$questiontypeFinder = new QuestionTypeFinder($db);
			$reportQuTypeName = $questiontypeFinder->getQuestionType($question_type_id_post);
		}
		if(isset($patron_type_id_post)){
			$patrontypeFinder = new PatronTypeFinder($db);
			$reportPatronName = $patrontypeFinder->getPatronType($patron_type_id_post);
    }
    if(isset($question_format_id_post)){
			$questionformatFinder = new QuestionFormatFinder($db);
			$reportQuestionFormat = $questionformatFinder->getQuestionFormat($question_format_id_post);
		}
		// prepare $results

		// since a CSV report is handled differently with the headers, configure the report here
		if (($report_id == "DataCSVReport") || (isset($_REQUEST["csv_export"]))) {
			$result['renderer'] = 'template_csv.inc';
			$result['content'] = 'content/outputCSV.php';		
		}
		$result['report_id'] = $report_id;
		$result['date1'] = $date1;
		$result['date2'] = $date2;
		$result['library_id'] = $library_id;
		$result['library_id_post'] = $library_id_post;
		$result['library_name'] = $reportLibName;
		$result['location_id'] = $location_id;
		$result['location_id_post'] = $location_id_post;
		$result['location_name'] = $reportLocName;
    $result['question_type_id'] = $question_type_id;
		$result['question_type_id_post'] = $question_type_id_post;
		$result['question_type'] = $reportQuTypeName;
		$result['patron_type_id'] = $patron_type_id;
		$result['patron_type_id_post'] = $patron_type_id_post;
		$result['patron_type'] = $reportPatronName;
    $result['question_format_id'] = $question_format_id;
		$result['question_format_id_post'] = $question_format_id_post;
		$result['question_format'] = $reportQuestionFormat;
		$result['reportCount'] = $reportCount;
		$result['reportQuestionCount'] = $reportQuestionCount;   
		$result['reportResults'] = $reportResults;
		$result['criteria'] = $criteria;
		$result['sql'] = $sql;
				
		return $result;
    }

    function isAuthenticationRequired() {
        return true;
    }
}

?>
