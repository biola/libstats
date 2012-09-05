<?php
require_once 'Action.php';

class QuestionEditFormAction extends Action {
    // This class is rather abstract, and its methods
    // are all do-nothing methods.
    // Then again, subclasses aren't that much better...

    function perform() {
	
        $layout = grwd('layout', 'menus');

        $optionFunctions = array(
            'pulldown' => 'getSelectPulldown',
            'radio' => 'getRadioList',
            'menus' => 'getSelectBox');
        $optionFunction = $optionFunctions[$layout];
        

        // Ensure we have the variable questionId
        $questionId = grwd('questionId', -1) + 0;
        $db = $_REQUEST['db'];
        $tsf = new TimeSpentFinder($db);
        $ptf = new PatronTypeFinder($db);
        $qff = new QuestionFormatFinder($db);
        $qtf = new QuestionTypeFinder($db);
        $if = new InitialsFinder($db);
        $lf = new LocationFinder($db);
        
        $userFinder = new UserFinder($db);
        $user = $userFinder->findById($_SESSION['userId']);

        if ($questionId != -1) {
            // to the edit form!
            $result = array(
                'renderer' => 'template_renderer.inc',
                'pageTitle' => SITE_NAME .' : Edit Question',
                'content' => 'content/questionEditForm.php');

            $result['optionFunction'] = $optionFunction;
            $libId = $user['library_id'];
            $result['timeSpentOpts'] = $tsf->findByLibrary($libId);
            $result['patronTypeOpts'] = $ptf->findByLibrary($libId);
            $result['questionTypeOpts'] = $qtf->findByLibrary($libId);
            $result['questionFormatOpts'] = $qff->findByLibrary($libId);
            $result['locationOpts'] = $lf->findByLibrary($libId);
            $result['locationId'] = $lf->getLastLocationId(
                $_SERVER['REMOTE_ADDR'], $libId);
            $result['user'] = $user;

            $questionFinder = new QuestionFinder($db);
            $result['question'] = $questionFinder->getQuestion($questionId);
			$result['answer'] = $questionFinder->getQuestion($questionId);
			$result['delete_hide'] = gpwd ('delete_hide');
			$result['origin'] = grwd('origin', 'questionAddForm.do');
        }
        else {
			echo "QuestionID $questionId not found";
            // Send somewere else
        }


        return $result;
    }

    function isAuthenticationRequired() {
        return true;
    }
}
?>
