<?php
require_once 'Action.php';
/**
 * An Action to add a question to the Library Stats. Does all the 
 * required checks and then adds the beast.
 */
class QuestionAddAction extends Action {

    function perform() {
        // Get the resources we need to do this update
        $db = $_REQUEST['db'];
        $userFinder = new UserFinder($db);
        $user = $userFinder->findById($_SESSION['userId']);
        $questionFinder = new QuestionFinder($db);

        // Grok all the relevant data from the form
        $qHash = array();
        $qHash['library_id'] = $user['library_id'];
        $qHash['location_id'] = gpwd('location', null);
        $qHash['question_type_id'] = gpwd('questionType', null);
        $qHash['question_type_other'] = gpwd('questionTypeOther');
        $qHash['time_spent_id'] = gpwd('timeSpent', null);
        $qHash['patron_type_id'] = gpwd('patronType', null);
        $qHash['question_format_id'] = gpwd('questionFormat', null);
        $qHash['initials'] = gpwd('initials');
        $qHash['client_ip'] = getRemoteIp();
        $qHash['user_id'] = $_SESSION['userId'];
        $qHash['question'] = gpwd('question');
		$qHash['answer'] = gpwd('answer');
        $qHash['question'] = trim($qHash['question']);
		$qHash['answer'] = trim($qHash['answer']);
        $qHash['hide'] =  0;
        if ($qHash['question'] == '' && $qHash['answer'] == '') { $qHash['hide'] = 1; }

        // Do the date
        $qHash['question_date'] = trim(gpwd('mydate', 'now'));
        if ($qHash['question_date'] == '') {
            $qHash['question_date'] = 'now';
        }
        $stamp = strtotime($qHash['question_date']);
        if ($stamp != -1) {
            $qHash['question_date'] = date('Y-m-d H:i:s', $stamp);
        }
        else {
            $qHash['question_date'] = null;
        }

        $qHash['date_added'] = date('Y-m-d H:i:s');

        // Clean up qHash; make numbers really numeric. The dirty little
        // trick: add 0 to non-null values names .*_id
        foreach ($qHash as $key=>$val) {
            if (strpos($key, '_id')) {
                if ($val != null) { 
                    $qHash[$key] = $val + 0; 
                }
            }
        }
        
        $target = "questionAddForm.do";
        $res = $questionFinder->addQuestion($qHash);
        if (!DB::isError($res)) {
            // Use a Location: header to fly back; we don't want to 
            // be able to double-enter by mistake.... I think.
            $url = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
			$url = substr($url, 0, -strrchr($url, '/')). $target;
            header("Location: $url");
            exit;
        }
        else {
            // A page error occurred!
            $_REQUEST['dbResult'] = $res;
            $act = new PageErrorAction();
            return $act->perform();
        }
    }

    function isAuthenticationRequired() {
        return true;
    }
}


?>
