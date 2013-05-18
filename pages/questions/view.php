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
        $question['creationdate'] = getPrettyDate($question['creationdate']);
        $question['lasteditdate'] = getPrettyDate($question['lasteditdate']);
    } catch(Exception $e) {
        $smarty->assign('warning_msg', "He need a valid question id to show you something useful");
        $smarty->display("showWarning.tpl");
        exit();
    }

   
    

    // get answers, votes, comments and dates to array
    $answers = getAnswersOfQuestion($id);
    $votes = array();
    $comments = array();

    $questionComments = getCommentsOfPost($id);
    foreach($questionComments as &$comment) {
        $comment['creationdate'] = getPrettyDate($comment['creationdate']);
    }

    $comments[] = $questionComments;

    // not working this
    $votes[] = json_decode(file_get_contents("{$BASE_URL}ajax/votes/voted_on_post.php?id=".$id), true);

    foreach($answers as &$answer) {
        $answerComments = getCommentsOfPost($answer['postid']);
        foreach($answerComments as &$comment) {
            $comment['creationdate'] = getPrettyDate($comment['creationdate']);
        }
        $comments[] = $answerComments;
        $answer['creationdate'] = getPrettyDate($answer['creationdate']);
        $answer['lasteditdate'] = getPrettyDate($answer['lasteditdate']);

        // not working this
        $votes[] = json_decode(file_get_contents("{$BASE_URL}ajax/votes/voted_on_post.php?id=".$answer['postid']), true);
    }

    // send data to smarty and display template
    $smarty->assign('question', $question);
    $smarty->assign('tags',getTagsOfQuestion($id));
    $smarty->assign('answers', $answers);
    $smarty->assign("comments", $comments);
    $smarty->assign("votes", $votes);

    $smarty->display("questions/view.tpl");
?>
