<?php
    // initialize
    include_once('../../common/init.php');
    include_once($BASE_PATH . 'database/answers.php');

    if(isset($_SESSION['s_username'])) {

    	$errors = new DatabaseException();
    	if(!isset($_POST['answer'])) {
            $errors->addError('answer', 'no_answer');
        }
    }

    returnIfHasErrors($errors, $_SERVER['HTTP_REFERER']);

    $answerText = $_POST['answer'];
    $answerid = $_SESSION['edit_answerid'];

    if(!validateAnswerText($answerText)) {
        $errors->addError('answer text', 'invalid');
    }
    if(!is_numeric($answerid)) {
    	$errors->addError("answerid", "invalid answer id type");
    }

    returnIfHasErrors($errors, "pages/answers/edit.php?id=$answerid");

    try {
    	$answer = getAnswerById($answerid);

    	if(!$answer) {
    		$errors->addError("answer", "no answer with this id: $answerid");
    		returnIfHasErrors($errors, $_SERVER['HTTP_REFERER']);
    	}

    	updateAnswer($answerid, $answerText);

    	// redirects to question page
	    header("Location: $BASE_URL"."pages/questions/view.php?id=".$answer['questionid']);
	    exit();  
    } catch(DatabaseException $e) {
    	returnIfHasErrors($errors, $_SERVER['HTTP_REFERER']);
    }  
?>
