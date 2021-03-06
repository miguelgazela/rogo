<?php
    // initialize
    include_once('../../common/init.php');
    include_once($BASE_PATH . 'database/questions.php');
    include_once($BASE_PATH . 'database/tags.php');

    $id = $_GET['id'];

    // fetch data
    try {
    	$question = getQuestionById($id);
    	if(!$question) {
    		$smarty->assign("warning_msg", "We don't have any question with that id");
    		$smarty->display("showWarning.tpl");
    		exit();
    	}
    } catch(Exception $e) {
    	$smarty->assign('warning_msg', "He need a valid question id to show you something useful");
        $smarty->display("showWarning.tpl");
        exit();
    }

    $question['title'] = htmlspecialchars(stripslashes($question['title']));
    $question['body'] = htmlspecialchars(stripslashes($question['body']));

    // check if the user is the owner of the question
    if($question['ownerid'] != $_SESSION['s_user_id'] && $_SESSION['s_user_permission'] == 1 && $_SESSION['s_user_reputation'] < 1000) {
    	$smarty->assign('warning_msg', "That's not your question! Why are you trying to change it?");
        $smarty->display("showWarning.tpl");
        exit();
    }

    // send data to smarty and display template
    $smarty->assign('question', $question);
    $smarty->assign('tags', getTagsOfQuestion($id));
    $_SESSION['edit_questionid'] = $id;
    $smarty->display("questions/edit.tpl");
?>
