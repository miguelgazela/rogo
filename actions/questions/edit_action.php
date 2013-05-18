<?php
    // initialize
    include_once('../../common/init.php');
    include_once($BASE_PATH . 'database/questions.php');
    include_once($BASE_PATH . 'database/tags.php');

    if(isset($_SESSION['s_username'])) {

    	$errors = new DatabaseException();

    	if(!isset($_POST['question'])) {
            $errors->addError('question', 'no_questions');
        }
        if(!isset($_POST['details'])) {
            $errors->addError('details', 'no_details');
        }
        if(!isset($_POST['tags'])) {
            $errors->addError('tags', 'no_tags');
        }
        if(!isset($_SESSION['edit_questionid'])) {
        	$errors->addError("questionid", "no_question_id");
        }
    }

    returnIfHasErrors($errors, $_SERVER['HTTP_REFERER']);

    $title = $_POST['question'];
    $questionid = $_SESSION['edit_questionid'];
    $details = $_POST['details'];
    $tags = $_POST['tags'];

    if(!validateQuestionTitle($title)) {
        $errors->addError('question', 'invalid');
    }
    if(!validateQuestionDetails($details)) {
        $errors->addError('details', 'invalid');
    }
    if(!is_numeric($questionid)) {
    	$errors->addError("questionid", "invalid question id type");
    }

    returnIfHasErrors($errors, "pages/questions/edit.php?id=$questionid");

    try {
    	$question = getQuestionById($questionid);

    	if(!$question) {
    		$errors->addError("question", "no question with this id: $questionid");
    		returnIfHasErrors($errors, $_SERVER['HTTP_REFERER']);
    	}

    	$db->beginTransaction();
    	updateQuestion($questionid, $title, $details);

    	// update tags!
    	$tags = explode(",", $tags);
    	$current_tags = getTagsOfQuestion($questionid);

    	foreach($current_tags as $c_tag) {
    		$found = false;
    		foreach($tags as $tag) {
    			if($c_tag['tagname'] == $tag) {
    				$found = true;
    			}
    		}
    		if(!$found) {
    			removeTagFromQuestion($questionid, $c_tag['tagid']);
    		}
    	}

    	foreach($tags as $tag) {
    		$found = false;
    		foreach($current_tags as $c_tag) {
    			if($c_tag['tagname'] == $tag) {
    				$found = true;
    			}
    		}
    		if(!$found) {

    			$existsTag = getTagByName($tag);
    			if(!$existsTag) {
    				try {
                        $existsTag['tagid'] = insertTag($tag);
                    } catch(DatabaseException $e) {
                        $db->rollBack();
                        returnIfHasErrors($e, "pages/questions/edit.php?id=".$questionid);
                    }
    			}
    			try {
                    addTagToQuestion($questionid, $existsTag['tagid']);
                } catch(DatabaseException $e) {
                    $db->rollBack();
                    returnIfHasErrors($e, "pages/questions/edit.php?id=".$questionid);
                }
    		}
    	}


    	// redirects to question page
	    $db->commit();
	    header("Location: $BASE_URL"."pages/questions/view.php?id=".$questionid);
	    exit();  
    } catch(DatabaseException $e) {
    	$db->rollBack();
    	returnIfHasErrors($errors, $_SERVER['HTTP_REFERER']);
    }  
?>
