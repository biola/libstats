<?php
require_once 'Action.php';
/**
 * An Action to add a question to the Library Stats. Does all the 
 * required checks and then adds the beast.
 */
 

class QuestionEditAction extends Action {

    function perform() {
        // Get the resources we need to do this update
        $db = $_REQUEST['db'];
        $userFinder = new UserFinder($db);
        $questionFinder = new QuestionFinder($db);
        $questionId = gpwd('questionId', 0) + 0;
		
		//Test for DELETE
		$delete = gpwd('deleteButton', '');
		$save = gpwd('saveButton', '');
		if ($save == "Save Question / Answer"){
			$delete = 0;
		} 
		else if ($delete == "Delete") {
			$delete = 1;
		}
		

        // Grok all the relevant data from the form
        $qHash = array();
        $qHash['location_id'] = gpwd('location', null);
        $qHash['question_type_id'] = gpwd('questionType', null);
        $qHash['question_type_other'] = gpwd('questionTypeOther');
        $qHash['time_spent_id'] = gpwd('timeSpent', null);
        $qHash['patron_type_id'] = gpwd('patronType', null);
        $qHash['question_format_id'] = gpwd('questionFormat', null);
        $qHash['initials'] = gpwd('initials');
        $qHash['question'] = gpwd('question');
		$qHash['answer'] = gpwd('answer');
        $qHash['question'] = trim($qHash['question']);
		$qHash['answer'] = trim($qHash['answer']);
        $qHash['hide'] =  0;
		$qHash['delete_hide'] = $delete;
        if ($qHash['question'] == '' && $qHash['answer'] == '') { $qHash['hide'] = 1; }
		
        // Do the date -- if we can't parse it, don't change it!
        $qTime = trim(gpwd('questionDate', ''));
        if ($qTime != '') {
            $stamp = strtotime($qTime);
            if ($stamp != -1) {
                $qHash['question_date'] = date('Y-m-d H:i:s', $stamp);
            }
        }

        // Clean up qHash; make numbers really numeric. The dirty little
        // trick: add 0 to non-null values names .*_id
        foreach ($qHash as $key=>$val) {
            if (strpos($key, '_id')) {
                if ($val != null) { 
                    $qHash[$key] = $val + 0; 
                }
            }
        }

        $res = $questionFinder->editQuestion($questionId, $qHash);
        if (!DB::isError($res)) {
            // Use a Location: header to fly back, to avoid people refreshing
            // and posting twice -- a common problem.
            $url = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
            $url = substr($url, 0, -strrchr($url, '/')) . $_REQUEST['origin'];
			
            header("Location: $url");
            exit;
        }
        else {
            echo "<pre>";
            var_dump($res);
            echo "</pre>";
        }
            
    }

    function isAuthenticationRequired() {
        return true;
    }
}
?>
