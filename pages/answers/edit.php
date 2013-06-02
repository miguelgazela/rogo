<?php
    // initialize
    include_once('../../common/init.php');
    include_once($BASE_PATH . 'database/answers.php');

    $id = $_GET['id'];

    // fetch data
    try {
    	$answer = getAnswerById($id);
    	if(!$answer) {
    		$smarty->assign("warning_msg", "We don't have any answer with that id");
    		$smarty->display("showWarning.tpl");
    		exit();
    	}
    } catch(Exception $e) {
    	$smarty->assign('warning_msg', "He need a valid answer id to show you something useful");
        $smarty->display("showWarning.tpl");
        exit();
    }

    // check if the user is the owner of the answer
    if($answer['ownerid'] != $_SESSION['s_user_id'] && $_SESSION['s_user_permission'] == 1 && $_SESSION['s_user_reputation'] < 1000) {
    	$smarty->assign('warning_msg', "That's not your answer! Why are you trying to change it?");
        $smarty->display("showWarning.tpl");
        exit;
    }

    // send data to smarty and display template
    $answer['body'] = htmlspecialchars(stripslashes($answer['body']));
    $smarty->assign('answer', $answer);
    $_SESSION['edit_answerid'] = $id;
    $smarty->display("answers/edit.tpl");
?>
