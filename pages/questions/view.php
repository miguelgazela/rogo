<?php
    // initialize
    include_once('../../common/init.php');

    // include needed database functions
    include_once($BASE_PATH . 'database/questions.php');    
    include_once($BASE_PATH . 'database/answers.php');    
    include_once($BASE_PATH . 'database/comments.php');
    include_once($BASE_PATH . 'database/tags.php');

    $id = $_GET['id'];

    // fetch data
    try {
        $question = getQuestionById($id);
        if(!$question) {
            $smarty->assign('warning_msg', "We don't have any question with that id");
            $smarty->display("showWarning.tpl");
            exit();
        }
        incQuestionViews($id);
    } catch(Exception $e) {
        $smarty->assign('warning_msg', "He need a valid question id to show you something useful");
        $smarty->display("showWarning.tpl");
        exit();
    }

    // send data to smarty and display template
    $smarty->assign('question', $question);
    $smarty->assign('tags',getTagsOfQuestion($id));
    $answers = getAnswersOfQuestion($id);
    $smarty->assign('answers', $answers);

    // get votes and comments to array
    $votes = array();
    $comments = array();
    $votes[] = json_decode(file_get_contents("{$BASE_URL}ajax/votes/voted_on_post.php?id=".$id), true);
    $comments[] = getCommentsOfPost($id);

    foreach($answers as $answer) {
        $comments[] = getCommentsOfPost($answer['postid']);
        $votes[] = json_decode(file_get_contents("{$BASE_URL}ajax/votes/voted_on_post.php?id=".$answer['postid']), true);
    }
    $smarty->assign("comments", $comments);
    $smarty->assign("votes", $votes);

    $smarty->display("questions/view.tpl");
?>
