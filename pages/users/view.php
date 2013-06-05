<?php
    // initialize
    include_once('../../common/init.php');

    include_once($BASE_PATH . 'database/users.php');
    include_once($BASE_PATH . 'database/questions.php');
    include_once($BASE_PATH . 'database/tags.php');
    include_once($BASE_PATH . 'database/answers.php');
    
    if(isset($_SESSION['s_username'])) {

		if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
			$smarty->assign('warning_msg', "He need a valid user id to show you something useful");
	        $smarty->display("showWarning.tpl");
	        exit;
		}

	    // fetch data
	    try {
	    	$id = $_GET['id'];
	        $user = getUserById($id);

	        if(!$user) {
	            $smarty->assign('warning_msg', "We don't have any user with that id");
	            $smarty->display("showWarning.tpl");
	            exit;
	        }

	        if(!isset($_GET['sort']) || !validSorting($_GET['sort'])) {
	        	$_GET['sort'] = "questions";
	    	}
	    	if(!isset($_GET['page']) || !is_numeric($_GET['page'])) {
	        	$_GET['page'] = "1";
	    	}

	    	$pageNumber = intval($_GET['page']);
	    	$sort = $_GET['sort'];
	    	
	    	if($sort == "questions") { // get questions of user
	    		$questions = getQuestionsOfUser($id, 5*$pageNumber);
	    		$counter = getNumberOfQuestionsOfUser($id);
	    		$tags = array();

	    		foreach($questions as &$question) {
	    			$tags[] = getTagsOfQuestion($question['questionid']);
	    			$question['creationdate_p'] = getPrettyDate($question['creationdate']);
	    			$question['title'] = htmlspecialchars(stripslashes($question['title']));
        			$question['body'] = getSmallerText(htmlspecialchars(stripslashes($question['body'])), 330);
	    		}

	    		$smarty->assign("total_number_questions", $counter['total']);
	    		$smarty->assign("number_presented_questions", count($questions));
	    		$smarty->assign("tags", $tags);
	    		$smarty->assign("questions", $questions);
	    	} 
	    	else if ($sort == "answers") {
    			$answers = getAnswersOfUser($id, 5*$pageNumber);
    			$counter = getNumberOfAnswersOfUser($id);

    			foreach($answers as &$answer) {
    				$answer['body'] = getSmallerText(htmlspecialchars(stripslashes($answer['body'])), 330);
    				$answer['creationdate_p'] = getPrettyDate($answer['creationdate']);
    			}

    			$smarty->assign("total_number_answers", $counter['total']);
    			$smarty->assign("number_presented_answers", count($answers));
    			$smarty->assign("answers", $answers);
	    	} else if ($sort == "drafts") {
	    		if($_SESSION['s_user_id'] != $id) { 
	        		header("Location: ".$BASE_URL."pages/users/view.php?id=$id");
	        		exit;
	    		}
	    		$drafts = getDraftsOfUser($id, 5*$pageNumber);
    			$counter = getNumberOfDraftsOfUser($id);

    			foreach($drafts as &$draft) {
    				$draft['body'] = getSmallerText(htmlspecialchars(stripslashes($draft['body'])), 330);
    				$draft['lastactivitydate_p'] = getPrettyDate($draft['lastactivitydate']);
    			}

    			$smarty->assign("total_number_drafts", $counter['total']);
    			$smarty->assign("number_presented_drafts", count($drafts));
    			$smarty->assign("drafts", $drafts);
	    	}

	        // only increments when other users see the profile
	        if($_SESSION['s_user_id'] != $id) { 
	        	incProfileViews($id);
	    	}

	        $user['registrationdate_p'] = getPrettyDate($user['registrationdate']);
	        $user['lastaccess_p'] = getPrettyDate($user['lastaccess']);
	        $user['gravatar'] = "http://www.gravatar.com/avatar/".md5(strtolower(trim($user['email'])))."?s=90&r=pg&d=identicon";

	        // send data to smarty and display template
	        $smarty->assign("user", $user);
	        $smarty->assign("sort_method", $sort);
	        $smarty->assign("page", $pageNumber);

	        $smarty->display("users/view.tpl");
	        exit;
	        
	    } catch(Exception $e) {
	        $smarty->assign('warning_msg', "Something wrong happened with our back-end. Please try again later");
	        $smarty->display("showWarning.tpl");
	        exit;
	    }
    } else {
    	$smarty->assign('warning_msg', "You have to <a href='{$BASE_URL}pages/auth/signin.php'>log in</a> to get access");
        $smarty->display('showWarning.tpl');
    }

    function validSorting($sort) {
    	return ($sort == "questions" || $sort == "answers" || $sort == "drafts");
    }
?>
