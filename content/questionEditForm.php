<?php include('mainHeader.php'); ?>
<?php $optionFunction = $rInfo['optionFunction']; ?>

<div>
<form name = "questionForm" method = "post" action = "editQuestion.do" 
    id = "qForm">
		
<div id = "toolbar">
    <input type = "hidden" name = "libraryId" 
        value = "<?php echo($rInfo['question']['library_id']); ?>" />
    <input type = "hidden" name = "questionId" 
        value = "<?php echo($rInfo['question']['question_id']); ?>" />
    <input type = "hidden" name = "origin"
        value = "<?php echo($rInfo['origin']); ?>" />
    <div class = "inputBox">
        <h5>Location</h5>
        <?php echo( $optionFunction(
            'location',
            $rInfo['locationOpts'],
            'location_id',
            'location_name',
            $rInfo['question']['location_id'], 5)
            ); ?>
    </div>
    <div class = "inputBox">
        <h5>Patron Type</h5>
        <?php echo( $optionFunction(
            'patronType',
            $rInfo['patronTypeOpts'],
            'patron_type_id',
            'patron_type',
            $rInfo['question']['patron_type_id'], 5)
            ); ?>
    </div>
    <div class = "inputBox">
        <h5>Question Type</h5>
        <?php echo( $optionFunction(
            'questionType',
            $rInfo['questionTypeOpts'],
            'question_type_id',
            'question_type',
            $rInfo['question']['question_type_id'], 5)
            ); ?>
    </div>
    <div class = "inputBox">
        <h5>Time Spent</h5>
        <?php echo( $optionFunction(
            'timeSpent',
            $rInfo['timeSpentOpts'],
            'time_spent_id',
            'time_spent',
            $rInfo['question']['time_spent_id'], 5)
            ); ?>
    </div>
    <div class = "inputBox">
        <h5>Question Format</h5>
         <?php echo( $optionFunction(
            'questionFormat',
            $rInfo['questionFormatOpts'],
            'question_format_id',
            'question_format',
            $rInfo['question']['question_format_id'], 5)
            ); ?>
    </div>
    <div class = "inputBox">
        <h5>Initials</h5>
        <input type = "text" size = "5" name = "initials" id = "initials" 
            value = "<?php echo($rInfo['question']['initials'] ); ?>" />
    </div>
    <div class = "inputBox">
        <h5>Date</h5>
        <input type = "text" size = "13" name = "questionDate" 
            id = "questionDate"
            class = "validDate" 
            value = "<?php echo(date("n/d/y g:i A", 
                strtotime($rInfo['question']['question_date']))); ?>" />
    </div>
</div>
<div id = "qBox">
    <h5>Question</h5>
    <textarea name = "question" id = "question" 
    rows = "4"><?php echo($rInfo['question']['question']); ?></textarea>
</div>
<div id = "qAnswer">
	<h5>Answer</h5>
	<textarea name = "answer" id = "answer"
	rows = "4"><?php echo($rInfo['answer']['answer']); ?></textarea>
</div>
<div style = "clear:both;">
<input type = "submit" name = "saveButton" value = "Save Question / Answer" />
<input type = "submit" name = "deleteButton" value = "Delete" />
</div>
</form>
</div>

<?php include 'footer.php'; ?>