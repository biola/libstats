<?php
require_once 'Action.php';

class QuestionAddFormAction extends Action {

    function perform() {
    
        $times = Array();
        $start = 0;
        $end = 0;

        $result = array(
            'renderer' => 'template_renderer.inc',
            'pageTitle' => SITE_NAME.' : Add Question',
            'content' => 'content/questionAddForm.php');
            
		// The number of questions and page offset we want
		$count = grwd('count', 50);
		$page = grwd('page', 1);
		$result['count'] = $count;
		$result['page'] = $page;

        // Learn what kind of menus we need
        $layout = grwd('layout', 'menus');
        
        $optionFunction = array(
            'pulldown' => 'getSelectPulldown',
            'radio' => 'getRadioList',
            'menus' => 'getSelectBox');

        $result['optionFunction'] = $optionFunction[$layout];

        $db = $_REQUEST['db'];
        $tsf = new TimeSpentFinder($db);
        $ptf = new PatronTypeFinder($db);
        $qff = new QuestionFormatFinder($db);
        $qtf = new QuestionTypeFinder($db);
        $if = new InitialsFinder($db);
        $lf = new LocationFinder($db);
        
        $userFinder = new UserFinder($db);
        $start = mTimeFloat();
        $user = $userFinder->findById($_SESSION['userId']);
        $end = mTimeFloat();
        $times['userfind'] = $end - $start;
        

        $clientIp = getRemoteIp();

        $libId = $user['library_id'];
        $start = mTimeFloat();
        $result['timeSpentOpts'] = $tsf->findByLibrary($libId);
        $result['patronTypeOpts'] = $ptf->findByLibrary($libId);
        $result['questionTypeOpts'] = $qtf->findByLibrary($libId);
        $result['questionFormatOpts'] = $qff->findByLibrary($libId);
        $result['locationOpts'] = $lf->findByLibrary($libId);
        $times['menus'] = mTimeFloat() - $start;
        
        $start = mTimeFloat();
        // Load the default (last used) values for this client
        $result['locationId'] = $lf->getLastLocationId(
            $clientIp, $libId);
        if ($result['locationId'] == null) {
            $result['locationId'] = $result['locationOpts'][0]['location_id'];
        }
        $start = mTimeFloat();
        $result['lastInitials'] = $if->getLastInitials($clientIp);
        $times['lastInitials'] = mTimeFloat() - $start;
        $start = mTimeFloat();
        $result['lastTimeSpent'] = $tsf->getLast($clientIp);
        $times['lastTimeSpent'] = mTimeFloat() - $start;
        $start = mTimeFloat();
        $result['lastPatronType'] = $ptf->getLast($clientIp);
        $times['lastPatronType'] = mTimeFloat() - $start;
        $start = mTimeFloat();
        $result['lastQuestionType'] = $qtf->getLast($clientIp);
        $times['lastQuestionType'] = mTimeFloat() - $start;
        $start = mTimeFloat();
        $result['lastQuestionFormat'] = $qff->getLast($clientIp);
        $times['lastQuestionFormat'] = mTimeFloat() - $start;
        
        $result['user'] = $user;

        $questionFinder = new QuestionFinder($db);
        $start = mTimeFloat();
        $result['lastAdded'] = 
            $questionFinder->getLastQuestionTime($clientIp, $libId);
        if ($result['lastAdded'] != null) {
            $result['lastAdded'] = 
                date('n/d g:i A', strtotime($result['lastAdded']));
        }
        $times['lastAdded'] = mTimeFloat() - $start;
        
		
        $start = mTimeFloat();

        $qResult = $questionFinder->getPagedList(
            $count, $page, 'questions.library_id = ?', array((int)$libId));
        $result['questionList'] =& $qResult['list'];
        $result['list_meta'] = $qResult['meta'];
        $result['questionCount'] = $qResult['meta']['totalQuestions'];
        $result['nonemptyQuestionCount'] = 
            $qResult['meta']['nonemptyQuestions'];
            
        $times['getQuestions'] = mTimeFloat() - $start;
    
        $origin = "questionAddForm.do?page=" . 
            $result['page'] . "&amp;count=" . $result['count'];
        $result['origin'] = $origin;
        $result['target'] = 'questionAddForm.do';
            
        // Disabled debugging output
		// echo "<!-- ";
        // var_dump($times);
        // echo " -->";
        
		return $result;
   }

    function isAuthenticationRequired() {
        return true;
    }
}
?>
